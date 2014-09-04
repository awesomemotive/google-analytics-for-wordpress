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

				if ( isset( $old_options ) && is_array( $old_options ) ) {
					if ( isset( $old_options['uastring'] ) && '' !== trim( $old_options['uastring'] ) ) {
						// Save UA as manual UA, instead of saving all the old GA crap
						$this->options['ga_general']['manual_ua_code']       = 1;
						$this->options['ga_general']['manual_ua_code_field'] = $old_options['uastring'];
					}

					// Other settings
					$this->options['ga_general']['allow_anchor']               = $old_options['allowanchor'];
					$this->options['ga_general']['add_allow_linker']           = $old_options['allowlinker'];
					$this->options['ga_general']['anonymous_data']             = $old_options['anonymizeip'];
					$this->options['ga_general']['track_outbound']             = $old_options['trackoutbound'];
					$this->options['ga_general']['track_internal_as_outbound'] = $old_options['internallink'];
					$this->options['ga_general']['track_internal_as_label']    = $old_options['internallinklabel'];
					$this->options['ga_general']['extensions_of_files']        = $old_options['dlextensions'];

				}

				delete_option( 'Yoast_Google_Analytics' );
			}

			// 5.0.0 to 5.0.1 fix of ignore users array
			if ( ! isset( $this->options['ga_general']['version'] ) || version_compare( $this->options['ga_general']['version'], '5.0.1', '<' ) ) {
				if ( ! is_array( $this->options['ga_general']['ignore_users'] ) ) {
					$this->options['ga_general']['ignore_users'] = (array) $this->options['ga_general']['ignore_users'];
				}
			}

			// Check is API option already exists - if not add it
			$yst_ga_api = get_option('yst_ga_api');
			if($yst_ga_api === false) {
				add_option( 'yst_ga_api', array(), '', 'no' );
			}

			// Set to the current version now that we've done all needed upgrades
			$this->options['ga_general']['version'] = GAWP_VERSION;

			update_option( 'yst_ga', $this->options );
		}
	}

	global $Yoast_GA_Options;
	$Yoast_GA_Options = new Yoast_GA_Options();

}