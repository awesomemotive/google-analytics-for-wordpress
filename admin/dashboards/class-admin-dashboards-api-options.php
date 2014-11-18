<?php

if ( ! class_exists( 'Yoast_GA_Dashboards_Api_Options' ) ) {

	class Yoast_GA_Dashboards_Api_Options {

		/**
		 * Store this instance
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Construct on the dashboards class for GA
		 */
		public function __construct() {
			$this->set_options();
		}

		/**
		 * Get the instance
		 *
		 * @return Yoast_GA_Dashboards
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Set the API options
		 */
		public function set_options() {
			$this->options = Yoast_Google_Analytics::instance()->get_options();

			if ( isset( $this->options['ga_oauth']['access_token']['oauth_token'] ) && isset( $this->options['ga_oauth']['access_token']['oauth_token_secret'] ) ) {
				$this->access_token = $this->options['ga_oauth']['access_token'];
			}
		}

		/**
		 * Get the API options
		 *
		 * @return mixed
		 */
		public function get_options() {
			return $this->options;
		}

		/**
		 * Get the access token from the options API, false on fail
		 *
		 * @return bool
		 */
		public function get_access_token() {
			if ( ! empty( $this->access_token ) ) {
				return $this->access_token;
			}
			else{
				return false;
			}
		}

	}

}