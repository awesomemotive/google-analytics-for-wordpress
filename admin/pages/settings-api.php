<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Google Analytics by Yoast: ', 'google-analytics-for-wordpress' ) . __( 'Settings', 'google-analytics-for-wordpress' ); ?></h2>

<?php
settings_errors( 'yoast_google_analytics' );
?>

	<h2 class="nav-tab-wrapper" id="ga-tabs">
		<a class="nav-tab" id="yst_ga_general-tab" href="#top#yst_ga_general"><?php _e( 'General', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="yst_ga_universal-tab" href="#top#yst_ga_universal"><?php _e( 'Universal', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="yst_ga_advanced-tab" href="#top#yst_ga_advanced"><?php _e( 'Advanced', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="yst_ga_scustomdimensions-tab" href="#top#customdimensions"><?php _e( 'Custom Dimensions', 'google-analytics-for-wordpress' ); ?></a>
		<?php do_action( 'yst_ga_custom_tabs-tab' ); ?>
		<a class="nav-tab" id="yst_ga_debugmode-tab" href="#top#yst_ga_debugmode"><?php _e( 'Debug mode', 'google-analytics-for-wordpress' ); ?></a>
	</h2>

<input type="hidden" name="return_tab" id="return_tab" value="general" />

<form method="post" action="<?php echo admin_url('options.php'); ?>">
	<div class="tabwrapper">
		<?php
//		settings_fields( 'yst_ga_settings_api' );
//		do_settings_sections( 'yst_ga_settings_api' );
		?>
		<div id="yst_ga_general" class="gatab">
		<?php
		settings_fields( 'yst_ga_settings_api_general' );
		do_settings_sections( 'yst_ga_settings_api_general' );
		?>
		</div>
		<div id="yst_ga_universal" class="gatab">
		<?php
		settings_fields( 'yst_ga_settings_api_universal' );
		do_settings_sections( 'yst_ga_settings_api_universal' );
		?>
		</div>
		<div id="yst_ga_advanced" class="gatab">
		<?php
		settings_fields( 'yst_ga_settings_api_advanced' );
		do_settings_sections( 'yst_ga_settings_api_advanced' );
		?>
		</div>
		<div id="yst_ga_debugmode" class="gatab">
		<?php
		echo '<div id="ga-promote">';
		echo '<p class="ga-topdescription">' . __( 'If you want to confirm that tracking on your blog is working as it should, enable this option and check the console of your browser. Be absolutely sure to disable debugging afterwards, as it is slower than normal tracking.', 'google-analytics-for-wordpress' ) . '</p>';
		echo '<p class="ga-topdescription">' . __( '<strong>Note</strong> the debugging is only loaded for administrators.', 'google-analytics-for-wordpress' ) . '</p>';
		echo '</div>';

		settings_fields( 'yst_ga_settings_api_debug' );
		do_settings_sections( 'yst_ga_settings_api_debug' );
		?>
		</div>
		<?php
		submit_button();
		?>
	</div>
</form>

<?php
echo $yoast_ga_admin->content_footer();
?>
<script type="text/javascript">
	jQuery(document).ready(
		function () {
//			jQuery('.chosen').chosen({
//				group_search: true
//			});

			jQuery('.chosen').chosen({
				placeholder_text_multiple: '<?php echo __( 'Select the users to ignore', 'google-analytics-for-wordpress' ); ?>'
			});
		}
	);
</script>
