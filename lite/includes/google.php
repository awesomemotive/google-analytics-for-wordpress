<?php
function monsterinsights_google_app_config() {
	// We'll go ahead and ask for these permissions on new installs
	$scopes = array( 
		'https://www.googleapis.com/auth/analytics.readonly',
		'https://www.googleapis.com/auth/analytics',
	//	'https://www.googleapis.com/auth/analytics.manage.users',
	//	'https://www.googleapis.com/auth/tagmanager.readonly',
	//	'https://www.googleapis.com/auth/webmasters.readonly'
	);
	$config = array(
		'application_name' => 'Google Analytics by MonsterInsights',
		'client_id'        => '346753076522-21smrc6aq0hq8oij8001s57dfoo8igf5.apps.googleusercontent.com',
		'client_secret'    => '5oWaEGFgp-bSrY6vWBmdPfIF',
		'redirect_uri'     => 'urn:ietf:wg:oauth:2.0:oob',
		'scopes'           => $scopes,
	);

	$config = apply_filters( 'monsterinsights_lite_google_app_config', $config );
	$config['scopes'] = $scopes; // Scopes requested are not changeable to minimize breakage.
	return $config;
}

function monsterinsights_create_client() {
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'assets/lib/google/src/Google/autoload.php';
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'assets/lib/google/class-ga-client.php';
	$config = monsterinsights_google_app_config();
	$client = new MonsterInsights_GA_Client( $config, 'lite' );
	return $client;
}

function monsterinsights_create_test_client() {
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'assets/lib/google/src/Google/autoload.php';
	require_once MONSTERINSIGHTS_PLUGIN_DIR . 'assets/lib/google/class-ga-client.php';
	$config = monsterinsights_google_app_config();
	$client = new MonsterInsights_GA_Client( $config, 'test_lite' );
	return $client;
}

function monsterinsights_set_client_oauth_version(){
	monsterinsights_update_option( 'oauth_version', '1.0' );
}

function monsterinsights_get_report_date_range() {
	return array(
		'start' => date( 'Y-m-d', strtotime( '-1 month' ) ),
		'end'   => date( 'Y-m-d', strtotime( 'yesterday' ) ),
	);
}