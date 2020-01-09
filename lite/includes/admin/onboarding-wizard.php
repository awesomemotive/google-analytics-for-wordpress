<?php
/**
 * MonsterInsights Onboarding Wizard
 *
 * @since 7.3
 *
 * @package MonsterInsights
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MonsterInsights_Dashboard_Widget
 */
class MonsterInsights_Onboarding_Wizard {


	/**
	 * MonsterInsights_Onboarding_Wizard constructor.
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'maybe_load_onboarding_wizard' ) );

		add_action( 'admin_menu', array( $this, 'add_dashboard_page' ) );

		add_action( 'wp_ajax_monsterinsights_onboarding_wpforms_install', array(
			$this,
			'install_and_activate_wpforms',
		) );

		add_action( 'wp_ajax_monsterinsights_onboarding_get_errors', array(
			$this,
			'get_install_errors',
		) );

		// This will only be called in the Onboarding Wizard context because of previous checks.
		add_filter( 'monsterinsights_maybe_authenticate_siteurl', array( $this, 'change_return_url' ) );
		add_filter( 'monsterinsights_auth_success_redirect_url', array( $this, 'change_success_url' ) );
		add_filter( 'monsterinsights_reauth_success_redirect_url', array( $this, 'change_success_url' ) );

	}

	/**
	 * Checks if the Wizard should be loaded in current context.
	 */
	public function maybe_load_onboarding_wizard() {

		// Check for wizard-specific parameter
		// Allow plugins to disable the onboarding wizard
		// Check if current user is allowed to save settings.
		if ( ! ( isset( $_GET['page'] ) || 'monsterinsights-onboarding' !== $_GET['page'] || apply_filters( 'monsterinsights_enable_onboarding_wizard', true ) || ! current_user_can( 'monsterinsights_save_settings' ) ) ) { // WPCS: CSRF ok, input var ok.
			return;
		}

		// Don't load the interface if doing an ajax call.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		set_current_screen();

		// Remove an action in the Gutenberg plugin ( not core Gutenberg ) which throws an error.
		remove_action( 'admin_print_styles', 'gutenberg_block_editor_admin_print_styles' );

		$this->load_onboarding_wizard();

	}

	/**
	 * Register page through WordPress's hooks.
	 */
	public function add_dashboard_page() {
		add_dashboard_page( '', '', 'monsterinsights_save_settings', 'monsterinsights-onboarding', '' );
	}

	/**
	 * Load the Onboarding Wizard template.
	 */
	private function load_onboarding_wizard() {

		$this->enqueue_scripts();

		$this->onboarding_wizard_header();
		$this->onboarding_wizard_content();
		$this->onboarding_wizard_footer();

		exit;

	}

