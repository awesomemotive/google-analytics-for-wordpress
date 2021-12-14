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
 * @return array Array of the options to use.
 * @since 7.0.0
 * @access public
 *
 */
function monsterinsights_tracking_script() {
	require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/class-tracking-abstract.php';

	$mode = is_preview() ? 'preview' : MonsterInsights()->get_tracking_mode();

	do_action( 'monsterinsights_tracking_before_' . $mode );
	do_action( 'monsterinsights_tracking_before', $mode );
	if ( 'preview' === $mode ) {
		require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/tracking/class-tracking-preview.php';
		$tracking = new MonsterInsights_Tracking_Preview();
		echo $tracking->frontend_output();
	} else {
		require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/tracking/class-tracking-gtag.php';
		$tracking = new MonsterInsights_Tracking_Gtag();
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
 * @return array Array of the options to use.
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_events_tracking() {
	$track_user = monsterinsights_track_user();

	if ( $track_user ) {
		require_once plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE ) . 'includes/frontend/events/class-gtag-events.php';
		new MonsterInsights_Gtag_Events();
	} else {
		// User is in the disabled group or events mode is off
	}
}

add_action( 'template_redirect', 'monsterinsights_events_tracking', 9 );

/**
 * Add the UTM source parameters in the RSS feeds to track traffic.
 *
 * @param string $guid The link for the RSS feed.
 *
 * @return string The new link for the RSS feed.
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_rss_link_tagger( $guid ) {
	global $post;

	if ( monsterinsights_get_option( 'tag_links_in_rss', false ) ) {
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
 * Checks used for loading the frontend scripts/admin bar button.
 */
function monsterinsights_prevent_loading_frontend_reports() {
	return ! current_user_can( 'monsterinsights_view_dashboard' ) || monsterinsights_get_option( 'hide_admin_bar_reports' ) || function_exists( 'monsterinsights_is_reports_page' ) && monsterinsights_is_reports_page() || function_exists( 'monsterinsights_is_settings_page' ) && monsterinsights_is_settings_page();
}

/**
 * Add an admin bar menu item on the frontend.
 *
 * @return void
 * @since 7.5.0
 *
 */
function monsterinsights_add_admin_bar_menu() {
	if ( monsterinsights_prevent_loading_frontend_reports() ) {
		return;
	}

	global $wp_admin_bar;

	$args = array(
		'id'    => 'monsterinsights_frontend_button',
		'title' => '<span class="ab-icon dashicons-before dashicons-chart-bar"></span> Insights',
		// Maybe allow translation?
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
 * @return void
 * @since 7.5.0
 *
 */
function monsterinsights_frontend_admin_bar_scripts() {
	if ( monsterinsights_prevent_loading_frontend_reports() ) {
		return;
	}

	$version_path    = monsterinsights_is_pro_version() ? 'pro' : 'lite';
	$rtl             = is_rtl() ? '.rtl' : '';
	$frontend_js_url = defined( 'MONSTERINSIGHTS_LOCAL_FRONTEND_JS_URL' ) && MONSTERINSIGHTS_LOCAL_FRONTEND_JS_URL ? MONSTERINSIGHTS_LOCAL_FRONTEND_JS_URL : plugins_url( $version_path . '/assets/vue/js/frontend.js', MONSTERINSIGHTS_PLUGIN_FILE );

	if ( ! defined( 'MONSTERINSIGHTS_LOCAL_FRONTEND_JS_URL' ) ) {
		wp_enqueue_style( 'monsterinsights-vue-frontend-style', plugins_url( $version_path . '/assets/vue/css/frontend' . $rtl . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
		wp_enqueue_script( 'monsterinsights-vue-vendors', plugins_url( $version_path . '/assets/vue/js/chunk-frontend-vendors.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );
		wp_enqueue_script( 'monsterinsights-vue-common', plugins_url( $version_path . '/assets/vue/js/chunk-common.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );
	} else {
		wp_enqueue_script( 'monsterinsights-vue-vendors', MONSTERINSIGHTS_LOCAL_VENDORS_JS_URL, array(), monsterinsights_get_asset_version(), true );
		wp_enqueue_script( 'monsterinsights-vue-common', MONSTERINSIGHTS_LOCAL_COMMON_JS_URL, array(), monsterinsights_get_asset_version(), true );
	}

	wp_register_script( 'monsterinsights-vue-frontend', $frontend_js_url, array(), monsterinsights_get_asset_version(), true );
	wp_enqueue_script( 'monsterinsights-vue-frontend' );

	$page_title = is_singular() ? get_the_title() : monsterinsights_get_page_title();
	// We do not have a current auth.
	$site_auth = MonsterInsights()->auth->get_viewname();
	$ms_auth   = is_multisite() && MonsterInsights()->auth->get_network_viewname();

	// Check if any of the other admin scripts are enqueued, if so, use their object.
	if ( ! wp_script_is( 'monsterinsights-vue-script' ) && ! wp_script_is( 'monsterinsights-vue-reports' ) && ! wp_script_is( 'monsterinsights-vue-widget' ) ) {
		$reports_url = is_network_admin() ? add_query_arg( 'page', 'monsterinsights_reports', network_admin_url( 'admin.php' ) ) : add_query_arg( 'page', 'monsterinsights_reports', admin_url( 'admin.php' ) );
		wp_localize_script(
			'monsterinsights-vue-frontend',
			'monsterinsights',
			array(
				'ajax'                => admin_url( 'admin-ajax.php' ),
				'nonce'               => wp_create_nonce( 'mi-admin-nonce' ),
				'network'             => is_network_admin(),
				'translations'        => wp_get_jed_locale_data( monsterinsights_is_pro_version() ? 'ga-premium' : 'google-analytics-for-wordpress' ),
				'assets'              => plugins_url( $version_path . '/assets/vue', MONSTERINSIGHTS_PLUGIN_FILE ),
				'addons_url'          => is_multisite() ? network_admin_url( 'admin.php?page=monsterinsights_network#/addons' ) : admin_url( 'admin.php?page=monsterinsights_settings#/addons' ),
				'page_id'             => is_singular() ? get_the_ID() : false,
				'page_title'          => $page_title,
				'plugin_version'      => MONSTERINSIGHTS_VERSION,
				'shareasale_id'       => monsterinsights_get_shareasale_id(),
				'shareasale_url'      => monsterinsights_get_shareasale_url( monsterinsights_get_shareasale_id(), '' ),
				'is_admin'            => is_admin(),
				'reports_url'         => $reports_url,
				'authed'              => $site_auth || $ms_auth,
				'getting_started_url' => is_multisite() ? network_admin_url( 'admin.php?page=monsterinsights_network#/about/getting-started' ) : admin_url( 'admin.php?page=monsterinsights_settings#/about/getting-started' ),
				'wizard_url'          => is_network_admin() ? network_admin_url( 'index.php?page=monsterinsights-onboarding' ) : admin_url( 'index.php?page=monsterinsights-onboarding' ),
			)
		);
	}
}

add_action( 'wp_enqueue_scripts', 'monsterinsights_frontend_admin_bar_scripts' );
add_action( 'admin_enqueue_scripts', 'monsterinsights_frontend_admin_bar_scripts', 1005 );


/**
 * Load the tracking notice for logged in users.
 */
function monsterinsights_administrator_tracking_notice() {
	// Don't do anything for guests.
	if ( ! is_user_logged_in() ) {
		return;
	}

	// Only show this to users who are not tracked.
	if ( monsterinsights_track_user() ) {
		return;
	}

	// Only show when tracking.
	$ua = monsterinsights_get_ua();
	if ( empty( $ua ) ) {
		return;
	}

	// Don't show if already dismissed.
	if ( get_option( 'monsterinsights_frontend_tracking_notice_viewed', false ) ) {
		return;
	}

	// Automatically dismiss when loaded.
	update_option( 'monsterinsights_frontend_tracking_notice_viewed', 1 );

	?>
	<div class="monsterinsights-tracking-notice monsterinsights-tracking-notice-hide">
		<div class="monsterinsights-tracking-notice-icon">
			<img src="<?php echo esc_url( plugins_url( 'assets/images/mascot.png', MONSTERINSIGHTS_PLUGIN_FILE ) ); ?>"
				 width="40" alt="MonsterInsights Mascot"/>
		</div>
		<div class="monsterinsights-tracking-notice-text">
			<h3><?php esc_html_e( 'Tracking is Disabled for Administrators', 'google-analytics-for-wordpress' ); ?></h3>
			<p>
				<?php
				$doc_url = 'https://monsterinsights.com/docs/tracking-disabled-administrators-editors';
				$doc_url = add_query_arg( array(
					'utm_source'   => monsterinsights_is_pro_version() ? 'proplugin' : 'liteplugin',
					'utm_medium'   => 'frontend-notice',
					'utm_campaign' => 'admin-tracking-doc',
				), $doc_url );
				// Translators: %s is the link to the article where more details about tracking are listed.
				printf( esc_html__( 'To keep stats accurate, we do not load Google Analytics scripts for admin users. %1$sLearn More &raquo;%2$s', 'google-analytics-for-wordpress' ), '<a href="' . esc_url( $doc_url ) . '" target="_blank">', '</a>' );
				?>
			</p>
		</div>
		<div class="monsterinsights-tracking-notice-close">&times;</div>
	</div>
	<style type="text/css">
        .monsterinsights-tracking-notice {
            position: fixed;
            bottom: 20px;
            right: 15px;
            font-family: Arial, Helvetica, "Trebuchet MS", sans-serif;
            background: #fff;
            box-shadow: 0 0 10px 0 #dedede;
            padding: 6px 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 380px;
            max-width: calc(100% - 30px);
            border-radius: 6px;
            transition: bottom 700ms ease;
            z-index: 10000;
        }

        .monsterinsights-tracking-notice h3 {
            font-size: 13px;
            color: #222;
            font-weight: 700;
            margin: 0 0 8px;
            padding: 0;
            line-height: 1;
            border: none;
        }

        .monsterinsights-tracking-notice p {
            font-size: 13px;
            color: #7f7f7f;
            font-weight: 400;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            border: none;
        }

        .monsterinsights-tracking-notice p a {
            color: #7f7f7f;
            font-size: 13px;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            text-decoration: underline;
            font-weight: 400;
        }

        .monsterinsights-tracking-notice p a:hover {
            color: #7f7f7f;
            text-decoration: none;
        }

        .monsterinsights-tracking-notice-icon img {
            height: auto;
            display: block;
            margin: 0;
        }

        .monsterinsights-tracking-notice-icon {
            padding: 14px;
            background-color: #f2f6ff;
            border-radius: 6px;
            flex-grow: 0;
            flex-shrink: 0;
            margin-right: 12px;
        }

        .monsterinsights-tracking-notice-close {
            padding: 0;
            margin: 0 3px 0 0;
            border: none;
            box-shadow: none;
            border-radius: 0;
            color: #7f7f7f;
            background: transparent;
            line-height: 1;
            align-self: flex-start;
            cursor: pointer;
            font-weight: 400;
        }

        .monsterinsights-tracking-notice.monsterinsights-tracking-notice-hide {
            bottom: -200px;
        }
	</style>
	<?php

	if ( ! wp_script_is( 'jquery', 'queue' ) ) {
		wp_enqueue_script( 'jquery' );
	}
	?>
	<script>
		if ( 'undefined' !== typeof jQuery ) {
			jQuery( document ).ready( function ( $ ) {
				/* Don't show the notice if we don't have a way to hide it (no js, no jQuery). */
				$( document.querySelector( '.monsterinsights-tracking-notice' ) ).removeClass( 'monsterinsights-tracking-notice-hide' );
				$( document.querySelector( '.monsterinsights-tracking-notice-close' ) ).on( 'click', function ( e ) {
					e.preventDefault();
					$( this ).closest( '.monsterinsights-tracking-notice' ).addClass( 'monsterinsights-tracking-notice-hide' );
					$.ajax( {
						url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
						method: 'POST',
						data: {
							action: 'monsterinsights_dismiss_tracking_notice',
							nonce: '<?php echo esc_js( wp_create_nonce( 'monsterinsights-tracking-notice' ) ); ?>',
						}
					} );
				} );
			} );
		}
	</script>
	<?php
}

add_action( 'wp_footer', 'monsterinsights_administrator_tracking_notice', 300 );

/**
 * Ajax handler to hide the tracking notice.
 */
function monsterinsights_dismiss_tracking_notice() {

	check_ajax_referer( 'monsterinsights-tracking-notice', 'nonce' );

	update_option( 'monsterinsights_frontend_tracking_notice_viewed', 1 );

	wp_die();

}

add_action( 'wp_ajax_monsterinsights_dismiss_tracking_notice', 'monsterinsights_dismiss_tracking_notice' );

/**
 * If the legacy shortcodes are not registered, make sure they don't output.
 */
function monsterinsights_maybe_handle_legacy_shortcodes() {

	if ( ! shortcode_exists( 'gadwp_useroptout' ) ) {
		add_shortcode( 'gadwp_useroptout', '__return_empty_string' );
	}

}

add_action( 'init', 'monsterinsights_maybe_handle_legacy_shortcodes', 1000 );
