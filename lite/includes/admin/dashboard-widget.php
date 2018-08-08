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
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) || $dashboards_disabled ) {
			return false;
		}

		add_action( 'wp_dashboard_setup', array( $this, 'register_dashboard_widget' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'widget_scripts' ) );
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
			$datepicker_options = array( 30, 7 );
			$url                = is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_reports' ) : admin_url( 'admin.php?page=monsterinsights_reports' );
			?>
			<div class="mi-dw-controls">
				<div class="mi-dw-datepicker mi-dw-btn-group" data-type="datepicker">
					<button class="mi-dw-btn-group-label">
						<?php
						// Translators: %d is the number of days.
						printf( esc_html__( 'Last %d days', 'google-analytics-for-wordpress' ), 30 );
						?>
					</button>
					<div class="mi-dw-btn-list">
						<?php foreach ( $datepicker_options as $datepicker_option ) { ?>
							<button class="mi-dw-btn <?php echo 30 === $datepicker_option ? 'selected' : ''; ?>" data-value=" <?php echo esc_attr( $datepicker_option ); ?>">
								<?php
								// Translators: %d is the number of days.
								printf( esc_html__( 'Last %d days', 'google-analytics-for-wordpress' ), esc_attr( $datepicker_option ) );
								?>
							</button>
						<?php } ?>
					</div>
				</div>
				<label class="mi-dw-styled-toggle mi-dw-widget-width-toggle-container" title="<?php esc_attr_e( 'Show in full-width mode', 'google-analytics-for-wordpress' ); ?>">
					<input type="checkbox" class="mi-dw-widget-width-toggle"/>
				</label>
				<div class="mi-dw-dropdown">
					<button class="mi-dw-button-cog mi-dw-dropdown-toggle" data-target="#mi-dw-reports-options" type="button"></button>
					<ul class="mi-dw-reports-options" id="mi-dw-reports-options"></ul>
				</div>
				<img class="mi-dw-mascot" src="<?php echo esc_url( plugins_url( 'assets/css/images/mascot.png', MONSTERINSIGHTS_PLUGIN_FILE ) ); ?>" srcset="<?php echo esc_url( plugins_url( 'assets/css/images/mascot@2x.png', MONSTERINSIGHTS_PLUGIN_FILE ) ); ?> 2x"/>
			</div>
			<div class="mi-dw-lite">
				<div class="mi-dw-lite-content">
					<h2><?php esc_html_e( 'View All Analytics on the WordPress Dashboard', 'google-analytics-for-wordpress' ); ?></h2>
					<p><?php esc_html_e( 'Once you upgrade to MonsterInsights Pro, you can see your analytics on the Dashboard', 'google-analytics-for-wordpress' ); ?></p>
					<a href="<?php echo esc_url( monsterinsights_get_upgrade_link( 'dashboard-widget', 'lite-cta' ) ); ?>" target="_blank" class="mi-dw-btn-large"><?php esc_html_e( 'Get MonsterInsights Pro', 'google-analytics-for-wordpress' ); ?></a>
					<br/>
					<a href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Go to MonsterInsights Reports', 'google-analytics-for-wordpress' ); ?></a>
				</div>
			</div>
			<?php
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
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'dashboard' === $screen->id ) {
			wp_enqueue_style( 'monsterinsights-dashboard-widget-styles', plugins_url( 'lite/assets/css/admin-dashboard-widget' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_script( 'monsterinsights-dashboard-widget', plugins_url( 'lite/assets/js/admin-dashboard-widget' . $suffix . '.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version(), true );
		}
	}
}
