<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
<h2 id="yoast_ga_title"><?php echo __('Yoast Google Analytics: Settings', 'google-analytics-for-wordpress'); ?></h2>

<h2 class="nav-tab-wrapper" id="ga-tabs">
	<a class="nav-tab" id="general-tab" href="#top#general"><?php _e( 'General', 'google-analytics-for-wordpress' );?></a>
	<a class="nav-tab" id="advanced-tab" href="#top#advanced"><?php _e( 'Advanced', 'google-analytics-for-wordpress' );?></a>
	<a class="nav-tab" id="debugmode-tab" href="#top#debugmode"><?php _e( 'Debug mode', 'google-analytics-for-wordpress' );?></a>
</h2>

<?php
echo $yoast_ga_admin->create_form('settings');
?>
<div class="tabwrapper">
	<div id="general" class="gatab">
	<?php
	echo '<h2>' . __( 'General settings', 'google-analytics-for-wordpress' ) . '</h2>';
	echo $yoast_ga_admin->select( 'Analytics profile', 'ga_general[analytics_profile]', $yoast_ga_admin->get_profiles(), 1432 );
	echo $yoast_ga_admin->input( 'checkbox', NULL, 'ga_general[manual_ua_code]', 'Manually enter your UA code');
	?>
	<div class="clear"><br /></div>

	<?php
	echo $yoast_ga_admin->input( 'checkbox', 'Track outbound click & downloads', 'ga_general[track_outbound]');
	echo $yoast_ga_admin->input( 'checkbox', 'Allow tracking of anonymous data', 'ga_general[anonymous_data]');
	echo $yoast_ga_admin->select( 'Ignore users', 'ga_general[ignore_users]', $yoast_ga_admin->get_userroles(), 'editor', 'Users of the role you select and higher will be ignored, so if you select Editor, all Editors and Administrators will be ignored.');
	echo $yoast_ga_admin->input( 'checkbox', 'Anonymize IP\'s', 'ga_general[anonymize_ips]');
	?>
	</div>
	<div id="advanced" class="gatab">
		<?php
		echo '<h2>' . __( 'Advanced settings', 'google-analytics-for-wordpress' ) . '</h2>';
		?>
	</div>
	<div id="debugmode" class="gatab">
		<?php
		echo '<h2>' . __( 'Debug settings', 'google-analytics-for-wordpress' ) . '</h2>';

		echo '<p class="ga-topdescription">'.__('If you want to confirm that tracking on your blog is working as it should, enable this options and check th console in Firebug (for Firefox), Firebug Lite (for other browsers) or Chrome & Safari\'s Web Inspector. Be absolutely sure to disable debugging afterwards, as it is slower than normal tracking.', 'google-analytics-for-wordpress').'</p>';
		echo '<p class="ga-topdescription"><strong>'.__('Note', 'google-analytics-for-wordpress').':</strong> '.__('the debugging and firebug scripts are only loaded for admins.', 'google-analytics-for-wordpress').'</p>';
		echo $yoast_ga_admin->input( 'checkbox', 'Enable debug mode', 'ga_general[debug_mode]');
		echo $yoast_ga_admin->input( 'checkbox', 'Enable Firebug Lite', 'ga_general[firebug_lite]');
		?>
	</div>
</div>
<?php
echo $yoast_ga_admin->end_form();
echo $yoast_ga_admin->content_footer();
?>