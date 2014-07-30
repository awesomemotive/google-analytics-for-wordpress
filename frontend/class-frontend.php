<?php
/**
 * The basic frontend class for the GA plugin, extendable for the children
 */

if( !class_exists('Yoast_GA_Frontend') ){

	class Yoast_GA_Frontend {

		public static $options = array();

		public function __construct(){
			self::$options = get_option( 'yst_ga' );

			add_action( 'wp_enqueue_scripts', array( $this, 'add_ga_javascript' ), 9 );

			if ( isset( self::$options['ga_general']['tag_links_in_rss'] ) && self::$options['ga_general']['tag_links_in_rss']==1 ){
				add_filter( 'the_permalink_rss', array( $this, 'rsslinktagger' ), 99 );
			}
		}

		public function get_options(){
			return self::$options;
		}

		/**
		 * Check if we need to show an actual tracking code
		 * @return bool
		 */
		public static function do_tracking() {
			global $current_user;
			$options = self::$options['ga_general'];

			get_currentuserinfo();

			if ( 0 == $current_user->ID ) {
				return true;
			}

			if ( in_array( $current_user->roles[0], $options["ignore_users"] ) ) {
				return false;
			} else {
				return true;
			}
		}

		/**
		 * Hook a Google Analytics Javascript to track downloads and outbound links
		 */
		public function add_ga_javascript() {
			wp_enqueue_script( 'yst_ga', GAWP_URL .'frontend/js/yst_ga.js', array(), false, true );
		}

		/**
		 * Parse the domain
		 * @param $uri
		 *
		 * @return array|bool
		 */
		public function yoast_ga_get_domain( $uri ) {
			$hostPattern     = "/^(http:\/\/)?([^\/]+)/i";
			$domainPatternUS = "/[^\.\/]+\.[^\.\/]+$/";
			$domainPatternUK = "/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/";

			preg_match( $hostPattern, $uri, $matches );
			$host = $matches[2];
			if ( preg_match( "/.*\..*\..*\..*$/", $host ) )
				preg_match( $domainPatternUK, $host, $matches );
			else
				preg_match( $domainPatternUS, $host, $matches );

			if ( isset( $matches[0] ) ) {
				return array( "domain" => $matches[0], "host" => $host );
			} else {
				return false;
			}
		}

		/**
		 * Add the UTM source parameters in the RSS feeds to track traffic
		 * @param $guid
		 *
		 * @return string
		 */
		function rsslinktagger( $guid ) {
			global $post;
			if ( is_feed() ) {
				if ( self::$options['ga_general']['allow_anchor'] ) {
					$delimiter = '#';
				} else {
					$delimiter = '?';
					if ( strpos( $guid, $delimiter ) > 0 )
						$delimiter = '&amp;';
				}
				return $guid . $delimiter . 'utm_source=rss&amp;utm_medium=rss&amp;utm_campaign=' . urlencode( $post->post_name );
			}
			return $guid;
		}
	}

	global $yoast_ga_frontend;
	$yoast_ga_frontend	=	new Yoast_GA_Frontend;
}