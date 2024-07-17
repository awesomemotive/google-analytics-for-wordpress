<?php
/**
 * This file contains the code to display metabox for LifterLMS Admin Orders Page.
 *
 * @since 8.7.0
 *
 * @package MonsterInsights
 * @subpackage MonsterInsights_User_Journey
 */

/**
 * Class to add metabox to LifterLMS admin order page.
 *
 * @since 8.7.0
 */
class MonsterInsights_Lite_User_Journey_LifterLMS_Metabox extends MonsterInsights_User_Journey_Lite_Metabox {

	/**
	 * Class constructor.
	 *
	 * @since 8.7.0
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_user_journey_metabox' ) );
	}

	/**
	 * Add metabox
	 *
	 * @return void
	 * @since 8.7.0
	 *
	 * @uses add_meta_boxes WP Hook
	 *
	 */
	public function add_user_journey_metabox() {
		add_meta_box(
			'lifterlms-monsterinsights-lite-user-journey-metabox',
			esc_html__( 'User Journey by MonsterInsights', 'monsterinsights' ),
			array( $this, 'display_meta_box' ),
			'llms_order',
			'normal',
			'core'
		);
	}

	/**
	 * Current Provider Name.
	 *
	 * @return string
	 * @since 8.7.0
	 *
	 */
	protected function get_provider() {
		return 'lifterlms';
	}

	/**
	 * Display metabox HTML.
	 *
	 * @param object $post LifterLMS Order custom post
	 *
	 * @return void
	 * @since 8.7.0
	 *
	 */
	public function display_meta_box( $post ) {
		$this->metabox_html();
	}
}

if ( function_exists( 'llms' ) ) {
	new MonsterInsights_Lite_User_Journey_LifterLMS_Metabox();
}
