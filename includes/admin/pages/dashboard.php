<?php
/**
 * Callback for getting all of the reports tabs for MonsterInsights.
 *
 * @since 6.0.0
 * @access public
 *
 * @return array Array of tab information.
 */
function monsterinsights_get_dashboard_report() {
	$reports = monsterinsights_get_reports();
	$picked  = monsterinsights_get_option( 'dashboard_report', 'overview' );
	if ( ! empty( $reports ) && is_array( $reports ) && array_key_exists ( $picked, $reports ) ) {
		return array( 'id' => $picked, 'title' => $reports[ $picked ] );
	} else {
		return array();
	}
}

/**
 * Callback to output the MonsterInsights reports page.
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_dashboard_page() {
	/** 
	 * Developer Alert:
	 *
	 * Per the README, this is considered an internal hook and should
	 * not be used by other developers. This hook's behavior may be modified
	 * or the hook may be removed at any time, without warning.
	 */
	do_action( 'monsterinsights_head' );

	if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
		wp_die( esc_html__( 'Access denied' , 'google-analytics-for-wordpress' ) );
	}

	$dashboard_disabled = monsterinsights_get_option( 'dashboard_disabled', false );
	$profile_enabled    = monsterinsights_get_option( 'analytics_profile', false ); // not using profile
	$oauth_version      = monsterinsights_get_option( 'oauth_version', '1.0' );
	$last_run           = monsterinsights_get_option( 'cron_last_run', false );
	$failed             = monsterinsights_get_option( 'cron_failed', false );
	$pro_access_key     = get_option( 'monsterinsights_pro_access_token', false );
	$lite_access_key    = get_option( 'monsterinsights_lite_access_token', false );
	$needs_re_auth      = ( ( $failed && ( $last_run === false || monsterinsights_hours_between( $last_run ) >= 48 ) ) || ( empty( $pro_access_key ) && empty( $lite_access_key ) ) || ( version_compare( $oauth_version, '1.0', '<' ) )  ) ? true : false;
	?>
	<?php echo monsterinsights_ublock_notice(); ?>
	<div id="monsterinsights-reports" class="wrap">
		<div class="monsterinsights-clear">
			<div class="monsterinsights-reports-action-bar">
				<div class="monsterinsights-reports-action-bar-title">
					<?php esc_html_e( 'Dashboard', 'google-analytics-for-wordpress' );?>
				</div>
				<div class="monsterinsights-reports-action-bar-actions"><?php 
					/** 
					 * Developer Alert:
					 *
					 * Per the README, this is considered an internal hook and should
					 * not be used by other developers. This hook's behavior may be modified
					 * or the hook may be removed at any time, without warning.
					 */
					do_action( 'monsterinsights_tab_reports_actions' ); 
					?> 
				</div>
			</div>
			<?php 
			$report = monsterinsights_get_dashboard_report();
			?>
			<div id="monsterinsights_dashboard_container">
				<?php if ( $dashboard_disabled ) { ?>
						<?php 
						if ( current_user_can( 'monsterinsights_save_settings' ) ) {
							echo monsterinsights_get_message( 'error', sprintf(
								sprintf(
									 esc_html__( 'Please %1$senable the dashboard%2$s to see report data.', 'google-analytics-for-wordpress' ),
										'<a href="' . admin_url( 'admin.php?page=monsterinsights_settings' ) . '">',
										'</a>'
									)
								) );
						} else {
							echo monsterinsights_get_message( 'error', esc_html__( 'The Google oAuth authentication needs to be re-authenticated to view data.', 'google-analytics-for-wordpress' ) );
						}
						?>
				<?php } else if ( ! $profile_enabled ) { ?>
						<?php 
						if ( current_user_can( 'monsterinsights_save_settings' ) ) {
							echo monsterinsights_get_message( 'error', sprintf(
								sprintf(
									 esc_html__( 'Please %1$sauthenticate %2$swith Google Analytics to allow the plugin to fetch data.', 'google-analytics-for-wordpress' ),
										'<a href="' . admin_url( 'admin.php?page=monsterinsights_settings' ) . '">',
										'</a>'
									)
								) );
						} else {
							echo monsterinsights_get_message( 'error', esc_html__( 'The Google oAuth authentication needs to be re-authenticated to view data.', 'google-analytics-for-wordpress' ) );
						}
						?>
				<?php } else if ( $needs_re_auth ) { ?>
						<?php 
						if ( current_user_can( 'monsterinsights_save_settings' ) ) {
							echo monsterinsights_get_message( 'error', sprintf(
								sprintf(
									 esc_html__( 'Please %1$sre-authenticate%2$s with Google Analytics to allow the plugin to fetch data.', 'google-analytics-for-wordpress' ),
										'<a href="' . admin_url( 'admin.php?page=monsterinsights_settings' ) . '">',
										'</a>'
									)
								) );
						} else {
							echo monsterinsights_get_message( 'error', esc_html__( 'The Google oAuth authentication needs to be re-authenticated to view data.', 'google-analytics-for-wordpress' ) );
						}
						?>
				<?php } else if ( $failed ) { ?>
						<?php 
						if ( current_user_can( 'monsterinsights_save_settings' ) ) {
							echo monsterinsights_get_message( 'error', sprintf(
								sprintf(
									esc_html__( 'Data is not up-to-date, there was an error in retrieving the data from Google Analytics. This error could be caused by several issues. If the error persists, please see %1$sthis page%2$s.', 'google-analytics-for-wordpress' ),
									'<a href="https://www.monsterinsights.com/docs/blocked-connection/">',
									'</a>'
								)
							) );
						} else {
							echo monsterinsights_get_message( 'error', esc_html__( 'The Google oAuth authentication needs to be re-authenticated to view data.', 'google-analytics-for-wordpress' ) );
						}
						?>
				<?php } else { ?>
				 <div class="monsterinsights-reports-wrap">
					<?php
					/** 
					 * Developer Alert:
					 *
					 * Per the README, this is considered an internal hook and should
					 * not be used by other developers. This hook's behavior may be modified
					 * or the hook may be removed at any time, without warning.
					 */
					?>
					<?php do_action( 'monsterinsights_tab_reports_notices' ); ?>
					<?php
					/** 
					 * Developer Alert:
					 *
					 * Per the README, this is considered an internal hook and should
					 * not be used by other developers. This hook's behavior may be modified
					 * or the hook may be removed at any time, without warning.
					 */
					?>
					<?php do_action( 'monsterinsights_tab_reports_' . $report['id'] ); ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
}