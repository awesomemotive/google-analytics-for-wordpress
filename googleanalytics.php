<?php
/**
 * Plugin Name:         Google Analytics for WordPress by MonsterInsights
 * Plugin URI:          https://www.monsterinsights.com/?utm_source=liteplugin&utm_medium=pluginheader&utm_campaign=pluginurl&utm_content=7%2E0%2E0
 * Description:         The best Google Analytics plugin for WordPress. See how visitors find and use your website, so you can keep them coming back.
 * Author:              MonsterInsights
 * Author URI:          https://www.monsterinsights.com/?utm_source=liteplugin&utm_medium=pluginheader&utm_campaign=authoruri&utm_content=7%2E0%2E0
 *
 * Version:             7.3.0
 * Requires at least:   3.8.0
 * Tested up to:        4.9
 *
 * License:             GPL v3
 * 
 * Text Domain:         google-analytics-for-wordpress
 * Domain Path:         /languages
 *
 * MonsterInsights Lite
 * Copyright (C) 2008-2018, MonsterInsights, support@monsterinsights.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category            Plugin
 * @copyright           Copyright © 2018 Chris Christoff
 * @author              Chris Christoff
 * @package             MonsterInsights
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @author  Chris Christoff
 * @access public
 */
final class MonsterInsights_Lite {

	/**
	 * Holds the class object.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var object Instance of instantiated MonsterInsights class.
	 */
	public static $instance;

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var string $version Plugin version.
	 */
	public $version = '7.3.0';

	/**
	 * Plugin file.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var string $file PHP File constant for main file.
	 */
	public $file;

	/**
	 * The name of the plugin.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var string $plugin_name Plugin name.
	 */
	public $plugin_name = 'MonsterInsights Lite';

	/**
	 * Unique plugin slug identifier.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var string $plugin_slug Plugin slug.
	 */
	public $plugin_slug = 'monsterinsights-lite';

	/**
	 * Holds instance of MonsterInsights License class.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var MonsterInsights_License $license Instance of License class.
	 */
	protected $license;

	/**
	 * Holds instance of MonsterInsights License Actions class.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var MonsterInsights_License_Actions $license_actions Instance of License Actions class.
	 */
	public $license_actions;

	/**
	 * Holds instance of MonsterInsights Admin Notice class.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var MonsterInsights_Admin_Notice $notices Instance of Admin Notice class.
	 */
	public $notices;

	/**
	 * Holds instance of MonsterInsights Reporting class.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var MonsterInsights_Reporting $reporting Instance of Reporting class.
	 */
	public $reporting;
	
	/**
	 * Holds instance of MonsterInsights Auth class.
	 *
	 * @since 7.0.0
	 * @access public
	 * @var MonsterInsights_Auth $auth Instance of Auth class.
	 */
	protected $auth;

	/**
	 * Holds instance of MonsterInsights API Auth class.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var MonsterInsights_Auth $api_auth Instance of APIAuth class.
	 */
	public $api_auth;

