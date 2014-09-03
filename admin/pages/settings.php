<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Yoast Google Analytics: Settings', 'google-analytics-for-wordpress' ); ?></h2>

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
			echo $yoast_ga_admin->input( 'checkbox', __('Track outbound click & downloads', 'google-analytics-for-wordpress' ), 'track_outbound' );
			echo $yoast_ga_admin->input( 'checkbox', __('Allow tracking of anonymous data', 'google-analytics-for-wordpress' ), 'anonymous_data' );
			echo $yoast_ga_admin->input( 'checkbox', __('Anonymize IP\'s', 'google-analytics-for-wordpress' ), 'anonymize_ips' );
			echo $yoast_ga_admin->select( 'Ignore users', 'ignore_users', $yoast_ga_admin->get_userroles(), __('Hint: Select multiple roles by using CTRL or CMD.', 'google-analytics-for-wordpress' ), true );
			?>
		</div>
		<div id="universal" class="gatab">
			<?php
			echo '<h2>' . __( 'Universal settings', 'google-analytics-for-wordpress' ) . '</h2>';
			echo $yoast_ga_admin->input( 'checkbox', __('Enable Universal tracking', 'google-analytics-for-wordpress' ), 'enable_universal' );
			echo $yoast_ga_admin->input( 'checkbox', __('Enable Demographics and Interest Reports', 'google-analytics-for-wordpress' ), 'demographics' );
			?>
		</div>
		<div id="advanced" class="gatab">
			<?php
			echo '<h2>' . __( 'Advanced settings', 'google-analytics-for-wordpress' ) . '</h2>';
			echo $yoast_ga_admin->select( __('Track downloads as', 'google-analytics-for-wordpress' ), 'track_download_as', $yoast_ga_admin->track_download_types() );
			echo $yoast_ga_admin->input( 'text', __('Extensions of files to track as downloads', 'google-analytics-for-wordpress' ), 'extensions_of_files', NULL, 'Please separate extensions using commas' );
			echo $yoast_ga_admin->select( __('Track full URL of outbound clicks or just the domain', 'google-analytics-for-wordpress' ), 'track_full_url', $yoast_ga_admin->get_track_full_url() );
			echo $yoast_ga_admin->input( 'text', __('Subdomain tracking', 'google-analytics-for-wordpress' ), 'subdomain_tracking', NULL, __('This allows you to set the domain that\'s set by <code>setDomainName</code> for tracking subdomains, if empty this will not be set.', 'google-analytics-for-wordpress' ) );

			echo $yoast_ga_admin->input( 'text', __('Set path for internal links to track as outbound links', 'google-analytics-for-wordpress' ), 'track_internal_as_outbound', NULL, 'If you want to track all internal links that begin with <code>/out/</code>, enter <code>/out/</code> in the box above. If you have multiple prefixes you can separate them with comma\'s: <code>/out/,/recommends/</code>' );
			echo $yoast_ga_admin->input( 'text', __('Label for those links', 'google-analytics-for-wordpress' ), 'track_internal_as_label', NULL, "The label to use for these links, this will be added to where the click came from, so if the label is \"aff\", the label for a click from the content of an article becomes \"outbound-article-aff\"." );

			echo $yoast_ga_admin->input( 'checkbox', __('Tag links in RSS feed with campaign variables', 'google-analytics-for-wordpress' ), 'tag_links_in_rss' );
			echo $yoast_ga_admin->input( 'checkbox', __('Allow anchor', 'google-analytics-for-wordpress' ), 'allow_anchor' );
			echo $yoast_ga_admin->input( 'checkbox', __('Add <code>_setAllowLinker</code>', 'google-analytics-for-wordpress' ), 'add_allow_linker' );
			echo $yoast_ga_admin->input( 'checkbox', __('Force SSL'), 'force_ssl' );
			echo $yoast_ga_admin->textarea( 'Custom code', 'custom_code', __('This code will be added in the Google Analytics javascript.', 'google-analytics-for-wordpress' ) );
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