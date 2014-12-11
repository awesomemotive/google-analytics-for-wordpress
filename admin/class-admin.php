<?php
/**
 * This class is for the backend, extendable for all child classes
 */

if ( ! class_exists( 'Yoast_GA_Admin' ) ) {

	class Yoast_GA_Admin extends Yoast_GA_Options {

		private $form_namespace;

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
			$this->api     = Yoast_Api_Libs::load_api_libraries( array( 'oauth', 'googleanalytics' ) );

			if ( is_null( $this->get_tracking_code() ) ) {
				add_action( 'admin_notices', array( $this, 'config_warning' ) );
			}

			if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

				if ( ! function_exists( 'wp_verify_nonce' ) ) {
					require_once( ABSPATH . 'wp-includes/pluggable.php' );
				}

				if ( isset( $_POST['ga-form-settings'] ) && wp_verify_nonce( $_POST['yoast_ga_nonce'], 'save_settings' ) ) {
					if ( ! isset ( $_POST['ignore_users'] ) ) {
						$_POST['ignore_users'] = array();
					}

					// Post submitted and verified with our nonce
					$this->save_settings( $_POST );
				}
			}

			/**
			 * Show the notifications if we have one
			 */
			$this->show_notification( 'ga_notifications' );

			$this->connect_with_google_analytics();

			// Load the Google Analytics Dashboards functionality
			$dashboards = Yoast_GA_Dashboards::get_instance();
			$dashboards->init_dashboards( $this->get_current_profile() );
		}

		/**
		 * Throw a warning if no UA code is set.
		 */
		public function config_warning() {
			echo '<div class="error"><p>' . sprintf( __( 'Please configure your %sGoogle Analytics settings%s!', 'google-analytics-for-wordpress' ), '<a href="' . admin_url( 'admin.php?page=yst_ga_settings' ) . '">', '</a>' ) . '</p></div>';
		}

		/**
		 * This function saves the settings in the option field and returns a wp success message on success
		 *
		 * @param $data
		 */
		public function save_settings( $data ) {
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
			foreach ( $defaults[$this->option_prefix] as $key => $value ) {
				if ( ! isset( $data[$key] ) ) {
					// If no data was passed in, set it to the default.
					$this->options[$key] = $value;
				}
			}

			if ( ! empty( $this->options['analytics_profile'] ) ) {
				$this->options['analytics_profile_code'] = $this->get_ua_code_from_profile( $this->options['analytics_profile'] );
			}

			if ( $this->update_option( $this->options ) ) {
				// Success, add a new notification
				$this->add_notification( 'ga_notifications', array(
					'type'        => 'success',
					'description' => __( 'Settings saved!', 'google-analytics-for-wordpress' ),
				) );

			} else {
				// Fail, add a new notification
				$this->add_notification( 'ga_notifications', array(
					'type'        => 'error',
					'description' => __( 'There where no changes to save, please try again.', 'google-analytics-for-wordpress' ),
				) );
			}

			#redirect
			wp_redirect( admin_url( 'admin.php' ) . '?page=yst_ga_settings#top#' . $data['return_tab'], 301 );
			exit;
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

			foreach ( $profiles as $profile ) {
				if ( isset( $profile['id'] ) && $profile['id'] == $profile_id ) {
					$ua_code = $profile['ua_code'];
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
			echo '<p>';
			printf( __( 'If you want to track custom dimensions, to for instance track page views per author or post type, you should upgrade to the %1$spremium version of Google Analytics by Yoast%2$s.', 'google-analytics-for-wordpress' ), '<a href="https://yoast.com/wordpress/plugins/google-analytics/#utm_medium=text-link&utm_source=gawp-config&utm_campaign=wpgaplugin&utm_content=custom_dimensions_tab">', '</a>' );
			echo ' ';
			_e( 'This will also give you email access to the support team at Yoast, who will provide support on the plugin 24/7.', 'google-analytics-for-wordpress' );
			echo '</p>';
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
			global $yoast_ga_admin_ga_js;
			$yoast_ga_admin_ga_js = new Yoast_GA_Admin_GA_JS;

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
		 * Create a form element to init a form
		 *
		 * @param string $namespace
		 *
		 * @return string
		 */
		public function create_form( $namespace ) {
			$this->form_namespace = $namespace;

			$action = admin_url( 'admin.php' );
			if ( isset( $_GET['page'] ) ) {
				$action .= '?page=' . $_GET['page'];
			}

			return '<form action="' . $action . '" method="post" id="yoast-ga-form-' . $this->form_namespace . '" class="yoast_ga_form">' . wp_nonce_field( 'save_settings', 'yoast_ga_nonce', null, false );
		}

		/**
		 * Return the form end tag and the submit button
		 *
		 * @param string $button_label
		 * @param string $name
		 *
		 * @return null|string
		 */
		public function end_form( $button_label = 'Save changes', $name = 'submit' ) {
			$output = null;
			$output .= '<div class="ga-form ga-form-input">';
			$output .= '<input type="submit" name="ga-form-' . $name . '" value="' . $button_label . '" class="button button-primary ga-form-submit" id="yoast-ga-form-submit-' . $this->form_namespace . '">';
			$output .= '</div></form>';

			return $output;
		}

		/**
		 * Create a input form element with our labels and wrap them
		 *
		 * @param string      $type
		 * @param null|string $title
		 * @param null|string $name
		 * @param null|string $text_label
		 * @param null|string $description
		 *
		 * @return null|string
		 */
		public function input( $type = 'text', $title = null, $name = null, $text_label = null, $description = null ) {
			$input = null;
			$id    = str_replace( '[', '-', $name );
			$id    = str_replace( ']', '', $id );

			// Catch a notice if the option doesn't exist, yet
			if ( ! isset( $this->options[$name] ) ) {
				$this->options[$name] = '';
			}

			$input .= '<div class="ga-form ga-form-input">';
			if ( ! is_null( $title ) ) {
				$input .= '<label class="ga-form ga-form-' . $type . '-label ga-form-label-left" id="yoast-ga-form-label-' . $type . '-' . $this->form_namespace . '-' . $id . '" />' . $title . ':</label>';
			}

			if ( $type == 'checkbox' && $this->options[$name] == 1 ) {
				$input .= '<input type="' . $type . '" class="ga-form ga-form-checkbox" id="yoast-ga-form-' . $type . '-' . $this->form_namespace . '-' . $id . '" name="' . $name . '" value="1" checked="checked" />';
			} elseif ( $type == 'checkbox' ) {
				$input .= '<input type="' . $type . '" class="ga-form ga-form-checkbox" id="yoast-ga-form-' . $type . '-' . $this->form_namespace . '-' . $id . '" name="' . $name . '" value="1" />';
			} else {
				$input .= '<input type="' . $type . '" class="ga-form ga-form-' . $type . '" id="yoast-ga-form-' . $type . '-' . $this->form_namespace . '-' . $id . '" name="' . $name . '" value="' . stripslashes( $this->options[$name] ) . '" />';
			}

			if ( ! is_null( $text_label ) ) {
				$input .= '<label class="ga-form ga-form-' . $type . '-label" id="yoast-ga-form-label-' . $type . '-textlabel-' . $this->form_namespace . '-' . $id . '" for="yoast-ga-form-' . $type . '-' . $this->form_namespace . '-' . $id . '" />' . $text_label . '</label>';
			}

			// If we get a description, append it to this select field in a new row
			if ( ! is_null( $description ) ) {
				$input .= $this->show_help( $id, $description );
			}

			$input .= '</div>';

			return $input;
		}

		/**
		 * Show a question mark with help
		 *
		 * @param string $id
		 * @param string $description
		 *
		 * @return string
		 */
		private function show_help( $id, $description ) {
			$help = '<img src="' . plugins_url( 'assets/img/question-mark.png', GAWP_FILE ) . '" class="alignleft yoast_help" id="' . esc_attr( $id . 'help' ) . '" alt="' . esc_attr( $description ) . '" />';

			return $help;
		}

		/**
		 * Generate a select box
		 *
		 * @param string      $title
		 * @param string      $name
		 * @param array       $values
		 * @param null|string $description
		 * @param bool        $multiple
		 * @param string      $empty_text
		 *
		 * @return null|string
		 */
		public function select( $title, $name, $values, $description = null, $multiple = false, $empty_text = null ) {
			$select = null;
			$id     = str_replace( '[', '-', $name );
			$id     = str_replace( ']', '', $id );

			// Catch a notice if the option doesn't exist, yet
			if ( ! isset( $this->options[$name] ) ) {
				$this->options[$name] = '';
			}

			$select .= '<div class="ga-form ga-form-input">';
			if ( ! is_null( $title ) ) {
				$select .= '<label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-label-select-' . $this->form_namespace . '-' . $id . '" />' . $title . ':</label>';
			}

			if ( $multiple ) {
				$select .= '<select multiple name="' . $name . '[]" id="yoast-ga-form-select-' . $this->form_namespace . '-' . $id . '" class="ga-multiple">';
			} else {
				$select .= '<select data-placeholder="' . $empty_text . '" name="' . $name . '" id="yoast-ga-form-select-' . $this->form_namespace . '-' . $id . '">';
				if ( !is_null( $empty_text ) ) {
					$select .= '<option></option>';
				}
			}
			if ( count( $values ) >= 1 ) {

				foreach ( $values as $optgroup => $value ) {

					if( !empty($value['options'])) {
						$select .= '<optgroup label="' . $optgroup . '">';

						foreach($value['options'] AS $option) {
							$select .= $this->option($name, $option);
						}

						$select .= '</optgroup>';

					} else {
						$select .= $this->option($name, $value);
					}
				}
			}
			$select .= '</select>';

			if ( ! is_null( $description ) ) {
				$select .= $this->show_help( $id, $description );
			}

			$select .= '</div>';

			return $select;
		}

		/**
		 * Parsing a option string for select
		 *
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		private function option($name, $value) {
			if ( is_array( $this->options[$name] ) ) {
				if ( in_array( $value['id'], $this->options[$name] ) ) {
					return  '<option value="' . $value['id'] . '" selected="selected">' . stripslashes( $value['name'] ) . '</option>';
				} else {
					return '<option value="' . $value['id'] . '">' . stripslashes( $value['name'] ) . '</option>';
				}
			} else {
				return '<option value="' . $value['id'] . '" ' . selected( $this->options[$name], $value['id'], false ) . '>' . stripslashes( $value['name'] ) . '</option>';
			}
		}

		/**
		 * Will parse the optgroups.
		 *
		 * @param array $values
		 *
		 * @return array
		 */
		public function parse_optgroups($values) {

			$optgroups = array();
			foreach($values AS $key => $value) {
				if(empty($value['parent_name'])) {
					$current = $value;
				} else {
					$optgroups[$value['parent_name']]['options'][] = $current;
				}
			}

			return $optgroups;
		}

		/**
		 * Generate a textarea field
		 *
		 * @param string      $title
		 * @param string      $name
		 * @param null|string $description
		 *
		 * @return null|string
		 */
		public function textarea( $title, $name, $description = null ) {
			$text = null;
			$id   = $this->option_prefix . '_' . $name;

			// Catch a notice if the option doesn't exist, yet
			if ( ! isset( $this->options[$name] ) ) {
				$this->options[$name] = '';
			}

			$text .= '<div class="ga-form ga-form-input">';
			if ( ! is_null( $title ) ) {
				$text .= '<label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-label-select-' . $this->form_namespace . '-' . $id . '" />' . __( $title, 'google-analytics-for-wordpress' ) . ':</label>';
			}
			$text .= '<textarea rows="5" cols="60" name="' . $name . '" id="yoast-ga-form-textarea-' . $this->form_namespace . '-' . $id . '">' . stripslashes( $this->options[$name] ) . '</textarea>';

			if ( ! is_null( $description ) ) {
				$text .= $this->show_help( $id, $description );
			}

			$text .= '</div>';

			return $text;
		}

		/**
		 * Get the Google Analytics profiles which are in this google account
		 *
		 * @return array
		 */
		public function get_profiles() {
			$return           = array();
			$google_analytics = Yoast_Google_Analytics::instance();
			if ( $google_analytics->has_token() ) {
				$return = $google_analytics->get_profiles();
			}

			return $return;
		}


		/**
		 * Checks if there is a callback or reauth to get token from Google Analytics api
		 */
		private function connect_with_google_analytics() {

			if ( isset( $_REQUEST['ga_oauth_callback'] ) ) {

				Yoast_Google_Analytics::instance()->authenticate( $_REQUEST['oauth_token'], $_REQUEST['oauth_verifier'] );

				wp_redirect( menu_page_url( 'yst_ga_settings', false ) );
				exit;
			}

			if ( ! empty ( $_GET['reauth'] ) ) {
				$authorize_url = Yoast_Google_Analytics::instance()->authenticate();

				delete_option( 'yst_ga_accounts' );
				delete_option( 'yst_ga_response' );

				wp_redirect( $authorize_url );
				exit;
			}
		}

		/**
		 * Get the current GA profile
		 *
		 * @return null
		 */
		private function get_current_profile() {
			$current_profile = null;
			foreach ( $this->get_profiles() as $profile ) {
				if ( ! empty( $profile['id'] ) && $profile['id'] == $this->options['analytics_profile'] ) {
					$current_profile = $profile['profile_id'];
				}
			}

			return $current_profile;
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
					'name' => $name['name'],
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
				0 => array( 'id' => 'event', 'name' => 'Event' ),
				1 => array( 'id' => 'pageview', 'name' => 'Pageview' ),
			);
		}

		/**
		 * Get options for the track full url or links setting
		 *
		 * @return array
		 */
		public function get_track_full_url() {
			return array(
				0 => array( 'id' => 'domain', 'name' => 'Just the domain' ),
				1 => array( 'id' => 'full_links', 'name' => 'Full links' ),
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
					'desc'   => __( 'The premium version of Google Analytics for WordPress with more features &amp; support.', 'google-analytics-for-wordpress' ),
					'status' => 'uninstalled',
				),
				'ecommerce'  => (object) array(
					'url'    => 'https://yoast.com/wordpress/plugins/ga-ecommerce/',
					'title'  => __( 'Google Analytics', 'google-analytics-for-wordpress' ) . '<br />' . __( 'E-Commerce tracking', 'google-analytics-for-wordpress' ),
					'desc'   => __( 'Track your E-Commerce data and transactions with this E-Commerce extension for Google Analytics.', 'google-analytics-for-wordpress' ),
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

}