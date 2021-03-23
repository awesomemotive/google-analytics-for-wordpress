<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function monsterinsights_get_mp_api_url( ) {
	return 'https://www.google-analytics.com/collect';
}

function monsterinsights_mp_api_call( $args = array() ) {
	$user_agent = '';
	if ( ! empty( $args['user-agent'] ) ) {
		$user_agent = $args['user-agent'];
		unset( $args['user-agent'] );
	}

	$payment_id = 0;
	if ( ! empty( $args['payment_id'] ) ) {
		$payment_id = $args['payment_id'];
		unset( $args['payment_id'] );
	}

	$defaults = array(
		't'  => 'event', // Required: Hit type
		'ec' => '',      // Optional: Event category
		'ea' => '', 	 // Optional: Event Action
		'el' => '', 	 // Optional: Event Label
		'ev' => null, 	 // Optional: Event Value
	);

	$body  = array_merge( $defaults , $args );

	// We want to get the user's IP address when possible
	$ip     = '';
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) && ! filter_var( $_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP ) === false ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! filter_var( $_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP ) === false ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	$ip = apply_filters( 'monsterinsights_mp_api_call_ip', $ip );

	// If possible, let's get the user's language
	$user_language = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) : array();
	$user_language = reset( $user_language );
	$user_language = sanitize_text_field( $user_language );

	$default_body = array(
		// Required: Version
		'v'   => '1',

		// Required: UA code
		'tid' => monsterinsights_get_ua_to_output( array( 'ecommerce' => $args ) ),

		// Required: User visitor ID
		'cid' => monsterinsights_get_client_id( $payment_id ),

		// Required: Type of hit (either pageview or event)
		't'   => 'pageview', // Required - Hit type

		// Optional: Was the event a non-interaction event (for bounce purposes)
		'ni'  => true,

		// Optional: Document Host Name
		'dh'  => str_replace( array( 'http://', 'https://' ), '', site_url() ),

		// Optional: Requested URI
		'dp'  => $_SERVER['REQUEST_URI'],

		// Optional: Page Title
		'dt'  => get_the_title(),

		// Optional: User language
		'ul'  => $user_language,

		// Optional: User IP address
		'uip' => $ip,

		// Optional: User Agent
		'ua'  => ! empty( $user_agent ) ?  $user_agent : $_SERVER['HTTP_USER_AGENT'],

		// Optional: Time of the event
		'z'   => time(),

		// Developer id.
		'did' => 'dZGIzZG',
	);

	$body = wp_parse_args( $body, $default_body );
	$body = apply_filters( 'monsterinsights_mp_api_call', $body );


	// Ensure that the CID is not empty
	if ( empty( $body['cid'] ) ) {
		$body['cid'] = monsterinsights_generate_uuid();
	}

	// Unset empty values to reduce request size
	foreach ( $body as $key => $value ) {
		if ( empty( $value ) ) {
			unset( $body[ $key ] );
		}
	}

	$debug_mode = monsterinsights_is_debug_mode();
	$args = array(
		'method'   => 'POST',
		'timeout'  => '5',
		'blocking' => ( $debug_mode ) ? true : false,
		'body'     => $body,
	);

	if ( ! empty( $user_agent ) ) {
		$args['user-agent'] = $user_agent;
	}

	$response = wp_remote_post( monsterinsights_get_mp_api_url(), $args );

	return $response;
}

function monsterinsights_mp_track_event_call( $args = array() ) {
	// Detect if browser request is a prefetch
	if ( ( isset( $_SERVER["HTTP_X_PURPOSE"] ) && ( 'prefetch' === strtolower( $_SERVER["HTTP_X_PURPOSE"] ) ) ) ||
	     ( isset( $_SERVER["HTTP_X_MOZ"] ) && ( 'prefetch' === strtolower( $_SERVER["HTTP_X_MOZ"] ) ) ) ) {
		return;
	}

	$default_args = array(
		// Change the default type to event
		't'  => 'event',

		// Required: Event Category
		'ec' => '',

		// Required: Event Action
		'ea' => '',

		// Required: Event Label
		'el' => '',

		// Optional: Event Value
		'ev' => null,
	);
	$args         = wp_parse_args( $args, $default_args );

	//$args = apply_filters( 'monsterinsights_mp_track_event_call', $args );

	return monsterinsights_mp_api_call( $args );
}
