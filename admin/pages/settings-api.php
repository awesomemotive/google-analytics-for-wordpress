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

<?php
echo Yoast_GA_Admin_Form::create_form( 'settings' );
?>
	<input type="hidden" name="return_tab" id="return_tab" value="general" />
	<div class="tabwrapper">
		<div id="general" class="gatab">
			<h2><?php _e( 'General settings', 'google-analytics-for-wordpress' ); ?></h2>

			<form method="post" action="<?php echo admin_url('options.php'); ?>">
			<?php
			settings_fields( 'yst_ga_settings_form' );
			do_settings_sections( 'yst_ga_settings_form' );
			submit_button();
			?>
			</form>
		</div>
	</div>
<?php
echo $yoast_ga_admin->content_footer();
?>