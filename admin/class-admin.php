<?php
/**
 * This class is for the backend, extendable for all child classes
 */

if ( ! class_exists( 'Yoast_GA_Admin' ) ) {

	class Yoast_GA_Admin {

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'create_menu' ), 5 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		}

		/**
		 * Create the admin menu
		 *
		 * @todo, we need to implement a new icon for this, currently we're using the WP seo icon
		 */
		public function create_menu() {
			// Add main page
			add_menu_page( __( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( 'General Settings', 'google-analytics-for-wordpress' ), __( 'Analytics', 'google-analytics-for-wordpress' ), 'manage_options', 'yst_ga_dashboard', array(
					$this,
					'load_page'
				), plugins_url( 'images/yoast-icon.png', WPSEO_FILE ), '2.00013467543' );

			// Sub menu pages
			$submenu_pages = array(
				array(
					'yst_ga_dashboard',
					__( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( 'Dashboard', 'google-analytics-for-wordpress' ),
					__( 'Dashboard', 'google-analytics-for-wordpress' ),
					'manage_options',
					'yst_ga_dashboard',
					array( $this, 'load_page' ),
					array( array( $this, 'yst_ga_dashboard' ) )
				),
				array(
					'yst_ga_dashboard',
					__( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( 'Settings', 'google-analytics-for-wordpress' ),
					__( 'Settings', 'google-analytics-for-wordpress' ),
					'manage_options',
					'yst_ga_settings',
					array( $this, 'load_page' ),
					array( array( $this, 'yst_ga_settings' ) )
				),
				array(
					'yst_ga_dashboard',
					__( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( 'Extensions', 'google-analytics-for-wordpress' ),
					__( '<span style="color:#f18500">' . __( 'Extensions', 'google-analytics-for-wordpress' ) . '</span>', 'google-analytics-for-wordpress' ),
					'manage_options',
					'yst_ga_licenses',
					array( $this, 'load_page' ),
					null
				),
			);

			if ( count( $submenu_pages ) ) {
				foreach ( $submenu_pages as $submenu_page ) {
					// Add submenu page
					add_submenu_page( $submenu_page[0], $submenu_page[1], $submenu_page[2], $submenu_page[3], $submenu_page[4], $submenu_page[5] );
				}
			}
		}

		/**
		 * Add the scripts to the admin head
		 *
		 * @todo add minified JS files
		 */
		public function enqueue_scripts(){
			wp_enqueue_script( 'yoast_ga_admin', GAWP_URL . 'js/yoast_ga_admin.js' );
		}

		/**
		 * Add the styles in the admin head
		 *
		 * @todo add minified CSS files
		 */
		public function enqueue_styles(){
			wp_enqueue_style( 'yoast_ga_styles', GAWP_URL . 'css/yoast_ga_styles.css' );
		}

		/**
		 * Load the page of a menu item in the GA plugin
		 */
		public function load_page() {

			require_once GAWP_PATH . 'admin/class-admin-ga-js.php';
			$ga_universal = false; // @todo get option if universal is enabled
			if( $ga_universal ){
				require_once GAWP_PATH . 'admin/class-admin-universal.php';
			}

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

	$Yoast_GA_Admin = new Yoast_GA_Admin;
}