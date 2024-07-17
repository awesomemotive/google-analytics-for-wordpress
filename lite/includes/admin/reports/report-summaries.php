<?php
/**
 * Summaries Report
 *
 * Ensures all of the reports have a uniform class with helper functions.
 *
 * @since 8.19.0
 *
 * @package MonsterInsights
 * @subpackage Reports
 * @author  Mahbubur Rahman
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_Report_Summaries extends MonsterInsights_Report {

	public $title;
	public $class = 'MonsterInsights_Report_Summaries';
	public $name = 'summaries';
	public $version = '1.0.0';
	public $level = 'basic';

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 8.19.0
	 */
	public function __construct() {
		$this->title = __( 'Summaries', 'google-analytics-for-wordpress' );
		parent::__construct();
	}

	/**
	 * Prepare report-specific data for output.
	 *
	 * @param array $data The data from the report before it gets sent to the frontend.
	 *
	 * @return mixed
	 */
	public function prepare_report_data( $data ) {

		// Escape urls for the top pages report.
		if ( ! empty( $data['data']['toppages'] ) ) {
			foreach ( $data['data']['toppages'] as $key => $page ) {
				$title = $data['data']['toppages'][ $key ]['title'];
				$url   = '(not set)' === $title ? '' : esc_url( $data['data']['toppages'][ $key ]['hostname'] );

				$data['data']['toppages'][ $key ]['hostname'] = $url;
			}
		}

		// Add GA links.
		if ( ! empty( $data['data'] ) ) {
			$data['data']['galinks'] = array(
				'referrals' => 'https://analytics.google.com/analytics/web/#report/trafficsources-referrals/' . MonsterInsights()->auth->get_referral_url() . $this->get_ga_report_range( $data['data'] ),
				'topposts'  => 'https://analytics.google.com/analytics/web/#/report/content-pages/' . MonsterInsights()->auth->get_referral_url() . $this->get_ga_report_range( $data['data'] ),
			);
		}

		return $data;
	}
}
