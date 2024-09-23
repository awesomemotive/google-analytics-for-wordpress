<?php
/**
 * Addons class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Callback to output the MonsterInsights addons page.
 *
 * @since 6.0.0
 */
function monsterinsights_addons_page() {
	echo monsterinsights_ublock_notice(); // phpcs:ignore
	monsterinsights_settings_error_page( 'monsterinsights-addons' );
	monsterinsights_settings_inline_js();
}

/**
 * Retrieves addons from the stored transient or remote server.
 *
 * @return bool | array    false | Array of licensed and unlicensed Addons.
 * @since 6.0.0
 *
 */
function monsterinsights_get_addons() {

	// Get license key and type.
	$key  = '';
	$type = 'lite';
	if ( monsterinsights_is_pro_version() ) {
		$key  = is_network_admin() ? MonsterInsights()->license->get_network_license_key() : MonsterInsights()->license->get_site_license_key();
		$type = is_network_admin() ? MonsterInsights()->license->get_network_license_type() : MonsterInsights()->license->get_site_license_type();
	}

	// Get addons data from transient or perform API query if no transient.
	if ( false === ( $addons = get_transient( '_monsterinsights_addons' ) ) ) {
		$addons = monsterinsights_get_addons_data( $key );
	}

	// If no Addons exist, return false
	if ( ! $addons ) {
		return false;
	}

	// Iterate through Addons, to build two arrays:
	// - Addons the user is licensed to use,
	// - Addons the user isn't licensed to use.
	$results = array(
		'licensed'   => array(),
		'unlicensed' => array(),
	);
	foreach ( (array) $addons as $i => $addon ) {

		// Determine whether the user is licensed to use this Addon or not.
		if (
			empty( $type ) ||
			( in_array( 'Pro', $addon->categories ) && ( $type != 'pro' && $type != 'master' ) ) ||
			( in_array( 'Plus', $addon->categories ) && $type != 'plus' && $type != 'pro' && $type != 'master' ) ||
			( in_array( 'Basic', $addon->categories ) && ( $type != 'basic' && $type != 'plus' && $type != 'pro' && $type != 'master' ) )
		) {
			// Unlicensed
			$results['unlicensed'][] = $addon;
			continue;
		}

		// Licensed
		$results['licensed'][] = $addon;

	}

	// Return Addons, split by licensed and unlicensed.
	return $results;

}

/**
 * Pings the remote server for addons data.
 *
 * @param string $key The user license key.
 *
 * @return  array               Array of addon data otherwise.
 * @since 6.0.0
 *
 */
function monsterinsights_get_addons_data( $key ) {
	// Get Addons
	// If the key is valid, we'll get personalised upgrade URLs for each Addon (if necessary) and plugin update information.
	if ( monsterinsights_is_pro_version() && $key ) {
		$addons = monsterinsights_perform_remote_request( 'get-addons-data-v600', array( 'tgm-updater-key' => $key ) );
	} else {
		$addons = monsterinsights_get_all_addons_data();
	}

	// If there was an API error, set transient for only 10 minutes.
	if ( ! $addons ) {
		set_transient( '_monsterinsights_addons', false, 10 * MINUTE_IN_SECONDS );

		return false;
	}

	// If there was an error retrieving the addons, set the error.
	if ( isset( $addons->error ) ) {
		set_transient( '_monsterinsights_addons', false, 10 * MINUTE_IN_SECONDS );

		return false;
	}

	// Otherwise, our request worked. Save the data and return it.
	set_transient( '_monsterinsights_addons', $addons, 4 * HOUR_IN_SECONDS );

	return $addons;

}

/**
 * Get all addons without a license, for lite users.
 *
 * @return array|bool|mixed|object
 */
function monsterinsights_get_all_addons_data() {

    $body = array(
        'tgm-updater-action'     => 'get-all-addons-data',
        'tgm-updater-key'        => '',
        'tgm-updater-wp-version' => get_bloginfo( 'version' ),
        'tgm-updater-referer'    => site_url(),
        'tgm-updater-mi-version' => MONSTERINSIGHTS_VERSION,
        'tgm-updater-is-pro'     => false,
    );

    return monsterinsights_perform_remote_request( 'verify-key', $body );
}

function monsterinsights_get_addon( $installed_plugins, $addons_type, $addon, $slug ) {
	$active          = false;
	$installed       = false;

	$slug = apply_filters( 'monsterinsights_addon_slug', $slug );

	$plugin_basename = monsterinsights_get_plugin_basename_from_slug( $slug );

	if ( isset( $installed_plugins[ $plugin_basename ] ) ) {
		$installed = true;

		if ( is_multisite() && is_network_admin() ) {
			$active = is_plugin_active_for_network( $plugin_basename );
		} else {
			$active = is_plugin_active( $plugin_basename );
		}
	}
	if ( empty( $addon->url ) ) {
		$addon->url = '';
	}

	$active_version = false;
	if ( $active ) {
		if ( ! empty( $installed_plugins[ $plugin_basename ]['Version'] ) ) {
			$active_version = $installed_plugins[ $plugin_basename ]['Version'];
		}
	}

	$addon->type           = $addons_type;
	$addon->installed      = $installed;
	$addon->active_version = $active_version;
	$addon->active         = $active;
	$addon->basename       = $plugin_basename;

	return $addon;
}

/**
 * Retrieve the plugin basename from the plugin slug.
 *
 * @param string $slug The plugin slug.
 *
 * @return string      The plugin basename if found, else the plugin slug.
 * @since 6.0.0
 *
 */
function monsterinsights_get_plugin_basename_from_slug( $slug ) {
	$keys = array_keys( get_plugins() );

	foreach ( $keys as $key ) {
		if ( preg_match( '|^' . $slug . '|', $key ) ) {
			return $key;
		}
	}

	return $slug;

}
