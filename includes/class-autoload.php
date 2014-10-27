<?php

if ( ! class_exists( 'Yoast_GA_Autoload' ) ) {

	class Yoast_GA_Autoload {

		private static $classes = null;

		public static function autoload( $class ) {

			$include_path = dirname( GAWP_FILE );

			if ( self::$classes === null ) {

				self::$classes = array(
					'yoast_ga_options'             => 'includes/class-options',

					// Frontend classes
					'yoast_ga_frontend'  		   => 'frontend/class-frontend',
					'yoast_ga_universal' 		   => 'frontend/class-universal',
					'yoast_ga_js'                  => 'frontend/class-ga-js',

					// Admin classes
					'yoast_ga_admin'			   => 'admin/class-admin',
					'yoast_google_analytics'       => 'admin/class-google-analytics',
					'yoast_ga_admin_ga_js'   	   => 'admin/class-admin-ga-js',

					// License manager
					'yoast_license_manager'        => 'admin/license-manager/class-license-manager',
					'yoast_plugin_license_manager' => 'admin/license-manager/class-plugin-license-manager',
					'yoast_product'                => 'admin/license-manager/class-product',
				);
			}

			$class_name = strtolower( $class );
			if ( isset( self::$classes[$class_name] ) ) {
				require_once $include_path . '/' . self::$classes[$class_name] . '.php';
			}

			add_action( 'plugins_loaded', array( 'Yoast_GA_Autoload', 'yst_ga_load_textdomain' ) );

		}

		/**
		 * Load plugin textdomain
		 */
		public static function yst_ga_load_textdomain() {
			load_plugin_textdomain( 'google-analytics-for-wordpress', false, dirname( plugin_basename( GAWP_FILE ) ) . '/languages/' );
		}

	}

	// register class autoloader
	spl_autoload_register( array( 'Yoast_GA_Autoload', 'autoload' ) );

}

