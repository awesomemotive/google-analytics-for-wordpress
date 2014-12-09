<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Yoast Google Analytics: ', 'google-analytics-for-wordpress' ) . __( 'Dashboard', 'google-analytics-for-wordpress' ); ?></h2>

	<h2 class="nav-tab-wrapper" id="ga-tabs">
		<a class="nav-tab" id="general-tab" href="#top#general"><?php _e( 'Overview', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="dimensions-tab" href="#top#dimensions"><?php _e( 'Reports', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="customdimensions-tab" href="#top#customdimensions"><?php _e( 'Custom dimension reports', 'google-analytics-for-wordpress' ); ?></a>
	</h2>

	<script type="text/javascript">
		var yoast_ga_dashboard_nonce = '<?php echo wp_create_nonce( 'yoast-ga-dashboard-nonce' ); ?>';

		jQuery(function () {
			jQuery.each(
				jQuery('select[data-rel=toggle_dimensions]'),
				function (num, element) {
					dimension_switch(element);
				}
			);
		});
	</script>

<div class="tabwrapper">
	<div id="general" class="wpseotab gatab active">
		<div class="yoast-graphs">
			<?php

			if(Yoast_GA_Options::instance()->get_tracking_code() !== '') {
				Yoast_GA_Dashboards_Display::get_instance()->display( 'general' );
			} else {
				echo sprintf(
					__( 'You have not yet finished setting up Google Analytics for Wordpress by Yoast. Please %sadd your Analytics profile here%s to enable tracking.','google-analytics-for-wordpress'),
					'<a href=" ' .admin_url( 'admin.php?page=yst_ga_settings#top#general' ) . '">',
					'</a>'
				);
			}
			?>
		</div>
	</div>

	<div id="dimensions" class="wpseotab gatab">
		<?php
			if(Yoast_GA_Options::instance()->get_tracking_code() !== '') {
				?>
				<div class="ga-form ga-form-input">
					<label class="ga-form ga-form-checkbox-label ga-form-label-left"><?php echo __( 'Select a dimension', 'google-analytics-for-wordpress' ); ?></label>
				</div>
				<select data-rel='toggle_dimensions' id="toggle_dimensions" style="width: 350px"></select>

				<?php
				Yoast_GA_Dashboards_Display::get_instance()->display( 'dimensions' );
			} else {
				echo sprintf(
					__( 'You have not yet finished setting up Google Analytics for Wordpress by Yoast. Please %sadd your Analytics profile here%s to enable tracking.','google-analytics-for-wordpress'),
					'<a href=" ' .admin_url( 'admin.php?page=yst_ga_settings#top#general' ) . '">',
					'</a>'
				);
			}
		?>
	</div>

	<div id="customdimensions" class="wpseotab gatab">
		<?php
			do_action('yst_ga_custom_dimension_add-dashboards-tab');
		?>
	</div>
</div>


<?php
echo $yoast_ga_admin->content_footer();
?>