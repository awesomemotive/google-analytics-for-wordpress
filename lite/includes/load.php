<?php

require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/settings.php';

if ( is_admin() ) {		
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/tools.php';

	//require_once MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/admin/tab-support.php';

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
}