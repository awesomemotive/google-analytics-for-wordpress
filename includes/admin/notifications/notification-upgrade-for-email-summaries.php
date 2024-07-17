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
		$notification['title'] = __( 'Get Weekly Email Reports', 'google-analytics-for-wordpress' );
		$notification['content'] = sprintf(
			/* translators: Placeholders add a link to an article. */
			__( 'Wouldn’t it be easy if you could get your website’s performance report in your email inbox every week? With Email Summaries, you can view all your important stats in a simple report that’s delivered straight to your inbox. <br><br>You get an overview of your site\'s performance without logging in to WordPress or going through different analytics reports. %1$sUpgrade to MonsterInsights Pro%2$s to enable the Email Summaries feature.', 'google-analytics-for-wordpress' ),
			'<a href="' . $this->get_upgrade_url() . '" target="_blank">',
			'</a>'
		);
		$notification['btns']    = array(
			"get_monsterinsights_pro" => array(
				'url'         => $this->get_upgrade_url(),
				'text'        => __( 'Get MonsterInsights Pro', 'google-analytics-for-wordpress' ),
				'is_external' => true,
			),
		);

		return $notification;
	}

}

// initialize the class
new MonsterInsights_Notification_Upgrade_For_Email_Summaries();
