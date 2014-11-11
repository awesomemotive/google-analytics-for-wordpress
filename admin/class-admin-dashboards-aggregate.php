<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Aggregrate' ) ) {

	class Yoast_GA_Dashboards_Aggregrate extends Yoast_GA_Dashboards {

		private $api;

		/**
		 * Construct on the dashboards class for GA
		 */
		public function __construct(){

			$this->api = Yoast_Api_Libs::load_api_libraries( array( 'oauth' ) );
		}

	}

}