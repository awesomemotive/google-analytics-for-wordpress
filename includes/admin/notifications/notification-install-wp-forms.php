<?php

/**
 * Add notification after 1 week of lite version installation
 * Recurrence: 40 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Install_WPForms extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_install_wpforms';
	public $notification_interval = 30; // in days
	public $notification_type = array( 'basic', 'lite', 'master', 'plus', 'pro' );
	public $notification_icon = 'star';
	public $notification_category = 'insight';
	public $notification_priority = 2;

	/**
	 * Build Notification
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {

		$form_plugin_active = class_exists( 'GFAPI' ) || function_exists( 'frm_forms_autoloader' ) || function_exists( 'wpforms' );

		if ( ! $form_plugin_active ) {
			$notification['title']   = __( 'Create a Contact Form in Only Minutes', 'google-analytics-for-wordpress' );
			$notification['content'] = __( 'Install WPForms and create contact forms in a matter of minutes.', 'google-analytics-for-wordpress' );

			return $notification;
		}

		return false;
	}

}

// initialize the class
new MonsterInsights_Notification_Install_WPForms();
