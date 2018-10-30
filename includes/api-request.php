<?php
/**
 * API Request class.
 *
 * @since 7.0.0
 *
 * @package MonsterInsights
 * @author  Chris Christoff
 */
final class MonsterInsights_API_Request {

	/**
	 * Base API route.
	 *
	 * @since 7.0.0
	 *
	 * @var string
	 */
	public $base = 'api.monsterinsights.com/v2/';

	/**
	 * Current API route.
	 *
	 * @since 7.0.0
	 *
	 * @var bool|string
	 */
	public $route = false;

	/**
	 * Full API URL endpoint.
	 *
	 * @since 7.0.0
	 *
	 * @var bool|string
	 */
	public $url = false;

	/**
	 * Current API method.
	 *
	 * @since 7.0.0
	 *
	 * @var bool|string
	 */
	public $method = false;

	/**
	 * Is a network request.
	 *
	 * @since 7.2.0
	 *
	 * @var bool
	 */
	public $network = false;

	/**
	 * API token.
	 *
	 * @since 7.0.0
	 *
	 * @var bool|string
	 */
	public $token = false;

	/**
	 * API Key.
	 *
	 * @since 7.0.0
	 *
	 * @var bool|string
	 */
	public $key = false;

	/**
	 * API tt.
	 *
	 * @since 7.0.0
	 *
	 * @var bool|string
	 */
	public $tt = false;

	/**
	 * API return.
	 *
	 * @since 7.0.0
	 *
	 * @var bool|string
	 */
	public $return = false;

	/**
	 * Start date.
	 *
	 * @since 7.0.0
	 *
	 * @var string
	 */
	public $start = '';

	/**
	 * End Date.
	 *
	 * @since 7.0.0
	 *
	 * @var string
	 */
	public $end = '';

	/**
	 * Plugin slug.
	 *
	 * @since 7.0.0
	 *
	 * @var bool|string
	 */
	public $plugin = false;

	/**
	 * Additional data to add to request body
	 *
	 * @since 7.0.0
	 *
	 * @var array
	 */
	protected $additional_data = array();

	/**
	 * Primary class constructor.
	 *
	 * @since 7.0.0
	 *
	 * @param string $route  The API route to target.
	 * @param array  $args   Array of API credentials.
	 * @param string $method The API method.
	 */
	public function __construct( $route, $args, $method = 'POST' ) {

		// Set class properties.
		$this->base      = trailingslashit( monsterinsights_get_api_url() );
		$this->route     = $route;
		$this->protocol  = 'https://';
		$this->url       = trailingslashit( $this->protocol . $this->base . $this->route );
		$this->method    = $method;
		$this->network   = is_network_admin() || ! empty( $args['network'] );

		$default_token   = $this->network ? MonsterInsights()->auth->get_network_token() : MonsterInsights()->auth->get_token();
		$default_key     = $this->network ? MonsterInsights()->auth->get_network_key()   : MonsterInsights()->auth->get_key();

		$this->token     = ! empty( $args['token'] )     ? $args['token']  : $default_token;
		$this->key       = ! empty( $args['key'] ) 	     ? $args['key']    : $default_key;
		$this->tt        = ! empty( $args['tt'] ) 		 ? $args['tt']     : '';
		$this->return    = ! empty( $args['return'] )    ? $args['return'] : '';
		$this->start     = ! empty( $args['start'] )	 ? $args['start']  : '';
		$this->end       = ! empty( $args['end'] )  	 ? $args['end']    : '';

		// We need to do this hack so that the network panel + the site_url of the main site are distinct
		$this->site_url  = is_network_admin() ? network_admin_url() : site_url();

		if ( monsterinsights_is_pro_version() ) {
			$this->license   = $this->network ? MonsterInsights()->license->get_network_license_key() : MonsterInsights()->license->get_site_license_key();
		}
		$this->plugin    = MonsterInsights()->plugin_slug;
		$this->miversion = MONSTERINSIGHTS_VERSION;
		$this->sitei     = ! empty( $args['sitei'] ) ? $args['sitei'] : '';
	}

