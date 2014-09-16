<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();

$has_extensions = false;

$extensions = array(
	'ecommerce' => (object) array(
		'url'    => 'https://yoast.com/wordpress/plugins/google-analytics/',
		'title'  => __( 'Google Analytics', 'google-analytics-for-wordpress' ) . '<br />' . __( 'E-Commerce tracking', 'google-analytics-for-wordpress' ),
		'desc'   => __( 'Track your E-Commerce data and transactions with this E-Commerce extension for Google Analytics.', 'google-analytics-for-wordpress' ),
		'status' => 'uninstalled',
	),
);

if ( class_exists( 'Yoast_GA_eCommerce_Tracking' ) ) {
	$has_extensions  = true;
	$product         = new Yoast_Product_GA_eCommerce();
	$license_manager = new Yoast_Plugin_License_Manager( $product );

	if ( $license_manager->license_is_valid() ) {
		$extensions['ecommerce']->status = 'active';
	} else {
		$extensions['ecommerce']->status = 'inactive';
	}
}
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Yoast Google Analytics: Extensions', 'google-analytics-for-wordpress' ); ?></h2>

	<h2 class="nav-tab-wrapper" id="ga-tabs">
		<a class="nav-tab active" id="extensions-tab" href="#top#extensions"><?php _e( 'Extensions', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="licenses-tab" href="#top#licenses"><?php _e( 'Licenses', 'google-analytics-for-wordpress' ); ?></a>
	</h2>
	<div class="tabwrapper">
		<div id="extensions" class="wpseotab gatab">
			<?php
				foreach ( $extensions as $name => $extension ) {
					?>
					<div class="extension <?php echo $name; ?>">
						<a target="_blank" href="<?php echo $extension->url; ?>#utm_medium=banner&utm_source=gawp-config&utm_campaign=extension-page-banners">
							<h3><?php echo $extension->title; ?></h3>
						</a>

						<p><?php echo $extension->desc; ?></p>

						<p>
							<?php if ( 'uninstalled' == $extension->status ) { ?>
								<a target="_blank" href="https://yoast.com/wordpress/plugins/ga-ecommerce-edd/#utm_medium=banner&utm_source=gawp-config&utm_campaign=extension-page-banners" class="button-primary">Get this extension</a>
							<?php } else if ( 'inactive' == $extension->status ) { ?>
								<a href="#top#licenses" class="activate-link button-primary">Activate License</a>
							<?php } else { ?>
								<button class="button-primary installed">Installed</button>
							<?php }  ?>
						</p>
					</div>
				<?php
				}
			?>
		</div>
		<div id="licenses" class="wpseotab gatab">
			<?php
			if ( ! $has_extensions ) {
				echo '<p>' . __( 'You have not installed any extensions for Yoast Google Analytics, so there are no licenses to activate.', 'google-analytics-for-wordpress' ) . '</p>';
			} else {
				echo $license_manager->show_license_form( false );
			}
			?>
		</div>
	</div>
	<div class="clear"></div>

<?php
echo $yoast_ga_admin->content_footer();
?>