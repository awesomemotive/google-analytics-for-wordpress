<?php

if ( ! class_exists( 'Yoast_GA_Options' ) ) {

	class Yoast_GA_Options {

		public $options;

		/**
		 * Holds the settings for the GA plugin and possible subplugins
		 *
		 * @var string
		 */
		public $option_name = 'yst_ga';

		/**
		 * Holds the prefix we use within the option to save settings
		 *
		 * @var string
		 */
		public $option_prefix = 'ga_general';

		/**
		 * Holds the path to the main plugin file
		 *
		 * @var string
		 */
		public $plugin_path;

		/**
		 * Holds the URL to the main plugin directory
		 *
		 * @var string
		 */
		public $plugin_url;

		/**
		 * Constructor for the options
		 */
		public function __construct() {
			$this->options = $this->get_options();

			$this->plugin_path = plugin_dir_path( GAWP_FILE );
			$this->plugin_url  = trailingslashit( plugin_dir_url( GAWP_FILE ) );

			if ( false == $this->options ) {
				add_option( $this->option_name, $this->default_ga_values() );
				$this->options = get_option( $this->option_name );
			}

			if ( ! isset( $this->options[ $this->option_prefix ]['version'] ) || $this->options[ $this->option_prefix ]['version'] < GAWP_VERSION ) {
				$this->upgrade();
			}
		}

		/**
		 * Updates the GA option
		 *
		 * @param array $val
		 *
		 * @return bool
		 */
		public function update_option( $val ) {
			return update_option( $this->option_name, $val );
		}

		/**
		 * Return the Google Analytics options
		 *
		 * @return mixed|void
		 */
		public function get_options() {
			return get_option( $this->option_name );
		}

		/**
		 * Get the Google Analytics tracking code for this website
		 *
		 * @return null
		 */
		public function get_tracking_code() {
			$tracking_code = null;
			$options       = $this->options;
			$options       = $options[ $this->option_prefix ];

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
			if ( ! isset( $this->options[ $this->option_prefix ]['version'] ) && is_null( $this->get_tracking_code() ) ) {
				$old_options = get_option( 'Yoast_Google_Analytics' );

				if ( isset( $old_options ) && is_array( $old_options ) ) {
					if ( isset( $old_options['uastring'] ) && '' !== trim( $old_options['uastring'] ) ) {
						// Save UA as manual UA, instead of saving all the old GA crap
						$this->options[ $this->option_prefix ]['manual_ua_code']       = 1;
						$this->options[ $this->option_prefix ]['manual_ua_code_field'] = $old_options['uastring'];
					}

					// Other settings
					$this->options[ $this->option_prefix ]['allow_anchor']               = $old_options['allowanchor'];
					$this->options[ $this->option_prefix ]['add_allow_linker']           = $old_options['allowlinker'];
					$this->options[ $this->option_prefix ]['anonymous_data']             = $old_options['anonymizeip'];
					$this->options[ $this->option_prefix ]['track_outbound']             = $old_options['trackoutbound'];
					$this->options[ $this->option_prefix ]['track_internal_as_outbound'] = $old_options['internallink'];
					$this->options[ $this->option_prefix ]['track_internal_as_label']    = $old_options['internallinklabel'];
					$this->options[ $this->option_prefix ]['extensions_of_files']        = $old_options['dlextensions'];

				}

				delete_option( 'Yoast_Google_Analytics' );
			}

			// 5.0.0 to 5.0.1 fix of ignore users array
			if ( ! isset( $this->options[ $this->option_prefix ]['version'] ) || version_compare( $this->options[ $this->option_prefix ]['version'], '5.0.1', '<' ) ) {
				if ( ! is_array( $this->options[ $this->option_prefix ]['ignore_users'] ) ) {
					$this->options[ $this->option_prefix ]['ignore_users'] = (array) $this->options[ $this->option_prefix ]['ignore_users'];
				}
			}

			// Check is API option already exists - if not add it
			$yst_ga_api = get_option( 'yst_ga_api' );
			if ( $yst_ga_api === false ) {
				add_option( 'yst_ga_api', array(), '', 'no' );
			}

			// Fallback to make sure every default option has a value
			$defaults = $this->default_ga_values();
			foreach ( $defaults[ $this->option_prefix ] as $key => $value ) {
				if ( ! isset( $this->options[ $this->option_prefix ][ $key ] ) ) {
					$this->options[ $this->option_prefix ][ $key ] = $value;
				}
			}

			// Set to the current version now that we've done all needed upgrades
			$this->options[ $this->option_prefix ]['version'] = GAWP_VERSION;

			update_option( $this->option_name, $this->options );
		}

		/**
		 * Set the default GA settings here
		 * @return array
		 */
		public function default_ga_values() {
			return array(
				$this->option_prefix => array(
					'analytics_profile'          => null,
					'manual_ua_code'             => 0,
					'manual_ua_code_field'       => null,
					'track_internal_as_outbound' => null,
					'track_internal_as_label'    => null,
					'track_outbound'             => 0,
					'anonymous_data'             => 0,
					'enable_universal'           => 0,
					'demographics'               => 0,
					'ignore_users'               => array( 'editor' ),
					'anonymize_ips'              => null,
					'track_download_as'          => 'event',
					'extensions_of_files'        => 'doc,exe,js,pdf,ppt,tgz,zip,xls',
					'track_full_url'             => 'domain',
					'subdomain_tracking'         => null,
					'tag_links_in_rss'           => 0,
					'allow_anchor'               => 0,
					'add_allow_linker'           => 0,
					'custom_code'                => null,
					'debug_mode'                 => 0,
					'firebug_lite'               => 0,
				)
			);
		}
	}
}