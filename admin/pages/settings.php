<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Yoast Google Analytics: Settings', 'google-analytics-for-wordpress' ); ?></h2>

	<h2 class="nav-tab-wrapper" id="ga-tabs">
		<a class="nav-tab" id="general-tab" href="#top#general"><?php _e( 'General', 'google-analytics-for-wordpress' ); ?></a>
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
			if( count($yoast_ga_admin->get_profiles()) == 0 ){

				echo '<div class="ga-form ga-form-input">';
				echo '<label class="ga-form ga-form-text-label ga-form-label-left" id="yoast-ga-form-label-text-ga-authwithgoogle" />' . __( 'Google profile', 'google-analytics-for-wordpress' ) . ':</label>';
				echo '<input type="button" name="authenticate" value="' . __('Authenticate with your Google account', 'google-analytics-for-wordpress') . '" class="button button-primary ga-form-authenticate" id="ga-authenticate" />';
				echo '</div>';

			}
			else{
				echo $yoast_ga_admin->select( 'Analytics profile', 'analytics_profile', $yoast_ga_admin->get_profiles());
			}
			echo '<label class="ga-form ga-form-checkbox-label ga-form-label-left">';
			echo $yoast_ga_admin->input( 'checkbox', NULL, 'manual_ua_code', 'Manually enter your UA code' );
			echo '</label>';
			echo '<div id="enter_ua">';
			echo $yoast_ga_admin->input( 'text', NULL, 'manual_ua_code_field');
			echo '</div>';
			echo '<div class="clear"></div></div>';
			?>
			<div class="clear"><br /></div>
			<?php
			echo $yoast_ga_admin->input( 'checkbox', 'Track outbound click & downloads', 'track_outbound' );
			echo $yoast_ga_admin->input( 'checkbox', 'Allow tracking of anonymous data', 'anonymous_data' );
			echo $yoast_ga_admin->select( 'Ignore users', 'ignore_users', $yoast_ga_admin->get_userroles(), 'Hint: Select multiple roles by using CTRL or CMD.', true );
			echo $yoast_ga_admin->input( 'checkbox', 'Anonymize IP\'s', 'anonymize_ips' );
			echo $yoast_ga_admin->input( 'checkbox', 'Enable Demographics and Interest Reports', 'demographics' );
			?>
		</div>
		<div id="advanced" class="gatab">
			<?php
			echo '<h2>' . __( 'Advanced settings', 'google-analytics-for-wordpress' ) . '</h2>';
			echo $yoast_ga_admin->select( 'Track downloads as', 'track_download_as', $yoast_ga_admin->track_download_types() );
			echo $yoast_ga_admin->input( 'text', 'Extensions of files to track as downloads', 'extensions_of_files', NULL, 'Comma separated' );
			echo $yoast_ga_admin->select( 'Track full URL of outbound clicks or just the domain', 'track_full_url', $yoast_ga_admin->get_track_full_url() );
			echo $yoast_ga_admin->input( 'text', 'Subdomain tracking', 'subdomain_tracking', NULL, 'This allows you to set the domain that\'s set by <code>setDomainName</code> for tracking subdomains, if empty this will not be set.' );
			echo $yoast_ga_admin->input( 'checkbox', 'Tag links in RSS feed with campaign variables', 'tag_links_in_rss' );
			echo $yoast_ga_admin->input( 'checkbox', 'Allow anchor', 'allow_anchor' );
			echo $yoast_ga_admin->input( 'checkbox', 'Add <code>_setAllowLinker</code>', 'add_allow_linker' );
			echo $yoast_ga_admin->input( 'checkbox', 'Force SSL', 'force_ssl' );
			echo $yoast_ga_admin->textarea( 'Custom code', 'custom_code', 'This code will be added in the Google Analytics javascript.' );
			?>
		</div>
		<div id="debugmode" class="gatab">
			<?php
			echo '<h2>' . __( 'Debug settings', 'google-analytics-for-wordpress' ) . '</h2>';

			echo '<div id="ga-promote">';
			echo '<p class="ga-topdescription">' . __( 'If you want to confirm that tracking on your blog is working as it should, enable this options and check the console in Firebug (for Firefox), Firebug Lite (for other browsers) or Chrome & Safari\'s Web Inspector. Be absolutely sure to disable debugging afterwards, as it is slower than normal tracking.', 'google-analytics-for-wordpress' ) . '</p>';
			echo '<p class="ga-topdescription"><strong>' . __( 'Note', 'google-analytics-for-wordpress' ) . ':</strong> ' . __( 'the debugging and firebug scripts are only loaded for admins.', 'google-analytics-for-wordpress' ) . '</p>';
			echo '</div>';
			echo $yoast_ga_admin->input( 'checkbox', 'Enable debug mode', 'debug_mode' );
			echo $yoast_ga_admin->input( 'checkbox', 'Enable Firebug Lite', 'firebug_lite' );
			?>
		</div>
	</div>
<?php
echo $yoast_ga_admin->end_form( 'Save changes', 'settings' );
echo $yoast_ga_admin->content_footer();
?>