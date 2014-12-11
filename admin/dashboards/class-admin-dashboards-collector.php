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
		 * Store the valid metrics, which should be
		 *
		 * @var array
		 */
		private $valid_metrics = array();

		/**
		 * Store the GA Profile ID
		 *
		 * @var
		 */
		public $ga_profile_id;

		/**
		 * Construct on the dashboards class for GA
		 *
		 * @param $ga_profile_id
		 * @param $active_metrics
		 * @param $valid_metrics
		 */
		public function __construct( $ga_profile_id, $active_metrics, $valid_metrics ) {
			$this->ga_profile_id = $ga_profile_id;

			$active_metrics       = $this->filter_metrics_to_dimensions( $active_metrics );
			$this->active_metrics = $active_metrics;

			add_filter( 'ga_dashboards_dimensions', array( $this, 'filter_dimensions' ), 10, 1 );

			$this->options = Yoast_GA_Dashboards_Api_Options::get_instance();

			$this->init_shutdown_hook();
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
		 * This hook runs on the shutdown to fetch data from GA
		 */
		private function init_shutdown_hook() {
			$this->api = Yoast_Api_Libs::load_api_libraries( array( 'oauth', 'googleanalytics' ) );

			// Hook the WP cron event
			add_action( 'wp', array( $this, 'setup_wp_cron_aggregate' ) );

			// Hook our function to the WP cron event the fetch data daily
			add_action( 'yst_ga_aggregate_data', array( $this, 'aggregate_data' ) );

			// Check if the WP cron did run on time
			if ( isset( $_GET['page'] ) && $_GET['page'] === 'yst_ga_dashboard' ) {
				add_action( 'shutdown', array( $this, 'check_api_call_hook' ) );
			}
		}

		/**
		 * Check if we scheduled the WP cron event, if not, do so.
		 */
		public function setup_wp_cron_aggregate() {
			if ( ! wp_next_scheduled( 'yst_ga_aggregate_data' ) ) {
				// Set the next event of fetching data
				wp_schedule_event( strtotime( date( 'Y-m-d', strtotime( 'tomorrow' ) ) . ' 00:05:00 ' ), 'daily', 'yst_ga_aggregate_data' );
			}
		}

		/**
		 * Check if the WP cron did run yesterday. If not, we need to run it form here
		 */
		public function check_api_call_hook() {
			$last_run = get_transient( 'yst_ga_last_wp_run' );

			if ( $last_run === false ) {
				/**
				 * Transient doesn't exists, so we need to run the
				 * hook (This function runs already on Shutdown so
				 * we can call it directly from now on)
				 */
				$this->aggregate_data();
			} else {
				// Transient exists
				if ( $this->hours_between( strtotime( $last_run ), time() ) >= 24 ) {
					$this->aggregate_data();
				}
			}
		}

		/**
		 * Calculate the date difference, return the amount of hours between the two dates
		 *
		 * @param $last_run datetime
		 * @param $now      datetime
		 *
		 * @return int
		 */
		private function hours_between( $last_run, $now ) {
			$seconds = max( ( $now - $last_run ), 1 );
			$hours   = $seconds / 3600;
			
			return floor( $hours );
		}

		/**
		 * Remove metrics and set them as a dimension if needed
		 *
		 * @param $metrics
		 *
		 * @return mixed
		 */
		private function filter_metrics_to_dimensions( $metrics ) {
			$filter_metrics = $this->get_filter_metrics();

			foreach ( $metrics as $key => $metric_name ) {
				if ( isset( $filter_metrics[$metric_name] ) ) {
					// Add and set the dimension
					$dimension        = array( $filter_metrics[$metric_name] );
					$this->dimensions = array_merge( $this->dimensions, $dimension );

					// Remove it from the metrics after we've added it into dimensions
					unset( $metrics[$key] );
				}
			}

			return $metrics;
		}

		/**
		 * Get array with metrics which we need to filter as a dimension
		 *
		 * @return array
		 */
		private function get_filter_metrics() {
			return array(
				'source'        => array(
					'metric'       => 'sessions',
					'dimension'    => 'source',
					'storage_name' => 'source',
				),
				'top_pageviews' => array(
					'metric'       => 'pageViews',
					'dimension'    => 'pagePath',
					'storage_name' => 'top_pageviews',
				),
				'top_countries' => array(
					'metric'       => 'sessions',
					'dimension'    => 'country',
					'storage_name' => 'top_countries',
				),
			);
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
				$this->execute_call( $access_tokens, $metric, date( 'Y-m-d', strtotime( '-6 weeks' ) ), date( 'Y-m-d', strtotime( 'yesterday' ) ) );
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
				if ( ( isset( $dimension['id'] ) || isset( $dimension['dimension'] ) ) && isset( $dimension['metric'] ) ) {
					if ( isset( $dimension['id'] ) ) {
						$this->execute_call( $access_tokens, $dimension['metric'], date( 'Y-m-d', strtotime( '-1 month' ) ), date( 'Y-m-d', strtotime( 'yesterday' ) ), 'ga:dimension' . $dimension['id'] );
					} elseif ( isset( $dimension['dimension'] ) ) {
						if ( isset( $dimension['storage_name'] ) ) {
							$this->execute_call( $access_tokens, $dimension['metric'], date( 'Y-m-d', strtotime( '-1 month' ) ), date( 'Y-m-d', strtotime( 'yesterday' ) ), 'ga:' . $dimension['dimension'], $dimension['storage_name'] );
						} else {
							$this->execute_call( $access_tokens, $dimension['metric'], date( 'Y-m-d', strtotime( '-1 month' ) ), date( 'Y-m-d', strtotime( 'yesterday' ) ), 'ga:' . $dimension['dimension'] );
						}
					}
				}
			}
		}

		/**
		 * Execute an API call to Google Analytics and store the data in the dashboards data class
		 *
		 * @param $access_tokens
		 * @param $metric
		 * @param $start_date   2014-10-16
		 * @param $end_date     2014-11-20
		 * @param $dimensions   ga:date
		 * @param $storage_name string
		 *
		 * @return bool
		 */
		private function execute_call( $access_tokens, $metric, $start_date, $end_date, $dimensions = 'ga:date', $storage_name = 'auto' ) {
			$dimensions   = $this->prepare_dimensions( $dimensions, $metric );
			$params       = $this->build_params_for_call( $start_date, $end_date, $dimensions, $metric );
			$storage_type = $this->get_storage_type( $dimensions );

			$response = Yoast_Googleanalytics_Reporting::instance()->do_api_request(
				'https://www.googleapis.com/analytics/v3/data/ga?' . $params,
				'https://www.googleapis.com/analytics/v3/data/ga',
				$access_tokens['oauth_token'],
				$access_tokens['oauth_token_secret'],
				$storage_type,
				$start_date,
				$end_date
			);

			if ( strpos( 'ga:date', $dimensions ) !== false ) {
				return $this->handle_response( $response, $metric, $dimensions, $start_date, $end_date, 'datelist', $storage_name );
			} else {
				return $this->handle_response( $response, $metric, $dimensions, $start_date, $end_date, 'table', $storage_name );
			}
		}

		/**
		 * Get the storage type from dimensions
		 *
		 * @param $dimensions
		 *
		 * @return string
		 */
		private function get_storage_type( $dimensions ) {
			if ( strpos( 'ga:date', $dimensions ) !== false ) {
				return 'datelist';
			} else {
				return 'table';
			}
		}

		/**
		 * Prepare dimensions before adding them as a parameter in a call
		 *
		 * @param $dimensions
		 *
		 * @return string
		 */
		private function prepare_dimensions( $dimensions, $metric ) {
			$filter_metrics = $this->get_filter_metrics();

			// Check if the dimensions param is an array, if so, glue it with implode to a comma separated string.
			if ( is_array( $dimensions ) ) {
				$dimensions = implode( ',', $dimensions );
			}

			if ( in_array( $metric, $this->valid_metrics ) ) {
				$dimensions = 'ga:date,' . $dimensions;
			} elseif ( isset( $filter_metrics[str_replace( 'ga:', '', $dimensions )] ) ) {
				// Make sure we don't have a ga:date property here
				$dimensions = str_replace( 'ga:date', '', $dimensions );
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
				'ids'         => 'ga:' . $this->ga_profile_id,
				'start-date'  => $start_date,
				'end-date'    => $end_date,
				'dimensions'  => $dimensions,
				'metrics'     => 'ga:' . $metric,
				'max-results' => 10000,
			);

			$params = $this->add_sort_direction( $params, $dimensions, $metric );
			$params = http_build_query( $params );

			return $params;
		}

		/**
		 * Add a sort direction if we need to (Especially on dimensions which are
		 * listed in $this->get_filter_metrics())
		 *
		 * @param $params
		 *
		 * @return mixed
		 */
		private function add_sort_direction( $params, $dimensions, $metric ) {
			$filter_dimensions = $this->get_filter_metrics();

			foreach ( $filter_dimensions as $dimension ) {
				if ( str_replace( 'ga:', '', $dimensions ) == $dimension['dimension'] && str_replace( 'ga:', '', $metric ) == $dimension['metric'] ) {
					$params['sort'] = '-ga:' . $dimension['metric'];
				}
			}

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
		 * @param $store_as
		 * @param $storage_name
		 *
		 * @return bool
		 */
		private function handle_response( $response, $metric, $dimensions, $start_date, $end_date, $store_as = 'table', $storage_name = 'auto' ) {
			if ( is_array( $response ) && isset( $response['response']['code'] ) && $response['response']['code'] == 200 ) {
				// Success, store this data
				$filter_metrics = $this->get_filter_metrics();
				$extracted      = str_replace( 'ga:', '', str_replace( 'ga:date,', '', $dimensions ) );

				if ( isset( $filter_metrics[$extracted] ) ) {
					$name = $extracted;

				} else {
					$name = $metric;
				}

				if ( $dimensions !== 'ga:date' && ! isset( $filter_metrics[$extracted] ) ) {
					$name = str_replace( 'ga:date,', '', $dimensions );
				}

				// Overwrite the name if we have a defined one
				if ( $storage_name != 'auto' ) {
					$name = $storage_name;
				}
				
				/**
				 * Success, set a transient which stores the latest runtime
				 */
				if ( ! empty($response['body'] ) ) {
					set_transient( 'yst_ga_last_wp_run', date( 'Y-m-d' ), 48 * HOUR_IN_SECONDS );
				}

				return Yoast_GA_Dashboards_Data::set( $name, $response['body'], strtotime( $start_date ), strtotime( $end_date ), $store_as );
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