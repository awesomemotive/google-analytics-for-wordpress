<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * MonsterInsights settings export.
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_process_export_settings() {
	if ( ! isset( $_POST['monsterinsights_action'] ) || empty( $_POST['monsterinsights_action'] ) ) {
		return;
	}

	if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
		return;
	}

	if ( 'monsterinsights_export_settings' !== $_POST['monsterinsights_action'] ) {
		return;
	}

	if ( empty( $_POST['monsterinsights_export_settings'] ) || ! wp_verify_nonce( $_POST['monsterinsights_export_settings'], 'mi-admin-nonce' ) ) {
		return;
	}

	$settings = monsterinsights_export_settings();
	ignore_user_abort( true );

	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=monsterinsights-settings-export-' . date( 'm-d-Y' ) . '.json' );
	header( "Expires: 0" );

	echo $settings;
	exit;
}

add_action( 'admin_init', 'monsterinsights_process_export_settings' );
