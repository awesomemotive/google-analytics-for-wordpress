<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Data' ) ) {

	class Yoast_GA_Dashboards_Data extends Yoast_GA_Dashboards {

		public function __construct() {

		}

		/**
		 * Get a data object
		 *
		 * @param      $type      sessions,bouncerate etc.
		 * @param      $startdate Unix timestamp
		 * @param null $enddate   Unix timestamp
		 *
		 * @return array
		 */
		public static function get( $type, $startdate, $enddate = NULL ) {
			$data  = array();
			$range = self::date_range( $startdate, $enddate );

			foreach ( $range as $date ) {
				$data[strtotime( $date )] = rand( 5, 50 );
			}

			return $data;
		}

		/**
		 * Save a data object
		 *
		 * @param $type
		 * @param $value
		 *
		 * @return bool
		 */
		public static function set( $type, $value ) {
			return true;
		}

		/**
		 * Calculate the date range between 2 dates
		 *
		 * @param        $first
		 * @param        $last
		 * @param string $step
		 * @param string $format
		 *
		 * @return array
		 */
		private static function date_range( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {
			$dates = array();
//			$current = strtotime( $first );
			$current = $first;
//			$last    = strtotime( $last );
			$last = $last;

			while ( $current <= $last ) {
				$dates[] = date( $format, $current );
				$current = strtotime( $step, $current );
			}

			return $dates;
		}
	}

}