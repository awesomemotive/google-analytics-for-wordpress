<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Graph' ) ) {

	class Yoast_GA_Dashboards_Graph {

		/**
		 * Property for holding instance of itself
		 *
		 * @var Yoast_Plugin_Conflict
		 */
		protected static $instance;

		protected $dashboards = array();

		protected $ajax_request = 'yoast_dashboard_graphdata';

		/**
		 * For the use of singleton pattern. Create instance of itself and return his instance
		 *
		 * @return Yoast_GA_Dasboards_Graph
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Protected constructor to prevent creating a new instance of the
		 * *Singleton* via the `new` operator from outside of this class.
		 */
		protected function __construct() {


		}

		public function initialize_ajax() {
			add_action( 'wp_ajax_' . $this->ajax_request, array('Yoast_GA_Dashboards_Graph', 'get_graph_data') );
		}

		public static function get_graph_data() {

			// @todo: Get this data from Peter
			$data = array();

			// Calculate period, in this case for last month
			$period = Yoast_GA_Dashboard_Period::calculate('lastmonth', time());

			// Combine data and period data
			$return = Yoast_GA_Dashboard_Mapper::combine($data, $period);

			echo json_encode($return);
			exit;
		}


		public function display() {

			foreach ( $this->dashboards AS $dashboard => $settings ) {
				require "views/graph.php";
			}

		}


		public function register( $dashboard, $values = false ) {

			if ( ! is_array( $dashboard ) ) {
				$dashboard = array( $dashboard => $values );
			}

			$this->dashboards = array_merge( $this->dashboards, $dashboard );

		}



	}
}