	/**
	 * Processes the API request.
	 *
	 * @since 7.0.0
	 *
	 * @return mixed $value The response to the API call.
	 */
	public function request() {
		// Make sure we're not blocked
		$blocked = $this->is_blocked( $this->url );
		if ( $blocked || is_wp_error( $blocked )  ) {
			if ( is_wp_error( $blocked ) ) {
				return new WP_Error( 'api-error', sprintf( __( 'The firewall of your server is blocking outbound calls. Please contact your hosting provider to fix this issue. %s', 'google-analytics-for-wordpress' ), $blocked->get_error_message() ) );
			} else {
				return new WP_Error( 'api-error', __( 'The firewall of your server is blocking outbound calls. Please contact your hosting provider to fix this issue.', 'google-analytics-for-wordpress' ) );
			}
		}

		// Build the body of the request.
		$body = array();

		if ( ! empty( $this->token ) ) {
			$body['token'] = $this->token;
		}

		if ( ! empty( $this->key ) ) {
			$body['key'] = $this->key;
		}

		if ( ! empty( $this->tt ) ) {
			$body['tt'] = $this->tt;
		}

		if ( ! empty( $this->return ) ) {
			$body['return'] = $this->return;
		}

		if ( monsterinsights_is_pro_version() && ! empty( $this->license ) ) {
			$body['license'] = $this->license;
		}

		if ( ! empty( $this->start ) ) {
			$body['start'] = $this->start;
		}

		if ( ! empty( $this->end ) ) {
			$body['end'] = $this->end;
		}

		if ( ! empty( $this->sitei ) ) {
			$body['sitei'] = $this->sitei;
		}

		$body['siteurl']   = $this->site_url;
		$body['miversion'] = $this->miversion;

		// If a plugin API request, add the data.
		if ( 'info' == $this->route || 'update' == $this->route ) {
			$body['miapi-plugin'] = $this->plugin;
		}

		// Add in additional data if needed.
		if ( ! empty( $this->additional_data ) ) {
			$body['miapi-data'] = maybe_serialize( $this->additional_data );
		}

		if ( 'GET' == $this->method ) {
			$body['time']   = time(); // just to avoid caching
		}

		$body['timezone'] = date('e');

		$body['network']  = $this->network ? 'network' : 'site';

		$body['ip']   = ! empty( $_SERVER['SERVER_ADDR'] ) ? $_SERVER['SERVER_ADDR'] : '';

		// This filter will be removed in the future.
		$body   = apply_filters( 'monsterinsights_api_request_body', $body );

		$string = http_build_query( $body, '', '&' );

		// Build the headers of the request.
		$headers = array(
			'Content-Type'          => 'application/x-www-form-urlencoded',
			'Cache-Control'         => 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0',
			'Pragma'		        => 'no-cache',
			'Expires'		        => 0,
			'MIAPI-Referer'         => is_network_admin() ? network_admin_url() : site_url(),
			'MIAPI-Sender'          => 'WordPress',
		);

		//if ( $this->apikey ) {
		//	$headers['X-MonsterInsights-ApiKey'] = $this->apikey;
		//}

		// Setup data to be sent to the API.
		$data = array(
			'headers'    => $headers,
			'body'       => $body,
			'timeout'    => 3000,
			'user-agent' => 'MI/' . MONSTERINSIGHTS_VERSION . '; ' . $this->site_url,
			'sslverify'  => false
		);

		// Perform the query and retrieve the response.
		$response      = 'GET' == $this->method ? wp_remote_get( esc_url_raw( $this->url ) . '?' . $string, $data ) : wp_remote_post( esc_url_raw( $this->url ), $data );
		//return new WP_Error( 'debug', '<pre>' . var_export( $response, true ) . '</pre>' );
		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
		//return new WP_Error( 'debug', '<pre>' . var_export( $response_body, true ) . '</pre>' );
		//var_dump( $response_body );
		// Bail out early if there are any errors.
		if ( is_wp_error( $response_body ) ) {
			return $response_body;
		}

		// If not a 200 status header, send back error.
		if ( 200 != $response_code ) {
			$type  = ! empty( $response_body['type'] ) ? $response_body['type'] : 'api-error';

			if ( empty( $response_code ) ) {
				return new WP_Error( $type, __( 'The API was unreachable.', 'google-analytics-for-wordpress' ) );
			}

			if ( empty( $response_body ) || ( empty( $response_body['message'] ) && empty( $response_body['error'] ) ) ) {
				return new WP_Error( $type, sprintf( __( 'The API returned a <strong>%s</strong> response', 'google-analytics-for-wordpress' ), $response_code ) );
			}

			if ( ! empty( $response_body['message'] ) ) {
				return new WP_Error( $type, sprintf( __( 'The API returned a <strong>%d</strong> response with this message: <strong>%s</strong>', 'google-analytics-for-wordpress' ), $response_code, stripslashes( $response_body['message'] ) ) );
			}

			if ( ! empty( $response_body['error'] ) ) {
				return new WP_Error( $type, sprintf( __( 'The API returned a <strong>%d</strong> response with this message: <strong>%s</strong>', 'google-analytics-for-wordpress' ), $response_code, stripslashes( $response_body['error'] ) ) );
			}
		}

		// If TT required
		if ( ! empty( $this->tt ) ) {
			if ( empty( $response_body['tt'] ) || ! hash_equals( $this->tt, $response_body['tt'] ) ) {
				// TT isn't set on return or doesn't match
				return new WP_Error( 'validation-error', sprintf( __( 'Improper API request.', 'google-analytics-for-wordpress' ) ) );
			} else {
				// if the TT is valid, reset so it cannot be replayed
				MonsterInsights()->api_auth->rotate_tt();
			}
		}

		// Return the json decoded content.
		return $response_body;
	}

