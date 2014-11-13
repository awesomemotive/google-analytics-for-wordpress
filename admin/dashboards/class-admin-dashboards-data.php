<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Data' ) ) {

	class Yoast_GA_Dashboards_Data extends Yoast_GA_Dashboards {

		public function __construct(){

		}

		/**
		 * Get a data object
		 *
		 * @param       $type
		 * @param array $args
		 *
		 * @return array
		 */
		public function get( $type, $args = array() ){
			$data = array();

			return $data;
		}

		/**
		 * Set a data object
		 *
		 * @param $type
		 * @param $value
		 *
		 * @return bool
		 */
		public function set( $type, $value ){
			return true;
		}

	}

}