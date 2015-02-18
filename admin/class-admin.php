<?php

/**
 * This class is for the backend, extendable for all child classes
 */
class Yoast_GA_Admin extends Yoast_GA_Options {

	/**
	 * Store the API instance
	 *
	 * @var
	 */
	public $api;

	public function __construct() {
		parent::__construct();

		add_action( 'plugins_loaded', array( $this, 'init_ga' ) );
		add_action( 'admin_init', array( $this, 'init_settings' ) );
	}

	/**
	 * Init function when the plugin is loaded
	 */
	public function init_ga() {

		new Yoast_GA_Admin_Menu( $this );

		add_filter( 'plugin_action_links_' . plugin_basename( GAWP_FILE ), array( $this, 'add_action_links' ) );

	}

	/**
	 * Init function for the settings of GA
	 */
	public function init_settings() {
		$this->options = $this->get_options();
		$this->api     = Yoast_Api_Libs::load_api_libraries( array( 'google', 'googleanalytics' ) );
		$dashboards    = Yoast_GA_Dashboards::get_instance();

		// Listener for reconnecting with google analytics
		$this->google_analytics_listener();

		if ( is_null( $this->get_tracking_code() ) && $this->show_admin_warning() ) {
			add_action( 'admin_notices', array( 'Yoast_Google_Analytics_Notice', 'config_warning' ) );
		}

		// Check if something has went wrong with GA-api calls
		$has_tracking_code = ( ! is_null( $this->get_tracking_code() ) && empty( $this->options['manual_ua_code_field'] ) );
		if ( $has_tracking_code && $this->show_admin_dashboard_warning() ) {
			Yoast_Google_Analytics::get_instance()->check_for_ga_issues();
		}


		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$this->handle_ga_post_request( $dashboards );
		}

		/**
		 * Show the notifications if we have one
		 */
		$this->show_notification( 'ga_notifications' );

