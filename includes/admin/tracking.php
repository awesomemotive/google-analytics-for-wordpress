<?php
/**
 * Tracking functions for reporting plugin usage to the MonsterInsights site for users that have opted in
 *
 * @package     MonsterInsights
 * @subpackage  Admin
 * @copyright   Copyright (c) 2017, Chris Christoff
 * @since       6.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Usage tracking
 *
 * @access public
 * @since  6.0.0
 * @return void
 */
class MonsterInsights_Tracking {

	/**
	 * The data to send to the EDD site
	 *
	 * @access private
	 */
	private $data;

	/**
	 * Get things going
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'schedule_send' ) );
		add_action( 'monsterinsights_settings_save_general_end', array( $this, 'check_for_settings_optin' ) );
		add_action( 'admin_head', array( $this, 'check_for_optin' ) );
		add_action( 'admin_head', array( $this, 'check_for_optout' ) );
		add_action( 'admin_notices', array( $this, 'monsterinsights_admin_notice' ) );
		add_filter( 'cron_schedules', array( $this, 'add_schedules' ) );
		add_action( 'monsterinsights_daily_cron', array( $this, 'send_checkin' ) );
	}

	/**
	 * Check if the user has opted into tracking
	 *
	 * @access private
	 * @return bool
	 */
	private function tracking_allowed() {
		return (bool) monsterinsights_get_option( 'anonymous_data', false );
	}

	/**
	 * Setup the data that is going to be tracked
	 *
	 * @access private
	 * @return void
	 */
	private function setup_data() {
		$data = array();

		// Retrieve current theme info
		$theme_data    = wp_get_theme();
		$theme         = $theme_data->Name . ' ' . $theme_data->Version;
		$tracking_mode = monsterinsights_get_option( 'tracking_mode', 'analytics' );
		$events_mode   = monsterinsights_get_option( 'events_mode', 'none' );

		if ( $tracking_mode === false ) {
			$tracking_mode = 'analytics';
		}

		if ( $events_mode === false ) {
			$events_mode = 'none';
		}

		$data['php_version'] = phpversion();
		$data['mi_version']  = MONSTERINSIGHTS_VERSION;
		$data['wp_version']  = get_bloginfo( 'version' );
		$data['server']      = isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : '';
		$data['over_time']   = get_option( 'monsterinsights_over_time', array() );
		$data['multisite']   = is_multisite();
		$data['url']         = home_url();
		$data['theme']       = $theme;
		$data['email']       = get_bloginfo( 'admin_email' );
		$data['key']         = monsterinsights_get_license();
		$data['sas']         = monsterinsights_get_shareasale_id();
		$data['setttings']     = monsterinsights_get_options();
		$data['tracking_mode'] = $tracking_mode;
		$data['events_mode']   = $events_mode;

		// Retrieve current plugin information
		if( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$plugins        = array_keys( get_plugins() );
		$active_plugins = get_option( 'active_plugins', array() );

		foreach ( $plugins as $key => $plugin ) {
			if ( in_array( $plugin, $active_plugins ) ) {
				// Remove active plugins from list so we can show active and inactive separately
				unset( $plugins[ $key ] );
			}
		}

		$data['active_plugins']   = $active_plugins;
		$data['inactive_plugins'] = $plugins;
		$data['locale']           = get_locale();

		$this->data = $data;
	}

	/**
	 * Send the data to the EDD server
	 *
	 * @access private
	 * @return void
	 */
	public function send_checkin( $override = false, $ignore_last_checkin = false ) {

		$home_url = trailingslashit( home_url() );
		if ( $home_url === 'https://www.monsterinsights.com/'     || 
			 $home_url === 'https://beta.monsterinsights.com/'    ||
			 $home_url === 'https://staging.monsterinsights.com/' ||
			 $home_url === 'https://testing.monsterinsights.com/'
		) {
			return false;
		}

		if( ! $this->tracking_allowed() && ! $override ) {
			return false;
		}

		// Send a maximum of once per week
		$last_send = $this->get_last_send();
		if( is_numeric( $last_send ) && $last_send > strtotime( '-1 week' ) && ! $ignore_last_checkin ) {
			return false;
		}

		$this->setup_data();

		$request = wp_remote_post( 'https://www.monsterinsights.com/?edd_action=checkin', array(
			'method'      => 'POST',
			'timeout'     => 20,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'body'        => $this->data,
			'user-agent'  => 'MI/' . MONSTERINSIGHTS_VERSION . '; ' . get_bloginfo( 'url' )
		) );

		if( is_wp_error( $request ) ) {
			return $request;
		}

		update_option( 'mi_tracking_last_send', time() );

		return true;

	}

	/**
	 * Check for a new opt-in on settings save
	 *
	 * This runs during the sanitation of General settings, thus the return
	 *
	 * @access public
	 * @return array
	 */
	public function check_for_settings_optin() {
		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		// Send an intial check in on settings save
		$anonymous_data = isset( $_POST['anonymous_data'] ) ? 1 : 0;
		if ( $anonymous_data ) {
			$this->send_checkin( true );
		}

	}

	/**
	 * Check for a new opt-in via the admin notice
	 *
	 * @access public
	 * @return void
	 */
	public function check_for_optin() {
		if ( ! ( ! empty( $_REQUEST['mi_action'] ) && 'opt_into_tracking' === $_REQUEST['mi_action'] ) ) {
			return;
		}

		if ( monsterinsights_get_option( 'anonymous_data', false ) ) {
			return;
		}

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}
		
		monsterinsights_update_option( 'anonymous_data', 1 );
		$this->send_checkin( true );
		update_option( 'monsterinsights_tracking_notice', 1 );
	}

