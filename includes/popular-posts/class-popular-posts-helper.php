<?php
/**
 * This is the helper class for the Popular Posts output functionality.
 * We will call this class or its methods when necessary.
 *
 * @package MonsterInsights
 */

/**
 * Class MonsterInsights_Popular_Posts_Helper
 * We will create helper methods as static.
 */
class MonsterInsights_Popular_Posts_Helper {

	/**
	 * Store self object.
	 */
	private static $instance;

	/**
	 * Instentiate the class.
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'transition_post_status', array( $this, 'transition_post_status' ), 10, 2 );
	}

	/**
	 * Here we are clearing cache for popular post.
	 */
	private function clear_cache() {
		// Delete Popular Posts cache.
		MonsterInsights_Popular_Posts_Inline()->get_cache()->delete_data();
		MonsterInsights_Popular_Posts_Widget()->get_cache()->delete_data();

		if ( monsterinsights_is_pro_version() ) {
			MonsterInsights_Popular_Posts_Products()->get_cache()->delete_data();
		}
	}

	/**
	 * Trigger when post status is changed.
	 */
	public function transition_post_status( $new_status, $old_status ) {
		// A post goes to any status from published.
		if ( $old_status == 'publish' && $new_status != 'publish' ) {
			$this->clear_cache();
		}

		// A post is being to published.
		if ( $new_status == 'publish' && $old_status != 'publish' ) {
			$this->clear_cache();
		}
	}
}

// Initiate here so that we can run hooks.
MonsterInsights_Popular_Posts_Helper::get_instance();
