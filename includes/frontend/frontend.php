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
 * @since 6.0.0
 * @access public
 *
 * @return array Array of the options to use.
 */
function monsterinsights_tracking_script( ) {

    $tracking_mode = monsterinsights_get_option( 'tracking_mode', 'analytics' );
    require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/class-tracking-abstract.php';
    $mode = '';

    if ( is_preview() ) {
        $mode = 'preview';
    } else if ( ! monsterinsights_track_user() ) {
        $mode = 'disabled';
    } else if ( $tracking_mode === 'analytics' ) {
        $mode = 'analytics';
    } else if ( $tracking_mode === 'ga' ) {
        $mode = 'ga';
    } else {
        //$mode = apply_filters( 'monsterinsights_custom_tracking_name', 'name-of-method' );
    }

    do_action( 'monsterinsights_tracking_before_' . $mode );
    do_action( 'monsterinsights_tracking_before', $mode );

    switch ( $mode ) {
        case 'preview':
            require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/tracking/class-tracking-preview.php';
            $tracking = new MonsterInsights_Tracking_Preview();
            echo $tracking->frontend_output();
            break;

        case 'disabled':
            require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/tracking/class-tracking-disabled.php';
            $tracking = new MonsterInsights_Tracking_Disabled();
            echo $tracking->frontend_output();
            break;

        case 'analytics':
            require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/tracking/class-tracking-analytics.php';
            $tracking = new MonsterInsights_Tracking_Analytics();
            echo $tracking->frontend_output();
            break;

        case 'ga':
            require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/tracking/class-tracking-ga.php';
            $tracking = new MonsterInsights_Tracking_GA();
            echo $tracking->frontend_output();
            break;

        default:
            //do_action( 'monsterinsights_custom_tracking' );
            break;
    }

    do_action( 'monsterinsights_tracking_after_' . $mode );
    do_action( 'monsterinsights_tracking_after', $mode );
}
add_action( 'wp_head', 'monsterinsights_tracking_script', 8 );
//add_action( 'login_head', 'monsterinsights_tracking_script', 8 );

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
    $tracking_mode = monsterinsights_get_option( 'tracking_mode', 'analytics' );
    $track_user    = monsterinsights_track_user();

    if ( $track_user && $events_mode === 'php' && ( $tracking_mode === 'ga' || $tracking_mode === 'analytics' ) ) {
        require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/events/class-events-php.php';
        new MonsterInsights_Events_PHP();
    } else if ( $track_user && $events_mode === 'js' && $tracking_mode === 'analytics' ) {
        require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/events/class-events-js.php';
        new MonsterInsights_Events_JS();
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