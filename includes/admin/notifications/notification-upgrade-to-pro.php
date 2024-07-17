<?php

/**
 * Add notification after 1 week of lite version installation
 * Recurrence: 40 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Upgrade_To_Pro extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_upgrade_to_pro';
	public $notification_interval = 40; // in days
	public $notification_first_run_time = '+7 day';
	public $notification_type = array( 'lite' );
	public $notification_icon = 'star';
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
		$notification['title'] = __( 'Upgrade to Unlock Advanced Tracking & Reports', 'google-analytics-for-wordpress' );
		// Translators: upgrade to pro notification content
		$notification['content'] = __( 'By upgrading to MonsterInsights Pro you get access to additional reports right in your WordPress dashboard and advanced tracking features like eCommerce, Custom Dimensions, Forms tracking and more!', 'google-analytics-for-wordpress' );
		$notification['btns']    = array(
			"upgrade_to_pro" => array(
				'url'         => $this->get_upgrade_url(),
				'text'        => __( 'Upgrade to Pro', 'google-analytics-for-wordpress' ),
				'is_external' => true,
			),
		);

		return $notification;
	}

}

// initialize the class
new MonsterInsights_Notification_Upgrade_To_Pro();