	/**
	 * Primary class constructor.
	 *
	 * @since 6.0.0
	 * @access public
	 */
	public function __construct() {
		// We don't use this
	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @access public
	 * @since 6.0.0
	 *
	 * @return object The MonsterInsights_Lite object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof MonsterInsights_Lite ) ) {
			self::$instance = new MonsterInsights_Lite();
			self::$instance->file = __FILE__;

			global $wp_version;

			// Detect non-supported WordPress version and return early
			if ( version_compare( $wp_version, '3.8', '<' ) && ( ! defined( 'MONSTERINSIGHTS_FORCE_ACTIVATION' ) || ! MONSTERINSIGHTS_FORCE_ACTIVATION ) ) {
				add_action( 'admin_notices', array( self::$instance, 'monsterinsights_wp_notice' ) );
				return;
			}
	
			// Detect Pro version and return early
			if ( class_exists( 'MonsterInsights' ) && defined( 'GAWP_VERSION' ) ) {
				add_action( 'admin_notices', array( self::$instance, 'monsterinsights_pro_notice' ) );
				return;
			}

			if ( defined( 'GAWP_ECOMMERCE_PATH' ) ) {
				add_action( 'admin_notices', array( self::$instance, 'monsterinsights_old_ecommerce' ) );
			}
			
			// Define constants
			self::$instance->define_globals();

			// Load in settings
			self::$instance->load_settings();

			// Load in Licensing
			self::$instance->load_licensing();

			// Load in Auth
			self::$instance->load_auth();

			// Load files
			self::$instance->require_files();

			// This does the version to version background upgrade routines and initial install
			$mi_version = get_option( 'monsterinsights_current_version', '5.5.3' );
			if ( version_compare( $mi_version, '7.0.0', '<' ) ) {
				monsterinsights_lite_call_install_and_upgrade();
			}

			if ( is_admin() ) {
				new AM_Notification( 'mi-lite', self::$instance->version );
				new AM_Deactivation_Survey( 'MonsterInsights', basename( __DIR__ ) );
			}

			// Load the plugin textdomain.
			add_action( 'plugins_loaded', array( self::$instance, 'load_plugin_textdomain' ) );

			// Load admin only components.
			if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
				self::$instance->notices          = new MonsterInsights_Notice_Admin();
				self::$instance->license_actions  = new MonsterInsights_License_Actions();
				self::$instance->reporting 	      = new MonsterInsights_Reporting();
				self::$instance->api_auth    	  = new MonsterInsights_API_Auth();
				if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
					self::$instance->require_updater();
				} else {
					add_action( 'admin_init', array( self::$instance, 'require_updater' ) );
				}
			}

			if ( monsterinsights_is_pro_version() ) {
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'pro/includes/load.php';
			} else {
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/load.php';
			}

			// Run hook to load MonsterInsights addons.
			do_action( 'monsterinsights_load_plugins' ); // the updater class for each addon needs to be instantiated via `monsterinsights_updater`
		}

		return self::$instance;

	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'google-analytics-for-wordpress' ), '6.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * Attempting to wakeup an MonsterInsights instance will throw a doing it wrong notice.
	 * 
	 * @since 6.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'google-analytics-for-wordpress' ), '6.0.0' );
	}

	/**
	 * Magic get function.
	 *
	 * We use this to lazy load certain functionality. Right now used to lazyload
	 * the API & Auth frontend, so it's only loaded if user is using a plugin
	 * that requires it.
	 *
	 * @since 7.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __get( $key ) {
		if ( $key === 'auth' ) {
			if ( empty( self::$instance->auth ) ) {
				// LazyLoad Auth for Frontend
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/auth.php';
				self::$instance->auth = new MonsterInsights_Auth();
			}
			return self::$instance->$key;
		} else if ( $key === 'license' ) {
			if ( empty( self::$instance->license ) ) {
				// LazyLoad Licensing for Frontend
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/license.php';
				self::$instance->license = new MonsterInsights_License();
			}
			return self::$instance->$key;
		} else {
			return self::$instance->$key;
		}
	}

	/**
	 * Define MonsterInsights constants.
	 *
	 * This function defines all of the MonsterInsights PHP constants.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function define_globals() {

		if ( ! defined( 'MONSTERINSIGHTS_VERSION' ) ) {
			define( 'MONSTERINSIGHTS_VERSION', $this->version );
		}

		if ( ! defined( 'MONSTERINSIGHTS_LITE_VERSION' ) ) {
			define( 'MONSTERINSIGHTS_LITE_VERSION', MONSTERINSIGHTS_VERSION );
		}

		if ( ! defined( 'GAWP_VERSION' ) ) {
			define( 'GAWP_VERSION', MONSTERINSIGHTS_VERSION );
		}

		if ( ! defined( 'MONSTERINSIGHTS_PLUGIN_NAME' ) ) {
			define( 'MONSTERINSIGHTS_PLUGIN_NAME', $this->plugin_name );
		}

		if ( ! defined( 'MONSTERINSIGHTS_PLUGIN_SLUG' ) ) {
			define( 'MONSTERINSIGHTS_PLUGIN_SLUG', $this->plugin_slug );
		}

		if ( ! defined( 'MONSTERINSIGHTS_PLUGIN_FILE' ) ) {
			define( 'MONSTERINSIGHTS_PLUGIN_FILE', $this->file );
		}

		if ( ! defined( 'GAWP_FILE' ) ) {
			define( 'GAWP_FILE', MONSTERINSIGHTS_PLUGIN_FILE );
		}

		if ( ! defined( 'MONSTERINSIGHTS_PLUGIN_DIR' ) ) {
			define( 'MONSTERINSIGHTS_PLUGIN_DIR', plugin_dir_path( $this->file )  );
		}

		if ( ! defined( 'GAWP_PATH' ) ) {
			define( 'GAWP_PATH', MONSTERINSIGHTS_PLUGIN_DIR  );
		}

		if ( ! defined( 'MONSTERINSIGHTS_PLUGIN_URL' ) ) {
			define( 'MONSTERINSIGHTS_PLUGIN_URL', plugin_dir_url( $this->file )  );
		}

		if ( ! defined( 'GAWP_URL' ) ) {
			define( 'GAWP_URL', MONSTERINSIGHTS_PLUGIN_URL  );
		}
	}	

	/**
	 * Loads the plugin textdomain for translation.
	 *
	 * @access public
	 * @since 6.0.0
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {

		$mi_locale = get_locale();
		if ( function_exists( 'get_user_locale' ) ) {
			$mi_locale = get_user_locale();
		}

		// Traditional WordPress plugin locale filter.
		$mi_locale  = apply_filters( 'plugin_locale',  $mi_locale, 'google-analytics-for-wordpress' );
		$mi_mofile  = sprintf( '%1$s-%2$s.mo', 'google-analytics-for-wordpress', $mi_locale ); 
	
		// Look for wp-content/languages/google-analytics-for-wordpress/google-analytics-for-wordpress-{lang}_{country}.mo
		$mi_mofile1 = WP_LANG_DIR . '/google-analytics-for-wordpress/' . $mi_mofile;

		// Look in wp-content/languages/plugins/google-analytics-for-wordpress/google-analytics-for-wordpress-{lang}_{country}.mo
		$mi_mofile2 = WP_LANG_DIR . '/plugins/google-analytics-for-wordpress/' . $mi_mofile;

		// Look in wp-content/languages/plugins/google-analytics-for-wordpress-{lang}_{country}.mo
		$mi_mofile3 = WP_LANG_DIR . '/plugins/' . $mi_mofile;

		// Look in wp-content/plugins/google-analytics-for-wordpress/languages/google-analytics-for-wordpress-{lang}_{country}.mo
		$mi_mofile4 = dirname( plugin_basename( MONSTERINSIGHTS_PLUGIN_FILE ) ) . '/languages/';
		$mi_mofile4 = apply_filters( 'monsterinsights_lite_languages_directory', $mi_mofile4 );

		if ( file_exists( $mi_mofile1 ) ) {
			load_textdomain( 'google-analytics-for-wordpress', $mi_mofile1 );
		} elseif ( file_exists( $mi_mofile2 ) ) {
			load_textdomain( 'google-analytics-for-wordpress', $mi_mofile2 );
		} elseif ( file_exists( $mi_mofile3 ) ) {
			load_textdomain( 'google-analytics-for-wordpress', $mi_mofile3 );
		} else {
			load_plugin_textdomain( 'google-analytics-for-wordpress', false, $mi_mofile4 );
		}

	}


	/**
	 * Output notice to update eCommerce
	 *
	 * @access public
	 * @since 6.0.0
	 *
	 * @return 	void
	 */
	public function monsterinsights_old_ecommerce() {
		?>
		<div class="error">
			<p><?php echo __( 'The version of MonsterInsights eCommerce addon you have is not compatible with the version of MonsterInsights installed. Please update the eCommerce addon as soon as possible', 'ga-premium' ); ?></p>
		</div>
		<?php

	}

	/**
	 * Output a nag notice if the user has an out of date WP version installed
	 *
	 * @access public
	 * @since 6.0.0
	 *
	 * @return 	void
	 */
	public function monsterinsights_wp_notice() {
		$url = admin_url( 'plugins.php' );
		// Check for MS dashboard
		if( is_network_admin() ) {
			$url = network_admin_url( 'plugins.php' );
		}
		?>
		<div class="error">
			<p><?php echo sprintf( esc_html__( 'Sorry, but your version of WordPress does not meet MonsterInsights\'s required version of %1$s3.8%2$s to run properly. The plugin not been activated. %3$sClick here to return to the Dashboard%4$s.', 'google-analytics-for-wordpress' ), '<strong>', '</strong>', '<a href="' . $url . '">', '</a>' ); ?></p>
		</div>
		<?php
	}
	
	/**
	 * Output a nag notice if the user has both Lite and Pro activated
	 *
	 * @access public
	 * @since 6.0.0
	 *
	 * @return 	void
	 */
	public function monsterinsights_pro_notice() {
		$url = admin_url( 'plugins.php' );
		// Check for MS dashboard
		if( is_network_admin() ) {
			$url = network_admin_url( 'plugins.php' );
		}
		?>
		<div class="error">
			<p><?php echo sprintf( esc_html__( 'Please %1$suninstall%2$s the MonsterInsights Lite Plugin. Your Pro version of MonsterInsights may not work as expected until the Lite version is uninstalled.', 'google-analytics-for-wordpress' ), '<a href="' . $url . '">', '</a>' ); ?></p>
		</div>
		<?php

	}

	/**
	 * Loads MonsterInsights settings
	 *
	 * Adds the items to the base object, and adds the helper functions.
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @return void
	 */
	public function load_settings() {
		global $monsterinsights_settings;
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/options.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/helpers.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/deprecated.php';
		$monsterinsights_settings  = monsterinsights_get_options();
	}


	/**
	 * Loads MonsterInsights License
	 *
	 * Loads license class used by MonsterInsights
	 *
	 * @since 7.0.0
	 * @access public
	 * 
	 * @return void
	 */
	public function load_licensing(){
		if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/license.php';
			self::$instance->license = new MonsterInsights_License();
		}
	}

	/**
	 * Loads MonsterInsights Auth
	 *
	 * Loads auth used by MonsterInsights
	 *
	 * @since 7.0.0
	 * @access public
	 * 
	 * @return void
	 */
	public function load_auth() {
		if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/auth.php';
			self::$instance->auth = new MonsterInsights_Auth();
		}
	}

	/**
	 * Loads all files into scope.
	 *
	 * @access public
	 * @since 6.0.0
	 *
	 * @return 	void
	 */
	public function require_files() {

		if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {

			// Lite and Pro files
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'assets/lib/pandora/class-am-notification.php';
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'assets/lib/pandora/class-am-deactivation-survey.php';
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'assets/lib/pandora/class-am-dashboard-widget-extend-feed.php';
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/ajax.php';
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/admin.php';
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/common.php';
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/notice.php';
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/capabilities.php';
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/licensing/license-actions.php';
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/licensing/autoupdate.php';
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/review.php';

			// Pages
				// Multisite
					require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/pages/network-settings.php';

				// Single Site
					require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/pages/settings.php';
					require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/pages/tracking.php';
					require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/pages/tools.php';
					require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/pages/reports.php';
				
				// Both	
					require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/pages/addons.php';

			// Settings Tabs
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/settings/tab-general.php';
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/settings/tab-tracking.php';
				//require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/settings/tab-status.php';
				//require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/settings/tab-support.php';
			
			// Register Settings + Settings API
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/settings/settings-api.php';
				require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/settings/register-settings.php';	

			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/api-auth.php';

			// Reports
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/reports/abstract-report.php';
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/reports/overview.php';

			// Reporting Functionality
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/reporting.php';
		}

		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/api-request.php';

		if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			// Late loading classes (self instantiating)
			require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/tracking.php';
		}

		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/frontend/frontend.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/frontend/seedprod.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/measurement-protocol.php';
	}

	/**
	 * Loads all updater related files and functions into scope.
	 *
	 * @access public
	 * @since 6.0.0
	 *
	 * @return null Return early if the license key is not set or there are key errors.
	 */
	public function require_updater() {

		// Retrieve the license key. If it is not set or if there are issues, return early.
		$key = self::$instance->license->get_valid_license_key();
		if ( ! $key ) {
			return;
		}

		// Load the updater class.
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/licensing/updater.php';

		// Fire a hook for Addons to register their updater since we know the key is present.
		do_action( 'monsterinsights_updater', $key );
	}
}

