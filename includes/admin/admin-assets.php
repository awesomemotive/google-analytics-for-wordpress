<?php
/**
 * WP ADMIN assets will be enqueued here.
 *
 * @package monsterinsights
 */

/**
 * Class MonsterInsights_Admin_Assets
 * This class is responsible for load CSS and JS in admin panel.
 */
class MonsterInsights_Admin_Assets {
	/**
	 * MonsterInsights handles.
	 */
	private $own_handles = array(
		'monsterinsights-vue-script',
		'monsterinsights-vue-frontend',
		'monsterinsights-vue-reports',
		'monsterinsights-vue-widget',
	);

	/**
	 * Store manifest.json file content.
	 *
	 * @var array
	 */
	private static $manifest_data;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_filter( 'script_loader_tag', array( $this, 'script_loader_tag' ), 99999, 3 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		$this->get_manifest_data();
	}

	/**
	 * Update script tag.
	 * The vue code needs type=module.
	 */
	public function script_loader_tag( $tag, $handle, $src ) {

		if ( ! in_array( $handle, $this->own_handles ) ) {
			return $tag;
		}

		// Change the script tag by adding type="module" and return it.
		$html = str_replace( '></script>', ' type="module"></script>', $tag );

		$domain = monsterinsights_is_pro_version() ? 'ga-premium' : 'google-analytics-for-wordpress';
		$html   = monsterinsights_get_printable_translations( $domain ) . $html;

		return $html;
	}

	/**
	 * Loads styles for all MonsterInsights-based Administration Screens.
	 *
	 * @return null Return early if not on the proper screen.
	 */
	public function admin_styles() {

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

		// For the settings pages, load the Vue app scripts.
		if ( monsterinsights_is_settings_page() ) {
			if ( ! defined( 'MONSTERINSIGHTS_LOCAL_JS_URL' ) ) {
				+$this->enqueue_script_specific_css( 'src/modules/settings/settings.js' );
			}

			// Don't load other scripts on the settings page.
			return;
		}

		// For the report pages, load the Vue app scripts.
		if ( monsterinsights_is_reports_page() ) {
			if ( ! defined( 'MONSTERINSIGHTS_LOCAL_JS_URL' ) ) {
				$this->enqueue_script_specific_css( 'src/modules/reports/reports.js' );
			}

			return;
		}

		// Tooltips
		wp_enqueue_script( 'jquery-ui-tooltip' );
	}

