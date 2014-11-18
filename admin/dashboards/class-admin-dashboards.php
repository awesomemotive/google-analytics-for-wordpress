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
		 * Construct on the dashboards class for GA
		 */
		public function __construct() {
			$this->aggregator = new Yoast_GA_Dashboards_Collector;

			$this->data = new Yoast_GA_Dashboards_Data;

			$this->set_options();
		}

		/**
		 * Set the API options
		 */
		public function set_options() {
			$google_analytics = Yoast_Google_Analytics::instance();
			$this->options    = $google_analytics->get_options();

			if ( isset( $this->options['ga_oauth']['access_token']['oauth_token'] ) && isset( $this->options['ga_oauth']['access_token']['oauth_token_secret'] ) ) {
				$this->access_token = $this->options['ga_oauth']['access_token'];
			}
		}

		/**
		 * Get the API options
		 *
		 * @return mixed
		 */
		public function get_options() {
			return $this->options;
		}

		/**
		 * Get the access token from the options API, false on fail
		 *
		 * @return bool
		 */
		public function get_access_token() {
			if ( ! empty( $this->access_token ) ) {
				return $this->access_token;
			}
			else{
				return false;
			}
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