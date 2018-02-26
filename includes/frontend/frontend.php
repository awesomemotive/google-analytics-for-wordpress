<?php
/**
 * Frontend events tracking.
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
 * Get frontend tracking options.
 *
 * This function is used to return an array of parameters
 * for the frontend_output() function to output. These are 
 * generally dimensions and turned on GA features.
 *
 * @since 7.0.0
 * @access public
 *
 * @return array Array of the options to use.
 */
function monsterinsights_tracking_script( ) {
    require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/class-tracking-abstract.php';

    $mode = is_preview() ? 'preview' : 'analytics';

    do_action( 'monsterinsights_tracking_before_' . $mode );
    do_action( 'monsterinsights_tracking_before', $mode );
    if ( $mode === 'preview' ) {
        require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/tracking/class-tracking-preview.php';
        $tracking = new MonsterInsights_Tracking_Preview();
        echo $tracking->frontend_output();
    } else {
         require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/tracking/class-tracking-analytics.php';
         $tracking = new MonsterInsights_Tracking_Analytics();
         echo $tracking->frontend_output();
    }

    do_action( 'monsterinsights_tracking_after_' . $mode );
    do_action( 'monsterinsights_tracking_after', $mode );
}
add_action( 'wp_head', 'monsterinsights_tracking_script', 6 );
//add_action( 'login_head', 'monsterinsights_tracking_script', 6 );

/**
 * Get frontend tracking options.
 *
 * This function is used to return an array of parameters
 * for the frontend_output() function to output. These are 
 * generally dimensions and turned on GA features.
 *
 * @since 6.0.0
 * @access public
 *
 * @return array Array of the options to use.
 */
function monsterinsights_events_tracking( ) {
    $events_mode   = monsterinsights_get_option( 'events_mode', false );
    $track_user    = monsterinsights_track_user();

    if ( $track_user && ( $events_mode === 'js' || $events_mode === 'php' ) ) {
        require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/events/class-analytics-events.php';
        new MonsterInsights_Analytics_Events();
    } else {
        // User is in the disabled group or events mode is off
    }
}
add_action( 'template_redirect', 'monsterinsights_events_tracking', 9 );

/**
 * Add the UTM source parameters in the RSS feeds to track traffic.
 *
 * @since 6.0.0
 * @access public
 *
 * @param string $guid The link for the RSS feed.
 *
 * @return string The new link for the RSS feed.
 */
function monsterinsights_rss_link_tagger( $guid ) {
    global $post;

    if ( monsterinsights_get_option( 'tag_links_in_rss', false ) ){
        if ( is_feed() ) {
            if ( monsterinsights_get_option( 'allow_anchor', false ) ) {
                $delimiter = '#';
            } else {
                $delimiter = '?';
                if ( strpos( $guid, $delimiter ) > 0 ) {
                    $delimiter = '&amp;';
                }
            }
            return $guid . $delimiter . 'utm_source=rss&amp;utm_medium=rss&amp;utm_campaign=' . urlencode( $post->post_name );
        }
    }
    return $guid;
}
add_filter( 'the_permalink_rss', 'monsterinsights_rss_link_tagger', 99 );