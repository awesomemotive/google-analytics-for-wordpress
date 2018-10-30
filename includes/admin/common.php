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

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// Load Common admin styles.
	wp_register_style( 'monsterinsights-admin-common-style', plugins_url( 'assets/css/admin-common' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
	wp_enqueue_style( 'monsterinsights-admin-common-style' );

	// Get current screen.
	$screen = get_current_screen();
	
	// Bail if we're not on a MonsterInsights screen.
	if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
		return;
	}

	// Bootstrap
		// Primary
			wp_register_style( 'monsterinsights-bootstrap', plugins_url( 'assets/css/bootstrap-prefixed' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
			wp_enqueue_style( 'monsterinsights-bootstrap' );

		// Secondary
			//wp_register_style( 'monsterinsights-bootstrap-base', plugins_url( 'assets/css/bootstrap' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
			//wp_enqueue_style( 'monsterinsights-bootstrap-base' );
			//wp_register_style( 'monsterinsights-bootstrap-theme', plugins_url( 'assets/css/bootstrap-theme' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'monsterinsights-bootstrap-base' ), monsterinsights_get_asset_version() );
			//wp_enqueue_style( 'monsterinsights-bootstrap-theme' );

	// Select300
	wp_register_style( 'monsterinsights-select300-style', plugins_url( 'assets/css/select300/select300.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
	wp_enqueue_style( 'monsterinsights-select300-style' );

	// Vendors
	wp_register_style( 'monsterinsights-vendors-style', plugins_url( 'assets/css/vendors' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
	wp_enqueue_style( 'monsterinsights-vendors-style' );

	// Tooltips
	wp_enqueue_script( 'jquery-ui-tooltip' );

	// Load necessary admin styles.
	wp_register_style( 'monsterinsights-admin-style', plugins_url( 'assets/css/admin' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
	wp_enqueue_style( 'monsterinsights-admin-style' );

	// Load LTR stylesheet where needed.
	if ( is_rtl() ) {
		wp_enqueue_style( 'monsterinsights-admin-style-rtl', plugins_url( 'assets/css/admin-rtl' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
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

	// Our Common Admin JS
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	
	wp_register_script( 'monsterinsights-admin-common-script', plugins_url( 'assets/js/admin-common' . $suffix . '.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version() );
	wp_enqueue_script( 'monsterinsights-admin-common-script' );
	wp_localize_script(
		'monsterinsights-admin-common-script',
		'monsterinsights_admin_common',
		array(
			'ajax'                  => admin_url( 'admin-ajax.php' ),
			'dismiss_notice_nonce'  => wp_create_nonce( 'monsterinsights-dismiss-notice' ),
		)
	);

	// Get current screen.
	$screen = get_current_screen();
	
	// Bail if we're not on a MonsterInsights screen.
	if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
		return;
	}

	// Tooltips
		wp_enqueue_script( 'jquery-ui-tooltip' );

	// Select300
		wp_register_script( 'monsterinsights-select300-script', plugins_url( 'assets/js/select300/select300.full.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version() );
		wp_enqueue_script( 'monsterinsights-select300-script' );

	// Vendors
		wp_register_script( 'monsterinsights-vendors-script', plugins_url( 'assets/js/vendors.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version() );
		wp_enqueue_script( 'monsterinsights-vendors-script' );


	// Our Admin JS
		$deps = array( 
			'jquery',
			'jquery-ui-tooltip',
			'monsterinsights-select300-script',
			'monsterinsights-vendors-script'
		);

		wp_register_script( 'monsterinsights-admin-script', plugins_url( 'assets/js/admin.js', MONSTERINSIGHTS_PLUGIN_FILE ), $deps, monsterinsights_get_asset_version() );
		wp_enqueue_script( 'monsterinsights-admin-script' );
		wp_localize_script(
			'monsterinsights-admin-script',
			'monsterinsights_admin',
			array(
				'ajax'                 			=> admin_url( 'admin-ajax.php' ),
				'dismiss_notice_nonce' 			=> wp_create_nonce( 'monsterinsights-dismiss-notice' ),
				'loadingtext'               	=> esc_html__( 'Loading...', 'google-analytics-for-wordpress' ),
				'settings_changed_confirm'  	=> esc_html__( 'Warning: You have unsaved setting changes. If you leave the settings page without saving your changes will be lost. Did you still want to leave the page?', 'google-analytics-for-wordpress' ),
				'activate_nonce'   				=> wp_create_nonce( 'monsterinsights-activate' ),
				'active'           				=> esc_html__( 'Status: Active', 'google-analytics-for-wordpress' ),
				'activate'         				=> esc_html__( 'Activate', 'google-analytics-for-wordpress' ),
				'networkactive'    				=> esc_html__( 'Status: Network Activated', 'google-analytics-for-wordpress' ),
				'networkactivate'  				=> esc_html__( 'Network activate', 'google-analytics-for-wordpress' ),
				'get_addons_nonce' 				=> wp_create_nonce( 'monsterinsights-get-addons' ),
				'activating'       				=> esc_html__( 'Activating...', 'google-analytics-for-wordpress' ),
				'deactivate'       				=> esc_html__( 'Deactivate', 'google-analytics-for-wordpress' ),
				'networkdeactivate'				=> esc_html__( 'Network deactivate', 'google-analytics-for-wordpress' ),
				'deactivate_nonce' 				=> wp_create_nonce( 'monsterinsights-deactivate' ),
				'deactivating'     				=> esc_html__( 'Deactivating...', 'google-analytics-for-wordpress' ),
				'inactive'         				=> esc_html__( 'Status: Inactive', 'google-analytics-for-wordpress' ),
				'networkinactive'  				=> esc_html__( 'Status: Network inactive', 'google-analytics-for-wordpress' ),
				'install'          				=> esc_html__( 'Install', 'google-analytics-for-wordpress' ),
				'install_nonce'    				=> wp_create_nonce( 'monsterinsights-install' ),
				'installing'       				=> esc_html__( 'Installing...', 'google-analytics-for-wordpress' ),
				'proceed'          				=> esc_html__( 'Proceed', 'google-analytics-for-wordpress' ),
				'isnetwork'        				=> is_network_admin(),
				'copied'           				=> esc_html__( 'Copied!', 'google-analytics-for-wordpress' ),
				'copytoclip'       				=> esc_html__( 'Copy to Clipboard', 'google-analytics-for-wordpress' ),
				'failed'           				=> esc_html__( 'Failed!', 'google-analytics-for-wordpress' ),
				'admin_nonce'      				=> wp_create_nonce( 'mi-admin-nonce' ),
				'shorten'         				=> esc_html__( 'Shorten URL' ,'google-analytics-for-wordpress'),
				'shortened'        				=> esc_html__( 'Shortened!' ,'google-analytics-for-wordpress'),
				'working'          				=> esc_html__( 'Working...' ,'google-analytics-for-wordpress'),
				'importtext'       				=> esc_html__( 'Import' ,'google-analytics-for-wordpress'),
				'imported'         				=> esc_html__( 'Imported!' ,'google-analytics-for-wordpress'),
				'redirect_loading_title_text'   => esc_html__( 'Preparing to redirect:' ,'google-analytics-for-wordpress'),
				'redirect_loading_text_text'    => esc_html__( "You'll be redirected momentarily to complete authentication. This may take a couple seconds." ,'google-analytics-for-wordpress'),
				'redirect_loading_error_title'  => esc_html__( "Authentication Error:" ,'google-analytics-for-wordpress'),
				'deauth_loading_title_text'  	=> esc_html__( 'Deauthenticating....' ,'google-analytics-for-wordpress'),
				'deauth_loading_text_text'   	=> esc_html__( "We're deactivating your site. This may take a couple seconds." ,'google-analytics-for-wordpress'),
				'deauth_loading_error_title' 	=> esc_html__( "Deactivation Error:" ,'google-analytics-for-wordpress'),
				'deauth_success_title_text'  	=> esc_html__( 'Deactivated Successfully!' ,'google-analytics-for-wordpress'),
				'deauth_success_text_text'   	=> esc_html__( "You've disconnected your site from MonsterInsights. Your site is no longer being tracked by Google Analytics and you won't see reports anymore." ,'google-analytics-for-wordpress'),
				'verify_loading_title_text'  	=> esc_html__( 'Verifying....' ,'google-analytics-for-wordpress'),
				'verify_loading_text_text'   	=> esc_html__( "We're verifying your site. This may take a couple seconds." ,'google-analytics-for-wordpress'),
				'verify_loading_error_title' 	=> esc_html__( "Verification Error:" ,'google-analytics-for-wordpress'),
				'verify_success_title_text' 	=> esc_html__( 'Verified Successfully!' ,'google-analytics-for-wordpress'),
				'verify_success_text_text'  	=> esc_html__( "Your site is connected to MonsterInsights!" ,'google-analytics-for-wordpress'),
				'ok_text' 						=> esc_html__( "OK" ,'google-analytics-for-wordpress'),
				'force_deauth_button_text'  	=> esc_html__( "Force Deauthenticate" ,'google-analytics-for-wordpress'),
				'refresh_report_title'          => esc_html__( 'Refreshing Report', 'google-analytics-for-wordpress' ),
				'refresh_report_text'           => esc_html__( 'Loading new report data...', 'google-analytics-for-wordpress' ),
				'refresh_report_success_text'   => esc_html__( 'Success', 'google-analytics-for-wordpress' ),
				'refresh_report_success_text'   => esc_html__( 'Retrieved the new report data successfully', 'google-analytics-for-wordpress' ),
				'refresh_report_failure_title'  => esc_html__( 'Error', 'google-analytics-for-wordpress' ),
				'timezone'						=> date('e'),
				'resume_report_title'			=> esc_html__( 'Real-Time Report Paused', 'google-analytics-for-wordpress' ),
				'resume_report_text'			=> esc_html__( 'The Real-Time Report automatically paused due to inactivity. Please refresh the page to resume the Real-Time Report.', 'google-analytics-for-wordpress' ),
			)
		);

	// ublock notice
	add_action( 'admin_print_footer_scripts', 'monsterinsights_settings_ublock_error_js', 9999999 );
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
		'tweeetshare_custome_style', // TweetShare - Click To Tweet
		'tweeetshare_notice_style', // TweetShare - Click To Tweet
		'tweeetshare_theme_style', // TweetShare - Click To Tweet
		'tweeetshare_tweet_box_style', // TweetShare - Click To Tweet
		'soultype2-admin', // SoulType Plugin
		'thesis-options-stylesheet', // Thesis Options Stylesheet
		'imagify-sweetalert-core', // Imagify 
		'imagify-sweetalert', // Imagify 
		'smls-backend-style', // Smart Logo Showcase Lite
		'wp-reactjs-starter', // wp-real-media-library
		'control-panel-modal-plugin', // Ken Theme
		'theme-admin-css', // Vitrine Theme
		'qi-framework-styles', //  Artisan Nayma Theme
		'artisan-pages-style', // Artisan Pages Plugin
		'control-panel-modal-plugin', // Ken Theme 
		'sweetalert', //  Church Suite Theme by Webnus
		'woo_stock_alerts_admin_css', // WooCommerce bolder product alerts
		'custom_wp_admin_css', // Fix for Add Social Share
		'fo_css', // Fix for Add Social Share
		'font_css', // Fix for Add Social Share
		'font2_css', // Fix for Add Social Share
		'font3_css', // Fix for Add Social Share
		'hover_css', // Fix for Add Social Share
		'fontend_styling' // Fix for Add Social Share
	);
	
	$scripts = array(
		'kad_admin_js', // Pinnacle theme
		'dt-chart', // DesignThemes core features plugin
		'tweeetshare_font_script', // TweetShare - Click To Tweet
		'tweeetshare_jquery_script',  // TweetShare - Click To Tweet
		'tweeetshare_jqueryui_script', // TweetShare - Click To Tweet
		'tweeetshare_custom_script', // TweetShare - Click To Tweet
		'imagify-promise-polyfill', // Imagify 
		'imagify-sweetalert', // Imagify 
		'imagify-chart', // Imagify
		'chartjs', // Comet Cache Pro
		'wp-reactjs-starter', // wp-real-media-library
		'jquery-tooltipster', // WP Real Media Library
		'jquery-nested-sortable', // WP Real Media Library
		'jquery-aio-tree', // WP Real Media Library
		'wp-media-picker', // WP Real Media Library
		'rml-general', // WP Real Media Library
		'rml-library', // WP Real Media Library
		'rml-grid', // WP Real Media Library
		'rml-list', // WP Real Media Library
		'rml-modal', // WP Real Media Library
		'rml-order', // WP Real Media Library
		'rml-meta', // WP Real Media Library
		'rml-uploader',  // WP Real Media Library
		'rml-options',  // WP Real Media Library
		'rml-usersettings',  // WP Real Media Library
		'rml-main', // WP Real Media Library
		'control-panel-sweet-alert', // Ken Theme
		'sweet-alert-js', // Vitrine Theme
		'theme-admin-script', // Vitrine Theme
		'sweetalert', //  Church Suite Theme by Webnus
		'be_alerts_charts', //  WooCommerce bolder product alerts
 		'magayo-lottery-results',  //  Magayo Lottery Results
		'control-panel-sweet-alert', // Ken Theme
		'cpm_chart', // WP Project Manager
		'adminscripts', //  Artisan Nayma Theme
		'artisan-pages-script', // Artisan Pages Plugin
		'tooltipster', // Grand News Theme
		'fancybox', // Grand News Theme
		'grandnews-admin-cript', // Grand News Theme
		'colorpicker', // Grand News Theme
		'eye', // Grand News Theme
		'icheck', // Grand News Theme
		'learn-press-chart', //  LearnPress
		'theme-script-main', //  My Listing Theme by 27collective
		'selz', //  Selz eCommerce
		'tie-admin-scripts', //   Tie Theme
		'blossomthemes-toolkit', //   BlossomThemes Toolkit
		'illdy-widget-upload-image', //   Illdy Companion By Colorlib
		'moment.js', // WooCommerce Table Rate Shipping
		'default', //   Bridge Theme
		'qode-tax-js', //   Bridge Theme
		'wc_smartship_moment_js', // WooCommerce Posti SmartShip by markup.fi
		'ecwid-admin-js', // Fixes Conflict for Ecwid Shopping Cart
		'td-wp-admin-js', // Newspaper by tagDiv
		'moment', // Screets Live Chat
		'wpmf-base', //  WP Media Folder Fix
		'wpmf-media-filters', //  WP Media Folder Fix
		'wpmf-folder-tree', //  WP Media Folder Fix
		'wpmf-assign-tree', //  WP Media Folder Fix
		'js_files_for_wp_admin', //  TagDiv Composer Fix
		'tdb_js_files_for_wp_admin_last', //  TagDiv Composer Fix
		'tdb_js_files_for_wp_admin', //  TagDiv Composer Fix
		'wd-functions', //  affiliate boxes
		'ellk-aliExpansion', // Ali Dropship Plugin
		'ftmetajs', // Houzez Theme
		'qode_admin_default', //  Fix For Stockholm Theme
		'qodef-tax-js', // Fix for Prowess Theme
		'qodef-user-js', // Fix for Prowess Theme
		'qodef-ui-admin', // Fix for Prowess Theme
		'ssi_script', // Fix for Add Social Share
		'live_templates', // Fix for Add Social Share
		'default', // Fix for Add Social Share
		'handsontable', // Fix WP Tables
		'moment-js', // Magee Shortcodes
		'postbox', // Scripts from wp-admin enqueued everywhere by WP Posts Filter
		'link', // Scripts from wp-admin enqueued everywhere by WP Posts Filter
		'wpvr_scripts', // WP Video Robot
		'wpvr_scripts_loaded', // WP Video Robot
		'wpvr_scripts_assets', // WP Video Robot
		'writee_widget_admin', // Fix for the Writtee theme
		'__ytprefs_admin__', // Fix for YouTube by EmbedPlus plugin
		'momentjs', // Fix for Blog Time plugin
		'c2c_BlogTime', //  Fix for Blog Time plugin
		'material-wp', // Fix for MaterialWP plugin
		'wp-color-picker-alpha', // Fix for MaterialWP plugin
		'swifty-img-widget-admin-script', // Fix for Swifty Image Widget.
	);

	if ( ! empty( $styles ) ) {
		foreach ( $styles as $style ) {
			wp_dequeue_style( $style ); // Remove CSS file from MI screen
			wp_deregister_style( $style );
		}
	}
	if ( ! empty( $scripts ) ) {
		foreach ( $scripts as $script ) {
			wp_dequeue_script( $script ); // Remove JS file from MI screen
			wp_deregister_script( $script );
		}
	}

	$third_party = array(
		'select300',
		'sweetalert',
		'clipboard',
		'matchHeight',
		'inputmask',
		'jquery-confirm',
		'list',
		'toastr',
		'tooltipster',
		'flag-icon',
		'bootstrap',
	);

	global $wp_styles;
	foreach ( $wp_styles->queue as $handle ) {
		if ( strpos( $wp_styles->registered[$handle]->src, 'wp-content') === false ) {
			return;
		}
		
		if ( strpos( $wp_styles->registered[$handle]->handle, 'monsterinsights') !== false ) {
			return;
		}

		foreach( $third_party as $partial ) {
			if ( strpos( $wp_styles->registered[$handle]->handle, $partial ) !== false ) {
				wp_dequeue_style( $handle ); // Remove css file from MI screen
				wp_deregister_style( $handle );
				break;
			} else if ( strpos( $wp_styles->registered[$handle]->src, $partial ) !== false ) {
				wp_dequeue_style( $handle ); // Remove css file from MI screen
				wp_deregister_style( $handle );
				break;
			}
		}
	}

	global $wp_scripts;
	foreach ( $wp_scripts->queue as $handle ) {
		if ( strpos( $wp_scripts->registered[$handle]->src, 'wp-content') === false ) {
			return;
		}
		
		if ( strpos( $wp_scripts->registered[$handle]->handle, 'monsterinsights') !== false ) {
			return;
		}

		foreach( $third_party as $partial ) {
			if ( strpos( $wp_scripts->registered[$handle]->handle, $partial ) !== false ) {
				wp_dequeue_script( $handle ); // Remove JS file from MI screen
				wp_deregister_script( $handle );
				break;
			} else if ( strpos( $wp_scripts->registered[$handle]->src, $partial ) !== false ) {
				wp_dequeue_script( $handle ); // Remove JS file from MI screen
				wp_deregister_script( $handle );
				break;
			}
		}
	}

	// Remove actions from themes that are not following best practices and break the admin doing so
		// Theme: Newspaper by tagDiv
			remove_action('admin_enqueue_scripts', 'load_wp_admin_js');
			remove_action('admin_enqueue_scripts', 'load_wp_admin_css');
			remove_action('admin_print_scripts-widgets.php', 'td_on_admin_print_scripts_farbtastic');
			remove_action('admin_print_styles-widgets.php', 'td_on_admin_print_styles_farbtastic');
			remove_action('admin_print_footer_scripts', 'check_if_media_uploads_is_loaded', 9999);
			remove_action('print_media_templates', 'td_custom_gallery_settings_hook');
			remove_action('print_media_templates', 'td_change_backbone_js_hook');
			remove_action('admin_head', 'tdc_on_admin_head'); //  TagDiv Composer Fix
			remove_action('print_media_templates', 'us_media_templates'); // Impreza Theme Fix
			remove_action('admin_footer', 'gt3pg_add_gallery_template'); // GT3 Photo & Video Gallery By GT3 Themes Plugin Fix
		// Plugin WP Booklist:
			remove_action('admin_footer', 'wpbooklist_jre_dismiss_prem_notice_forever_action_javascript');
			remove_action('admin_footer', 'wpbooklist_dashboard_add_book_action_javascript');
			remove_action('admin_footer', 'wpbooklist_edit_book_show_form_action_javascript');
			remove_action('admin_footer', 'wpbooklist_show_book_in_colorbox_action_javascript');
			remove_action('admin_footer', 'wpbooklist_new_lib_shortcode_action_javascript');
			remove_action('admin_footer', 'wpbooklist_dashboard_save_library_display_options_action_javascript');
			remove_action('admin_footer', 'wpbooklist_dashboard_save_post_display_options_action_javascript');
			remove_action('admin_footer', 'wpbooklist_dashboard_save_page_display_options_action_javascript');
			remove_action('admin_footer', 'wpbooklist_update_display_options_action_javascript');
			remove_action('admin_footer', 'wpbooklist_edit_book_pagination_action_javascript');
			remove_action('admin_footer', 'wpbooklist_edit_book_switch_lib_action_javascript');
			remove_action('admin_footer', 'wpbooklist_edit_book_search_action_javascript');
			remove_action('admin_footer', 'wpbooklist_edit_book_actual_action_javascript');
			remove_action('admin_footer', 'wpbooklist_delete_book_action_javascript');
			remove_action('admin_footer', 'wpbooklist_user_apis_action_javascript');
			remove_action('admin_footer', 'wpbooklist_upload_new_stylepak_action_javascript');
			remove_action('admin_footer', 'wpbooklist_upload_new_post_template_action_javascript');
			remove_action('admin_footer', 'wpbooklist_upload_new_page_template_action_javascript');
			remove_action('admin_footer', 'wpbooklist_create_db_library_backup_action_javascript');
			remove_action('admin_footer', 'wpbooklist_restore_db_library_backup_action_javascript');
			remove_action('admin_footer', 'wpbooklist_create_csv_action_javascript');
			remove_action('admin_footer', 'wpbooklist_amazon_localization_action_javascript');
			remove_action('admin_footer', 'wpbooklist_delete_book_bulk_action_javascript');
			remove_action('admin_footer', 'wpbooklist_reorder_action_javascript');
			remove_action('admin_footer', 'wpbooklist_exit_results_action_javascript');
			remove_action('admin_footer', 'wpbooklist_storytime_select_category_action_javascript');
			remove_action('admin_footer', 'wpbooklist_storytime_get_story_action_javascript');
			remove_action('admin_footer', 'wpbooklist_storytime_expand_browse_action_javascript');
			remove_action('admin_footer', 'wpbooklist_storytime_save_settings_action_javascript');
			remove_action('admin_footer', 'wpbooklist_delete_story_action_javascript');
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
				if ( is_object( $arr['function'] ) && $arr['function'] instanceof Closure ) {
					unset( $wp_filter['user_admin_notices']->callbacks[ $priority ][ $name ] );
					continue;
				}
				if ( ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) && strpos( strtolower( get_class( $arr['function'][0] ) ), 'monsterinsights' ) !== false ) {
					continue;
				}
				if ( !empty( $name ) && strpos( $name, 'monsterinsights' ) === false ) {
					unset( $wp_filter['user_admin_notices']->callbacks[$priority][$name] );
				}
			}
		}
	}

	if ( !empty( $wp_filter['admin_notices']->callbacks ) && is_array( $wp_filter['admin_notices']->callbacks ) ) {
		foreach( $wp_filter['admin_notices']->callbacks as $priority => $hooks ) {
			foreach ( $hooks as $name => $arr ) {
				if ( is_object( $arr['function'] ) && $arr['function'] instanceof Closure ) {
					unset( $wp_filter['admin_notices']->callbacks[ $priority ][ $name ] );
					continue;
				}
				if ( ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) && strpos( strtolower( get_class( $arr['function'][0] ) ), 'monsterinsights' ) !== false ) {
					continue;
				}
				if ( !empty( $name ) && strpos( $name, 'monsterinsights' ) === false ) {
					unset( $wp_filter['admin_notices']->callbacks[$priority][$name] );
				}
			}
		}
	}

	if ( !empty( $wp_filter['all_admin_notices']->callbacks ) && is_array( $wp_filter['all_admin_notices']->callbacks ) ) {
		foreach( $wp_filter['all_admin_notices']->callbacks as $priority => $hooks ) {
			foreach ( $hooks as $name => $arr ) {
				if ( is_object( $arr['function'] ) && $arr['function'] instanceof Closure ) {
					unset( $wp_filter['all_admin_notices']->callbacks[ $priority ][ $name ] );
					continue;
				}
				if ( ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) && strpos( strtolower( get_class( $arr['function'][0] ) ), 'monsterinsights' ) !== false ) {
					continue;
				}
				if ( !empty( $name ) && strpos( $name, 'monsterinsights' ) === false ) {
					unset( $wp_filter['all_admin_notices']->callbacks[$priority][$name] );
				}
			}
		}
	}
}
add_action('admin_print_scripts', 'hide_non_monsterinsights_warnings');  
add_action('admin_head', 'hide_non_monsterinsights_warnings', PHP_INT_MAX  );  

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
function monsterinsights_get_upgrade_link( $medium = '', $campaign = '', $url = '' ) {
	$url = monsterinsights_get_url( $medium, $campaign, $url, false );

	if ( monsterinsights_is_pro_version() ) {
		return esc_url( $url );
	}

	// Get the ShareASale ID
	$shareasale_id   = monsterinsights_get_shareasale_id();

	// If we have a shareasale ID return the shareasale url
	if ( ! empty( $shareasale_id ) ) {
		$shareasale_id  = absint( $shareasale_id );
		return esc_url( monsterinsights_get_shareasale_url( $shareasale_id, $url ) );
	} else {
		return esc_url( $url );
	}
}

function monsterinsights_get_url( $medium = '', $campaign = '', $url = '', $escape = true  ) {
	// Setup Campaign variables
	$source          = monsterinsights_is_pro_version()   ? 'proplugin' : 'liteplugin';
	$medium          = ! empty( $medium )   ? $medium     : 'defaultmedium';
	$campaign        = ! empty( $campaign ) ? $campaign   : 'defaultcampaign';
	$content 		 = MONSTERINSIGHTS_VERSION;
	$default_url     = monsterinsights_is_pro_version()   ? '' : 'lite/';
	$url             = ! empty( $url ) ? $url : 'https://www.monsterinsights.com/' . $default_url;

	// Put together redirect URL
	$url = add_query_arg(
		array(
		    'utm_source'   => $source,   // Pro/Lite Plugin
		    'utm_medium'   => sanitize_key( $medium ),   // Area of MonsterInsights (example Reports)
		    'utm_campaign' => sanitize_key( $campaign ), // Which link (example eCommerce Report)
		    'utm_content'  => $content,  // Version number of MI
		),
		trailingslashit( $url )
	);

	if ( $escape ) {
		return esc_url( $url );
	} else {
		return $url;
	}
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

	// Ensure it's a number
	$shareasale_id = absint( $shareasale_id );
	return $shareasale_id;
}

// Passed in with mandatory default redirect and shareasaleid from monsterinsights_get_upgrade_link
function monsterinsights_get_shareasale_url( $shareasale_id, $shareasale_redirect ) {
   // Check if there's a constant.
	$custom = false;
	if ( defined( 'MONSTERINSIGHTS_SHAREASALE_REDIRECT_URL' ) ) {
		$shareasale_redirect = MONSTERINSIGHTS_SHAREASALE_REDIRECT_URL;
		$custom 			 = true;
	}

	// If there's no constant, check if there's an option.
	if ( empty( $custom ) ) {
		$shareasale_redirect = get_option( 'monsterinsights_shareasale_redirect_url', '' );
		$custom 			 = true;
	}

	// Whether we have an ID or not, filter the ID.
	$shareasale_redirect = apply_filters( 'monsterinsights_shareasale_redirect_url', $shareasale_redirect, $custom );
	$shareasale_url      = sprintf( 'http://www.shareasale.com/r.cfm?B=971799&U=%s&M=69975&urllink=%s', $shareasale_id, $shareasale_redirect );
	return $shareasale_url;
}

function monsterinsights_settings_ublock_error_js(){
	echo "<script type='text/javascript'>\n";
	echo "jQuery( document ).ready( function( $ ) {
			if ( window.uorigindetected == null){
			   $('#monsterinsights-ublock-origin-error').show();
			   $('.monsterinsights-nav-tabs').hide();
			   $('.monsterinsights-nav-container').hide();
			   $('#monsterinsights-addon-heading').hide();
			   $('#monsterinsights-addons').hide();
			   $('#monsterinsights-reports').hide();
			}
		});";
	echo "\n</script>";
}

function monsterinsights_ublock_notice() {
	ob_start();?>
	<div id="monsterinsights-ublock-origin-error" class="error inline" style="display:none;">
		<?php echo sprintf( esc_html__( 'MonsterInsights has detected that it\'s files are being blocked. This is usually caused by a adblock browser plugin (particularly uBlock Origin), or a conflicting WordPress theme or plugin. This issue only affects the admin side of MonsterInsights. To solve this, ensure MonsterInsights is whitelisted for your website URL in any adblock browser plugin you use. For step by step directions on how to do this, %1$sclick here%2$s. If this doesn\'t solve the issue (rare), send us a ticket %3$shere%2$s and we\'ll be happy to help diagnose the issue.', 'google-analytics-for-wordpress'), '<a href="https://monsterinsights.com/docs/monsterinsights-asset-files-blocked/" target="_blank" rel="noopener noreferrer" referrer="no-referrer">', '</a>', '<a href="https://monsterinsights.com/contact/" target="_blank" rel="noopener noreferrer" referrer="no-referrer">');
		?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Some themes/plugins don't add proper checks and load JS code in all admin pages causing conflicts.
 */
function monsterinsights_remove_unnecessary_footer_hooks() {

	$screen = get_current_screen();
	// Bail if we're not on a MonsterInsights screen.
	if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
		return;
	}

	// Remove js code added by Newspaper theme - version 8.8.0.
	remove_action( 'print_media_templates', 'td_custom_gallery_settings_hook' );
	remove_action( 'print_media_templates', 'td_change_backbone_js_hook' );
	// Remove js code added by the Brooklyn theme - version 4.5.3.1.
	remove_action( 'print_media_templates', 'ut_create_gallery_options' );

	// Remove js code added by WordPress Book List Plugin - version 5.8.1.
	remove_action( 'admin_footer', 'wpbooklist_jre_dismiss_prem_notice_forever_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_dashboard_add_book_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_edit_book_show_form_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_show_book_in_colorbox_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_new_lib_shortcode_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_dashboard_save_library_display_options_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_dashboard_save_post_display_options_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_dashboard_save_page_display_options_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_update_display_options_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_edit_book_pagination_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_edit_book_switch_lib_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_edit_book_search_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_edit_book_actual_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_delete_book_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_user_apis_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_upload_new_stylepak_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_upload_new_post_template_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_upload_new_page_template_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_create_db_library_backup_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_restore_db_library_backup_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_create_csv_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_amazon_localization_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_delete_book_bulk_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_reorder_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_exit_results_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_storytime_select_category_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_storytime_get_story_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_storytime_expand_browse_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_storytime_save_settings_action_javascript' );
	remove_action( 'admin_footer', 'wpbooklist_delete_story_action_javascript' );
}

add_action( 'admin_head', 'monsterinsights_remove_unnecessary_footer_hooks', 15 );
