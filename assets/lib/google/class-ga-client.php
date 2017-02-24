<?php
/**
 * Class MonsterInsights_GA_Client
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class MonsterInsights_GA_Client extends MonsterInsights_GA_Lib_Client {

	/**
	 * @var string
	 */
	protected $option_refresh_token;

	/**
	 * @var string
	 */
	protected $option_access_token;

	/**
	 * @var string
	 */
	protected $http_response_code;

	/**
	 * @var string
	 */
	public $client; // used for reporting purposes

	/**
	 * Initialize the config and refresh the token
	 *
	 * @param array  $config
	 * @param string $option_prefix
	 */
	public function __construct( $google_settings, $client_name ) {

		// Initialize the config to set all properties properly.
		$config = $this->init_config( $google_settings );

		parent::__construct( $config );

		$this->client = new MonsterInsights_GA_Lib_Client( $config );

		if ( ! empty( $google_settings['scopes'] ) ) {
			$this->setScopes( $google_settings['scopes'] );
		}

		$this->setAccessType( 'offline' );

		// Client name will be `pro`, `lite`, or `support`
		$this->option_refresh_token = 'monsterinsights_' . $client_name . '_refresh_token';
		$this->option_access_token  = 'monsterinsights_' . $client_name . '_access_token';

		// Let's get an access token if we've got a refresh token.
		$this->refresh_tokens();
	}

	/**
	 * Authenticate the client. If $authorization_code is empty it will lead the user through the validation process of
	 * Google. If set it will be get the access token for current session and save the refresh_token for future use
	 *
	 * @param mixed $authorization_code
	 *
	 * @return bool
	 */
	public function authenticate_client( $authorization_code = null ) {
		static $has_retried;

		// Authenticate client.
		try {
			$this->authenticate( $authorization_code );

			// Get access response.
			$response = $this->getAccessToken();

			// Check if there is a response body.
			if ( ! empty( $response ) ) {
				$response = json_decode( $response );

				if ( is_object( $response ) ) {
					// Save the refresh token.
					$this->save_refresh_token( $response->refresh_token );
					$this->save_initial_access_token( $response );
					return true;
				}
			}
		} catch ( MonsterInsights_GA_Lib_Auth_Exception $exception ) {
			// If there aren't any attempts before, try again and set attempts on true, to prevent further attempts.
			if ( empty( $has_retried ) ) {
				$has_retried = true;

				return $this->authenticate_client( $authorization_code );
			}
			//error_log( $exception ); // @todo proper logging and handling of already used oauth token
		}

		return false;
	}

	/**
	 * Doing a request to the API
	 *
	 * @param string $target_request_url
	 * @param bool   $decode_response
	 * @param string $request_method
	 *
	 * @return array
	 */
	public function do_request( $target_request_url, $decode_response = false, $request_method = 'GET', $body = array() ) {
		// Get response.
		$request  = new MonsterInsights_GA_Lib_Http_Request( $target_request_url, $request_method );
		if ( ! empty( $body ) ) {
			$request->setPostBody( $body ); // used exclusively for auth profiles
		}

		$response = $this->getAuth()->authenticatedRequest( $request );

		// Storing the response code.
		$this->http_response_code = $response->getResponseHttpCode();

		if ( $decode_response ) {
			return $this->decode_response( $response );
		}

		return $response;
	}

	/**
	 * Decode the JSON response
	 *
	 * @param object $response
	 * @param int    $accepted_response_code
	 *
	 * @return mixed
	 */
	public function decode_response( $response, $accepted_response_code = 200 ) {
		if ( $accepted_response_code === $response->getResponseHttpCode() ) {
			return json_decode( $response->getResponseBody() );
		}
	}

	/**
	 * Getting the response code, saved from latest request to Google
	 *
	 * @return mixed
	 */
	public function get_http_response_code() {
		return $this->http_response_code;
	}

	/**
	 * Clears the options and revokes the token
	 */
	public function clear_data() {
		$this->revokeToken();
		delete_option( $this->option_access_token );
		delete_option( $this->option_refresh_token );
	}

	/**
	 * Moves test options to live
	 */
	public function move_test_to_live() {
		$new_option_access_token  = str_replace( 'test_', '', $this->option_access_token );
		$new_option_refresh_token = str_replace( 'test_', '', $this->option_refresh_token );

		update_option( $new_option_access_token,  get_option( $this->option_access_token,  '' ) );
		update_option( $new_option_refresh_token, get_option( $this->option_refresh_token, '' ) );

		delete_option( $this->option_access_token );
		delete_option( $this->option_refresh_token );
	}

	/**
	 * Check if user is authenticated
	 *
	 * @return bool
	 */
	public function is_authenticated() {
		$has_refresh_token    = ( $this->get_refresh_token() !== '' );
		$access_token_expired = $this->access_token_expired();

		return $has_refresh_token && ! $access_token_expired;
	}

	/**
	 * Initialize the config, will merge given config with default config to be sure all settings are available
	 *
	 * @param array $settings
	 */
	protected function init_config( array $google_config ) {

		// Load MI io
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'assets/lib/google/class-ga-io.php';

		// Load MI cache
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'assets/lib/google/class-ga-cache.php';

		// Load MI logger
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'assets/lib/google/class-ga-logger.php';

		$config = new MonsterInsights_GA_Lib_Config();

		$config->setIoClass( 'MonsterInsights_GA_IO' );
		$config->setCacheClass( 'MonsterInsights_GA_Cache' );
		$config->setLoggerClass( 'MonsterInsights_GA_Logger' );

		if ( ! empty( $google_config['application_name'] ) ) {
			$config->setApplicationName( $google_config['application_name'] );
		}

		if ( ! empty( $google_config['client_id'] ) ) {
			$config->setClientId( $google_config['client_id'] );
		}

		if ( ! empty( $google_config['client_secret'] ) ) {
			$config->setClientSecret( $google_config['client_secret'] );
		}

		if ( ! empty( $google_config['redirect_uri'] ) ) {
			$config->setRedirectUri( $google_config['redirect_uri'] );
		}

		return $config;
	}

	/**
	 * Refreshing the tokens
	 */
	protected function refresh_tokens() {
		if ( ( $refresh_token = $this->get_refresh_token() ) !== '' && $this->access_token_expired() ) {
			try {
				// Refresh the token.
				$this->refreshToken( $refresh_token );

				$response = $this->getAuth()->getAccessToken();
				$response = json_decode( $response );

				// Check response and if there is an access_token.
				if ( ! empty( $response ) && ! empty ( $response->access_token ) ) {
					$this->save_access_token( $response );
				}
			}
			catch ( Exception $e ) {
				return false;
			}
		}
	}

	/**
	 * Save the refresh token
	 *
	 * @param string $refresh_token
	 */
	protected function save_refresh_token( $refresh_token ) {
		update_option( $this->option_refresh_token, trim( $refresh_token ) );
	}

	/**
	 * Return refresh token
	 *
	 * @return string
	 */
	protected function get_refresh_token() {
		return get_option( $this->option_refresh_token, '' );
	}


	/**
	 * Saving the access token as an option for further use till it expires.
	 *
	 * @param array $response
	 */
	protected function save_initial_access_token( $response ) {
		update_option(
			$this->option_access_token,
			array(
				'refresh_token' => $response->refresh_token,
				'access_token'  => $response->access_token,
				'expires'       => current_time( 'timestamp' ) + $response->expires_in,
				'expires_in'    => $response->expires_in,
				'created'       => $response->created,
			)
		);

		try {
			$this->setAccessToken( json_encode( $response ) );
		} catch ( MonsterInsights_GA_Lib_Auth_Exception $exception ) {

		}
	}

	/**
	 * Saving the access token as an option for further use till it expires.
	 *
	 * @param array $response
	 */
	protected function save_access_token( $response ) {
		update_option(
			$this->option_access_token,
			array(
				'refresh_token' => $this->get_refresh_token(),
				'access_token'  => $response->access_token,
				'expires'       => current_time( 'timestamp' ) + $response->expires_in,
				'expires_in'    => $response->expires_in,
				'created'       => $response->created,
			)
		);

		try {
			$this->setAccessToken( json_encode( $response ) );
		} catch ( MonsterInsights_GA_Lib_Auth_Exception $exception ) {

		}
	}

	/**
	 * Check if current access token is expired.
	 *
	 * @return bool
	 */
	private function access_token_expired() {
		$access_token = $this->get_access_token();

		if ( empty( $access_token ) || empty( $access_token['expires'] ) || current_time( 'timestamp' ) >= $access_token['expires'] ) {
			return true;
		}

		try {
			$this->setAccessToken( json_encode( $access_token ) );
		} catch ( MonsterInsights_GA_Lib_Auth_Exception $exception ) {
			return true;
		}
		return false;
	}

	/**
	 * Getting the current access token from the options
	 *
	 * @return mixed
	 */
	public function get_access_token() {
		return get_option( $this->option_access_token, array( 'access_token' => false, 'expires' => 0 ) );
	}

}