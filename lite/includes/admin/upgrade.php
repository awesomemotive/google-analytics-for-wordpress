<?php

/**
 * Ajax handler for grabbing the upgrade url.
 */
function monsterinsights_upgrade_license() {

	check_ajax_referer( 'mi-admin-nonce', 'nonce' );

	// Check for permissions.
	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'You are not allowed to install plugins.', 'google-analytics-for-wordpress' ) ) );
	}

	if ( monsterinsights_is_dev_url( home_url() ) ) {
		wp_send_json_success( array(
			'url' => 'https://www.monsterinsights.com/docs/go-lite-pro/#manual-upgrade',
		) );
	}

	// Check license key.
	$license = monsterinsights_get_license_key();
	if ( empty( $license ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'You are not licensed.', 'google-analytics-for-wordpress' ) ) );
	}

	if ( monsterinsights_is_pro_version() ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Only the Lite version can upgrade.', 'google-analytics-for-wordpress' ) ) );
	}

	$url = esc_url_raw(
		add_query_arg(
			array(
				'page' => 'monsterinsights_settings',
			),
			admin_url( 'admin.php' )
		)
	);

	// Verify pro version is not installed.
	$active = activate_plugin( 'google-analytics-premium/googleanalytics-premium.php', false, false, true );
	if ( ! is_wp_error( $active ) ) {
		// Deactivate plugin.
		deactivate_plugins( plugin_basename( MONSTERINSIGHTS_PLUGIN_FILE ) );
		wp_send_json_error( array(
			'message' => esc_html__( 'Pro version is already installed.', 'google-analytics-for-wordpress' ),
			'reload'  => true,
		) );
	}

	$args = array(
		'plugin_name' => 'MonsterInsights Pro',
		'plugin_slug' => 'pro',
		'plugin_path' => plugin_basename( __FILE__ ),
		'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'pro',
		'remote_url'  => 'https://monsterinsights.com/',
		'version'     => MonsterInsights()->version,
		'key'         => $license,
	);

	$updater = new MonsterInsights_Updater( $args );
	$addons  = $updater->update_plugins_filter( $updater );
	if ( empty( $addons->update->package ) ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'We encountered a problem unlocking the PRO features. Please install the PRO version manually.', 'google-analytics-for-wordpress' ),
		) );
	}

	// Redirect.
	$oth = hash( 'sha512', wp_rand() );
	update_option( 'monsterinsights_one_click_upgrade', $oth );
	$version  = MonsterInsights()->version;
	$file     = $addons->update->package;
	$siteurl  = admin_url();
	$endpoint = admin_url( 'admin-ajax.php' );
	$redirect = admin_url( 'admin.php?page=monsterinsights_settings' );

	$url = add_query_arg( array(
		'key'      => $license,
		'oth'      => $oth,
		'endpoint' => $endpoint,
		'version'  => $version,
		'siteurl'  => $siteurl,
		'redirect' => rawurldecode( base64_encode( $redirect ) ),
		'file'     => rawurldecode( base64_encode( $file ) ),
	), 'https://upgrade.monsterinsights.com' );

	wp_send_json_success( array(
		'url' => $url,
	) );

}

add_action( 'wp_ajax_monsterinsights_upgrade_license', 'monsterinsights_upgrade_license' );

/**
 * Endpoint for one-click upgrade.
 */
