<?php


if ( ! class_exists( 'Yoast_Google_Analytics', false ) ) {

	class Yoast_Google_Analytics {

		private $access_token;
		private $secret;

		private $option_name = 'yst_ga_api';
		private $options = array();

		private static $instance = null;

		public function __construct() {

			if ( is_null( self::$instance ) ) {
				self::$instance = $this;
			}

			$this->options = $this->get_options();

			if ( $this->has_token() ) {
				$this->set_access_token();
				$this->set_secret();
			}
		}

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function has_token() {
			return ! empty( $this->options['ga_token'] );
		}

		public function connect( $token = false, $verifier = false ) {

			$args = array(
				'scope'              => 'https://www.google.com/analytics/feeds/',
				'xoauth_displayname' => 'Google Analytics by Yoast',
			);

			if ( ! empty( $token ) && ! empty ( $verifier ) ) {
				if ( isset( $this->options['ga_oauth']['oauth_token'] ) && $this->options['ga_oauth']['oauth_token'] == $token ) {
					$gdata = new WP_GData(
						$args,
						$this->options['ga_oauth']['oauth_token'],
						$this->options['ga_oauth']['oauth_token_secret']
					);

					$this->options['ga_oauth']['access_token'] = $gdata->get_access_token( $verifier );
					unset( $this->options['ga_oauth']['oauth_token'] );
					unset( $this->options['ga_oauth']['oauth_token_secret'] );
					$this->options['ga_token'] = $this->options['ga_oauth']['access_token']['oauth_token'];

					$this->update_options();
				}
			} else {

			}

		}

		public function get_profiles() {
			$return   = array();
			$args     = array(
				'scope'              => 'https://www.googleapis.com/auth/analytics.readonly',
				'xoauth_displayname' => 'Google Analytics for WordPress by Yoast',
			);
			$response = $this->do_request( 'https://www.googleapis.com/analytics/v2.4/management/accounts/~all/webproperties/~all/profiles', $args );

			if ( $response ) {
				$return = $this->parse_profile_response();
			}

			return $return;
		}

		/**
		 * Sorting the array in alphabetic order
		 *
		 * @param string $a
		 * @param string $b
		 *
		 * @return int
		 */
		public function sort_profiles( $a, $b ) {
			return strcmp( $a['title'], $b['title'] );
		}

		private function do_request( $target_url, $args ) {
			$gdata     = new WP_Gdata( $args, $this->access_token, $this->secret );
			$response  = $gdata->get( $target_url );
			$http_code = wp_remote_retrieve_response_code( $response );
			$response  = wp_remote_retrieve_body( $response );

			if ( $http_code == 200 ) {
				$this->options['ga_api_response'] = array(
					'response' => array( 'code' => $http_code ),
					'body'     => $response,
				);

				$this->update_options();

				return true;
			} else {
				return false;
			}

		}

		private function parse_profile_response() {
			$return = array();

			try {
				$xml_reader = new SimpleXMLElement( $this->options['ga_api_response']['body'] );

				if ( ! empty( $xml_reader->entry ) ) {

					$ga_accounts = array();

					// Check whether the feed output is the new one, first set, or the old one, second set.
					if ( $xml_reader->link['href'] == 'https://www.googleapis.com/analytics/v2.4/management/accounts/~all/webproperties/~all/profiles' ) {
						$ga_accounts = $this->parse_entries( $xml_reader->entry, 1, 2 );
					} elseif ( $xml_reader->link['href'] == 'https://www.google.com/analytics/feeds/accounts/default' ) {
						$ga_accounts = $this->parse_entries( $xml_reader->entry, 3, 2 );
					}

					if ( is_array( $ga_accounts ) ) {
						usort( $ga_accounts, array( $this, 'sort_profiles' ) );
					}

					foreach ( $ga_accounts as $key => $ga_account ) {
						$return[] = array(
							'id'   => $ga_account['ua'],
							'name' => $ga_account['title'] . ' (' . $ga_account['ua'] . ')',
						);
					}
				}
			} catch ( Exception $e ) {

			}

		}

		private function parse_entries( $entries, $ua_key, $title_key ) {
			$return = array();

			foreach ( $entries->entry AS $entry ) {
				$ns         = $entry->getNamespaces( true );
				$properties = $entry->children( $ns['dxp'] )->property;

				if ( isset ( $properties[$ua_key]->attributes()->value ) ) {
					$ua = (string) trim( $properties[$ua_key]->attributes()->value );
				}

				if ( isset ( $properties[$title_key]->attributes()->value ) ) {
					$title = (string) trim( $properties[$title_key]->attributes()->value );
				}

				if ( ! empty( $ua ) && ! empty( $title ) ) {
					$return[] = array(
						'ua'    => $ua,
						'title' => $title,
					);
				}
			}

			return $return;
		}

		private function set_access_token() {
			$this->access_token = $this->options['ga_oauth']['access_token']['oauth_token'];
		}

		private function set_secret() {
			$this->secret = $this->options['ga_oauth']['access_token']['oauth_token_secret'];
		}

		private function get_options() {
			return get_option( $this->option_name );
		}

		private function update_options() {
			update_option( $this->option_name, $this->options );
		}


	}

}