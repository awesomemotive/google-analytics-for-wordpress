<?php
/**
 * Site Insights Report
 *
 * @since 8.24.0
 *
 * @package MonsterInsights
 * @subpackage Reports
 * @author  Andrei Lupu
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_Report_Site_Insights extends MonsterInsights_Report {

	public $title;
	public $class = 'MonsterInsights_Report_Site_Insights';
	public $name = 'site-insights';
	public $version = '1.0.0';
	public $level = 'basic';

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 7.11.0
	 */
	public function __construct() {
		$this->title = __( 'Site Insights', 'ga-premium' );

		add_filter( 'monsterinsights_report_use_cache', array( $this, 'use_cache' ), 10, 2 );
		add_filter( 'monsterinsights_report_transient_expiration', array( $this, 'set_cache_expiration' ), 10, 2 );

		parent::__construct();
	}

	/**
	 * Force the use of transients for site-insights report cache.
	 * Used in `monsterinsights_report_use_cache` where true means that cache is saved with set_options,
	 * and false means set_transient will be used.
	 *
	 * @param $use_cache
	 * @param $name
	 * @return bool
	 */
	public function use_cache( $use_cache, $name ) {
		return $this->name === $name ? false : $use_cache;
	}

	/**
	 * A method for the `monsterinsights_report_transient_expiration` filter.
	 * It will force the expiration time for the transient to be "tomorrow".
	 *
	 * @param $length
	 * @param $name
	 * @return int|string
	 */
	public function set_cache_expiration( $length, $name ) {
		$current_timestamp = current_time( 'U' );
		$expire = ( strtotime( 'Tomorrow 12:05am', $current_timestamp ) - $current_timestamp );

		return $this->name === $name ? $expire : $length;
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

		return $data;
	}
}
