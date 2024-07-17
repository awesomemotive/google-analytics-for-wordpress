<?php

/**
 * Add notification for headline analyzer
 * Recurrence: 60 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Headline_Analyzer extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_headline_analyzer';
	public $notification_interval = 60; // in days
	public $notification_first_run_time = '+7 day';
	public $notification_type = array( 'basic', 'lite', 'master', 'plus', 'pro' );
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

		$is_em = defined( 'EXACTMETRICS_VERSION' );

		$learn_more_url = $is_em
			? 'https://www.exactmetrics.com/headline-analyzer/'
			: 'https://www.monsterinsights.com/headline-analyzer/';

		$notification['title'] = __( 'Try the Headline Analyzer to Boost Your Clicks & Traffic', 'google-analytics-for-wordpress' );
		// Translators: Headline Analyzer notification content.
		$notification['content'] = sprintf( __( 'Try the %1$sMonsterInsights Headline Analyzer%2$s tool. We built it to help increase engagement and make your content get more traffic from search engines.', 'google-analytics-for-wordpress' ), '<a href="' . $this->build_external_link( 'https://www.monsterinsights.com/announcing-monsterinsights-new-headline-analyzer/' ) . '" target="_blank">', '</a>' );
		$notification['btns']    = array(
			"learn_more" => array(
				'url'         => $this->build_external_link( $learn_more_url ),
				'text'        => __( 'Learn More', 'google-analytics-for-wordpress' ),
				'is_external' => true,
			),
		);

		return $notification;
	}

}

// initialize the class
new MonsterInsights_Notification_Headline_Analyzer();
