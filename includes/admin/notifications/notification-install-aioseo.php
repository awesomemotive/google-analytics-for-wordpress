<?php

/**
 * Add notification after 1 week of lite version installation
 * Recurrence: 40 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Install_AIOSEO extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_install_aioseo';
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

		$seo_plugin_active = function_exists( 'YoastSEO' ) || function_exists( 'aioseo' );

		if ( ! $seo_plugin_active ) {
			$notification['title']   = __( 'Install All-In-One SEO', 'google-analytics-for-wordpress' );
			$notification['content'] = __( 'Install All in One SEO to optimize your site for better search engine rankings.', 'google-analytics-for-wordpress' );

			return $notification;
		}

		return false;
	}

}

// initialize the class
new MonsterInsights_Notification_Install_AIOSEO();
