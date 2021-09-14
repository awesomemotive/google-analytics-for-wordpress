<?php

/**
 * Add notification when lite, plus version activated
 * Recurrence: 30 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Upgrade_For_Google_Optimize extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_upgrade_for_google_optimize';
	public $notification_interval = 30; // in days
	public $notification_type = array( 'basic', 'lite', 'plus' );
	public $notification_icon = 'warning';

	/**
	 * Build Notification
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		$notification['title'] = __( 'Upgrade to MonsterInsights Pro to Enable Google Optimize', 'google-analytics-for-wordpress' );
		// Translators: upgrade for google optimize notification content
		$notification['content'] = sprintf( __( '%sGoogle Optimize%s is a free A/B testing and personalization product by Google that lets you easily conduct experiments to see what works best on your site. With Google Optimize, you can use split testing and personalization to create online experiences that engage and delight your customers. %sUpgrade to MonsterInsights Pro%s to unlock the Google Optimize addon.', 'google-analytics-for-wordpress' ), '<a href="' . $this->build_external_link( 'https://www.monsterinsights.com/addon/google-optimize/' ) . '" target="_blank">', '</a>', '<a href="' . $this->get_upgrade_url() . '" target="_blank">', '</a>' );
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
new MonsterInsights_Notification_Upgrade_For_Google_Optimize();
