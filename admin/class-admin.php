<?php
/**
 * This class is for the backend, extendable for all child classes
 */

if ( ! class_exists( 'Yoast_GA_Admin' ) ) {

	class Yoast_GA_Admin extends Yoast_GA_Options {

		private $form_namespace;

		public function __construct() {
			parent::__construct();

			add_action( 'plugins_loaded', array( $this, 'init_ga' ) );
			add_action( 'admin_init', array( $this, 'init_settings' ) );
		}

		/**
		 * Init function when the plugin is loaded
		 */
		public function init_ga() {
			add_action( 'admin_menu', array( $this, 'create_menu' ), 5 );

			add_filter( 'plugin_action_links_' . plugin_basename( GAWP_FILE ), array( $this, 'add_action_links' ) );
		}

		/**
		 * Init function for the settings of GA
		 */
		public function init_settings() {
			$this->options = $this->get_options();

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
					$this->options[$key] = $value;
				}
			}

			// Check checkboxes, on a uncheck they won't be posted to this function
			$defaults = $this->default_ga_values();
			foreach ( $defaults[$this->option_prefix] as $key => $value ) {
				if ( ! isset( $data[$key] ) ) {
					$this->options[$key] = $value;
				}
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
		 * Create the admin menu
		 *
		 * @todo, we need to implement a new icon for this, currently we're using the WP seo icon
		 */
		public function create_menu() {
			/**
			 * Filter: 'wpga_menu_on_top' - Allows filtering of menu location of the GA plugin, if false is returned, it moves to bottom.
			 *
			 * @api book unsigned
			 */
			$on_top = apply_filters( 'wpga_menu_on_top', true );

			// Base 64 encoded SVG image
			$icon_svg = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCIgWw0KCTwhRU5USVRZIG5zX2Zsb3dzICJodHRwOi8vbnMuYWRvYmUuY29tL0Zsb3dzLzEuMC8iPg0KCTwhRU5USVRZIG5zX2V4dGVuZCAiaHR0cDovL25zLmFkb2JlLmNvbS9FeHRlbnNpYmlsaXR5LzEuMC8iPg0KCTwhRU5USVRZIG5zX2FpICJodHRwOi8vbnMuYWRvYmUuY29tL0Fkb2JlSWxsdXN0cmF0b3IvMTAuMC8iPg0KCTwhRU5USVRZIG5zX2dyYXBocyAiaHR0cDovL25zLmFkb2JlLmNvbS9HcmFwaHMvMS4wLyI+DQpdPg0KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYWFnXzEiIHhtbG5zOng9IiZuc19leHRlbmQ7IiB4bWxuczppPSImbnNfYWk7IiB4bWxuczpncmFwaD0iJm5zX2dyYXBoczsiDQoJIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOmE9Imh0dHA6Ly9ucy5hZG9iZS5jb20vQWRvYmVTVkdWaWV3ZXJFeHRlbnNpb25zLzMuMC8iDQoJIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDAgMzEuODkiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDQwIDMxLjg5IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KPHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTQwLDEyLjUyNEM0MCw1LjYwOCwzMS40NjksMCwyMCwwQzguNTMsMCwwLDUuNjA4LDAsMTIuNTI0YzAsNS41Niw1LjI0MywxMC4yNzIsMTMuNTU3LDExLjkwN3YtNC4wNjUNCgljMCwwLDAuMDQtMS0wLjI4LTEuOTJjLTAuMzItMC45MjEtMS43Ni0zLjAwMS0xLjc2LTUuMTIxYzAtMi4xMjEsMi41NjEtOS41NjMsNS4xMjItMTAuNDQ0Yy0wLjQsMS4yMDEtMC4zMiw3LjY4My0wLjMyLDcuNjgzDQoJczEuNCwyLjcyLDQuNjQxLDIuNzJjMy4yNDIsMCw0LjUxMS0xLjc2LDQuNzE1LTIuMmMwLjIwNi0wLjQ0LDAuODQ2LTguNzIzLDAuODQ2LTguNzIzczQuMDgyLDQuNDAyLDMuNjgyLDkuMzYzDQoJYy0wLjQwMSw0Ljk2Mi00LjQ4Miw3LjIwMy02LjEyMiw5LjEyM2MtMS4yODYsMS41MDUtMi4yMjQsMy4xMy0yLjYyOSw0LjE2OGMwLjgwMS0wLjAzNCwxLjU4Ny0wLjA5OCwyLjM2MS0wLjE4NGw5LjE1MSw3LjA1OQ0KCWwtNC44ODQtNy44M0MzNS41MzUsMjIuMTYxLDQwLDE3LjcxMyw0MCwxMi41MjR6Ii8+DQo8L2c+DQo8L3N2Zz4=';

			// Add main page
			add_menu_page( __( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( 'General Settings', 'google-analytics-for-wordpress' ), __( 'Analytics', 'google-analytics-for-wordpress' ), 'manage_options', 'yst_ga_dashboard', array(
				$this,
				'load_page',
			), $icon_svg, $on_top ? '2.00013467543' : '100.00013467543' );

			$this->add_submenu_pages();
		}

		/**
		 * Prepares an array that can be used to add a submenu page to the Google Analytics for Wordpress menu
		 *
		 * @param $submenu_name
		 * @param $font_color
		 *
		 * @return array
		 */
		private function prepare_submenu_page( $submenu_name, $font_color ) {
			$menu_title = __( ucfirst( $submenu_name ), 'google-analytics-for-wordpress' );
			if ( ! empty( $font_color ) ) {
				$menu_title = __( '<span style="color:' . $font_color . '">' . $menu_title . '</span>', 'google-analytics-for-wordpress' );
			}

			$submenu_page = array(
				'parent_slug'      => 'yst_ga_dashboard',
				'page_title'       => __( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( ucfirst( $submenu_name ), 'google-analytics-for-wordpress' ),
				'menu_title'       => $menu_title,
				'capability'       => 'manage_options',
				'menu_slug'        => 'yst_ga_' . $submenu_name,
				'submenu_function' => array( $this, 'load_page' ),
			);

			return $submenu_page;
		}

		/**
		 * Adds a submenu page to the Google Analytics for WordPress menu
		 *
		 * @param $submenu_page
		 */
		private function add_submenu_page( $submenu_page ) {
			$page = add_submenu_page( $submenu_page['parent_slug'], $submenu_page['page_title'], $submenu_page['menu_title'], $submenu_page['capability'], $submenu_page['menu_slug'], $submenu_page['submenu_function'] );
			add_action( 'admin_print_styles-' . $page, array( $this, 'enqueue_styles' ) );
			if ( 'yst_ga_settings' === $submenu_page['menu_slug'] || 'yst_ga_extensions' === $submenu_page['menu_slug'] ) {
				add_action( 'admin_print_styles-' . $page, array( $this, 'enqueue_settings_styles' ) );
				add_action( 'admin_print_scripts-' . $page, array( $this, 'enqueue_scripts' ) );
			}
		}

		/**
		 * Prepares and adds submenu pages to the Google Analytics for Wordpress menu:
		 * - Dashboard
		 * - Settings
		 * - Extensions
		 *
		 * @return array
		 */
		private function add_submenu_pages() {
			/**
			 * Array structure:
			 *
			 * array(
			 *   $submenu_name => $font_color,
			 *   ..,
			 *   ..,
			 * )
			 *
			 * $font_color can be left empty.
			 *
			 */
			$submenu_types = array(
				'dashboard'  => '',
				'settings'   => '',
				'extensions' => '#f18500',
			);

			foreach ( $submenu_types as $submenu_name => $font_color ) {
				$submenu_page = $this->prepare_submenu_page( $submenu_name, $font_color );
				$this->add_submenu_page( $submenu_page );
			}
		}

		/**
		 * Check whether we can include the minified version or not
		 *
		 * @param string $ext
		 *
		 * @return string
		 */
		private function file_ext( $ext ) {
			if ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ) {
				$ext = '.min' . $ext;
			}

			return $ext;
		}

		/**
		 * Add the scripts to the admin head
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'jquery-qtip', $this->plugin_url . 'js/jquery.qtip.min.js', array( 'jquery' ), '1.0.0-RC3', true );

			wp_enqueue_script( 'yoast_ga_admin', $this->plugin_url . 'js/yoast_ga_admin' . $this->file_ext( '.js' ) );

			// Eqneue the chosen js file
			wp_enqueue_script( 'chosen_js', plugins_url( 'js/chosen.jquery.min.js', GAWP_FILE ), array(), false, true );
		}

		/**
		 * Add the styles in the admin head
		 */
		public function enqueue_styles() {
			wp_enqueue_style( 'yoast_ga_styles', $this->plugin_url . 'css/yoast_ga_styles' . $this->file_ext( '.css' ) );
		}

		/**
		 * Enqueues the settings page specific styles
		 */
		public function enqueue_settings_styles() {
			// Enqueue the chosen css file
			wp_enqueue_style( 'chosen_css', $this->plugin_url . 'css/chosen' . $this->file_ext( '.css' ) );
		}

		/**
		 * Load the page of a menu item in the GA plugin
		 */
		public function load_page() {
			global $yoast_ga_admin_ga_js;
			$yoast_ga_admin_ga_js = new Yoast_GA_Admin_GA_JS;

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
			$help = '<img src="' . plugins_url( 'img/question-mark.png', GAWP_FILE ) . '" class="alignleft yoast_help" id="' . esc_attr( $id . 'help' ) . '" alt="' . esc_attr( $description ) . '" />';

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
		 *
		 * @return null|string
		 */
		public function select( $title, $name, $values, $description = null, $multiple = false ) {
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
				$select .= '<select name="' . $name . '" id="yoast-ga-form-select-' . $this->form_namespace . '-' . $id . '">';
			}
			if ( count( $values ) >= 1 ) {
				foreach ( $values as $value ) {
					if ( is_array( $this->options[$name] ) ) {
						if ( in_array( $value['id'], $this->options[$name] ) ) {
							$select .= '<option value="' . $value['id'] . '" selected="selected">' . stripslashes( $value['name'] ) . '</option>';
						} else {
							$select .= '<option value="' . $value['id'] . '">' . stripslashes( $value['name'] ) . '</option>';
						}
					} else {
						$select .= '<option value="' . $value['id'] . '" ' . selected( $this->options[$name], $value['id'], false ) . '>' . stripslashes( $value['name'] ) . '</option>';
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

				wp_redirect( $authorize_url );
				exit;
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
			$banners   = array();
			$banners[] = array(
				'url'    => 'https://yoast.com/hire-us/website-review/#utm_medium=banner&utm_source=gawp-config&utm_campaign=wpgaplugin',
				'banner' => $this->plugin_url . 'img/banner-website-review.png',
				'title'  => 'Get a website review by Yoast',
			);
			$banners[] = array(
				'url'    => 'https://yoast.com/wordpress/plugins/seo-premium/#utm_medium=banner&utm_source=gawp-config&utm_campaign=wpgaplugin',
				'banner' => $this->plugin_url . 'img/banner-premium-seo.png',
				'title'  => 'Get WordPress SEO premium',
			);
			$banners[] = array(
				'url'    => 'https://yoast.com/ebook-optimize-wordpress-site/#utm_medium=banner&utm_source=gawp-config&utm_campaign=wpgaplugin',
				'banner' => $this->plugin_url . 'img/eBook_261x130.png',
				'title'  => 'Get the Yoast ebook!',
			);
			$banners[] = array(
				'url'    => 'https://yoast.com/wordpress/plugins/local-seo/#utm_medium=banner&utm_source=gawp-config&utm_campaign=wpgaplugin',
				'banner' => $this->plugin_url . 'img/banner-local-seo.png',
				'title'  => 'Get WooCommerce integrated in your Analytics',
			);

			shuffle( $banners );

			if ( true == WP_DEBUG ) {
				// Show the debug information if debug is enabled in the wp_config file
				echo '<div id="ga-debug-info" class="postbox"><h3 class="hndle"><span>' . __( 'Debug information', 'google-analytics-for-wordpress' ) . '</span></h3><div class="inside"><pre>';
				var_dump( $this->options );
				echo '</pre></div></div>';
			}

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
					'url'    => 'https://yoast.com/wordpress/plugins/google-analytics/',
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