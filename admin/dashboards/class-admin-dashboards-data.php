<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Data' ) ) {

	class Yoast_GA_Dashboards_Data extends Yoast_GA_Dashboards {

		public function __construct(){

		}

		/**
		 * Get a data object
		 *
		 * @param array $args
		 *
		 * @return array
		 */
		public function get( $args = array() ){
			$data = array();

			return $data;
		}

		/**
		 * Set a data object
		 *
		 * @param array $args
		 *
		 * @return bool
		 */
		public function set( $args = array() ){
			return true;
		}

	}

}