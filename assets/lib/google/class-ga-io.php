<?php
/**
 * WP based implementation of apiIO.
 *
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class MonsterInsights_GA_IO extends MonsterInsights_GA_Lib_IO_Abstract {
	const TIMEOUT = "timeout";
	private $options = array();
	
	private static $ENTITY_HTTP_METHODS = array( "POST" => null, "PUT" => null, "DELETE" => null );
	private static $HOP_BY_HOP = array(
		'connection', 'keep-alive', 'proxy-authenticate', 'proxy-authorization',
		'te', 'trailers', 'transfer-encoding', 'upgrade' );

	/**
	 * Execute a apiHttpRequest
	 *
	 * @param Yoast_Google_HttpRequest $request the http request to be executed
	 *
	 * @return Yoast_Google_HttpRequest http request with the response http code, response
	 * headers and response body filled in
	 */
	public function executeRequest( MonsterInsights_GA_Lib_Http_Request $request ) {

		// First, check to see if we have a valid cached version.
		$cached = $this->getCachedRequest( $request );
		if ( $cached !== false ) {
			if ( ! $this->checkMustRevaliadateCachedRequest( $cached, $request ) ) {
				return $cached;
			}
		}

		if ( array_key_exists( $request->getRequestMethod(), self::$ENTITY_HTTP_METHODS ) ) {
			$request = $this->processEntityRequest( $request );
		}

		$params = array(
			'user-agent' => $request->getUserAgent(),
			'timeout'    => 30,
			'sslverify'  => false,
		);

		$curl_version = $this->get_curl_version();
		if ( $curl_version !== false ) { // @todo fix this
			if ( version_compare( $curl_version, '7.19.0', '<=' ) && version_compare( $curl_version, '7.19.8', '>' ) ) {
				add_filter( 'http_api_transports', array( $this, 'filter_curl_from_transports' ) );
			}
		}

		if ( $request->getPostBody() ) {
			$params['body'] = $request->getPostBody();
		}

		$requestHeaders = $request->getRequestHeaders();
		if ( $requestHeaders && is_array( $requestHeaders ) ) {
			$params['headers'] = $requestHeaders;
		}

		// There might be some problems with decompressing, so we prevent this by setting the param to false
		$params['decompress'] = false;


		switch ( $request->getRequestMethod() ) {
			case 'POST' :
				$response = wp_remote_post( $request->getUrl(), $params );
				break;

			case 'GET' :
				$response = wp_remote_get( $request->getUrl(), $params );
				break;
			case 'DELETE' :
				$params['method'] = 'DELETE';
				$response = wp_remote_get( $request->getUrl(), $params );
				break;
		}

		$responseBody    = wp_remote_retrieve_body( $response );
		$respHttpCode    = wp_remote_retrieve_response_code( $response );
		$responseHeaders = wp_remote_retrieve_headers( $response );
		

		 $this->client->getLogger()->debug(
			'Stream response',
			array(
				'code' => $respHttpCode,
				'headers' => $responseHeaders,
				'body' => $responseBody,
			)
		);

		// And finally return it
		return array( $responseBody, $responseHeaders, $respHttpCode );
	}

	/**
	 * Remove Curl from the transport
	 *
	 * @param $transports
	 *
	 * @return mixed
	 */
	public function filter_curl_from_transports( $transports ) {
		unset( $transports['curl'] );

		return $transports;
	}

	/**
	 * Set options that update default behavior.
	 *
	 * @param array $optParams Multiple options used by a session.
	 */
  public function setOptions($options) {
	$this->options = $options + $this->options;
  }

	/**
	 * Get the current curl verison if curl is installed
	 *
	 * @return bool|string
	 */
	public function get_curl_version() {
		if ( function_exists( 'curl_version' ) ) {
			$curl = curl_version();

			if ( isset( $curl['version'] ) ) {
				return $curl['version'];
			}
		}

		return false;
	}

  /**
   * Set the maximum request time in seconds.
   * @param $timeout in seconds
   */
  public function setTimeout($timeout)
  {
	$this->options[self::TIMEOUT] = $timeout;
  }

  /**
   * Get the maximum request time in seconds.
   * @return timeout in seconds
   */
  public function getTimeout()
  {
	return $this->options[self::TIMEOUT];
  }

  /**
   * Test for the presence of a cURL header processing bug
   *
   * {@inheritDoc}
   *
   * @return boolean
   */
  protected function needsQuirk()
  {
	return false;
  }

}