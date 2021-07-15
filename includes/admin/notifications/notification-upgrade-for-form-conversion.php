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
	public $notification_icon = 'warning';

	/**
	 * Build Notification
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		$notification['title'] = __( 'Upgrade to MonsterInsights Pro to Track Form Conversion', 'google-analytics-for-wordpress' );
		// Translators: upgrade for form conversion notification content
		$notification['content'] = sprintf( __( 'Forms are one of the most important points of interaction on your website. When a visitor fills out a form on your site, they’re taking the next step in their customer journey. That’s why it’s so crucial that your WordPress forms are optimized for conversions. Upgrade to %sMonsterInsights Pro%s to track %sform conversions in Google Analytics.%s', 'google-analytics-for-wordpress' ), '<a href="' . $this->get_upgrade_url() . '" target="_blank">', '</a>', '<a href="' . $this->build_external_link( 'https://www.monsterinsights.com/addon/forms/' ) . '" target="_blank">', '</a>' );
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
new MonsterInsights_Notification_Upgrade_For_Form_Conversion();
