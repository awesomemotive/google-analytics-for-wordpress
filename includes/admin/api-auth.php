<?php
/**
 * Google Client admin class.  
 *
 * Handles retrieving whether a particular notice has been dismissed or not,
 * as well as marking a notice as dismissed.
 *
 * @since 7.0.0
 *
 * @package MonsterInsights
 * @subpackage GA Client
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_API_Auth {

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 7.0.0
	 */
	public function __construct() {

		// Authentication Actions
		add_action( 'wp_ajax_monsterinsights_maybe_authenticate',    array( $this, 'maybe_authenticate' ) );
		add_action( 'wp_ajax_monsterinsights_maybe_reauthenticate',  array( $this, 'maybe_reauthenticate' ) );
		add_action( 'wp_ajax_monsterinsights_maybe_verify',          array( $this, 'maybe_verify' ) );
		add_action( 'wp_ajax_monsterinsights_maybe_delete',          array( $this, 'maybe_delete' ) );

		add_action( 'admin_init',          							 array( $this, 'authenticate_listener' ) );
		add_action( 'admin_init',          							 array( $this, 'reauthenticate_listener' ) );
	}

	public function get_tt(){
		$tt = is_network_admin() ? get_site_option( 'monsterinsights_network_tt', '' ) : get_option( 'monsterinsights_site_tt', '' );
		if ( empty( $tt ) ) {
			// if TT is empty, generate a new one, save it and then return it
			$tt = $this->generate_tt();
			$this->is_network_admin() ? update_site_option( 'monsterinsights_network_tt', $tt ) : update_option( 'monsterinsights_site_tt', $tt );
		}
		return $tt;
	}
	
	public function rotate_tt(){
		$tt = $this->generate_tt();
		is_network_admin() ? update_site_option( 'monsterinsights_network_tt', $tt ) : update_option( 'monsterinsights_site_tt', $tt );
	}

	public function generate_tt(){
		return hash( 'sha512', wp_generate_password( 128, true, true ) . AUTH_SALT . uniqid( "", true ) );
	}

	public function validate_tt( $passed_tt = '' ) {
		$tt = $this->get_tt();
		return hash_equals( $tt, $passed_tt );
	}

	public function maybe_authenticate(){

		// Check nonce
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		// current user can authenticate
		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json_error( array(	'message' => __( "You don't have permission to authenticate MonsterInsights.", 'google-analytics-for-wordpress' ) ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && $_REQUEST['isnetwork'] ) {
			define( 'WP_NETWORK_ADMIN', true );
		}

		// Only for Pro users, require a license key to be entered first so we can link to things.
		$valid = is_network_admin() ? MonsterInsights()->license->is_network_licensed() : MonsterInsights()->license->is_site_licensed();
		if ( monsterinsights_is_pro_version() && ! $valid ) {
			wp_send_json_error( array(	'message' => __( "Cannot authenticate. Please enter a valid, active license key for MonsterInsights Pro into the settings.", 'google-analytics-for-wordpress' ) ) );
		}

		// we do not have a current auth
		if ( ! $this->is_network_admin() && MonsterInsights()->auth->is_authed() ) {
			wp_send_json_error( array(	'message' => __( "Cannot authenticate. Please re-authenticate.", 'google-analytics-for-wordpress' ) ) );
		} else if ( $this->is_network_admin() && MonsterInsights()->auth->is_network_authed() ) {
			wp_send_json_error( array(	'message' => __( "Cannot network authenticate. Please re-authenticate on the network settings panel.", 'google-analytics-for-wordpress' ) ) );
		}

		$siteurl = add_query_arg( array(
			'tt'        => $this->get_tt(),
			'sitei'     => $this->get_sitei(),
			'miversion' => MONSTERINSIGHTS_VERSION,
			'siteurl'   => is_network_admin() ? network_admin_url() : site_url(),
			'return'    => is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_network' ) : admin_url( 'admin.php?page=monsterinsights_settings' ),
		 ), $this->get_route( 'https://' . monsterinsights_get_api_url() . 'auth/new/{type}' ) );

		if ( monsterinsights_is_pro_version() ) {
			$key     = is_network_admin() ? MonsterInsights()->license->get_network_license_key() : MonsterInsights()->license->get_site_license_key();
			$siteurl = add_query_arg( 'license', $key, $siteurl );
		}

		$siteurl = apply_filters( 'monsterinsights_maybe_authenticate_siteurl', $siteurl );
		wp_send_json_success( array( 'redirect' => $siteurl ) );
	}


	public function authenticate_listener(){
		// Make sure it's for us
		if ( empty( $_REQUEST['mi-oauth-action'] ) || $_REQUEST['mi-oauth-action'] !== 'auth' ) {
			return;
		}


		// User can authenticate
		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		// Invalid request
		if ( empty( $_REQUEST['tt'] ) || ! $this->validate_tt( $_REQUEST['tt'] ) ) {
			return;
		}

		// Make sure has required params
		if ( empty( $_REQUEST['key'] )      ||
			 empty( $_REQUEST['token'] )    ||
			 empty( $_REQUEST['ua'] )       ||
			 empty( $_REQUEST['miview'] )   ||
			 empty( $_REQUEST['a'] )        ||
			 empty( $_REQUEST['w'] )        ||
			 empty( $_REQUEST['p'] )
		) {
			return;
		}

		// Invalid UA code
		$ua = monsterinsights_is_valid_ua( $_REQUEST['ua'] );
		if ( empty( $ua ) ) {
			return;
		}

		$profile = array( 
			'key'      => sanitize_text_field( $_REQUEST['key'] ),
			'token'    => sanitize_text_field( $_REQUEST['token'] ),
			'ua'       => monsterinsights_is_valid_ua( $_REQUEST['ua'] ),
			'viewname' => sanitize_text_field( $_REQUEST['miview'] ),
			'a'        => sanitize_text_field( $_REQUEST['a'] ), // AccountID
			'w'        => sanitize_text_field( $_REQUEST['w'] ), // PropertyID
			'p'        => sanitize_text_field( $_REQUEST['p'] ), // View ID
		);

		$worked = $this->verify_auth( $profile );
		if ( ! $worked ) {
			return;
		}

		// Rotate tt
		$this->rotate_tt();

		// Save Profile
		$this->is_network_admin() ? MonsterInsights()->auth->set_network_analytics_profile( $profile ) : MonsterInsights()->auth->set_analytics_profile( $profile );

		// Clear cache
		$where = $this->is_network_admin() ? 'network' : 'site';
		MonsterInsights()->reporting->delete_aggregate_data( $where );

		$url = $this->is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_network' )  : admin_url( 'admin.php?page=monsterinsights_settings' ) ;
		$url = add_query_arg( array(
			 'mi_action' => 'auth',
			 'success'   => 'true',
			), $url );
		wp_safe_redirect( $url );
		exit;
	}

	public function maybe_reauthenticate(){

		// Check nonce
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		// current user can authenticate
		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json_error( array(	'message' => __( "You don't have permission to re-authenticate MonsterInsights.", 'google-analytics-for-wordpress' ) ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && $_REQUEST['isnetwork'] ) {
			define( 'WP_NETWORK_ADMIN', true );
		}

		// Only for Pro users, require a license key to be entered first so we can link to things.
		$valid = is_network_admin() ? MonsterInsights()->license->is_network_licensed() : MonsterInsights()->license->is_site_licensed();
		if ( monsterinsights_is_pro_version() && ! $valid ) {
			wp_send_json_error( array(	'message' => __( "Cannot re-authenticate. Please enter a valid, active license key for MonsterInsights Pro into the settings.", 'google-analytics-for-wordpress' ) ) );
		}

		// we do have a current auth
		if ( ! $this->is_network_admin() && ! MonsterInsights()->auth->is_authed() ) {
			wp_send_json_error( array(	'message' => __( "Cannot re-authenticate. Please authenticate.", 'google-analytics-for-wordpress' ) ) );
		} else if ( $this->is_network_admin() && ! MonsterInsights()->auth->is_network_authed() ) {
			wp_send_json_error( array(	'message' => __( "Cannot re-authenticate the network. Please authenticate on the network settings panel.", 'google-analytics-for-wordpress' ) ) );
		}

		$siteurl = add_query_arg( array(
			'tt'        => $this->get_tt(),
			'sitei'     => $this->get_sitei(),
			'miversion' => MONSTERINSIGHTS_VERSION,
			'siteurl'   => is_network_admin() ? network_admin_url() : site_url(),
			'key'       => MonsterInsights()->auth->get_key(),
			'token'     => MonsterInsights()->auth->get_token(),
			'return'    => is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_network' ) : admin_url( 'admin.php?page=monsterinsights_settings' ),
		 ), $this->get_route( 'https://' . monsterinsights_get_api_url() . 'auth/reauth/{type}' ) );

		if ( monsterinsights_is_pro_version() ) {
			$key     = is_network_admin() ? MonsterInsights()->license->get_network_license_key() : MonsterInsights()->license->get_site_license_key();
			$siteurl = add_query_arg( 'license', $key, $siteurl );
		}

		$siteurl = apply_filters( 'monsterinsights_maybe_authenticate_siteurl', $siteurl );

		wp_send_json_success( array( 'redirect' => $siteurl ) );
	}

	public function reauthenticate_listener(){
		// Make sure it's for us
		if ( empty( $_REQUEST['mi-oauth-action'] ) || $_REQUEST['mi-oauth-action'] !== 'reauth' ) {
			return;
		}

		// User can authenticate
		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		// Invalid request
		if ( empty( $_REQUEST['tt'] ) || ! $this->validate_tt( $_REQUEST['tt'] ) ) {
			return;
		}

		// Make sure has required params
		if (
			 empty( $_REQUEST['ua'] )       ||
			 empty( $_REQUEST['miview'] )   ||
			 empty( $_REQUEST['a'] )        ||
			 empty( $_REQUEST['w'] )        ||
			 empty( $_REQUEST['p'] )       
		) {
			return;
		}

		// Invalid UA code
		$ua = monsterinsights_is_valid_ua( $_REQUEST['ua'] );
		if ( empty( $ua ) ) {
			return;
		}

		// we do have a current auth
		$existing = $this->is_network_admin() ? MonsterInsights()->auth->get_network_analytics_profile() : MonsterInsights()->auth->get_analytics_profile();
		if ( empty( $existing['key'] ) || empty( $existing['token'] ) ) {
			return;
		}

		$profile = array(
			'key'      => $existing['key'],
			'token'    => $existing['token'],
			'ua'       => monsterinsights_is_valid_ua( $_REQUEST['ua'] ),
			'viewname' => sanitize_text_field( $_REQUEST['miview'] ),
			'a'        => sanitize_text_field( $_REQUEST['a'] ),
			'w'        => sanitize_text_field( $_REQUEST['w'] ),
			'p'        => sanitize_text_field( $_REQUEST['p'] ),
		);

		// Rotate tt
		$this->rotate_tt();

		// Save Profile
		$this->is_network_admin() ? MonsterInsights()->auth->set_network_analytics_profile( $profile ) : MonsterInsights()->auth->set_analytics_profile( $profile );

		// Clear cache
		$where = $this->is_network_admin() ? 'network' : 'site';
		MonsterInsights()->reporting->delete_aggregate_data( $where );

		$url = $this->is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_network' )  : admin_url( 'admin.php?page=monsterinsights_settings' ) ;
		$url = add_query_arg( array(
			 'mi_action' => 'reauth',
			 'success'   => 'true',
			), $url );
		wp_safe_redirect( $url );
		exit;
	}

	public function maybe_verify(){

		// Check nonce
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		// current user can verify
		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json_error( array(	'message' => __( "You don't have permission to verify MonsterInsights.", 'google-analytics-for-wordpress' ) ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && $_REQUEST['isnetwork'] ) {
			define( 'WP_NETWORK_ADMIN', true );
		}

		// we have an auth to verify
		if ( $this->is_network_admin() && ! MonsterInsights()->auth->is_network_authed() ) {
			wp_send_json_error( array(	'message' => __( "Cannot verify. Please authenticate.", 'google-analytics-for-wordpress' ) ) );
		} else if ( ! $this->is_network_admin() && ! MonsterInsights()->auth->is_authed() ) {
			wp_send_json_error( array(	'message' => __( "Cannot verify. Please authenticate.", 'google-analytics-for-wordpress' ) ) );
		}

		$valid = is_network_admin() ? MonsterInsights()->license->is_network_licensed() : MonsterInsights()->license->is_site_licensed();
		if ( monsterinsights_is_pro_version() && ! $valid ) {
			wp_send_json_error( array(	'message' => __( "Cannot verify. Please enter a valid, active license key for MonsterInsights Pro into the settings.", 'google-analytics-for-wordpress' ) ) );
		}

		$worked = $this->verify_auth();
		if ( $worked && ! is_wp_error(  $worked ) ) {
			wp_send_json_success( array( 'message' => __( "Successfully verified.", 'google-analytics-for-wordpress' ) ) );
		} else {
			wp_send_json_error( array( 'message' => __( "Could not verify.", 'google-analytics-for-wordpress' ) ) );
		}
	}

	public function verify_auth( $credentials = array() ){
		$creds = ! empty( $credentials ) ? $credentials : ( $this->is_network_admin() ? MonsterInsights()->auth->get_network_analytics_profile( true ) : MonsterInsights()->auth->get_analytics_profile( true ) );

		if ( empty( $creds['key'] ) ) {
			return false;
		}
		
		$api   = new MonsterInsights_API_Request( $this->get_route( 'auth/verify/{type}/' ), array( 'network' => $this->is_network_admin(), 'tt' => $this->get_tt(), 'key' => $creds['key'], 'token' => $creds['token'] ) );
		$ret   = $api->request();
		
		if ( is_wp_error( $ret ) ) {
			return false;
		} else {
			return true;
		}
	}

	public function maybe_delete(){

		// Check nonce
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		// current user can delete
		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			wp_send_json_error( array(	'message' => __( "You don't have permission to deauthenticate MonsterInsights.", 'google-analytics-for-wordpress' ) ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && $_REQUEST['isnetwork'] ) {
			define( 'WP_NETWORK_ADMIN', true );
		}

		// we have an auth to delete
		if ( $this->is_network_admin() && ! MonsterInsights()->auth->is_network_authed() ) {
			wp_send_json_error( array(	'message' => __( "Cannot deauthenticate. You are not currently authed.", 'google-analytics-for-wordpress' ) ) );
		} else if ( ! $this->is_network_admin() && ! MonsterInsights()->auth->is_authed() ) {
			wp_send_json_error( array(	'message' => __( "Cannot deauthenticate. You are not currently authed.", 'google-analytics-for-wordpress' ) ) );
		}

		$valid = is_network_admin() ? MonsterInsights()->license->is_network_licensed() : MonsterInsights()->license->is_site_licensed();
		if ( monsterinsights_is_pro_version() && ! $valid ) {
			wp_send_json_error( array(	'message' => __( "Cannot deauthenticate. Please enter a valid, active license key for MonsterInsights Pro into the settings.", 'google-analytics-for-wordpress' ) ) );
		}

		$force = ! empty( $_REQUEST['forcedelete'] ) && $_REQUEST['forcedelete'] === 'true';

		$worked = $this->delete_auth( $force );
		if ( $worked && ! is_wp_error(  $worked ) ) {
			wp_send_json_success( array( 'message' => __( "Successfully deauthenticated.", 'google-analytics-for-wordpress' ) ) );
		} else {
			if ( $force ) {
				wp_send_json_success( array( 'message' => __( "Successfully force deauthenticated.", 'google-analytics-for-wordpress' ) ) );
			} else {
				wp_send_json_error( array( 'message' => __( "Could not deauthenticate.", 'google-analytics-for-wordpress' ) ) );
			}
		}
	}

	public function delete_auth( $force = false ){
		if ( $this->is_network_admin() && ! MonsterInsights()->auth->is_network_authed() ) {
			return false;
		} else if ( ! $this->is_network_admin() && ! MonsterInsights()->auth->is_authed() ) {
			return false;
		}

		$creds = $this->is_network_admin() ? MonsterInsights()->auth->get_network_analytics_profile( true ) : MonsterInsights()->auth->get_analytics_profile( true );

		if ( empty( $creds['key'] ) ) {
			return false;
		}

		$api   = new MonsterInsights_API_Request( $this->get_route( 'auth/delete/{type}/' ), array( 'network' => $this->is_network_admin(), 'tt' => $this->get_tt(), 'key' => $creds['key'], 'token' => $creds['token'] ) );
		$ret   = $api->request();

		if ( is_wp_error( $ret ) && ! $force ) {
			return false;
		} else {
			if ( $this->is_network_admin() ) {
				MonsterInsights()->auth->delete_network_analytics_profile( true );
			} else {
				MonsterInsights()->auth->delete_analytics_profile( true );

			}
			return true;
		}
	}

	public function get_type() {
		return monsterinsights_is_pro_version() ? 'pro' : 'lite';
	}

	public function get_route( $route = '' ) {
		$route = str_replace( '{type}', $this->get_type(), $route );
		$route = trailingslashit( $route );
		return $route;
	}

	public function is_network_admin() {
		return is_multisite() && is_network_admin();
	}

	public function get_sitei() {
		return  mb_strimwidth( rtrim( ltrim( sanitize_text_field( preg_replace('/[^a-zA-Z0-9]/', '', AUTH_KEY . SECURE_AUTH_KEY . LOGGED_IN_KEY )  ) ) ), 0, 30 );
	}
}