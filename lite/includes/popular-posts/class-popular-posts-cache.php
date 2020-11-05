<?php
/**
 * This class is used for handling Popular Posts caching.
 *
 * @package MonsterInsights
 */

/**
 * Class MonsterInsights_Popular_Posts_Cache
 */
class MonsterInsights_Popular_Posts_Cache {

	/**
	 * Instance type (inline/widget/products).
	 *
	 * @var string
	 */
	public $type;

	/**
	 * MonsterInsights_Popular_Posts_Cache constructor.
	 *
	 * @param string $type The instance type (inline/widget/products).
	 */
	public function __construct( $type ) {

		$this->type = $type;
	}

	/**
	 * Build an unique key from the arguments so we can cache different instances.
	 * This way, the Gutenberg block or the sidebar widget get cached with their own query settings
	 * if they are different from the ones set in Vue.
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function get_args_key( $args ) {
		return md5( wp_json_encode( $args ) );
	}

	/**
	 * Get the specific options key for the set type.
	 *
	 * @return string
	 */
	public function get_cache_key() {
		return 'monsterinsights_popular_posts_cache_' . $this->type;
	}

	/**
	 * Get cached posts data and check expiration. Each query result is stored with the timestamp and that
	 * is used to compare to the current settings for expiration.
	 *
	 * @param array $args This is an array with query parameters for WP_Query used to identify if this query has been cached.
	 *
	 * @return array
	 */
	public function get_cached_posts( $args ) {

		$cache_refresh_days = $this->get_cache_interval();
		$cached_data        = get_option( $this->get_cache_key(), array() );
		$args_key           = $this->get_args_key( $args ); // Generate an unique key based on the instance settings.

		if ( isset( $cached_data[ $args_key ] ) && isset( $cached_data[ $args_key ]['saved_at'] ) ) {
			$time_since = time() - $cached_data[ $args_key ]['saved_at'];

			if ( $time_since < intval( $cache_refresh_days ) * DAY_IN_SECONDS ) {
				return $cached_data[ $args_key ]['posts'];
			} else {
				// It's expired so let's delete it.
				unset( $cached_data[ $args_key ] );
				update_option( $this->get_cache_key(), $cached_data );
			}
		}

		return array();

	}


	/**
	 * Get the option set in the settings for cache expiration.
	 *
	 * @return int
	 */
	private function get_cache_interval() {
		$cache_refresh_days = monsterinsights_get_option( 'popular_posts_caching_refresh', 7 );

		// If they downgraded and previously used a custom interval use the default 7 until the update the option.
		if ( 'custom' === $cache_refresh_days ) {
			$cache_refresh_days = 7;
		}

		return intval( $cache_refresh_days );
	}

	/**
	 * Store a query result in the cache along with the arguments used to grab them.
	 *
	 * @param array $args Arguments used in WP_Query used to build an unique key for the loaded data.
	 * @param array $posts An array of posts that resulted from the query to be saved in the cache.
	 */
	public function save_posts_to_cache( $args, $posts ) {

		if ( empty( $posts ) ) {
			// Don't save empty posts.
			return;
		}

		$args_key    = md5( wp_json_encode( $args ) ); // Generate an unique key based on the instance settings.
		$cached_data = get_option( $this->get_cache_key(), array() );

		$cached_data[ $args_key ] = array(
			'saved_at' => time(),
			'posts'    => $posts,
		);

		update_option( $this->get_cache_key(), $cached_data );

	}

}
