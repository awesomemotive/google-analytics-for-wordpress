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
		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
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
		// Add the welcome screen to the network dashboard.
		add_action( 'network_admin_menu', array( $this, 'register' ) );

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
		if ( ! get_transient( '_monsterinsights_activation_redirect' ) || isset( $_GET['monsterinsights-redirect'] ) ) {
			return;
		}

		// Delete the redirect transient.
		delete_transient( '_monsterinsights_activation_redirect' );

		// Bail if activating from network, or bulk.
		if ( isset( $_GET['activate-multi'] ) ) {
			return;
		}

		$upgrade = get_option( 'monsterinsights_version_upgraded_from', false );
		if ( apply_filters( 'monsterinsights_enable_onboarding_wizard', false === $upgrade ) ) {
			$path     = 'index.php?page=monsterinsights-getting-started&monsterinsights-redirect=1';
			$redirect = is_network_admin() ? network_admin_url( $path ) : admin_url( $path );
			wp_safe_redirect( $redirect );
			exit;
		}
	}

	/**
	 * Scripts for loading the welcome screen Vue instance.
	 */
	public function welcome_scripts() {

		$current_screen = get_current_screen();
		$screens        = array(
			'dashboard_page_monsterinsights-getting-started',
			'index_page_monsterinsights-getting-started-network',
		);

		if ( empty( $current_screen->id ) || ! in_array( $current_screen->id, $screens, true ) ) {
			return;
		}

		$version_path = monsterinsights_is_pro_version() ? 'pro' : 'lite';
		if ( ! defined( 'MONSTERINSIGHTS_LOCAL_JS_URL' ) ) {
			MonsterInsights_Admin_Assets::enqueue_script_specific_css( 'src/modules/wizard-onboarding/wizard.js' );
		}

		$app_js_url = MonsterInsights_Admin_Assets::get_js_url( 'src/modules/wizard-onboarding/wizard.js' );
		wp_register_script( 'monsterinsights-vue-script', $app_js_url, array(), monsterinsights_get_asset_version(), true );
		wp_enqueue_script( 'monsterinsights-vue-script' );

		$user_data = wp_get_current_user();

		wp_localize_script(
			'monsterinsights-vue-script',
			'monsterinsights',
			array(
				'ajax'                 => add_query_arg( 'page', 'monsterinsights-onboarding', admin_url( 'admin-ajax.php' ) ),
				'nonce'                => wp_create_nonce( 'mi-admin-nonce' ),
				'network'              => is_network_admin(),
				'translations'         => wp_get_jed_locale_data( 'mi-vue-app' ),
				'assets'               => plugins_url( $version_path . '/assets/vue', MONSTERINSIGHTS_PLUGIN_FILE ),
				'roles'                => monsterinsights_get_roles(),
				'roles_manage_options' => monsterinsights_get_manage_options_roles(),
				'wizard_url'           => is_network_admin() ? network_admin_url( 'index.php?page=monsterinsights-onboarding' ) : admin_url( 'index.php?page=monsterinsights-onboarding' ),
				'shareasale_id'        => monsterinsights_get_shareasale_id(),
				'shareasale_url'       => monsterinsights_get_shareasale_url( monsterinsights_get_shareasale_id(), '' ),
				// Used to add notices for future deprecations.
				'versions'             => monsterinsights_get_php_wp_version_warning_data(),
				'plugin_version'       => MONSTERINSIGHTS_VERSION,
				'first_name'           => ! empty( $user_data->first_name ) ? $user_data->first_name : '',
				'exit_url'             => add_query_arg( 'page', 'monsterinsights_settings', admin_url( 'admin.php' ) ),
				'had_ecommerce'        => monsterinsights_get_option( 'gadwp_ecommerce', false ),
			)
		);
	}

	/**
	 * Load the welcome screen content.
	 */
	public function welcome_screen() {
		do_action( 'monsterinsights_head' );

		monsterinsights_settings_error_page( $this->get_screen_id() );
		monsterinsights_settings_inline_js();
	}

	/**
	 * Get the screen id to control which Vue component is loaded.
	 *
	 * @return string
	 */
	public function get_screen_id() {
		$screen_id = 'monsterinsights-welcome';

		if ( defined( 'EXACTMETRICS_VERSION' ) && function_exists( 'ExactMetrics' ) ) {
			$migrated = monsterinsights_get_option( 'gadwp_migrated', 0 );
			if ( time() - $migrated < HOUR_IN_SECONDS || isset( $_GET['monsterinsights-migration'] ) ) {
				$screen_id = 'monsterinsights-migration-wizard';
			}
		}

		return $screen_id;
	}
}

new MonsterInsights_Welcome();
