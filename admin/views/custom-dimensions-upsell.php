<?php
/**
 * @package GoogleAnalytics\AdminUpSellView
 */

?>
<?php if ( $add_tab_div === true ) : ?>
<div id="yst_ga_custom_dimensions" class="gatab">
<?php endif; ?>
<div class="ga-promote">
	<p>
	<?php
	printf( __( 'If you want to track custom dimensions like page views per author or post type, you should upgrade to the %1$spremium version of Google Analytics by Yoast%2$s.', 'google-analytics-for-wordpress' ), '<a href="https://yoast.com/wordpress/plugins/google-analytics/#utm_medium=text-link&utm_source=gawp-config&utm_campaign=wpgaplugin&utm_content=custom_dimensions_tab">', '</a>' );
	echo ' ';
	_e( 'This will also give you email access to the support team at Yoast, who will provide support on the plugin 24/7.', 'google-analytics-for-wordpress' );
	?>
	</p>
</div>
<?php if ( $add_tab_div === true ) : ?>
</div>
<?php endif; ?>