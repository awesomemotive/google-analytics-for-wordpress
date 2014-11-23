<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Yoast Google Analytics: ', 'google-analytics-for-wordpress' ) . __( 'Dashboard', 'google-analytics-for-wordpress' ); ?></h2>

	<div id="ga-promote">
		<p><?php printf( __( 'This feature %1$swill be coming soon%2$s. For now, you can %3$sread our posts on Analytics%2$s, or of course, %4$slog into Google Analytics%2$s yourself.', 'google-analytics-for-wordpress' ), '<a href="https://yoast.com/google-analytics-5/#utm_medium=textlink&utm_source=gawp-config&utm_campaign=wpgaplugin">', '</a>', '<a href="https://yoast.com/cat/analytics/#utm_medium=textlink&utm_source=gawp-config&utm_campaign=wpgaplugin">', '<a href="http://www.google.com/analytics/">' ); ?>
	</div>

<?php
echo $yoast_ga_admin->content_footer();
?>