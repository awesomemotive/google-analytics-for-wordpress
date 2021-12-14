<?php
/**
 * Tracking functions for reporting plugin usage to the MonsterInsights site for users that have opted in
 *
 * @package     MonsterInsights
 * @subpackage  Admin
 * @copyright   Copyright (c) 2018, Chris Christoff
 * @since       7.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Usage tracking
 *
 * @access public
 * @since  7.0.0
 * @return void
 */
class MonsterInsights_Tracking {

	public function __construct() {
		add_action( 'init', array( $this, 'schedule_send' ) );
		add_action( 'monsterinsights_settings_save_general_end', array( $this, 'check_for_settings_optin' ) );
		add_action( 'admin_head', array( $this, 'check_for_optin' ) );
		add_action( 'admin_head', array( $this, 'check_for_optout' ) );
		add_filter( 'cron_schedules', array( $this, 'add_schedules' ) );
		add_action( 'monsterinsights_usage_tracking_cron', array( $this, 'send_checkin' ) );
	}

	private function get_data() {
		$data = array();

		// Retrieve current theme info
		$theme_data    = wp_get_theme();
		$tracking_mode = monsterinsights_get_option( 'tracking_mode', 'gtag' );
		$events_mode   = monsterinsights_get_option( 'events_mode', 'none' );
		$update_mode   = monsterinsights_get_option( 'automatic_updates', false );

		if ( $tracking_mode === false ) {
			$tracking_mode = 'gtag';
		}
		if ( $events_mode === false ) {
			$events_mode = 'none';
		}

		if ( $update_mode === false ) {
			$update_mode = 'none';
		}

		$count_b = 1;
		if ( is_multisite() ) {
			if ( function_exists( 'get_blog_count' ) ) {
				$count_b = get_blog_count();
			} else {
				$count_b = 'Not Set';
			}
		}

		$usesauth = 'No';
		$local    = MonsterInsights()->auth->is_authed();
		$network  = MonsterInsights()->auth->is_network_authed();

		if ( $local && $network ) {
			$usesauth = 'Both';
		} else if ( $local ) {
			$usesauth = 'Local';
		} else if ( $network ) {
			$usesauth = 'Network';
		}

		$data['php_version']   = phpversion();
		$data['mi_version']    = MONSTERINSIGHTS_VERSION;
		$data['wp_version']    = get_bloginfo( 'version' );
		$data['server']        = isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : '';
		$data['over_time']     = get_option( 'monsterinsights_over_time', array() );
		$data['multisite']     = is_multisite();
		$data['url']           = home_url();
		$data['themename']     = $theme_data->Name;
		$data['themeversion']  = $theme_data->Version;
		$data['email']         = get_bloginfo( 'admin_email' );
		$data['key']           = monsterinsights_get_license_key();
		$data['sas']           = monsterinsights_get_shareasale_id();
		$data['settings']      = monsterinsights_get_options();
		$data['tracking_mode'] = $tracking_mode;
		$data['events_mode']   = $events_mode;
		$data['autoupdate']    = $update_mode;
		$data['pro']           = (int) monsterinsights_is_pro_version();
		$data['sites']         = $count_b;
		$data['usagetracking'] = get_option( 'monsterinsights_usage_tracking_config', false );
		$data['usercount']     = function_exists( 'get_user_count' ) ? get_user_count() : 'Not Set';
		$data['usesauth']      = $usesauth;
		$data['timezoneoffset']= date('P');

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

		return $data;
	}

	public function send_checkin( $override = false, $ignore_last_checkin = false ) {

		$home_url = trailingslashit( home_url() );
		if ( strpos( $home_url, 'monsterinsights.com' ) !== false ) {
			return false;
		}

		if( ! $this->tracking_allowed() && ! $override ) {
			return false;
		}

		// Send a maximum of once per week
		$last_send = get_option( 'monsterinsights_usage_tracking_last_checkin' );
		if ( is_numeric( $last_send ) && $last_send > strtotime( '-1 week' ) && ! $ignore_last_checkin ) {
			return false;
		}

		$request = wp_remote_post( 'https://miusage.com/v1/checkin/', array(
			'method'      => 'POST',
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => false,
			'body'        => $this->get_data(),
			'user-agent'  => 'MI/' . MONSTERINSIGHTS_VERSION . '; ' . get_bloginfo( 'url' )
		) );

		// If we have completed successfully, recheck in 1 week
		update_option( 'monsterinsights_usage_tracking_last_checkin', time() );
		return true;
	}

	private function tracking_allowed() {
		return (bool) monsterinsights_get_option( 'anonymous_data', false ) || monsterinsights_is_pro_version();
	}

	public function schedule_send() {
		if ( ! wp_next_scheduled( 'monsterinsights_usage_tracking_cron' ) ) {
			$tracking             = array();
			$tracking['day']      = rand( 0, 6  );
			$tracking['hour']     = rand( 0, 23 );
			$tracking['minute']   = rand( 0, 59 );
			$tracking['second']   = rand( 0, 59 );
			$tracking['offset']   = ( $tracking['day']    * DAY_IN_SECONDS    ) +
									( $tracking['hour']   * HOUR_IN_SECONDS   ) +
									( $tracking['minute'] * MINUTE_IN_SECONDS ) +
									 $tracking['second'];
			$tracking['initsend'] = strtotime("next sunday") + $tracking['offset'];

			wp_schedule_event( $tracking['initsend'], 'weekly', 'monsterinsights_usage_tracking_cron' );
			update_option( 'monsterinsights_usage_tracking_config', $tracking );
		}
	}

	public function check_for_settings_optin() {
		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( monsterinsights_is_pro_version() ) {
			return;
		}

		// Send an intial check in on settings save
		$anonymous_data = isset( $_POST['anonymous_data'] ) ? 1 : 0;
		if ( $anonymous_data ) {
			$this->send_checkin( true, true );
		}

	}

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

		if ( monsterinsights_is_pro_version() ) {
			return;
		}

		monsterinsights_update_option( 'anonymous_data', 1 );
		$this->send_checkin( true, true );
		update_option( 'monsterinsights_tracking_notice', 1 );
	}

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

		if ( monsterinsights_is_pro_version() ) {
			return;
		}

		monsterinsights_update_option( 'anonymous_data', 0 );
		update_option( 'monsterinsights_tracking_notice', 1 );
	}

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
