<?php
/**
 * @package GoogleAnalytics\Admin
 */

/**
 * This class is for the backend
 */
class Yoast_GA_Admin {

	/**
	 * Store the API instance
	 *
	 * @var resource
	 */
	public $api;

	/**
	 * Store the options
	 *
	 * @var array
	 */
	private $options;

	/**
	 * @var string
	 */
	private $plugin_path;

	/**
	 * @var string
	 */
	private $plugin_url;

	/**
	 * Construct the admin class
	 */
	public function __construct() {
		$this->plugin_path = GAWP_DIR . '/';
		$this->plugin_url  = GAWP_URL;

		add_action( 'plugins_loaded', array( $this, 'init_ga' ) );
		add_action( 'admin_init', array( $this, 'init_settings' ) );
	}

	/**
	 * Init function when the plugin is loaded
	 */
	public function init_ga() {
		new Yoast_GA_Admin_Menu( $this );

		new Yoast_GA_Admin_Settings_Registrar();

		add_filter( 'plugin_action_links_' . plugin_basename( GAWP_FILE ), array( $this, 'add_action_links' ) );
	}

	/**
	 * Init function for the settings of GA
	 */
	public function init_settings() {
		$options_instance = Yoast_GA_Options::instance();
		$this->options = $options_instance->get_options();

		$this->api     = Yoast_Api_Libs::load_api_libraries( array( 'google', 'googleanalytics' ) );
		$dashboards    = Yoast_GA_Dashboards::get_instance();

		// Listener for reconnecting with google analytics
		$this->google_analytics_listener();

		if ( is_null( $options_instance->get_tracking_code() ) && $this->show_admin_warning() ) {
			add_action( 'admin_notices', array( 'Yoast_Google_Analytics_Notice', 'config_warning' ) );
		}

		// Check if something has went wrong with GA-api calls
		$has_tracking_code = ( ! is_null( $options_instance->get_tracking_code() ) && empty( $this->options['manual_ua_code_field'] ) );
		if ( $has_tracking_code && $this->show_admin_dashboard_warning() ) {
			Yoast_Google_Analytics::get_instance()->check_for_ga_issues();
		}

		/**
		 * Show the notifications if we have one
		 */
		$this->show_notification( 'ga_notifications' );

		// Load the Google Analytics Dashboards functionality
		$dashboards->init_dashboards( $this->get_current_profile() );
	}

	/**
	 * Run a this deactivation hook on deactivation of GA. When this happens we'll
	 * remove the options for the profiles and the refresh token.
	 */
	public static function ga_deactivation_hook() {
		// Remove the refresh token and other API settings
		self::analytics_api_clean_up();
	}

	/**
	 * Are we allowed to show a warning message? returns true if it's allowed
	 *
	 * @return bool
	 */
	private function show_admin_warning() {
		return ( current_user_can( 'manage_options' ) && ( ! isset( $_GET['page'] ) || ( isset( $_GET['page'] ) && $_GET['page'] !== 'yst_ga_settings' ) ) );
	}

	/**
	 * Are we allowed to show a warning message? returns true if it's allowed ( this is meant to be only for dashboard )
	 *
	 * @return bool
	 */
	private function show_admin_dashboard_warning() {
		return ( current_user_can( 'manage_options' ) && isset( $_GET['page'] ) && $_GET['page'] === 'yst_ga_dashboard' );
	}

