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
		'reports'  => array(
			'overview'  => array(
				'toppages'    => true,
				'newvsreturn' => true,
				'devices'     => true,
			),
			'publisher' => array(
				'landingpages'   => false,
				'exitpages'      => false,
				'outboundlinks'  => false,
				'affiliatelinks' => false,
				'downloadlinks'  => false,
			),
			'ecommerce' => array(
				'infobox'     => false, // E-commerce Overview.
				'products'    => false, // Top Products.
				'conversions' => false, // Top Products.
				'addremove'   => false, // Total Add/Remove.
				'days'        => false, // Time to purchase.
				'sessions'    => false, // Sessions to purchase.
			),
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
		$show_widget         = is_multisite() ? apply_filters( 'monsterinsights_show_dashboard_widget', true ) : true;
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
			monsterinsights_settings_error_page( 'monsterinsights-dashboard-widget' );
		}

	}

	/**
	 * Message to display when the plugin is not authenticated.
	 */
	public function widget_content_no_auth() {

		$url = is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_settings' ) : admin_url( 'admin.php?page=monsterinsights_settings' );
		?>
		<div class="mi-dw-not-authed">
			<h2><?php esc_html_e( 'Reports are not available', 'google-analytics-for-wordpress' ); ?></h2>
			<p><?php esc_html_e( 'Please connect MonsterInsights to Google Analytics to see reports.', 'google-analytics-for-wordpress' ); ?></p>
			<a href="<?php echo esc_url( $url ); ?>" class="mi-dw-btn-large"><?php esc_html_e( 'Configure MonsterInsights', 'google-analytics-for-wordpress' ); ?></a>
		</div>
		<?php
	}


	/**
	 * Load widget-specific scripts.
	 */
	public function widget_scripts() {
		$version_path = 'lite';
		$rtl          = is_rtl() ? '.rtl' : '';

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'dashboard' === $screen->id ) {
			global $wp_version;
			if ( ! defined( 'MONSTERINSIGHTS_LOCAL_WIDGET_JS_URL' ) ) {
				wp_enqueue_style( 'monsterinsights-vue-style-vendors', plugins_url( $version_path . '/assets/vue/css/chunk-vendors' . $rtl . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
				wp_enqueue_style( 'monsterinsights-vue-widget-style', plugins_url( $version_path . '/assets/vue/css/widget' . $rtl . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
				wp_enqueue_script( 'monsterinsights-vue-vendors', plugins_url( $version_path . '/assets/vue/js/chunk-vendors.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );
				wp_enqueue_script( 'monsterinsights-vue-common', plugins_url( $version_path . '/assets/vue/js/chunk-common.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );
			}
			$widget_js_url = defined( 'MONSTERINSIGHTS_LOCAL_WIDGET_JS_URL' ) && MONSTERINSIGHTS_LOCAL_WIDGET_JS_URL ? MONSTERINSIGHTS_LOCAL_WIDGET_JS_URL : plugins_url( $version_path . '/assets/vue/js/widget.js', MONSTERINSIGHTS_PLUGIN_FILE );
			wp_register_script( 'monsterinsights-vue-widget', $widget_js_url, array(), monsterinsights_get_asset_version(), true );
			wp_enqueue_script( 'monsterinsights-vue-widget' );

			$plugins           = get_plugins();
			$wp_forms_url      = false;
			$wpforms_installed = false;
			if ( current_user_can( 'install_plugins' ) ) {
				$wpforms_key = 'wpforms-lite/wpforms.php';
				if ( array_key_exists( $wpforms_key, $plugins ) ) {
					$wp_forms_url      = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $wpforms_key ), 'activate-plugin_' . $wpforms_key );
					$wpforms_installed = true;
				} else {
					$wp_forms_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=wpforms-lite' ), 'install-plugin_wpforms-lite' );
				}
			}
			wp_localize_script(
				'monsterinsights-vue-widget',
				'monsterinsights',
				array(
					'ajax'              => admin_url( 'admin-ajax.php' ),
					'nonce'             => wp_create_nonce( 'mi-admin-nonce' ),
					'network'           => is_network_admin(),
					'translations'      => wp_get_jed_locale_data( monsterinsights_is_pro_version() ? 'ga-premium' : 'google-analytics-for-wordpress' ),
					'assets'            => plugins_url( $version_path . '/assets/vue', MONSTERINSIGHTS_PLUGIN_FILE ),
					'shareasale_id'     => monsterinsights_get_shareasale_id(),
					'shareasale_url'    => monsterinsights_get_shareasale_url( monsterinsights_get_shareasale_id(), '' ),
					'addons_url'        => is_multisite() ? network_admin_url( 'admin.php?page=monsterinsights_network#/addons' ) : admin_url( 'admin.php?page=monsterinsights_settings#/addons' ),
					'widget_state'      => $this->get_options(),
					'wpforms_enabled'   => function_exists( 'wpforms' ),
					'wpforms_installed' => $wpforms_installed,
					'wpforms_url'       => $wp_forms_url,
					// Used to add notices for future deprecations.
					'versions'          => array(
						'php_version'          => phpversion(),
						'php_version_below_54' => version_compare( phpversion(), '5.4', '<' ),
						'php_version_below_56' => version_compare( phpversion(), '5.6', '<' ),
						'php_update_link'      => monsterinsights_get_url( 'settings-notice', 'settings-page', 'https://www.monsterinsights.com/docs/update-php/' ),
						'wp_version'           => $wp_version,
						'wp_version_below_46'  => version_compare( $wp_version, '4.6', '<' ),
						'wp_version_below_49'  => version_compare( $wp_version, '4.9', '<' ),
						'wp_update_link'       => monsterinsights_get_url( 'settings-notice', 'settings-page', 'https://www.monsterinsights.com/docs/update-wordpress/' ),
					),
					'plugin_version'    => MONSTERINSIGHTS_VERSION,
				)
			);
		}
	}

	/**
	 * Store the widget state in the db using an Ajax call.
	 */
	public function save_widget_state() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$default = self::$default_options;

		$reports = $default['reports'];
		if ( isset( $_POST['reports'] ) ) {
			$reports = json_decode( sanitize_text_field( wp_unslash( $_POST['reports'] ) ), true );
			array_walk( $reports, 'boolval' );
		}

		$options = array(
			'width'    => ! empty( $_POST['width'] ) ? sanitize_text_field( wp_unslash( $_POST['width'] ) ) : $default['width'],
			'interval' => ! empty( $_POST['interval'] ) ? absint( wp_unslash( $_POST['interval'] ) ) : $default['interval'],
			'reports'  => $reports,
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

		return apply_filters( 'monsterinsights_dashboard_widget_options', $this->options );

	}

	/**
	 * Recursive wp_parse_args.
	 *
	 * @param string|array|object $a Value to merge with $b.
	 * @param array               $b The array with the default values.
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
}
