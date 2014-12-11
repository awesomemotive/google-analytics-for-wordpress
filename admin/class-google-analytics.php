<?php


if ( ! class_exists( 'Yoast_Google_Analytics', false ) ) {

	class Yoast_Google_Analytics {

		private $http_response_code;

		private $access_token;
		private $secret;

		private $option_name = 'yst_ga_api';
		private $options = array();

		private static $instance = null;

		private $client;

		protected function __construct() {

			if ( is_null( self::$instance ) ) {
				self::$instance = $this;
			}

			$this->options = $this->get_options();

			// Setting the client
			$this->set_client();

			$response = $this->do_request( 'https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties' );

		}

		/**
		 * Getting the instance object
		 *
		 * This method will return the instance of itself, if instance not exists, becauses of it's called for the first
		 * time, the instance will be created.
		 *
		 * @return null|Yoast_Google_Analytics
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		protected function set_client() {
			$config = array(
				'application_name' => "Google Analytics for Wordpress",
				'client_id'        => '709980676664-djogli4so02l820q0vegovol4nf2p9f9.apps.googleusercontent.com',
				'client_secret'    => 'quP3lv-GQxSCCxJ5k0reu50g',
			);

			$config = apply_filters( 'yst-ga-filter-ga-config', $config );

			$this->client = new Yoast_Google_Analytics_Client( $config );


		}

		public function authenticate( $authentication_code = null ) {
			$this->client->authenticate_client( $authentication_code );
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
		 * Getting the analytics profiles
		 *
		 * Doing the request to the Google analytics API and if there is a response, parses this response and return its
		 * array
		 *
		 * @return array
		 */
		public function get_profiles() {
			$accounts = $this->format_profile_call(
				$this->do_request( 'https://www.googleapis.com/analytics/v3/management/accountSummaries' )
			);

			if (  is_array( $accounts ) ) {
				$this->save_profile_response( $accounts );

				return $accounts;
			}

			return array();
		}

		/**
		 * Format the accounts request
		 *
		 * @param $response
		 *
		 * @return mixed
		 */
		private function format_profile_call( $response ) {

			if ( isset( $response['response']['code'] ) && $response['response']['code'] == 200 ) {
				if ( is_array( $response['body']['items'] ) ) {
					$accounts = array();

					foreach ( $response['body']['items'] as $item ) {

						$profiles = array();
						foreach ( $item['webProperties'] AS $property ) {
							foreach($property['profiles'] AS $key => $profile) {
								$property['profiles'][$key]['name'] = $profile['name'] . ' (' . $property['id'] . ')';
							}

							$profiles = array_merge( $profiles, $property['profiles'] );
						}

						$accounts[$item['id']] = array(
							'id'          => $item['id'],
							'parent_name' => $item['name'],
							'profiles'    => $profiles,
						);

					}

					return $accounts;
				}
			}

			return false;
		}

		/**
		 * Doing request to Google Analytics
		 *
		 * This method will do a request to google and get the response code and body from content
		 *
		 * @param string $target_url
		 *
		 * @return array|null
		 */
		protected function do_request( $target_request_url ) {

			$response = $this->client->do_request( $target_request_url );

			if ( !empty( $response ) ) {
				return array(
					'response' => array( 'code' => '200' ),
					'body'     => json_decode( $response->getResponseBody(), true ),
				);
			}

		}


		/**
		 * Saving profile response in options
		 *
		 * @param $response
		 */
		protected function save_profile_response( $accounts ) {
			$this->options['ga_api_response_accounts'] = $accounts;

			$this->update_options();
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
		 * Getting the options bases on $this->option_name from the database
		 *
		 * @return mixed
		 */
		public function get_options() {
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