<?php
/**
 * Option functions.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Options
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function monsterinsights_get_options() {
	$settings = array();
	$option_name = monsterinsights_get_option_name();
	//$settings             = get_site_option( $option_name );
	//$use_network_settings = ! empty( $use_network_settings['use_network_settings'] ) ? true : false;
	//$is_network           = is_multisite();

	//if ( $is_network && $use_network_settings ) {
	//    return $settings;
	//} else if ( $is_network ) {
		$settings = get_option( $option_name );
	//} else {
	//    return $settings;
	//}
	if ( empty( $settings ) || ! is_array( $settings ) ) {
		$settings = array();
	}
	return $settings;
}

/**
 * Helper method for getting a setting's value. Falls back to the default
 * setting value if none exists in the options table.
 *
 * @since 6.0.0
 * @access public
 *
 * @param string $key   The setting key to retrieve.
 * @param mixed $default   The default value of the setting key to retrieve.
 * @return string       The value of the setting.
 */
function monsterinsights_get_option( $key = '', $default = false ) {
	global $monsterinsights_settings;
	$value = ! empty( $monsterinsights_settings[ $key ] ) ? $monsterinsights_settings[ $key ] : $default;
	$value = apply_filters( 'monsterinsights_get_option', $value, $key, $default );
	return apply_filters( 'monsterinsights_get_option_' . $key, $value, $key, $default );
}

/**
 * Helper method for getting the UA string.
 *
 * @since 6.0.0
 * @access public
 *
 * @return string The UA to use.
 */
function monsterinsights_get_ua() {
	$ua = '';
	if ( is_multisite() ) {
		if ( defined( 'MONSTERINSIGHTS_MS_GA_UA' ) && monsterinsights_is_valid_ua( MONSTERINSIGHTS_MS_GA_UA ) ) {
			$ua = MONSTERINSIGHTS_MS_GA_UA;
		}
	}

	if ( is_multisite() ) {
		$ua_code = monsterinsights_is_valid_ua( get_site_option( 'monsterinsights_network_manual_ua_code', '' ) );
		if ( $ua_code ) {
			$ua = $ua_code;
		}
	}

	if ( defined( 'MONSTERINSIGHTS_GA_UA' ) && monsterinsights_is_valid_ua( MONSTERINSIGHTS_GA_UA ) ) {
		$ua = MONSTERINSIGHTS_GA_UA;
	}

	$ua_code = monsterinsights_is_valid_ua( monsterinsights_get_option( 'analytics_profile_code', '' ) );

	if ( $ua_code ) {
		$ua = $ua_code;
	}

	$manual_ua_code = monsterinsights_is_valid_ua( monsterinsights_get_option( 'manual_ua_code', '' ) );

	if ( $manual_ua_code ) {
		$ua = $manual_ua_code;
	}

	$ua = apply_filters( 'monsterinsights_get_ua', $ua );

	return monsterinsights_is_valid_ua( $ua );
}

/**
 * Helper method for getting the UA string that's output on the frontend.
 *
 * @since 6.0.0
 * @access public
 *
 * @param array $args Allow calling functions to give args to use in future applications.
 * @return string The UA to use on frontend.
 */
function monsterinsights_get_ua_to_output( $args = array() ) {
	$ua = monsterinsights_get_ua();
	$ua = apply_filters( 'monsterinsights_get_ua_to_output', $ua, $args );
	return monsterinsights_is_valid_ua( $ua );
}

/**
 * Helper method for updating a setting's value.
 *
 * @since 6.0.0
 * @access public
 *
 * @param string $key   The setting key.
 * @param string $value The value to set for the key.
 * @return boolean True if updated, false if not.
 */
function monsterinsights_update_option( $key = '', $value = false ) {

	// If no key, exit
	if ( empty( $key ) ){
		return false;
	}

	if ( empty( $value ) ) {
		$remove_option = monsterinsights_delete_option( $key );
		return $remove_option;
	}

	$option_name = monsterinsights_get_option_name();

	// First let's grab the current settings

	// if on network panel or if on single site using network settings
	//$settings              = get_site_option( $option_name );
	//$use_network_settings  = ! empty( $use_network_settings['use_network_settings'] ) ? true : false;
	//$is_network            = is_multisite();
	//$update_network_option = true;
	//if ( ! is_network_admin() && ! ( $is_network && $use_network_settings ) ) {
	   $settings = get_option( $option_name );
	//   $update_network_option = false;
	//}

	if ( ! is_array( $settings ) ) {
		$settings = array();
	}

	// Let's let devs alter that value coming in
	$value = apply_filters( 'monsterinsights_update_option', $value, $key );

	// Next let's try to update the value
	$settings[ $key ] = $value;
	$did_update = false;
	//if ( $update_network_option ) {
	//    $did_update = update_site_option( $option_name, $settings );
	//} else {
		$did_update = update_option( $option_name, $settings );
	//}

	// If it updated, let's update the global variable
	if ( $did_update ){
		global $monsterinsights_settings;
		$monsterinsights_settings[ $key ] = $value;
	}

	return $did_update;
}

 /**
 * Helper method for deleting a setting's value.
 *
 * @since 6.0.0
 * @access public
 *
 * @param string $key   The setting key.
 * @return boolean True if removed, false if not.
 */
