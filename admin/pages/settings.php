<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
<h2 id="yoast_ga_title"><?php echo __( 'Yoast Google Analytics: ', 'google-analytics-for-wordpress' ) . __( 'Settings', 'google-analytics-for-wordpress' ); ?></h2>

<?php
settings_errors( 'yoast_google_analytics' );
?>

<h2 class="nav-tab-wrapper" id="ga-tabs">
	<a class="nav-tab" id="general-tab" href="#top#general"><?php _e( 'General', 'google-analytics-for-wordpress' ); ?></a>
	<a class="nav-tab" id="universal-tab" href="#top#universal"><?php _e( 'Universal', 'google-analytics-for-wordpress' ); ?></a>
	<a class="nav-tab" id="advanced-tab" href="#top#advanced"><?php _e( 'Advanced', 'google-analytics-for-wordpress' ); ?></a>
	<a class="nav-tab" id="customdimensions-tab" href="#top#customdimensions"><?php _e( 'Custom dimensions', 'google-analytics-for-wordpress' ); ?></a>
	<?php do_action( 'yst_ga_custom_tabs-tab' ); ?>
	<a class="nav-tab" id="debugmode-tab" href="#top#debugmode"><?php _e( 'Debug mode', 'google-analytics-for-wordpress' ); ?></a>
</h2>

