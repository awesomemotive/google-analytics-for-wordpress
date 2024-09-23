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

	private $schema;

	private function __construct() {
		$this->is_debug       = monsterinsights_is_debug_mode();
		$this->measurement_id = monsterinsights_get_v4_id_to_output();

		$this->schema = array(
			'currency'       => 'string',
			'value'          => 'money',
			'coupon'         => 'string',
			'transaction_id' => 'string',
			'affiliation'    => 'string',
			'shipping'       => 'double',
			'tax'            => 'double',
			'user_id'        => 'string',
			'items'          => array(
				'item_id'        => 'string',
				'item_name'      => 'string',
				'affiliation'    => 'string',
				'coupon'         => 'string',
				'currency'       => 'string',
				'discount'       => 'double',
				'index'          => 'integer',
				'item_brand'     => 'string',
				'item_category'  => 'string',
				'item_list_id'   => 'string',
				'item_list_name' => 'string',
				'item_variant'   => 'string',
				'location_id'    => 'string',
				'price'          => 'money',
				'quantity'       => 'integer',
			),
		);
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
				'api_secret'     => apply_filters('monsterinsights_get_mp_call_secret', $api_secret),
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

	private function sanitize_event( $params, $schema ) {
		$sanitized_params = array();

		foreach ( $params as $key => $value ) {
			if ( ! array_key_exists( $key, $schema ) ||
			     ( ! is_array( $value ) && gettype( $value ) === $schema[ $key ] )
			) {
				$sanitized_params[ $key ] = $value;
				continue;
			}

			if ( is_array( $value ) && is_array( $schema[ $key ] ) ) {
				$sanitized_params[ $key ] = array();
				foreach ( $value as $item_index => $item ) {
					$sanitized_params[ $key ][ $item_index ] = $this->sanitize_event( $item, $schema[ $key ] );
				}
				continue;
			}

			switch ( $schema[ $key ] ) {
				case 'string':
					$sanitized_params[ $key ] = (string) $value;
					break;

				case 'double':
					$sanitized_params[ $key ] = (float) $value;
					break;

				case 'integer':
					$sanitized_params[ $key ] = (int) $value;
					break;

				case 'money':
					$sanitized_params[ $key ] = MonsterInsights_eCommerce_Helper::round_price( $value );
					break;
			}
		}

		return $sanitized_params;
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
			$out['user_id'] = (string) $args['user_id'];
		}

		foreach ( $out['events'] as $event_index => $event ) {
			$sanitized_event         = array();
			$sanitized_event['name'] = (string) $event['name'];

			if ( ! empty( $event['params'] ) ) {
				$sanitized_event['params'] = $this->sanitize_event( $event['params'], $this->schema );
			}

			$out['events'][ $event_index ] = $sanitized_event;
		}

		return $out;
	}

	private function request( $args ) {
		if ( empty( $this->measurement_id ) ) {
			return;
		}

        $session_id = monsterinsights_get_browser_session_id( $this->measurement_id );

		$defaults = array(
			'client_id' => $this->get_client_id( $args ),
			'events'    => array(),
			'consent' => array(
				'ad_personalization' => 'GRANTED',
			),
		);

		$body = $this->validate_args( $args, $defaults );

        foreach ( $body['events'] as $index => $event ) {

            //  Provide a default session id if not set already.
            if ( !empty( $session_id ) && empty( $body['events'][$index]['params']['session_id'] ) ) {
                $body['events'][$index]['params']['session_id'] = $session_id;
            }

            if ( $this->is_debug ) {
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
		if ( ( isset( $_SERVER["HTTP_X_PURPOSE"] ) && ( 'prefetch' === strtolower( sanitize_text_field($_SERVER["HTTP_X_PURPOSE"]) ) ) ) ||
		     ( isset( $_SERVER["HTTP_X_MOZ"] ) && ( 'prefetch' === strtolower( sanitize_text_field($_SERVER["HTTP_X_MOZ"]) ) ) ) ) {
			return;
		}

		return $this->request( $args );
	}
}

function monsterinsights_mp_collect_v4( $args ) {
	return MonsterInsights_Measurement_Protocol_V4::get_instance()->collect( $args );
}
