<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_Lite_Report_YearInReview extends MonsterInsights_Report {

	public $title;
	public $class   = 'MonsterInsights_Lite_Report_YearInReview';
	public $name    = 'yearinreview';
	public $version = '1.0.0';
	public $level   = 'lite';

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 7.11.0
	 */
	public function __construct() {
		$this->title = __( 'Year in Review', 'google-analytics-for-wordpress' );
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
		// Add flags to the countries report.
		if ( ! empty( $data['data']['countries'] ) ) {
			$country_names = monsterinsights_get_country_list( true );
			foreach ( $data['data']['countries'] as $key => $country ) {
				$data['data']['countries'][ $key ]['name'] = isset( $country_names[ $country['iso'] ] ) ? $country_names[ $country['iso'] ] : $country['iso'];
			}
		}

		// Escape urls for the top pages report.
		if ( ! empty( $data['data']['toppages'] ) ) {
			foreach ( $data['data']['toppages'] as $key => $page ) {
				$title = $data['data']['toppages'][ $key ]['title'];
				$url   = '(not set)' === $title ? '' : esc_url( $data['data']['toppages'][ $key ]['hostname'] );

				$data['data']['toppages'][ $key ]['hostname'] = $url;
			}
		}

		// Add logged in user name
		$user_info = wp_get_current_user();
		$data['data']['user_name'] = '';

		if ( ! empty( $user_info->user_firstname ) ) {
			$first_name = $user_info->user_firstname;

			$data['data']['user_name'] = $first_name;
		}

		return $data;
	}
}
