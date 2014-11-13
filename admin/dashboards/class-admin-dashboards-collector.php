<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Collector' ) ) {

	class Yoast_GA_Dashboards_Collector extends Yoast_GA_Dashboards {

		/**
		 * API storage
		 *
		 * @package
		 */
		public $api;

		public $valid_types = array( 'sessions', 'bouncerate' );

		/**
		 * Construct on the dashboards class for GA
		 */
		public function __construct() {

			$this->api = Yoast_Api_Libs::load_api_libraries( array( 'oauth' ) );

			add_action( 'shutdown', array( $this, 'aggregate_data' ) );
		}

		/**
		 * Fetch the data from Google Analytics and store it
		 */
		public function aggregate_data() {

		}

		/**
		 * Validate the registered types of dashboards
		 *
		 * @param $types
		 *
		 * @return bool
		 */
		public function validate_dashboard_types( $types ) {
			$valid = true;

			if ( is_array( $types ) ) {
				foreach ( $types as $check_type ) {
					if ( ! in_array( $check_type, $types ) ) {
						$valid = false;
					}
				}
			}

			return $valid;
		}

	}

}