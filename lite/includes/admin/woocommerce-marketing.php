<?php
/**
 * Manage MonsterInsights section on WooCommerce Marketing page
 *
 * @since 8.16
 *
 * @package MonsterInsights
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MonsterInsights_WooCommerce_Marketing
 */
class MonsterInsights_WooCommerce_Marketing {

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'handle_enqueuing_assets' ), 20 );
	}

	/**
	 * Handle enqueuing script.
	 *
	 * @return void
	 */
	public function handle_enqueuing_assets( $page ) {
		if ( ! check_is_it_monsterinsights_lite() ) {
			return;
		}

		if ( 'woocommerce_page_wc-admin' != $page ) {
			return;
		}

		if ( isset( $_GET['path'] ) && '/marketing' == $_GET['path'] ) {
			wp_enqueue_script(
				'monsterinsights-wc-marketing-box',
				plugins_url( 'assets/js/wc-marketing.js', MONSTERINSIGHTS_PLUGIN_FILE ),
				array( 'jquery' ),
				monsterinsights_get_asset_version(),
				true
			);

			add_action( 'admin_footer', array( $this, 'output_analytics_card_template' ), 20 );
		}
	}

	/**
	 * Print analytics card to marketing page.
	 *
	 * @return void
	 */
	public function output_analytics_card_template() {
		echo $this->get_card_style();
		?>
		<div id="monsterinsights-wcm-components-card" class="monsterinsights-wcm-components-card" style="display: none">
			<div class="monsterinsights-wcm-components-card-header">
				<div
					class="woocommerce-marketing-card-header-title"><?php esc_html_e( 'Track WooCommerce Sales', 'google-analytics-for-wordpress' ); ?></div>
			</div>
			<div class="monsterinsights-wcm-components-card-body woocommerce_marketing_plugin_card_body">
				<div class="woocommerce_marketing_plugin_card_body__icon">
					<img src="<?php echo plugins_url( 'assets/images/mascot.png', MONSTERINSIGHTS_PLUGIN_FILE ); ?>"
						 alt="MonsterInsights Icon">
				</div>
				<div class="woocommerce_marketing_plugin_card_body__details">
					<div class="woocommerce_marketing_plugin_card_body__details-name">MonsterInsights</div>
					<div
						class="woocommerce_marketing_plugin_card_body__details-description"><?php esc_html_e( 'MonsterInsights makes it easy to see the stats that matter to help make you more money.', 'google-analytics-for-wordpress' ); ?></div>
				</div>
				<a href="<?php echo esc_url( monsterinsights_get_url( 'wc-admin', 'wc-marketing' ) ); ?>"
				   target="_blank"
				   class="components-button is-secondary"><?php esc_html_e( 'Upgrade Now', 'google-analytics-for-wordpress' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Necessary CSS for card.
	 *
	 * @return string
	 */
	private function get_card_style() {
		ob_start();
		?>
		<style>
			.monsterinsights-wcm-components-card * {
				box-sizing: border-box;
			}

			.monsterinsights-wcm-components-card {
				background-color: rgb(255, 255, 255);
				color: rgb(30, 30, 30);
				position: relative;
				box-shadow: rgba(0, 0, 0, 0.1) 0 0 0 1px;
				border-radius: calc(1px);
			}

			.monsterinsights-wcm-components-card-header {
				border-bottom: 1px solid rgba(0, 0, 0, 0.1);
				border-top-color: rgba(0, 0, 0, 0.1);
				border-right-color: rgba(0, 0, 0, 0.1);
				border-left-color: rgba(0, 0, 0, 0.1);
				padding: calc(16px) calc(24px);
			}

			.monsterinsights-wcm-components-card-body {
				padding: 16px 24px;
			}
		</style>
		<?php
		$contents = ob_get_clean();

		return str_replace( array( "\n", "\r", "\t" ), '', $contents );
	}
}

new MonsterInsights_WooCommerce_Marketing();
