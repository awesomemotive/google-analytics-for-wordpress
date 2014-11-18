<?php

if ( ! class_exists( 'Yoast_GA_Dashboards' ) ) {

	class Yoast_GA_Dashboards {

		/**
		 * Store the data aggregator
		 *
		 * @package
		 */
		public $aggregator;

		/**
		 * Store the Data instance
		 *
		 * @package
		 */
		public $data;

		/**
		 * Get the options
		 *
		 * @var
		 */
		public $options;

		/**
		 * Store the access token
		 *
		 * @var
		 */
		public $access_token;

		/**
		 * Store this instance
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Construct on the dashboards class for GA
		 */
		public function __construct() {
			$this->init_dashboards();
		}

		/**
		 * Init the dashboards
		 */
		private function init_dashboards() {
			$this->data = new Yoast_GA_Dashboards_Data;

			$this->aggregator = new Yoast_GA_Dashboards_Collector;
		}

		/**
		 * Get the instance
		 *
		 * @return Yoast_GA_Dashboards
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Get the data instance
		 *
		 * @return Yoast_GA_Dashboards_Data
		 */
		public function data() {
			return $this->data;
		}

		/**
		 * Register the dashboard types
		 *
		 * @param $types array or singular string
		 *
		 * @return bool
		 */
		public static function register( $types ) {
			if ( is_array( $types ) == false ) {
				$types = array( $types );
			}

			if ( is_array( $types ) && count( $types ) >= 1 ) {
				if ( Yoast_GA_Dashboards_Collector::validate_dashboard_types( $types ) ) {
					return set_transient( 'yst_ga_dashboard_types', $types, 12 * HOUR_IN_SECONDS );
				}
			}

			return false;
		}

	}

}