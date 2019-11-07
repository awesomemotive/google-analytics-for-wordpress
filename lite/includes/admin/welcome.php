<?php

/**
 * Class MonsterInsights_Welcome
 */
class MonsterInsights_Welcome {

	/**
	 * MonsterInsights_Welcome constructor.
	 */
	public function __construct() {

		// If we are not in admin or admin ajax, return
		if ( ! is_admin() ) {
			return;
		}

		// If user is in admin ajax or doing cron, return
		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX  ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			return;
		}

		// If user is not logged in, return
		if ( ! is_user_logged_in() ) {
			return;
		}

		// If user cannot manage_options, return
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_action( 'admin_init', array( $this, 'maybe_redirect' ), 9999 );
		add_action( 'admin_menu', array( $this, 'register' ) );
		add_action( 'admin_head', array( $this, 'hide_menu' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'welcome_scripts' ) );
	}

	/**
	 * Register the pages to be used for the Welcome screen.
	 *
	 * These pages will be removed from the Dashboard menu, so they will
	 * not actually show. Sneaky, sneaky.
	 *
	 * @since 1.0.0
	 */
	public function register() {

		// Getting started - shows after installation.
		add_dashboard_page(
			esc_html__( 'Welcome to MonsterInsights', 'google-analytics-for-wordpress' ),
			esc_html__( 'Welcome to MonsterInsights', 'google-analytics-for-wordpress' ),
			apply_filters( 'monsterinsights_welcome_cap', 'manage_options' ),
			'monsterinsights-getting-started',
			array( $this, 'welcome_screen' )
		);
	}

	/**
	 * Removed the dashboard pages from the admin menu.
	 *
	 * This means the pages are still available to us, but hidden.
	 *
	 * @since 1.0.0
	 */
	public function hide_menu() {
		remove_submenu_page( 'index.php', 'monsterinsights-getting-started' );
	}


	/**
	 * Check if we should do any redirect.
	 */
	public function maybe_redirect() {

		// Bail if no activation redirect.
		if ( ! get_transient( '_monsterinsights_activation_redirect' ) ) {
			return;
		}

		// Delete the redirect transient.
		delete_transient( '_monsterinsights_activation_redirect' );

		// Bail if activating from network, or bulk.
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) { // WPCS: CSRF ok, input var ok.
			return;
		}

		$upgrade = get_option( 'monsterinsights_version_upgraded_from', false );
		if ( apply_filters( 'monsterinsights_enable_onboarding_wizard', false === $upgrade ) ) {
			$redirect = admin_url( 'index.php?page=monsterinsights-getting-started' );
			wp_safe_redirect( $redirect );
			exit;
		}
	}

	/**
	 * Scripts for loading the welcome screen Vue instance.
	 */
	public function welcome_scripts() {

		$current_screen = get_current_screen();

		if ( empty( $current_screen->id ) || 'dashboard_page_monsterinsights-getting-started' !== $current_screen->id ) {
			return;
		}

		global $wp_version;
		$version_path = monsterinsights_is_pro_version() ? 'pro' : 'lite';
		if ( ! defined( 'MONSTERINSIGHTS_LOCAL_WIZARD_JS_URL' ) ) {
			wp_enqueue_style( 'monsterinsights-vue-welcome-style-vendors', plugins_url( $version_path . '/assets/vue/css/chunk-vendors.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
			wp_enqueue_style( 'monsterinsights-vue-welcome-style-common', plugins_url( $version_path . '/assets/vue/css/chunk-common.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
			wp_enqueue_style( 'monsterinsights-vue-welcome-style', plugins_url( $version_path . '/assets/vue/css/wizard.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
			wp_enqueue_script( 'monsterinsights-vue-welcome-vendors', plugins_url( $version_path . '/assets/vue/js/chunk-vendors.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );
			wp_enqueue_script( 'monsterinsights-vue-welcome-common', plugins_url( $version_path . '/assets/vue/js/chunk-common.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );
			wp_register_script( 'monsterinsights-vue-welcome-script', plugins_url( $version_path . '/assets/vue/js/wizard.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(
				'monsterinsights-vue-welcome-vendors',
				'monsterinsights-vue-welcome-common',
			), monsterinsights_get_asset_version(), true );
		} else {
			wp_enqueue_script( 'monsterinsights-vue-welcome-vendors', MONSTERINSIGHTS_LOCAL_VENDORS_JS_URL, array(), monsterinsights_get_asset_version(), true );
			wp_enqueue_script( 'monsterinsights-vue-welcome-common', MONSTERINSIGHTS_LOCAL_COMMON_JS_URL, array(), monsterinsights_get_asset_version(), true );
			wp_register_script( 'monsterinsights-vue-welcome-script', MONSTERINSIGHTS_LOCAL_WIZARD_JS_URL, array(
				'monsterinsights-vue-welcome-vendors',
				'monsterinsights-vue-welcome-common',
			), monsterinsights_get_asset_version(), true );
		}
		wp_enqueue_script( 'monsterinsights-vue-welcome-script' );

		$user_data = wp_get_current_user();

		wp_localize_script(
			'monsterinsights-vue-welcome-script',
			'monsterinsights',
			array(
				'ajax'                 => add_query_arg( 'page', 'monsterinsights-onboarding', admin_url( 'admin-ajax.php' ) ),
				'nonce'                => wp_create_nonce( 'mi-admin-nonce' ),
				'network'              => is_network_admin(),
				'translations'         => wp_get_jed_locale_data( 'mi-vue-app' ),
				'assets'               => plugins_url( $version_path . '/assets/vue', MONSTERINSIGHTS_PLUGIN_FILE ),
				'roles'                => monsterinsights_get_roles(),
				'roles_manage_options' => monsterinsights_get_manage_options_roles(),
				'wizard_url'           => admin_url( 'index.php?page=monsterinsights-onboarding' ),
				'shareasale_id'        => monsterinsights_get_shareasale_id(),
				'shareasale_url'       => monsterinsights_get_shareasale_url( monsterinsights_get_shareasale_id(), '' ),
				// Used to add notices for future deprecations.
				'versions'             => array(
					'php_version'          => phpversion(),
					'php_version_below_54' => apply_filters( 'monsterinsights_temporarily_hide_php_52_and_53_upgrade_warnings', version_compare( phpversion(), '5.4', '<' ) ),
					'php_version_below_56' => apply_filters( 'monsterinsights_temporarily_hide_php_54_and_55_upgrade_warnings', version_compare( phpversion(), '5.6', '<' ) ),
					'php_update_link'      => monsterinsights_get_url( 'settings-notice', 'settings-page', 'https://www.monsterinsights.com/docs/update-php/' ),
					'wp_version'           => $wp_version,
					'wp_version_below_46'  => version_compare( $wp_version, '4.6', '<' ),
					'wp_version_below_49'  => version_compare( $wp_version, '4.9', '<' ),
					'wp_update_link'       => monsterinsights_get_url( 'settings-notice', 'settings-page', 'https://www.monsterinsights.com/docs/update-wordpress/' ),
				),
				'plugin_version'       => MONSTERINSIGHTS_VERSION,
				'first_name'           => ! empty( $user_data->first_name ) ? $user_data->first_name : '',
			)
		);
	}

	/**
	 * Load the welcome screen content.
	 */
	public function welcome_screen() {
		do_action( 'monsterinsights_head' );

		monsterinsights_settings_error_page( 'monsterinsights-welcome' );
		monsterinsights_settings_inline_js();
	}
}

new MonsterInsights_Welcome();
