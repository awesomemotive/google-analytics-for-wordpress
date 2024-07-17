<?php
/**
 * This file contains the code to display metabox for Restrict Content Pro Admin Orders Page.
 *
 * @since 8.7.0
 *
 * @package MonsterInsights
 * @subpackage MonsterInsights_User_Journey
 */

/**
 * Class to add metabox to Restrict Content Pro admin order page.
 *
 * @since 8.7.0
 */
class MonsterInsights_Lite_User_Journey_Restrict_Content_Pro_Metabox extends MonsterInsights_User_Journey_Lite_Metabox {

	/**
	 * Class constructor.
	 *
	 * @since 8.7.0
	 */
	public function __construct() {
		add_action( 'rcp_edit_payment_after', array( $this, 'add_user_journey_metabox' ), 10, 3 );
	}

	/**
	 * Check if we are on RCP Edit Order page.
	 *
	 * @return boolean
	 * @since 8.7.0
	 *
	 */
	public function is_rcp_order_screen() {
		if ( ! $this->is_valid_array( $_GET, 'page', true ) ) {
			return false;
		}

		if ( ! $this->is_valid_array( $_GET, 'payment_id', true ) ) {
			return false;
		}

		if ( ! $this->is_valid_array( $_GET, 'view', true ) ) {
			return false;
		}

		if ( 'rcp-payments' !== $_GET['page'] && 'edit-payment' !== $_GET['view'] ) { // phpcs:ignore
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
		return 'restrict-content-pro';
	}

	/**
	 * Add metabox
	 *
	 * @param object $payment RCP Payment Object
	 * @param object $membership_level RCP Membership level Object
	 * @param object $uer WordPress User Info from RCP
	 *
	 * @return void
	 * @since 8.7.0
	 *
	 */
	public function add_user_journey_metabox( $payment, $membership_level, $user ) {
		if ( ! $this->is_rcp_order_screen() ) {
			return;
		}

		?>
		<tr>
			<td colspan="2">
				<?php $this->metabox_html(); ?>
			</td>
		</tr>
		<?php
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

if ( class_exists( 'Restrict_Content_Pro' ) ) {
	new MonsterInsights_Lite_User_Journey_Restrict_Content_Pro_Metabox();
}
