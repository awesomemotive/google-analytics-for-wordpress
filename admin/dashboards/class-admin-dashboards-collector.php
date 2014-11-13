<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Collector' ) ) {

	class Yoast_GA_Dashboards_Collector extends Yoast_GA_Dashboards {

		/**
		 * API storage
		 *
		 * @var bool
		 */
		public $api;

		/**
		 * Construct on the dashboards class for GA
		 */
		public function __construct(){

			$this->api = Yoast_Api_Libs::load_api_libraries( array( 'oauth' ) );

			add_action('shutdown', array($this, 'aggregate_data'));
		}

		/**
		 * Fetch the data from Google Analytics and store it
		 */
		public function aggregate_data(){

		}

	}

}