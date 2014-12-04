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
		 *
		 * @return array
		 */
		public static function get( $type ) {
			$transient = get_transient( 'yst_ga_' . $type );

			if ( false === $transient ) {
				// Transient does not exist, abort
				return array();
			}

			// @TODO loop through transient to get the correct date range

			return $transient;
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
			$store = array(
				'store_as'   => $store_as,
				'type'       => $type,
				'start_date' => $start_date,
				'end_date'   => $end_date,
				'value'      => $value,
			);

			return set_transient( 'yst_ga_' . $type, $store, self::$store_transient_time );
		}
	}

}