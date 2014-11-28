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

		/**
		 * Getting the instance object
		 *
		 * This method will return the instance of itself, if instance not exists, becauses of it's called for the first
		 * time, the instance will be created.
		 *
		 * @return null|Yoast_Google_Analytics
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Is there a token set
		 *
		 * Checks in options whether there is a token set or not. Will return true if token is set
		 *
		 * @return bool
		 */
		public function has_token() {
			return ! empty( $this->options['ga_token'] );
		}

		/**
		 * Connect with google analytics
		 *
		 * @param bool $token
		 * @param bool $verifier
		 *
		 * @return string
		 */
		public function authenticate( $token = false, $verifier = false ) {

			if ( ! empty( $token ) && ! empty ( $verifier ) ) {
				if ( isset( $this->options['ga_oauth']['oauth_token'] ) && $this->options['ga_oauth']['oauth_token'] == $token ) {
					$this->get_access_token( $verifier );
				}
			} else {
				$authorize_url = $this->get_authorize_url();

				return $authorize_url;
			}

		}

		/**
		 * Getting the analytics profiles
		 *
		 * Doing the request to the Google analytics API and if there is a response, parses this response and return its
		 * array
		 *
		 * @return array
		 */
		public function get_profiles() {
			$return   = array();
			$accounts = $this->format_accounts_call( $this->do_request( 'https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties', 'https://www.googleapis.com/auth/analytics.readonly' ) );

			$response = $this->do_request( 'https://www.googleapis.com/analytics/v2.4/management/accounts/~all/webproperties/~all/profiles', 'https://www.googleapis.com/auth/analytics.readonly' );

			if ( $response ) {
				$this->save_profile_response( $response, $accounts );

				$return = $this->parse_profile_response( $response );
			}

			return $return;
		}

		/**
		 * Format the accounts request
		 *
		 * @param $response
		 *
		 * @return array
		 */
		private function format_accounts_call( $response ) {
			if ( isset( $response['response']['code'] ) && $response['response']['code'] == 200 ) {
				$body = json_decode( $response['body'] );

				if ( is_array( $body->items ) ) {
					$accounts = array();

					foreach ( $body->items as $item ) {
						$accounts[(string) $item->id] = (string) $item->name;
					}

					return $accounts;
				}
			}

			return array();
		}

		/**
		 * Getting a access token
		 *
		 * With this token a reconnection to Google Analytics is possible
		 *
		 * @param string $verifier
		 */
		protected function get_access_token( $verifier ) {
			$gdata = $this->get_gdata(
				'https://www.google.com/analytics/feeds/',
				$this->options['ga_oauth']['oauth_token'],
				$this->options['ga_oauth']['oauth_token_secret']
			);

			$access_token = $gdata->get_access_token( $verifier );

			$this->options['ga_oauth']['access_token'] = $access_token;
			$this->options['ga_token']                 = $access_token['oauth_token'];

			unset( $this->options['ga_oauth']['oauth_token'] );
			unset( $this->options['ga_oauth']['oauth_token_secret'] );

			$this->update_options();
		}

		/**
		 * Getting the URL to authenticate the use
		 *
		 * @return string
		 */
		protected function get_authorize_url() {
			$gdata         = $this->get_gdata( 'https://www.google.com/analytics/feeds/' );
			$request_token = $this->get_request_token( $gdata );

			if ( is_array( $this->options ) ) {
				unset( $this->options['ga_token'] );
				if ( is_array( $this->options['ga_oauth'] ) ) {
					unset( $this->options['ga_oauth']['access_token'] );
				}
			}

			$this->options['ga_oauth']['oauth_token']        = $request_token['oauth_token'];
			$this->options['ga_oauth']['oauth_token_secret'] = $request_token['oauth_token_secret'];

			$this->update_options();

			return $gdata->get_authorize_url( $request_token );
		}

		/**
		 * Get the request token from Google Analytics
		 *
		 * @param WP_Gdata $gdata
		 *
		 * @return array
		 */
		protected function get_request_token( $gdata ) {
			$oauth_callback = add_query_arg( array( 'ga_oauth_callback' => 1 ), menu_page_url( 'yst_ga_settings', false ) );
			$request_token  = $gdata->get_request_token( $oauth_callback );

			return $request_token;
		}

		/**
		 * Doing request to Google Analytics
		 *
		 * This method will do a request to google and get the response code and body from content
		 *
		 * @param string $target_url
		 * @param string $scope
		 *
		 * @return array|null
		 */
		protected function do_request( $target_url, $scope ) {
			$gdata     = $this->get_gdata( $scope, $this->access_token, $this->secret );
			$response  = $gdata->get( $target_url );
			$http_code = wp_remote_retrieve_response_code( $response );
			$response  = wp_remote_retrieve_body( $response );

			if ( $http_code == 200 ) {
				return array(
					'response' => array( 'code' => $http_code ),
					'body'     => $response,
				);
			}
		}

		/**
		 * Getting WP_GData object
		 *
		 * If not available include class file and create an instance of WP_GDAta
		 *
		 * @param string $scope
		 * @param null   $token
		 * @param null   $secret
		 *
		 * @return WP_GData
		 */
		protected function get_gdata( $scope, $token = null, $secret = null ) {
			$args = array(
				'scope'              => $scope,
				'xoauth_displayname' => 'Google Analytics by Yoast',
			);

			$gdata = new WP_GData( $args, $token, $secret );

			return $gdata;
		}

		/**
		 * Saving profile response in options
		 *
		 * @param $response
		 */
		protected function save_profile_response( $response, $accounts ) {
			$this->options['ga_api_response']          = $response;
			$this->options['ga_api_response_accounts'] = $accounts;

			$this->update_options();
		}

		/**
		 * Parsing the profile response
		 *
		 * Create XML_Reader for the response. Check if there are entries available. Check which link is used and parsing the entries.
		 * If there are entries parse, then sort them and rebuild array
		 *
		 * @return array
		 */
		protected function parse_profile_response() {
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
						$tmp_array = array(
							'id'   => $ga_account['ua'],
							'name' => $ga_account['title'] . ' (' . $ga_account['ua'] . ')',
						);

						if ( isset( $this->options['ga_api_response_accounts'][$ga_account['ua']] ) ) {
							$tmp_array['parent_name'] = $this->options['ga_api_response_accounts'][$ga_account['ua']];
						}

						$return[] = $tmp_array;
					}
				}
			} catch ( Exception $e ) {

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
		protected function sort_profiles( $a, $b ) {
			return strcmp( $a['title'], $b['title'] );
		}


		/**
		 * Parses the entries
		 *
		 * The keys can be different for some types of responses, so there are two params which defines the target keys
		 *
		 * @param SimpleXMLElement $entries
		 * @param integer          $ua_key
		 * @param integer          $title_key
		 *
		 * @return array
		 */
		protected function parse_entries( $entries, $ua_key, $title_key ) {
			$return = array();

			foreach ( $entries AS $entry ) {
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

		/**
		 * Setting the token for Google Analytics api
		 */
		protected function set_access_token() {
			$this->access_token = $this->options['ga_oauth']['access_token']['oauth_token'];
		}

		/**
		 * Setting the token secret for Google Analytics API
		 */
		protected function set_secret() {
			$this->secret = $this->options['ga_oauth']['access_token']['oauth_token_secret'];
		}

		/**
		 * Getting the options bases on $this->option_name from the database
		 *
		 * @return mixed
		 */
		protected function get_options() {
			return get_option( $this->option_name );
		}

		/**
		 * Updating the options based on $this->option_name and the internal property $this->options
		 */
		protected function update_options() {
			update_option( $this->option_name, $this->options );
		}

	}

}