function monsterinsights_run_one_click_upgrade() {
	$error = esc_html__( 'Could not install upgrade. Please download from monsterinsights.com and install manually.', 'google-analytics-for-wordpress' );

	// verify params present (oth & download link).
	$post_oth = ! empty( $_REQUEST['oth'] ) ? sanitize_text_field( $_REQUEST['oth'] ) : '';
	$post_url = ! empty( $_REQUEST['file'] ) ? $_REQUEST['file'] : '';
	if ( empty( $post_oth ) || empty( $post_url ) ) {
		wp_send_json_error( $error );
	}
	// Verify oth.
	$oth = get_option( 'monsterinsights_one_click_upgrade' );
	if ( empty( $oth ) ) {
		wp_send_json_error( $error );
	}
	if ( ! hash_equals( $oth, $post_oth ) ) {
		wp_send_json_error( $error );
	}
	// Delete so cannot replay.
	delete_option( 'monsterinsights_one_click_upgrade' );
	// Set the current screen to avoid undefined notices.
	set_current_screen( 'insights_page_monsterinsights_settings' );
	// Prepare variables.
	$url = esc_url_raw(
		add_query_arg(
			array(
				'page' => 'monsterinsights-settings',
			),
			admin_url( 'admin.php' )
		)
	);
	// Verify pro not activated.
	if ( monsterinsights_is_pro_version() ) {
		wp_send_json_success( esc_html__( 'Plugin installed & activated.', 'google-analytics-for-wordpress' ) );
	}
	// Verify pro not installed.
	$active = activate_plugin( 'google-analytics-premium/googleanalytics-premium.php', $url, false, true );
	if ( ! is_wp_error( $active ) ) {
		deactivate_plugins( plugin_basename( MONSTERINSIGHTS_PLUGIN_FILE ) );
		wp_send_json_success( esc_html__( 'Plugin installed & activated.', 'wpforms-lite' ) );
	}
	$creds = request_filesystem_credentials( $url, '', false, false, null );
	// Check for file system permissions.
	if ( false === $creds ) {
		wp_send_json_error( $error );
	}
	if ( ! WP_Filesystem( $creds ) ) {
		wp_send_json_error( $error );
	}
	// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/licensing/skin.php';
	// Do not allow WordPress to search/download translations, as this will break JS output.
	remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );
	// Create the plugin upgrader with our custom skin.
	$installer = new Plugin_Upgrader( new MonsterInsights_Skin() );
	// Error check.
	if ( ! method_exists( $installer, 'install' ) ) {
		wp_send_json_error( $error );
	}

	// Check license key.
	$license = monsterinsights_get_license_key();
	if ( empty( $license ) ) {
		wp_send_json_error( new WP_Error( '403', esc_html__( 'You are not licensed.', 'google-analytics-for-wordpress' ) ) );
	}
	$args = array(
		'plugin_name' => 'MonsterInsights Pro',
		'plugin_slug' => 'pro',
		'plugin_path' => plugin_basename( __FILE__ ),
		'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'pro',
		'remote_url'  => 'https://monsterinsights.com/',
		'version'     => MonsterInsights()->version,
		'key'         => $license,
	);

	$updater = new MonsterInsights_Updater( $args );
	$addons  = $updater->update_plugins_filter( $updater );
	if ( empty( $addons->update->package ) ) {
		wp_send_json_error();
	}

	$installer->install( $addons->update->package ); // phpcs:ignore
	// Flush the cache and return the newly installed plugin basename.
	wp_cache_flush();
	if ( $installer->plugin_info() ) {
		$plugin_basename = $installer->plugin_info();

		// Deactivate the lite version first.
		deactivate_plugins( plugin_basename( MONSTERINSIGHTS_PLUGIN_FILE ) );

		// Activate the plugin silently.
		$activated = activate_plugin( $plugin_basename, '', false, true );
		if ( ! is_wp_error( $activated ) ) {
			wp_send_json_success( esc_html__( 'Plugin installed & activated.', 'google-analytics-for-wordpress' ) );
		} else {
			// Reactivate the lite plugin if pro activation failed.
			activate_plugin( plugin_basename( MONSTERINSIGHTS_PLUGIN_FILE ), '', false, true );
			wp_send_json_error( esc_html__( 'Pro version installed but needs to be activated from the Plugins page inside your WordPress admin.', 'google-analytics-for-wordpress' ) );
		}
	}
	wp_send_json_error( $error );
}

add_action( 'wp_ajax_nopriv_monsterinsights_run_one_click_upgrade', 'monsterinsights_run_one_click_upgrade' );
