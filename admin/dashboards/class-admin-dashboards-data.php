<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Data' ) ) {

	class Yoast_GA_Dashboards_Data extends Yoast_GA_Dashboards {

		public function __construct() {

		}

		/**
		 * Get a data object
		 *
		 * @param      $type	  sessions,bouncerate etc.
		 * @param      $startdate Unix timestamp
		 * @param null $enddate   Unix timestamp
		 *
		 * @return array
		 */
		public static function get( $type, $startdate, $enddate = NULL ) {
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
		public static function set( $type, $value ) {
			return true;
		}

	}

}