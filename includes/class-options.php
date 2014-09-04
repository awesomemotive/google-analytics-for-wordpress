<?php

if ( ! class_exists( 'Yoast_GA_Options' ) ) {

	class Yoast_GA_Options {

		private $options;

		/**
		 * Constructor for the options
		 */
		public function __construct() {
			$this->options = $this->get_options();

			if ( ! isset( $this->options['ga_general']['version'] ) || $this->options['ga_general']['version'] < GAWP_VERSION ) {
				$this->upgrade();
			}
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
			$tracking_code = null;
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

		/**
		 * Upgrade the settings when settings are changed.
		 *
		 * @since 5.0.1
		 */
		private function upgrade() {
			if ( ! isset( $this->options['ga_general']['version'] ) && is_null( $this->get_tracking_code() ) ) {
				$old_options = get_option( 'Yoast_Google_Analytics' );

				$this->options['ga_general']['manual_ua_code']       = 1;
				$this->options['ga_general']['manual_ua_code_field'] = $old_options['uastring'];
				delete_option( 'Yoast_Google_Analytics' );
			}

			// 5.0.0 to 5.0.1 fix of ignore users array
			if ( ! isset( $this->options['ga_general']['version'] ) || version_compare( $this->options['ga_general']['version'], '5.0.1', '<' ) ) {
				if ( ! is_array( $this->options['ga_general']['ignore_users'] ) ) {
					$this->options['ga_general']['ignore_users'] = (array) $this->options['ga_general']['ignore_users'];
				}
			}

			// Set to the current version now that we've done all needed upgrades
			$this->options['ga_general']['version'] = GAWP_VERSION;

			update_option( 'yst_ga', $this->options );
		}
	}

	global $Yoast_GA_Options;
	$Yoast_GA_Options = new Yoast_GA_Options();

}