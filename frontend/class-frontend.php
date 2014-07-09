<?php
/**
 * The basic frontend class for the GA plugin, extendable for the children
 */

if( !class_exists('Yoast_GA_Frontend') ){

	class Yoast_GA_Frontend {

		public static $options = array();

		public function __construct(){
			self::$options = get_option( 'yst_ga' );

			add_action( 'wp_enqueue_scripts', array( $this, 'add_ga_javascript' ) );
		}

		public function get_options(){
			return self::$options;
		}

		/**
		 * Hook a Google Analytics Javascript to track downloads and outbound links
		 */
		public function add_ga_javascript() {
			wp_enqueue_script( 'yst_ga', GAWP_URL .'frontend/js/yst_ga.js' );
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
	}

	global $yoast_ga_frontend;
	$yoast_ga_frontend	=	new Yoast_GA_Frontend;
}