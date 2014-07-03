<?php
/**
 * The basic frontend class for the GA plugin, extendable for the children
 */

if( !class_exists('Yoast_GA_Frontend') ){

	class Yoast_GA_Frontend {

		public static $options = array();

		public function __construct(){
			self::$options = get_option( 'yst_ga' );
		}

		public function get_options(){
			return self::$options;
		}
	}

	global $yoast_ga_frontend;
	$yoast_ga_frontend	=	new Yoast_GA_Frontend;
}