/**
 * Fired when the plugin is activated.
 *
 * @access public
 * @since 6.0.0
 *
 * @global int $wp_version      The version of WordPress for this install.
 * @global object $wpdb         The WordPress database object.
 * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false otherwise.
 *
 * @return void
 */
function monsterinsights_lite_activation_hook( $network_wide ) {

	global $wp_version;
	
	$url = admin_url( 'plugins.php' );
	// Check for MS dashboard
	if ( is_network_admin() ) {
		$url = network_admin_url( 'plugins.php' );
	}
	
	if ( version_compare( $wp_version, '3.8', '<' ) && ( ! defined( 'MONSTERINSIGHTS_FORCE_ACTIVATION' ) || ! MONSTERINSIGHTS_FORCE_ACTIVATION ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( sprintf( esc_html__( 'Sorry, but your version of WordPress does not meet MonsterInsight\'s required version of %1$s3.8%2$s to run properly. The plugin not been activated. %3$sClick here to return to the Dashboard%4$s.', 'google-analytics-by-wordpress' ), '<strong>', '</strong>', '<a href="' . $url . '">', '</a>' ) );
	}
	
	if ( class_exists( 'MonsterInsights' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( sprintf( esc_html__( 'Please uninstall and remove MonsterInsights Pro before activating Google Analytics for WordPress by MonsterInsights. The Lite version has not been activated. %1$sClick here to return to the Dashboard%2$s.', 'google-analytics-by-wordpress' ), '<a href="' . $url . '">', '</a>' ) );
	}
}
register_activation_hook( __FILE__, 'monsterinsights_lite_activation_hook' );

/**
 * Fired when the plugin is uninstalled.
 *
 * @access public
 * @since 6.0.0
 * 
 * @return 	void
 */
function monsterinsights_lite_uninstall_hook() {
	wp_cache_flush();

	// Note, if both MI Pro and Lite are active, this is an MI Pro instance
	// Therefore MI Lite can only use functions of the instance common to
	// both plugins. If it needs to be pro specific, then include a file that
	// has that method.
	$instance = MonsterInsights();

	if ( is_multisite() ) {
		$site_list = get_sites();
		foreach ( (array) $site_list as $site ) {
			switch_to_blog( $site->blog_id );

			// Delete auth
			$instance->api_auth->delete_auth();

			// Delete data
			$instance->reporting->delete_aggregate_data('site');

			// Delete license
			$instance->license->delete_site_license();

			restore_current_blog();
		}
		// Delete network auth using a custom function as some variables are not initiated.
		$instance->api_auth->uninstall_network_auth();

		// Delete network data
		$instance->reporting->delete_aggregate_data('network');

		// Delete network license
		$instance->license->delete_network_license();
	} else {
		// Delete auth
		$instance->api_auth->delete_auth();

		// Delete data
		$instance->reporting->delete_aggregate_data('site');

		// Delete license
		$instance->license->delete_site_license();
	}

}
register_uninstall_hook( __FILE__, 'monsterinsights_lite_uninstall_hook' );

/**
 * The main function responsible for returning the one true MonsterInsights_Lite
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $monsterinsights = MonsterInsights_Lite(); ?>
 *
 * @since 6.0.0
 *
 * @uses MonsterInsights_Lite::get_instance() Retrieve MonsterInsights_Lite instance.
 * 
 * @return MonsterInsights_Lite The singleton MonsterInsights_Lite instance.
 */
function MonsterInsights_Lite() {
	return MonsterInsights_Lite::get_instance();
}

/**
 * MonsterInsights Install and Updates.
 *
 * This function is used install and upgrade MonsterInsights. This is used for upgrade routines
 * that can be done automatically, behind the scenes without the need for user interaction
 * (for example pagination or user input required), as well as the initial install.
 *
 * @since 6.0.0
 * @access public
 *
 * @global string $wp_version WordPress version (provided by WordPress core).
 * @uses MonsterInsights_Lite::load_settings() Loads MonsterInsights settings
 * @uses MonsterInsights_Install::init() Runs upgrade process
 * 
 * @return void
 */
function monsterinsights_lite_install_and_upgrade() {
	global $wp_version;

	// If the WordPress site doesn't meet the correct WP version requirements, don't activate MonsterInsights
	if ( version_compare( $wp_version, '3.8', '<' ) ) {
		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			return;
		}
	}

	// Don't run if MI Pro is installed
	if ( class_exists( 'MonsterInsights' ) ) {
		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			return;
		}
	}


	// Load settings and globals (so we can use/set them during the upgrade process)
	MonsterInsights_Lite()->define_globals();
	MonsterInsights_Lite()->load_settings();

	// Load in Licensing
	MonsterInsights()->load_licensing();

	// Load in Auth
	MonsterInsights()->load_auth();

	// Load upgrade file
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/install.php';
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/install.php'; // Lite only install stuff

	// Run the MonsterInsights upgrade routines
	$updates = new MonsterInsights_Install();
	$updates->init();
}

