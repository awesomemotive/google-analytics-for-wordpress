<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function monsterinsights_tools_url_builder() {
	ob_start(); ?>
	<div class="monsterinsights-upsell-under-box">
		<h2><?php esc_html_e( "Want even more fine tuned control over your website analytics?", 'google-analytics-for-wordpress' ); ?></h2>
		<p class="monsterinsights-upsell-lite-text"><?php esc_html_e( "By upgrading to MonsterInsights Pro, you can unlock the MonsterInsights URL builder that helps you better track your advertising and email marketing campaigns.", 'google-analytics-for-wordpress' ); ?></p>
		<p><a href="<?php echo monsterinsights_get_upgrade_link(); // phpcs:ignore ?>"
			  class="button button-primary"><?php esc_html_e( "Click here to Upgrade", 'google-analytics-for-wordpress' ); ?></a>
		</p>
	</div>
	<?php
	// Sanitization applied above
	echo ob_get_clean(); // phpcs:ignore
}

add_action( 'monsterinsights_tools_url_builder_tab', 'monsterinsights_tools_url_builder' );
