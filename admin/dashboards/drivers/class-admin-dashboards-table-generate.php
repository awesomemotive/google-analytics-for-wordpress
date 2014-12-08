<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Table_Generate' ) ) {

	class Yoast_GA_Dashboards_Table_Generate extends Yoast_GA_Dashboards_Driver_Generate {

		protected $dimension_id;

		/**
		 * Construct will set all values and generate the date for response
		 */
		public function __construct() {
			parent::__construct();

			$this->set_dimension_id();

			$this->generate();
		}


		/**
		 * Putting $this->data and $this->mapping and give them back as a json encoded string
		 *
		 * @return string
		 */
		public function get_json() {

			$return = array(
				'data' => $this->data
			);

			return json_encode( $return );
		}

		private function set_dimension_id() {
			$this->dimension_id = filter_input( INPUT_GET, 'dimension_id' );

			if ( ! empty( $this->dimension_id ) ) {
				$this->graph_type = 'ga:dimension' . $this->dimension_id;
			} else {
				$this->graph_type = $this->graph_type;
			}
		}


		/**
		 * Generate the data for the frontend based on the $google_data
		 */
		private function generate() {

			$google_data = $this->get_google_data();

			foreach ( $google_data AS $key => $values ) {
				$this->add_data( $values );
			}

		}

		/**
		 * Filtering the current data to eliminate all values which are not in given period
		 *
		 * @param integer $google_data
		 *
		 * @return integer
		 */
		protected function filter_google_data( $google_data ) {
			return $google_data['value'];
		}

		/**
		 * Adding value to data property
		 *
		 * x is position on x-axis, always starting from 0
		 * y is the value of that point.
		 *
		 * @param integer $value
		 */
		private function add_data( $values ) {
			$this->data[] = $values;
		}

	}
}
