<?php
/**
 * Overview Report
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

final class MonsterInsights_Report_Overview extends MonsterInsights_Report {

	public $title;
	public $class = 'MonsterInsights_Report_Overview';
	public $name = 'overview';
	public $version = '1.0.0';
	public $level = 'lite';

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 6.0.0
	 */
	public function __construct() {
		$this->title = __( 'Overview', 'google-analytics-for-wordpress' );
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

		// Bounce rate add symbol.
		if ( ! empty( $data['data']['infobox']['bounce']['value'] ) ) {
			$data['data']['infobox']['bounce']['value'] .= '%';
		}

		// Add GA links.
		if ( ! empty( $data['data'] ) ) {
			$data['data']['galinks'] = array(
				'countries' => $this->get_ga_report_url( 'user-demographics-detail', $data['data'] ),
				'referrals' => $this->get_ga_report_url( 'lifecycle-user-acquisition', $data['data'], '_r.explorerCard..seldim=["userAcquiredCampaignSource"]' ),
				'topposts'  => $this->get_ga_report_url( 'all-pages-and-screens', $data['data'] ),
			);
		}

		$this->define_chart_overlay( $data );

		return apply_filters( 'monsterinsights_report_overview_data', $data );
	}

	/**
	 * Determine we need show chart overlay or not.
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	private function define_chart_overlay( &$data ) {
		// Set default value.
		$data['data']['show_chart_overlay'] = false;

		$connection_time = $this->get_connection_time();

		if ( ! $connection_time ) {
			return;
		}

		// If 24 hour passed then remove overlay.
		if ( $connection_time + DAY_IN_SECONDS < time() ) {
			return;
		}

		// If till now no data has tracked.
		if ( ! $data['data']['overviewgraph']['sessions']['max'] ) {
			$data['data']['show_chart_overlay'] = true;

			// Generate random chart data.
			for ( $i = 0; $i < $data['data']['overviewgraph']['count']; $i++ ) {
				$data['data']['overviewgraph']['sessions']['datapoints'][ $i ] = wp_rand(1, 5);
				$data['data']['overviewgraph']['pageviews']['datapoints'][ $i ] = wp_rand(1, 5);
			}

		}
	}

	/**
	 * Find when GA connected.
	 *
	 * @return int
	 */
	private function get_connection_time() {
		$activated = get_option( 'monsterinsights_over_time', array() );

		// If user had both activate.
		if ( ! empty( $activated['connected_date_lite'] ) && ! empty( $activated['connected_date_pro'] ) ) {
			// If lite activated last.
			if ( $activated['connected_date_lite'] > $activated['connected_date_pro'] ) {
				return $activated['connected_date_lite'];
			}

			return $activated['connected_date_pro'];
		}

		// If user has only lite activated.
		if ( ! empty( $activated['connected_date_lite'] ) ) {
			return $activated['connected_date_lite'];
		}

		// If user has only pro activated.
		if ( ! empty( $activated['connected_date_pro'] ) ) {
			return $activated['connected_date_pro'];
		}
	}
}
