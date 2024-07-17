<?php

/**
 * Add visitors notification
 * Recurrence: 30 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Visitors extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_visitors';
	public $notification_interval = 30; // in days
	public $notification_type = array( 'basic', 'lite', 'master', 'plus', 'pro' );
	public $notification_icon = 'lightning';
	public $notification_category = 'insight';
	public $notification_priority = 2;

	/**
	 * Build Notification
	 *
	 * @param array $report Overview report
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		$report = $this->get_report();

		if ( ! is_array( $report ) || empty( $report ) ) {
			return false;
		}

		$total_visitors = isset( $report['data']['infobox']['sessions']['value'] ) ? $report['data']['infobox']['sessions']['value'] : 0;
		// Translators: visitors notification title
		$notification['title'] = sprintf( __( 'See how %s Visitors Found Your Site!', 'google-analytics-for-wordpress' ), $total_visitors );
		// Translators: visitors notification content
		$notification['content'] = sprintf( __( 'Your website has been visited by %s visitors in the past 30 days. Click the button below to view the full analytics report.', 'google-analytics-for-wordpress' ), $total_visitors );
		$notification['btns']    = array(
			"view_report" => array(
				'url'  => $this->get_view_url( 'monsterinsights-report-overview', 'monsterinsights_reports' ),
				'text' => __( 'View Report', 'google-analytics-for-wordpress' ),
			),
		);

		return $notification;
	}

}

// initialize the class
new MonsterInsights_Notification_Visitors();
