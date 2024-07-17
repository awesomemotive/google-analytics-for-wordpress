<?php

/**
 * Add notification when lite version activated
 * Recurrence: 20 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Upgrade_For_Form_Conversion extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_upgrade_for_form_conversion';
	public $notification_interval = 20; // in days
	public $notification_type = array( 'basic', 'lite', 'plus' );
	public $notification_category = 'insight';
	public $notification_priority = 3;

	/**
	 * Build Notification
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		$notification['title'] = __( 'Easily Track Form Conversions', 'google-analytics-for-wordpress' );
		/* translators: Placeholders add a link to the settings page. */
		$notification['content'] = sprintf( __( 'Track your website\'s form conversion rates by upgrading to %1$sMonsterInsights Pro%2$s.', 'google-analytics-for-wordpress' ), '<a href="' . $this->get_upgrade_url() . '" target="_blank">', '</a>' );
		$notification['btns']    = array(
			"get_monsterinsights_pro" => array(
				'url'         => $this->get_upgrade_url(),
				'text'        => __( 'Upgrade Now', 'google-analytics-for-wordpress' ),
				'is_external' => true,
			),
		);

		return $notification;
	}

}

// initialize the class
new MonsterInsights_Notification_Upgrade_For_Form_Conversion();