	/**
	 * Add a link to the settings page to the plugins list
	 *
	 * @param array $links array of links for the plugins, adapted when the current plugin is found.
	 *
	 * @return array $links
	 */
	public function add_action_links( $links ) {
		// add link to knowledgebase
		// @todo UTM link fix
		$faq_link = '<a title="Yoast Knowledge Base" href="http://kb.yoast.com/category/43-google-analytics-for-wordpress">' . __( 'FAQ', 'google-analytics-for-wordpress' ) . '</a>';
		array_unshift( $links, $faq_link );

		$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=yst_ga_settings' ) ) . '">' . __( 'Settings', 'google-analytics-for-wordpress' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Register the custom dimensions tab
	 */
	public function register_custom_dimensions_tab() {
		echo '<a class="nav-tab" id="yst_ga_custom_dimensions-tab" href="#top#yst_ga_custom_dimensions">' . __( 'Custom Dimensions', 'google-analytics-for-wordpress' ) . '</a>';
	}

	/**
	 * Adds some promo text for the premium plugin on the custom dimensions tab.
	 */
	public function premium_promo_tab() {
		echo $this->premium_promo( true );
	}

	/**
	 * Adds some promo text for the premium plugin on the custom dimensions tab.
	 *
	 * @param bool $add_tab_div Add the div wrapper to make it a tab
	 */
	public function premium_promo( $add_tab_div = false ) {
		require_once( $this->plugin_path . 'admin/views/custom-dimensions-upsell.php' );
	}

	/**
	 * Initialize the promo class for our translate site
	 *
	 * @return yoast_i18n
	 */
	public function translate_promo() {
		$yoast_ga_i18n = new yoast_i18n(
			array(
				'textdomain'     => 'google-analytics-for-wordpress',
				'project_slug'   => 'google-analytics-for-wordpress',
				'plugin_name'    => 'Google Analytics by Yoast',
				'hook'           => 'yoast_ga_admin_footer',
				'glotpress_url'  => 'http://translate.yoast.com',
				'glotpress_name' => 'Yoast Translate',
				'glotpress_logo' => 'https://cdn.yoast.com/wp-content/uploads/i18n-images/Yoast_Translate.svg',
				'register_url '  => 'http://translate.yoast.com/projects#utm_source=plugin&utm_medium=promo-box&utm_campaign=yoast-ga-i18n-promo',
			)
		);

		return $yoast_ga_i18n;
	}

	/**
	 * Load the page of a menu item in the GA plugin
	 */
	public function load_page() {

		$this->translate_promo();

		if ( ! has_action( 'yst_ga_custom_dimensions_tab-content' ) ) {
			add_action( 'yst_ga_custom_tabs-tab', array( $this, 'register_custom_dimensions_tab' ) );
			add_action( 'yst_ga_custom_tabs-content', array( $this, 'premium_promo_tab' ) );
		}

		if ( ! has_action( 'yst_ga_custom_dimension_add-dashboards-tab' ) ) {
			add_action( 'yst_ga_custom_dimension_add-dashboards-tab', array( $this, 'premium_promo' ) );
		}

		switch ( filter_input( INPUT_GET, 'page' ) ) {
			case 'yst_ga_settings':
				require_once( $this->plugin_path . 'admin/pages/settings-api.php' );
				break;
			case 'yst_ga_extensions':
				require_once( $this->plugin_path . 'admin/pages/extensions.php' );
				break;
			case 'yst_ga_dashboard':
			default:
				require_once( $this->plugin_path . 'admin/pages/dashboard.php' );
				break;
		}
	}

	/**
	 * Checks if there is a callback or reauth to get token from Google Analytics api
	 */
	private function google_analytics_listener() {
		if ( ! empty( $this->options['google_auth_code'] ) ) {
			Yoast_Google_Analytics::get_instance()->authenticate( trim( $this->options['google_auth_code'] ) );
		}
		$google_auth_code = filter_input( INPUT_POST, 'google_auth_code' );
		if ( $google_auth_code && current_user_can( 'manage_options' ) && wp_verify_nonce( 'yoast_ga_nonce', 'save_settings' ) ) {

			self::analytics_api_clean_up();

			Yoast_Google_Analytics::get_instance()->authenticate( trim( $google_auth_code ) );
		}
	}

	/**
	 * Clean up the Analytics API settings
	 */
	public static function analytics_api_clean_up() {
		delete_option( 'yoast-ga-refresh_token' );
		delete_option( 'yst_ga_api_call_fail' );
		delete_option( 'yst_ga_last_wp_run' );
		delete_option( 'yst_ga_api' );
	}

	/**
	 * Get the UA code from a profile
	 *
	 * @param bool $ua_code
	 *
	 * @return null
	 */
	public function get_current_profile( $ua_code = false ) {
		if ( ! empty( $this->options['analytics_profile'] ) ) {
			if ( $ua_code ) {
				return $this->options['analytics_profile_code'];
			}

			return $this->options['analytics_profile'];
		}

		return null;
	}

	/**
	 * Render the admin page head for the GA Plugin
	 */
	public function content_head() {
		require 'views/content_head.php';
	}

	/**
	 * Render the admin page footer with sidebar for the GA Plugin
	 */
	public function content_footer() {

		do_action( 'yoast_ga_admin_footer' );

		if ( true == WP_DEBUG ) {
			// Show the debug information if debug is enabled in the wp_config file
			echo '<div id="ga-debug-info" class="postbox"><h3 class="hndle"><span>' . __( 'Debug information', 'google-analytics-for-wordpress' ) . '</span></h3><div class="inside"><pre>';
			var_dump( $this->options );
			echo '</pre></div></div>';
		}

		if ( class_exists( 'Yoast_Product_GA_Premium' ) ) {
			$license_manager = new Yoast_Plugin_License_Manager( new Yoast_Product_GA_Premium() );
			if ( $license_manager->license_is_valid() ) {
				return;
			}
		}

		$banners   = array();
		$banners[] = array(
			'url'    => 'https://yoast.com/hire-us/website-review/#utm_medium=banner&utm_source=gawp-config&utm_campaign=wpgaplugin',
			'banner' => $this->plugin_url . 'assets/img/banner-website-review.png',
			'title'  => 'Get a website review by Yoast',
		);
		$banners[] = array(
			'url'    => 'https://yoast.com/wordpress/plugins/google-analytics/#utm_medium=banner&utm_source=gawp-config&utm_campaign=wpgaplugin',
			'banner' => $this->plugin_url . 'assets/img/banner-premium-ga.png',
			'title'  => 'Get the premium version of Google Analytics by Yoast!',
		);
		$banners[] = array(
			'url'    => 'https://yoast.com/ebook-optimize-wordpress-site/#utm_medium=banner&utm_source=gawp-config&utm_campaign=wpgaplugin',
			'banner' => $this->plugin_url . 'assets/img/eBook_261x130.png',
			'title'  => 'Get the Yoast ebook!',
		);
		$banners[] = array(
			'url'    => 'https://yoast.com/wordpress/plugins/ga-ecommerce/#utm_medium=banner&utm_source=gawp-config&utm_campaign=wpgaplugin',
			'banner' => $this->plugin_url . 'assets/img/banner-ga-ecommerce.png',
			'title'  => 'Get advanced eCommerce tracking for WooCommerce and Easy Digital Downloads!',
		);

		shuffle( $banners );

		require 'views/content-footer.php';

	}

	/**
	 * Returns a list of all available extensions
	 *
	 * @return array
	 */
	public function get_extensions() {
		$extensions = array(
			'ga_premium' => (object) array(
				'url'    => 'https://yoast.com/wordpress/plugins/google-analytics/',
				'title'  => __( 'Google Analytics by Yoast Premium', 'google-analytics-for-wordpress' ),
				'desc'   => __( 'The premium version of Google Analytics by Yoast with more features and support.', 'google-analytics-for-wordpress' ),
				'status' => 'uninstalled',
			),
			'ecommerce'  => (object) array(
				'url'    => 'https://yoast.com/wordpress/plugins/ga-ecommerce/',
				'title'  => __( 'Google Analytics by Yoast', 'google-analytics-for-wordpress' ) . '<br />' . __( 'eCommerce tracking', 'google-analytics-for-wordpress' ),
				'desc'   => __( 'Track your eCommerce data and transactions with this eCommerce extension for Google Analytics.', 'google-analytics-for-wordpress' ),
				'status' => 'uninstalled',
			),
		);

		$extensions = apply_filters( 'yst_ga_extension_status', $extensions );

		return $extensions;
	}

	/**
	 * Show the notification that should be set, after showing the notification this function unset the transient
	 *
	 * @param string $transient_name The name of the transient which contains the notification
	 */
	public function show_notification( $transient_name ) {
		$transient = get_transient( $transient_name );

		if ( isset( $transient['type'] ) && isset( $transient['description'] ) ) {
			if ( $transient['type'] == 'success' ) {
				add_settings_error(
					'yoast_google_analytics',
					'yoast_google_analytics',
					$transient['description'],
					'updated'
				);
			}
			else {
				add_settings_error(
					'yoast_google_analytics',
					'yoast_google_analytics',
					$transient['description'],
					'error'
				);
			}

			delete_transient( $transient_name );
		}
	}

}
