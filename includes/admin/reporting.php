<?php
/**
 * MonsterInsights Reporting.
 *
 * Handles aggregating data.
 *
 * @since 7.0.0
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

	public $reports = array();

	public function __construct( ) {

	}

	public function add_report( $report = false ){
		if ( empty( $report ) || ! is_object( $report ) ) {
			return;
		}

		if ( version_compare( $report->version, '1.0.0', '<' ) ) {
			return;
		}

		$this->reports[] = $report;
	}

	public function get_aggregate_data() {
		if ( ! empty( $this->reports ) ) {
			foreach ( $this->reports as $report ) {
				$report->get_data( array( 'default' => true ) );
			}
		}
	}

	// $where possible values: auto, site, network, both
	public function delete_aggregate_data( $where = 'site' ) {
		if ( ! empty( $this->reports ) ) {
			foreach ( $this->reports as $report ) {
				$report->delete_cache( $where );
			}
		}
	}

	public function get_report( $name = '' ) {
		if ( empty( $name ) || empty( $this->reports ) ) {
			return false;
		}

		foreach ( $this->reports as $report ) {
			if ( $name === $report->name ) {
				return $report;
			}
		}
		return false;
	}
}