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
    $track_user    = monsterinsights_track_user();

    if ( $track_user ) {
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

/**
 * Add an admin bar menu item on the frontend.
 *
 * @since 7.5.0
 *
 * @return void
 */
function monsterinsights_add_admin_bar_menu() {
	if ( monsterinsights_get_option( 'hide_admin_bar_reports' ) || function_exists( 'monsterinsights_is_reports_page' ) && monsterinsights_is_reports_page() ) {
		return;
	}

	global $wp_admin_bar;

	$args = array(
		'id'    => 'monsterinsights_frontend_button',
		'title' => '<span class="ab-icon dashicons-before dashicons-chart-bar"></span> Insights', // Maybe allow translation?
		'href'  => '#',
	);

	if ( method_exists( $wp_admin_bar, 'add_menu' ) ) {
		$wp_admin_bar->add_menu( $args );
	}
}

add_action( 'admin_bar_menu', 'monsterinsights_add_admin_bar_menu', 999 );

/**
 * Load the scripts needed for the admin bar.
 *
 * @since 7.5.0
 *
 * @return void
 */
function monsterinsights_frontend_admin_bar_scripts() {
	if ( ! is_admin_bar_showing() || monsterinsights_get_option( 'hide_admin_bar_reports' ) || function_exists( 'monsterinsights_is_reports_page' ) && monsterinsights_is_reports_page() ) {
		return;
	}

	$version_path    = monsterinsights_is_pro_version() ? 'pro' : 'lite';
	$rtl             = is_rtl() ? '.rtl' : '';
	$frontend_js_url = defined( 'MONSTERINSIGHTS_LOCAL_FRONTEND_JS_URL' ) && MONSTERINSIGHTS_LOCAL_FRONTEND_JS_URL ? MONSTERINSIGHTS_LOCAL_FRONTEND_JS_URL : plugins_url( $version_path . '/assets/vue/js/frontend.js', MONSTERINSIGHTS_PLUGIN_FILE );

	if ( ! defined( 'MONSTERINSIGHTS_LOCAL_FRONTEND_JS_URL' ) ) {
		wp_enqueue_style( 'monsterinsights-vue-frontend-style', plugins_url( $version_path . '/assets/vue/css/frontend' . $rtl . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
		wp_enqueue_script( 'monsterinsights-vue-vendors', plugins_url( $version_path . '/assets/vue/js/chunk-vendors.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );
		wp_enqueue_script( 'monsterinsights-vue-common', plugins_url( $version_path . '/assets/vue/js/chunk-common.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );
	}

	wp_register_script( 'monsterinsights-vue-frontend', $frontend_js_url, array(), monsterinsights_get_asset_version(), true );
	wp_enqueue_script( 'monsterinsights-vue-frontend' );

	$page_title = is_singular() ? get_the_title() : monsterinsights_get_page_title();

	// Check if any of the other admin scripts are enqueued, if so, use their object.
	if ( ! wp_script_is( 'monsterinsights-vue-script' ) && ! wp_script_is( 'monsterinsights-vue-reports' ) && ! wp_script_is( 'monsterinsights-vue-widget' ) ) {
		wp_localize_script(
			'monsterinsights-vue-frontend',
			'monsterinsights',
			array(
				'ajax'           => admin_url( 'admin-ajax.php' ),
				'nonce'          => wp_create_nonce( 'mi-admin-nonce' ),
				'network'        => is_network_admin(),
				'translations'   => wp_get_jed_locale_data( monsterinsights_is_pro_version() ? 'ga-premium' : 'google-analytics-for-wordpress' ),
				'assets'         => plugins_url( $version_path . '/assets/vue', MONSTERINSIGHTS_PLUGIN_FILE ),
				'addons_url'     => is_multisite() ? network_admin_url( 'admin.php?page=monsterinsights_network#/addons' ) : admin_url( 'admin.php?page=monsterinsights_settings#/addons' ),
				'page_id'        => is_singular() ? get_the_ID() : false,
				'page_title'     => $page_title,
				'plugin_version' => MONSTERINSIGHTS_VERSION,
				'shareasale_id'  => monsterinsights_get_shareasale_id(),
				'shareasale_url' => monsterinsights_get_shareasale_url( monsterinsights_get_shareasale_id(), '' ),
				'is_admin'       => is_admin(),
				'reports_url'    => add_query_arg( 'page', 'monsterinsights_reports', admin_url( 'admin.php' ) ),
			)
		);
	}
}

add_action( 'wp_enqueue_scripts', 'monsterinsights_frontend_admin_bar_scripts' );
add_action( 'admin_enqueue_scripts', 'monsterinsights_frontend_admin_bar_scripts', 1005 );
