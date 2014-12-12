<?php

if ( ! class_exists( 'Yoast_GA_Autoload' ) ) {

	class Yoast_GA_Autoload {

		private static $classes = null;

		public static function autoload( $class ) {

			$include_path = dirname( GAWP_FILE );

			if ( self::$classes === null ) {

				self::$classes = array(
					'yoast_ga_options'                    => 'includes/class-options',
					'yoast_ga_utils'                      => 'includes/class-utils',


					// Frontend classes
					'yoast_ga_frontend'                   => 'frontend/class-frontend',
					'yoast_ga_universal'                  => 'frontend/class-universal',
					'yoast_ga_js'                         => 'frontend/class-ga-js',

					// Admin classes
					'yoast_ga_admin'                      => 'admin/class-admin',
					'yoast_ga_admin_menu'                 => 'admin/class-admin-menu',
					'yoast_google_analytics'              => 'admin/class-google-analytics',
					'yoast_ga_admin_ga_js'                => 'admin/class-admin-ga-js',
					'yoast_ga_admin_assets'               => 'admin/class-admin-assets',
					'yoast_ga_admin_form'                 => 'admin/class-admin-form',

					// Dashboards
					'yoast_ga_dashboards_api_options'     => 'admin/dashboards/class-admin-dashboards-api-options',
					'yoast_ga_dashboards'                 => 'admin/dashboards/class-admin-dashboards',
					'yoast_ga_dashboards_collector'       => 'admin/dashboards/class-admin-dashboards-collector',
					'yoast_ga_dashboards_data'            => 'admin/dashboards/class-admin-dashboards-data',

					'yoast_ga_dashboards_display'         => 'admin/dashboards/class-admin-dashboards-display',
					'yoast_ga_dashboards_driver'          => 'admin/dashboards/drivers/class-admin-dashboards-driver',
					'yoast_ga_dashboards_driver_generate' => 'admin/dashboards/drivers/class-admin-dashboards-driver-generate',
					'yoast_ga_dashboards_table'           => 'admin/dashboards/drivers/class-admin-dashboards-table',
					'yoast_ga_dashboards_table_generate'  => 'admin/dashboards/drivers/class-admin-dashboards-table-generate',
					'yoast_ga_dashboards_graph'           => 'admin/dashboards/drivers/class-admin-dashboards-graph',
					'yoast_ga_dashboards_graph_generate'  => 'admin/dashboards/drivers/class-admin-dashboards-graph-generate',

					// License manager
					'yoast_license_manager'               => 'admin/license-manager/class-license-manager',
					'yoast_plugin_license_manager'        => 'admin/license-manager/class-plugin-license-manager',
					'yoast_product'                       => 'admin/license-manager/class-product',

					// API libraries
					'yoast_api_libs'                      => 'admin/api-libs/class-api-libs',

					// i18n module
					'yoast_i18n'                          => 'admin/i18n-module/i18n-module',

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

