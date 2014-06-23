<?php
/**
 * This class is for the backend, extendable for all child classes
 */

if( !class_exists('Yoast_GA_Admin') ){

	class Yoast_GA_Admin {

		public function __construct(){
			add_action( 'admin_menu', array( $this, 'yst_ga_create_menu' ), 5 );
		}

		/**
		 * Create the admin menu
		 *
		 * @todo, we need to implement a new icon for this, currently we're using the WP seo icon
		 */
		public function yst_ga_create_menu(){
			// Add main page
			add_menu_page( __( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( 'General Settings', 'google-analytics-for-wordpress' ), __( 'Analytics', 'google-analytics-for-wordpress' ), 'manage_options', 'yst_ga_dashboard', array( $this, 'load_page' ), plugins_url( 'images/yoast-icon.png', WPSEO_FILE ), '2.00013467543' );

			// Sub menu pages
			$submenu_pages = array(
				array( 'yst_ga_dashboard', __( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( 'Dashboard', 'google-analytics-for-wordpress' ), __( 'Dashboard', 'google-analytics-for-wordpress' ), 'manage_options', 'yst_ga_dashboard', array( $this, 'load_page' ), array( array( $this, 'yst_ga_dashboard' ) ) ),
				array( 'yst_ga_dashboard', __( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( 'Settings', 'google-analytics-for-wordpress' ), __( 'Settings', 'google-analytics-for-wordpress' ), 'manage_options', 'yst_ga_settings', array( $this, 'load_page' ), array( array( $this, 'yst_ga_settings' ) ) ),
				array( 'yst_ga_dashboard', __( 'Yoast Google Analytics:', 'google-analytics-for-wordpres' ) . ' ' . __( 'Extensions', 'google-analytics-for-wordpress'), __('<span style="color:#f18500">'.__( 'Extensions', 'google-analytics-for-wordpress' ) .'</span>', 'google-analytics-for-wordpress' ), 'manage_options', 'yst_ga_licenses', array( $this, 'load_page' ), null ),
			);

			if ( count( $submenu_pages ) ) {
				foreach ( $submenu_pages as $submenu_page ) {
					// Add submenu page
					add_submenu_page( $submenu_page[0], $submenu_page[1], $submenu_page[2], $submenu_page[3], $submenu_page[4], $submenu_page[5] );
				}
		}
		}

		/**
		 * Load the page of a menu item in the GA plugin
		 */
		public function load_page() {
			if ( isset( $_GET['page'] ) ) {
				switch ( $_GET['page'] ) {
					case 'yst_ga_settings':
						require_once( GAWP_PATH . 'admin/pages/settings.php' );
						break;
					case 'yst_ga_licenses':
						require_once( GAWP_PATH . 'admin/pages/extensions.php' );
						break;
					case 'yst_ga_dashboard':
					default:
						require_once( GAWP_PATH . 'admin/pages/dashboard.php' );
						break;
				}
			}
		}

	}

	$Yoast_GA_Admin	=	new Yoast_GA_Admin;
}