<?php
/**
 * This file contains the code to display metabox for GiveWP Admin Orders Page.
 *
 * @since 8.7.0
 *
 * @package MonsterInsights
 * @subpackage MonsterInsights_User_Journey
 */

/**
 * Class to add metabox to GiveWP admin order page.
 *
 * @since 8.7.0
 */
class MonsterInsights_Lite_User_Journey_GiveWP_Metabox extends MonsterInsights_User_Journey_Lite_Metabox {

	/**
	 * Class constructor.
	 *
	 * @since 8.7.0
	 */
	public function __construct() {
		add_action( 'give_view_donation_details_billing_after', array( $this, 'add_user_journey_metabox' ), 10, 1 );
	}

	/**
	 * Check if we are on GiveWP order screen.
	 *
	 * @return array
	 * @since 8.7.0
	 *
	 */
	public function is_givewp_order_screen() {
		if ( ! $this->is_valid_array( $_GET, 'view', true ) ) {
			return false;
		}

		if ( ! $this->is_valid_array( $_GET, 'id', true ) ) {
			return false;
		}

		if ( ! $this->is_valid_array( $_GET, 'post_type', true ) ) {
			return false;
		}

		if ( ! $this->is_valid_array( $_GET, 'page', true ) ) {
			return false;
		}

		if ( 'give_forms' !== $_GET['post_type'] && 'give-payment-history' !== $_GET['page'] && 'view-payment-details' !== $_GET['view'] ) { // phpcs:ignore
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
		return 'givewp';
	}

	/**
	 * Add metabox
	 *
	 * @param int $payment_id Order ID of GiveWP.
	 *
	 * @return void
	 * @since 8.7.0
	 *
	 */
	public function add_user_journey_metabox( $payment_id ) {
		if ( ! $this->is_givewp_order_screen() ) {
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

if ( function_exists( 'give' ) ) {
	new MonsterInsights_Lite_User_Journey_GiveWP_Metabox();
}
