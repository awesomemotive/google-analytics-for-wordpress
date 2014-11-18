<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Graph_Generate' ) ) {

	class Yoast_GA_Dashboards_Graph_Generate {

		private $graph_type;

		private $period;

		private $end_date;

		private $start_date;

		private $date_field = 'j';


		private $data    = array();
	    private $mapping = array();


		/**
		 * Protected constructor to prevent creating a new instance of the
		 * *Singleton* via the `new` operator from outside of this class.
		 */
		public function __construct() {

			$this->set_graph_type();
			$this->set_period();

			$this->set_end_date();
			$this->set_start_date();

			$this->set_date_field();


			$this->generate();

		}

		public function get_json() {

			$return = array(
				'data'    => $this->data,
				'mapping' => $this->mapping
			);

			return json_encode( $return );
		}

		private function set_graph_type() {
			$graph_id         = filter_input( INPUT_GET, 'graph_id' );
			$graph_type       = substr( 'graph-', $graph_id );
			$this->graph_type = $graph_type;
		}

		private function set_period() {
			$this->period = 'lastmonth';//filter_input( INPUT_GET, 'period' );
		}

		private function set_end_date() {
			$this->end_date = time();
		}

		private function set_start_date() {

			switch ( $this->period ) {
				default:
				case 'lastmonth' :
					$start_date = strtotime( '-1 month', $this->end_date );
					break;

			}

			$this->start_date = $start_date;
		}

		private function set_date_field() {
			switch ( $this->period ) {
				case 'lastmonth' :
					$date_field = 'j';
					break;
			}

			$this->date_field = $date_field;
		}

		private function get_google_data() {
			return Yoast_GA_Dashboards_Data::get($this->graph_type, $this->start_date, $this->end_date);
		}

		private function add_data( $value ) {
			static $current_x = 0;

			$this->data[] = array(
				'x' => $current_x,
				'y' => $value
			);

			$current_x++;
		}

		private function add_mapping($timestamp) {
			$this->mapping[] = date( $this->date_field, $timestamp );
		}

		private function generate() {

			$google_data = $this->get_google_data();

			foreach ( $google_data AS $timestamp => $value ) {
				$this->add_data( $value );
				$this->add_mapping($timestamp);
			}
		}

	}
}
