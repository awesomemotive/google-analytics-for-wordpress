<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Callback to output the MonsterInsights settings page.
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_tools_page() {
	/** 
	 * Developer Alert:
	 *
	 * Per the README, this is considered an internal hook and should
	 * not be used by other developers. This hook's behavior may be modified
	 * or the hook may be removed at any time, without warning.
	 */
	do_action( 'monsterinsights_head' );
	?>
	<?php echo monsterinsights_ublock_notice(); ?>

	<!-- Tabs -->
	<h1 id="monsterinsights-tools-page-main-nav" class="monsterinsights-main-nav-container monsterinsights-nav-container" data-container="#monsterinsights-tools-pages">
		<a class="monsterinsights-main-nav-item monsterinsights-nav-item monsterinsights-spacing-item" href="#">&nbsp;</a>

		<a class="monsterinsights-main-nav-item monsterinsights-nav-item monsterinsights-active" href="#monsterinsights-main-tab-settings" title="<?php echo esc_attr( __( 'Import/Export', 'google-analytics-for-wordpress' ) ); ?>">
			<?php echo esc_html__( 'Import/Export', 'google-analytics-for-wordpress' ); ?>
		</a>

		<a class="monsterinsights-main-nav-item monsterinsights-nav-item" href="#monsterinsights-main-tab-url-builder" title="<?php echo esc_attr( __( 'Campaign URL Builder', 'google-analytics-for-wordpress' ) ); ?>">
			<?php echo esc_html__( 'URL Builder', 'google-analytics-for-wordpress' ); ?>
		</a>
	</h1>


	<!-- Tab Panels -->
	<div id="monsterinsights-tools-pages" class="monsterinsights-main-nav-tabs monsterinsights-nav-tabs wrap" data-navigation="#monsterinsights-tools-page-main-nav">
		<h1 class="monsterinsights-hideme"></h1><!-- so wp notices are below the nav bar -->
		<div id="monsterinsights-main-tab-settings" class="monsterinsights-main-nav-tab monsterinsights-nav-tab monsterinsights-active">
			<?php monsterinsights_tools_settings_tab(); ?>
		</div>
		<div id="monsterinsights-main-tab-url-builder" class="monsterinsights-main-nav-tab monsterinsights-nav-tab">
			<?php monsterinsights_tools_url_builder_tab(); ?>
		</div>
	</div>
	<?php
}

function monsterinsights_tools_url_builder_tab(){
	do_action( 'monsterinsights_tools_url_builder_tab' );
}

function monsterinsights_tools_settings_tab() {
	ob_start();?>
	<h2><?php echo esc_html__( 'Setting Tools', 'google-analytics-for-wordpress' );?></h2>
	<p><?php echo esc_html__( 'You can use the below tools to import settings from other MonsterInsights websites or export settings to import into another MonsterInsights install.', 'google-analytics-for-wordpress' ); ?> </p>
	<br />
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="monsterinsights-import-settings">
						<?php echo esc_html__( 'Import Settings', 'google-analytics-for-wordpress' );?>
					</label>
				</th>
				<td>
					<?php 
					if ( ! empty( $_REQUEST['action'] ) && ! empty( $_REQUEST['result'] ) && $_REQUEST['action'] === 'import' && $_REQUEST['result'] === 'success' ){
						echo MonsterInsights()->notices->display_inline_notice( 'monsterinsights_standard_notice', '', __( 'Successfully imported settings!','google-analytics-for-wordpress'), 'success', false, array() );
					}
					?>
					<form method="post" enctype="multipart/form-data">
						<p>
							<input type="file" name="import_file"/>
							<input type="hidden" name="monsterinsights_action" value="monsterinsights_import_settings" />
						</p>
						<p class="description"><?php echo esc_html__( 'Paste the import field content from another MonsterInsights site in above.', 'google-analytics-for-wordpress' );?></p>
						<p>
							<?php wp_nonce_field( 'monsterinsights_import_settings', 'monsterinsights_import_settings' ); ?>
						</p>
						<p>
							<?php submit_button( __( 'Import', 'google-analytics-for-wordpress' ), 'monsterinsights-action-button button button-action', 'submit', false ); ?>
						</p>
					</form>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="monsterinsights-export-settings">
						<?php echo esc_html__( 'Export Settings:', 'google-analytics-for-wordpress' );?>
					</label>
				</th>
				<td>
					<form method="post" enctype="multipart/form-data">
						<p>
							<input type="hidden" name="monsterinsights_action" value="monsterinsights_export_settings" />
						</p>
						<p>
							<?php wp_nonce_field( 'monsterinsights_export_settings', 'monsterinsights_export_settings' ); ?>
						</p>
						<p>
							<?php submit_button( __( 'Export', 'google-analytics-for-wordpress' ), 'monsterinsights-settings-export monsterinsights-action-button button button-action', 'submit', false ); ?>
						</p>
					</form>
				</td>
			</tr>

		</tbody>
	</table>
	<?php
	echo ob_get_clean();
}

