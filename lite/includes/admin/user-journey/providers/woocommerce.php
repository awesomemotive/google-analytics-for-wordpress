<?php
/**
 * This file contains the code to display metabox for WooCommerce Admin Orders Page.
 *
 * @since 8.5.0
 *
 * @package MonsterInsights
 * @subpackage MonsterInsights_User_Journey
 */

/**
 * Class to add metabox to woocommerce admin order page.
 *
 * @since 8.5.0
 */
class MonsterInsights_Lite_User_Journey_WooCommerce_Metabox extends MonsterInsights_User_Journey_Lite_Metabox {

	/**
	 * Class constructor.
	 *
	 * @since 8.5.0
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_user_journey_metabox' ) );
	}

	/**
	 * Current Provider Name.
	 *
	 * @return string
	 * @since 8.7.0
	 *
	 */
	protected function get_provider() {
		return 'woocommerce';
	}

	/**
	 * Add metabox
	 *
	 * @return void
	 * @since 8.5.0
	 *
	 * @uses add_meta_boxes WP Hook
	 *
	 */
	public function add_user_journey_metabox() {
		add_meta_box(
			'woocommerce-monsterinsights-lite-user-journey-metabox',
			esc_html__( 'User Journey by MonsterInsights', 'monsterinsights' ),
			array( $this, 'display_meta_box' ),
			'shop_order',
			'normal',
			'core'
		);
	}

	/**
	 * Display metabox HTML.
	 *
	 * @param object $post WooCommerce Order custom post
	 *
	 * @return void
	 * @since 8.5.0
	 *
	 */
	public function display_meta_box( $post ) {
		$this->metabox_html( $post );
	}
}

if ( class_exists( 'WooCommerce' ) ) {
	new MonsterInsights_Lite_User_Journey_WooCommerce_Metabox();
}
