<?php

/**
 * Add notification when lite version activated
 * Recurrence: 30 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Upgrade_For_Email_Summaries extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_upgrade_for_email_summaries';
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
		$notification['title'] = __( 'Upgrade to MonsterInsights Pro to get weekly email reports', 'google-analytics-for-wordpress' );
		// Translators: upgrade for email summaries notification content
		$notification['content'] = sprintf( __( 'Wouldn’t it be easy if you could get your website’s performance report in your email inbox every week? With our new feature, Email Summaries, you can now view all your important stats in a simple report that’s delivered straight to your inbox. <br><br>You get an overview of your site\'s performance without logging in to WordPress or going through different Analytics reports. %sUpgrade to MonsterInsights Pro%s to enable the Email Summaries feature.', 'google-analytics-for-wordpress' ), '<a href="' . $this->get_upgrade_url() . '" target="_blank">', '</a>' );
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
new MonsterInsights_Notification_Upgrade_For_Email_Summaries();
