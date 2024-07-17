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
	$settings    = array();
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
 * @param string $key The setting key to retrieve.
 * @param mixed $default The default value of the setting key to retrieve.
 *
 * @return string       The value of the setting.
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_get_option( $key = '', $default = false ) {
	global $monsterinsights_settings;
	$value = ! empty( $monsterinsights_settings[ $key ] ) ? $monsterinsights_settings[ $key ] : $default;
	$value = apply_filters( 'monsterinsights_get_option', $value, $key, $default );

	return apply_filters( 'monsterinsights_get_option_' . $key, $value, $key, $default );
}

/**
 * Helper method for getting the V4 string.
 *
 * @return string The V4 ID to use.
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_get_v4_id() {
	// Allow short circuiting (for staging sites)
	if ( defined( 'MONSTERINSIGHTS_DISABLE_TRACKING' ) && MONSTERINSIGHTS_DISABLE_TRACKING ) {
		return '';
	}

	// Try getting it from the auth V4
	$v4_id = MonsterInsights()->auth->get_v4_id();

	// If that didn't work, try the manual V4 at the site level
	if ( empty( $v4_id ) ) {
		$v4_id = MonsterInsights()->auth->get_manual_v4_id();
		// If that didn't work try getting it from the network
		if ( empty( $v4_id ) ) {
			$v4_id = monsterinsights_get_network_v4_id();
			// If that didn't work, try getting it from the overall constant. If it's not there, leave it blank
			if ( empty( $v4_id ) ) {
				$v4_id = defined( 'MONSTERINSIGHTS_GA_V4_ID' ) && MONSTERINSIGHTS_GA_V4_ID ? monsterinsights_is_valid_v4_id( MONSTERINSIGHTS_GA_V4_ID ) : '';
			}
		}
	}

	// Feed through the filter
	$pre_filter = $v4_id;
	$v4_id      = apply_filters( 'monsterinsights_get_v4_id', $v4_id );

	// Only run through monsterinsights_is_valid_v4 if it's different than pre-filter
	return $pre_filter === $v4_id ? $v4_id : monsterinsights_is_valid_v4_id( $v4_id );
}

/**
 * Helper method for getting the network V4 string.
 *
 * @return string The V4 ID to use.
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_get_network_v4_id() {
	if ( ! is_multisite() ) {
		return '';
	}

	// First try network auth V4
	$v4_id = MonsterInsights()->auth->get_network_v4_id();
	if ( ! empty( $v4_id ) ) {
		return $v4_id;
	}

	// Then try manual network V4
	$v4_id = MonsterInsights()->auth->get_network_manual_v4_id();
	if ( ! empty( $v4_id ) ) {
		return $v4_id;
	}

	// See if the constant is defined
	if ( defined( 'MONSTERINSIGHTS_MS_GA_V4_ID' ) && monsterinsights_is_valid_v4_id( MONSTERINSIGHTS_MS_GA_V4_ID ) ) {
		return MONSTERINSIGHTS_MS_GA_V4_ID;
	}

	return '';
}

/**
 * Helper method for getting the UA string that's output on the frontend.
 *
 * @param array $args Allow calling functions to give args to use in future applications.
 *
 * @return string The UA to use on frontend.
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_get_v4_id_to_output( $args = array() ) {
	$v4_id = monsterinsights_get_v4_id();
	$v4_id = apply_filters( 'monsterinsights_get_v4_id_to_output', $v4_id, $args );

	return monsterinsights_is_valid_v4_id( $v4_id );
}

/**
 * Helper method for updating a setting's value.
 *
 * @param string $key The setting key.
 * @param string $value The value to set for the key.
 *
 * @return boolean True if updated, false if not.
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_update_option( $key = '', $value = false ) {

	// If no key, exit
	if ( empty( $key ) ) {
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
	$did_update       = false;
	//if ( $update_network_option ) {
	//    $did_update = update_site_option( $option_name, $settings );
	//} else {
	$did_update = update_option( $option_name, $settings );
	//}

	// If it updated, let's update the global variable
	if ( $did_update ) {
		global $monsterinsights_settings;
		$monsterinsights_settings[ $key ] = $value;
	}

	return $did_update;
}

/**
 * Helper method for deleting a setting's value.
 *
 * @param string $key The setting key.
 *
 * @return boolean True if removed, false if not.
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_delete_option( $key = '' ) {
	// If no key, exit
	if ( empty( $key ) ) {
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
	if ( isset( $settings[ $key ] ) ) {
		unset( $settings[ $key ] );
	}

	$did_update = false;
	//if ( $update_network_option ) {
	//    $did_update = update_site_option( 'monsterinsights_settings', $settings );
	//} else {
	$did_update = update_option( $option_name, $settings );
	//}

	// If it updated, let's update the global variable
	if ( $did_update ) {
		global $monsterinsights_settings;
		$monsterinsights_settings = $settings;
	}

	return $did_update;
}

/**
 * Helper method for deleting multiple settings value.
 *
 * @param string $key The setting key.
 *
 * @return boolean True if removed, false if not.
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_delete_options( $keys = array() ) {
	// If no keys, exit
	if ( empty( $keys ) || ! is_array( $keys ) ) {
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
		if ( isset( $settings[ $key ] ) ) {
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
	if ( $did_update ) {
		global $monsterinsights_settings;
		$monsterinsights_settings = $settings;
	}

	return $did_update;
}

function monsterinsights_sanitize_tracking_id( $id ) {
	$id = (string) $id; // Rare case, but let's make sure it never happens.
	$id = trim( $id );

	if ( empty( $id ) ) {
		return '';
	}

	// Replace all type of dashes (n-dash, m-dash, minus) with normal dashes.
	$id = str_replace( array( '–', '—', '−' ), '-', $id );

	return $id;
}

/**
 * Is this a valid GT code
 *
 * @param string $gt_code
 *
 * @return bool
 */
