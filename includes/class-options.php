<?php

if ( ! class_exists( 'Yoast_GA_Options' ) ) {

	class Yoast_GA_Options {

		private $options;

		/**
		 * Constructor for the options
		 */
		public function __construct() {
			$this->options = $this->get_options();
		}

		/**
		 * Return the Google Analytics options
		 *
		 * @return mixed|void
		 */
		public function get_options() {
			return get_option( 'yst_ga' );
		}

		/**
		 * Get the Google Analytics tracking code for this website
		 *
		 * @return null
		 */
		public function get_tracking_code() {
			$tracking_code = NULL;
			$options       = $this->options;
			$options       = $options['ga_general'];

			if ( ! empty( $options['analytics_profile'] ) ) {
				$tracking_code = $options['analytics_profile'];
			}

			if ( ! empty( $options['manual_ua_code_field'] ) && ! empty( $options['manual_ua_code'] ) ) {
				$tracking_code = $options['manual_ua_code_field'];
			}

			return $tracking_code;
		}

	}

	global $Yoast_GA_Options;
	$Yoast_GA_Options = new Yoast_GA_Options();

}