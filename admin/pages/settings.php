<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Yoast Google Analytics: Settings', 'google-analytics-for-wordpress' ); ?></h2>

<?php
settings_errors('yoast_google_analytics');
?>

	<h2 class="nav-tab-wrapper" id="ga-tabs">
		<a class="nav-tab" id="general-tab" href="#top#general"><?php _e( 'General', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="universal-tab" href="#top#universal"><?php _e( 'Universal', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="advanced-tab" href="#top#advanced"><?php _e( 'Advanced', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="debugmode-tab" href="#top#debugmode"><?php _e( 'Debug mode', 'google-analytics-for-wordpress' ); ?></a>
	</h2>

<?php
echo $yoast_ga_admin->create_form( 'settings' );
?>
	<div class="tabwrapper">
		<div id="general" class="gatab">
			<?php
			echo '<h2>' . __( 'General settings', 'google-analytics-for-wordpress' ) . '</h2>';
			echo '<div id="ga-promote">';

			$profiles = $yoast_ga_admin->get_profiles();
			$ga_url   = $_SERVER['PHP_SELF'];
			if ( isset( $_GET['page'] ) ) {
				$ga_url .= '?page=' . $_GET['page'];
			}
			$ga_url .= '&reauth=true';

			echo "<div id='google_ua_code_field'>";
			if ( count( $profiles ) == 0 ) {
				echo '<div class="ga-form ga-form-input">';
				echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle" />' . __( 'Google profile', 'google-analytics-for-wordpress' ) . ':</label>';
				echo '<a class="button" href="' . $ga_url . '">' . __('Authenticate with your Google account', 'google-analytics-for-wordpress') . '</a>';
				echo '</div>';
				echo '<div class="ga-form ga-form-input">';
				echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle" />' . __('Current UA-profile', 'google-analytics-for-wordpress') . '</label>';
				echo $yoast_ga_admin->get_setting('analytics_profile');
				echo '</div>';
			} else {
				echo $yoast_ga_admin->select( 'Analytics profile', 'analytics_profile', $profiles );

				echo '<div class="ga-form ga-form-input">';
				echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle" />&nbsp;</label>';
				echo '<a class="button" href="' . $ga_url . '">' . __('Re-authenticate with your Google account', 'google-analytics-for-wordpress') . '</a>';
				echo '</div>';
			}
			echo "</div>";

			echo '<label class="ga-form ga-form-checkbox-label ga-form-label-left">';
			echo $yoast_ga_admin->input( 'checkbox', NULL, 'manual_ua_code', __('Manually enter your UA code', 'google-analytics-for-wordpress' ) );
			echo '</label>';
			echo '<div id="enter_ua">';
			echo $yoast_ga_admin->input( 'text', NULL, 'manual_ua_code_field');
			echo '</div>';
			echo '<div class="clear"></div></div>';
			?>
			<div class="clear"><br /></div>
			<?php
			echo $yoast_ga_admin->input( 'checkbox', __('Track outbound click & downloads', 'google-analytics-for-wordpress' ), 'track_outbound', NULL, __( 'Clicks &amp; downloads will be tracked as events, you can find these under Content &raquo; Event Tracking in your Google Analytics reports.', 'google-analytics-for-wordpress' ) );
			echo $yoast_ga_admin->input( 'checkbox', __('Allow tracking of anonymous data', 'google-analytics-for-wordpress' ), 'anonymous_data', NULL,  __( 'By allowing us to track anonymous data we can better help you, because we know with which WordPress configurations, themes and plugins we should test. No personal data will be submitted.', 'google-analytics-for-wordpress' ) );
			echo $yoast_ga_admin->input( 'checkbox', __('Anonymize IP\'s', 'google-analytics-for-wordpress' ), 'anonymize_ips', NULL, __( 'This adds <code><a href="http://code.google.com/apis/analytics/docs/gaJS/gaJSApi_gat.html#_gat._anonymizeIp" target="_blank">_anonymizeIp</a></code>, telling Google Analytics to anonymize the information sent by the tracker objects by removing the last octet of the IP address prior to its storage.', 'google-analytics-for-wordpress' ) );
			echo $yoast_ga_admin->select( 'Ignore users', 'ignore_users', $yoast_ga_admin->get_userroles(), __('Hint: Select multiple roles by using CTRL or CMD.', 'google-analytics-for-wordpress' ) . '<br />' . __( 'Users of the role you select and higher will be ignored, so if you select Editor, all Editors and Administrators will be ignored.', 'google-analytics-for-wordpress' ), true );
			?>
		</div>
		<div id="universal" class="gatab">
			<?php
			echo '<h2>' . __( 'Universal settings', 'google-analytics-for-wordpress' ) . '</h2>';
			echo $yoast_ga_admin->input( 'checkbox', __('Enable Universal tracking', 'google-analytics-for-wordpress' ), 'enable_universal', NULL, __('First enable Universal tracking in your Google Analytics account. How to do that, please read <a href="https://developers.google.com/analytics/devguides/collection/upgrade/guide" target="_blank">this guide</a> to learn how to do that.', 'google-analytics-for-wordpress') );
			echo $yoast_ga_admin->input( 'checkbox', __('Enable Demographics and Interest Reports', 'google-analytics-for-wordpress' ), 'demographics', NULL, __('You have to enable the Demographics in Google Analytics before you can see the tracking data. We have a doc in our <a href="http://kb.yoast.com/article/154-enable-demographics-and-interests-report-in-google-analytics//#utm_medium=kb-link&utm_source=gawp-config&utm_campaign=wpgaplugin" target="_blank">knowlegde base</a> about this feature.', 'google-analytics-for-wordpress') );
			?>
		</div>
		<div id="advanced" class="gatab">
			<?php
			echo '<h2>' . __( 'Advanced settings', 'google-analytics-for-wordpress' ) . '</h2>';
			echo $yoast_ga_admin->select( __('Track downloads as', 'google-analytics-for-wordpress' ), 'track_download_as', $yoast_ga_admin->track_download_types(), __( 'Not recommended, as this would skew your statistics, but it does make it possible to track downloads as goals.', 'google-analytics-for-wordpress' ) );
			echo $yoast_ga_admin->input( 'text', __('Extensions of files to track as downloads', 'google-analytics-for-wordpress' ), 'extensions_of_files', NULL, 'Please separate extensions using commas' );
			echo $yoast_ga_admin->select( __('Track full URL of outbound clicks or just the domain', 'google-analytics-for-wordpress' ), 'track_full_url', $yoast_ga_admin->get_track_full_url() );
			echo $yoast_ga_admin->input( 'text', __('Subdomain tracking', 'google-analytics-for-wordpress' ), 'subdomain_tracking', NULL, __('This allows you to set the domain that\'s set by <code>setDomainName</code> for tracking subdomains, if empty this will not be set.', 'google-analytics-for-wordpress' ) );

			echo $yoast_ga_admin->input( 'text', __('Set path for internal links to track as outbound links', 'google-analytics-for-wordpress' ), 'track_internal_as_outbound', NULL, 'If you want to track all internal links that begin with <code>/out/</code>, enter <code>/out/</code> in the box above. If you have multiple prefixes you can separate them with comma\'s: <code>/out/,/recommends/</code>' );
			echo $yoast_ga_admin->input( 'text', __('Label for those links', 'google-analytics-for-wordpress' ), 'track_internal_as_label', NULL, "The label to use for these links, this will be added to where the click came from, so if the label is \"aff\", the label for a click from the content of an article becomes \"outbound-article-aff\"." );

			echo $yoast_ga_admin->input( 'checkbox', __('Tag links in RSS feed with campaign variables', 'google-analytics-for-wordpress' ), 'tag_links_in_rss', NULL, __( 'Do not use this feature if you use FeedBurner, as FeedBurner can do this automatically, and better than this plugin can. Check <a href="http://www.google.com/support/feedburner/bin/answer.py?hl=en&amp;answer=165769" target="_blank">this help page</a> for info on how to enable this feature in FeedBurner.', 'google-analytics-for-wordpress' ) );
			echo $yoast_ga_admin->input( 'checkbox', __('Allow anchor', 'google-analytics-for-wordpress' ), 'allow_anchor', NULL, __( 'This adds a <code><a href="http://code.google.com/apis/analytics/docs/gaJSApiCampaignTracking.html#_gat.GA_Tracker_._setAllowAnchor" target="_blank">_setAllowAnchor</a></code> call to your tracking code, and makes RSS link tagging use a # as well.', 'google-analytics-for-wordpress' ) );
			echo $yoast_ga_admin->input( 'checkbox', __('Add <code>_setAllowLinker</code>', 'google-analytics-for-wordpress' ), 'add_allow_linker', NULL, __( 'This adds a <code><a href="http://code.google.com/apis/analytics/docs/gaJS/gaJSApiDomainDirectory.html#_gat.GA_Tracker_._setAllowLinker" target="_blank">_setAllowLinker</a></code> call to your tracking code,  allowing you to use <code>_link</code> and related functions.', 'google-analytics-for-wordpress' ) );
			echo $yoast_ga_admin->textarea( 'Custom code', 'custom_code', __( 'Not for the average user: this allows you to add a line of code, to be added before the <code>trackPageview</code> call.', 'google-analytics-for-wordpress' ) );
			?>
		</div>
		<div id="debugmode" class="gatab">
			<?php
			echo '<h2>' . __( 'Debug settings', 'google-analytics-for-wordpress' ) . '</h2>';

			echo '<div id="ga-promote">';
			echo '<p class="ga-topdescription">' . __( 'If you want to confirm that tracking on your blog is working as it should, enable these options and check the console in Firebug (for Firefox), Firebug Lite (for other browsers) or Chrome & Safari\'s Web Inspector. Be absolutely sure to disable debugging afterwards, as it is slower than normal tracking.', 'google-analytics-for-wordpress' ) . '</p>';
			echo '<p class="ga-topdescription"><strong>' . __( 'Note', 'google-analytics-for-wordpress' ) . ':</strong> ' . __( 'the debugging and firebug scripts are only loaded for admins.', 'google-analytics-for-wordpress' ) . '</p>';
			echo '</div>';
			echo $yoast_ga_admin->input( 'checkbox', __('Enable debug mode', 'google-analytics-for-wordpress' ), 'debug_mode' );
			echo $yoast_ga_admin->input( 'checkbox', __('Enable Firebug Lite', 'google-analytics-for-wordpress' ), 'firebug_lite' );
			?>
		</div>
	</div>
<?php
echo $yoast_ga_admin->end_form( 'Save changes', 'settings' );
echo $yoast_ga_admin->content_footer();
?>
<script	type="text/javascript">
	jQuery(document).ready(
		function() {
			jQuery('#yoast-ga-form-select-settings-analytics_profile').chosen();
			jQuery('#yoast-ga-form-select-settings-ignore_users').chosen( { placeholder_text_multiple : '<?php echo __( 'Select the users to ignore', 'google-analytics-for-wordpress' ); ?>' });
		}
	);
</script>