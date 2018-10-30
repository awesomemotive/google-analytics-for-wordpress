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
	echo monsterinsights_ublock_notice(); ?>
	
	<!-- Tabs -->
	<h1 id="monsterinsights-reports-page-main-nav" class="monsterinsights-main-nav-container monsterinsights-nav-container" data-container="#monsterinsights-reports-pages" data-update-hashbang="1">
		<?php 
		$i = 0;
		?>
		
		<?php
		foreach ( (array) MonsterInsights()->reporting->reports as $report ) {
			?>
			<a class="monsterinsights-main-nav-item monsterinsights-nav-item " href="#monsterinsights-main-tab-<?php echo esc_attr( $report->name ); ?>" title="<?php echo esc_attr( $report->title ); ?>"><?php echo esc_html( $report->title ); ?></a>
			<?php 
			$i++; 
		}
		?>
	</h1>

	<div id="monsterinsights-reports" class="wrap monsterinsights-bootstrap-container">
		<div class="monsterinsights-clear">

			<!-- Tab Panels -->
			<div id="monsterinsights-reports-pages" class="monsterinsights-main-nav-tabs monsterinsights-nav-tabs" data-navigation="#monsterinsights-reports-page-main-nav">
				
				<?php 
				$i = 0; 
				foreach ( (array) MonsterInsights()->reporting->reports as $report ) {
					?>
					<div id="monsterinsights-main-tab-<?php echo esc_attr( $report->name ); ?>" class="monsterinsights-main-nav-tab monsterinsights-nav-tab">
						<div class="monsterinsights-reports-action-bar monsterinsights-clear">
							<div class="monsterinsights-reports-action-bar-title">
								<?php echo esc_html( sprintf( __( '%s Report', 'google-analytics-for-wordpress' ), $report->title  ) ); ?>
							</div>
							<div class="monsterinsights-reports-action-bar-actions"><?php 
								/** 
								 * Developer Alert:
								 *
								 * Per the README, this is considered an internal hook and should
								 * not be used by other developers. This hook's behavior may be modified
								 * or the hook may be removed at any time, without warning.
								 */
								do_action( 'monsterinsights_tab_reports_actions', $report->name ); 
								?> 
							</div>
						</div>
						 <div class="monsterinsights-reports-wrap container-fluid">
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
							<?php //echo $report->show_report(); @todo we don't need to init output them. ?>
						</div>
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

function monsterinsights_refresh_reports_data() {
	check_ajax_referer( 'mi-admin-nonce', 'security' );

	// Get variables
	$start 		 = ! empty( $_REQUEST['start'] )  		? $_REQUEST['start']		: '';
	$end 		 = ! empty( $_REQUEST['end'] )    		? $_REQUEST['end']   		: '';
	$name        = ! empty( $_REQUEST['report'] )    	? $_REQUEST['report']       : '';
	$isnetwork   = ! empty( $_REQUEST['isnetwork'] )    ? $_REQUEST['isnetwork']    : '';


	// Current user can authenticate
	if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
		wp_send_json_error( array(	'message' => __( "You don't have permission to view MonsterInsights reports.", 'google-analytics-for-wordpress' ) ) );
	}

	if ( ! empty( $_REQUEST['isnetwork'] ) && $_REQUEST['isnetwork'] ) {
		define( 'WP_NETWORK_ADMIN', true );
	}

	// Only for Pro users, require a license key to be entered first so we can link to things.
	if ( monsterinsights_is_pro_version()  ) {
		if ( ! MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->is_network_licensed() ) {
			wp_send_json_error( array(	'message' => __( "You can't view MonsterInsights reports because you are not licensed.", 'google-analytics-for-wordpress' ) ) );
		} else if ( MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->site_license_has_error() ) {
			// good to go: site licensed
		} else if ( MonsterInsights()->license->is_network_licensed() && ! MonsterInsights()->license->network_license_has_error() ) {
			// good to go: network licensed
		} else {
			wp_send_json_error( array(	'message' => __( "You can't view MonsterInsights reports due to license key errors.", 'google-analytics-for-wordpress' ) ) );
		}
	}

	// we do not have a current auth
	$site_auth   = MonsterInsights()->auth->get_viewname();
	$ms_auth     = is_multisite() && MonsterInsights()->auth->get_network_viewname();
	if ( ! $site_auth && ! $ms_auth ) {
		wp_send_json_error( array(	'message' => __( "You must authenticate with MonsterInsights before you can view reports.", 'google-analytics-for-wordpress' ) ) );
	}

	if ( empty( $name ) ) {
		wp_send_json_error( array(	'message' => __( "Unknown report. Try refreshing and retrying. Contact support if this issue persists.", 'google-analytics-for-wordpress' ) ) );
	}

	$report = MonsterInsights()->reporting->get_report( $name );

	if ( empty( $report ) ) {
		wp_send_json_error( array(	'message' => __( "Unknown report. Try refreshing and retrying. Contact support if this issue persists.", 'google-analytics-for-wordpress' ) ) );
	}

	$args  = array( 'start' => $start, 'end' => $end );
	if ( $isnetwork ) {
		$args['network'] = true;
	}

	$data  = $report->get_data( $args );
	if ( ! empty( $data['success'] ) ) {
		$data = $report->show_report( 
			array( 'start'   => $start,
				   'end'     => $end,
				   'data'    => $data['data'],
				   'success' => true 
			) 
		);
		wp_send_json_success( array( 'html' => $data  ) );
	} else {
		wp_send_json_error( array( 'message' => $data['error'], 'data' => $data['data'] ) );
	}
}
add_action( 'wp_ajax_monsterinsights_refresh_reports', 'monsterinsights_refresh_reports_data' );