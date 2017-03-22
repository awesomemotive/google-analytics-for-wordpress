<?php
/**
 * Tools class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Tools
 * @author  Chris Christoff
 */

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

		<a class="monsterinsights-main-nav-item monsterinsights-nav-item monsterinsights-active" href="#monsterinsights-main-tab-url-builder" title="<?php echo esc_attr( __( 'Campaign URL Builder', 'google-analytics-for-wordpress' ) ); ?>">
			<?php echo esc_html__( 'URL Builder', 'google-analytics-for-wordpress' ); ?>
		</a>

		<a class="monsterinsights-main-nav-item monsterinsights-nav-item" href="#monsterinsights-main-tab-settings" title="<?php echo esc_attr( __( 'Settings', 'google-analytics-for-wordpress' ) ); ?>">
			<?php echo esc_html__( 'Settings', 'google-analytics-for-wordpress' ); ?>
		</a>
	</h1>


	<!-- Tab Panels -->
	<div id="monsterinsights-tools-pages" class="monsterinsights-main-nav-tabs monsterinsights-nav-tabs wrap" data-navigation="#monsterinsights-tools-page-main-nav">
		<h1 class="monsterinsights-hideme"></h1><!-- so wp notices are below the nav bar -->
		 <div id="monsterinsights-main-tab-url-builder" class="monsterinsights-main-nav-tab monsterinsights-nav-tab monsterinsights-active">
			<?php monsterinsights_tools_url_builder_tab(); ?>
		</div>
		 <div id="monsterinsights-main-tab-settings" class="monsterinsights-main-nav-tab monsterinsights-nav-tab">
			<?php monsterinsights_tools_settings_tab(); ?>
		</div>
	</div>
	<?php
}

