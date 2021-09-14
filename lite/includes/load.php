<?php
if ( is_admin() ) {
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/tools.php';

	//require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/tab-support.php';
}

if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
	$overview_report = new MonsterInsights_Report_Overview();
	MonsterInsights()->reporting->add_report( $overview_report );

	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/reports/report-publisher.php';
	$publisher_report = new MonsterInsights_Lite_Report_Publisher();
	MonsterInsights()->reporting->add_report( $publisher_report );

	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/reports/report-ecommerce.php';
	$ecommerce_report = new MonsterInsights_Lite_Report_eCommerce();
	MonsterInsights()->reporting->add_report( $ecommerce_report );

	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/reports/report-queries.php';
	$queries_report = new MonsterInsights_Lite_Report_Queries();
	MonsterInsights()->reporting->add_report( $queries_report );

	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/reports/report-dimensions.php';
	$dimensions_report = new MonsterInsights_Lite_Report_Dimensions();
	MonsterInsights()->reporting->add_report( $dimensions_report );

	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/reports/report-forms.php';
	$forms_report = new MonsterInsights_Lite_Report_Forms();
	MonsterInsights()->reporting->add_report( $forms_report );

	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/reports/report-realtime.php';
	$realtime_report = new MonsterInsights_Lite_Report_RealTime();
	MonsterInsights()->reporting->add_report( $realtime_report );

	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/reports/report-year-in-review.php';
	$year_in_review = new MonsterInsights_Lite_Report_YearInReview();
	MonsterInsights()->reporting->add_report( $year_in_review );
}

if ( is_admin() ) {
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/dashboard-widget.php';
	new MonsterInsights_Dashboard_Widget();

	// Load the Welcome class.
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/welcome.php';

	// Load the MonsterInsights Connect class.
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/connect.php';

	if ( isset( $_GET['page'] ) && 'monsterinsights-onboarding' === $_GET['page'] ) { // WPCS: CSRF ok, input var ok.
		// Only load the Onboarding wizard if the required parameter is present.
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/onboarding-wizard.php';
	}

	// Site Health logic.
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/wp-site-health.php';

	// Helper functions specific to this version of the plugin.
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/helpers.php';
}

if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
	// SharedCounts functionality.
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/sharedcount.php';
}

// Popular posts.
require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/popular-posts/class-popular-posts-themes.php';
require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/popular-posts/class-popular-posts.php';
// Lite popular posts specific.
require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/popular-posts/class-popular-posts-inline.php';
require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/popular-posts/class-popular-posts-cache.php';
require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/popular-posts/class-popular-posts-widget.php';
require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/popular-posts/class-popular-posts-widget-sidebar.php';
require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/popular-posts/class-popular-posts-ajax.php';
// Lite Gutenberg blocks.
require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/gutenberg/frontend.php';

