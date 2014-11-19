<?php

if ( ! class_exists( 'Yoast_GA_Autoload' ) ) {

	class Yoast_GA_Autoload {

		private static $classes = null;

		public static function autoload( $class ) {

			$include_path = dirname( GAWP_FILE );

			if ( self::$classes === null ) {

				self::$classes = array(
					'yoast_ga_options'              => 'includes/class-options',

					// Frontend classes
					'yoast_ga_frontend'             => 'frontend/class-frontend',
					'yoast_ga_universal'            => 'frontend/class-universal',
					'yoast_ga_js'                   => 'frontend/class-ga-js',

					// Admin classes
					'yoast_ga_admin'                => 'admin/class-admin',
					'yoast_ga_admin_menu'          	=> 'admin/class-admin-menu',
					'yoast_google_analytics'        => 'admin/class-google-analytics',
					'yoast_ga_admin_ga_js'          => 'admin/class-admin-ga-js',
					'wp_gdata'                      => 'admin/wp-gdata/wp-gdata',

					// Dashboards
					'yoast_ga_dashboards'           => 'admin/class-admin-dashboards',
					'yoast_ga_dashboards_aggregate' => 'admin/class-admin-dashboards-aggregate',

					// License manager
					'yoast_license_manager'         => 'admin/license-manager/class-license-manager',
					'yoast_plugin_license_manager'  => 'admin/license-manager/class-plugin-license-manager',
					'yoast_product'                 => 'admin/license-manager/class-product',

					// API libraries
					'yoast_api_libs'                => 'admin/api-libs/class-api-libs',

					// i18n module
					'yoast_i18n'                    => 'admin/i18n-module/i18n-module',
				);
			}

			$class_name = strtolower( $class );
			if ( isset( self::$classes[$class_name] ) ) {
				require_once $include_path . '/' . self::$classes[$class_name] . '.php';
			}

		}

	}

	// register class autoloader
	spl_autoload_register( array( 'Yoast_GA_Autoload', 'autoload' ) );

}

