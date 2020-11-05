<?php

/**
 * Add notification for headline analyzer
 * Recurrence: 60 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Headline_Analyzer extends MonsterInsights_Notification_Event {

	public $notification_id             = 'monsterinsights_notification_headline_analyzer';
	public $notification_interval       = 60; // in days
	public $notification_first_run_time = '+7 day';
	public $notification_type           = array( 'basic', 'lite', 'master', 'plus', 'pro' );

	/**
	 * Build Notification
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		$notification['title']   = __( 'Headline Analyzer to Boost Your Clicks & Traffic', 'google-analytics-for-wordpress' );
		// Translators: Headline Analyzer notification content
		$notification['content'] = sprintf( __( 'Did you know that 36%% of SEO experts think the headline is the most important SEO element? Yet many website owners don’t know how to optimize their headlines for SEO and clicks. Instead, they write copy and hope for the best, only to see disappointing results. Now there’s an easier way! <br><br>%sWith the MonsterInsights Headline Analyzer%s, you can get targeted suggestions to improve your headlines, right in the WordPress editor.', 'google-analytics-for-wordpress' ), '<a href="'. $this->build_external_link('https://www.monsterinsights.com/announcing-monsterinsights-new-headline-analyzer/' ) .'" target="_blank">', '</a>' );
		$notification['btns'] = array(
			"learn_more" => array(
				'url'   => $this->build_external_link('https://www.monsterinsights.com/announcing-monsterinsights-new-headline-analyzer/' ),
				'text'  => __( 'Learn More', 'google-analytics-for-wordpress' )
			),
		);

		return $notification;
	}

}

// initialize the class
new MonsterInsights_Notification_Headline_Analyzer();
