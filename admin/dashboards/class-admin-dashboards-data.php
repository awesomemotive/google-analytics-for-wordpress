<?php

/**
 * This class is used to store and get the data of the dashboards. The data is aggregated by
 * the class-admin-dashboards-collector.php and saved with Yoast_GA_Dashboards_Data::set().
 *
 * You can retrieve the data by using the function Yoast_GA_Dashboards_Data::get() in this
 * class.
 */

if ( ! class_exists( 'Yoast_GA_Dashboards_Data' ) ) {

	class Yoast_GA_Dashboards_Data {

		/**
		 * The time to store a transient (in seconds)
		 *
		 * @var int
		 */
		private static $store_transient_time = DAY_IN_SECONDS;

		/**
		 * Get a data object
		 *
		 * @param      $type      String
		 * @param      $startdate Unix timestamp
		 * @param      $enddate   Unix timestamp
		 *
		 * @return array
		 */
		public static function get( $type, $startdate, $enddate ) {
			$data      = array();
			$range     = self::date_range( $startdate, $enddate );
			$transient = get_transient( 'yst_ga_' . $type );

			if ( false === $transient ) {
				// Transient does not exist, abort
				return array();
			}

			foreach ( $range as $date ) {
				$date_unix        = strtotime( $date );
				$data[$date_unix] = 0; // Set default value

				foreach ( $transient['value']['body'] as $value ) {
					if ( $date_unix == $value['date'] ) {
						if ( isset( $value['bool'] ) ) {
							$data[$date_unix] = array(
								'value' => $value['value'],
								'bool'  => $value['bool'],
							);
						} else {
							$data[$date_unix] = $value['value'];
						}
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
		 * @param $store_as
		 *
		 * @return bool
		 */
		public static function set( $type, $value, $start_date, $end_date, $store_as ) {
			//echo $store_as . '_' . $type;
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