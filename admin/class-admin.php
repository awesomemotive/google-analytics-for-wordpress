<?php
/**
 * This class is for the backend, extendable for all child classes
 */

if ( ! class_exists( 'Yoast_GA_Admin' ) ) {

	class Yoast_GA_Admin {

		private $form_namespace;

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
		public function enqueue_scripts() {
			wp_enqueue_script( 'yoast_ga_admin', GAWP_URL . 'js/yoast_ga_admin.js' );
		}

		/**
		 * Add the styles in the admin head
		 *
		 * @todo add minified CSS files
		 */
		public function enqueue_styles() {
			wp_enqueue_style( 'yoast_ga_styles', GAWP_URL . 'css/yoast_ga_styles.css' );
		}

		/**
		 * Load the page of a menu item in the GA plugin
		 */
		public function load_page() {

			require_once GAWP_PATH . 'admin/class-admin-ga-js.php';
			$ga_universal = false; // @todo get option if universal is enabled
			if ( $ga_universal ) {
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

		/**
		 * Create a form element to init a form
		 *
		 * @param $namespace
		 *
		 * @return string
		 */
		public function create_form( $namespace ) {
			$this->form_namespace = $namespace;

			return '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" id="yoast-ga-form-' . $this->form_namespace . '" class="yoast_ga_form">';
		}

		/**
		 * Return the form end tag and the submit button
		 *
		 * @param string $button_label
		 *
		 * @return null|string
		 */
		public function end_form( $button_label = "Save changes" ) {
			$output = NULL;
			$output .= '<div class="ga-form ga-form-input">';
			$output .= '<input type="submit" name="submit" value="' . $button_label . '" class="button button-primary ga-form-submit" id="yoast-ga-form-submit-' . $this->form_namespace . '">';
			$output .= '</div></form>';

			return $output;
		}

		/**
		 * Create a input form element with our labels and wrap them
		 *
		 * @param string $type
		 * @param null   $title
		 * @param null   $name
		 * @param null   $text_label
		 * @param int    $value
		 * @param bool   $checked
		 * @param null   $description
		 *
		 * @return null|string
		 */
		public function input( $type = 'text', $title = NULL, $name = NULL, $text_label = NULL, $value = 1, $checked = false, $description = NULL ) {
			$input = NULL;
			$input .= '<div class="ga-form ga-form-input">';
			if ( ! is_null( $title ) ) {
				$input .= '<label class="ga-form ga-form-' . $type . '-label ga-form-label-left" id="yoast-ga-form-label-' . $type . '-' . $this->form_namespace . '-' . $name . '" />' . __( $title, 'google-analytics-for-wordpress' ) . ':</label>';
			}

			if ( $checked ) {
				$input .= '<input type="' . $type . '" class="ga-form ga-form-checkbox" id="yoast-ga-form-' . $type . '-' . $this->form_namespace . '-' . $name . '" name="' . $name . '" value="' . $value . '" checked="checked" />';
			} else {
				$input .= '<input type="' . $type . '" class="ga-form ga-form-checkbox" id="yoast-ga-form-' . $type . '-' . $this->form_namespace . '-' . $name . '" name="' . $name . '" value="' . $value . '" />';
			}

			if ( ! is_null( $text_label ) ) {
				$input .= '<label class="ga-form ga-form-' . $type . '-label" id="yoast-ga-form-label-' . $type . '-textlabel-' . $this->form_namespace . '-' . $name . '" for="yoast-ga-form-' . $type . '-' . $this->form_namespace . '-' . $name . '" />' . __( $text_label, 'google-analytics-for-wordpress' ) . '</label>';
			}

			$input .= '</div>';

			// If we get a description, append it to this select field in a new row
			if ( ! is_null( $description ) ) {
				$input .= '<div class="ga-form ga-form-input">';
				$input .= '<label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-description-select-' . $this->form_namespace . '-' . $name . '" />&nbsp;</label>';
				$input .= '<span class="ga-form ga-form-description">' . __( $description, 'google-analytics-for-wordpress' ) . '</span>';
				$input .= '</div>';
			}

			return $input;
		}

		/**
		 * Generate a select box
		 *
		 * @param      $title
		 * @param      $name
		 * @param      $values
		 * @param      $selected
		 * @param null $description
		 *
		 * @return null|string
		 */
		public function select( $title, $name, $values, $selected, $description = NULL ) {
			$select = NULL;
			$select .= '<div class="ga-form ga-form-input">';
			if ( ! is_null( $title ) ) {
				$select .= '<label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-label-select-' . $this->form_namespace . '-' . $name . '" />' . __( $title, 'google-analytics-for-wordpress' ) . ':</label>';
			}
			$select .= '<select name="' . $name . '" id="yoast-ga-form-select-' . $this->form_namespace . '-' . $name . '">';
			if ( count( $values ) >= 1 ) {
				foreach ( $values as $value ) {
					$select .= '<option value="' . $value['id'] . '" ' . selected( $selected, $value['id'], false ) . '>' . $value['name'] . '</option>';
				}
			}
			$select .= '</select>';
			$select .= '</div>';

			// If we get a description, append it to this select field in a new row
			if ( ! is_null( $description ) ) {
				$select .= '<div class="ga-form ga-form-input">';
				$select .= '<label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-description-select-' . $this->form_namespace . '-' . $name . '" />&nbsp;</label>';
				$select .= '<span class="ga-form ga-form-description">' . __( $description, 'google-analytics-for-wordpress' ) . '</span>';
				$select .= '</div>';
			}

			return $select;
		}

		/**
		 * Generate a textarea field
		 *
		 * @param        $title
		 * @param        $name
		 * @param string $value
		 * @param null   $description
		 *
		 * @return null|string
		 */
		public function textarea( $title, $name, $value = '', $description = NULL ) {
			$text = NULL;
			$text .= '<div class="ga-form ga-form-input">';
			if ( ! is_null( $title ) ) {
				$text .= '<label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-label-select-' . $this->form_namespace . '-' . $name . '" />' . __( $title, 'google-analytics-for-wordpress' ) . ':</label>';
			}
			$text .= '<textarea rows="5" cols="60" name="' . $name . '" id="yoast-ga-form-textarea-' . $this->form_namespace . '-' . $name . '">' . $value . '</textarea>';
			$text .= '</div>';

			// If we get a description, append it to this select field in a new row
			if ( ! is_null( $description ) ) {
				$text .= '<div class="ga-form ga-form-input">';
				$text .= '<label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-description-select-' . $this->form_namespace . '-' . $name . '" />&nbsp;</label>';
				$text .= '<span class="ga-form ga-form-description">' . __( $description, 'google-analytics-for-wordpress' ) . '</span>';
				$text .= '</div>';
			}

			return $text;
		}

		/**
		 * Get the Google Analytics profiles which are in this google account
		 * @return array
		 * @todo OAuth connection to Google.com?
		 */
		public function get_profiles() {
			return array(
				0 => array( 'id' => '1234', 'ua_code' => 'UA-317889-17', 'name' => 'Yoast.com' ),
				1 => array( 'id' => '1432', 'ua_code' => 'UA-317889-18', 'name' => 'Yoast.com' )
			);
		}

		/**
		 * Get the user roles of this WordPress blog
		 * @return array
		 */
		public function get_userroles() {
			global $wp_roles;

			$all_roles      = $wp_roles->roles;
			$roles          = array();
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
		 * @return array
		 */
		public function track_download_types() {
			return array(
				0 => array( 'id' => 'event', 'name' => 'Event' ),
				1 => array( 'id' => 'pageview', 'name' => 'Pageview' ),
			);
		}

		public function get_track_full_url(){
			return array(
				0 => array( 'id' => 0, 'name' => 'Just the domain'),
				1 => array( 'id' => 1, 'name' => 'Full links'),
			);
		}

		/**
		 * Render the admin page head for the GA Plugin
		 */
		public function content_head() {
			require( "views/content_head.php" );
		}

		/**
		 * Render the admin page footer with sidebar for the GA Plugin
		 */
		public function content_footer() {
			$banners   = array();
			$banners[] = array(
				'url'    => 'https://yoast.com',
				'banner' => GAWP_URL . 'img/banner-website-review.png',
				'title'  => 'Get a website review by Yoast'
			);

			if ( class_exists( 'Woocommerce' ) ) {
				$banners[] = array(
					'url'    => 'https://yoast.com',
					'banner' => GAWP_URL . 'img/banner-local-seo.png',
					'title'  => 'Get WooCommerce integrated in your Analytics'
				);
			}

			shuffle( $banners );

			require( "views/content_footer.php" );
		}

	}

	global $yoast_ga_admin;
	$yoast_ga_admin = new Yoast_GA_Admin;
}