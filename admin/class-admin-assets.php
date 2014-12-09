<?php

/**
 * This class is for the backend
 */
if ( ! class_exists( 'Yoast_GA_Admin_Assets' ) ) {

	class Yoast_GA_Admin_Assets {

		/**
		 * Add the scripts to the admin head
		 */
		public static function enqueue_scripts() {
			wp_enqueue_script( 'yoast_ga_admin', self::get_asset_path( 'assets/js/yoast_ga_admin' ) . self::file_ext( '.js' ) );

			// Enqueue the qtip js file
			wp_enqueue_script( 'jquery-qtip', self::get_asset_path( 'assets/dependencies/qtip/jquery.qtip.min.js' ) , array( 'jquery' ), '1.0.0-RC3', true );

			// Enqueue the chosen js file
			wp_enqueue_script( 'chosen_js', self::get_asset_path( 'assets/dependencies/chosen/chosen.jquery.min.js' ), array(), false, true );
		}

		/**
		 * Add the styles in the admin head
		 */
		public static function enqueue_styles() {
			wp_enqueue_style( 'yoast_ga_styles', self::get_asset_path( 'assets/css/yoast_ga_styles' ) . self::file_ext( '.css' ) );
		}

		/**
		 * Enqueues the settings page specific styles
		 */
		public static function enqueue_settings_styles() {
			// Enqueue the chosen css file
			wp_enqueue_style( 'chosen_css', self::get_asset_path( 'assets/dependencies/chosen/chosen' ) . self::file_ext( '.css' ) );
		}

		/**
		 * Loading the assets for dashboard
		 */
		public static function enqueue_dashboard_assets() {

			//
			wp_enqueue_script('ga-admin-dashboard', self::get_asset_path( 'assets/js/yoast_ga_admin_dashboard' ) . self::file_ext( '.js' ) );
			wp_enqueue_style('ga-admin-dashboard-css', self::get_asset_path( 'assets/css/yoast_ga_admin_dashboard' ). self::file_ext( '.css' ) ) ;

			// Enqueue the d3 js file
			wp_enqueue_script( 'd3_js', self::get_asset_path( 'assets/dependencies/rickshaw/d3.v3.min.js' ), array(), false, true );

			// Enqueue the ricksaw js file
			wp_enqueue_script( 'rickshaw_js', self::get_asset_path( 'assets/dependencies/rickshaw/rickshaw.min.js' ), array(), false, true );

			// Enqueue the rickshaw css
			wp_enqueue_style( 'rickshaw_css', self::get_asset_path( 'assets/dependencies/rickshaw/rickshaw.min.css' ) );

			// Enqueue the datatables js file
			wp_enqueue_script( 'datatables_js', self::get_asset_path( 'assets/dependencies/datatables/js/jquery.dataTables.min.js' ), array(), false, true );

			// Enqueue the datatables css
			wp_enqueue_style( 'datatables_css', self::get_asset_path( 'assets/dependencies/datatables/css/jquery.dataTables.min.css' ) );

			Yoast_GA_Dashboards::get_instance()->add_dashboard_js_translations();
		}

		/**
		 * Getting the full path to given $asset
		 *
		 * @param string $asset
		 *
		 * @return string
		 */
		private static function get_asset_path( $asset ) {
			static $plugin_directory;

			if ( $plugin_directory == null ) {
				$plugin_directory = plugin_dir_url( GAWP_FILE );
			}

			$return = $plugin_directory . $asset;

			return $return;
		}

		/**
		 * Check whether we can include the minified version or not
		 *
		 * @param string $ext
		 *
		 * @return string
		 */
		private static function file_ext( $ext ) {
			if ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ) {
				$ext = '.min' . $ext;
			}

			return $ext;
		}

	}

}