<?php
/**
 * MonsterInsights Reporting.
 *
 * Handles aggregating data via cron, as well as the admin
 * reporting/dashboard pages.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage GA Reporting
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_Reporting {

	/**
	 * Holds the base object.
	 *
	 * @access public
	 * @since 6.0.0
	 * @var MonsterInsights $base MonsterInsights Base object.
	 */
	public $base;

	/**
	 * Holds the GA client object if using oAuth.
	 *
	 * @access public
	 * @since 6.0.0
	 * @var MonsterInsights_GA_Client $client GA client object.
	 */
	public $client;

	/**
	 * Is the dashboard/reporting pages disabled?
	 *
	 * @access public
	 * @since 6.0.0
	 * @var bool $dashboard_disabled If the dashboard is disabled.
	 */
	public $dashboard_disabled;

	/**
	 * Dashboard report
	 *
	 * @access public
	 * @since 6.0.0
	 * @var string $dashboard_report Report hook name of dashboard report.
	 */
	public $dashboard_report;

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 6.0.0
	 */
	public function __construct( ) {

		// Get object 
		$this->base        		  = MonsterInsights();
		$this->client       	  = $this->base->ga;
		$this->dashboard_disabled = monsterinsights_get_option( 'dashboards_disabled', false );
		$this->dashboard_report   = monsterinsights_get_option( 'dashboard_report', 'overview' );

		if ( isset( $this->client->status ) && $this->client->status === 'valid' ) {
			// Cron actions
				// Add cron if its not there
				add_action( 'wp', array( $this, 'schedule_cron' ) );
				
				// Collect analytics data on cron event
				add_action( 'monsterinsights_daily_cron', array( $this, 'run_cron' ) );

				// If cron did not run for some reason (no users visited site), run it now
				if ( filter_input( INPUT_GET, 'page' ) === 'monsterinsights_dashboard' || filter_input( INPUT_GET, 'page' ) === 'monsterinsights_reports' ) {
					add_action( 'admin_init', array( $this, 'maybe_get_data' ) );
				}
		}


		// Dashboard disabled setting
		add_action( 'monsterinsights_settings_save_general', array( $this, 'dashboard_disabled' ), 9 );
	}

	public function dashboard_disabled( ) {
		$dashboards_disabled     = isset( $_POST['dashboards_disabled'] ) ? 1 : 0;
		$dashboards_disabled_old = monsterinsights_get_option( 'dashboards_disabled', false );

		// We only care if the switch is going on or off
		if ( $dashboards_disabled && ! $dashboards_disabled_old ) {
			// The dashboards are now being disabled
				// Clear data + last run + failed
				$this->delete_aggregate_data();

		} else if ( ! $dashboards_disabled && $dashboards_disabled_old ){
			// The dashboards are now being enabled
				// Refresh data + schedule cron
				$this->refresh_aggregate_data();
				$this->schedule_cron();
		}
	}

	public function schedule_cron() {
		if ( ! wp_next_scheduled( 'monsterinsights_daily_cron' ) ) {
			// Set the next event of fetching data
			wp_schedule_event( strtotime( date( 'Y-m-d', strtotime( 'tomorrow' ) ) . ' 00:01:00 ' ), 'daily', 'monsterinsights_daily_cron' );
		}
	}

	public function maybe_get_data() {
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			return false;
		}

		$last_run = monsterinsights_get_option( 'cron_last_run', false );

		// See if issue connecting or expired
		if ( $last_run === false || monsterinsights_hours_between( $last_run ) >= 24 || date( 'Ymd', $last_run ) !== date('Ymd', time() ) ) { 
			$this->run_cron();
		}
	}

	public function run_cron() {
		if ( $this->dashboard_disabled ) {
			return;
		}

		if ( is_numeric( (int) $this->client->profile ) && $this->client->status === 'valid' ) {
			// Profile is set
			$this->add_aggregate_data();
		}
	}

	public function refresh_aggregate_data() {
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			return false;
		}

		$this->delete_aggregate_data();
		$this->add_aggregate_data();
	}

	private function add_aggregate_data() {
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			return false;
		}
		/** 
		 * Developer Alert:
		 *
		 * Per the README, this is considered an internal hook and should
		 * not be used by other developers. This hook's behavior may be modified
		 * or the hook may be removed at any time, without warning.
		 */
		do_action( 'monsterinsights_add_aggregate_data', $this->client, $this->client->profile );
		monsterinsights_update_option( 'cron_last_run', time() );
		monsterinsights_delete_option( 'cron_failed' );
	}

	public function delete_aggregate_data() {
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			return false;
		}
		/** 
		 * Developer Alert:
		 *
		 * Per the README, this is considered an internal hook and should
		 * not be used by other developers. This hook's behavior may be modified
		 * or the hook may be removed at any time, without warning.
		 */
		if ( ! empty( $this->client ) && ! empty( $this->client->profile ) ) {
			do_action( 'monsterinsights_delete_aggregate_data', $this->client, $this->client->profile );
		}
		$options = array(
			'cron_failed',
			'cron_last_run',
		);
		monsterinsights_delete_options( $options );
	}
}