/**
 * MonsterInsights check for install and update processes.
 *
 * This function is used to call the MonsterInsights automatic upgrade class, which in turn
 * checks to see if there are any update procedures to be run, and if
 * so runs them. Also installs MonsterInsights for the first time.
 *
 * @since 6.0.0
 * @access public
 *
 * @uses MonsterInsights_Install() Runs install and upgrade process.
 * 
 * @return void
 */
function monsterinsights_lite_call_install_and_upgrade(){
	add_action( 'wp_loaded', 'monsterinsights_lite_install_and_upgrade' );
}

/**
 * Returns the MonsterInsights combined object that you can use for both
 * MonsterInsights Lite and Pro Users. When both plugins active, defers to the 
 * more complete Pro object. 
 *
 * Warning: Do not use this in Lite or Pro specific code (use the individual objects instead). 
 * Also do not use in the MonsterInsights Lite/Pro upgrade and install routines.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Prevents the need to do conditional global object logic when you have code that you want to work with
 * both Pro and Lite.
 *
 * Example: <?php $monsterinsights = MonsterInsights(); ?>
 *
 * @since 6.0.0
 *
 * @uses MonsterInsights::get_instance() Retrieve MonsterInsights Pro instance.
 * @uses MonsterInsights_Lite::get_instance() Retrieve MonsterInsights Lite instance.
 * 
 * @return MonsterInsights The singleton MonsterInsights instance.
 */
if ( ! function_exists( 'MonsterInsights' ) ) {
	function MonsterInsights() {
		return ( class_exists( 'MonsterInsights' ) ? MonsterInsights_Pro() : MonsterInsights_Lite() );
	}
	add_action( 'plugins_loaded', 'MonsterInsights' );
}