	/**
	 * Load the scripts needed for the Onboarding Wizard.
	 */
	public function enqueue_scripts() {

		global $wp_version;
		$version_path = monsterinsights_is_pro_version() ? 'pro' : 'lite';
		$rtl          = is_rtl() ? '.rtl' : '';
		if ( ! defined( 'MONSTERINSIGHTS_LOCAL_WIZARD_JS_URL' ) ) {
			wp_enqueue_style( 'monsterinsights-vue-style-vendors', plugins_url( $version_path . '/assets/vue/css/chunk-vendors' . $rtl . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
			wp_enqueue_style( 'monsterinsights-vue-style-common', plugins_url( $version_path . '/assets/vue/css/chunk-common' . $rtl . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
			wp_enqueue_style( 'monsterinsights-vue-style', plugins_url( $version_path . '/assets/vue/css/wizard' . $rtl . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
			wp_enqueue_script( 'monsterinsights-vue-vendors', plugins_url( $version_path . '/assets/vue/js/chunk-vendors.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );
			wp_enqueue_script( 'monsterinsights-vue-common', plugins_url( $version_path . '/assets/vue/js/chunk-common.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );
			wp_register_script( 'monsterinsights-vue-script', plugins_url( $version_path . '/assets/vue/js/wizard.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(
				'monsterinsights-vue-vendors',
				'monsterinsights-vue-common',
			), monsterinsights_get_asset_version(), true );
		} else {
			wp_enqueue_script( 'monsterinsights-vue-vendors', MONSTERINSIGHTS_LOCAL_VENDORS_JS_URL, array(), monsterinsights_get_asset_version(), true );
			wp_enqueue_script( 'monsterinsights-vue-common', MONSTERINSIGHTS_LOCAL_COMMON_JS_URL, array(), monsterinsights_get_asset_version(), true );
			wp_register_script( 'monsterinsights-vue-script', MONSTERINSIGHTS_LOCAL_WIZARD_JS_URL, array(
				'monsterinsights-vue-vendors',
				'monsterinsights-vue-common',
			), monsterinsights_get_asset_version(), true );
		}
		wp_enqueue_script( 'monsterinsights-vue-script' );

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
				'wizard_url'           => admin_url( 'index.php?page=monsterinsights-onboarding' ),
				'is_eu'                => $this->should_include_eu_addon(),
				'activate_nonce'       => wp_create_nonce( 'monsterinsights-activate' ),
				'install_nonce'        => wp_create_nonce( 'monsterinsights-install' ),
				'exit_url'             => add_query_arg( 'page', 'monsterinsights_settings', admin_url( 'admin.php' ) ),
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
			)
		);

	}

	/**
	 * Outputs the simplified header used for the Onboarding Wizard.
	 */
	public function onboarding_wizard_header() {
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width"/>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<title><?php esc_html_e( 'MonsterInsights &rsaquo; Onboarding Wizard', 'google-analytics-for-wordpress' ); ?></title>
			<?php do_action( 'admin_print_styles' ); ?>
			<?php do_action( 'admin_head' ); ?>
			<?php do_action( 'admin_print_scripts' ); ?>
		</head>
		<body class="monsterinsights-onboarding">
		<?php
	}

	/**
	 * Outputs the content of the current step.
	 */
	public function onboarding_wizard_content() {
		monsterinsights_settings_error_page( 'monsterinsights-vue-onboarding-wizard', '<a href="' . admin_url() . '">' . esc_html__( 'Return to Dashboard', 'google-analytics-for-wordpress' ) . '</a>' );
		monsterinsights_settings_inline_js();
	}

	/**
	 * Outputs the simplified footer used for the Onboarding Wizard.
	 */
	public function onboarding_wizard_footer() {
		?>
		<?php wp_print_scripts( 'monsterinsights-vue-script' ); ?>
		</body>
		</html>
		<?php
	}

	/**
	 * Check if we should suggest the EU Compliance addon by attempting to determine the website's location.
	 *
	 * @return bool
	 */
	public function should_include_eu_addon() {

		// Is WooCommerce installed and the countries class installed.
		if ( class_exists( 'WooCommerce' ) && class_exists( 'WC_Countries' ) ) {
			$wc_countries = new WC_Countries();
			$country      = $wc_countries->get_base_country();
			$continent    = $wc_countries->get_continent_code_for_country( $country );

			if ( 'EU' === $continent ) {
				return true;
			}
		}

		// Is EDD installed?
		if ( class_exists( 'Easy_Digital_Downloads' ) && function_exists( 'edd_get_shop_country' ) ) {
			$country      = strtoupper( edd_get_shop_country() );
			$eu_countries = self::get_eu_countries();

			// Check if the country code is in the list of EU countries we have stored.
			if ( in_array( $country, $eu_countries, true ) ) {
				return true;
			}
		}

		// If no store installed, check the timezone setting.
		$timezone_string = get_option( 'timezone_string' );
		if ( 0 === strpos( strtolower( $timezone_string ), 'europe' ) ) {
			// If the timezone is set to Europe, assume the website is based in Europe.
			return true;
		}

		return false;

	}

	/**
	 * Install WPForms lite and activate it, prevent initial setup step.
	 *
	 * @return null|string
	 */
	public function install_and_activate_wpforms() {

		check_ajax_referer( 'monsterinsights-install', 'nonce' );

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json( array(
				'message' => esc_html__( 'You are not allowed to install plugins', 'google-analytics-for-wordpress' ),
			) );
		}

		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$api = plugins_api( 'plugin_information', array(
			'slug'   => 'wpforms-lite',
			'fields' => array(
				'short_description' => false,
				'sections'          => false,
				'requires'          => false,
				'rating'            => false,
				'ratings'           => false,
				'downloaded'        => false,
				'last_updated'      => false,
				'added'             => false,
				'tags'              => false,
				'compatibility'     => false,
				'homepage'          => false,
				'donate_link'       => false,
			),
		) );

		if ( is_wp_error( $api ) ) {
			return $api->get_error_message();
		}

		$download_url = $api->download_link;

		$method = '';
		$url    = add_query_arg(
			array(
				'page' => 'monsterinsights-settings',
			),
			admin_url( 'admin.php' )
		);
		$url    = esc_url( $url );

		ob_start();
		if ( false === ( $creds = request_filesystem_credentials( $url, $method, false, false, null ) ) ) {
			$form = ob_get_clean();

			wp_send_json( array( 'form' => $form ) );
		}

		// If we are not authenticated, make it happen now.
		if ( ! WP_Filesystem( $creds ) ) {
			ob_start();
			request_filesystem_credentials( $url, $method, true, false, null );
			$form = ob_get_clean();

			wp_send_json( array( 'form' => $form ) );

		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		$base = MonsterInsights();
		require_once plugin_dir_path( $base->file ) . '/includes/admin/licensing/skin.php';

		// Create the plugin upgrader with our custom skin.
		$installer = new Plugin_Upgrader( new MonsterInsights_Skin() );
		$installer->install( $download_url );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();
		if ( $installer->plugin_info() ) {
			// Set this option to prevent WP Forms setup from showing up after the wizard completes.
			update_option( 'wpforms_activation_redirect', true );
			activate_plugin( $installer->plugin_info() );
			wp_send_json_success();
		}

		wp_die();

	}

	/**
	 * Update the redirect url so the user returns to the Onboarding Wizard after auth.
	 *
	 * @param string $siteurl The url to which the user is redirected for auth.
	 *
	 * @return mixed
	 */
	public function change_return_url( $siteurl ) {

		$url = wp_parse_url( $siteurl );

		if ( isset( $url['query'] ) ) {

			parse_str( $url['query'], $parameters );

			$parameters['return'] = rawurlencode( add_query_arg( array(
				'page' => 'monsterinsights-onboarding',
			), admin_url() ) );

			$siteurl = str_replace( $url['query'], '', $siteurl );

			$siteurl = add_query_arg( $parameters, $siteurl );

			$siteurl .= '#/authenticate';

		}

		return $siteurl;

	}

	/**
	 * Update the success redirect URL so if all is well we get to the next step.
	 *
	 * @param string $siteurl The url to which the user is redirected after a successful auth.
	 *
	 * @return mixed
	 */
	public function change_success_url( $siteurl ) {

		$siteurl = add_query_arg( array(
			'page' => 'monsterinsights-onboarding',
		), admin_url() );

		$siteurl .= '#/recommended_settings';

		return $siteurl;

	}

	/**
	 * Retrieve an array of European countries.
	 *
	 * @return array
	 */
	public static function get_eu_countries() {
		return array(
			'AD',
			'AL',
			'AT',
			'AX',
			'BA',
			'BE',
			'BG',
			'BY',
			'CH',
			'CY',
			'CZ',
			'DE',
			'DK',
			'EE',
			'ES',
			'FI',
			'FO',
			'FR',
			'GB',
			'GG',
			'GI',
			'GR',
			'HR',
			'HU',
			'IE',
			'IM',
			'IS',
			'IT',
			'JE',
			'LI',
			'LT',
			'LU',
			'LV',
			'MC',
			'MD',
			'ME',
			'MK',
			'MT',
			'NL',
			'NO',
			'PL',
			'PT',
			'RO',
			'RS',
			'RU',
			'SE',
			'SI',
			'SJ',
			'SK',
			'SM',
			'TR',
			'UA',
			'VA',
		);
	}

	/**
	 * Ajax handler for grabbing the installed code status.
	 */
	public function get_install_errors() {

		wp_send_json( monsterinsights_is_code_installed_frontend() );

	}

}

new MonsterInsights_Onboarding_Wizard();
