<?php
/**
 * Manage the MonsterInsights Dashboard Widget
 *
 * @since 7.1
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
class MonsterInsights_Dashboard_Widget {

	const WIDGET_KEY = 'monsterinsights_reports_widget';
	/**
	 * The default options for the widget.
	 *
	 * @var array $default_options
	 */
	public static $default_options = array(
		'width'    => 'regular',
		'interval' => '30',
		'compact'  => false,
		'reports'  => array(
			'overview'    => array(
				'toppages'    => true,
				'newvsreturn' => true,
				'devices'     => true,
			),
			'publisher'   => array(
				'landingpages'   => false,
				'exitpages'      => false,
				'outboundlinks'  => false,
				'affiliatelinks' => false,
				'downloadlinks'  => false,
			),
			'ecommerce'   => array(
				'infobox'            => false, // E-commerce Overview.
				'products'           => false, // Top Products.
				'conversions'        => false, // Top Products.
				'addremove'          => false, // Total Add/Remove.
				'days'               => false, // Time to purchase.
				'sessions'           => false, // Sessions to purchase.
				'newcustomers'       => false,
				'abandonedcheckouts' => false,
			),
			'notice30day' => false,
		),
	);
	/**
	 * The widget options.
	 *
	 * @var array $options
	 */
	public $options;

	/**
	 * MonsterInsights_Dashboard_Widget constructor.
	 */
	public function __construct() {
		// Allow dashboard widget to be hidden on multisite installs
		$show_widget = is_multisite() ? apply_filters( 'monsterinsights_show_dashboard_widget', true ) : true;
		if ( ! $show_widget ) {
			return false;
		}

		// Check if reports should be visible.
		$dashboards_disabled = monsterinsights_get_option( 'dashboards_disabled', false );
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) || 'disabled' === $dashboards_disabled ) {
			return false;
		}

		add_action( 'wp_dashboard_setup', array( $this, 'register_dashboard_widget' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'widget_scripts' ) );

		add_action( 'wp_ajax_monsterinsights_save_widget_state', array( $this, 'save_widget_state' ) );

		// Reminder notice.
