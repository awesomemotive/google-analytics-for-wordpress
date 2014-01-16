<?php
/*
Plugin Name: Google Analytics for WordPress
Plugin URI: http://yoast.com/wordpress/google-analytics/#utm_source=wordpress&utm_medium=plugin&utm_campaign=wpgaplugin&utm_content=v420
Description: This plugin makes it simple to add Google Analytics to your WordPress blog, adding lots of features, eg. custom variables and automatic clickout and download tracking.
Author: Joost de Valk
Version: 4.3.3
Requires at least: 3.0
Author URI: http://yoast.com/
License: GPL v3

Google Analytics for WordPress
Copyright (C) 2008-2013, Joost de Valk - joost@yoast.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// This plugin was originally based on Rich Boakes' Analytics plugin: http://boakes.org/analytics, but has since been rewritten and refactored multiple times.

/**
 * @author		Joost de Valk <joost@yoast.com>
 * @copyright	Copyright (c) 2008-2013, Joost de Valk
 * @license		https://www.gnu.org/licenses/gpl.html GPLv3
 * @package		Yoast\Google_Analytics
 * @version		4.3.3
 */

/**
 * Main class to start the plugin
 * 
 * @since 4.3.3
 */
class Yoast_Google_Analytics {
	
	/**
	 * Current version of the plugin.
	 *
	 * @since	4.3.3
	 * @access	public
	 * @static
	 * @var		string	$version
	 */
	public static $version = '4.3.3';
	
	/**
	 * Holds a copy of the main plugin filepath.
	 * 
	 * @since	4.3.3
	 * @access	private
	 * @var		string	$file
	 */
	private static $file = __FILE__;
	
	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 * 
	 * @since	4.3.3
	 * @access	public
	 * 
	 * @return	void
	 */
	public function __construct() {
		
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			require_once plugin_dir_path( self::$file ) . 'admin/ajax.php';

		} else if ( defined('DOING_CRON') && DOING_CRON ) {

			$options = get_option( 'Yoast_Google_Analytics' );
			if ( isset( $options['yoast_tracking'] ) && $options['yoast_tracking'] )
				require_once 'inc/class-tracking.php';

		} else {
			
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

			require_once plugin_dir_path( self::$file ) . 'inc/functions.php';

			if ( is_admin() ) {
				require_once plugin_dir_path( self::$file ) . 'admin/class-admin.php';
			} else {
				require_once plugin_dir_path( self::$file ) . 'frontend/class-frontend.php';
			}
		}
		
		register_activation_hook( __FILE__, array( 'Yoast_Google_Analytics', 'activate_plugin' ) );
		
	} // END __construct()
	
	/**
	 * Return defaults for activation and resets
	 * 
	 * @since	4.3.3
	 * @access	public
	 * @static
	 * 
	 * @return	array
	 */
	public static function get_defaults() {
		
		$defaults = array(
			'advancedsettings'   => false,
			'allowanchor'        => false,
			'allowhash'          => false,
			'allowlinker'        => false,
			'anonymizeip'        => false,
			'customcode'         => '',
			'cv_loggedin'        => false,
			'cv_authorname'      => false,
			'cv_category'        => false,
			'cv_all_categories'  => false,
			'cv_tags'            => false,
			'cv_year'            => false,
			'cv_post_type'       => false,
			'debug'              => false,
			'dlextensions'       => 'doc,exe,js,pdf,ppt,tgz,zip,xls',
			'domain'             => '',
			'domainorurl'        => 'domain',
			'extrase'            => false,
			'extraseurl'         => '',
			'firebuglite'        => false,
			'ga_token'           => '',
			'ga_api_responses'   => array(),
			'gajslocalhosting'   => false,
			'gajsurl'            => '',
			'ignore_userlevel'   => '11',
			'internallink'       => false,
			'internallinklabel'  => '',
			'outboundpageview'   => false,
			'downloadspageview'  => false,
			'othercrossdomains'  => '',
			'position'           => 'footer',
			'primarycrossdomain' => '',
			'theme_updated'      => false,
			'trackcommentform'   => true,
			'trackcrossdomain'   => false,
			'trackadsense'       => false,
			'trackoutbound'      => true,
			'trackregistration'  => false,
			'rsslinktagging'     => true,
			'uastring'           => '',
		);
		
		$options = apply_filters( 'yoast-ga-default-options', $defaults );
		
		$options[] = self::$version;
		
		return $options;
	}
	
	/**
	 * Getter method for retrieving the main plugin filepath.
	 * 
	 * @since	4.3.3
	 * @static
	 * @access	public
	 * 
	 * @return	string	self::$file
	 */
	public static function get_file() {

		return self::$file;

	} // END get_file()
	
	/**
	 * Load the plugin's textdomain hooked to 'plugins_loaded'.
	 * 
	 * @since	4.3.3
	 * @access	public
	 * 
	 * @return	void
	 */
	public function load_plugin_textdomain() {
		
		load_plugin_textdomain(
			'gawp',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);
		
	} // END load_plugin_textdomain()
	
	/**
	 * Fired when plugin is activated
	 * 
	 * @since	4.3.3
	 * @access	public
	 * 
	 * @param	bool	$network_wide TRUE if WPMU 'super admin' uses Network Activate option
	 * @return	void
	 */
	public function activate_plugin( $network_wide ) {
		
		$defaults = self::get_defaults();
		
		if ( is_multisite() && ( true == $network_wide ) ) {
			
			global $wpdb;
			$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );

			if ( $blogs ) {
				foreach( $blogs as $blog ) {
					switch_to_blog( $blog['blog_id'] );
					add_option( 'Yoast_Google_Analytics', $defaults );
				}
				restore_current_blog();
			}
			
		} else {

			add_option( 'Yoast_Google_Analytics', $defaults );
			
		}
		
	} // END activate_plugin()
	
} // END class Yoast_Google_Analytics

/**
 * Instantiate the main class
 * 
 * @since	4.3.3
 * @access	public
 * 
 * @var	object	$yoast_google_analytics holds the instantiated class {@uses Yoast_Google_Analytics}
 */
$yoast_google_analytics = new Yoast_Google_Analytics();