function monsterinsights_delete_option( $key = '' ) {
	// If no key, exit
	if ( empty( $key ) ){
		return false;
	}

	$option_name = monsterinsights_get_option_name();

	// First let's grab the current settings

	// if on network panel or if on single site using network settings
	//$settings              = get_site_option( $option_name );
	//$use_network_settings  = ! empty( $use_network_settings['use_network_settings'] ) ? true : false;
	//$is_network            = is_multisite();
	//$update_network_option = true;
	//if ( ! is_network_admin() && ! ( $is_network && $use_network_settings ) ) {
	   $settings = get_option( $option_name );
	//   $update_network_option = false;
	//}

	// Next let's try to remove the key
	if( isset( $settings[ $key ] ) ) {
		unset( $settings[ $key ] );
	}

	$did_update = false;
	//if ( $update_network_option ) {
	//    $did_update = update_site_option( 'monsterinsights_settings', $settings );
	//} else {
		$did_update = update_option( $option_name, $settings );
	//}

	// If it updated, let's update the global variable
	if ( $did_update ){
		global $monsterinsights_settings;
		$monsterinsights_settings = $settings;
	}

	return $did_update;
}

 /**
 * Helper method for deleting multiple settings value.
 *
 * @since 6.0.0
 * @access public
 *
 * @param string $key   The setting key.
 * @return boolean True if removed, false if not.
 */
function monsterinsights_delete_options( $keys = array() ) {
	// If no keys, exit
	if ( empty( $keys ) || ! is_array( $keys ) ){
		return false;
	}

	$option_name = monsterinsights_get_option_name();

	// First let's grab the current settings

	// if on network panel or if on single site using network settings
	//$settings              = get_site_option( $option_name );
	//$use_network_settings  = ! empty( $use_network_settings['use_network_settings'] ) ? true : false;
	//$is_network            = is_multisite();
	//$update_network_option = true;
	//if ( ! is_network_admin() && ! ( $is_network && $use_network_settings ) ) {
	   $settings = get_option( $option_name );
	//   $update_network_option = false;
	//}

	// Next let's try to remove the keys
	foreach ( $keys as $key ) {
		if( isset( $settings[ $key ] ) ) {
			unset( $settings[ $key ] );
		}
	}

	$did_update = false;
	//if ( $update_network_option ) {
	//    $did_update = update_site_option( 'monsterinsights_settings', $settings );
	//} else {
		$did_update = update_option( $option_name, $settings );
	//}

	// If it updated, let's update the global variable
	if ( $did_update ){
		global $monsterinsights_settings;
		$monsterinsights_settings = $settings;
	}

	return $did_update;
}

/**
 * Helper method for getting the license information.
 *
 * @since 6.0.0
 * @access public
 *
 * @param string $key   The setting key to retrieve.
 * @param mixed $default_value   The default value of the setting key to retrieve.
 * @return string       The value of the setting.
 */
function monsterinsights_get_license() {
	$license = false;
	if ( defined( 'MONSTERINSIGHTS_LICENSE_KEY' ) && is_string( MONSTERINSIGHTS_LICENSE_KEY ) && strlen( MONSTERINSIGHTS_LICENSE_KEY ) > 10 ) {
		$license = array( 'key' => MONSTERINSIGHTS_LICENSE_KEY );
	} else if ( is_multisite() && monsterinsights_is_network_active() ){
		$network_license = get_site_option( 'monsterinsights_license' );
		if ( ! empty( $network_license['key'] ) && is_string( $network_license['key'] ) && strlen( $network_license['key'] ) > 10 ) {
			$license = $network_license;
		} else {
			$site_license = get_option( 'monsterinsights_license' );
			if ( ! empty( $site_license['key'] ) && is_string( $site_license['key'] ) && strlen( $site_license['key'] ) > 10 ) {
				$license = $site_license;
			}
		}
	} else {
		$site_license = get_option( 'monsterinsights_license' );
		if ( ! empty( $site_license['key'] ) && is_string( $site_license['key'] ) && strlen( $site_license['key'] ) > 10 ) {
			$license = $site_license;
		}
	}

	return apply_filters( 'monsterinsights_license', $license );
}

/**
 * Helper method for getting the license key.
 *
 * @since 6.0.0
 * @access public
 *
 * @param string $key   The setting key to retrieve.
 * @param mixed $default_value   The default value of the setting key to retrieve.
 * @return string       The value of the setting.
 */