function monsterinsights_tools_url_builder_tab() {
	ob_start();?>
	<h2><?php echo esc_html__( 'Generate custom campaign parameters for your advertising URLS.', 'google-analytics-for-wordpress' );?></h2>
	<p><?php echo  esc_html__( 'The URL builder helps you add parameters to your URLs you use in custom web-based or email ad campaigns. A custom campaign is any ad campaign not using the AdWords auto-tagging feature. When users click one of the custom links, the unique parameters are sent to your Analytics account, so you can identify the urls that are the most effective in attracting users to your content.', 'google-analytics-for-wordpress' ); ?> </p>
	<p><?php echo esc_html__('Fill out the required fields (marked with *) in the form below, and as you make changes the full campaign URL will be generated for you.', 'google-analytics-for-wordpress' ); ?></p>
	<br />
	<form id="monsterinsights-url-builder" action="javascript:void(0);">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="monsterinsights-url-builer-domain">
							<?php echo esc_html__( 'Website URL', 'google-analytics-for-wordpress' );?><span class="monsterinsights-required-indicator">*</span>
						</label>
					</th>
					<td>
						<input type="url" name="domain" id="monsterinsights-url-builer-domain" value="" />
						<p class="description"><?php echo sprintf( esc_html__( 'The full website URL (e.g. %1$s)', 'google-analytics-for-wordpress' ), home_url() );?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="monsterinsights-url-builer-source">
							<?php echo esc_html__( 'Campaign Source', 'google-analytics-for-wordpress' );?><span class="monsterinsights-required-indicator">*</span>
						</label>
					</th>
					<td>
						<input type="text" name="source" id="monsterinsights-url-builer-source" value="" />
						<p class="description"><?php echo sprintf( esc_html__( 'Enter a referrer (e.g. %1$s, %2$s, %3$s)', 'google-analytics-for-wordpress' ), '<code>facebook</code>', '<code>newsletter</code>', '<code>google</code>' );?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="monsterinsights-url-builer-medium">
							<?php echo esc_html__( 'Campaign Medium', 'google-analytics-for-wordpress' );?>
						</label>
					</th>
					<td>
						<input type="text" name="medium" id="monsterinsights-url-builer-medium" value="" />
						<p class="description"><?php echo sprintf( esc_html__( 'Enter a marketing medium (e.g. %1$s, %2$s, %3$s)', 'google-analytics-for-wordpress' ), '<code>cpc</code>', '<code>banner</code>', '<code>email</code>' );?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="monsterinsights-url-builer-name">
							<?php echo esc_html__( 'Campaign Name', 'google-analytics-for-wordpress' );?>
						</label>
					</th>
					<td>
						<input type="text" name="name" id="monsterinsights-url-builer-name" value="" />
						<p class="description"><?php echo sprintf( esc_html__( 'Enter a name to identify the campaign (e.g. %1$s)', 'google-analytics-for-wordpress' ), '<code>spring_sale</code>' );?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="monsterinsights-url-builer-term">
							<?php echo esc_html__( 'Campaign Term', 'google-analytics-for-wordpress' );?>
						</label>
					</th>
					<td>
						<input type="text" name="term" id="monsterinsights-url-builer-term" value="" />
						<p class="description"><?php echo esc_html__( 'Enter the paid keyword', 'google-analytics-for-wordpress' );?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="monsterinsights-url-builer-content">
							<?php echo esc_html__( 'Campaign Content', 'google-analytics-for-wordpress' );?>
						</label>
					</th>
					<td>
						<input type="text" name="content" id="monsterinsights-url-builer-content" value="" />
						<p class="description"><?php echo esc_html__( 'Enter something to differentiate ads', 'google-analytics-for-wordpress' );?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="monsterinsights-url-builer-fragment">
							<?php echo esc_html__( 'Use Fragment', 'google-analytics-for-wordpress' );?>
						</label>
					</th>
					<td>
						<input type="checkbox" name="fragment" id="monsterinsights-url-builer-fragment" value="" />
						<p class="description"><?php echo esc_html__( 'Set the parameters in the fragment portion of the URL (not recommended).', 'google-analytics-for-wordpress' );?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="monsterinsights-url-builer-url">
							<?php echo esc_html__( 'URL to use (updates automatically):', 'google-analytics-for-wordpress' );?>
						</label>
					</th>
					<td>
						<textarea name="url" id="monsterinsights-url-builer-url" value="" readonly="readonly"></textarea>
						<p>
							<button class="monsterinsights-copy-to-clipboard monsterinsights-action-button button button-action" data-clipboard-target="#monsterinsights-url-builer-url">
								<?php echo esc_html__( 'Copy to clipboard' ,'google-analytics-for-wordpress');?>
							</button>
							<button id="monsterinsights-shorten-url" class="monsterinsights-action-button button button-secondary" style="margin-left: 20px;">
								<?php echo esc_html__( 'Shorten URL' ,'google-analytics-for-wordpress');?>
							</button>
						</p>
					</td>
				</tr>

			</tbody>
		</table>
	</form>
	<h2><?php echo esc_html__( 'More information and examples for each option', 'google-analytics-for-wordpress');?></h2>
	<p><?php echo esc_html__( 'The following table gives a detailed explanation and example of each of the campaign parameters.', 'google-analytics-for-wordpress');?></p>
	<table class="wp-list-table widefat striped">
	  <tbody>
		<tr>
		  <td>
			<p><strong><?php echo esc_html__( 'Campaign Source', 'google-analytics-for-wordpress');?></strong></p>
			<p><code>utm_source</code></p>
		  </td>
		  <td>
			<p><strong><?php echo esc_html__( 'Required.', 'google-analytics-for-wordpress');?></strong></p>
			<p><?php echo sprintf( esc_html__( 'Use %1$s to identify a search engine, newsletter name, or other source.', 'google-analytics-for-wordpress'),'<code>utm_source</code>');?></p>
			<p><em><?php echo esc_html__( 'Example:', 'google-analytics-for-wordpress');?></em> <code>google</code></p>
		  </td>
		</tr>
		<tr>
		  <td>
			<p><strong><?php echo esc_html__( 'Campaign Medium', 'google-analytics-for-wordpress');?></strong></p>
			<p><code>utm_medium</code></p>
		  </td>
		  <td>
			<p><?php echo sprintf(esc_html__( 'Use %1$s to identify a medium such as email or cost-per- click.', 'google-analytics-for-wordpress'),'<code>utm_medium</code>');?></p>
			<p><em><?php echo esc_html__( 'Example:', 'google-analytics-for-wordpress');?></em> <code>cpc</code></p>
		  </td>
		</tr>
		<tr>
		  <td>
			<p><strong><?php echo esc_html__( 'Campaign Name', 'google-analytics-for-wordpress');?></strong></p>
			<p><code>utm_campaign</code></p>
		  </td>
		  <td>
			<p><?php echo sprintf(esc_html__( 'Used for keyword analysis. Use %1$s to identify a specific product promotion or strategic campaign.', 'google-analytics-for-wordpress'),'<code>utm_campaign</code>');?></p>
			<p><em><?php echo esc_html__( 'Example:', 'google-analytics-for-wordpress');?></em> <code>utm_campaign=spring_sale</code></p>
		  </td>
		</tr>
		<tr>
		  <td>
			<p><strong><?php echo esc_html__( 'Campaign Term', 'google-analytics-for-wordpress');?></strong></p>
			<p><code>utm_term</code></p>
		  </td>
		  <td>
			<p><?php echo sprintf( esc_html__( 'Used for paid search. Use %1$s to note the keywords for this ad.', 'google-analytics-for-wordpress'),'<code>utm_term</code>');?></p>
			<p><em><?php echo esc_html__( 'Example:', 'google-analytics-for-wordpress');?></em> <code>running+shoes</code></p>
		  </td>
		</tr>
		<tr>
		  <td>
			<p><strong><?php echo esc_html__( 'Campaign Content', 'google-analytics-for-wordpress');?></strong></p>
			<p><code>utm_content</code></p>
		  </td>
		  <td>
			<p><?php echo sprintf(esc_html__( 'Used for A/B testing and content-targeted ads. Use %1$s to differentiate ads or links that point to the same URL.', 'google-analytics-for-wordpress'),'<code>utm_content</code>');?></p>
			<p><em><?php echo esc_html__( 'Examples:', 'google-analytics-for-wordpress');?></em> <code>logolink</code> <em><?php echo esc_html__( 'or', 'google-analytics-for-wordpress');?></em> <code>textlink</code></p>
		  </td>
		</tr>
	  </tbody>
	</table>
	
	<h2 id="monsterinsights-related-resources"><?php echo esc_html__( 'More information:', 'google-analytics-for-wordpress');?></h2>

	<ul id="monsterinsights-related-resources-list">
	  <li><a href="https://support.google.com/analytics/answer/1247851"><?php echo esc_html__( 'About Campaigns', 'google-analytics-for-wordpress');?></a></li>
	  <li><a href="https://support.google.com/analytics/answer/1033863"><?php echo esc_html__( 'About Custom Campaigns', 'google-analytics-for-wordpress');?></a></li>
	  <li><a href="https://support.google.com/analytics/answer/1037445"><?php echo esc_html__( 'Best practices for creating Custom Campaigns', 'google-analytics-for-wordpress');?></a></li>
	  <li><a href="https://support.google.com/analytics/answer/1247839"><?php echo esc_html__( 'About the Referral Traffic report', 'google-analytics-for-wordpress');?></a></li>
	  <li><a href="https://support.google.com/analytics/answer/1033173"><?php echo esc_html__( 'About traffic source dimensions', 'google-analytics-for-wordpress');?></a></li>
	  <li><a href="https://support.google.com/adwords/answer/1752125"><?php echo esc_html__( 'AdWords Auto-Tagging', 'google-analytics-for-wordpress');?></a></li>
	</ul>
	<?php
	echo ob_get_clean();
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