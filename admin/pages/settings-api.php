<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Google Analytics by Yoast: ', 'google-analytics-for-wordpress' ) . __( 'Settings', 'google-analytics-for-wordpress' ); ?></h2>

<?php
settings_errors( 'yoast_google_analytics' );
?>

	<h2 class="nav-tab-wrapper" id="ga-tabs">
		<a class="nav-tab" id="general-tab" href="#top#general"><?php _e( 'General', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="universal-tab" href="#top#universal"><?php _e( 'Universal', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="advanced-tab" href="#top#advanced"><?php _e( 'Advanced', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="customdimensions-tab" href="#top#customdimensions"><?php _e( 'Custom Dimensions', 'google-analytics-for-wordpress' ); ?></a>
		<?php do_action( 'yst_ga_custom_tabs-tab' ); ?>
		<a class="nav-tab" id="debugmode-tab" href="#top#debugmode"><?php _e( 'Debug mode', 'google-analytics-for-wordpress' ); ?></a>
	</h2>

<form method="post" action="<?php echo admin_url('options.php'); ?>">
	<input type="hidden" name="return_tab" id="return_tab" value="general" />
	<div class="tabwrapper">
		<div id="general" class="gatab">
			<?php
			settings_fields( 'yst_ga_settings_form' );
			do_settings_sections( 'yst_ga_settings_form_general' );
			?>
		</div>
		<div id="universal" class="gatab">
			<?php
			settings_fields( 'yst_ga_settings_form' );
			do_settings_sections( 'yst_ga_settings_form_universal' );
			?>
		</div>
		<div id="advanced" class="gatab">
			<?php
			settings_fields( 'yst_ga_settings_form' );
			do_settings_sections( 'yst_ga_settings_form_advanced' );
			?>
		</div>
		<div id="customdimensions" class="gatab">
			<?php
			echo '<h3>' . __( 'Custom dimensions', 'google-analytics-for-wordpress' ) . '</h3>';
			do_action( 'yst_ga_custom_dimensions_tab-content' );
			?>
		</div>
		<div id="debugmode" class="gatab">
			<div id="ga-promote">
				<p class="ga-topdescription"><?php _e( 'If you want to confirm that tracking on your blog is working as it should, enable this option and check the console of your browser. Be absolutely sure to disable debugging afterwards, as it is slower than normal tracking.', 'google-analytics-for-wordpress' );?></p>
				<p class="ga-topdescription"><?php _e( '<strong>Note:</strong> the debugging is only loaded for administrators.', 'google-analytics-for-wordpress' ); ?></p>
			</div>
			<?php
			settings_fields( 'yst_ga_settings_form' );
			do_settings_sections( 'yst_ga_settings_form_debug' );
			?>
		</div>
	</div>
	<?php
	submit_button();
	?>
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
