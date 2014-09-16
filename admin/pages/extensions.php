<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();

$activated = false;
if ( class_exists( 'Yoast_Product_GA_eCommerce' ) ) {
	$product         = new Yoast_Product_GA_eCommerce();
	$license_manager = new Yoast_Plugin_License_Manager( $product );
	if ( $license_manager->license_is_valid() ) {
		$activated = true;
	}
}

?>
	<h2 id="yoast_ga_title"><?php echo __( 'Yoast Google Analytics: Extensions', 'google-analytics-for-wordpress' ); ?></h2>

	<div id="extensions" class="wpseotab">
		<div class="extension ecommerce">
			<a target="_blank" href="https://yoast.com/wordpress/plugins/seo-premium/#utm_medium=banner&utm_source=gawp-config&utm_campaign=extension-page-banners">
				<h3>Google Analytics<br />E-Commerce tracking</h3>
			</a>
			<p>Track your E-Commerce data and transactions with this E-Commerce extension for Google Analytics.</p>
			<p>
				<?php if ( class_exists( 'Yoast_GA_eCommerce_Tracking' ) ) { ?>
				<a target="_blank" href="https://yoast.com/wordpress/plugins/ga-ecommerce-edd/#utm_medium=banner&utm_source=gawp-config&utm_campaign=extension-page-banners" class="button-primary">Get this extension</a>
				<?php } else if ( ! $activated ) { ?>
					<a href="#top#licenses" class="button-primary">Activate License</a>
				<?php } else { ?>
					<button class="button-primary installed">Installed</button>
				<?php } ?>
			</p>
		</div>
	</div>
	<div class="clear"></div>
<?php
echo $yoast_ga_admin->content_footer();
?>