<?php

/**
 * Add notification when percentage of visitors from mobile devices is more than 70%
 * Recurrence: 30 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Mobile_Device_High_Traffic extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_mobile_device_high_traffic';
	public $notification_interval = 30; // in days
	public $notification_type = array( 'basic', 'lite', 'master', 'plus', 'pro' );
	public $notification_category = 'insight';
	public $notification_priority = 2;

	/**
	 * Prepare Notification
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		$report         = $this->get_report( 'overview', $this->report_start_from, $this->report_end_to );
		$mobile_traffic = isset( $report['data']['devices']['mobile'] ) ? $report['data']['devices']['mobile'] : 0;
		$tablet_traffic = isset( $report['data']['devices']['tablet'] ) ? $report['data']['devices']['tablet'] : 0;

		$total_mobile_traffic_percentage = $mobile_traffic + $tablet_traffic;

		if ( $total_mobile_traffic_percentage > 70 ) {
			// Translators: Mobile device notification title
			$notification['title'] = sprintf( __( 'Traffic From Mobile Devices is %s%%', 'google-analytics-for-wordpress' ), $total_mobile_traffic_percentage );
			// Translators: Mobile device notification content
			$notification['content'] = sprintf( __( 'In the last 30 days, your site has received %s%% of traffic through a mobile or tablet device. Make sure your site is optimized for these visitors to maximize engagement.', 'google-analytics-for-wordpress' ), $total_mobile_traffic_percentage );

			return $notification;
		}

		return false;
	}

}

// initialize the class
new MonsterInsights_Notification_Mobile_Device_High_Traffic();
