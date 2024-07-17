<?php

/**
 * Add notification when lite version activated
 * Recurrence: 30 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Upgrade_For_Search_Console extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_upgrade_for_search_console_report';
	public $notification_interval = 30; // in days
	public $notification_type = array( 'lite' );
	public $notification_icon = 'warning';
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
		$notification['title'] = __( 'See Top Performing Keywords', 'google-analytics-for-wordpress' );
		/* translators: Placeholders add a link to the upgrade url. */
		$notification['content'] = sprintf( __( '%1$sUpgrade to MonsterInsights Pro%2$s to see which keywords are driving traffic to your website so you can focus on what\'s working.', 'google-analytics-for-wordpress' ), '<a href="' . $this->get_upgrade_url() . '" target="_blank">', '</a>' );
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
new MonsterInsights_Notification_Upgrade_For_Search_Console();
