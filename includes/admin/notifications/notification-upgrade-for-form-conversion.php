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
	public $notification_icon = '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<circle cx="16" cy="16" r="16" fill="#FAD1D1"/>
<path d="M17.3634 19.0714C17.792 19.4821 18.0063 19.9821 18.0063 20.5714C18.0063 21.1607 17.792 21.6607 17.3634 22.0714C16.9527 22.5 16.4527 22.7143 15.8634 22.7143C15.2742 22.7143 14.7652 22.5 14.3367 22.0714C13.9259 21.6607 13.7206 21.1607 13.7206 20.5714C13.7206 19.9821 13.9259 19.4821 14.3367 19.0714C14.7652 18.6429 15.2742 18.4286 15.8634 18.4286C16.4527 18.4286 16.9527 18.6429 17.3634 19.0714ZM13.9617 9.66964C13.9617 9.49107 14.0242 9.33929 14.1492 9.21429C14.2742 9.07143 14.4259 9 14.6045 9H17.1224C17.3009 9 17.4527 9.07143 17.5777 9.21429C17.7027 9.33929 17.7652 9.49107 17.7652 9.66964L17.3902 16.9554C17.3902 17.1339 17.3277 17.2857 17.2027 17.4107C17.0777 17.5179 16.9259 17.5714 16.7474 17.5714H14.9795C14.8009 17.5714 14.6492 17.5179 14.5242 17.4107C14.3992 17.2857 14.3367 17.1339 14.3367 16.9554L13.9617 9.66964Z" fill="#EB5757"/>
</svg>';

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
				'url'  => $this->get_upgrade_url(),
				'text' => __( 'Get MonsterInsights Pro', 'google-analytics-for-wordpress' )
			),
		);

		return $notification;
	}

}

// initialize the class
new MonsterInsights_Notification_Upgrade_For_Form_Conversion();
