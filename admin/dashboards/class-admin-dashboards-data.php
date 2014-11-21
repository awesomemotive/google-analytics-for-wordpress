<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Data' ) ) {

	class Yoast_GA_Dashboards_Data extends Yoast_GA_Dashboards {

		/**
		 * The time to store a transient (in seconds)
		 *
		 * @var int
		 */
		private static $store_transient_time = DAY_IN_SECONDS;

		public function __construct() {

		}

		/**
		 * Get a data object
		 *
		 * @param      $type      sessions,bouncerate etc.
		 * @param      $startdate Unix timestamp
		 * @param      $enddate   Unix timestamp
		 *
		 * @return array
		 */
		public static function get( $type, $startdate, $enddate ) {
			$data  = array();
			$range = self::date_range( $startdate, $enddate );
			$transient = get_transient( 'yst_ga_' . $type );

			if ( false === $transient ) {
				// Transient does not exist, abort
				return array();
			}

			foreach ( $range as $date ) {
				$date_unix = strtotime( $date );
				$data[ $date_unix ] = 0; // Set default value

				foreach($transient['value']['body'] as $value){
					if( $date_unix == $value['date'] ){
						$data[ $date_unix ] = $value['value'];
					}
				}
			}

			return $data;
		}

		/**
		 * Save a data object
		 *
		 * @param $type
		 * @param $value
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return bool
		 */
		public static function set( $type, $value, $start_date, $end_date ) {
			$store = array(
				'type'       => $type,
				'start_date' => $start_date,
				'end_date'   => $end_date,
				'value'      => $value,
			);

			return set_transient( 'yst_ga_' . $type, $store, self::$store_transient_time );
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
			$dates   = array();
			$current = $first;
			$last    = $last;

			while ( $current <= $last ) {
				$dates[] = date( $format, $current );
				$current = strtotime( $step, $current );
			}

			return $dates;
		}
	}

}