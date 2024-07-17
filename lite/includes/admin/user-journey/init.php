<?php
/**
 * Initialize Admin - User Journey.
 *
 * @since 8.5.0
 *
 * @package MonsterInsights
 * @subpackage MonsterInsights_User_Journey
 */

/**
 * Admin functions and init functionality.
 *
 * @since 8.5.0
 */
final class MonsterInsights_Lite_User_Journey_Admin {

	/**
	 * Screens on which we want to load the assets.
	 *
	 * @since 8.5.0
	 *
	 * @var array
	 */
	public $screens = array(
		'shop_order',
		'llms_order',
		'give_forms_page_give-payment-history',
		'download_page_edd-payment-history',
		'restrict_page_rcp-payments',
		'memberpress_page_memberpress-trans',
	);

	/**
	 * eCommerce Providers.
	 *
	 * @since 8.7.0
	 *
	 * @var array
	 */
	public $providers = array(
		'woocommerce',
		'lifterlms',
		'givewp',
		'edd',
		'restrict-content-pro',
		'memberpress',
	);

	/**
	 * Holds singleton instance
	 *
	 * @since 8.5.0
	 *
	 * @var MonsterInsights_User_Journey_Admin
	 */
	private static $instance;

	/**
	 * Return Singleton instance
	 *
	 * @return MonsterInsights_User_Journey_Admin
	 * @since 8.5.0
	 *
	 * @since 1.0.0
	 *
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @since 8.5.0
	 */
	public function __construct() {
		add_action( 'admin_head', array( $this, 'add_admin_scripts' ) );

		// Load eCommerce Providers.
		$this->load_files();
	}

	/**
	 * Add required admin scripts.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function add_admin_scripts() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		$current_screen = get_current_screen();

		if ( ! is_object( $current_screen ) ) {
			return;
		}

		if ( ! in_array( $current_screen->id, $this->screens, true ) ) {
			return;
		}

		$url = MONSTERINSIGHTS_PLUGIN_URL . 'lite/includes/admin/user-journey/assets/css/user-journey.css';

		wp_enqueue_style( 'monsterinsights-lite-user-journey-admin', esc_url( $url ), MONSTERINSIGHTS_VERSION );
	}

	/**
	 * Require eCommerce Providers PHP Files.
	 *
	 * @return void
	 * @since 8.7.0
	 *
	 */
	private function load_files() {
		if ( ! $this->can_view_user_journey() ) {
			return;
		}

		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/user-journey/providers/class-abstract-lite-metabox.php';

		if ( ! empty( $this->providers ) ) {
			$providers = $this->providers;

			foreach ( $providers as $provider ) {
				$file = MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/user-journey/providers/' . $provider . '.php';
				if ( file_exists( $file ) ) {
					require_once wp_normalize_path( $file );
				}
			}
		}
	}

	/**
	 * Hide User Journey reports/metabox if reports are disabled
	 * in the settings and also if current user role does not
	 * have permission to view reports.
	 *
	 * @return bool
	 * @since 8.7.0
	 *
	 */
	public static function can_view_user_journey() {
		if ( monsterinsights_get_option( 'dashboards_disabled' ) ) {
			if ( 'dashboard_widget' === monsterinsights_get_option( 'dashboards_disabled' ) || 'disabled' === monsterinsights_get_option( 'dashboards_disabled' ) ) {
				return false;
			}
		}

		$view_reports       = monsterinsights_get_option( 'view_reports' );
		$current_user_roles = wp_get_current_user()->roles;
		$in_roles           = array();

		if ( is_array( $view_reports ) && is_array( $current_user_roles ) ) {
			$in_roles = array_intersect( $current_user_roles, $view_reports );

			if ( empty( $in_roles ) ) {
				return false;
			}
		}

		return true;
	}
}

// Initialize the class
MonsterInsights_Lite_User_Journey_Admin::get_instance();
