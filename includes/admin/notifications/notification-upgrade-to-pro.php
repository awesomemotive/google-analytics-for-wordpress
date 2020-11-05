<?php

/**
 * Add notification after 1 week of lite version installation
 * Recurrence: 40 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Upgrade_To_Pro extends MonsterInsights_Notification_Event {

	public $notification_id             = 'monsterinsights_notification_upgrade_to_pro';
	public $notification_interval       = 40; // in days
	public $notification_first_run_time = '+7 day';
	public $notification_type           = array( 'lite' );
	public $notification_icon           = '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<circle cx="16" cy="16" r="16" fill="#D4E7F7"/>
<path d="M15.0867 9.48214C15.2474 9.16071 15.5063 9 15.8634 9C16.2206 9 16.4795 9.16071 16.6402 9.48214L18.3813 13.0179L22.292 13.6071C22.6492 13.6429 22.8813 13.8304 22.9884 14.1696C23.0956 14.5089 23.0242 14.8036 22.7742 15.0536L19.9349 17.8125L20.6045 21.7232C20.6581 22.0625 20.542 22.3304 20.2563 22.5268C19.9706 22.7411 19.6759 22.7679 19.3724 22.6071L15.8634 20.7857L12.3545 22.6071C12.0509 22.7857 11.7563 22.7679 11.4706 22.5536C11.1849 22.3393 11.0688 22.0625 11.1224 21.7232L11.792 17.8125L8.95274 15.0536C8.70274 14.8036 8.63131 14.5089 8.73845 14.1696C8.84559 13.8304 9.07774 13.6429 9.43488 13.6071L13.3456 13.0179L15.0867 9.48214Z" fill="#2679C1"/>
</svg>';

	/**
	 * Build Notification
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		$notification['title']   = __( 'Upgrade to MonsterInsights Pro and unlock advanced tracking and reports', 'google-analytics-for-wordpress' );
		// Translators: upgrade to pro notification content
		$notification['content'] = __( 'By upgrading to MonsterInsights Pro you get access to additional reports right in your WordPress dashboard and advanced tracking features like eCommerce, Custom Dimensions, Forms tracking and more!', 'google-analytics-for-wordpress' );
		$notification['btns'] = array(
			"upgrade_to_pro" => array(
				'url'   => $this->get_upgrade_url(),
				'text'  => __( 'Upgrade to Pro', 'google-analytics-for-wordpress' )
			),
		);

		return $notification;
	}

}

// initialize the class
new MonsterInsights_Notification_Upgrade_To_Pro();
