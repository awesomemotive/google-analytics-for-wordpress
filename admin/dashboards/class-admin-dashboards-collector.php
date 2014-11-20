<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Collector' ) ) {

	class Yoast_GA_Dashboards_Collector {

		/**
		 * API storage
		 *
		 * @package
		 */
		public $api;

		/**
		 * Store the active metrics
		 *
		 * @var
		 */
		public $active_metrics;

		/**
		 * Store the GA Profile ID
		 *
		 * @var
		 */
		public $ga_profile_id;

		/**
		 * Store the API libs
		 *
		 * @package
		 */
		public $api_libs;

		/**
		 * Construct on the dashboards class for GA
		 *
		 * @param $ga_profile_id
		 * @param $active_metrics
		 */
		public function __construct( $ga_profile_id, $active_metrics ) {
			$this->ga_profile_id = $ga_profile_id;
			$this->active_metrics = $active_metrics;

			$this->options = Yoast_GA_Dashboards_Api_Options::instance();

			$this->init_shutdown_hook();
		}

		/**
		 * This hook runs on the shutdown to fetch data from GA
		 */
		private function init_shutdown_hook() {
			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				$this->api = Yoast_Api_Libs::load_api_libraries( array( 'oauth', 'googleanalytics' ) );

				add_action( 'shutdown', array( $this, 'aggregate_data' ) );
			}
		}

		/**
		 * Fetch the data from Google Analytics and store it
		 */
		public function aggregate_data() {
			$instance = null;

			$access_tokens = $this->options->get_access_token();

			if ( $access_tokens != false && is_array( $access_tokens ) ) {
				// Access tokens are set, continue

				foreach ( $this->active_metrics as $metric ) {
					$this->execute_call( $access_tokens, $metric, date( 'Y-m-d', strtotime( '-6 weeks' ) ), date( 'Y-m-d' ) );
				}
			} else {
				// Failure on authenticating, please reauthenticate
			}
		}

		/**
		 * Execute an API call to Google Analytics and store the data in the dashboards data class
		 *
		 * @param $access_tokens
		 * @param $metric
		 * @param $start_date 2014-10-16
		 * @param $end_date   2014-11-20
		 *
		 * @return bool
		 */
		private function execute_call( $access_tokens, $metric, $start_date, $end_date ) {
			$params = array(
				'ids'        => 'ga:' . $this->ga_profile_id,
				'start-date' => $start_date,
				'end-date'   => $end_date,
				'dimensions' => 'ga:date',
				'metrics'    => 'ga:' . $metric,
			);
			$params = http_build_query( $params );
			$api_ga = Yoast_Googleanalytics_Reporting::instance();

			$response = $api_ga->do_api_request( 'https://www.googleapis.com/analytics/v3/data/ga?' . $params, 'https://www.googleapis.com/analytics/v3/data/ga', $access_tokens['oauth_token'], $access_tokens['oauth_token_secret'] );

			if ( is_array( $response ) && $response['response']['code'] == 200 ) {
				// Success, store this data

				return Yoast_GA_Dashboards_Data::set( $metric, $response, strtotime( $start_date ), strtotime( $end_date ) );
			} else {
				// Failure on API call try to log it

				if ( true == WP_DEBUG ) {
					if ( function_exists( 'error_log' ) ) {
						error_log( 'Yoast Google Analytics (Dashboard API): ' . print_r( $response['body_raw'], true ) );
					}
				}

				return false;
			}
		}

	}

}