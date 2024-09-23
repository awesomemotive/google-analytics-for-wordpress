<?php
/**
 * Gutenberg-specific scripts.
 */

/**
 * Gutenberg editor assets.
 */
function monsterinsights_gutenberg_editor_assets() {
	global $wp_scripts;

	// stop loading gutenberg related assets/blocks/sidebars if WP version is less than 5.4
	if ( ! monsterinsights_load_gutenberg_app() ) {
		return;
	}

	if ( function_exists( 'get_current_screen' ) ) {
		$current_screen = get_current_screen();

		if ( is_object( $current_screen ) && 'widgets' === $current_screen->id ) {
			return;
		}
	}

	$suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
	wp_enqueue_script( 'lodash', includes_url('js') . '/underscore.min.js' );
	// @TODO Robo minification is breaking the editor. We will use the main version for now.
	$plugins_js_path    = '/assets/gutenberg/js/editor.js';
	$plugins_style_path = '/assets/gutenberg/css/editor.css';
	$version_path       = monsterinsights_is_pro_version() ? 'pro' : 'lite';

	$plugins_js_url = apply_filters(
		'monsterinsights_editor_scripts_url',
		plugins_url( $plugins_js_path, MONSTERINSIGHTS_PLUGIN_FILE )
	);

	$plugins_css_url = apply_filters(
		'monsterinsights_editor_style_url',
		plugins_url( $plugins_style_path, MONSTERINSIGHTS_PLUGIN_FILE )
	);

	$js_dependencies = array(
		'wp-plugins',
		'wp-element',
		'wp-i18n',
		'wp-api-request',
		'wp-data',
		'wp-hooks',
		'wp-plugins',
		'wp-components',
		'wp-blocks',
		'wp-block-editor',
		'wp-compose',
	);

	if (
		! $wp_scripts->query( 'wp-edit-widgets', 'enqueued' ) &&
		! $wp_scripts->query( 'wp-customize-widgets', 'enqueued' )
	) {
		$js_dependencies[] = 'wp-editor';
		$js_dependencies[] = 'wp-edit-post';
	}

	// Enqueue our plugin JavaScript.
	wp_enqueue_script(
		'monsterinsights-gutenberg-editor-js',
		$plugins_js_url,
		$js_dependencies,
		monsterinsights_get_asset_version(),
		true
	);

	// Enqueue our plugin JavaScript.
	wp_enqueue_style(
		'monsterinsights-gutenberg-editor-css',
		$plugins_css_url,
		array(),
		monsterinsights_get_asset_version()
	);

	$plugins                 = get_plugins();
	$install_woocommerce_url = false;
	if ( current_user_can( 'install_plugins' ) ) {
		$woo_key = 'woocommerce/woocommerce.php';
		if ( array_key_exists( $woo_key, $plugins ) ) {
			$install_woocommerce_url = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $woo_key ), 'activate-plugin_' . $woo_key );
		} else {
			$install_woocommerce_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' );
		}
	}

	$posttype = monsterinsights_get_current_post_type();

	// Localize script for sidebar plugins.
	wp_localize_script(
		'monsterinsights-gutenberg-editor-js',
		'monsterinsights_gutenberg_tool_vars',
		apply_filters( 'monsterinsights_gutenberg_tool_vars', array(
			'ajaxurl'                      => admin_url( 'admin-ajax.php' ),
			'nonce'                        => wp_create_nonce( 'monsterinsights_gutenberg_headline_nonce' ),
			'allowed_post_types'           => apply_filters( 'monsterinsights_headline_analyzer_post_types', array( 'post' ) ),
			'current_post_type'            => $posttype,
			'translations'                 => wp_get_jed_locale_data( monsterinsights_is_pro_version() ? 'ga-premium' : 'google-analytics-for-wordpress' ),
			'is_headline_analyzer_enabled' => apply_filters( 'monsterinsights_headline_analyzer_enabled', true ) && 'true' !== monsterinsights_get_option( 'disable_headline_analyzer' ),
			'reports_url'                  => add_query_arg( 'page', 'monsterinsights_reports', admin_url( 'admin.php' ) ),
			'vue_assets_path'              => plugins_url( $version_path . '/assets/vue/', MONSTERINSIGHTS_PLUGIN_FILE ),
			'is_woocommerce_installed'     => class_exists( 'WooCommerce' ),
			'license_type'                 => MonsterInsights()->license->get_license_type(),
			'upgrade_url'                  => monsterinsights_get_upgrade_link( 'pageinsights-meta', 'products' ),
			'install_woocommerce_url'      => $install_woocommerce_url,
			'supports_custom_fields'       => post_type_supports( $posttype, 'custom-fields' ),
			'public_post_type'             => $posttype ? is_post_type_viewable( $posttype ) : 0,
			'page_insights_addon_active'   => class_exists( 'MonsterInsights_Page_Insights' ),
			'page_insights_nonce'          => wp_create_nonce( 'mi-admin-nonce' ),
			'isnetwork'                    => is_network_admin(),
			'is_v4'                        => true,
			'dismiss_envira_promo'         => isset($plugins['envira-gallery-lite/envira-gallery-lite.php']) || isset($plugins['envira-gallery/envira-gallery.php']) || get_transient('_monsterinsights_dismiss_envira_promo'),
		) )
	);
}

add_action( 'enqueue_block_editor_assets', 'monsterinsights_gutenberg_editor_assets' );
