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

	private $profile = array();
	private $network = array();

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 7.0.0
	 */
	public function __construct() {
		$this->profile = $this->get_analytics_profile();
		$this->network = $this->get_network_analytics_profile();
	}

	public function is_manual() {
        $manual_code = $this->profile['manual'];
        return monsterinsights_is_valid_v4_id( $manual_code );
	}

	public function is_network_manual( $type = false ) {
		$manual_code = $this->network['manual'];
        return monsterinsights_is_valid_v4_id( $manual_code );
	}

	public function is_authed() {
		return ! empty( $this->profile['key'] ) && ! empty( $this->profile[ 'v4' ] );
	}

	public function is_network_authed() {
        return ! empty( $this->network['key'] ) && ! empty( $this->network[ 'v4' ] );
	}

	public function get_analytics_profile( $force = false ) {
		if ( ! empty( $this->profile ) && ! $force ) {
			return $this->profile;
		} else {
			$profile       = get_option( 'monsterinsights_site_profile', array() );
			$this->profile = $profile;

			return $profile;
		}
	}

	public function get_network_analytics_profile( $force = false ) {
		if ( ! empty( $this->network ) && ! $force ) {
			return $this->network;
		} else {
			$profile       = get_site_option( 'monsterinsights_network_profile', array() );
			$this->network = $profile;

			return $profile;
		}
	}

	public function set_analytics_profile( $data = array() ) {
		if ( ! empty( $data ) ) {
			$data['connection_time'] = time();
		}

		update_option( 'monsterinsights_site_profile', $data );
		$this->profile = $data;

		// If this is the first time, save the date when they connected.
		$over_time    = get_option( 'monsterinsights_over_time', array() );
		$needs_update = false;
		if ( monsterinsights_is_pro_version() && empty( $over_time['connected_date_pro'] ) ) {
			$over_time['connected_date_pro'] = time();
			$needs_update                    = true;
		}
		if ( ! monsterinsights_is_pro_version() && empty( $over_time['connected_date_lite'] ) ) {
			$over_time['connected_date_lite'] = time();
			$needs_update                     = true;
		}
		if ( $needs_update ) {
			update_option( 'monsterinsights_over_time', $over_time, false );
		}
	}

	public function set_network_analytics_profile( $data = array() ) {
		update_site_option( 'monsterinsights_network_profile', $data );
		$this->network = $data;
	}

	public function delete_analytics_profile( $migrate = true ) {
		if ( $migrate ) {
			$newdata = array();
			if ( isset( $this->profile['v4'] ) ) {
				$newdata['manual_v4'] = $this->profile['v4'];
                $newdata['measurement_protocol_secret'] = $this->profile['measurement_protocol_secret'];
			}
			$this->profile = $newdata;
			$this->set_analytics_profile( $newdata );
		} else {
			$this->profile = array();
			delete_option( 'monsterinsights_site_profile' );
		}
	}

	public function delete_network_analytics_profile( $migrate = true ) {
		if ( $migrate ) {
			$newdata = array();
			if ( isset( $this->network['v4'] ) ) {
				$newdata['manual_v4'] = $this->network['v4'];
                $newdata['measurement_protocol_secret'] = $this->profile['measurement_protocol_secret'];
			}
			$this->network = $newdata;
			$this->set_network_analytics_profile( $newdata );
		} else {
			$this->network = array();
			delete_site_option( 'monsterinsights_network_profile' );
		}
	}

	public function set_manual_v4_id( $v4 = '' ) {
		if ( empty( $v4 ) ) {
			return;
		}

		if ( $this->is_authed() ) {
			MonsterInsights()->api_auth->delete_auth();
		}

		$data = array();
		if ( empty( $this->profile ) ) {
			$data['manual_v4'] = $v4;
		} else {
			$data              = $this->profile;
			$data['manual_v4'] = $v4;
		}

		do_action( 'monsterinsights_reports_delete_aggregate_data' );

		$this->profile = $data;
		$this->set_analytics_profile( $data );
	}

	public function set_network_manual_v4_id( $v4 = '' ) {
		if ( empty( $v4 ) ) {
			return;
		}

		if ( $this->is_network_authed() ) {
			MonsterInsights()->api_auth->delete_auth();
		}

		$data = array();
		if ( empty( $this->network ) ) {
			$data['manual_v4'] = $v4;
		} else {
			$data                      = $this->network;
			$data['manual_v4']         = $v4;
			$data['network_manual_v4'] = $v4;
		}

		do_action( 'monsterinsights_reports_delete_network_aggregate_data' );

		$this->network = $data;
		$this->set_network_analytics_profile( $data );
	}

	public function get_measurement_protocol_secret() {
		return ! empty( $this->profile['measurement_protocol_secret'] ) ? $this->profile['measurement_protocol_secret'] : '';
	}

	public function get_network_measurement_protocol_secret() {
		return ! empty( $this->network['measurement_protocol_secret'] ) ? $this->network['measurement_protocol_secret'] : '';
	}

	public function set_measurement_protocol_secret( $value ) {
		$data = array();
		if ( empty( $this->profile ) ) {
			$data['measurement_protocol_secret'] = $value;
		} else {
			$data                                = $this->profile;
			$data['measurement_protocol_secret'] = $value;
		}

		$this->profile = $data;
		$this->set_analytics_profile( $data );
	}

	public function set_network_measurement_protocol_secret( $value ) {
		$data = array();
		if ( empty( $this->network ) ) {
			$data['measurement_protocol_secret'] = $value;
		} else {
			$data                                = $this->network;
			$data['measurement_protocol_secret'] = $value;
		}

		$this->network = $data;
		$this->set_network_analytics_profile( $data );
	}

	public function delete_manual_v4_id() {
		if ( ! empty( $this->profile ) && ! empty( $this->profile['manual_v4'] ) ) {
			unset( $this->profile['manual_v4'] );
			$this->set_analytics_profile( $this->profile );
		}
	}

	public function delete_network_manual_v4_id() {
		if ( ! empty( $this->network ) && ! empty( $this->network['manual_v4'] ) ) {
			unset( $this->network['manual_v4'] );
			$this->set_network_analytics_profile( $this->network );
		}
	}

	public function get_manual_v4_id() {
		return ! empty( $this->profile['manual_v4'] ) ? monsterinsights_is_valid_v4_id( $this->profile['manual_v4'] ) : '';
	}

	public function get_network_manual_v4_id() {
		return ! empty( $this->network['manual_v4'] ) ? monsterinsights_is_valid_v4_id( $this->network['manual_v4'] ) : '';
	}

	public function get_v4_id() {
		return ! empty( $this->profile['v4'] ) ? monsterinsights_is_valid_v4_id( $this->profile['v4'] ) : '';
	}

	public function get_network_v4_id() {
		return ! empty( $this->network['v4'] ) ? monsterinsights_is_valid_v4_id( $this->network['v4'] ) : '';
	}

	public function get_viewname() {
		return ! empty( $this->profile['viewname'] ) ? $this->profile['viewname'] : '';
	}

	public function get_network_viewname() {
		return ! empty( $this->network['viewname'] ) ? $this->network['viewname'] : '';
	}

	public function get_accountid() {
		return ! empty( $this->profile['a'] ) ? $this->profile['a'] : '';
	}

	public function get_network_accountid() {
		return ! empty( $this->network['a'] ) ? $this->network['a'] : '';
	}

	public function get_propertyid() {
		return ! empty( $this->profile['w'] ) ? $this->profile['w'] : '';
	}

	public function get_network_propertyid() {
		return ! empty( $this->network['w'] ) ? $this->network['w'] : '';
	}

	public function get_viewid() { // also known as profileID
		return ! empty( $this->profile['p'] ) ? $this->profile['p'] : '';
	}

	public function get_network_viewid() { // also known as profileID
		return ! empty( $this->network['p'] ) ? $this->network['p'] : '';
	}

	public function get_key() {
		return ! empty( $this->profile['key'] ) ? $this->profile['key'] : '';
	}

	public function get_network_key() {
		return ! empty( $this->network['key'] ) ? $this->network['key'] : '';
	}

	public function get_token() {
		return ! empty( $this->profile['token'] ) ? $this->profile['token'] : '';
	}

	public function get_network_token() {
		return ! empty( $this->network['token'] ) ? $this->network['token'] : '';
	}

	public function get_referral_url() {
		$auth = MonsterInsights()->auth;

		if ( $this->is_authed() ) {
			$acc_id      = $auth->get_accountid();
			$view_id     = $auth->get_viewid();
			$property_id = $auth->get_propertyid();
		} else if ( $this->is_network_authed() ) {
			$acc_id      = $auth->get_network_accountid();
			$view_id     = $auth->get_network_viewid();
			$property_id = $auth->get_network_propertyid();
		}

		if ( ! empty( $acc_id ) && ! empty( $view_id ) && ! empty( $property_id ) ) {
			$format = 'p%2$s';

			return sprintf( $format, $acc_id, $property_id, $view_id );
		}

		return '';
	}
}
