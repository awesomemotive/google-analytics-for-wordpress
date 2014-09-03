<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Yoast Google Analytics: Dashboard', 'google-analytics-for-wordpress' ); ?></h2>

	<div id="ga-promote">
		<p>This feature is coming soon. For now, we have this avatar from Yoast for you:</p>

		<p align="center"><img src="<?php echo GAWP_URL . 'img/'; ?>yoast_avatar_joost.png" width="250"></p>
	</div>

<?php
echo $yoast_ga_admin->content_footer();
?>