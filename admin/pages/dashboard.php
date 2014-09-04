<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Yoast Google Analytics: Dashboard', 'google-analytics-for-wordpress' ); ?></h2>

	<div id="ga-promote">
		<p>This feature
			<a href="https://yoast.com/google-analytics-5/#utm_medium=textlink&utm_source=gawp-config&utm_campaign=wpgaplugin">will be coming soon</a>. For now, you can
			<a href="https://yoast.com/cat/analytics/#utm_medium=textlink&utm_source=gawp-config&utm_campaign=wpgaplugin">read our posts on Analytics</a>, or, of course,
			<a href="http://www.google.com/analytics/">log into Google Analytics</a> yourself.</p>

		<p align="center"><img src="<?php echo GAWP_URL . 'img/'; ?>yoast_avatar_joost.png" width="250"></p>
	</div>

<?php
echo $yoast_ga_admin->content_footer();
?>