	/**
	 * Loads scripts for all MonsterInsights-based Administration Screens.
	 *
	 * @return null Return early if not on the proper screen.
	 */
	public function admin_scripts() {

		// Our Common Admin JS.
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_script( 'monsterinsights-admin-common-script', plugins_url( 'assets/js/admin-common' . $suffix . '.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version(), true );

		wp_localize_script(
			'monsterinsights-admin-common-script',
			'monsterinsights_admin_common',
			array(
				'ajax'                 => admin_url( 'admin-ajax.php' ),
				'dismiss_notice_nonce' => wp_create_nonce( 'monsterinsights-dismiss-notice' ),
			)
		);

		// Get current screen.
		$screen = get_current_screen();

		// Bail if we're not on a MonsterInsights screen.
		if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
			return;
		}

		$version_path = monsterinsights_is_pro_version() ? 'pro' : 'lite';

		// For the settings page, load the Vue app.
		if ( monsterinsights_is_settings_page() ) {
			$app_js_url = self::get_js_url( 'src/modules/settings/settings.js' );
			wp_register_script( 'monsterinsights-vue-script', $app_js_url, array( 'wp-i18n' ), monsterinsights_get_asset_version(), true );
			wp_enqueue_script( 'monsterinsights-vue-script' );

			$plugins         = get_plugins();
			$install_amp_url = false;
			if ( monsterinsights_can_install_plugins() ) {
				$amp_key = 'amp/amp.php';
				if ( array_key_exists( $amp_key, $plugins ) ) {
					$install_amp_url = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $amp_key ), 'activate-plugin_' . $amp_key );
				} else {
					$install_amp_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=amp' ), 'install-plugin_amp' );
				}
			}

			$install_woocommerce_url = false;
			if ( monsterinsights_can_install_plugins() ) {
				$woo_key = 'woocommerce/woocommerce.php';
				if ( array_key_exists( $woo_key, $plugins ) ) {
					$install_woocommerce_url = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $woo_key ), 'activate-plugin_' . $woo_key );
				} else {
					$install_woocommerce_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' );
				}
			}

			$prepared_dimensions = array();
			if ( class_exists( 'MonsterInsights_Admin_Custom_Dimensions' ) ) {
				$dimensions          = new MonsterInsights_Admin_Custom_Dimensions();
				$dimensions          = $dimensions->custom_dimensions();
				$prepared_dimensions = array();
				foreach ( $dimensions as $dimension_type => $dimension ) {
					$dimension['type']     = $dimension_type;
					$prepared_dimensions[] = $dimension;
				}
			}

			$is_authed = ( MonsterInsights()->auth->is_authed() || MonsterInsights()->auth->is_network_authed() );

			wp_localize_script(
				'monsterinsights-vue-script',
				'monsterinsights',
				array(
					'ajax'                            => admin_url( 'admin-ajax.php' ),
					'nonce'                           => wp_create_nonce( 'mi-admin-nonce' ),
					'network'                         => is_network_admin(),
					'assets'                          => plugins_url( $version_path . '/assets/vue', MONSTERINSIGHTS_PLUGIN_FILE ),
					'roles'                           => monsterinsights_get_roles(),
					'roles_manage_options'            => monsterinsights_get_manage_options_roles(),
					'shareasale_id'                   => monsterinsights_get_shareasale_id(),
					'shareasale_url'                  => monsterinsights_get_shareasale_url( monsterinsights_get_shareasale_id(), '' ),
					'addons_url'                      => is_multisite() ? network_admin_url( 'admin.php?page=monsterinsights_network#/addons' ) : admin_url( 'admin.php?page=monsterinsights_settings#/addons' ),
					'seo_settings_page_url'           => is_multisite() ? network_admin_url( 'admin.php?page=monsterinsights_network#/seo' ) : admin_url( 'admin.php?page=monsterinsights_settings#/seo' ),
					'aioseo_dashboard_url'            => is_multisite() ? network_admin_url( 'admin.php?page=aioseo' ) : admin_url( 'admin.php?page=aioseo' ),
					'wp_plugins_page_url'             => is_multisite() ? network_admin_url( 'plugins.php' ) : admin_url( 'plugins.php' ),
					'email_summary_url'               => admin_url( 'admin.php?monsterinsights_email_preview&monsterinsights_email_template=summary' ),
					'install_amp_url'                 => $install_amp_url,
					'install_woo_url'                 => $install_woocommerce_url,
					'dimensions'                      => $prepared_dimensions,
					'wizard_url'                      => is_network_admin() ? network_admin_url( 'index.php?page=monsterinsights-onboarding' ) : admin_url( 'index.php?page=monsterinsights-onboarding' ),
					'install_plugins'                 => monsterinsights_can_install_plugins(),
					'unfiltered_html'                 => current_user_can( 'unfiltered_html' ),
					'activate_nonce'                  => wp_create_nonce( 'monsterinsights-activate' ),
					'deactivate_nonce'                => wp_create_nonce( 'monsterinsights-deactivate' ),
					'install_nonce'                   => wp_create_nonce( 'monsterinsights-install' ),
					// Used to add notices for future deprecations.
					'versions'                        => monsterinsights_get_php_wp_version_warning_data(),
					'plugin_version'                  => MONSTERINSIGHTS_VERSION,
					'is_admin'                        => true,
					'admin_email'                     => get_option( 'admin_email' ),
					'site_url'                        => get_site_url(),
					'reports_url'                     => add_query_arg( 'page', 'monsterinsights_reports', admin_url( 'admin.php' ) ),
					'ecommerce_report_url'            => add_query_arg( 'page', 'monsterinsights_reports#/ecommerce', admin_url( 'admin.php' ) ),
					'ecommerce_settings_tab_url'      => add_query_arg( 'page', 'monsterinsights_settings#/ecommerce', admin_url( 'admin.php' ) ),
					'first_run_notice'                => apply_filters( 'monsterinsights_settings_first_time_notice_hide', monsterinsights_get_option( 'monsterinsights_first_run_notice' ) ),
					'getting_started_url'             => is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_network#/about' ) : admin_url( 'admin.php?page=monsterinsights_settings#/about/getting-started' ),
					'authed'                          => $is_authed,
					'new_pretty_link_url'             => admin_url( 'post-new.php?post_type=pretty-link' ),
					'wpmailsmtp_admin_url'            => admin_url( 'admin.php?page=wp-mail-smtp' ),
					'load_headline_analyzer_settings' => monsterinsights_load_gutenberg_app() ? 'true' : 'false',
					'exit_url'                        => add_query_arg( 'page', 'monsterinsights_settings', admin_url( 'admin.php' ) ),
					'timezone'                        => date( 'e' ),
					'funnelkit_stripe_woo_page_url'   => admin_url( 'admin.php?page=wc-settings&tab=fkwcs_api_settings' ),
					'funnelkit_stripe_woo_nonce'      => wp_create_nonce( 'monsterinsights-funnelkit-stripe-woo-nonce' ),
				)
			);

			// Don't load other scripts on the settings page.
			return;
		}

		// For the report pages, load the Vue app.
		if ( monsterinsights_is_reports_page() ) {

			$app_js_url = self::get_js_url( 'src/modules/reports/reports.js' );
			wp_register_script( 'monsterinsights-vue-reports', $app_js_url, array( 'wp-i18n' ), monsterinsights_get_asset_version(), true );
			wp_enqueue_script( 'monsterinsights-vue-reports' );

			// We do not have a current auth.
			$auth      = MonsterInsights()->auth;
			$site_auth = $auth->get_viewname();
			$ms_auth   = is_multisite() && $auth->get_network_viewname();

			// Localize the script with the necessary data.
			wp_localize_script(
				'monsterinsights-vue-reports',
				'monsterinsights',
				array(
					'ajax'                => admin_url( 'admin-ajax.php' ),
					'nonce'               => wp_create_nonce( 'mi-admin-nonce' ),
					'rest_nonce'          => wp_create_nonce( 'wp_rest' ),
					'rest_url'            => get_rest_url(),
					'network'             => is_network_admin(),
					'translations'        => wp_get_jed_locale_data( monsterinsights_is_pro_version() ? 'ga-premium' : 'google-analytics-for-wordpress' ),
					'assets'              => plugins_url( $version_path . '/assets/vue', MONSTERINSIGHTS_PLUGIN_FILE ),
					'pro_assets'          => plugins_url( $version_path . '/assets', MONSTERINSIGHTS_PLUGIN_FILE ),
					'shareasale_id'       => monsterinsights_get_shareasale_id(),
					'shareasale_url'      => monsterinsights_get_shareasale_url( monsterinsights_get_shareasale_id(), '' ),
					'addons_url'          => is_multisite() ? network_admin_url( 'admin.php?page=monsterinsights_network#/addons' ) : admin_url( 'admin.php?page=monsterinsights_settings#/addons' ),
					'timezone'            => date('e'), // phpcs:ignore
					'authed'              => $site_auth || $ms_auth,
					'settings_url'        => add_query_arg( 'page', 'monsterinsights_settings', admin_url( 'admin.php' ) ),
					// Used to add notices for future deprecations.
					'versions'            => monsterinsights_get_php_wp_version_warning_data(),
					'plugin_version'      => MONSTERINSIGHTS_VERSION,
					'is_admin'            => true,
					'admin_email'         => get_option( 'admin_email' ),
					'site_url'            => get_site_url(),
					'wizard_url'          => is_network_admin() ? network_admin_url( 'index.php?page=monsterinsights-onboarding' ) : admin_url( 'index.php?page=monsterinsights-onboarding' ),
					'install_nonce'       => wp_create_nonce( 'monsterinsights-install' ),
					'activate_nonce'      => wp_create_nonce( 'monsterinsights-activate' ),
					'deactivate_nonce'    => wp_create_nonce( 'monsterinsights-deactivate' ),
					'update_settings'     => current_user_can( 'monsterinsights_save_settings' ),
					'migrated'            => monsterinsights_get_option( 'gadwp_migrated', 0 ),
					'yearinreview'        => monsterinsights_yearinreview_dates(),
					'reports_url'         => add_query_arg( 'page', 'monsterinsights_reports', admin_url( 'admin.php' ) ),
					'feedback'            => MonsterInsights_Feature_Feedback::get_settings(),
					'addons_pre_check'    => array(
						'ai_insights' => is_plugin_active( 'monsterinsights-ai-insights/monsterinsights-ai-insights.php' ),
					),
				)
			);

			// Load the script with specific translations.
			wp_set_script_translations(
				'monsterinsights-vue-reports',
				monsterinsights_is_pro_version() ? 'ga-premium' : 'google-analytics-for-wordpress',
				MONSTERINSIGHTS_PLUGIN_DIR . 'languages'
			);

			return;
		}

		// ublock notice
		add_action( 'admin_print_footer_scripts', array( $this, 'monsterinsights_settings_ublock_error_js' ), 9999999 );
	}

	/**
	 * Need to identify why this function is using.
	 */
	public function monsterinsights_settings_ublock_error_js() {
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

	/**
	 * Load CSS from manifest.json
	 */
	public static function enqueue_script_specific_css( $js_file_path ) {
		if ( defined( 'MONSTERINSIGHTS_LOCAL_JS_URL' ) ) {
			return;
		}

		$version_path = monsterinsights_is_pro_version() ? 'pro' : 'lite';
		$plugin_path  = plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE );

		if ( ! isset( self::$manifest_data[ $js_file_path ] ) ) {
			return;
		}

		$js_imports    = self::$manifest_data[ $js_file_path ]['imports'];
		$css_file_path = $plugin_path . $version_path . '/assets/vue/';

		// Add JS own CSS file.
		if ( isset( self::$manifest_data[ $js_file_path ]['css'] ) ) {
			self::add_js_own_css_files( self::$manifest_data[ $js_file_path ]['css'], $version_path );
		}

		// Loop through all imported js file of entry file.
		foreach ( $js_imports as $js_filename ) {
			// Check imported file available in manifest.json
			if ( ! isset( self::$manifest_data[ $js_filename ] ) ) {
				continue;
			}

			// Check imported js file has it's own css.
			if ( ! isset( self::$manifest_data[ $js_filename ]['css'] ) ) {
				continue;
			}

			$js_file_css = self::$manifest_data[ $js_filename ]['css'];

			// css must be array.
			if ( ! is_array( $js_file_css ) ) {
				continue;
			}

			// Loop to css files of a imported js file.
			foreach ( $js_file_css as $css_hash_name ) {
				if ( file_exists( $css_file_path . $css_hash_name ) ) {
					wp_enqueue_style(
						'monsterinsights-style-' . basename( $css_hash_name ),
						plugins_url( $version_path . '/assets/vue/' . $css_hash_name, MONSTERINSIGHTS_PLUGIN_FILE ),
						array(),
						monsterinsights_get_asset_version()
					);
				}
			}
		}
	}

	/**
	 * Add JS it's own CSS build file.
	 */
	private static function add_js_own_css_files( $css_files, $version_path ) {
		foreach ( $css_files as $css_filename ) {
			wp_enqueue_style(
				'monsterinsights-style-' . basename( $css_filename ),
				plugins_url( $version_path . '/assets/vue/' . $css_filename, MONSTERINSIGHTS_PLUGIN_FILE ),
				array(),
				monsterinsights_get_asset_version()
			);
		}
	}

	/**
	 * Get JS build file URL of a entry file.
	 *
	 * @return string
	 */
	public static function get_js_url( $path ) {
		if ( ! $path ) {
			return;
		}

		if ( defined( 'MONSTERINSIGHTS_LOCAL_JS_URL' ) && MONSTERINSIGHTS_LOCAL_JS_URL ) {
			return MONSTERINSIGHTS_LOCAL_JS_URL . $path;
		}

		// If the file is not available on manifest.
		if ( ! isset( self::$manifest_data[ $path ] ) ) {
			return;
		}

		$js_file      = self::$manifest_data[ $path ]['file'];
		$version_path = monsterinsights_is_pro_version() ? 'pro' : 'lite';

		return plugins_url( $version_path . '/assets/vue/' . $js_file, MONSTERINSIGHTS_PLUGIN_FILE );
	}

	/**
	 * Fetch manifest.json data and store it to array for future use.
	 *
	 * @return void
	 */
	private function get_manifest_data() {
		$version_path  = monsterinsights_is_pro_version() ? 'pro' : 'lite';
		$plugin_path   = plugin_dir_path( MONSTERINSIGHTS_PLUGIN_FILE );
		$manifest_path = $plugin_path . $version_path . '/assets/vue/manifest.json';

		// Return if manifest.json not exists.
		if ( ! file_exists( $manifest_path ) ) {
			return;
		}

		self::$manifest_data = json_decode( file_get_contents( $manifest_path ), true );
	}
}

new MonsterInsights_Admin_Assets();
