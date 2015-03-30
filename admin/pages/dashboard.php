<?php
/**
 * @package GoogleAnalytics\Admin
 */

global $yoast_ga_admin;

$options_class = Yoast_GA_Options::instance();
$options       = $options_class->get_options();
$tracking_code = $options_class->get_tracking_code();

echo $yoast_ga_admin->content_head();
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Google Analytics by Yoast: ', 'google-analytics-for-wordpress' ) . __( 'Dashboard', 'google-analytics-for-wordpress' ); ?> <?php do_action( 'yst_ga_dashboard_title' ); ?></h2>

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
				if ( $tracking_code !== '' ) {
					if ( empty( $options['analytics_profile'] ) ) {
						echo '<div class="ga-promote"><p>';
						echo sprintf(
							__( 'We need you to authenticate with Google Analytics to use this functionality. If you set your UA-code manually, this won\'t work. You can %sauthenticate your Google Analytics profile here%s to enable dashboards.', 'google-analytics-for-wordpress' ),
							'<a href=" ' . admin_url( 'admin.php?page=yst_ga_settings#top#general' ) . '">',
							'</a>'
						);
						echo '</p></div>';
					}
					else if ( ! Yoast_Google_Analytics::get_instance()->has_refresh_token() ) {
						echo '<div class="ga-promote"><p>';
						echo sprintf(
							__( 'Because we\'ve switched to a newer version of the Google Analytics API, you\'ll need to re-authenticate with Google Analytics. We\'re sorry for the inconvenience. You can %sre-authenticate your Google Analytics profile here%s.', 'google-analytics-for-wordpress' ),
							'<a href=" ' . admin_url( 'admin.php?page=yst_ga_settings#top#general' ) . '">',
							'</a>'
						);
						echo '</p></div>';
					}
					else {
						Yoast_GA_Dashboards_Display::get_instance()->display( 'general' );
					}
				}
				else {
					echo '<div class="ga-promote"><p>';
					echo sprintf(
						__( 'You have not yet finished setting up Google Analytics for Wordpress by Yoast. Please %sadd your Analytics profile here%s to enable tracking.', 'google-analytics-for-wordpress' ),
						'<a href=" ' . admin_url( 'admin.php?page=yst_ga_settings#top#general' ) . '">',
						'</a>'
					);
					echo '</p></div>';
				}
				?>
			</div>
		</div>

		<div id="dimensions" class="wpseotab gatab">
			<?php

			if ( $tracking_code !== '' ) {
				if ( empty( $options['analytics_profile'] ) ) {
					echo '<div class="ga-promote"><p>';
					echo sprintf(
						__( 'We need you to authenticate with Google Analytics to use this functionality. If you set your UA-code manually, this won\'t work. You can %sauthenticate your Google Analytics profile here%s to enable dashboards.', 'google-analytics-for-wordpress' ),
						'<a href=" ' . admin_url( 'admin.php?page=yst_ga_settings#top#general' ) . '">',
						'</a>'
					);
					echo '</p></div>';
				}
				else if ( ! Yoast_Google_Analytics::get_instance()->has_refresh_token() ) {
					echo '<div class="ga-promote"><p>';
					echo sprintf(
						__( 'Because we\'ve switched to a newer version of the Google Analytics API, you\'ll need to re-authenticate with Google Analytics. We\'re sorry for the inconvenience. You can %sre-authenticate your Google Analytics profile here%s.', 'google-analytics-for-wordpress' ),
						'<a href=" ' . admin_url( 'admin.php?page=yst_ga_settings#top#general' ) . '">',
						'</a>'
					);
					echo '</p></div>';
				}
				else {
					?>
					<div class="ga-form ga-form-input">
						<label class="ga-form ga-form-checkbox-label ga-form-label-left"><?php echo __( 'Select a dimension', 'google-analytics-for-wordpress' ); ?></label>
					</div>
					<select data-rel='toggle_dimensions' id="toggle_dimensions" style="width: 350px"></select>

					<?php
					Yoast_GA_Dashboards_Display::get_instance()->display( 'dimensions' );
				}
			}
			else {
				echo '<div class="ga-promote"><p>';
				echo sprintf(
					__( 'You have not yet finished setting up Google Analytics for Wordpress by Yoast. Please %sadd your Analytics profile here%s to enable tracking.', 'google-analytics-for-wordpress' ),
					'<a href=" ' . admin_url( 'admin.php?page=yst_ga_settings#top#general' ) . '">',
					'</a>'
				);
				echo '</p></div>';
			}
			?>
		</div>

		<div id="customdimensions" class="wpseotab gatab">
			<?php
			do_action( 'yst_ga_custom_dimension_add-dashboards-tab' );
			?>
		</div>
	</div>


<?php
echo $yoast_ga_admin->content_footer();
?>