<?php
/**
 * A class that handles HTML and data fetching/filtering for the MonsterInsights Stats block.
 */
class MonsterInsights_Site_Insights_Block {

	/**
	 * Register CSS/JS assets used by out MonsterInsights block.
	 * We will only register assets here, the enqueue will take place only when the block is used in content.
	 * At the moment we only load the minified assets since the @wordpress-scripts package doesn't split yet.
	 *
	 * @return void
	 */
	public function register_frontend_scripts() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_register_script( 'apexcharts', plugins_url( 'assets/js/frontend/apexcharts.min.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );

		wp_register_style( 'apexcharts', plugins_url( 'assets/js/frontend/apexcharts.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );

		$scripts_url = apply_filters(
			'monsterinsights_frontend_scripts_url',
			plugins_url( 'assets/js/frontend/block-scripts' . $suffix . '.js', MONSTERINSIGHTS_PLUGIN_FILE )
		);

		wp_register_script( 'monsterinsights-block-scripts', $scripts_url, array( 'apexcharts', 'jquery' ), monsterinsights_get_asset_version(), true );

		// Load the script with specific translations.
		wp_set_script_translations( 'monsterinsights-block-scripts', monsterinsights_is_pro_version() ? 'ga-premium' : 'google-analytics-for-wordpress', plugin_dir_path( __FILE__ ) . 'languages' );

		$style_url = apply_filters(
			'monsterinsights_frontend_style_url',
			plugins_url( 'assets/css/frontend' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE )
		);

		// Load Popular Posts styles.
		wp_register_style( 'monsterinsights-editor-frontend-style', $style_url, array(), monsterinsights_get_asset_version() );

		$use_async = apply_filters( 'monsterinsights_frontend_gtag_script_async', true );

		if ( $use_async ) {
			wp_script_add_data( 'apexcharts', 'strategy', 'async' );
			wp_script_add_data( 'monsterinsights-block-scripts', 'strategy', 'defer' );
		}
	}

	/**
	 * Enqueue styles and scripts needed by this block.
	 *
	 * @return void
	 */
	private function load_assets() {
		wp_enqueue_script( 'monsterinsights-block-scripts' );
		wp_enqueue_style( 'monsterinsights-editor-frontend-style' );
	}

	/**
	 * This method handles the block display.
	 *
	 * @param $attributes
	 * @param $block
	 * @return false|string
	 */
	public function block_output( $attributes, $block ) {
		$type = $attributes['type'];
		$metric = $attributes['metric'];

		// We want our scripts to be loaded only when a block is present.
		$this->load_assets();
		$data = $this->get_data();

		if ( empty( $data ) ) {
			return null;
		}

		// Based on the $type and $metric we'll compose the template path and class name for the block output.
		$template_file = MONSTERINSIGHTS_PLUGIN_DIR . 'includes/gutenberg/site-insights/templates/' . $type . '/class-' . $type . '-' . $metric . '.php';

		if ( ! file_exists( $template_file ) ) {
			return false;
		}

		require_once MONSTERINSIGHTS_PLUGIN_DIR . '/includes/gutenberg/site-insights/templates/class-site-insights-metric-template.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . '/includes/gutenberg/site-insights/templates/class-site-insights-duoscorecard-template.php';
		require_once $template_file;

		$class_name = 'MonsterInsights_SiteInsights_Template_' . ucfirst( $type ) . '_' . ucfirst( $metric );

		if ( ! class_exists( $class_name ) ) {
			return false;
		}

		$template = new $class_name( $attributes, $data );
		$output = $template->output();

		$block_attributes = get_block_wrapper_attributes(
			array(
				'class' => "monsterinsights-{$type}-block",
			)
		);

		return sprintf( '<div %1$s>%2$s</div>', $block_attributes, $output );
	}

	/**
	 * Returns report data for a specific date range.
	 *
	 * @return array|bool
	 */
	private function get_data() {
		// We do not have a current auth.
		$site_auth = MonsterInsights()->auth->get_viewname();
		$ms_auth   = is_multisite() && MonsterInsights()->auth->get_network_viewname();
		if ( ! $site_auth && ! $ms_auth ) {
			return false;
		}

		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/reports/abstract-report.php';
		require_once MONSTERINSIGHTS_PLUGIN_DIR . 'includes/admin/reports/site-insights.php';

		$report = new MonsterInsights_Report_Site_Insights();

		$data = $report->get_data(
			array(
				'start' => date( 'Y-m-d', strtotime( '-31 days' ) ),
				'end' => date( 'Y-m-d', strtotime( '-1 days' ) ),
			)
		);

		if ( ! isset( $data['success'] ) || ! $data['success'] || empty( $data['data'] ) ) {
			return false;
		}

		return $data['data'];
	}
}