function monsterinsights_get_license_key() {
	$license = false;
	if ( defined( 'MONSTERINSIGHTS_LICENSE_KEY' ) && is_string( MONSTERINSIGHTS_LICENSE_KEY ) && strlen( MONSTERINSIGHTS_LICENSE_KEY ) > 10 ) {
		$license = MONSTERINSIGHTS_LICENSE_KEY;
	} else {
		$license = monsterinsights_get_license();
		if ( ! empty( $license['key'] ) && is_string( $license['key'] ) && strlen( $license['key'] ) > 10 ) {
			$license = $license['key'];
		} else {
			$license = false;
		}
	}

	return apply_filters( 'monsterinsights_license_key', $license );
}

/**
 * Helper method for updating the license key.
 *
 * @since 6.0.0
 * @access public
 *
 * @param string $key   The setting key.
 * @param string $value The value to set for the key.
 * @return boolean True if updated, false if not.
 */
function monsterinsights_update_license_key( $license_key = false, $network = false, $override = false ) {
	if ( ! $license_key || ! is_string( $license_key ) || ! strlen( $license_key ) > 10  ) {
		return false;
	}

	if ( $network && is_multisite() && ( is_network_admin() || $override ) ) {
		update_site_option( 'monsterinsights_license', $license_key );
		return true;
	} else {
		update_option( 'monsterinsights_license', $license_key );
		return true;
	}
}

 /**
 * Helper method for deleting the license key.
 *
 * @since 6.0.0
 * @access public
 *
 * @param string $key   The setting key.
 * @return boolean True if removed, false if not.
 */
function monsterinsights_delete_license_key( $network = false, $override = false ) {
	if ( $network && is_multisite() && ( is_network_admin() || $override ) ) {
		delete_site_option( 'monsterinsights_license' );
		return true;
	} else {
		delete_option( 'monsterinsights_license' );
		return true;
	}
}


/**
 * Returns the license key type for MonsterInsights.
 *
 * @access public
 * @since 6.0.0
 *
 * @return string $type The user's license key type for MonsterInsights.
 */
function monsterinsights_get_license_key_type() {
	$type = false;
	$license = monsterinsights_get_license();
	if ( ! empty( $license['type'] ) && is_string( $license['type'] ) ) {
		if ( in_array( $license['type'], array( 'master', 'pro', 'plus', 'basic' ) ) ) {
			$type = $license['type'];
		}
	}
	return $type;
}

/**
 * Returns possible license key error flag.
 *
 * @access public
 * @since 6.0.0
 *
 * @return bool True if there are license key errors, false otherwise.
 */
function monsterinsights_get_license_key_errors() {
	$errors = false;
	$license = monsterinsights_get_license();
	if ( ! empty( $license['type'] ) && is_string( $license['type'] ) && strlen( $license['type'] ) > 3 ) {
		if ( ( isset( $license['is_expired'] )  && $license['is_expired'] ) 
		  || ( isset( $license['is_disabled'] ) && $license['is_disabled'] )
		  || ( isset( $license['is_invalid'] )  && $license['is_invalid'] ) ) {
			$errors = true;
		}
	}
	return $errors;
}

/**
 * Is valid ua code.
 *
 * @access public
 * @since 6.0.0
 *
 * @param string $ua_code UA code to check validity for.
 *
 * @return string|false Return cleaned ua string if valid, else returns false.
 */
function monsterinsights_is_valid_ua( $ua_code = '' ) {
	$ua_code = (string) $ua_code; // Rare case, but let's make sure it never happens.
	$ua_code = trim( $ua_code );

	if ( empty( $ua_code ) ) {
		return '';
	}
	
	// Replace all type of dashes (n-dash, m-dash, minus) with normal dashes.
	$ua_code = str_replace( array( '–', '—', '−' ), '-', $ua_code );
	
	if ( preg_match( "/^(UA|YT|MO)-\d{4,}-\d+$/", strval( $ua_code ) ) ) {
		return $ua_code;
	} else {
		return '';
	}
}

function monsterinsights_get_option_name() {
	//if ( monsterinsights_is_pro_version() ) {
		return 'monsterinsights_settings';
	//} else {
	//	return 'monsterinsights_settings';
	//}
}

function monsterinsights_export_settings() {
	$settings = monsterinsights_get_options();
	$exclude  = array( 
				'analytics_profile',
				'analytics_profile_code',
				'analytics_profile_name',
				'oauth_version',
				'cron_last_run',
				'monsterinsights_oauth_status',
	);

	foreach ( $exclude as $e ) {
		if ( ! empty( $settings[ $e ] ) ) {
			unset( $settings[ $e ] );
		}
	}
	return wp_json_encode( $settings );
}