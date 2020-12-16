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
	public $notification_icon = '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<circle cx="16" cy="16" r="16" fill="#E1DAF1"/>
<path d="M20.0331 13.2857C20.2831 13.2857 20.4706 13.3929 20.5956 13.6071C20.7206 13.8214 20.7206 14.0357 20.5956 14.25L15.8813 22.3929C15.7563 22.6071 15.5688 22.7143 15.3188 22.7143C15.1045 22.7143 14.9349 22.6339 14.8099 22.4732C14.6849 22.3125 14.6492 22.125 14.7027 21.9107L15.9349 16.7143H12.7474C12.6224 16.7143 12.5063 16.6786 12.3992 16.6071C12.292 16.5357 12.2117 16.4464 12.1581 16.3393C12.1045 16.2321 12.0867 16.1161 12.1045 15.9911L12.9617 9.5625C12.9795 9.45536 13.0152 9.35714 13.0688 9.26786C13.1402 9.17857 13.2206 9.11607 13.3099 9.08036C13.3992 9.02679 13.4974 9 13.6045 9H17.4617C17.6759 9 17.8456 9.08929 17.9706 9.26786C18.0956 9.42857 18.1313 9.60714 18.0777 9.80357L16.9527 13.2857H20.0331Z" fill="#6F4BBB"/>
</svg>';

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
		$notification['title'] = sprintf( __( 'See how %s visitors found your site!', 'google-analytics-for-wordpress' ), $total_visitors );
		// Translators: visitors notification content
		$notification['content'] = sprintf( __( 'Your website has been visited by %s visitors in the past 30 days. Click the button below to view the full analytics report.', 'google-analytics-for-wordpress' ), $total_visitors );
		$notification['btns']    = array(
			"view_report" => array(
				'url'  => $this->get_view_url(),
				'text' => __( 'View Report', 'google-analytics-for-wordpress' )
			),
		);

		return $notification;
	}

}

// initialize the class
new MonsterInsights_Notification_Visitors();
