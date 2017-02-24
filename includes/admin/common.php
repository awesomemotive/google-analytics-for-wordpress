<?php
/**
 * Common admin class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Common
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loads styles for all MonsterInsights-based Administration Screens.
 *
 * @since 6.0.0
 * @access public
 *
 * @return null Return early if not on the proper screen.
 */
function monsterinsights_admin_styles() {

	// Get current screen.
	$screen = get_current_screen();
	
	// Bail if we're not on a MonsterInsights screen.
	if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
		return;
	}

	 // Maps
	wp_register_style( MONSTERINSIGHTS_PLUGIN_SLUG . '-jvectormap-style', plugins_url( 'assets/css/jvectormap/jquery-jvectormap-2.0.3.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
	wp_enqueue_style( MONSTERINSIGHTS_PLUGIN_SLUG . '-jvectormap-style' );

	// FontAwesome (used for message boxes)
	wp_register_style( MONSTERINSIGHTS_PLUGIN_SLUG . '-font-awesome', plugins_url( 'assets/css/font-awesome/font-awesome.min.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
	wp_enqueue_style( MONSTERINSIGHTS_PLUGIN_SLUG . '-font-awesome' );

	 // Select2
	wp_register_style( MONSTERINSIGHTS_PLUGIN_SLUG . '-select2-style', plugins_url( 'assets/css/select2/select2.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
	wp_enqueue_style( MONSTERINSIGHTS_PLUGIN_SLUG . '-select2-style' );

	// Tooltips
	wp_enqueue_script( 'jquery-ui-tooltip' );

	// Load necessary admin styles.
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	if ( ! file_exists( MONSTERINSIGHTS_PLUGIN_DIR . 'assets/css/admin.min.css' ) ) {
		$suffix = '';
	}
	wp_register_style( MONSTERINSIGHTS_PLUGIN_SLUG . '-admin-style', plugins_url( 'assets/css/admin' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
	wp_enqueue_style( MONSTERINSIGHTS_PLUGIN_SLUG . '-admin-style' );

	// See if there's an admin.css file for this plugin version
	if ( file_exists( MONSTERINSIGHTS_PLUGIN_DIR . 'pro/assets/css/admin.css' ) ) {
		wp_register_style( MONSTERINSIGHTS_PLUGIN_SLUG . '-pro-admin-style', plugins_url( 'pro/assets/css/admin.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
		wp_enqueue_style( MONSTERINSIGHTS_PLUGIN_SLUG . '-pro-admin-style' );
	} else if ( file_exists( MONSTERINSIGHTS_PLUGIN_DIR . 'lite/assets/css/admin.css' ) ) {
		wp_register_style( MONSTERINSIGHTS_PLUGIN_SLUG . '-only-admin-style', plugins_url( 'lite/assets/css/admin.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
		wp_enqueue_style( MONSTERINSIGHTS_PLUGIN_SLUG . '-only-admin-style' );
	}
}
add_action( 'admin_enqueue_scripts', 'monsterinsights_admin_styles' );

/**
 * Loads scripts for all MonsterInsights-based Administration Screens.
 *
 * @since 6.0.0
 * @access public
 *
 * @return null Return early if not on the proper screen.
 */
function monsterinsights_admin_scripts() {

	// Get current screen.
	$screen = get_current_screen();
	
	// Bail if we're not on a MonsterInsights screen.
	if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
		return;
	}
	
	// Get the base class object.
	$base = MonsterInsights();

	// Load necessary admin scripts
		// List.js
			wp_register_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-list-script', plugins_url( 'assets/js/list/list.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version(), true );
			wp_enqueue_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-list-script' );
			
		// Charts.js
			wp_register_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-chartjs-script', plugins_url( 'assets/js/chartjs/Chart.bundle.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version(), true );
			wp_enqueue_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-chartjs-script' );

		// Maps
			wp_register_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-jvectormap-script', plugins_url( 'assets/js/jvectormap/jquery-jvectormap-2.0.3.min.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version(), true );
			wp_enqueue_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-jvectormap-script' );
			wp_register_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-jvectormap-world-script', plugins_url( 'assets/js/jvectormap/jquery-jvectormap-world-mill.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery', MONSTERINSIGHTS_PLUGIN_SLUG . '-jvectormap-script' ), monsterinsights_get_asset_version(), true );
			wp_enqueue_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-jvectormap-world-script' );

		// Select2
			wp_register_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-select2-script', plugins_url( 'assets/js/select2/select2.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version() );
			wp_enqueue_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-select2-script' );

		// Our Admin JS
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			if ( ! file_exists( MONSTERINSIGHTS_PLUGIN_DIR . 'assets/js/admin.min.js' ) ) {
				$suffix = '';
			}
			wp_register_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-admin-script', plugins_url( 'assets/js/admin' . $suffix . '.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version() );
			wp_enqueue_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-admin-script' );
			wp_localize_script(
				MONSTERINSIGHTS_PLUGIN_SLUG . '-admin-script',
				'monsterinsights_admin',
				array(
					'ajax'                  => admin_url( 'admin-ajax.php' ),
					'dismiss_notice_nonce'  => wp_create_nonce( 'monsterinsights-dismiss-notice' ),
					'loadingtext'               => esc_html__( 'Loading...', 'google-analytics-for-wordpress' ),
					'settings_changed_confirm'  => esc_html__( 'Warning: You have unsaved setting changes. If you leave the settings page without saving your changes will be lost. Did you still want to leave the page?', 'google-analytics-for-wordpress' ),
					'activate_nonce'   => wp_create_nonce( 'monsterinsights-activate' ),
					'active'           => esc_html__( 'Status: Active', 'google-analytics-for-wordpress' ),
					'activate'         => esc_html__( 'Activate', 'google-analytics-for-wordpress' ),
					'networkactive'    => esc_html__( 'Status: Network Activated', 'google-analytics-for-wordpress' ),
					'networkactivate'  => esc_html__( 'Network activate', 'google-analytics-for-wordpress' ),
					'get_addons_nonce' => wp_create_nonce( 'monsterinsights-get-addons' ),
					'activating'       => esc_html__( 'Activating...', 'google-analytics-for-wordpress' ),
					'deactivate'       => esc_html__( 'Deactivate', 'google-analytics-for-wordpress' ),
					'networkdeactivate'=> esc_html__( 'Network deactivate', 'google-analytics-for-wordpress' ),
					'deactivate_nonce' => wp_create_nonce( 'monsterinsights-deactivate' ),
					'deactivating'     => esc_html__( 'Deactivating...', 'google-analytics-for-wordpress' ),
					'inactive'         => esc_html__( 'Status: Inactive', 'google-analytics-for-wordpress' ),
					'networkinactive'  => esc_html__( 'Status: Network inactive', 'google-analytics-for-wordpress' ),
					'install'          => esc_html__( 'Install', 'google-analytics-for-wordpress' ),
					'install_nonce'    => wp_create_nonce( 'monsterinsights-install' ),
					'installing'       => esc_html__( 'Installing...', 'google-analytics-for-wordpress' ),
					'proceed'          => esc_html__( 'Proceed', 'google-analytics-for-wordpress' ),
					'isnetwork'        => is_network_admin()
				)
			);

	// See if there's an admin.js file for this plugin version
		if ( file_exists( MONSTERINSIGHTS_PLUGIN_DIR . 'pro/assets/js/admin.js' ) ) {
			wp_register_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-pro-admin-script', plugins_url( 'pro/assets/js/admin.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version() );
			wp_enqueue_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-pro-admin-script' );
			wp_localize_script(
				MONSTERINSIGHTS_PLUGIN_SLUG . '-pro-admin-script',
				'monsterinsights_admin',
				array(
					'ajax'                  => admin_url( 'admin-ajax.php' )
				)
			);
		} else if ( file_exists( MONSTERINSIGHTS_PLUGIN_DIR . 'lite/assets/js/admin.js' ) ) {
			wp_register_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-only-admin-script', plugins_url( 'only/assets/js/admin.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version() );
			wp_enqueue_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-only-admin-script' );
			wp_localize_script(
				MONSTERINSIGHTS_PLUGIN_SLUG . '-only-admin-script',
				'monsterinsights_admin',
				array(
					'ajax'                  => admin_url( 'admin-ajax.php' )
				)
			);
		} 
}
add_action( 'admin_enqueue_scripts', 'monsterinsights_admin_scripts' );

/**
 * Remove Assets that conflict with ours from our screens.
 *
 * @since 6.0.4
 * @access public
 *
 * @return null Return early if not on the proper screen.
 */
function monsterinsights_remove_conflicting_asset_files() {

	// Get current screen.
	$screen = get_current_screen();
	
	// Bail if we're not on a MonsterInsights screen.
	if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
		return;
	}
	
	$styles = array(
		'kt_admin_css', // Pinnacle theme
		'select2-css', // Schema theme
		'tweetshare_style', // TweetShare - Click To Tweet
		'tweetshare_custom_style', // TweetShare - Click To Tweet
		'tweeetshare_font_script', // TweetShare - Click To Tweet
		'tweeetshare_jquery_script',  // TweetShare - Click To Tweet
		'tweeetshare_jqueryui_script', // TweetShare - Click To Tweet
		'tweeetshare_custom_script', // TweetShare - Click To Tweet
		'tweeetshare_custome_style', // TweetShare - Click To Tweet
		'tweeetshare_notice_style', // TweetShare - Click To Tweet
		'tweeetshare_theme_style', // TweetShare - Click To Tweet
		'tweeetshare_tweet_box_style', // TweetShare - Click To Tweet
	);
	
	$scripts = array(
		'kad_admin_js', // Pinnacle theme
	);

	foreach ( $styles as $style ) {
		wp_dequeue_style( $style ); // Remove CSS file from MI screen
	}

	foreach ( $scripts as $script ) {
		wp_dequeue_script( $script ); // Remove JS file from MI screen
	}
}
add_action( 'admin_enqueue_scripts', 'monsterinsights_remove_conflicting_asset_files', 9999 );

/**
 * Remove non-MI notices from MI page.
 *
 * @since 6.0.0
 * @access public
 *
 * @return null Return early if not on the proper screen.
 */
function hide_non_monsterinsights_warnings () {
	// Bail if we're not on a MonsterInsights screen.
	if ( empty( $_REQUEST['page'] ) || strpos( $_REQUEST['page'], 'monsterinsights' ) === false ) {
		return;
	}

	global $wp_filter;
	if ( !empty( $wp_filter['user_admin_notices']->callbacks ) && is_array( $wp_filter['user_admin_notices']->callbacks ) ) {
		foreach( $wp_filter['user_admin_notices']->callbacks as $priority => $hooks ) {
			foreach ( $hooks as $name => $arr ) {
				if ( !empty( $name ) && strpos( $name, 'monsterinsights' ) === false ) {
					unset( $wp_filter['user_admin_notices']->callbacks[$priority][$name] );
				}
			}
		}
	}

	if ( !empty( $wp_filter['admin_notices']->callbacks ) && is_array( $wp_filter['admin_notices']->callbacks ) ) {
		foreach( $wp_filter['admin_notices']->callbacks as $priority => $hooks ) {
			foreach ( $hooks as $name => $arr ) {
				if ( !empty( $name ) && strpos( $name, 'monsterinsights' ) === false ) {
					unset( $wp_filter['admin_notices']->callbacks[$priority][$name] );
				}
			}
		}
	}

	if ( !empty( $wp_filter['all_admin_notices']->callbacks ) && is_array( $wp_filter['all_admin_notices']->callbacks ) ) {
		foreach( $wp_filter['all_admin_notices']->callbacks as $priority => $hooks ) {
			foreach ( $hooks as $name => $arr ) {
				if ( !empty( $name ) && strpos( $name, 'monsterinsights' ) === false ) {
					unset( $wp_filter['all_admin_notices']->callbacks[$priority][$name] );
				}
			}
		}
	}
}
add_action('admin_print_scripts', 'hide_non_monsterinsights_warnings');  

/**
 * Called whenever an upgrade button / link is displayed in Lite, this function will
 * check if there's a shareasale ID specified.
 *
 * There are three ways to specify an ID, ordered by highest to lowest priority
 * - add_filter( 'monsterinsights_shareasale_id', function() { return 1234; } );
 * - define( 'MONSTERINSIGHTS_SHAREASALE_ID', 1234 );
 * - get_option( 'monsterinsights_shareasale_id' ); (with the option being in the wp_options table)
 *
 * If an ID is present, returns the ShareASale link with the affiliate ID, and tells
 * ShareASale to then redirect to monsterinsights.com/lite
 *
 * If no ID is present, just returns the monsterinsights.com/lite URL with UTM tracking.
 *
 * @since 6.0.0
 * @access public
 *
 * @return string Upgrade link.
 */
function monsterinsights_get_upgrade_link() {

	if ( class_exists( 'MonsterInsights' ) ) {
		// User is using MonsterInsights, so just take them to the Pricing page.
		// Note: On the Addons screen, if the user has a license, we won't hit this function,
		// as the API will tell us the direct URL to send the user to based on their license key,
		// so they see pro-rata pricing.
		return 'https://www.monsterinsights.com/pricing/?utm_source=proplugin&utm_medium=link&utm_campaign=WordPress';
	}

	$shareasale_id = monsterinsights_get_shareasale_id();
	
	// If at this point we still don't have an ID, we really don't have one!
	// Just return the standard upgrade URL.
	if ( empty( $shareasale_id ) ) {
		return 'https://www.monsterinsights.com/pricing/?utm_source=liteplugin&utm_medium=link&utm_campaign=WordPress';
	}

	// If here, we have a ShareASale ID
	// Return ShareASale URL with redirect.
	return 'https://www.shareasale.com/r.cfm?u=' . $shareasale_id . '&b=971799&m=69975&afftrack=&urllink=monsterinsights%2Ecom%2Flite%2F';
}

function monsterinsights_get_shareasale_id() {
   // Check if there's a constant.
	$shareasale_id = '';
	if ( defined( 'MONSTERINSIGHTS_SHAREASALE_ID' ) ) {
		$shareasale_id = MONSTERINSIGHTS_SHAREASALE_ID;
	}

	// If there's no constant, check if there's an option.
	if ( empty( $shareasale_id ) ) {
		$shareasale_id = get_option( 'monsterinsights_shareasale_id', '' );
	}

	// Whether we have an ID or not, filter the ID.
	$shareasale_id = apply_filters( 'monsterinsights_shareasale_id', $shareasale_id );
	return $shareasale_id;
}