<?php
/**
 * Reports class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Reports
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function monsterinsights_reports_page_body_class( $classes ) {
	if ( ! empty( $_REQUEST['page'] ) && $_REQUEST['page'] === 'monsterinsights_reports' ) {
		$classes .= ' monsterinsights-reporting-page ';
	}
	return $classes;
}
add_filter( 'admin_body_class', 'monsterinsights_reports_page_body_class' );

/**
 * Callback for getting all of the reports tabs for MonsterInsights.
 *
 * @since 6.0.0
 * @access public
 *
 * @return array Array of tab information.
 */
function monsterinsights_get_reports() {
	/**
	 * Developer Alert:
	 *
	 * Per the README, this is considered an internal hook and should
	 * not be used by other developers. This hook's behavior may be modified
	 * or the hook may be removed at any time, without warning.
	 */
	$reports =  apply_filters( 'monsterinsights_get_reports', array() );
	return $reports;
}

/**
 * Callback to output the MonsterInsights reports page.
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_reports_page() {
	/**
	 * Developer Alert:
	 *
	 * Per the README, this is considered an internal hook and should
	 * not be used by other developers. This hook's behavior may be modified
	 * or the hook may be removed at any time, without warning.
	 */
	do_action( 'monsterinsights_head' );
	echo monsterinsights_ublock_notice();
	monsterinsights_settings_error_page( 'monsterinsights-reports');
}
