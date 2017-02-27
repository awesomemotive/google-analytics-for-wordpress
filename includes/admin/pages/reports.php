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
	
	<!-- Tabs -->
	<h1 id="monsterinsights-reports-page-main-nav" class="monsterinsights-main-nav-container monsterinsights-nav-container" data-container="#monsterinsights-reports-pages" data-update-hashbang="1">
		<?php 
		$i = 0;
		?>
		<a class="monsterinsights-main-nav-item monsterinsights-nav-item monsterinsights-spacing-item" href="#">&nbsp;</a>
		<?php
		foreach ( (array) monsterinsights_get_reports() as $id => $title ) {
			$class = ( 0 === $i ? 'monsterinsights-active' : '' ); 
			?>
			<a class="monsterinsights-main-nav-item monsterinsights-nav-item <?php echo $class; ?>" href="#monsterinsights-main-tab-<?php echo esc_attr( $id ); ?>" title="<?php echo esc_attr( $title ); ?>">
				<?php echo esc_html( $title ); ?>
			</a>
			<?php 
			$i++; 
		}
		?>
	</h1>

	<div id="monsterinsights-reports" class="wrap">
		<div class="monsterinsights-clear">
			<div class="monsterinsights-reports-action-bar">
				<div class="monsterinsights-reports-action-bar-title">
					<?php esc_html_e( 'Reports', 'google-analytics-for-wordpress' );?>
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

			<!-- Tab Panels -->
			<div id="monsterinsights-reports-pages" class="monsterinsights-main-nav-tabs monsterinsights-nav-tabs" data-navigation="#monsterinsights-reports-page-main-nav">
				<h1 class="monsterinsights-hideme"></h1><!-- so wp notices are below the nav bar -->
				<?php 
				$i = 0; 
				foreach ( (array) monsterinsights_get_reports() as $id => $title ) {
					$class = ( 0 === $i ? 'monsterinsights-active' : '' ); 
					?>
					<div id="monsterinsights-main-tab-<?php echo esc_attr( $id ); ?>" class="monsterinsights-main-nav-tab monsterinsights-nav-tab <?php echo $class; ?>">
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
							<?php do_action( 'monsterinsights_tab_reports_' . $id ); ?>
						</div>
						<?php } ?>
					</div>
					<?php
					$i++;
				}
				?>
			</div>
		</div>
	</div>
	<?php
}