	/**
	 * Sets a class property.
	 *
	 * @since 7.0.0
	 *
	 * @param string $key The property to set.
	 * @param string $val The value to set for the property.
	 * @return mixed $value The response to the API call.
	 */
	public function set( $key, $val ) {
		$this->{$key} = $val;
	}

	/**
	 * Allow additional data to be passed in the request
	 *
	 * @since 7.0.0
	 *
	 * @param array $data
	 * return void
	 */
	public function set_additional_data( array $data ) {
		$this->additional_data = array_merge( $this->additional_data, $data );
	}

	/**
	 * Checks for SSL for making API requests.
	 *
	 * @since 7.0.0
	 *
	 * return bool True if SSL is enabled, false otherwise.
	 */
	public function is_ssl() {
		// Use the base is_ssl check first.
		if ( is_ssl() ) {
			return true;
		} else if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO'] ) {
			// Also catch proxies and load balancers.
			return true;
		} else if ( defined( 'FORCE_SSL_ADMIN' ) && FORCE_SSL_ADMIN ) {
			return true;
		}

		// Otherwise, return false.
		return false;
	}

	private function is_blocked( $url = '' ) {
		if ( defined( 'AIRMDE_VER' ) ) {
			return new WP_Error( 'api-error', __( 'Reason: The API was unreachable because the Airplane Mode plugin is active.', 'google-analytics-for-wordpress' ) );
		}

		// The below page is a testing empty content HTML page used for firewall/router login detection
		// and for image linking purposes in Google Images. We use it to test outbound connections since it is run on google.com
		// and is only a few bytes large. Plus on Google's main CDN so it loads in most places in 0.07 seconds or less. Perfect for our
		// use case of quickly testing outbound connections. 
		$testurl = 'http://www.google.com/blank.html';
		if ( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL ) {
			if ( defined( 'WP_ACCESSIBLE_HOSTS' ) ) {
				$wp_http      = new WP_Http();
				$on_blacklist = $wp_http->block_request( $url );
				if ( $on_blacklist ) {
					return new WP_Error( 'api-error', __( 'Reason: The API was unreachable because the API url is on the WP HTTP blocklist.', 'google-analytics-for-wordpress' ) );
				} else {
					$params = array(
						'sslverify'     => false,
						'timeout'       => 2,
						'user-agent'    => 'MonsterInsights/' . MONSTERINSIGHTS_VERSION,
						'body'          => ''
					);
					$response = wp_remote_get( $testurl, $params );
					if( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
						return false;
					} else {
						if ( is_wp_error( $response ) ) {
							return $response;
						} else {
							return new WP_Error( 'api-error', __( 'Reason: The API was unreachable because the call to Google failed.', 'google-analytics-for-wordpress' ) );
						}
					}
				}
			} else {
				return new WP_Error( 'api-error', __( 'Reason: The API was unreachable because no external hosts are allowed on this site.', 'google-analytics-for-wordpress' ) );
			}
		} else {
			$params = array(
				'sslverify'     => false,
				'timeout'       => 2,
				'user-agent'    => 'MonsterInsights/' . MONSTERINSIGHTS_VERSION,
				'body'          => ''
			);
			$response = wp_remote_get( $testurl, $params );
			
			if( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
				return false;
			} else {
				if ( is_wp_error( $response ) ) {
					return $response;
				} else {
					return new WP_Error( 'api-error', __( 'Reason: The API was unreachable because the call to Google failed.', 'google-analytics-for-wordpress' ) );
				}
			}
		}
	}
}