function monsterinsights_is_valid_gt( $gt_code = '' ) {
	return (bool) preg_match( '/^GT-[a-zA-Z0-9]{5,}$/', $gt_code );
}

function monsterinsights_is_valid_v4_id( $v4_code = '' ) {
	$v4_code = monsterinsights_sanitize_tracking_id( $v4_code );

	if (
		preg_match( '/G-[A-Za-z\d]+/', $v4_code ) ||
		monsterinsights_is_valid_gt( $v4_code )
	) {
		return strtoupper( $v4_code );
	}

	return '';
}

/**
 * Helper method for getting the license information.
 *
 * @param string $key The setting key to retrieve.
 * @param mixed $default_value The default value of the setting key to retrieve.
 *
 * @return string       The value of the setting.
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_get_license() {
	$license = MonsterInsights()->license->get_site_license();
	$license = $license ? $license : MonsterInsights()->license->get_network_license();
	$default = MonsterInsights()->license->get_default_license_key();
	if ( empty( $license ) && ! empty( $default ) ) {
		$license        = array();
		$license['key'] = MonsterInsights()->license->get_default_license_key();
	}

	return $license;
}

/**
 * Helper method for getting the license key.
 *
 * @param string $key The setting key to retrieve.
 * @param mixed $default_value The default value of the setting key to retrieve.
 *
 * @return string       The value of the setting.
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_get_license_key() {
	if ( monsterinsights_is_pro_version() ) {
		return MonsterInsights()->license->get_license_key();
	}

	return '';
}

function monsterinsights_get_option_name() {
	//if ( monsterinsights_is_pro_version() ) {
	return 'monsterinsights_settings';
	//} else {
	//	return 'monsterinsights_settings';
	//}
}

/**
 * Export necessary settings to export as JSON.
 *
 * @return string
 */
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

	// Get site notes.
	$settings['site_notes'] = monsterinsights_get_site_notes_to_export();

	return wp_json_encode( $settings );
}

/**
 * Always return 'gtag' when grabbing the tracking mode.
 *
 * @param string $value The value to override.
 *
 * @return string
 */
function monsterinsights_force_tracking_mode( $value ) {
	return 'gtag';
}

add_filter( 'monsterinsights_get_option_tracking_mode', 'monsterinsights_force_tracking_mode' );

/**
 * Always return 'js' when grabbing the events mode.
 *
 * @param string $value The value to override.
 *
 * @return string
 */
function monsterinsights_force_events_mode( $value ) {
	return 'js';
}

add_filter( 'monsterinsights_get_option_events_mode', 'monsterinsights_force_events_mode' );

/**
 * Prepare site notes to export.
 */
function monsterinsights_get_site_notes_to_export() {
	$notes_db = new MonsterInsights_Site_Notes_DB_Base();

	$note_items = $notes_db->get_items( array(
		'per_page' => -1,
		'orderby'  => 'id',
		'order'    => 'asc',
		'page'     => 1,
	) );

	$notes = array();

	foreach ( $note_items['items'] as $note_item ) {
		$notes[] = array(
			'note_title'    => $note_item['note_title'],
			'note_date'     => $note_item['note_date_ymd'],
			'important'     => $note_item['important'],
			'category_name' => empty( $note_item['category']['name'] ) ? '' : html_entity_decode( $note_item['category']['name'] ),
		);
	}

	$categories = $notes_db->get_categories( array(
		'per_page' => -1,
		'page'     => 1,
		'orderby'  => 'term_id',
		'order'    => 'asc',
	) );

	$note_categories = array();

	if ( is_array( $categories ) && ! empty( $categories ) ) {
		foreach ( $categories as $category ) {
			$note_categories[] = array(
				'name'  => html_entity_decode( $category['name'] ),
				'color' => $category['background_color'],
			);
		}
	}

	return array(
		'notes'      => $notes,
		'categories' => $note_categories,
	);
}
