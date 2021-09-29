<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class MonsterInsights_Measurement_Protocol_V4 {
	private static $instance;

	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private $is_debug;

	private $measurement_id;

	private function __construct() {
		$this->is_debug       = monsterinsights_is_debug_mode();
		$this->measurement_id = monsterinsights_get_v4_id_to_output();
	}

	private function get_base_url() {
		return 'https://www.google-analytics.com/mp/collect';
	}

	private function get_url() {
		$api_secret = is_multisite() && is_network_admin()
			? MonsterInsights()->auth->get_network_measurement_protocol_secret()
			: MonsterInsights()->auth->get_measurement_protocol_secret();

		return add_query_arg(
			array(
				'api_secret'     => $api_secret,
				'measurement_id' => $this->measurement_id,
			),
			$this->get_base_url()
		);
	}

	private function get_client_id( $args ) {
		if ( ! empty( $args['client_id'] ) ) {
			return $args['client_id'];
		}

		$payment_id = 0;
		if ( ! empty( $args['payment_id'] ) ) {
			$payment_id = $args['payment_id'];
		}

		return monsterinsights_get_client_id( $payment_id );
	}

	private function validate_args( $args, $defaults ) {
		$out = array();

		foreach ( $defaults as $key => $default ) {
			if ( array_key_exists( $key, $args ) ) {
				$out[ $key ] = $args[ $key ];
			} else {
				$out[ $key ] = $default;
			}
		}

		if ( ! empty( $args['user_id'] ) && monsterinsights_get_option( 'userid', false ) ) {
			$out['user_id'] = $args['user_id'];
		}

		return $out;
	}

	private function request( $args ) {
		if ( empty( $this->measurement_id ) ) {
			return;
		}

		$defaults = array(
			'client_id' => $this->get_client_id( $args ),
			'events'    => array(),
		);

		$body = $this->validate_args( $args, $defaults );

		if ( $this->is_debug ) {
			foreach ( $body['events'] as $index => $event ) {
				$body['events'][ $index ]['params']['debug_mode'] = true;
			}
		}

		$body = apply_filters( 'monsterinsights_mp_v4_api_call', $body );

		return wp_remote_post(
			$this->get_url(),
			array(
				'method'   => 'POST',
				'timeout'  => 5,
				'blocking' => $this->is_debug,
				'body'     => wp_json_encode( $body ),
			)
		);
	}

	public function collect( $args ) {
		// Detect if browser request is a prefetch
		if ( ( isset( $_SERVER["HTTP_X_PURPOSE"] ) && ( 'prefetch' === strtolower( $_SERVER["HTTP_X_PURPOSE"] ) ) ) ||
		     ( isset( $_SERVER["HTTP_X_MOZ"] ) && ( 'prefetch' === strtolower( $_SERVER["HTTP_X_MOZ"] ) ) ) ) {
			return;
		}

		return $this->request( $args );
	}
}

function monsterinsights_mp_collect_v4( $args ) {
	return MonsterInsights_Measurement_Protocol_V4::get_instance()->collect( $args );
}
