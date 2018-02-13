<?php
/**
 * Auth class.  
 *
 * Helper for auth.
 *
 * @since 7.0.0
 *
 * @package MonsterInsights
 * @subpackage Auth
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_Auth {

	private $profile  = array();
	private $network = array();

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 7.0.0
	 */
	public function __construct() {
		$this->profile       = $this->get_analytics_profile();
		$this->network       = $this->get_network_analytics_profile();
	}

	public function is_manual() {
		return ! empty( $this->profile['manual'] );
	}
	public function is_network_manual() {
		return ! empty( $this->network['manual'] );
	}

	public function is_authed() {
		return ! empty( $this->profile['key'] );
	}
	
	public function is_network_authed() {
		return ! empty( $this->network['key'] );
	}

	public function get_analytics_profile( $force = false ) {
		if ( ! empty( $this->profile ) && ! $force ) {
			return $this->profile;
		} else {
			$profile = get_option( 'monsterinsights_site_profile', array() );
			$this->profile = $profile;
			return $profile;
		}
	}

	public function get_network_analytics_profile( $force = false ) {
		if ( ! empty( $this->network ) && ! $force ) {
			return $this->network;
		} else {
			$profile = get_site_option( 'monsterinsights_network_profile', array() );
			$this->network = $profile;
			return $profile;
		}
	}

	public function set_analytics_profile( $data = array() ){
		update_option( 'monsterinsights_site_profile', $data );
		$this->profile      = $data;
	}

	public function set_network_analytics_profile( $data = array() ){
		update_site_option( 'monsterinsights_network_profile', $data );
		$this->network      = $data;
	}

	public function delete_analytics_profile( $migrate = true ){
		if ( $migrate ) {
			$newdata = array();
			if ( isset( $this->profile['ua'] ) ) {
				$newdata['manual'] = $this->profile['ua'];
			}
			$this->profile      = $newdata;
			$this->set_analytics_profile( $newdata );
		} else {
			$this->profile      = array();
			delete_option( 'monsterinsights_site_profile' );
		}
	}

	public function delete_network_analytics_profile( $migrate = true ){
		if ( $migrate ) {
			$newdata = array();
			if ( isset( $this->network['ua'] ) ) {
				$newdata['manual'] = $this->network['ua'];
			}
			$this->network      = $newdata;
			$this->set_network_analytics_profile( $newdata );
		} else {
			$this->network      = array();
			delete_site_option( 'monsterinsights_network_profile' );
		}
	}

	public function set_manual_ua( $ua = '' ) {
		if ( empty( $ua ) ) {
			return;
		}

		if ( $this->is_authed() ) {
			MonsterInsights()->api_auth->delete_auth();
		}

		$data = array();
		if ( empty( $this->profile ) ) {
			$data['manual'] = $ua;
		} else {
			$data           = $this->profile;
			$data['manual'] = $ua;
		}
		
		do_action( 'monsterinsights_reports_delete_aggregate_data' );

		$this->profile      = $data;
		$this->set_analytics_profile( $data );
	}

	public function set_network_manual_ua( $ua = '' ) {
		if ( empty( $ua ) ) {
			return;
		}

		if ( $this->is_network_authed() ) {
			MonsterInsights()->api_auth->delete_auth();
		}

		$data = array();
		if ( empty( $this->network ) ) {
			$data['manual'] = $ua;
		} else {
			$data           = $this->network;
			$data['manual'] = $ua;
		}
		
		do_action( 'monsterinsights_reports_delete_network_aggregate_data' );

		$this->network = $data;
		$this->set_network_analytics_profile( $data );
	}

	public function delete_manual_ua() {
		if ( ! empty( $this->profile ) && ! empty( $this->profile['manual'] ) ) {
			unset( $this->profile['manual'] );
			$this->set_analytics_profile( $this->profile );
		}
	}

	public function delete_network_manual_ua() {
		if ( ! empty( $this->network ) && ! empty( $this->network['manual'] ) ) {
			unset( $this->network['manual'] );
			$this->set_network_analytics_profile( $this->network );
		}
	}

	public function get_manual_ua() {
		return ! empty( $this->profile['manual'] ) ? monsterinsights_is_valid_ua( $this->profile['manual'] ) : '';
	}

	public function get_network_manual_ua() {
		return ! empty( $this->network['manual'] ) ? monsterinsights_is_valid_ua( $this->network['manual'] ) : '';
	}

	public function get_ua() {
		return ! empty( $this->profile['ua'] ) ? monsterinsights_is_valid_ua( $this->profile['ua'] ) : '';
	}

	public function get_network_ua() {
		return ! empty( $this->network['ua'] ) ? monsterinsights_is_valid_ua( $this->network['ua'] ) : '';
	}

	public function get_viewname(){
		return ! empty( $this->profile['viewname'] ) ? $this->profile['viewname'] : '';
	}

	public function get_network_viewname(){
		return ! empty( $this->network['viewname'] ) ? $this->network['viewname'] : '';
	}

	public function get_accountid(){
		return ! empty( $this->profile['a'] ) ? $this->profile['a'] : '';
	}

	public function get_network_accountid(){
		return ! empty( $this->network['a'] ) ? $this->network['a'] : '';
	}

	public function get_propertyid(){
		return ! empty( $this->profile['w'] ) ? $this->profile['w'] : '';
	}

	public function get_network_propertyid(){
		return ! empty( $this->network['w'] ) ? $this->network['w'] : '';
	}

	public function get_viewid(){ // also known as profileID
		return ! empty( $this->profile['p'] ) ? $this->profile['p'] : '';
	}

	public function get_network_viewid(){ // also known as profileID
		return ! empty( $this->network['p'] ) ? $this->network['p'] : '';
	}

	public function get_key(){
		return ! empty( $this->profile['key'] ) ? $this->profile['key'] : '';
	}

	public function get_network_key(){
		return ! empty( $this->network['key'] ) ? $this->network['key'] : '';
	}

	public function get_token(){
		return ! empty( $this->profile['token'] ) ? $this->profile['token'] : '';
	}

	public function get_network_token(){
		return ! empty( $this->network['token'] ) ? $this->network['token'] : '';
	}

	public function get_referral_url(){
		$url = '';

		if ( $this->is_authed() ) {
			$url .= 'a' . MonsterInsights()->auth->get_accountid() . 'w' . MonsterInsights()->auth->get_propertyid() . 'p' . MonsterInsights()->auth->get_viewid() . '/';
		} else if ( $this->is_network_authed() ) {
			$url .= 'a' . MonsterInsights()->auth->get_network_accountid() . 'w' . MonsterInsights()->auth->get_network_propertyid() . 'p' . MonsterInsights()->auth->get_network_viewid() . '/';
		}

		return $url;
	}
}