<?php
/**
 * This class is for the backend, extendable for all child classes
 */

require_once plugin_dir_path( __FILE__ ) . '/wp-gdata/wp-gdata.php';

if ( ! class_exists( 'Yoast_GA_Admin' ) ) {

	class Yoast_GA_Admin {

		private $form_namespace;

		public $options;

		private $form_prefix = 'ga_general';

		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'init_ga' ) );
			add_action( 'admin_init', array( $this, 'init_settings' ) );
		}

		/**
		 * Init function when the plugin is loaded
		 */
		public function init_ga() {
			add_action( 'admin_menu', array( $this, 'create_menu' ), 5 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			add_filter( 'plugin_action_links_' . plugin_basename( GAWP_FILE ), array( $this, 'add_action_links' ), 10, 2 );
		}

		/**
		 * Init function for the settings of GA
		 */
		public function init_settings() {
			$this->options = get_option( 'yst_ga' );

			if ( false == $this->options ) {
				add_option( 'yst_ga', $this->default_ga_values() );
				$this->options = get_option( 'yst_ga' );
			}

			global $Yoast_GA_Options;
			if ( is_null( $Yoast_GA_Options->get_tracking_code() ) ) {
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

					add_settings_error(
						'yoast_google_analytics',
						'yoast_google_analytics',
						__( 'Settings saved!', 'google-analytics-for-wordpress' ),
						'updated'
					);
				}
			}

			$this->connect_with_google_analytics();
		}

		/**
		 * Throw a warning if no UA code is set.
		 */
		public function config_warning() {
			echo '<div class="error"><p>' . sprintf( __( 'Please configure your %sGoogle Analytics settings%s!', 'google-analytics-for-wordpress' ), '<a href="' . admin_url( 'admin.php?page=yst_ga_settings' ) . '">', '</a>' ) . '</p></div>';
		}

		/**
		 * Set the default GA settings here
		 * @return array
		 */
		public function default_ga_values() {
			return array(
				$this->form_prefix => array(
					'analytics_profile'          => null,
					'manual_ua_code'             => 0,
					'manual_ua_code_field'       => null,
					'track_internal_as_outbound' => null,
					'track_internal_as_label'    => null,
					'track_outbound'             => 0,
					'anonymous_data'             => 0,
					'enable_universal'           => 0,
					'demographics'               => 0,
					'ignore_users'               => array( 'editor' ),
					'anonymize_ips'              => null,
					'track_download_as'          => 'event',
					'extensions_of_files'        => 'doc,exe,js,pdf,ppt,tgz,zip,xls',
					'track_full_url'             => 'domain',
					'subdomain_tracking'         => null,
					'tag_links_in_rss'           => 0,
					'allow_anchor'               => 0,
					'add_allow_linker'           => 0,
					'custom_code'                => null,
					'debug_mode'                 => 0,
					'firebug_lite'               => 0,
				)
			);
		}

		/**
		 * Return the setting by name
		 *
		 * @param $name
		 *
		 * @return null
		 */
		public function get_setting( $name ) {

			if ( isset( $this->options[ $this->form_prefix ][ $name ] ) ) {
				return $this->options[ $this->form_prefix ][ $name ];
			} else {
				return null;
			}
		}

		/**
		 * This function saves the settings in the option field and returns a wp success message on success
		 *
		 * @param $data
		 */
		public function save_settings( $data ) {
			foreach ( $data as $key => $value ) {
				$this->options[ $this->form_prefix ][ $key ] = $value;
			}

			// Check checkboxes, on a uncheck they won't be posted to this function
			$defaults = $this->default_ga_values();
			foreach ( $defaults['ga_general'] as $key => $value ) {
				if ( ! isset( $data[ $key ] ) ) {
					$this->options[ $this->form_prefix ][ $key ] = $value;
				}
			}

			if ( update_option( 'yst_ga', $this->options ) ) {
				// Success!
			} else {
				// Fail..
			}

		}

		/**
		 * Get the singleton instance of this class
		 *
		 * @return object
		 */
		public static function get_instance() {
			if ( ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Add a link to the settings page to the plugins list
		 *
		 * @staticvar string $this_plugin holds the directory & filename for the plugin
		 *
		 * @param    array  $links array of links for the plugins, adapted when the current plugin is found.
		 * @param    string $file  the filename for the current plugin, which the filter loops through.
		 *
		 * @return    array    $links
		 */
		function add_action_links( $links, $file ) {
			// add link to knowledgebase
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
		public function create_menu( $param = null ) {
			// Add main page
			add_menu_page( __( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( 'General Settings', 'google-analytics-for-wordpress' ), __( 'Analytics', 'google-analytics-for-wordpress' ), 'manage_options', 'yst_ga_dashboard', array(
				$this,
				'load_page'
			), plugins_url( 'img/yoast-icon.png', GAWP_FILE ), '2.00013467543' );

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

			$action = $_SERVER['PHP_SELF'];
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
		public function end_form( $button_label = "Save changes", $name = 'submit' ) {
			$output = null;
			$output .= '<div class="ga-form ga-form-input">';
			$output .= '<input type="submit" name="ga-form-' . $name . '" value="' . $button_label . '" class="button button-primary ga-form-submit" id="yoast-ga-form-submit-' . $this->form_namespace . '">';
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
		 * @param null   $description
		 *
		 * @return null|string
		 */
		public function input( $type = 'text', $title = null, $name = null, $text_label = null, $description = null ) {
			$input = null;
			$id    = str_replace( '[', '-', $name );
			$id    = str_replace( ']', '', $id );

			$input .= '<div class="ga-form ga-form-input">';
			if ( ! is_null( $title ) ) {
				$input .= '<label class="ga-form ga-form-' . $type . '-label ga-form-label-left" id="yoast-ga-form-label-' . $type . '-' . $this->form_namespace . '-' . $id . '" />' . $title . ':</label>';
			}

			if ( $type == 'checkbox' && $this->get_setting( $name ) == 1 ) {
				$input .= '<input type="' . $type . '" class="ga-form ga-form-checkbox" id="yoast-ga-form-' . $type . '-' . $this->form_namespace . '-' . $id . '" name="' . $name . '" value="1" checked="checked" />';
			} elseif ( $type == 'checkbox' ) {
				$input .= '<input type="' . $type . '" class="ga-form ga-form-checkbox" id="yoast-ga-form-' . $type . '-' . $this->form_namespace . '-' . $id . '" name="' . $name . '" value="1" />';
			} else {
				$input .= '<input type="' . $type . '" class="ga-form ga-form-' . $type . '" id="yoast-ga-form-' . $type . '-' . $this->form_namespace . '-' . $id . '" name="' . $name . '" value="' . stripslashes( $this->get_setting( $name ) ) . '" />';
			}

			if ( ! is_null( $text_label ) ) {
				$input .= '<label class="ga-form ga-form-' . $type . '-label" id="yoast-ga-form-label-' . $type . '-textlabel-' . $this->form_namespace . '-' . $id . '" for="yoast-ga-form-' . $type . '-' . $this->form_namespace . '-' . $id . '" />' . __( $text_label, 'google-analytics-for-wordpress' ) . '</label>';
			}

			$input .= '</div>';

			// If we get a description, append it to this select field in a new row
			if ( ! is_null( $description ) ) {
				$input .= '<div class="ga-form ga-form-input">';
				$input .= '<label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-description-select-' . $this->form_namespace . '-' . $id . '" />&nbsp;</label>';
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
		 * @param null $description
		 * @param bool $multiple
		 *
		 * @return null|string
		 */
		public function select( $title, $name, $values, $description = null, $multiple = false ) {
			$select = null;
			$id     = str_replace( '[', '-', $name );
			$id     = str_replace( ']', '', $id );
			$select .= '<div class="ga-form ga-form-input">';
			if ( ! is_null( $title ) ) {
				$select .= '<label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-label-select-' . $this->form_namespace . '-' . $id . '" />' . __( $title, 'google-analytics-for-wordpress' ) . ':</label>';
			}

			if ( $multiple ) {
				$select .= '<select multiple name="' . $name . '[]" id="yoast-ga-form-select-' . $this->form_namespace . '-' . $id . '" class="ga-multiple">';
			} else {
				$select .= '<select name="' . $name . '" id="yoast-ga-form-select-' . $this->form_namespace . '-' . $id . '">';
			}
			if ( count( $values ) >= 1 ) {
				foreach ( $values as $value ) {
					if ( is_array( $this->get_setting( $name ) ) ) {
						if ( in_array( $value['id'], $this->get_setting( $name ) ) ) {
							$select .= '<option value="' . $value['id'] . '" selected="selected">' . stripslashes( $value['name'] ) . '</option>';
						} else {
							$select .= '<option value="' . $value['id'] . '">' . stripslashes( $value['name'] ) . '</option>';
						}
					} else {
						$select .= '<option value="' . $value['id'] . '" ' . selected( $this->get_setting( $name ), $value['id'], false ) . '>' . stripslashes( $value['name'] ) . '</option>';
					}
				}
			}
			$select .= '</select>';
			$select .= '</div>';

			// If we get a description, append it to this select field in a new row
			if ( ! is_null( $description ) ) {
				$select .= '<div class="ga-form ga-form-input">';
				$select .= '<label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-description-select-' . $this->form_namespace . '-' . $id . '" />&nbsp;</label>';
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
		 * @param null   $description
		 *
		 * @return null|string
		 */
		public function textarea( $title, $name, $description = null ) {
			$text = null;
			$id   = $this->form_prefix . '_' . $name;
			$text .= '<div class="ga-form ga-form-input">';
			if ( ! is_null( $title ) ) {
				$text .= '<label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-label-select-' . $this->form_namespace . '-' . $id . '" />' . __( $title, 'google-analytics-for-wordpress' ) . ':</label>';
			}
			$text .= '<textarea rows="5" cols="60" name="' . $name . '" id="yoast-ga-form-textarea-' . $this->form_namespace . '-' . $id . '">' . stripslashes( $this->get_setting( $name ) ) . '</textarea>';
			$text .= '</div>';

			// If we get a description, append it to this select field in a new row
			if ( ! is_null( $description ) ) {
				$text .= '<div class="ga-form ga-form-input">';
				$text .= '<label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-description-select-' . $this->form_namespace . '-' . $id . '" />&nbsp;</label>';
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

			// Enqueue the chosen css file
			wp_enqueue_style( 'chosen_css', plugins_url( 'js/chosen.css', GAWP_FILE ) );

			// Eqneue the chosen js file
			wp_enqueue_script( 'chosen_js', plugins_url( 'js/chosen.jquery.min.js', GAWP_FILE ), array(), false, true );

			$option_name = 'yst_ga_api';
			$options     = get_option( $option_name );
			$return      = array();

			if ( ! empty ( $options['ga_token'] ) ) {
				$args         = array(
					'scope'              => 'https://www.googleapis.com/auth/analytics.readonly',
					'xoauth_displayname' => 'Google Analytics for WordPress by Yoast',
				);
				$access_token = $options['ga_oauth']['access_token'];
				$gdata        = new WP_Gdata( $args, $access_token['oauth_token'], $access_token['oauth_token_secret'] );

				$response  = $gdata->get( 'https://www.googleapis.com/analytics/v2.4/management/accounts/~all/webproperties/~all/profiles' );
				$http_code = wp_remote_retrieve_response_code( $response );
				$response  = wp_remote_retrieve_body( $response );

				if ( $http_code == 200 ) {
					$options['ga_api_response'] = array(
						'response' => array( 'code' => $http_code ),
						'body'     => $response
					);
					update_option( $option_name, $options );
				} else {
					return $return;
				}

				try {
					$xml_reader = new SimpleXMLElement( $options['ga_api_response']['body'] );

					if ( ! empty( $xml_reader->entry ) ) {

						$ga_accounts = array();

						// Check whether the feed output is the new one, first set, or the old one, second set.
						if ( $xml_reader->link['href'] == 'https://www.googleapis.com/analytics/v2.4/management/accounts/~all/webproperties/~all/profiles' ) {
							foreach ( $xml_reader->entry AS $entry ) {
								$ns         = $entry->getNamespaces( true );
								$properties = $entry->children( $ns['dxp'] )->property;

								if ( isset ( $properties[1]->attributes()->value ) ) {
									$ua = (string) trim( $properties[1]->attributes()->value );
								}

								if ( isset ( $properties[2]->attributes()->value ) ) {
									$title = (string) trim( $properties[2]->attributes()->value );
								}

								if ( ! empty( $ua ) && ! empty( $title ) ) {
									$ga_accounts[] = array(
										'ua'    => $ua,
										'title' => $title,
									);
								}

							}
						} else {
							if ( $xml_reader->link['href'] == 'https://www.google.com/analytics/feeds/accounts/default' ) {
								foreach ( $xml_reader->entry AS $entry ) {
									$ns         = $entry->getNamespaces( true );
									$properties = $entry->children( $ns['dxp'] )->property;

									if ( isset ( $properties[3]->attributes()->value ) ) {
										$ua = (string) trim( $properties[3]->attributes()->value );
									}

									if ( isset ( $properties[2]->attributes()->value ) ) {
										$title = (string) trim( $properties[2]->attributes()->value );
									}

									if ( ! empty( $ua ) && ! empty( $title ) ) {
										$ga_accounts[] = array(
											'ua'    => $ua,
											'title' => $title,
										);
									}

								}
							}
						}

						if ( is_array( $ga_accounts ) ) {
							usort( $ga_accounts, array( $this, 'sort_profiles' ) );
						}

						foreach ( $ga_accounts as $key => $ga_account ) {
							$return[] = array(
								'id'   => $ga_account['ua'],
								'name' => $ga_account['title'] . ' (' . $ga_account['ua'] . ')',
							);
						}
					}
				} catch ( Exception $e ) {

				}
			}

			return $return;
		}

		/**
		 * Sorting the array in alphabetic order
		 *
		 * @param $a
		 * @param $b
		 *
		 * @return int
		 */
		public function sort_profiles( $a, $b ) {
			return strcmp( $a["title"], $b["title"] );
		}

		/**
		 * Checks if there is a callback or reauth to get token from Google Analytics api
		 *
		 */
		private function connect_with_google_analytics() {

			$option_name = 'yst_ga_api';

			if ( isset( $_REQUEST['ga_oauth_callback'] ) ) {
				$o = get_option( $option_name );
				if ( isset( $o['ga_oauth']['oauth_token'] ) && $o['ga_oauth']['oauth_token'] == $_REQUEST['oauth_token'] ) {
					$gdata = new WP_GData(
						array(
							'scope'              => 'https://www.google.com/analytics/feeds/',
							'xoauth_displayname' => 'Google Analytics by Yoast'
						),
						$o['ga_oauth']['oauth_token'],
						$o['ga_oauth']['oauth_token_secret']
					);

					$o['ga_oauth']['access_token'] = $gdata->get_access_token( $_REQUEST['oauth_verifier'] );
					unset( $o['ga_oauth']['oauth_token'] );
					unset( $o['ga_oauth']['oauth_token_secret'] );
					$o['ga_token'] = $o['ga_oauth']['access_token']['oauth_token'];
				}

				update_option( $option_name, $o );

				wp_redirect( menu_page_url( 'yst_ga_settings', false ) );
				exit;
			}

			if ( ! empty ( $_GET['reauth'] ) ) {
				$gdata = new WP_GData(
					array(
						'scope'              => 'https://www.google.com/analytics/feeds/',
						'xoauth_displayname' => 'Google Analytics by Yoast'
					)
				);

				$oauth_callback = add_query_arg( array( 'ga_oauth_callback' => 1 ), menu_page_url( 'yst_ga_settings', false ) );
				$request_token  = $gdata->get_request_token( $oauth_callback );

				$options = get_option( $option_name );

				if ( is_array( $options ) ) {
					unset( $options['ga_token'] );
					if ( is_array( $options['ga_oauth'] ) ) {
						unset( $options['ga_oauth']['access_token'] );
					}
				}

				$options['ga_oauth']['oauth_token']        = $request_token['oauth_token'];
				$options['ga_oauth']['oauth_token_secret'] = $request_token['oauth_token_secret'];
				update_option( $option_name, $options );

				wp_redirect( $gdata->get_authorize_url( $request_token ) );
				exit;
			}
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

		/**
		 * Get options for the track full url or links setting
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
			require( "views/content_head.php" );
		}

		/**
		 * Render the admin page footer with sidebar for the GA Plugin
		 */
		public function content_footer() {
			$banners   = array();
			$banners[] = array(
				'url'    => 'https://yoast.com/hire-us/website-review/#utm_medium=banner&utm_source=gawp-config&utm_campaign=wpgaplugin',
				'banner' => GAWP_URL . 'img/banner-website-review.png',
				'title'  => 'Get a website review by Yoast'
			);
			$banners[] = array(
				'url'    => 'https://yoast.com/wordpress/plugins/seo-premium/#utm_medium=banner&utm_source=gawp-config&utm_campaign=wpgaplugin',
				'banner' => GAWP_URL . 'img/banner-premium-seo.png',
				'title'  => 'Get WordPress SEO premium'
			);
			$banners[] = array(
				'url'    => 'https://yoast.com/ebook-optimize-wordpress-site/#utm_medium=banner&utm_source=gawp-config&utm_campaign=wpgaplugin',
				'banner' => GAWP_URL . 'img/eBook_261x130.png',
				'title'  => 'Get the Yoast ebook!'
			);
			$banners[] = array(
				'url'    => 'https://yoast.com/wordpress/plugins/local-seo/#utm_medium=banner&utm_source=gawp-config&utm_campaign=wpgaplugin',
				'banner' => GAWP_URL . 'img/banner-local-seo.png',
				'title'  => 'Get WooCommerce integrated in your Analytics'
			);

			shuffle( $banners );

			if ( true == WP_DEBUG ) {
				// Show the debug information if debug is enabled in the wp_config file
				echo '<div id="ga-debug-info" class="postbox"><h3 class="hndle"><span>' . __( 'Debug information', 'google-analytics-for-wordpress' ) . '</span></h3><div class="inside"><pre>';
				var_dump( $this->options );
				echo '</pre></div></div>';
			}

			require( "views/content_footer.php" );
		}

	}

	global $yoast_ga_admin;
	$yoast_ga_admin = new Yoast_GA_Admin;
}