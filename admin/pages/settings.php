<?php
/**
 * @package GoogleAnalytics\Admin
 */

global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
<h2 id="yoast_ga_title">Google Analytics by Yoast: <?php _e( 'Settings', 'google-analytics-for-wordpress' ); ?></h2>

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

	<!-- start general tab -->
	<div id="general" class="gatab">
		<h2><?php _e( 'General settings', 'google-analytics-for-wordpress' ); ?></h2>

		<div id="ga-promote">
			<?php

			/**
			 * Check if google can be accessed
			 *
			 * @return bool
			 */
			function check_google_access() {
				$ga_class = Yoast_Google_Analytics::get_instance();

				return ( $ga_class->check_google_access_from_wp() && $ga_class->check_google_access() );
			}

			// Start if statement wp_block_google && check_google_access.
			if ( check_google_access() ) :

				$profiles = Yoast_GA_Admin_Form::parse_optgroups( $yoast_ga_admin->get_profiles() );

				$auth_url = Yoast_Google_Analytics::get_instance()->create_auth_url();
				add_thickbox();
			?>

				<script>yst_thickbox_heading = "<?php _e( 'Paste your Google authentication code', 'google-analytics-for-wordpress' )?>";</script>

				<div id='google_ua_code_field'>
					<?php if ( count( $profiles ) === 0 ) : ?>
						<div class="ga-form ga-form-input">
							<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle"><?php _e( 'Google profile', 'google-analytics-for-wordpress' ); ?>:</label>
							<a id="yst_ga_authenticate" class="button" onclick="yst_popupwindow('<?php echo $auth_url; ?>',500,500);"><?php _e( 'Authenticate with your Google account', 'google-analytics-for-wordpress' ); ?></a>
						</div>
						<div class="ga-form ga-form-input">
							<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle"><?php _e( 'Current UA-profile', 'google-analytics-for-wordpress' ); ?></label>
							<?php echo esc_html( $yoast_ga_admin->get_tracking_code() ); ?>
						</div>
					<?php else :
						echo Yoast_GA_Admin_Form::select( __( 'Analytics profile', 'google-analytics-for-wordpress' ), 'analytics_profile', $profiles, null, false, __( 'Select a profile', 'google-analytics-for-wordpress' ) ); ?>

						<div class="ga-form ga-form-input">
							<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle">&nbsp;</label>
							<a id="yst_ga_authenticate" class="button" onclick="yst_popupwindow('<?php echo $auth_url ?>',500,500);"><?php _e( 'Re-authenticate with your Google account', 'google-analytics-for-wordpress' )?></a>
						</div>
					<?php endif; ?>
				</div>

				<div id="oauth_code" class="ga-form ga-form-input">
					<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle"><?php _e( 'Paste your Google code here', 'google-analytics-for-wordpress' ) ?></label>
					<?php echo Yoast_GA_Admin_Form::input( 'text', null, 'google_auth_code', null, null ); ?>

					<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle-submit">&nbsp;</label>
					<div class="ga-form ga-form-input"><input type="submit" name="ga-form-settings" value="<?php _e( 'Save authentication code', 'google-analytics-for-wordpress' ) ?>" class="button button-primary ga-form-submit" id="yoast-ga-form-submit-settings" onclick="yst_closepopupwindow();"></div>
				</div>
			<?php else : ?>
				<h3>' <?php _e( 'Cannot connect to Google', 'google-analytics-for-wordpress' ); ?></h3>;
				<?php
				if ( ! check_google_access() ) : ?>
					<p> <?php _e( 'Your server is blocking requests to Google, to fix this, add <code>*.googleapis.com</code> to the <code>WP_ACCESSIBLE_HOSTS</code> constant in your <em>wp-config.php</em> or ask your webhost to do this.', 'google-analytics-for-wordpress' ); ?></p>
		<?php   else : ?>
					<p><?php _e( 'Your firewall or webhost is blocking requests to Google, please ask your webhost company to fix this.', 'google-analytics-for-wordpress' ); ?> </p>
			<?php endif; ?>
			<p><?php _e( 'Until this is fixed, you can only use the manual authentication method and cannot use the dashboards feature.', 'google-analytics-for-wordpress' ); ?></p>
		<?php endif; ?>

			<label class="ga-form ga-form-checkbox-label ga-form-label-left">
				<?php echo Yoast_GA_Admin_Form::input( 'checkbox', null, 'manual_ua_code', __( 'Manually enter your UA code', 'google-analytics-for-wordpress' ) ); ?>
			</label>
			<div id="enter_ua">
				<?php echo Yoast_GA_Admin_Form::input( 'text', null, 'manual_ua_code_field' ); ?>
				<p><strong><?php _e( 'Warning: If you use a manual UA code, you won\'t be able to use the dashboards.', 'google-analytics-for-wordpress' ); ?></strong></p>
			</div>
			<div class="clear"></div>
		</div>

		<div class="clear"><br /></div>
		<?php
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Track outbound click and downloads', 'google-analytics-for-wordpress' ), 'track_outbound', null, __( 'Clicks and downloads will be tracked as events, you can find these under Content &#xBB; Event Tracking in your Google Analytics reports.', 'google-analytics-for-wordpress' ) );
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Allow tracking of anonymous data', 'google-analytics-for-wordpress' ), 'anonymous_data', null, __( 'By allowing us to track anonymous data we can better help you, because we know with which WordPress configurations, themes and plugins we should test. No personal data will be submitted.', 'google-analytics-for-wordpress' ) );
		/* translators: %s is a link to the Google Analytics help page on Google.com for _anonymizeIp */
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Anonymize IPs', 'google-analytics-for-wordpress' ), 'anonymize_ips', null, sprintf( __( 'This adds %1$s, telling Google Analytics to anonymize the information sent by the tracker objects by removing the last octet of the IP address prior to its storage.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApi_gat?csw=1#_gat._anonymizeIp" target="_blank"><code>_anonymizeIp</code></a>' ) );
		echo Yoast_GA_Admin_Form::select( __( 'Ignore users', 'google-analytics-for-wordpress' ), 'ignore_users', $yoast_ga_admin->get_userroles(), __( 'Users of the role you select will be ignored, so if you select Editor, all Editors will be ignored.', 'google-analytics-for-wordpress' ), true );
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Disable analytics dashboard', 'google-analytics-for-wordpress' ), 'dashboards_disabled', null, __( 'This will completely disable the dashboard and stop the plugin from fetching the latest analytics data.', 'google-analytics-for-wordpress' ) );

		?>
		<div class="clear"><br /></div>

		<?php if ( get_user_meta( get_current_user_id(), 'ga_ignore_tour' ) ) : ?>
		<h2><?php _e( 'Introduction Tour', 'google-analytics-for-wordpress' ) ?></h2>
		<div id="ga_form_introduction_tour">
			<p>
				<?php _e( 'Take this tour to quickly learn about the use of this plugin.', 'google-analytics-for-wordpress' ); ?>
			</p>
			<p>
				<a class="button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=yst_ga_settings&ga_restart_tour=1' ) ); ?>"><?php _e( 'Start Tour', 'google-analytics-for-wordpress' ); ?></a>
			</p>
		</div>
		<br/>
		<?php endif; ?>

	</div>
	<!-- end general tab -->

	<!-- start universal tab -->
	<div id="universal" class="gatab">
		<h2><?php _e( 'Universal settings', 'google-analytics-for-wordpress' ); ?></h2>
		<?php
		/* translators: %s is a link to the Google Analytics help page on Google.com to enable Universal tracking */
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Enable Universal tracking', 'google-analytics-for-wordpress' ), 'enable_universal', null, sprintf( __( 'First enable Universal tracking in your Google Analytics account. Please read %1$sthis guide%2$s to learn how to do that.', 'google-analytics-for-wordpress' ), '<a href="http://kb.yoast.com/article/125-universal-analytics#utm_medium=kb-link&utm_source=gawp-config&utm_campaign=wpgaplugin" target="_blank">', '</a>' ) );
		echo Yoast_GA_Admin_Form::input(
			'checkbox',
			__( 'Enable Demographics and Interests Reports for Remarketing and Advertising', 'google-analytics-for-wordpress' ),
			'demographics',
			'<span id="yoast-ga-displayfeatures-warning">' . __( 'Note that usage of this function is affected by privacy and cookie laws around the world. Be sure to follow the laws that affect your target audience.', 'google-analytics-for-wordpress' ) . '</span>',
			/* translators: 1: contains the link to a knowledge base article, 2: contains the the closing tag for both links, 3: contains a link to Google documentation about remarketing. */
			sprintf(
				__( 'Check this setting to add the Demographics and Remarketing features to your Google Analytics tracking code. We\'ve written an article in our %1$sknowledge base%2$s about Demographics and Interest reports. For more information about Remarketing, we refer you to %3$sGoogle\'s documentation%2$s.', 'google-analytics-for-wordpress' ),
				'<a href="http://kb.yoast.com/article/154-enable-demographics-and-interests-report-in-google-analytics/#utm_medium=kb-link&amp;utm_source=gawp-config&amp;utm_campaign=wpgaplugin" target="_blank">',
				'</a>',
				'<a href="https://support.google.com/analytics/answer/2444872?hl=' . get_locale() . '" target="_blank">'
			)
		);
		/* translators: 1: links to the Google Analytics help page on Google.com to enable link attribution, 2: closing tag for the link */
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Enhanced Link Attribution', 'google-analytics-for-wordpress' ), 'enhanced_link_attribution', null, sprintf( __( 'Add %1$sEnhanced Link Attribution%2$s to your tracking code.', 'google-analytics-for-wordpress' ), '<a href="https://support.google.com/analytics/answer/2558867" target="_blank">', ' </a>' ) );

		/**
		 * Action: 'yst_ga_universal_tab' - Allow adding to the universal tab of the settings
		 */
		do_action( 'yst_ga_universal_tab' );
		?>
	</div>
	<!-- end universal tab -->

	<!-- start advanced tab -->
	<div id="advanced" class="gatab">
		<h2><?php _e( 'Advanced settings', 'google-analytics-for-wordpress' ); ?></h2>
		<?php
		echo Yoast_GA_Admin_Form::select( __( 'Track downloads as', 'google-analytics-for-wordpress' ), 'track_download_as', $yoast_ga_admin->track_download_types(), __( 'Not recommended, as this would skew your statistics, but it does make it possible to track downloads as goals.', 'google-analytics-for-wordpress' ) );
		echo Yoast_GA_Admin_Form::input( 'text', __( 'Extensions of files to track as downloads', 'google-analytics-for-wordpress' ), 'extensions_of_files', null, 'Please separate extensions using commas' );
		echo Yoast_GA_Admin_Form::select( __( 'Track full URL of outbound clicks or just the domain', 'google-analytics-for-wordpress' ), 'track_full_url', $yoast_ga_admin->get_track_full_url() );
		/* translators: 1: link to the Google Analytics help page on Google.com to set subdomain tracking.*/
		echo Yoast_GA_Admin_Form::input( 'text', __( 'Subdomain tracking', 'google-analytics-for-wordpress' ), 'subdomain_tracking', null, sprintf( __( 'This allows you to set the domain that\'s set by %1$s for tracking subdomains.<br/>If empty, this will not be set.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiDomainDirectory#_gat.GA_Tracker_._setDomainName" target="_blank"><code>_setDomainName</code></a>' ) );
		/* translators: 1: will output `<code>/out/</code>`. 2: will output `<code>/out/,/recommends/</code>` */
		echo Yoast_GA_Admin_Form::input( 'text', __( 'Set path for internal links to track as outbound links', 'google-analytics-for-wordpress' ), 'track_internal_as_outbound', null, sprintf( __( 'If you want to track all internal links that begin with %1$s, enter %1$s in the box above. If you have multiple prefixes you can separate them with comma\'s: %2$s', 'google-analytics-for-wordpress' ), '<code>/out/</code>', '<code>/out/,/recommends/</code>' ) );
		echo Yoast_GA_Admin_Form::input( 'text', __( 'Label for those links', 'google-analytics-for-wordpress' ), 'track_internal_as_label', null, 'The label to use for these links, this will be added to where the click came from, so if the label is "aff", the label for a click from the content of an article becomes "outbound-article-aff".' );

		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Tag links in RSS feed with campaign variables', 'google-analytics-for-wordpress' ), 'tag_links_in_rss', null, __( 'Do not use this feature if you use FeedBurner, as FeedBurner can do this automatically and better than this plugin can. Check <a href="https://support.google.com/feedburner/answer/165769?hl=en&amp;ref_topic=13075" target="_blank">this help page</a> for info on how to enable this feature in FeedBurner.', 'google-analytics-for-wordpress' ) );
		/* translators: 1: link to the Google Analytics help page on Google.com to enable link attribution. 2: code tag - <code>#</a> */
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Allow anchor', 'google-analytics-for-wordpress' ), 'allow_anchor', null, sprintf( __( 'This adds a %1$s call to your tracking code, and makes RSS link tagging use a %2$s as well.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiCampaignTracking?csw=1#_gat.GA_Tracker_._setAllowAnchor" target="_blank"><code>_setAllowAnchor</code></a>', '<code>#</code>' ) );
		/* translators: 1: link to the Google Analytics help page on Google.com add the allow linker tag. 2: code tag - <code>_link</code> */
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Add <code>_setAllowLinker</code>', 'google-analytics-for-wordpress' ), 'add_allow_linker', null, sprintf( __( 'This adds a %1$s call to your tracking code, allowing you to use %2$s and related functions.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiDomainDirectory?csw=1#_gat.GA_Tracker_._setAllowLinker" target="_blank"><code>_setAllowLinker</code></a>', ' <code>_link</code>' ) );

		if ( current_user_can( 'unfiltered_html' ) ) {
			/* translators: 1: is a link to the Google Analytics help page on Google.com to add custom code for the tracking */
			echo Yoast_GA_Admin_Form::textarea( __( 'Custom code', 'google-analytics-for-wordpress' ), 'custom_code', sprintf( __( 'Not for the average user: this allows you to add a line of code, to be added before the %1$s call.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiBasicConfiguration#_gat.GA_Tracker_._trackPageview" target="_blank"><code>_trackPageview</code></a>' ) );
		}

		/**
		 * Action: 'yst_ga_advanced-tab' - Allow adding to the advanced tab of the settings
		 */
		do_action( 'yst_ga_advanced-tab' );
		?>
	</div>
	<!-- end advanced tab -->

	<!-- start custom dimensions tab -->
	<div id="customdimensions" class="gatab">
		<h2><?php _e( 'Custom dimensions', 'google-analytics-for-wordpress' ); ?></h2>
		<?php do_action( 'yst_ga_custom_dimensions_tab-content' ); ?>
	</div>
	<!-- end custom dimensions tab -->

	<?php do_action( 'yst_ga_custom_tabs-content' ); ?>

	<!-- start debug mode tab -->
	<div id="debugmode" class="gatab">
		<h2><?php _e( 'Debug', 'google-analytics-for-wordpress' ) ?></h2>
		<div id="ga-promote">
			<p class="ga-topdescription"><?php _e( 'If you want to confirm that tracking on your blog is working as it should, enable this option and check the console of your browser. Be absolutely sure to disable debugging afterwards, as it is slower than normal tracking.', 'google-analytics-for-wordpress' ); ?></p>
			<p class="ga-topdescription"><?php _e( '<strong>Note</strong> the debugging is only loaded for administrators.', 'google-analytics-for-wordpress' ); ?></p>
		</div>
		<?php echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Enable debug mode', 'google-analytics-for-wordpress' ), 'debug_mode' ); ?>
	</div>
	<!-- end debug mode tab -->

</div>
<?php
echo Yoast_GA_Admin_Form::end_form( __( 'Save changes', 'google-analytics-for-wordpress' ), 'settings', 'yst_closepopupwindow();' );
echo $yoast_ga_admin->content_footer();
?>
<script type="text/javascript">
	jQuery(document).ready(
		function () {
			jQuery('#yoast-ga-form-select-settings-analytics_profile').chosen({
				group_search: true
			});
			jQuery('#yoast-ga-form-select-settings-ignore_users').chosen({placeholder_text_multiple: '<?php echo __( 'Select the users to ignore', 'google-analytics-for-wordpress' ); ?>'});
		}
	);
</script>
