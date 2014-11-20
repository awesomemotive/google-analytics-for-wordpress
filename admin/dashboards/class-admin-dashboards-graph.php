<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Graph' ) ) {

	class Yoast_GA_Dashboards_Graph {

		/**
		 * Property for holding instance of itself
		 *
		 * @var Yoast_Plugin_Conflict
		 */
		protected static $instance;

		/**
		 * Container for holding setted dashboards
		 *
		 * @var array
		 */
		protected $dashboards = array();

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

		/**
		 * Setting hook for doing ajax request
		 *
		 */
		public function initialize_ajax() {
			add_action( 'wp_ajax_yoast_dashboard_graphdata', array( 'Yoast_GA_Dashboards_Graph', 'get_graph_data' ) );
		}

		/**
		 * Displaying the graph on screen
		 *
		 */
		public function display() {
			foreach ( $this->dashboards AS $dashboard => $settings ) {
				require 'views/graph.php';
			}
		}

		/**
		 * Register a dashboard with settings.
		 *
		 * Dashboard can contain multiple dashboard-types. If so, $values shouldn't be passed and $dashboard argument
		 * should be key->value, key = dashboard and value should contain the values
		 *
		 * Given arguments will be marge with objects property dashboards
		 *
		 * @param mixed $dashboard
		 * @param mixed $values
		 */
		public function register( $dashboard, $values = false ) {

			if ( ! is_array( $dashboard ) ) {
				$dashboard = array( $dashboard => $values );
			}

			$this->dashboards = array_merge( $this->dashboards, $dashboard );

		}

		/**
		 * Method which will be called by AJAX
		 *
		 * Will echo json for graph
		 */
		public static function get_graph_data() {

			$graph = new Yoast_GA_Dashboards_Graph_Generate();
			$json  = $graph->get_json();

			echo $json;
			die();
		}

	}
}
