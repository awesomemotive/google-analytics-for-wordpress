<?php

/**
 * Add notification when returning visitor rate is lower than 10%
 * Recurrence: 15 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Returning_Visitors extends MonsterInsights_Notification_Event {

	public $notification_id             = 'monsterinsights_notification_returning_visitors';
	public $notification_interval       = 15; // in days
	public $notification_type           = array( 'basic', 'lite', 'master', 'plus', 'pro' );

	/**
	 * Build Notification
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		if ( ! monsterinsights_is_pro_version() ) {
			// Improve performance for lite users by disabling external API calls they can’t access.
			// Since lite users can’t access this feature return early.
			return false;
		}
		$data              = array();
		$report            = $this->get_report( 'overview', $this->report_start_from, $this->report_end_to );
		$data['returning'] = isset( $report['data']['newvsreturn']['returning'] ) ? $report['data']['newvsreturn']['returning'] : 0;

		if ( ! empty( $data ) && $data['returning'] < 10 ) {
			// Translators: Returning visitors notification title
			$notification['title']   = sprintf( __( 'Only %s%% of your visitors return to your site', 'google-analytics-for-wordpress' ), $data['returning'] );
			// Translators: Returning visitors notification content
			$notification['content'] = sprintf( __( 'For any website, returning visitors are important because they indicate how successful your marketing campaigns are, who are your loyal customers, and how powerful your brand is. %sIn this article%s, we’ll show you 7 proven ways to increase your returning visitor rate.', 'google-analytics-for-wordpress' ), '<a href="'. $this->build_external_link( 'https://www.monsterinsights.com/proven-ways-to-increase-your-returning-visitor-rate/' ) .'" target="_blank">', '</a>' );
			$notification['btns']    = array(
				"view_report" => array(
					'url'  => $this->get_view_url(),
					'text' => __( 'View Report', 'google-analytics-for-wordpress' )
				),
				"learn_more"  => array(
					'url'  => $this->build_external_link( 'https://www.monsterinsights.com/proven-ways-to-increase-your-returning-visitor-rate/' ),
					'text' => __( 'Learn More', 'google-analytics-for-wordpress' )
				),
			);

			return $notification;
		}

		return false;
	}

}

// initialize the class
new MonsterInsights_Notification_Returning_Visitors();
