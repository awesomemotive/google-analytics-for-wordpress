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

	/**
	 * Build Notification
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		$notification['title'] = __( 'Get access to Google Search Keywords data by upgrading to MonsterInsights Pro', 'google-analytics-for-wordpress' );
		// Translators: upgrade for search console notification content
		$notification['content'] = sprintf( __( 'Do you want to find out which search terms from Google bring your site the most visitors? %sUpgrade to MonsterInsights PRO%s today and get access to the %sSearch Console Report%s and more directly in your WordPress admin.', 'google-analytics-for-wordpress' ), '<a href="' . $this->get_upgrade_url() . '" target="_blank">', '</a>', '<a href="' . $this->build_external_link( 'https://www.monsterinsights.com/feature/search-console-report/' ) . '" target="_blank">', '</a>' );
		$notification['btns']    = array(
			"get_monsterinsights_pro" => array(
				'url'           => $this->get_upgrade_url(),
				'text'          => __( 'Get MonsterInsights Pro', 'google-analytics-for-wordpress' ),
				'is_external'   => true,
			),
		);

		return $notification;
	}

}

// initialize the class
new MonsterInsights_Notification_Upgrade_For_Search_Console();
