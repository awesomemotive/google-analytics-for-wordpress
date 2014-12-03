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
		 * Store the dimensions
		 *
		 * @var array
		 */
		private $dimensions = array();

		/**
		 * Store the GA Profile ID
		 *
		 * @var
		 */
		public $ga_profile_id;

		/**
		 * The $_GET pages where the shutdown hook should be executed to aggregate data
		 *
		 * @var array
		 */
		private $shutdown_get_pages = array( 'yst_ga_dashboard', 'yst_ga_settings', 'yst_ga_extensions' );

		/**
		 * The $_SERVER['SCRIPT_NAME'] pages where the shutdown hook should be executed to aggregate data
		 */
		private $shutdown_pages = array( '/wp-admin/index.php' );

		/**
		 * Construct on the dashboards class for GA
		 *
		 * @param $ga_profile_id
		 * @param $active_metrics
		 */
		public function __construct( $ga_profile_id, $active_metrics ) {
			$this->ga_profile_id  = $ga_profile_id;
			$this->active_metrics = $active_metrics;

			add_filter( 'ga_dashboards_dimensions', array( $this, 'filter_dimensions' ), 10, 1 );

			$this->options = Yoast_GA_Dashboards_Api_Options::get_instance();

			$this->init_shutdown_hook();
		}

		/**
		 * This hook runs on the shutdown to fetch data from GA
		 */
		private function init_shutdown_hook() {
			$this->api = Yoast_Api_Libs::load_api_libraries( array( 'oauth', 'googleanalytics' ) );

			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				if ( $this->run_shutdown_hook_get() || $this->run_shutdown_hook_page() ) {
					add_action( 'shutdown', array( $this, 'aggregate_data' ), 10 );

					return;
				}
			}
		}

		/**
		 * Fetch the data from Google Analytics and store it
		 */
		public function aggregate_data() {
			$access_tokens = $this->options->get_access_token();

			if ( $access_tokens != false && is_array( $access_tokens ) ) {
				// Access tokens are set, continue

				/**
				 * Implement the metric data first
				 */
				if ( is_array( $this->active_metrics ) && count( $this->active_metrics ) >= 1 ) {
					$this->aggregate_metrics( $access_tokens, $this->active_metrics );
				}

				/**
				 * Now implement the dimensions that are set
				 */
				if ( is_array( $this->dimensions ) && count( $this->dimensions ) >= 1 ) {
					$this->aggregate_dimensions( $access_tokens, $this->dimensions );
				}
			} else {
				// Failure on authenticating, please reauthenticate
			}
		}

		/**
		 * Filter function for adding dimensions
		 *
		 * @filter ga_dashboards_dimensions
		 *
		 * @param $dimensions
		 *
		 * @return array
		 */
		public function filter_dimensions( $dimensions = array() ) {
			if ( is_array( $dimensions ) && count( $dimensions ) >= 1 ) {
				$dimensions       = array_merge( $this->dimensions, $dimensions );
				$this->dimensions = $dimensions;
			}

			return $this->dimensions;
		}

		/**
		 * Aggregate metrics from GA. This function should be called in the shutdown function.
		 *
		 * @param $access_tokens
		 * @param $metrics
		 */
		private function aggregate_metrics( $access_tokens, $metrics ) {
			foreach ( $metrics as $metric ) {
				$this->execute_call( $access_tokens, $metric, date( 'Y-m-d', strtotime( '-6 weeks' ) ), date( 'Y-m-d' ) );
			}
		}

		/**
		 * Aggregate dimensions from GA. This function should be called in the shutdown function.
		 *
		 * @param $access_tokens
		 * @param $dimensions
		 */
		private function aggregate_dimensions( $access_tokens, $dimensions ) {
			foreach ( $dimensions as $dimension ) {
				if ( isset( $dimension['id'] ) && isset( $dimension['metric'] ) ) {
					$this->execute_call( $access_tokens, $dimension['metric'], date( 'Y-m-d', strtotime( '-6 weeks' ) ), date( 'Y-m-d' ), 'ga:dimension' . $dimension['id'] );
				}
			}
		}

		/**
		 * Check if the shutdown hook should be ran on the GET var
		 *
		 * @return bool
		 */
		private function run_shutdown_hook_get() {
			if ( isset( $_GET['page'] ) && in_array( $_GET['page'], $this->shutdown_get_pages ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if the shutdown hook should be ran on this page
		 *
		 * @return bool
		 */
		private function run_shutdown_hook_page() {
			if ( isset( $_SERVER['SCRIPT_NAME'] ) && in_array( $_SERVER['SCRIPT_NAME'], $this->shutdown_pages ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Execute an API call to Google Analytics and store the data in the dashboards data class
		 *
		 * @param $access_tokens
		 * @param $metric
		 * @param $start_date 2014-10-16
		 * @param $end_date   2014-11-20
		 * @param $dimensions ga:date
		 *
		 * @return bool
		 */
		private function execute_call( $access_tokens, $metric, $start_date, $end_date, $dimensions = 'ga:date' ) {
			$dimensions = $this->prepare_dimensions( $dimensions );
			$params     = $this->build_params_for_call( $start_date, $end_date, $dimensions, $metric );
			$api_ga     = Yoast_Googleanalytics_Reporting::instance();

			$response = $api_ga->do_api_request(
				'https://www.googleapis.com/analytics/v3/data/ga?' . $params,
				'https://www.googleapis.com/analytics/v3/data/ga',
				$access_tokens['oauth_token'],
				$access_tokens['oauth_token_secret']
			);

			return $this->handle_response( $response, $metric, $dimensions, $start_date, $end_date );
		}

		/**
		 * Prepare dimensions before adding them as a parameter in a call
		 *
		 * @param $dimensions
		 *
		 * @return string
		 */
		private function prepare_dimensions( $dimensions ) {
			// Check if the dimensions param is an array, if so, glue it with implode to a comma separated string.
			if ( is_array( $dimensions ) ) {
				$dimensions = implode( ',', $dimensions );
			}

			if ( strpos( 'ga:date', $dimensions ) === false ) {
				$dimensions = 'ga:date,' . $dimensions;
			}

			return $dimensions;
		}

		/**
		 * Build the params for a call to Google Analytics, return them prepared for a http query
		 *
		 * @param $start_date
		 * @param $end_date
		 * @param $dimensions
		 * @param $metric
		 *
		 * @return array|string
		 */
		private function build_params_for_call( $start_date, $end_date, $dimensions, $metric ) {
			$params = array(
				'ids'        => 'ga:' . $this->ga_profile_id,
				'start-date' => $start_date,
				'end-date'   => $end_date,
				'dimensions' => $dimensions,
				'metrics'    => 'ga:' . $metric,
			);
			$params = http_build_query( $params );

			return $params;
		}

		/**
		 * Handle the response from the Google Analytics api.
		 *
		 * @param $response
		 * @param $metric
		 * @param $dimensions
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return bool
		 */
		private function handle_response( $response, $metric, $dimensions, $start_date, $end_date ) {
			if ( is_array( $response ) && isset( $response['response']['code'] ) && $response['response']['code'] == 200 ) {
				// Success, store this data
				$name = $metric;

				if ( $dimensions !== 'ga:date' ) {
					$name = str_replace( 'ga:date,', '', $dimensions );
				}

				return Yoast_GA_Dashboards_Data::set( $name, $response, strtotime( $start_date ), strtotime( $end_date ) );
			} else {
				// Failure on API call try to log it
				$this->log_error( print_r( $response['body_raw'], true ) );

				return false;
			}
		}

		/**
		 * Log an error while calling the Google Analytics API
		 *
		 * @param $error
		 */
		private function log_error( $error ) {
			if ( true == WP_DEBUG ) {
				if ( function_exists( 'error_log' ) ) {
					error_log( 'Yoast Google Analytics (Dashboard API): ' . $error );
				}
			}
		}

	}

}