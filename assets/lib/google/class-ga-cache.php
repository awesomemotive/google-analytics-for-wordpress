<?php
/*
 * This class implements the caching mechanism for WordPress
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class MonsterInsights_GA_Cache extends MonsterInsights_GA_Lib_Cache_Abstract {
	private $client;
	/**
	 * If wp_cache_get doesn't exists, include the file
	 *
	 */
	public function __construct( MonsterInsights_GA_Lib_Client $client ) {
		$this->client = $client;
		if( ! function_exists('wp_cache_get') ) {
			require_once( ABSPATH . 'wp-includes/cache.php' );
		}
	}

	/**
	 * Retrieves the data for the given key, or false if they
	 * key is unknown or expired
	 *
	 * @param String $key The key who's data to retrieve
	 * @param boolean|int $expiration - Expiration time in seconds
	 *
	 * @return mixed
	 *
	 */
	public function get($key, $expiration = false) {
		return wp_cache_get( $key );
	}

	/**
	 * Store the key => $value set. The $value is serialized
	 * by this function so can be of any type
	 *
	 * @param string $key Key of the data
	 * @param string $value data
	 */
	public function set($key, $value) {
		wp_cache_set( $key, $value ) ;
	}

	/**
	 * Removes the key/data pair for the given $key
	 *
	 * @param String $key
	 */
	public function delete($key) {
		wp_cache_delete( $key );
	}


}