		// Load the Google Analytics Dashboards functionality
		$dashboards->init_dashboards( $this->get_current_profile() );
	}

	/**
	 * This function saves the settings in the option field and returns a wp success message on success
	 *
	 * @param $data
	 */
	public function save_settings( $data ) {

		unset( $data['google_auth_code'] );

		foreach ( $data as $key => $value ) {
			if ( $key != 'return_tab' ) {
				if ( $key != 'custom_code' && is_string( $value ) ) {
					$value = strip_tags( $value );
				}
				$this->options[$key] = $value;
			}
		}

		// Check checkboxes, on a uncheck they won't be posted to this function
		$defaults = $this->default_ga_values();
		foreach ( $defaults[$this->option_prefix] as $option_name => $value ) {
			$this->handle_default_setting( $data, $option_name, $value );
		}

		if ( ! empty( $this->options['analytics_profile'] ) ) {
			$this->options['analytics_profile_code'] = $this->get_ua_code_from_profile( $this->options['analytics_profile'] );
		}

		if ( $this->update_option( $this->options ) ) {
			// Success, add a new notification
			$this->add_notification( 'ga_notifications', array(
				'type'        => 'success',
				'description' => __( 'Settings saved.', 'google-analytics-for-wordpress' ),
			) );

		} else {
			// Fail, add a new notification
			$this->add_notification( 'ga_notifications', array(
				'type'        => 'error',
				'description' => __( 'There were no changes to save, please try again.', 'google-analytics-for-wordpress' ),
			) );
		}

		#redirect
		wp_redirect( admin_url( 'admin.php' ) . '?page=yst_ga_settings#top#' . $data['return_tab'], 301 );
		exit;
	}

	/**
	 * Run a this deactivation hook on deactivation of GA. When this happens we'll
	 * remove the options for the profiles and the refresh token.
	 */
	public static function ga_deactivation_hook() {
		// Remove the refresh token
		delete_option( 'yoast-ga-refresh_token' );

		// Remove the ga accounts and response
		delete_option( 'yst_ga_accounts' );
		delete_option( 'yst_ga_response' );

	}

	/**
	 * Handle a default setting in GA
	 *
	 * @param $data
	 * @param $option_name
	 * @param $value
	 */
	private function handle_default_setting( $data, $option_name, $value ) {
		if ( ! isset( $data[$option_name] ) ) {
			// If no data was passed in, set it to the default.
			if ( $value === 1 ) {
				// Disable the checkbox for now, use value 0
				$this->options[$option_name] = 0;
			} else {
				$this->options[$option_name] = $value;
			}
		}
	}

	/**
	 * Handle the post requests in the admin form of the GA plugin
	 *
	 * @param $dashboards
	 */
	private function handle_ga_post_request( $dashboards ) {
		if ( ! function_exists( 'wp_verify_nonce' ) ) {
			require_once( ABSPATH . 'wp-includes/pluggable.php' );
		}

		if ( isset( $_POST['ga-form-settings'] ) && wp_verify_nonce( $_POST['yoast_ga_nonce'], 'save_settings' ) ) {
			if ( ! isset ( $_POST['ignore_users'] ) ) {
				$_POST['ignore_users'] = array();
			}

			$dashboards_disabled = Yoast_GA_Settings::get_instance()->dashboards_disabled();

			if ( ( $dashboards_disabled == false && isset( $_POST['dashboards_disabled'] ) ) || $this->ga_profile_changed( $_POST ) ) {
				$dashboards->reset_dashboards_data();
			}

			// Post submitted and verified with our nonce
			$this->save_settings( $_POST );
		}
	}

	/**
	 * Is there selected an other property in the settings post? Returns true or false.
	 *
	 * @param $post
	 *
	 * @return bool
	 */
	private function ga_profile_changed( $post ) {
		if ( isset( $post['analytics_profile'] ) && isset( $this->options['analytics_profile'] ) ) {
			if ( $post['analytics_profile'] != $this->options['analytics_profile'] ) {
				return true;
			}
		}

		return false;
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
	 * Transform the Profile ID into an helpful UA code
	 *
	 * @param $profile_id
	 *
	 * @return null
	 */
	private function get_ua_code_from_profile( $profile_id ) {
		$profiles = $this->get_profiles();
		$ua_code  = null;

		foreach ( $profiles as $account ) {
			foreach ( $account['items'] as $profile ) {
				foreach ( $profile['items'] as $subprofile ) {
					if ( isset( $subprofile['id'] ) && $subprofile['id'] === $profile_id ) {
						return $subprofile['ua_code'];
					}
				}
			}
		}

		return $ua_code;
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
	 * Adds some promo text for the premium plugin on the custom dimensions tab.
	 */
	public function premium_promo() {
		echo '<div class="ga-promote">';
		echo '<p>';
		printf( __( 'If you want to track custom dimensions like page views per author or post type, you should upgrade to the %1$spremium version of Google Analytics by Yoast%2$s.', 'google-analytics-for-wordpress' ), '<a href="https://yoast.com/wordpress/plugins/google-analytics/#utm_medium=text-link&utm_source=gawp-config&utm_campaign=wpgaplugin&utm_content=custom_dimensions_tab">', '</a>' );
		echo ' ';
		_e( 'This will also give you email access to the support team at Yoast, who will provide support on the plugin 24/7.', 'google-analytics-for-wordpress' );
		echo '</p>';
		echo '</div>';
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
			add_action( 'yst_ga_custom_dimensions_tab-content', array( $this, 'premium_promo' ) );
		}

		if ( ! has_action( 'yst_ga_custom_dimension_add-dashboards-tab' ) ) {
			add_action( 'yst_ga_custom_dimension_add-dashboards-tab', array( $this, 'premium_promo' ) );
		}

		if ( isset( $_GET['page'] ) ) {
			switch ( $_GET['page'] ) {
				case 'yst_ga_settings':
					require_once( $this->plugin_path . 'admin/pages/settings.php' );
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
	}


	/**
	 * Get the Google Analytics profiles which are in this google account
	 *
	 * @return array
	 */
	public function get_profiles() {
		$return = Yoast_Google_Analytics::get_instance()->get_profiles();

		return $return;
	}

	/**
	 * Checks if there is a callback or reauth to get token from Google Analytics api
	 */
	private function google_analytics_listener() {

		if ( ! empty( $_POST['google_auth_code'] ) ) {
			Yoast_Google_Analytics::get_instance()->authenticate( trim( $_POST['google_auth_code'] ) );
		}


		if ( ! empty ( $_GET['reauth'] ) ) {

			delete_option( 'yst_ga_accounts' );
			delete_option( 'yst_ga_response' );

			Yoast_Google_Analytics::get_instance()->authenticate();
		}

	}

	/**
	 * Get the current GA profile
	 *
	 * @return null
	 */
	private function get_current_profile() {
		if ( ! empty( $this->options['analytics_profile'] ) ) {
			return $this->options['analytics_profile'];
		} else {
			return null;
		}
	}

	/**
	 * Get the user roles of this WordPress blog
	 *
	 * @return array
	 */
	public function get_userroles() {
		global $wp_roles;

		$all_roles = $wp_roles->roles;
		$roles     = array();

		/**
		 * Filter: 'editable_roles' - Allows filtering of the roles shown within the plugin (and elsewhere in WP as it's a WP filter)
		 *
		 * @api array $all_roles
		 */
		$editable_roles = apply_filters( 'editable_roles', $all_roles );

		foreach ( $editable_roles as $id => $name ) {
			$roles[] = array(
				'id'   => $id,
				'name' => translate_user_role($name['name']),
			);
		}

		return $roles;
	}

	/**
	 * Get types of how we can track downloads
	 *
	 * @return array
	 */
	public function track_download_types() {
		return array(
			0 => array( 'id' => 'event', 'name' => __( 'Event', 'google-analytics-for-wordpress' ) ),
			1 => array( 'id' => 'pageview', 'name' => __( 'Pageview', 'google-analytics-for-wordpress' ) ),
		);
	}

	/**
	 * Get options for the track full url or links setting
	 *
	 * @return array
	 */
	public function get_track_full_url() {
		return array(
			0 => array( 'id' => 'domain', 'name' => __( 'Just the domain', 'google-analytics-for-wordpress' )  ),
			1 => array( 'id' => 'full_links', 'name' => __( 'Full links', 'google-analytics-for-wordpress' )  ),
		);
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
	 * Add a notification to the notification transient
	 *
	 * @param $transient_name
	 * @param $settings
	 */
	private function add_notification( $transient_name, $settings ) {
		set_transient( $transient_name, $settings, MINUTE_IN_SECONDS );
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
			} else {
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
