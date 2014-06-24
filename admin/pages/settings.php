<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
<h2 id="yoast_ga_title"><?php echo __('Yoast Google Analytics: Settings', ''); ?></h2>

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
	echo '<h2>' . __( 'Title settings', 'google-analytics-for-wordpress' ) . '</h2>';
	echo $yoast_ga_admin->input( 'checkbox', 'Do you like this test', 'ga_general[like_test]', 'Yes, I do like it!');
	echo $yoast_ga_admin->input( 'checkbox', 'Do you like this test', 'ga_general[like_test2]', 'Yes, I do like it! 2');
	echo $yoast_ga_admin->input( 'checkbox', 'Do you like this test', 'ga_general[like_test3]', 'Yes, I do like it! 3');
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
		?>
	</div>
</div>
<?php
echo $yoast_ga_admin->end_form();
echo $yoast_ga_admin->content_footer();
?>