<?php
/**
 * Google Client admin class.  
 *
 * Handles retrieving whether a particular notice has been dismissed or not,
 * as well as marking a notice as dismissed.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage GA Client
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_GA {

	/**
	 * Holds the GA client object if using oAuth.
	 *
	 * @access public
	 * @since 6.0.0
	 * @var MonsterInsights_GA_Client $client GA client object.
	 */
	public $client;

	/**
	 * Google Profile ID.
	 *
	 * @access public
	 * @since 6.0.0
	 * @var int $profile ID of profile in use.
	 */
	public $profile;

	/**
	 * Google UA code.
	 *
	 * @access public
	 * @since 6.0.0
	 * @var string|false $ua Google UA code for the current profile if valid oAuth in use, else false.
	 */
	public $ua;

	/**
	 * Google profile name.
	 *
	 * @access public
	 * @since 6.0.0
	 * @var string|false $ua Google profile name for the current profile if valid oAuth in use, else false.
	 */
	public $name;

	/**
	 * Status of Google client object.
	 *
	 * @access public
	 * @since 6.0.0
	 * @var string $status Possible values include manual, expired, valid, needs-permissions, blocked and none.
	 */
	public $status;

	/**
	 * oAuth Permissions Version.
	 *
	 * @access public
	 * @since 6.0.0
	 * @var string $oauth_version Version of oAuth permissions granted.
	 */
	public $oauth_version;	

	/**
	 * Holds the base object.
	 *
	 * @access public
	 * @since 6.0.0
	 * @var MonsterInsights $base MonsterInsights Base object.
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 6.0.0
	 */
	public function __construct() {
		// Get object 
		$this->client        = $this->get_client();
		$this->profile       = $this->get_profile();
		$this->ua            = $this->get_ua();
		$this->name          = $this->get_name();
		$this->oauth_version = $this->get_oauth_version();
		$this->status        = $this->get_status();
		$this->base 		 = MonsterInsights();


		// Show any info/error notices
		$this->get_notices();

		// Authentication Actions
		add_action( 'wp_ajax_monsterinsights_google_view',  array( $this, 'google_auth_view' ) );
		add_action( 'wp_ajax_monsterinsights_google_cancel',  array( $this, 'google_cancel' ) );

		add_action( 'admin_init', array( $this, 'deactivate_google' ) ); // Deactivate
	}

	private function get_client() {
		return ! empty( $this->client ) ? $this->client : monsterinsights_create_client();
	}

	private function set_test_client() { 
		$this->client = monsterinsights_create_test_client();
	}

	private function set_client() { 
		$this->client = monsterinsights_create_client();
	}

	/**
	 * Get the current GA profile
	 *
	 * @return null
	 */
	private function get_profile() {
		return monsterinsights_get_option( 'analytics_profile', false );
	}

	private function get_name() {
		return monsterinsights_get_option( 'analytics_profile_name', false );
	}

	private function get_ua() {
		return monsterinsights_get_ua();
	}

	private function get_oauth_version() {
		return monsterinsights_get_option( 'oauth_version', '1.0' );
	}

	private function get_status() {
		$status = 'valid';
		if ( ! empty( $this->profile ) ) {
			// We are using oAuth
			
			$last_run = monsterinsights_get_option( 'cron_last_run', false );
			$failed   = monsterinsights_get_option( 'cron_failed', false );
			$dash_dis = monsterinsights_get_option( 'dashboards_disabled', false );

			// See if issue connecting or expired
			if ( ! $dash_dis && $failed && ( $last_run === false || monsterinsights_hours_between( $last_run ) >= 48 )  ) { 
				$status = 'blocked';
			}

			$access_token = $this->client->get_access_token();

			// Check to make sure access token is there and not expired
			if ( empty( $access_token ) || empty( $access_token['expires'] ) || current_time( 'timestamp' ) >= $access_token['expires'] ) {
				$status = 'expired';
				return $status;
			}

			// See if needs permissions
			if ( version_compare( $this->oauth_version, '1.0', '<' ) ) { 
				$status = 'needs-permissions';
			}
			
		} else if ( ! empty( $this->ua ) ) {
			// We are using manual
			
		} else {
			// We do not have oAuth or manual active
			$status = 'empty';
		}

		return $status;
	}

	private function get_notices() {
		// Notice for no manual or profile GA
		if ( $this->status === 'empty' ) {
			add_action( 'admin_notices', array( $this, 'monsterinsights_show_admin_config_empty_notice' ) );
		}

		$current_page = filter_input( INPUT_GET, 'page' );

		// Only show expired, needs permission, or blocked notices on the MI pages
		// We do this because unlike status empty, these only block reporting, not the frontend
		// tracking from working, and as a result, these are not as urgent. Plus users generally
		// don't like global notices.
		if ( strpos( $current_page, 'monsterinsights' ) === 0 ) {
		
			// Notice for GA Access token expired (needs re-authenticate)
			if ( $this->status === 'expired' ) {
				add_action( 'admin_notices', array( $this, 'monsterinsights_show_admin_config_expired_notice' ) );
			}
			
			// Notice for Needs Permissions
			if ( $this->status === 'needs-permissions' ) {
				add_action( 'admin_notices', array( $this, 'monsterinsights_show_admin_config_needs_permissions_notice' ) );
			}

			// Notice for trouble connecting to Google
			if ( $this->status === 'blocked' ) {
				add_action( 'admin_notices', array( $this, 'monsterinsights_show_admin_config_blocked_notice' ) );
			}
		}
	}

	/**
	 * Used when switching GA profiles, or switching
	 * to/from oAuth <--> manual, or when reauthenticating
	 * or deleting GA profile.
	 *
	 * @return null
	 */
	private function reinitialize() { 
		// Get object 
		$this->client        = $this->get_client();
		$this->profile       = $this->get_profile();
		$this->ua            = $this->get_ua();
		$this->name          = $this->get_name();
		$this->oauth_version = $this->get_oauth_version();
		$this->status        = $this->get_status();

		// Re-get data if possible
		if ( $this->ua === 'valid' ) {
			$this->refresh_dashboard_data();
		}
	}

	public function create_auth_url() {
		return $this->client->createAuthUrl();
	}

	/**
	 * Getting the analytics profiles
	 *
	 * Doing the request to the Google analytics API and if there is a response, parses this response and return its
	 * array
	 *
	 * @return array
	 */
	public function get_profiles() { // @todo: this needs exception handling for a 401 login required		
		$accounts = $this->format_profile_call();
		if ( is_array( $accounts ) ) {
			return $accounts;
		} else {
			return array();
		}
	}

	public function find_selected_profile( $profile_id ) {
		$profiles = $this->get_profiles();
		$found  = array();
		foreach ( $profiles as $account ) {
			foreach ( $account['items'] as $profile ) {
				foreach ( $profile['items'] as $subprofile ) {
					if ( isset( $subprofile['id'] ) && $subprofile['id'] == $profile_id ) {
						$found = array(
							'id'   => $profile_id,
							'ua'   => $subprofile['ua_code'],
							'name' => $subprofile['name'],

						);
						break 3;
					}
				}
			}
		}
		return $found;
	}

	public function save_selected_profile( $profile ) {
		monsterinsights_update_option( 'analytics_profile', $profile['id'] );
		monsterinsights_update_option( 'analytics_profile_code', $profile['ua'] );
		monsterinsights_update_option( 'analytics_profile_name', $profile['name'] );
		monsterinsights_set_client_oauth_version();
	}


	/**
	 * Format the accounts request
	 *
	 * @param array $response
	 *
	 * @return mixed
	 */
	private function format_profile_call() {
		$accounts    = array();
		$start_index = 1;
		$paginate    = false;
		$continue    = true;
		while ( $continue ) {
			$body     = array(
				'max-results'  => 1000,
				'start-index'  => $paginate ? $start_index + 1000 : $start_index,
			);
			if ( $paginate ) {
				$start_index = $start_index + 1000;
			}
			$response = $this->client->do_request( 'https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties/~all/profiles', false, 'GET', $body );
			if ( ! empty( $response ) ) {
				$response = array(
					'response' => array( 'code' => $this->client->get_http_response_code() ),
					'body'     => json_decode( $response->getResponseBody(), true ),
				);
			} else {
				break;
			}
			
			if ( isset( $response['response']['code'] ) && $response['response']['code'] == 200 ) {
				if ( ! empty( $response['body']['items'] ) && is_array( $response['body']['items'] ) ) {
					foreach ( $response['body']['items'] as $item ) {

						// Only deal with web properties, not apps.
						if ( isset( $item['type'] ) && 'WEB' != $item['type'] ) {
							continue;
						}
						
						if ( empty( $accounts[ $item['accountId'] ] ) ) {
							$accounts[ $item['accountId'] ] = array( 
								'id'          => $item['accountId'],
								'ua_code'     => $item['webPropertyId'],
								'parent_name' => $item['websiteUrl'],
								'items'       => array(),
							);
						}

						if ( empty( $accounts[ $item['accountId'] ]['items'][ $item['internalWebPropertyId'] ] ) ) {
							$accounts[ $item['accountId'] ]['items'][ $item['internalWebPropertyId'] ]= array( 
								'id'          => $item['webPropertyId'],
								'name'        => $item['websiteUrl'],
								'items'       => array(),
							);
						}

						if ( empty( $accounts[ $item['accountId'] ]['items'][ $item['internalWebPropertyId'] ]['items'][ $item['id'] ] ) ) {
							$accounts[ $item['accountId'] ]['items'][ $item['internalWebPropertyId'] ]['items'][ $item['id'] ] = array( 
								'name'    => $item['name'] . ' (' . $item['webPropertyId'] . ')',
								'ua_code' => $item['webPropertyId'],
								'id'      => $item['id'],
							);
						}
					}
				}
			}
			if ( isset( $response['body']['totalResults'] ) && $start_index < $response['body']['totalResults'] && ! empty( $response['body']['nextLink'] ) ) {
				$paginate    = true;
			} else {
				$continue   = false;
			}
		}
		return $accounts;
	}

	private function clear_oauth_data() {
		// Delete the stored profiles
		$options = array(
			'analytics_profile_code',
			'analytics_profile',
			'analytics_profile_name',
			'oauth_version',
			'cron_failed',
			'cron_last_run',
		);
		monsterinsights_delete_options( $options );

		// Destroy the data
		$this->base->reports->delete_aggregate_data();
		
		$this->client->clear_data();
	}

	private function clear_manual_data() {
		// Delete the manual ua code
		monsterinsights_delete_option( 'manual_ua_code' );
	}

	public function deactivate_google() {
		// Check if user pressed the deactivate button and nonce is valid
		if ( ! isset( $_POST['monsterinsights-google-deauthenticate-submit'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['monsterinsights-google-authenticated-nonce'], 'monsterinsights-google-authenticated-nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		// Destroy the client
		$this->clear_oauth_data();

		// Refresh the client
		$this->reinitialize();
	}

	public function refresh_dashboard_data( ) {
		// Destroy the data
		$this->base->reports->delete_aggregate_data();
		
		$this->base->reports->run_cron();
	}

	/**
	 * Check if client has a refresh token
	 * @return bool
	 */
	public function has_refresh_token() {
		return $this->client->is_authenticated();
	}

	/**
	 * Doing request to Google Analytics
	 *
	 * This method will do a request to google and get the response code and body from content
	 *
	 * @param string $target_request_url
	 *
	 * @return array|null
	 */
	public function do_request( $target_request_url ) {
		$response = $this->client->do_request( $target_request_url );
		if ( ! empty( $response ) ) {
			return array(
				'response' => array( 'code' => $this->client->get_http_response_code() ),
				'body'     => json_decode( $response->getResponseBody(), true ),
			);
		}
	}
	
	/**
	 * Wrapper for authenticate the client. If authentication code is send it will get and check an access token.
	 *
	 * @param mixed $authentication_code
	 *
	 * @return boolean
	 */
	public function authenticate( $authentication_code = null ) {
		// When authentication again we should clean up some stuff
		monsterinsights_delete_options( array( 'cron_last_run', 'cron_failed' ) );
		return $this->client->authenticate_client( $authentication_code );
	}

	public function google_auth_view() {
		$view   = isset( $_POST['view'] ) && in_array( $_POST['view'], array( 'prestart', 'start', 'enterkey', 'selectprofile', 'done' ) ) ? $_POST['view'] : '';
		$reauth = isset( $_POST['reauth'] ) && $_POST['reauth'] && $_POST['reauth'] !== 'false'  ? true : false;

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			echo esc_html__( 'Permission Denied', 'google-analytics-for-wordpress');
			wp_die();
		}

		// We run the save routines, if required, for a view, and then after that send back the results + next view
		$nextview = array();

		switch ( $view ) {
			case 'start':
				if ( ! $this->is_wp_blocking_google() && ! $this->is_google_on_blacklist() ) {
					$auth_url = $this->create_auth_url();
					$nextview = monsterinsights_google_auth_enterkey_view( $reauth, $auth_url );
				} else {
					$error    = esc_html__( 'Cannot connect to Google', 'google-analytics-for-wordpress' );
					$nextview = monsterinsights_google_auth_error_view( $reauth, $error );
				}
				break;

			case 'enterkey':
				$auth_key = ! empty( $_POST['stepdata'] ) ? sanitize_text_field( $_POST['stepdata'] ) : '';
				if ( $auth_key ) {
					if ( $this->test_authkey( $auth_key ) ) {
						$profiles = $this->get_profiles();
						delete_option( 'monsterinsights_get_profiles' );
						update_option( 'monsterinsights_get_profiles', $profiles );
						if ( ! empty( $profiles ) ) {
							$select = $this->ga_select();
							$nextview = monsterinsights_google_auth_selectprofile_view( $reauth, $select );
						} else {
							// No profiles or not enough permissions
							$auth_url = $this->create_auth_url();
							$this->client->clear_data();
							$this->set_test_client();
							$nextview = monsterinsights_google_auth_enterkey_view( $reauth, $auth_url, esc_html__( 'No profiles viewable for that account. Please use another account.', 'google-analytics-for-wordpress' ) );
						}
					} else {
						// if bad authentication error message
						$auth_url = $this->create_auth_url();
						$this->client->clear_data();
						$this->set_test_client();
						$nextview = monsterinsights_google_auth_enterkey_view( $reauth, $auth_url, esc_html__( 'Bad Google Code. Please try again.', 'google-analytics-for-wordpress' ) );
					}
				} else {
					$auth_url = $this->create_auth_url();
					$this->client->clear_data();
					$this->set_test_client();
					// if no auth key error message
					$nextview = monsterinsights_google_auth_enterkey_view( $reauth, $auth_url, esc_html__( 'Please paste in your Google code.', 'google-analytics-for-wordpress' ) );
				}
				break;

			case 'selectprofile':
				$profile = ! empty( $_POST['stepdata'] ) ? absint( sanitize_text_field( $_POST['stepdata'] ) ) : '';
				if ( ! empty( $profile ) ) {
					$this->set_test_client();
					$profile = $this->find_selected_profile( $profile );
					if ( ! empty( $profile ) ) {

						$this->clear_manual_data(); // Just in case we were manual, clear out UA

						$this->client->move_test_to_live();
						$this->save_selected_profile( $profile );
						$this->set_client();
						$this->reinitialize();

						// Refresh reporting data
						$this->base->reports->refresh_aggregate_data();

						$nextview = monsterinsights_google_auth_done_view( $reauth );
					} else {
						// Invalid profile selected
						$profiles = get_option( 'monsterinsights_get_profiles', array() );
						$select   = $this->ga_select( $profiles );
						$nextview = monsterinsights_google_auth_selectprofile_view( $reauth, $select, esc_html__( 'Invalid profile selected.', 'google-analytics-for-wordpress' ) );
					}
				} else { 
					// No profile selected
					$profiles = get_option( 'monsterinsights_get_profiles', array() );
					$select   = $this->ga_select( $profiles );
					$nextview = monsterinsights_google_auth_selectprofile_view( $reauth, $select, esc_html__( 'Please select a profile.', 'google-analytics-for-wordpress' ) );
				}
				break;

			case 'done':
				$nextview = monsterinsights_google_auth_done_view( $reauth );
				break;
				
			case 'prestart':
			default:
				 $nextview = monsterinsights_google_auth_start_view( $reauth );
				break;
		}
		echo $nextview;
		wp_die();
	}

	public function google_cancel() {
		$view   = isset( $_POST['view'] ) && in_array( $_POST['view'], array( 'prestart', 'start', 'enterkey', 'selectprofile', 'done' ) ) ? $_POST['view'] : '';
		$reauth = isset( $_POST['reauth'] ) && $_POST['reauth'] && $_POST['reauth'] !== 'false'  ? true : false;

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			echo esc_html__( 'Permission Denied', 'google-analytics-for-wordpress');
			wp_die();
		}

		// If we cancelled on enterkey or selectprofile, delete the temp access tokens and set client back to normal client
		if ( $view === 'enterkey' || $view === 'selectprofile' ) {
			$this->client->clear_data();
			delete_option( 'monsterinsights_get_profiles' );
			$this->set_client();
		}
	}

	private function test_authkey( $authkey ) {
		$this->set_test_client();
		$result = $this->authenticate( $authkey );
		return $result;
	}

	/**
	 * Generates the GA select box
	 *
	 * @return null|string
	 */
	private function ga_select( $profiles = array() ) {
		if ( empty( $profiles ) || ! is_array( $profiles ) ) { 
			$profiles = $this->get_profiles();
		}

		$optgroups = array();
		foreach ( $profiles as $key => $value ) {
			foreach ( $value['items'] as $subitem ) {
				$optgroups[ $subitem['name'] ]['items'] = $subitem['items'];
			}
		}

		$values = $optgroups;
		$select = '';
		$select .= '<div class="monsterinsights_ga_form">';
		$select .= '<label for="monsterinsights_step_data" id="monsterinsights_select_ga_profile_label">' . esc_html__( 'Analytics profile', 'google-analytics-for-wordpress' ) . ':</label>';
		$select .= '<select data-placeholder="' . esc_attr__( 'Select a profile', 'google-analytics-for-wordpress' ) . '" name="monsterinsights_step_data" class="monsterinsights-select2 monsterinsights_select_ga_profile" id="monsterinsights_step_data" style="width:80%;margin-left:10%;margin-right:10%;">';
		$select .= '<option></option>';

		if ( count( $values ) >= 1 ) {
			foreach ( $values as $optgroup => $value ) {
				if ( ! empty( $value['items'] ) ) {
					$select .= $this->create_optgroup( $optgroup, $value );
				}
				else {
					$select .= '<option value="' . esc_attr( $value['id'] ) . '">' . esc_attr( stripslashes( $value['name'] ) ) . '</option>';
				}
			}
		}
		$select .= '</select>';
		$select .= '</div>';
		return $select;
	}

	/**
	 * Creates a optgroup with the items. If items contain items it will create a nested optgroup
	 *
	 * @param string $optgroup
	 * @param array  $value
	 * @param array  $select_value
	 *
	 * @return string
	 */
	private function create_optgroup( $optgroup, $value ) {
		$optgroup = '<optgroup label="' . esc_attr( $optgroup ) . '">';

		foreach ( $value['items'] as $option ) {
			if ( ! empty( $option['items'] ) ) {

				$optgroup .= $this->create_optgroup( esc_attr( $option['name'] ), $option );
			}
			else {
				$optgroup .= '<option value="' . esc_attr( $option['id'] ) . '">' . esc_attr( stripslashes( $option['name'] ) ) . '</option>';
			}
		}

		$optgroup .= '</optgroup>';

		return $optgroup;
	}

	/**
	 * See if Google is on the block_request list.
	 *
	 * @return bool 
	 */
	private function is_wp_blocking_google() {
		if ( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL ) { // @todo: put this in sysinfo 
			if ( defined( 'WP_ACCESSIBLE_HOSTS' ) ) {
				$on_blacklist = $this->is_google_on_blacklist();
				if ( $on_blacklist ) {
					return true;
				} else {
					$params = array(
						'sslverify'     => false,
						'timeout'       => 60,
						'user-agent'    => 'MonsterInsights/' . MONSTERINSIGHTS_VERSION,
						'body'          => ''
					);
					$response = wp_remote_get( 'https://www.googleapis.com/discovery/v1/apis/analytics/v3/rest', $params );
					if( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
						return false;
					} else {
						return true;
					}
				}
			} else {
				return true;
			}
		} else {
			$params = array(
				'sslverify'     => false,
				'timeout'       => 60,
				'user-agent'    => 'MonsterInsights/' . MONSTERINSIGHTS_VERSION,
				'body'          => ''
			);
			$response = wp_remote_get( 'https://www.googleapis.com/discovery/v1/apis/analytics/v3/rest', $params );
			
			if( !is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
				return false;
			} else {
				return true;
			}
		}
	}

	/**
	 * See if Google is on the block_request list.
	 *
	 * @return bool 
	 */
	private function is_google_on_blacklist() { // @todo: put this in sysinfo
		$wp_http = new WP_Http();
		if ( $wp_http->block_request( 'https://www.googleapis.com/discovery/v1/apis/analytics/v3/rest' ) ) {
			return true;
		}

		return false;
	}

	public function monsterinsights_show_admin_config_empty_notice() {
		$screen = get_current_screen(); 
		if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) !== false ) {
			return;
		}
		echo '<div class="error"><p>' . 
			sprintf( esc_html__( 'Please configure your %1$sGoogle Analytics settings%2$s!', 'google-analytics-for-wordpress' ),
				'<a href="' . admin_url( 'admin.php?page=monsterinsights_settings' ) . '">',
				'</a>'
			)
		. '</p></div>';
	}

	/**
	 * Throw a warning when the fetching failed
	 */
	public function monsterinsights_show_admin_config_expired_notice() {
		echo '<div class="error"><p>' . 
			sprintf(
				esc_html__( 'It seems the authentication for the plugin has expired or the connection to Google Analytics is blocked, please try %1$sre-authenticating%2$s with Google Analytics to allow the plugin to fetch data.', 'google-analytics-for-wordpress' ),
				'<a href="' . admin_url( 'admin.php?page=monsterinsights_settings' ) . '">',
				'</a>'
			)
		. '</p></div>';
	}

	/**
	 * Throw a warning when the fetching failed
	 */
	public function monsterinsights_show_admin_config_needs_permissions_notice() {
		echo '<div class="error"><p>' . 
			sprintf(
				esc_html__( 'It seems the authentication for the plugin is missing permissions. Please %1$sre-authenticate%2$s with Google Analytics to allow the plugin to fetch data.', 'google-analytics-for-wordpress' ),
				'<a href="' . admin_url( 'admin.php?page=monsterinsights_settings' ) . '">',
				'</a>'
			)
		. '</p></div>';
	}

	/**
	 * Throw a warning when the fetching failed
	 */
	public function monsterinsights_show_admin_config_blocked_notice() {
		echo '<div class="error"><p>' . 
			sprintf(
				esc_html__( 'Data is not up-to-date, there was an error in retrieving the data from Google Analytics. This error could be caused by several issues. If the error persists, please see %1$sthis page%2$s.', 'google-analytics-for-wordpress' ),
				'<a href="https://www.monsterinsights.com/docs/blocked-connection/">',
				'</a>'
			)
		. '</p></div>';
	}
}