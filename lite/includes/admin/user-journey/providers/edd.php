<?php
/**
 * This file contains the code to display metabox for EDD Admin Orders Page.
 *
 * @since 8.7.0
 *
 * @package MonsterInsights
 * @subpackage MonsterInsights_User_Journey
 */

/**
 * Class to add metabox to EDD admin order page.
 *
 * @since 8.7.0
 */
class MonsterInsights_Lite_User_Journey_EDD_Metabox extends MonsterInsights_User_Journey_Lite_Metabox {

	/**
	 * Class constructor.
	 *
	 * @since 8.7.0
	 */
	public function __construct() {
		add_action( 'edd_view_order_details_main_after', array( $this, 'add_user_journey_metabox' ), 10, 1 );
	}

	/**
	 * Check if we are on EDD edit order screen.
	 *
	 * @return bool
	 * @since 8.7.0
	 *
	 */
	public function is_edd_order_screen() {
		if ( ! $this->is_valid_array( $_GET, 'page', true ) ) {
			return false;
		}

		if ( ! $this->is_valid_array( $_GET, 'view', true ) ) {
			return false;
		}

		if ( ! $this->is_valid_array( $_GET, 'id', true ) ) {
			return false;
		}

		if ( 'edd-payment-history' !== $_GET['page'] && 'view-order-details' !== $_GET['view'] ) { // phpcs:ignore
			return false;
		}

		return true;
	}

	/**
	 * Current Provider Name.
	 *
	 * @return string
	 * @since 8.7.0
	 *
	 */
	protected function get_provider() {
		return 'edd';
	}

	/**
	 * Add metabox
	 *
	 * @param int $order_id EDD Order ID.
	 *
	 * @return void
	 * @since 8.7.0
	 *
	 */
	public function add_user_journey_metabox( $order_id ) {
		if ( ! $this->is_edd_order_screen() ) {
			return;
		}

		$this->metabox_html();
	}

	/**
	 * Metabox Title.
	 *
	 * @return void
	 * @since 8.7.0
	 *
	 */
	protected function metabox_title() {
		?>
		<div class="monsterinsights-uj-metabox-title">
			<h2><?php esc_html_e( 'User Journey by MonsterInsights', 'monsterinsights' ); ?></h2>
		</div>
		<?php
	}
}

if ( class_exists( 'Easy_Digital_Downloads' ) ) {
	new MonsterInsights_Lite_User_Journey_EDD_Metabox();
}
