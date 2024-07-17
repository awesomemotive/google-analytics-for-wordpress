<?php

/**
 * Add notification when no links set up for Affiliate tracking or just the default links exist
 * Recurrence: 25 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_To_Setup_Affiliate_Links extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_to_setup_affiliate_links';
	public $notification_interval = 25; // in days
	public $notification_type = array( 'basic', 'lite', 'master', 'plus', 'pro' );
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
		$affiliate_links = monsterinsights_get_option( 'affiliate_links', array() );
		$no_new_links    = false;

		if ( is_array( $affiliate_links ) && ! empty( $affiliate_links ) ) {
			if ( 2 === count( $affiliate_links ) && isset( $affiliate_links[0]['path'] ) && isset( $affiliate_links[1]['path'] ) ) {
				$no_new_links = "/go/" === $affiliate_links[0]['path'] && "/recommend/" === $affiliate_links[1]['path'] ? true : false;
			}
		}

		if ( true === $no_new_links || ( is_array( $affiliate_links ) && empty( $affiliate_links ) ) ) {

			$is_em = defined( 'EXACTMETRICS_VERSION' );

			$learn_more_url = $is_em
				? 'https://www.exactmetrics.com/how-to-set-up-affiliate-link-tracking-in-wordpress/'
				: 'https://www.monsterinsights.com/how-to-set-up-affiliate-link-tracking-in-wordpress/';

			$notification['title'] = __( 'Set Up Affiliate Link Tracking', 'google-analytics-for-wordpress' );
			$notification['content'] = sprintf(
				/* translators: Placeholders add a link to an article. */
				__( 'By tracking your affiliate links in Google Analytics, you can gather all the data you need to optimize your links for maximizing affiliate revenue. You can track affiliate link clicks on your website with little configuration needed.<br><br>%1$sIn this article%2$s, we’ll show you how to set up affiliate link tracking in WordPress.', 'google-analytics-for-wordpress' ),
				'<a href="' . $this->build_external_link( 'https://www.monsterinsights.com/how-to-set-up-affiliate-link-tracking-in-wordpress/' ) . '" target="_blank">',
				'</a>'
			);
			$notification['btns']    = array(
				"read_more" => array(
					'url'         => $this->build_external_link( $learn_more_url ),
					'text'        => __( 'Read More', 'google-analytics-for-wordpress' ),
					'is_external' => true,
				),
			);

			return $notification;
		}

		return false;
	}

}

// initialize the class
new MonsterInsights_Notification_To_Setup_Affiliate_Links();
