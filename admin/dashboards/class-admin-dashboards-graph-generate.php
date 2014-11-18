<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Graph_Generate' ) ) {

	class Yoast_GA_Dashboards_Graph_Generate {

		/**
		 * Which type of data should be loaded
		 *
		 * @var
		 */
		private $graph_type;

		/**
		 * For which period should the data be shown
		 *
		 * @var
		 */
		private $period;

		/**
		 * The end date
		 * @var
		 */
		private $end_date;

		/**
		 * The start date
		 *
		 * @var
		 */
		private $start_date;

		/**
		 * The field that will be used for displaying on x-axis. Mostly it will be the j (day in month)
		 *
		 * See: http://nl3.php.net/manual/en/function.date.php under 'format'
		 *
		 * @var string
		 */
		private $date_field = 'j';

		/**
		 * Storage for $data
		 *
		 * @var array
		 */
		private $data    = array();

		/**
		 * Storage for mapping
		 *
		 * @var array
		 */
	    private $mapping = array();

		/**
		 * Construct will set all values and generate the date for response
		 */
		public function __construct() {
			$this->set_graph_type();
			$this->set_period();
			$this->set_end_date();
			$this->set_start_date();
			$this->set_date_field();

			$this->generate();
		}

		/**
		 * Putting $this->data and $this->mapping and give them back as a json encoded string
		 *
		 * @return string
		 */
		public function get_json() {

			$return = array(
				'data'    => $this->data,
				'mapping' => $this->mapping
			);

			return json_encode( $return );
		}

		/**
		 * Getting graph_id from post and strip HTML-prefix graph- to get the type
		 */
		private function set_graph_type() {
			$graph_id         = filter_input( INPUT_GET, 'graph_id' );
			$graph_type       = substr( 'graph-', $graph_id );
			$this->graph_type = $graph_type;
		}

		/**
		 * Getting the period from post
		 */
		private function set_period() {
			$this->period = filter_input( INPUT_GET, 'period' );
		}

		/**
		 * Setting the end date
		 */
		private function set_end_date() {
			$this->end_date = time();
		}

		/**
		 * This method will set a start_date based on $this->period
		 *
		 * The values in dropdown, that will be mapped in strtotime
		 * See: http://php.net/manual/en/datetime.formats.relative.php
		 */
		private function set_start_date() {

			switch ( $this->period ) {
				case 'lastweek' :
					$time = '-6 days';
					break;
				default:
				case 'lastmonth' :
					$time = '-1 month';
					break;
			}

			$start_date = strtotime( $time, $this->end_date );

			$this->start_date = $start_date;
		}

		/**
		 * Which field should be taken from timestamp. Most cases J will be good
		 */
		private function set_date_field() {
			switch ( $this->period ) {
				case 'lastweek' :
				case 'lastmonth' :
					$date_field = 'j';
					break;
			}

			$this->date_field = $date_field;
		}

		/**
		 * Generate the data for the frontend based on the $google_data
		 */
		private function generate() {

			$google_data = $this->get_google_data();

			foreach ( $google_data AS $timestamp => $value ) {
				$this->add_data( $value );
				$this->add_mapping($timestamp);
			}
		}

		/**
		 * Getting the saved Google data
		 *
		 * @return array
		 */
		private function get_google_data() {
			return Yoast_GA_Dashboards_Data::get($this->graph_type, $this->start_date, $this->end_date);
		}

		/**
		 * Adding value to data property
		 *
		 * x is position on x-axis, always starting from 0
		 * y is the value of that point.
		 *
		 * @param $value
		 */
		private function add_data( $value ) {
			static $current_x = 0;

			$this->data[] = array(
				'x' => $current_x,
				'y' => $value
			);

			$current_x++;
		}

		/**
		 * Add date field to the mapping
		 *
		 * Key will be auto numbered by PHP, starting with 0, the key will always point to the the x in x-axis
		 * The value will be always the value that should be displayed.
		 *
		 * @param $timestamp
		 */
		private function add_mapping($timestamp) {
			$this->mapping[] = date( $this->date_field, $timestamp );
		}

	}
}
