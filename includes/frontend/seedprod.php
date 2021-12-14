<?php
/**
 * SeedProd Tracking for 404 and Coming Soon.
 *
 * @since 7.3.0
 *
 * @package MonsterInsights
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Disable SeedProd settings (done in seedprod)
// 2. Output tracking code, if settings is not set to use wp_head() (done in seedprod and below)
// 3. Disable ga_tracking in their setting (done in seedprod)
function monsterinsights_seedprod_tracking( $settings ) {
    require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/class-tracking-abstract.php';


    do_action( 'monsterinsights_tracking_before_analytics' );
    do_action( 'monsterinsights_tracking_before', 'analytics' );

	require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/tracking/class-tracking-gtag.php';
	$tracking = new MonsterInsights_Tracking_Gtag();
	echo $tracking->frontend_output();

	do_action( 'monsterinsights_tracking_after_gtag' );
    do_action( 'monsterinsights_tracking_after', 'gtag' );

    $track_user    = monsterinsights_track_user();

    if ( $track_user ) {
	    require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/events/class-gtag-events.php';
	    new MonsterInsights_Gtag_Events();

        // Let's run form tracking if we find it
        if ( function_exists( 'monsterinsights_forms_output_after_script' ) ) {
            monsterinsights_forms_output_after_script( array() );
        }
    }
}
add_action( 'seedprod_monsterinsights_output_tracking', 'monsterinsights_seedprod_tracking', 6, 1 );