/**
 * MonsterInsights settings export.
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_process_export_settings() {
	if ( !isset( $_POST['monsterinsights_action'] ) || empty( $_POST['monsterinsights_action'] ) ) {
		return;
	}

	if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
		return;
	}

	if ( $_POST['monsterinsights_action'] !== 'monsterinsights_export_settings' ){
		return;
	}

	if ( empty( $_POST['monsterinsights_export_settings'] ) || ! wp_verify_nonce( $_POST['monsterinsights_export_settings'], 'monsterinsights_export_settings' ) ) {
		return;
	}
	
	$settings = monsterinsights_export_settings();
	ignore_user_abort( true );

	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=monsterinsights-settings-export-' . date( 'm-d-Y' ) . '.json' );
	header( "Expires: 0" );

	echo $settings;
	exit;
}
add_action( 'admin_init', 'monsterinsights_process_export_settings' );

function monsterinsights_import_settings() {

	if ( !isset( $_POST['monsterinsights_action'] ) || empty( $_POST['monsterinsights_action'] ) ) {
		return;
	}

	if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
		return;
	}

	if ( $_POST['monsterinsights_action'] !== 'monsterinsights_import_settings' ){
		return;
	}

	if ( !wp_verify_nonce( $_POST['monsterinsights_import_settings'], 'monsterinsights_import_settings' ) ) {
		return;
	}

	$extension = explode( '.', $_FILES['import_file']['name'] );
	$extension = end( $extension );

	if ( $extension != 'json' ) {
		wp_die( __( 'Please upload a valid .json file', 'google-analytics-for-wordpress' ) );
	}

	$import_file = $_FILES['import_file']['tmp_name'];

	if ( empty( $import_file ) ) {
		wp_die( __( 'Please upload a file to import', 'google-analytics-for-wordpress' ) );
	}

	$file     = file_get_contents( $import_file );
	if ( empty( $file ) ) {
		wp_die( __( 'Please upload a real settings export file to import', 'google-analytics-for-wordpress' ) );
	}

	// Retrieve the settings from the file and convert the json object to an array.
	$new_settings = json_decode( wp_json_encode( json_decode( $file ) ), true );
	$settings     = monsterinsights_get_options();
	$exclude      = array( 
						'analytics_profile',
						'analytics_profile_code',
						'analytics_profile_name',
						'oauth_version',
						'cron_last_run',
						'monsterinsights_oauth_status',
	);

	foreach ( $exclude as $e ) {
		if ( ! empty( $new_settings[ $e ] ) ) {
			unset( $new_settings[ $e ] );
		}
	}

	if ( ! is_super_admin() ) {
		if ( ! empty( $new_settings[ 'custom_code' ] ) ) {
			unset( $new_settings[ 'custom_code' ] );
		}
	}

	foreach ( $exclude as $e ) {
		if ( ! empty( $settings[ $e ] ) ) {
			$new_settings = $settings[ $e ];
		}
	}

	global $monsterinsights_settings;
	$monsterinsights_settings = $new_settings;

	update_option( monsterinsights_get_option_name(), $new_settings );
	wp_safe_redirect( admin_url( 'admin.php?page=monsterinsights_tools&action=import&result=success#monsterinsights-main-tab-settings' ) ); exit;
}
add_action( 'admin_init', 'monsterinsights_import_settings' );