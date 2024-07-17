<?php

class MonsterInsights_WP_Site_Health {
	public function __construct() {
		add_filter( 'site_status_tests', array( $this, 'add_tests' ) );
	}

	public function add_tests( $tests ) {
		$tests['direct']['monsterinsights_dual_tracking'] = array(
			'label' => __( 'MonsterInsights Dual Tracking', 'google-analytics-for-wordpress' ),
			'test'  => array( $this, 'test_dual_tracking' ),
		);

		return $tests;
	}

	public function test_dual_tracking() {

		$has_v4_id = strlen( monsterinsights_get_v4_id() ) > 0;

		if ( $has_v4_id ) {
			return false;
		}

		$setup_link = add_query_arg( array(
			'page'                      => 'monsterinsights_settings',
			'monsterinsights-scroll'    => 'monsterinsights-dual-tracking-id',
			'monsterinsights-highlight' => 'monsterinsights-dual-tracking-id',
		), admin_url( 'admin.php' ) );

		return array(
			'label'       => __( 'Enable Google Analytics 4', 'google-analytics-for-wordpress' ),
			'status'      => 'critical',
			'badge'       => array(
				'label' => __( 'MonsterInsights', 'google-analytics-for-wordpress' ),
				'color' => 'blue',
			),
			'description' => __( 'Starting July 1, 2023, Google\'s Universal Analytics (GA3) will not accept any new traffic or event data. Upgrade to Google Analytics 4 today to be prepared for the sunset.', 'google-analytics-for-wordpress' ),
			'actions'     => sprintf(
				'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></p>',
				$setup_link,
				__( 'Set Up Dual Tracking', 'google-analytics-for-wordpress' )
			),
			'test'        => 'monsterinsights_dual_tracking',
		);
	}
}

new MonsterInsights_WP_Site_Health();
