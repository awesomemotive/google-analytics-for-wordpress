<?php

/**
 * Add audience notification
 * Recurrence: 30 Days
 *
 * @since 7.12.3
 */
final class MonsterInsights_Notification_Audience extends MonsterInsights_Notification_Event {

	public $notification_id = 'monsterinsights_notification_audience';
	public $notification_interval = 30; // in days
	public $notification_type = array( 'basic', 'lite', 'master', 'plus', 'pro' );

	/**
	 * Build Notification
	 *
	 * @param array $notification
	 * @param array $data
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function prepare_notification_data( $notification ) {
		$data = $this->get_notification_data();

		if ( ! is_array( $data ) || empty( $data ) ) {
			return false;
		}

		// Translators: Audience notification title
		$notification['title'] = sprintf( __( '%s%% of your Audience is from %s', 'google-analytics-for-wordpress' ), $data['percentage'], $data['country'] );
		// Translators: Audience notification content
		$notification['content'] = sprintf( __( 'Is your site properly translated? By adding translated content specific to your audience you could gain big boosts in pageviews, time spent on page and a reduced bounce rate.<br><br>If you need help choosing a translation plugin to get you started take a look at %sthis article%s for the best options available.', 'google-analytics-for-wordpress' ), '<a href="' . $this->build_external_link( 'https://www.wpbeginner.com/showcase/9-best-translation-plugins-for-wordpress-websites/' ) . '" target="_blank">', '</a>' );
		$notification['btns']    = array(
			"view_report" => array(
				'url'  => $this->get_view_url(),
				'text' => __( 'View Report', 'google-analytics-for-wordpress' )
			),
			"learn_more"  => array(
				'url'  => $this->build_external_link( 'https://www.wpbeginner.com/showcase/9-best-translation-plugins-for-wordpress-websites/' ),
				'text' => __( 'Learn More', 'google-analytics-for-wordpress' )
			),
		);

		return $notification;
	}

	/**
	 * Add report to notifications
	 *
	 * @since 7.12.3
	 */
	public function get_notification_data() {
		require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );

		$data                       = array();
		$report                     = $this->get_report();
		$sessions                   = isset( $report['data']['infobox']['sessions']['value'] ) ? $report['data']['infobox']['sessions']['value'] : 0;
		$countries                  = isset( $report['data']['countries'] ) ? $report['data']['countries'] : 0;
		$english_speaking_countries = monsterinsights_get_english_speaking_countries();

		if ( $sessions > 0 && is_array( $countries ) && ! empty( $countries ) ) {
			foreach ( $countries as $country ) {
				if ( empty( $country['iso'] ) || array_key_exists( $country['iso'], $english_speaking_countries ) ) {
					continue;
				}

				if ( $country['sessions'] > 0 ) {
					// get the country's session percentage by comparing with the total sessions
					$country_session_percentage = round( $country['sessions'] * 100 / $sessions );

					if ( $country_session_percentage < 15 ) {
						continue;
					}

					$site_language = get_locale();
					$translations  = wp_get_available_translations();

					if ( is_array( $translations ) && ! empty( $translations ) ) {
						$site_iso = isset( $translations[ $site_language ]['iso'] ) ? $translations[ $site_language ]['iso'] : array(); // keep empty array, because site language has no iso setup for en_US language

						if ( is_array( $site_iso ) && ! in_array( $country['iso'], $site_iso ) ) {
							$data['country']    = $country['name'];
							$data['percentage'] = $country_session_percentage;
							break;
						}
					}
				}
			}
		}

		return $data;
	}

}

// initiate the class
new MonsterInsights_Notification_Audience();
