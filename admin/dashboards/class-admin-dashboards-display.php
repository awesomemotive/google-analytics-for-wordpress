<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Display' ) ) {

	class Yoast_GA_Dashboards_Display {

		/**
		 * Property for holding instance of itself
		 *
		 * @var Yoast_Plugin_Conflict
		 */
		protected static $instance;

		/**
		 * Container for holding the setted dashboards
		 *
		 * @var array
		 */
		protected $dashboards = array();

		/**
		 * @var array The dashboard types which can be used
		 */
		protected $dashboard_types = array( 'graph', 'table' );

		/**
		 * @var array For each dashboard type there will be created a driver that will be stored in this property
		 */
		protected $drivers = array();

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
			foreach ( $this->dashboard_types AS $dashboard_type ) {
				if ( ! $this->driver_exists( $dashboard_type ) ) {
					$this->create_driver( $dashboard_type );
				}
			}
		}

		/**
		 * Get the driver from property, this->drivers
		 *
		 * If driver doesn't exist, it will be created first
		 *
		 * @param string $dashboard_type - The name of the driver that will be returned
		 *
		 * @return object
		 */
		private function driver( $dashboard_type ) {

			if ( ! $this->driver_exists( $dashboard_type ) ) {
				$this->create_driver( $dashboard_type );
			}

			return $this->drivers[$dashboard_type];
		}

		/**
		 * Adding dashboards to $this->dashboard en register them to the driver by $this->register
		 *
		 * @param array $dashboards
		 */
		public function add_dashboards( $dashboards ) {
			// Save all dashboards to property - for future use
			$this->dashboards = array_merge( $this->dashboards, $dashboards );

			$this->register( $dashboards );
		}

		/**
		 * Register dashboards to the drivers
		 *
		 * @param array $dashboards
		 */
		private function register( $dashboards ) {
			foreach ( $dashboards AS $dashboard_name => $dashboard_settings ) {
				if ( ! empty( $dashboard_settings['type'] ) ) {
					$this->driver( $dashboard_settings['type'] )->register( $dashboard_name, $dashboard_settings );
				}
			}
		}

		/**
		 * Displaying the $dashboards on the screen. If $dashboards isn't given it will display all registered
		 * dashboards
		 *
		 * @param array $dashboards
		 */
		public function display( $dashboards = array(), $tab ) {

			$dashboards_to_show = $this->get_dashboards_to_display( $dashboards );

			foreach ( $dashboards_to_show AS $dashboard_name => $dashboard_settings ) {
				if ( !empty($dashboard_settings['tab']) && $dashboard_settings['tab'] === $tab ) {
					$this->driver( $dashboard_settings['type'] )->display( $dashboard_name );
				}
			}
		}

		/**
		 * Gets an array of all dashboard that should be displayed
		 *
		 * @param array $dashboards
		 *
		 * @return array
		 */
		protected function get_dashboards_to_display( $dashboards ) {

			if ( ! empty( $dashboards ) ) {
				$return = array();
				foreach ( $dashboards AS $dashboard ) {
					$return[$dashboard] = $this->dashboards[$dashboard];
				}

				return $return;
			} else {
				return $this->dashboards;
			}

		}

		/**
		 * Check if given $dashboard_type exists and if it's an object
		 *
		 * @param string $dashboard_type
		 *
		 * @return bool
		 */
		protected function driver_exists( $dashboard_type ) {
			return array_key_exists( $dashboard_type, $this->drivers ) && is_object( $this->drivers[$dashboard_type] );
		}

		/**
		 * Creates a driver based on given $dashboard_type
		 *
		 * @param $dashboard_type
		 */
		protected function create_driver( $dashboard_type ) {
			$driver_class                   = 'Yoast_GA_Dashboards_' . ucfirst( $dashboard_type );
			$this->drivers[$dashboard_type] = new $driver_class();
		}

	}

}