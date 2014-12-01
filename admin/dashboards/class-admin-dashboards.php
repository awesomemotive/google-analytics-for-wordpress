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
		 * Store the active metrics
		 *
		 * @var
		 */
		public $active_metrics;

		/**
		 * Store the valid metrics which are available in the Google API, more can be added
		 *
		 * @var array
		 *
		 * @link https://ga-dev-tools.appspot.com/explorer/
		 */
		private $valid_metrics = array( 'sessions', 'bounces', 'users', 'newUsers', 'percentNewSessions', 'bounceRate', 'sessionDuration', 'avgSessionDuration', 'hits' );

		/**
		 * Store this instance
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Construct on the dashboards class for GA
		 */
		protected function __construct() {

		}

		/**
		 * Init the dashboards
		 */
		public function init_dashboards( $ga_profile_id ) {
			$dashboards = array(
				'sessions' => array(
					'title'      => __( 'Sessions', 'google-analytics-for-wordpress' ),
					'data-label' => __( 'Number of sessions', 'google-analytics-for-wordpress' ),
				),
				'bounceRate' => array(
					'title'      => __( 'Bouncerate', 'google-analytics-for-wordpress' ),
					'data-label' => __( 'Number of bounces', 'google-analytics-for-wordpress' ),
				),
			);

			// Register the active metrics
			$register = array();
			foreach( $dashboards as $metric => $value ){
				$register[] = $metric;
			}

			// @TODO enable this after merging to features/dashboards
			Yoast_GA_Dashboards_Graph::get_instance()->register($dashboards);

			$this->aggregator = new Yoast_GA_Dashboards_Collector( $ga_profile_id, $register );
			$this->register( $register );
		}

		/**
		 * Get the instance
		 *
		 * @return Yoast_GA_Dashboards
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Register the dashboard types
		 *
		 * @param $types array or singular string
		 *
		 * @return bool
		 */
		public function register( $types ) {
			if ( is_array( $types ) == false ) {
				$types = array( $types );
			}

			if ( is_array( $types ) && count( $types ) >= 1 ) {
				if ( $this->validate_dashboard_types( $types ) ) {
					$this->active_metrics = $types;

					return true;
				}
			}

			return false;
		}



		/**
		 * Validate the registered types of dashboards
		 *
		 * @param $types
		 *
		 * @return bool
		 */
		private function validate_dashboard_types( $types ) {
			$valid = true;

			if ( is_array( $types ) ) {
				foreach ( $types as $check_type ) {
					if ( ! in_array( $check_type, $this->valid_metrics ) ) {
						$valid = false;
					}
				}
			}

			return $valid;
		}
	}

}