	/**
	 * Check for a new opt-in via the admin notice
	 *
	 * @access public
	 * @return void
	 */
	public function check_for_optout() {
		if ( ! ( ! empty( $_REQUEST['mi_action'] ) && 'opt_out_of_tracking' === $_REQUEST['mi_action'] ) ) {
			return;
		}

		if ( monsterinsights_get_option( 'anonymous_data', false ) ) {
			return;
		}

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		monsterinsights_update_option( 'anonymous_data', 0 );
		update_option( 'monsterinsights_tracking_notice', 1 );
	}

	/**
	 * Get the last time a checkin was sent
	 *
	 * @access private
	 * @return false|string
	 */
	private function get_last_send() {
		return get_option( 'monsterinsights_tracking_last_send' );
	}

	/**
	 * Schedule a weekly checkin
	 *
	 * @access public
	 * @return void
	 */
	public function schedule_send() {
		// We send once a day (while tracking is allowed) to check in, which can be used to determine active sites
		if ( ! wp_next_scheduled( 'monsterinsights_daily_cron' ) ) {
			// Set the next event of fetching data
			wp_schedule_event( strtotime( date( 'Y-m-d', strtotime( 'tomorrow' ) ) . ' 00:01:00 ' ), 'daily', 'monsterinsights_daily_cron' );
		}
	}

	/**
	 * Display the admin notice to users that have not opted-in or out
	 *
	 * @access public
	 * @return void
	 */
	public function monsterinsights_admin_notice() {

		$hide_notice = get_option( 'monsterinsights_tracking_notice' );

		if ( $hide_notice ) {
			return;
		}

		if ( monsterinsights_get_option( 'anonymous_data', false ) ) {
			return;
		}

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if (
			stristr( network_site_url( '/' ), 'dev'       ) !== false ||
			stristr( network_site_url( '/' ), 'localhost' ) !== false ||
			stristr( network_site_url( '/' ), ':8888'     ) !== false // This is common with MAMP on OS X
		) {
			update_option( 'monsterinsights_tracking_notice', '1' );
		} else {
			$optin_url  = add_query_arg( 'mi_action', 'opt_into_tracking' );
			$optout_url = add_query_arg( 'mi_action', 'opt_out_of_tracking' );
			echo '<div class="updated"><p>';
			echo esc_html__( 'Allow MonsterInsights to track plugin usage? Opt-in to tracking and our newsletter to stay informed of the latest changes to MonsterInsights and help us ensure compatibility.', 'google-analytics-for-wordpress' );
			echo '&nbsp;<a href="' . esc_url( $optin_url ) . '" class="button-secondary">' . __( 'Allow', 'google-analytics-for-wordpress' ) . '</a>';
			echo '&nbsp;<a href="' . esc_url( $optout_url ) . '" class="button-secondary">' . __( 'Do not allow', 'google-analytics-for-wordpress' ) . '</a>';
			echo '</p></div>';
		}
	}

	/**
	 * Registers new cron schedules
	 *
	 * @since 6.0.0
	 *
	 * @param array $schedules
	 * @return array
	 */
	public function add_schedules( $schedules = array() ) {
		// Adds once weekly to the existing schedules.
		$schedules['weekly'] = array(
			'interval' => 604800,
			'display'  => __( 'Once Weekly', 'google-analytics-for-wordpress' )
		);
		return $schedules;
	}

}
new MonsterInsights_Tracking();