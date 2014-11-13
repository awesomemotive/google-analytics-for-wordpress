<?php

if ( ! class_exists( 'Yoast_GA_Dashboards' ) ) {

	class Yoast_GA_Dashboards extends Yoast_GA_Admin {

		/**
		 * Store the data aggregator
		 *
		 * @var
		 */
		public $aggregator;

		/**
		 * Store the Data instance
		 *
		 * @var
		 */
		public $data;

		/**
		 * Construct on the dashboards class for GA
		 */
		public function __construct() {
			$this->aggregator = new Yoast_GA_Dashboards_Collector;

			$this->data = new Yoast_GA_Dashboards_Data;
		}

		/**
		 * Get the data instance
		 *
		 * @return Yoast_GA_Dashboards_Data
		 */
		public function data() {
			return $this->data;
		}

	}

}