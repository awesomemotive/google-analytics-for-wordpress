<?php
/**
 * MonsterInsights Lite Installation and Automatic Upgrades.
 *
 * This file handles special Lite install & upgrade routines.
 *
 * @package MonsterInsights
 * @subpackage Install/Upgrade
 * @since 6.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { 
	exit;
}

// @todo: Add defaults for new installs
//do_action( 'monsterinsights_after_new_install_routine', $version );

// do_action( 'monsterinsights_after_existing_upgrade_routine', $version );

// Add default 
//do_action( 'monsterinsights_after_install_routine', $version );

function monsterinsights_lite_upgrade_from_yoast( $key, $network ) {
	if ( $network ) {
		$option                = array();
		$option['key']         = $key;
		$option['type']        = '';
		$option['is_expired']  = false;
		$option['is_disabled'] = false;
		$option['is_invalid']  = false;
		update_site_option( 'monsterinsights_license', $option );
	} else {
		$option                = array();
		$option['key']         = $key;
		$option['type']        = '';
		$option['is_expired']  = false;
		$option['is_disabled'] = false;
		$option['is_invalid']  = false;
		update_option( 'monsterinsights_license', $option );        
	}
}
add_action( 'monsterinsights_upgrade_from_yoast', 'monsterinsights_lite_upgrade_from_yoast', 10, 2 );
