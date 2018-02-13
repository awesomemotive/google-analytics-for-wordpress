<?php
/**
 * License class.  
 *
 * Helper for licenses.
 *
 * @since 7.0.0
 *
 * @package MonsterInsights
 * @subpackage License
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_License {

	private $site     = array();
	private $network  = array();
	private $licensed = false;
	private $using_nl = false;

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 7.0.0
	 */
	public function __construct() {
		$this->site       = $this->get_site_license();
		$this->network    = $this->get_network_license();
		$this->license_to_use();
	}

	private function license_to_use() {
		if ( is_network_admin() ) {
			$license = $this->get_network_license_key();
			if ( ! empty( $license ) ) {
				$this->licensed = true;
				$this->using_nl = true;
			}
		} else {
			$license = $this->get_site_license_key();
			if ( ! empty( $license ) ) {
				$this->licensed = true;
				$this->using_nl = false;
			} else {
				$license = $this->get_network_license_key();
				if ( ! empty( $license ) ) {
					$this->licensed = true;
					$this->using_nl = true;
				}
			}
		}
	}

	public function using_network_license() {
		return $this->using_nl;
	}

	public function get_site_license( $force = false ) {
		if ( ! empty( $this->site ) && ! $force ) {
			return $this->site;
		} else {
			$site = get_option( 'monsterinsights_license', array() );
			$this->site = $site;
			return $site;
		}
	}

	public function get_network_license( $force = false ) {
		if ( ! empty( $this->network ) && ! $force ) {
			return $this->network;
		} else {
			$network = get_site_option( 'monsterinsights_network_license', array() );
			$this->network = $network;
			return $network;
		}
	}

	public function get_license_key() {
		$license_key  = MonsterInsights()->license->get_site_license_key();
		$license_key  = ! empty( $license_key ) ? $license_key : MonsterInsights()->license->get_network_license_key();
		$license_key  = ! empty( $license_key ) ? $license_key : MonsterInsights()->license->get_default_license_key();
		return $license_key;
	}
	public function get_site_license_key(){
		return ( ! empty( $this->site['key'] ) && is_string( $this->site['key'] ) && strlen( $this->site['key'] ) > 10 ) ? $this->site['key'] : '';
	}
	public function get_network_license_key(){
		return ( ! empty( $this->network['key'] ) && is_string( $this->network['key'] ) && strlen( $this->network['key'] ) > 10 ) ? $this->network['key'] : '';
	}

	public function has_license() { 
		return $this->licensed;
	}

	public function is_site_licensed(){
		return    ! empty( $this->site['key'] ) // has key
			   &&   $this->get_site_license_type() // has type
			   && ! $this->site_license_expired()  // isn't expired
			   && ! $this->site_license_disabled()  // isn't disabled
			   && ! $this->site_license_invalid()  // isn't invalid
		;
	}

	public function is_network_licensed() {
		return    ! empty( $this->network['key'] ) // has key
			   &&   $this->get_network_license_type() // has type
			   && ! $this->network_license_expired()  // isn't expired
			   && ! $this->network_license_disabled()  // isn't disabled
			   && ! $this->network_license_invalid()  // isn't invalid
		;
	}


	public function get_site_license_updates(){
		return get_option( 'monsterinsights_license_updates', '' );
	}
	public function get_network_license_updates(){
		return get_site_option( 'monsterinsights_network_license_updates', '' );
	}

	public function set_site_license_updates(){
		update_option( 'monsterinsights_license_updates', strtotime( '+8 hours' ) );
	}
	public function set_network_license_updates() {
		update_site_option( 'monsterinsights_network_license_updates', strtotime( '+8 hours' ) );
	}

	public function delete_site_license_updates(){
		delete_option( 'monsterinsights_license_updates' );
	}
	public function delete_network_license_updates(){
		delete_site_option( 'monsterinsights_license_updates' );
	}

	public function time_to_check_site_license(){
		$timestamp = get_option( 'monsterinsights_license_updates' );
		if ( ! $timestamp ) {
			 return true;
		} else {
			$current_timestamp = time();
			if ( $current_timestamp < $timestamp ) {
				return false;
			} else {
			   return true;
			}
		}
	}	
	public function time_to_check_network_license(){
		$timestamp = get_site_option( 'monsterinsights_network_license_updates' );
		if ( ! $timestamp ) {
			 return true;
		} else {
			$current_timestamp = time();
			if ( $current_timestamp < $timestamp ) {
				return false;
			} else {
			   return true;
			}
		}
	}

	public function set_site_license( $data = array() ){
		update_option( 'monsterinsights_license', $data );
		$this->set_site_license_updates();
		$this->site      = $data;
	}
	public function set_network_license( $data = array() ){
		update_site_option( 'monsterinsights_network_license', $data );
		$this->set_network_license_updates();
		$this->network   = $data;
	}

	public function delete_site_license() {
		delete_option( 'monsterinsights_license' );
		$this->delete_site_license_updates();
		$this->site      = array();
	}
	public function delete_network_license() {
		delete_site_option( 'monsterinsights_network_license' );
		$this->delete_network_license_updates();
		$this->network = array();
	}

	public function get_license_type(){
		if ( ! $this->has_license() ) {
			return false;
		}

		return $this->using_network_license() ? $this->get_network_license_type() : $this->get_site_license_type();
	}
	public function get_site_license_type(){
		return ( $this->get_site_license_key() && ! empty( $this->site['type'] ) && $this->is_valid_license_type( $this->site['type'] ) ) ? $this->site['type'] : '';
	}
	public function get_network_license_type(){
		return ( $this->get_network_license_key() && ! empty( $this->network['type'] ) && $this->is_valid_license_type( $this->network['type'] ) ) ? $this->network['type'] : '';
	}

	public function license_has_error(){
		if ( ! $this->has_license() ) {
			return false;
		}

		return $this->using_network_license() ? $this->network_license_has_error() : $this->site_license_has_error();
	}
	public function site_license_has_error() {
		return
				  $this->site_license_expired()  // is expired
			   || $this->site_license_disabled()  // is disabled
			   || $this->site_license_invalid()  // is invalid
		;
	}
	public function network_license_has_error(){
		return 
				  $this->network_license_expired()  // is expired
			   || $this->network_license_disabled()  // is disabled
			   || $this->network_license_invalid()  // is invalid
		;
	}

	public function license_expired(){
		if ( ! $this->has_license() ) {
			return false;
		}
		
		return $this->using_network_license() ? $this->network_license_expired() : $this->site_license_expired();
	}
	public function site_license_expired(){
		return ! empty( $this->site['is_expired'] );
	}
	public function network_license_expired(){
		return ! empty( $this->network['is_expired'] );
	}

	public function license_disabled(){
		if ( ! $this->has_license() ) {
			return false;
		}
		
		return $this->using_network_license() ? $this->network_license_disabled() : $this->site_license_disabled();
	}
	public function site_license_disabled() {
		return ! empty( $this->site['is_disabled'] );
	}
	public function network_license_disabled(){
		return ! empty( $this->network['is_disabled'] );
	}

	public function license_invalid(){
		if ( ! $this->has_license() ) {
			return false;
		}
		
		return $this->using_network_license() ? $this->network_license_invalid() : $this->site_license_invalid();
	}
	public function site_license_invalid() {
		return ! empty( $this->site['is_invalid'] );
	}
	public function network_license_invalid(){
		return ! empty( $this->network['is_invalid'] );
	}


	public function get_license_error(){
		if ( ! $this->has_license() ) {
			return false;
		}
		
		return $this->using_network_license() ? $this->get_network_license_error() : $this->get_site_license_error();
	}
	public function get_site_license_error(){
		if ( $this->site_license_expired() ) {
			return sprintf( esc_html__( 'Your license key for MonsterInsights has expired. %1$sPlease click here to renew your license key.%2$s', 'google-analytics-for-wordpress' ), '<a href="https://www.monsterinsights.com/login/" target="_blank" rel="noopener noreferrer" referrer="no-referrer">', '</a>' );
		} else if ( $this->site_license_disabled() ) {
			return esc_html__( 'Your license key for MonsterInsights has been disabled. Please use a different key.', 'google-analytics-for-wordpress' );
		} else if ( $this->site_license_invalid() ) {
			return esc_html__( 'Your license key for MonsterInsights is invalid. The key no longer exists or the user associated with the key has been deleted. Please use a different key.', 'google-analytics-for-wordpress' );
		}
		return '';
	}

	public function get_network_license_error(){
		if ( $this->site_license_expired() ) {
			return sprintf( esc_html__( 'Your license key for MonsterInsights has expired. %1$sPlease click here to renew your license key.%2$s', 'google-analytics-for-wordpress' ), '<a href="https://www.monsterinsights.com/login/" target="_blank" rel="noopener noreferrer" referrer="no-referrer">', '</a>' );
		} else if ( $this->site_license_disabled() ) {
			return esc_html__( 'Your license key for MonsterInsights has been disabled. Please use a different key.', 'google-analytics-for-wordpress' );
		} else if ( $this->site_license_invalid() ) {
			return esc_html__( 'Your license key for MonsterInsights is invalid. The key no longer exists or the user associated with the key has been deleted. Please use a different key.', 'google-analytics-for-wordpress' );
		}
		return '';
	}

	public function license_can( $requires = 'lite' ) {		
		if ( ! monsterinsights_is_pro_version() || ! $this->has_license() ) {
			return $requires === 'lite';
		}
		return $this->using_network_license() ? $this->network_license_can( $requires ) : $this->site_license_can( $requires );
	}
	public function site_license_can( $requires = 'lite' ) {
		$level      = $this->get_site_license_type();
		$level      = $level ? $level : 'lite';
		$can_access = false;

		switch ( $requires ) {
			case 'master':
				$can_access = ( $level === 'master' ) ? true : false;
				break;

			case 'pro':
				$can_access = ( $level === 'master' || $level === 'pro' ) ? true : false;
				break;

			case 'plus':
				$can_access = ( $level === 'master' || $level === 'pro' || $level === 'plus' ) ? true : false;
				break;

			case 'basic':
				$can_access = ( $level === 'master' || $level === 'pro' || $level === 'plus' || $level === 'basic' ) ? true : false;
				break;

			case 'lite':
			default:
				$can_access = true;
				break;
		}

		return $can_access;
	}
	public function network_license_can( $requires = 'lite' ) {
		$level      = $this->get_network_license_type();
		$level      = $level ? $level : 'lite';
		$can_access = false;

		switch ( $requires ) {
			case 'master':
				$can_access = ( $level === 'master' ) ? true : false;
				break;

			case 'pro':
				$can_access = ( $level === 'master' || $level === 'pro' ) ? true : false;
				break;

			case 'plus':
				$can_access = ( $level === 'master' || $level === 'pro' || $level === 'plus' ) ? true : false;
				break;

			case 'basic':
				$can_access = ( $level === 'master' || $level === 'pro' || $level === 'plus' || $level === 'basic' ) ? true : false;
				break;

			case 'lite':
			default:
				$can_access = true;
				break;
		}

		return $can_access;
	}

	public function get_default_license_key(){
		if ( defined( 'MONSTERINSIGHTS_LICENSE_KEY' ) && is_string( MONSTERINSIGHTS_LICENSE_KEY ) && strlen( MONSTERINSIGHTS_LICENSE_KEY ) > 10 ) {
			return MONSTERINSIGHTS_LICENSE_KEY;
		}
		return '';
	}

	public function get_valid_license_key() {
		if ( $this->is_site_licensed() ) {
			return $this->get_site_license_key();
		} else if ( $this->is_network_licensed() ) {
			return $this->get_network_license_key();
		} else if ( $this->get_default_license_key() ) {
			return $this->get_default_license_key();
		} else {
			return '';
		}
	}

	public function is_network_admin() {
		return is_multisite() && is_network_admin();
	}

	public function is_valid_license_type( $type = '' ) {
		return ! empty( $type ) && is_string( $type ) && in_array( $type, $this->valid_license_types() );
	}

	public function valid_license_types() {
		return array(
			'basic',
			'plus',
			'pro',
			'master'
		);
	}
}