//		add_action( 'admin_footer', array( $this, 'load_notice' ) );

		add_action( 'wp_ajax_monsterinsights_mark_notice_closed', array( $this, 'mark_notice_closed' ) );
	}

	/**
	 * Register the dashboard widget.
	 */
	public function register_dashboard_widget() {
		global $wp_meta_boxes;

		wp_add_dashboard_widget(
			self::WIDGET_KEY,
			esc_html__( 'MonsterInsights', 'google-analytics-for-wordpress' ),
			array( $this, 'dashboard_widget_content' )
		);

		// Attept to place the widget at the top.
		$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
		$widget_instance  = array( self::WIDGET_KEY => $normal_dashboard[ self::WIDGET_KEY ] );
		unset( $normal_dashboard[ self::WIDGET_KEY ] );
		$sorted_dashboard                             = array_merge( $widget_instance, $normal_dashboard );
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	}

	/**
	 * Load the widget content.
	 */
	public function dashboard_widget_content() {
		$is_authed = ( MonsterInsights()->auth->is_authed() || MonsterInsights()->auth->is_network_authed() );

		if ( ! $is_authed ) {
			$this->widget_content_no_auth();
		} else {
			monsterinsights_settings_error_page( 'monsterinsights-dashboard-widget', '', '0' );
			monsterinsights_settings_inline_js();
		}

	}

	/**
	 * Message to display when the plugin is not authenticated.
	 */
	public function widget_content_no_auth() {

		$url      = is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights-onboarding' ) : admin_url( 'admin.php?page=monsterinsights-onboarding' );
		$migrated = monsterinsights_get_option( 'gadwp_migrated', 0 );
		if ( $migrated > 0 ) {
			$url = admin_url( 'admin.php?page=monsterinsights-getting-started&monsterinsights-migration=1' );
		}
		?>
		<div class="mi-dw-not-authed">
			<?php
			// Translators: Wizrd Link tag starts with url and Wizard link tag ends.
			$message = sprintf(
				esc_html__( 'Your website analytics dashboard is not currently configured. Please use our %1$ssetup wizard%2$s to get started.', 'google-analytics-for-wordpress' ),
				'<a href="' . esc_url( $url ) . '">',
				'</a>'
			);
			?>
			<h2><?php echo $message; // phpcs:ignore ?></h2>
			<?php if ( current_user_can( 'monsterinsights_save_settings' ) ) { ?>
				<p><?php esc_html_e( 'To see your website stats, please connect MonsterInsights to Google Analytics.', 'google-analytics-for-wordpress' ); ?></p>
				<a href="<?php echo esc_url( $url ); ?>"
				   class="mi-dw-btn-large"><?php esc_html_e( 'Setup Website Analytics', 'google-analytics-for-wordpress' ); ?></a>
			<?php } else { ?>
				<p><?php esc_html_e( 'To see your website stats, please ask your site administrator to connect MonsterInsights to Google Analytics.', 'google-analytics-for-wordpress' ); ?></p>
			<?php } ?>
		</div>
		<?php
	}


	/**
	 * Load widget-specific scripts.
	 */
	public function widget_scripts() {
		$version_path = 'lite';
		$screen       = get_current_screen();

		if ( isset( $screen->id ) && 'dashboard' === $screen->id ) {
			if ( ! defined( 'MONSTERINSIGHTS_LOCAL_JS_URL' ) ) {
				MonsterInsights_Admin_Assets::enqueue_script_specific_css( 'src/modules/widget/widget.js' );
			}
			$widget_js_url = MonsterInsights_Admin_Assets::get_js_url( 'src/modules/widget/widget.js' );
			wp_register_script( 'monsterinsights-vue-widget', $widget_js_url, array(), monsterinsights_get_asset_version(), true );
			wp_enqueue_script( 'monsterinsights-vue-widget' );

			$plugins                = get_plugins();
			$wp_forms_url           = false;
			$wpforms_installed      = false;
			$userfeedback_url       = false;
			$userfeedback_installed = false;
			if ( monsterinsights_can_install_plugins() ) {
				$wpforms_key = 'wpforms-lite/wpforms.php';
				if ( array_key_exists( $wpforms_key, $plugins ) ) {
					$wp_forms_url      = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $wpforms_key ), 'activate-plugin_' . $wpforms_key );
					$wpforms_installed = true;
				} else {
					$wp_forms_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=wpforms-lite' ), 'install-plugin_wpforms-lite' );
				}

				$userfeedback_keys     = array ( 'userfeedback-lite/userfeedback.php' => 1, 'userfeedback/userfeedback.php' => 2 );
				$userfeedback_versions = array_intersect_key($userfeedback_keys, $plugins);

				if ( ! empty( $userfeedback_versions ) ) {
					$uf_plugin_key          = array_keys( $userfeedback_versions )[0];
					$userfeedback_url       = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $uf_plugin_key ), 'activate-plugin_' . $uf_plugin_key );
					$userfeedback_installed = true;
				} else {
					$userfeedback_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=userfeedback-lite' ), 'install-plugin_userfeedback-lite' );
				}
			}

			// We do not have a current auth.
			$auth      = MonsterInsights()->auth;
			$is_authed = ( $auth->is_authed() || $auth->is_network_authed() );
			wp_localize_script(
				'monsterinsights-vue-widget',
				'monsterinsights',
				array(
					'ajax'                   => admin_url( 'admin-ajax.php' ),
					'nonce'                  => wp_create_nonce( 'mi-admin-nonce' ),
					'network'                => is_network_admin(),
					'translations'           => wp_get_jed_locale_data( monsterinsights_is_pro_version() ? 'ga-premium' : 'google-analytics-for-wordpress' ),
					'assets'                 => plugins_url( $version_path . '/assets/vue', MONSTERINSIGHTS_PLUGIN_FILE ),
					'shareasale_id'          => monsterinsights_get_shareasale_id(),
					'shareasale_url'         => monsterinsights_get_shareasale_url( monsterinsights_get_shareasale_id(), '' ),
					'addons_url'             => is_multisite() ? network_admin_url( 'admin.php?page=monsterinsights_network#/addons' ) : admin_url( 'admin.php?page=monsterinsights_settings#/addons' ),
					'widget_state'           => $this->get_options(),
					'wpforms_enabled'        => function_exists( 'wpforms' ),
					'wpforms_installed'      => $wpforms_installed,
					'wpforms_url'            => $wp_forms_url,
					'userfeedback_enabled'   => class_exists( 'UserFeedback_Base' ),
					'userfeedback_installed' => $userfeedback_installed,
					'userfeedback_url'       => $userfeedback_url,
					'authed'                 => $is_authed,
					// Used to add notices for future deprecations.
					'versions'               => monsterinsights_get_php_wp_version_warning_data(),
					'plugin_version'         => MONSTERINSIGHTS_VERSION,
					'is_admin'               => true,
					'reports_url'            => add_query_arg( 'page', 'monsterinsights_reports', admin_url( 'admin.php' ) ),
					'getting_started_url'    => is_multisite() ? network_admin_url( 'admin.php?page=monsterinsights_network#/about/getting-started' ) : admin_url( 'admin.php?page=monsterinsights_settings#/about/getting-started' ),
					'wizard_url'             => admin_url( 'index.php?page=monsterinsights-onboarding' ),
				)
			);

			$this->remove_conflicting_asset_files();
		}
	}

	/**
	 * Remove assets added by other plugins which conflict.
	 */
	public function remove_conflicting_asset_files() {
		$scripts = array(
			'jetpack-onboarding-vendor', // Jetpack Onboarding Bluehost.
		);

		if ( ! empty( $scripts ) ) {
			foreach ( $scripts as $script ) {
				wp_dequeue_script( $script ); // Remove JS file.
				wp_deregister_script( $script );
			}
		}
	}

	/**
	 * Store the widget state in the db using an Ajax call.
	 */
	public function save_widget_state() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$default         = self::$default_options;
		$current_options = $this->get_options();

		$reports = $default['reports'];
		if ( isset( $_POST['reports'] ) ) {
			$reports = json_decode( sanitize_text_field( wp_unslash( $_POST['reports'] ) ), true );
		}

		$options = array(
			'width'       => ! empty( $_POST['width'] ) ? sanitize_text_field( wp_unslash( $_POST['width'] ) ) : $default['width'],
			'interval'    => ! empty( $_POST['interval'] ) ? absint( wp_unslash( $_POST['interval'] ) ) : $default['interval'],
			'compact'     => ! empty( $_POST['compact'] ) ? 'true' === sanitize_text_field( wp_unslash( $_POST['compact'] ) ) : $default['compact'],
			'reports'     => $reports,
			'notice30day' => $current_options['notice30day'],
		);

		array_walk( $options, 'sanitize_text_field' );
		update_user_meta( get_current_user_id(), 'monsterinsights_user_preferences', $options );

		wp_send_json_success();

	}

	/**
	 * Load & store the dashboard widget settings.
	 *
	 * @return array
	 */
	public function get_options() {
		if ( ! isset( $this->options ) ) {
			$this->options = self::wp_parse_args_recursive( get_user_meta( get_current_user_id(), 'monsterinsights_user_preferences', true ), self::$default_options );
		}

        // Set interval fixed to last30days on lite plugin.
		$this->options['interval'] = 'last30days';

		return apply_filters( 'monsterinsights_dashboard_widget_options', $this->options );

	}

	/**
	 * Recursive wp_parse_args.
	 *
	 * @param string|array|object $a Value to merge with $b.
	 * @param array $b The array with the default values.
	 *
	 * @return array
	 */
	public static function wp_parse_args_recursive( $a, $b ) {
		$a      = (array) $a;
		$b      = (array) $b;
		$result = $b;
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $result[ $k ] ) ) {
				$result[ $k ] = self::wp_parse_args_recursive( $v, $result[ $k ] );
			} else {
				$result[ $k ] = $v;
			}
		}

		return $result;
	}

	/**
	 * Reminder notice markup.
	 */
	public function load_notice() {

		$screen = get_current_screen();
		$tracking_id     = monsterinsights_get_v4_id();
		if ( isset( $screen->id ) && 'dashboard' === $screen->id && ! empty( $tracking_id ) ) {
			?>
			<div id="monsterinsights-reminder-notice"></div>
			<?php
		}

	}

	/**
	 * Mark notice as dismissed.
	 */
	public function mark_notice_closed() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );
		$options                = $this->get_options();
		$options['notice30day'] = time();
		update_user_meta( get_current_user_id(), 'monsterinsights_user_preferences', $options );

		wp_send_json_success();
	}
}