<?php
echo Yoast_GA_Admin_Form::create_form( 'settings' );
?>
<input type="hidden" name="return_tab" id="return_tab" value="general" />
<div class="tabwrapper">
	<div id="general" class="gatab">
		<?php
		echo '<h2>' . __( 'General settings', 'google-analytics-for-wordpress' ) . '</h2>';
		echo '<div id="ga-promote">';

		$profiles = Yoast_GA_Admin_Form::parse_optgroups( $yoast_ga_admin->get_profiles() );
		$ga_url   = $_SERVER['PHP_SELF'];
		if ( isset( $_GET['page'] ) ) {
			$ga_url .= '?page=' . $_GET['page'];
		}
		$ga_url .= '&reauth=true';

		echo "<div id='google_ua_code_field'>";
		if ( count( $profiles ) == 0 ) {
			echo '<div class="ga-form ga-form-input">';
			echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle" />' . __( 'Google profile', 'google-analytics-for-wordpress' ) . ':</label>';
			echo '<a id="yst_ga_authenticate" class="button" href="' . $ga_url . '" target="_blank">' . __( 'Authenticate with your Google account', 'google-analytics-for-wordpress' ) . '</a>';
			echo '</div>';
			echo '<div class="ga-form ga-form-input">';
			echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle" />' . __( 'Current UA-profile', 'google-analytics-for-wordpress' ) . '</label>';
			echo $yoast_ga_admin->get_tracking_code();
			echo '</div>';
		} else {
			echo Yoast_GA_Admin_Form::select( __('Analytics profile', 'google-analytics-for-wordpress' ), 'analytics_profile', $profiles, null, false, __( 'Select a profile', 'google-analytics-for-wordpress' ) );

			echo '<div class="ga-form ga-form-input">';
			echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle" />&nbsp;</label>';
			echo '<a id="yst_ga_authenticate" class="button" href="' . $ga_url . '" target="_blank">' . __( 'Re-authenticate with your Google account', 'google-analytics-for-wordpress' ) . '</a>';
			echo '</div>';
		}

		echo '<div id="oauth_code" class="ga-form ga-form-input">';
		echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle"  />' . __( 'Paste your Google code here', 'google-analytics-for-wordpress' ) . ':</label>';
		echo Yoast_GA_Admin_Form::input( 'text', null, 'google_auth_code', null, __('Please leave this field empty if everything is all right for you', 'google-analytics-for-wordpress') );
		echo '</label>';

		echo '</div>';
		echo '</div>';

		echo '<label class="ga-form ga-form-checkbox-label ga-form-label-left">';
		echo Yoast_GA_Admin_Form::input( 'checkbox', null, 'manual_ua_code', __( 'Manually enter your UA code', 'google-analytics-for-wordpress' ) );
		echo '</label>';
		echo '<div id="enter_ua">';
		echo Yoast_GA_Admin_Form::input( 'text', null, 'manual_ua_code_field' );
		echo '<p><strong>' . __('Warning: If you use a manual UA code, you won\'t be able to use the dashboards.', 'google-analytics-for-wordpress') . '</strong></p>';
		echo '</div>';
		echo '<div class="clear"></div></div>';
		?>
		<div class="clear"><br /></div>
		<?php
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Track outbound click & downloads', 'google-analytics-for-wordpress' ), 'track_outbound', null, __( 'Clicks &amp; downloads will be tracked as events, you can find these under Content &raquo; Event Tracking in your Google Analytics reports.', 'google-analytics-for-wordpress' ) );
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Allow tracking of anonymous data', 'google-analytics-for-wordpress' ), 'anonymous_data', null, __( 'By allowing us to track anonymous data we can better help you, because we know with which WordPress configurations, themes and plugins we should test. No personal data will be submitted.', 'google-analytics-for-wordpress' ) );
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Anonymize IP\'s', 'google-analytics-for-wordpress' ), 'anonymize_ips', null, sprintf( __( 'This adds <code>%1$s _anonymizeIp%2$s</code>, telling Google Analytics to anonymize the information sent by the tracker objects by removing the last octet of the IP address prior to its storage.', 'google-analytics-for-wordpress' ), '<a href="http://code.google.com/apis/analytics/docs/gaJS/gaJSApi_gat.html#_gat._anonymizeIp" target="_blank">', '</a>' ) );
		echo Yoast_GA_Admin_Form::select( 'Ignore users', 'ignore_users', $yoast_ga_admin->get_userroles(), __( 'Users of the role you select will be ignored, so if you select Editor, all Editors will be ignored.', 'google-analytics-for-wordpress' ), true );
		?>
	</div>
	<div id="universal" class="gatab">
		<?php
		echo '<h2>' . __( 'Universal settings', 'google-analytics-for-wordpress' ) . '</h2>';
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Enable Universal tracking', 'google-analytics-for-wordpress' ), 'enable_universal', null,  sprintf( __( 'First enable Universal tracking in your Google Analytics account. How to do that, please read %1$sthis guide%2$s to learn how to do that.', 'google-analytics-for-wordpress' ), '<a href="http://kb.yoast.com/article/125-universal-analytics#utm_medium=kb-link&utm_source=gawp-config&utm_campaign=wpgaplugin" target="_blank">', '</a>' ) );
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Enable Demographics and Interest Reports', 'google-analytics-for-wordpress' ), 'demographics', null, sprintf( __( 'You have to enable the Demographics in Google Analytics before you can see the tracking data. We have a doc in our %1$sknowlegde base%2$s about this feature.', 'google-analytics-for-wordpress' ), '<a href="http://kb.yoast.com/article/154-enable-demographics-and-interests-report-in-google-analytics/#utm_medium=kb-link&utm_source=gawp-config&utm_campaign=wpgaplugin" target="_blank">', '</a>' ) );
		?>
	</div>
	<div id="advanced" class="gatab">
		<?php
		echo '<h2>' . __( 'Advanced settings', 'google-analytics-for-wordpress' ) . '</h2>';
		echo Yoast_GA_Admin_Form::select( __( 'Track downloads as', 'google-analytics-for-wordpress' ), 'track_download_as', $yoast_ga_admin->track_download_types(), __( 'Not recommended, as this would skew your statistics, but it does make it possible to track downloads as goals.', 'google-analytics-for-wordpress' ) );
		echo Yoast_GA_Admin_Form::input( 'text', __( 'Extensions of files to track as downloads', 'google-analytics-for-wordpress' ), 'extensions_of_files', null, 'Please separate extensions using commas' );
		echo Yoast_GA_Admin_Form::select( __( 'Track full URL of outbound clicks or just the domain', 'google-analytics-for-wordpress' ), 'track_full_url', $yoast_ga_admin->get_track_full_url() );
		echo Yoast_GA_Admin_Form::input( 'text', __( 'Subdomain tracking', 'google-analytics-for-wordpress' ), 'subdomain_tracking', null, __( 'This allows you to set the domain that\'s set by <code>setDomainName</code> for tracking subdomains, if empty this will not be set.', 'google-analytics-for-wordpress' ) );

		echo Yoast_GA_Admin_Form::input( 'text', __( 'Set path for internal links to track as outbound links', 'google-analytics-for-wordpress' ), 'track_internal_as_outbound', null, 'If you want to track all internal links that begin with <code>/out/</code>, enter <code>/out/</code> in the box above. If you have multiple prefixes you can separate them with comma\'s: <code>/out/,/recommends/</code>' );
		echo Yoast_GA_Admin_Form::input( 'text', __( 'Label for those links', 'google-analytics-for-wordpress' ), 'track_internal_as_label', null, 'The label to use for these links, this will be added to where the click came from, so if the label is "aff", the label for a click from the content of an article becomes "outbound-article-aff".' );

		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Tag links in RSS feed with campaign variables', 'google-analytics-for-wordpress' ), 'tag_links_in_rss', null, __( 'Do not use this feature if you use FeedBurner, as FeedBurner can do this automatically, and better than this plugin can. Check <a href="https://support.google.com/feedburner/answer/165769?hl=en&amp;ref_topic=13075" target="_blank">this help page</a> for info on how to enable this feature in FeedBurner.', 'google-analytics-for-wordpress' ) );
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Allow anchor', 'google-analytics-for-wordpress' ), 'allow_anchor', null, sprintf( __( 'This adds a <code>%1$s_setAllowAnchor%2$s</code> call to your tracking code, and makes RSS link tagging use a # as well.', 'google-analytics-for-wordpress' ), '<a href="http://code.google.com/apis/analytics/docs/gaJSApiCampaignTracking.html#_gat.GA_Tracker_._setAllowAnchor" target="_blank">', '</a>' ) );
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Add <code>_setAllowLinker</code>', 'google-analytics-for-wordpress' ), 'add_allow_linker', null, sprintf( __( 'This adds a <code>%1$s_setAllowLinker%2$s</code> call to your tracking code,  allowing you to use <code>_link</code> and related functions.', 'google-analytics-for-wordpress' ), '<a href="http://code.google.com/apis/analytics/docs/gaJS/gaJSApiDomainDirectory.html#_gat.GA_Tracker_._setAllowLinker" target="_blank">', '</a>' ) );
		echo Yoast_GA_Admin_Form::textarea( 'Custom code', 'custom_code', __( 'Not for the average user: this allows you to add a line of code, to be added before the <code>trackPageview</code> call.', 'google-analytics-for-wordpress' ) );

		do_action( 'yst_ga_advanced-tab' );
		?>
	</div>
	<div id="customdimensions" class="gatab">
		<?php
		echo '<h2>' . __( 'Custom dimensions', 'google-analytics-for-wordpress' ) . '</h2>';
		do_action( 'yst_ga_custom_dimensions_tab-content' );
		?>
	</div>
	<?php do_action( 'yst_ga_custom_tabs-content' ); ?>
	<div id="debugmode" class="gatab">
		<?php
		echo '<h2>' . __( 'Debug settings', 'google-analytics-for-wordpress' ) . '</h2>';

		echo '<div id="ga-promote">';
		echo '<p class="ga-topdescription">' . __( 'If you want to confirm that tracking on your blog is working as it should, enable this option and check the console of your browser. Be absolutely sure to disable debugging afterwards, as it is slower than normal tracking.', 'google-analytics-for-wordpress' ) . '</p>';
		echo '<p class="ga-topdescription"><strong>' . __( 'Note', 'google-analytics-for-wordpress' ) . ':</strong> ' . __( 'the debugging scripts is only loaded for admins.', 'google-analytics-for-wordpress' ) . '</p>';
		echo '</div>';
		echo Yoast_GA_Admin_Form::input( 'checkbox', __( 'Enable debug mode', 'google-analytics-for-wordpress' ), 'debug_mode' );
		?>
	</div>
</div>
<?php
echo Yoast_GA_Admin_Form::end_form( 'Save changes', 'settings' );
echo $yoast_ga_admin->content_footer();
?>
<script type="text/javascript">
	jQuery(document).ready(
		function () {
			jQuery('#yoast-ga-form-select-settings-analytics_profile').chosen({
				group_search : true
			});
			jQuery('#yoast-ga-form-select-settings-ignore_users').chosen({placeholder_text_multiple: '<?php echo __( 'Select the users to ignore', 'google-analytics-for-wordpress' ); ?>'});
		}
	);
</script>
