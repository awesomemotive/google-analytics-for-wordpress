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
			<div id="ga-promote">
			<?php
			$ga_class            = Yoast_Google_Analytics::get_instance();
			$wp_block_google     = $ga_class->check_google_access_from_wp();
			$check_google_access = $ga_class->check_google_access();

			if ( $wp_block_google && $check_google_access ) {

				$profiles = Yoast_GA_Admin_Form::parse_optgroups( $yoast_ga_admin->get_profiles() );

				$auth_url = Yoast_Google_Analytics::get_instance()->create_auth_url();
				add_thickbox();
				echo '<script>yst_thickbox_heading = "' . __( 'Paste your Google authentication code', 'google-analytics-for-wordpress' ) . '";</script>';

				echo "<div id='google_ua_code_field'>";
				if ( count( $profiles ) == 0 ) {
					echo '<div class="ga-form ga-form-input">';
					echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle">' . __( 'Google profile', 'google-analytics-for-wordpress' ) . ':</label>';
					echo '<a id="yst_ga_authenticate" class="button" onclick="yst_popupwindow(\'' . $auth_url . '\',500,500);">' . __( 'Authenticate with your Google account', 'google-analytics-for-wordpress' ) . '</a>';
					echo '</div>';
					echo '<div class="ga-form ga-form-input">';
					echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle">' . __( 'Current UA-profile', 'google-analytics-for-wordpress' ) . '</label>';
					echo $yoast_ga_admin->get_tracking_code();
					echo '</div>';
				} else {
					echo Yoast_GA_Admin_Form::select( __('Analytics profile', 'google-analytics-for-wordpress' ), 'analytics_profile', $profiles, null, false, __( 'Select a profile', 'google-analytics-for-wordpress' ) );

					echo '<div class="ga-form ga-form-input">';
					echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle">&nbsp;</label>';
					echo '<a id="yst_ga_authenticate" class="button" onclick="yst_popupwindow(\'' . $auth_url . '\',500,500);">' . __( 'Re-authenticate with your Google account', 'google-analytics-for-wordpress' ) . '</a>';
					echo '</div>';
				}
				echo '</div>';

				echo '<div id="oauth_code" class="ga-form ga-form-input">';
				echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle">' . __( 'Paste your Google code here', 'google-analytics-for-wordpress' ) . ':</label>';
				echo Yoast_GA_Admin_Form::input( 'text', null, 'google_auth_code', null, null );

				echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle-submit">&nbsp;</label>';
				echo '<div class="ga-form ga-form-input"><input type="submit" name="ga-form-settings" value="' . __('Save authentication code', 'google-analytics-for-wordpress') . '" class="button button-primary ga-form-submit" id="yoast-ga-form-submit-settings" onclick="yst_closepopupwindow();"></div>';
				echo '</div>';
			} else {
				echo '<h3>' . __( 'Cannot connect to Google', 'google-analytics-for-wordpress' ) . '</h3>';
				if ( $wp_block_google == false && $check_google_access == false ) {
					echo '<p>' . __( 'Your server is blocking requests to Google, to fix this, add <code>*.googleapis.com</code> to the <code>WP_ACCESSIBLE_HOSTS</code> constant in your <em>wp-config.php</em> or ask your webhost to do this.', 'google-analytics-for-wordpress' ) . '</p>';
				} else {
					echo '<p>' . __( 'Your firewall or webhost is blocking requests to Google, please ask your webhost company to fix this.', 'google-analytics-for-wordpress' ) . '</p>';
				}
				echo '<p>' . __( 'Until this is fixed, you can only use the manual authentication method and cannot use the dashboards feature.', 'google-analytics-for-wordpress' ) . '</p>';
			}

			settings_fields( 'yst_ga_settings_form' );
			do_settings_sections( 'yst_ga_settings_form_general_tracking' );
			?>
			</div>
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
