<?php


class MonsterInsights_Notification_Multiple_Gtags extends MonsterInsights_Notification_Event {
	public $notification_id = 'monsterinsights_notification_multiple_gtags';
	public $notification_interval = 30;
	public $notification_type = array( 'basic', 'lite', 'master', 'plus', 'pro' );
	public $notification_category = 'alert';
	public $notification_icon = 'warning';
	public $notification_priority = 1;

	public function prepare_notification_data( $notification ) {

		$response = wp_remote_get( site_url() );

		if ( is_array( $response ) ) {
			$content = $response['body'];

			$document = new DOMDocument();
			libxml_use_internal_errors( true );
			@$document->loadHTML( $content );
			libxml_clear_errors();

			$gtag_count = 0;

			foreach ( $document->getElementsByTagName( 'script' ) as $script ) {
				$script_src = $script->getAttribute( 'src' );

				if ( preg_match( "/googletagmanager.com\/gtag\/js/i", $script_src ) ) {
					$gtag_count ++;
				}
			}

			if ( $gtag_count < 2 ) {
				return false;
			}

			$notification['title']   = __( "Multiple Google Analytics Tags Found", "google-analytics-for-wordpress" );
			$notification['content'] = __( "MonsterInsights has detected multiple analytics tags on your website. Please disable the other plugin to ensure accurate tracking.", 'google-analytics-for-wordpress' );

			return $notification;
		}

		return false;
	}
}

new MonsterInsights_Notification_Multiple_Gtags();
