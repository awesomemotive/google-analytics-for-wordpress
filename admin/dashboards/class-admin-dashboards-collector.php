<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Collector' ) ) {

	class Yoast_GA_Dashboards_Collector extends Yoast_GA_Dashboards {

		/**
		 * API storage
		 *
		 * @package
		 */
		public $api;

		/**
		 * Set the valid types here. Make sure you create a class with the following naming convention:
		 * admin/dashboards/class-admin-dashboards-collector-TYPENAME.php
		 *
		 * TYPENAME is the type name written in lowercase
		 *
		 * @var array
		 */
		public $valid_types = array( 'sessions', 'bouncerate' );

		/**
		 * Placeholder for the classes which need to be loaded in the aggregate_data function
		 *
		 * @var array
		 */
		public static $aggregator_classes = array();

		/**
		 * Construct on the dashboards class for GA
		 */
		public function __construct() {
			$this->init_shutdown_hook();
		}

		private function init_shutdown_hook(){
			if( is_admin() && !defined( 'DOING_AJAX' ) ) {
				$this->api = Yoast_Api_Libs::load_api_libraries( array( 'oauth' ) );

				add_action( 'shutdown', array( $this, 'aggregate_data' ) );
			}
		}

		/**
		 * Fetch the data from Google Analytics and store it
		 */
		public function aggregate_data() {
			$classes  = self::$aggregator_classes;
			$instance = NULL;

			// Check if we need to fetch data, if so, authenticate and call child classes
			if ( is_array( $classes ) ) {
				//$auth_status = $this->oauth_authenticate();
				$auth_status = NULL;

				foreach ( $classes as $class ) {
					$instance = NULL;
					if ( class_exists( $class, false ) ) {
						$instance = new $class( $auth_status );
					}
				}
			}
		}

		/**
		 * Validate the registered types of dashboards
		 *
		 * @param $types
		 *
		 * @return bool
		 */
		public static function validate_dashboard_types( $types ) {
			$valid = true;

			if ( is_array( $types ) ) {
				foreach ( $types as $check_type ) {
					if ( ! in_array( $check_type, $types ) ) {
						$valid = false;
					}
				}
			}

			if ( $valid ) {
				self::load_valid_types( $types );
			}

			return $valid;
		}

		/**
		 * Load the validated types, to add one go to line 22 of this class
		 *
		 * @param $types
		 */
		private static function load_valid_types( $types ) {
			$load = array();

			if ( is_array( $types ) ) {
				foreach ( $types as $type ) {
					$include_file = dirname( __FILE__ ) . '/class-admin-dashboards-collector-' . $type . '.php';
					$class_name   = 'Yoast_GA_Dashboards_Collector_' . ucfirst( $type );

					if ( file_exists( $include_file ) ) {
						require( $include_file );

						if ( class_exists( $class_name, false ) ) {
							$load[] = $class_name;
						}
					}
				}
			}

			if ( is_array( $load ) && count( $load ) >= 1 ) {
				self::load_on_aggregate( $load );
			}
		}

		/**
		 * Set the valid classes for the aggregation process
		 *
		 * @param $classes
		 */
		private static function load_on_aggregate( $classes ) {
			self::$aggregator_classes = $classes;
		}

	}

}