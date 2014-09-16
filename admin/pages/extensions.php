<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Yoast Google Analytics: Extensions', 'google-analytics-for-wordpress' ); ?></h2>

	<h2 class="nav-tab-wrapper" id="ga-tabs">
		<a class="nav-tab active" id="extensions-tab" href="#top#extensions"><?php _e( 'Extensions', 'google-analytics-for-wordpress' ); ?></a>
		<a class="nav-tab" id="licenses-tab" href="#top#licenses"><?php _e( 'Licenses', 'google-analytics-for-wordpress' ); ?></a>
	</h2>
	<div class="tabwrapper">
		<div id="extensions" class="wpseotab gatab">
			<div class="extension ecommerce">
				<a target="_blank" href="https://yoast.com/wordpress/plugins/seo-premium/#utm_medium=banner&utm_source=gawp-config&utm_campaign=extension-page-banners">
					<h3>Google Analytics<br />E-Commerce tracking</h3>
				</a>
				<p>Track your E-Commerce data and transactions with this E-Commerce extension for Google Analytics.</p>
				<p>
					<?php if ( ! class_exists( 'Yoast_GA_eCommerce_Tracking' ) ) { ?>
						<a target="_blank" href="https://yoast.com/wordpress/plugins/ga-ecommerce-edd/#utm_medium=banner&utm_source=gawp-config&utm_campaign=extension-page-banners" class="button-primary">Get this extension</a>
					<?php } else { ?>
						<button class="button-primary installed">Installed</button>
					<?php } ?>
				</p>
			</div>
		</div>
		<div id="licenses" class="wpseotab gatab">
			<?php
			$has_extensions = false;
			if ( class_exists( 'Yoast_GA_eCommerce_Tracking' ) ) {
				$has_extensions  = true;
				$product         = new Yoast_Product_GA_eCommerce();
				$license_manager = new Yoast_Plugin_License_Manager( $product );

				echo $license_manager->show_license_form( false );

				unset( $license_manager );
			}

			if ( ! $has_extensions ) {
			?>
			<p>
				<?php echo __( 'You have not installed any extensions for Yoast Google Analytics, so there are no licenses to activate.', 'google-analytics-for-wordpress' ); ?>
			</p>
			<?php
			}
			?>
		</div>
	</div>
	<div class="clear"></div>

<?php
echo $yoast_ga_admin->content_footer();
?>