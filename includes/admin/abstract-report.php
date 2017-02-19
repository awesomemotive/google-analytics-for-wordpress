<?php
/**
 * Report Abstract  
 *
 * Ensures all of the reports have a uniform class with helper functions.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Reports
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class MonsterInsights_Report {

	public $report_name;

	public $report_hook;

	public $range;

	/**
	 * Holds the GA client object if using oAuth.
	 *
	 * @access public
	 * @since 6.0.0
	 * @var MonsterInsights_GA_Client $client GA client object.
	 */
	public $client;

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 6.0.0
	 */
	public function __construct( $range = array() ) {

		$this->range = monsterinsights_get_report_date_range();

		// filter to add tab for this report
		add_filter( 'monsterinsights_get_reports', array( $this, 'add_report' ), 10, 1 );

		// filter to show the view for this report
		add_action( 'monsterinsights_tab_reports_' . $this->report_hook, array( $this, 'show_report' ), 10, 1 );

		add_action( 'monsterinsights_add_aggregate_data', array( $this, 'add_report_data' ), 10, 2 );
		add_action( 'monsterinsights_delete_aggregate_data', array( $this, 'delete_report_data' ), 10, 2 );

	}

	 // Adds the report to the array of reports.
	public function add_report( $reports ) {
		$reports[ $this->report_hook ] = $this->report_name;
		return $reports;
	}

	abstract public function add_report_data( $client, $id ); // Adds/Refreshes the data
	abstract public function get_report_data(); // Gets the data
	abstract public function delete_report_data(); // Removes report data
	abstract public function show_report(); // Outputs the report.

	/**
	 * Get the start and and date for aggregation functionality.
	 *
	 * @return array
	 */
	protected function get_date_range() {
		return $this->range;
	}

	/**
	 * Get the api limit for aggregation functionality.
	 *
	 * By default Google will return 1000 rows at most. They will return less in certain circumstances.
	 * For example, the countries query will never return more than 300 rows as there's not more than 300 values
	 * for ga:countries dimension. If you are a large site you might need to use this filter to lower the number requested. 
	 * We only request 300, as it's the max number for the largest report we need (by country maxes at 300 countries).
	 *
	 * In the future, we'll likely have each report use the limit returned in this function, or a report-set default limit,
	 * whichever is lower.
	 *
	 * @return int
	 */
	protected function get_api_max_limit() {
		return apply_filters( 'monsterinsights_reporting_get_max_api_limit', 300 );
	}
}