<?php
/**
 * MonsterInsights Metaboxes
 *
 * @since 8.5.1
 *
 * @package MonsterInsights
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MonsterInsights_MetaBoxes' ) ) {
	class MonsterInsights_MetaBoxes {

		private static $_instance = null;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function register_hooks() {
			add_action( 'monsterinsights_after_exclude_metabox', array(
				$this,
				'print_dummy_page_insights_metabox_html'
			) );
		}

		public function print_dummy_page_insights_metabox_html() {
			?>
			<div class="monsterinsights-metabox lite" id="monsterinsights-metabox-page-insights">
				<a class="button" href="#" id="monsterinsights_show_page_insights">
					<?php _e( 'Show Page Insights', 'google-analytics-for-wordpress' ); ?>
				</a>

				<div id="monsterinsights-page-insights-content">
					<div class="monsterinsights-page-insights__tabs">
						<a href="#" class="monsterinsights-page-insights__tabs-tab active"
						   data-tab="monsterinsights-last-30-days-content">
							<?php _e( 'Last 30 days', 'google-analytics-for-wordpress' ); ?>
						</a>
						<a href="#" class="monsterinsights-page-insights__tabs-tab"
						   data-tab="monsterinsights-yesterday-content">
							<?php _e( 'Yesterday', 'google-analytics-for-wordpress' ); ?>
						</a>
					</div>
					<div class="monsterinsights-page-insights-tabs-content">
						<div class="monsterinsights-page-insights-tabs-content__tab active"
							 id="monsterinsights-last-30-days-content">
							<div class="monsterinsights-page-insights-tabs-content__tab-items">

								<div class="monsterinsights-page-insights-tabs-content__tab-item">
									<div class="monsterinsights-page-insights-tabs-content__tab-item__result">
										<span>1m 43s</span>
									</div>
									<div class="monsterinsights-page-insights-tabs-content__tab-item__title">
										<?php _e( 'Time on Page', 'google-analytics-for-wordpress' ); ?>
									</div>
								</div>

								<div class="monsterinsights-page-insights-tabs-content__tab-item">
									<div class="monsterinsights-page-insights-tabs-content__tab-item__result">
										<span>19056</span>
									</div>
									<div class="monsterinsights-page-insights-tabs-content__tab-item__title">
										<?php _e( 'Entrances', 'google-analytics-for-wordpress' ); ?>
									</div>
								</div>
								<div class="monsterinsights-page-insights-tabs-content__tab-item">
									<div class="monsterinsights-page-insights-tabs-content__tab-item__result">
										<span>26558</span>
									</div>
									<div class="monsterinsights-page-insights-tabs-content__tab-item__title">
										<?php _e( 'Page Views', 'google-analytics-for-wordpress' ); ?>
									</div>
								</div>
								<div class="monsterinsights-page-insights-tabs-content__tab-item">
									<div class="monsterinsights-page-insights-tabs-content__tab-item__result">
										<span>13428</span>
									</div>
									<div class="monsterinsights-page-insights-tabs-content__tab-item__title">
										<?php _e( 'Exits', 'google-analytics-for-wordpress' ); ?>
									</div>
								</div>

							</div>
						</div>
						<div class="monsterinsights-page-insights-tabs-content__tab"
							 id="monsterinsights-yesterday-content">
							<div class="monsterinsights-page-insights-tabs-content__tab-items">

								<div class="monsterinsights-page-insights-tabs-content__tab-item">
									<div class="monsterinsights-page-insights-tabs-content__tab-item__result">
										<span>1m 43s</span>
									</div>
									<div class="monsterinsights-page-insights-tabs-content__tab-item__title">
										<?php _e( 'Time on Page', 'google-analytics-for-wordpress' ); ?>
									</div>
								</div>

								<div class="monsterinsights-page-insights-tabs-content__tab-item">
									<div class="monsterinsights-page-insights-tabs-content__tab-item__result">
										<span>19056</span>
									</div>
									<div class="monsterinsights-page-insights-tabs-content__tab-item__title">
										<?php _e( 'Entrances', 'google-analytics-for-wordpress' ); ?>
									</div>
								</div>
								<div class="monsterinsights-page-insights-tabs-content__tab-item">
									<div class="monsterinsights-page-insights-tabs-content__tab-item__result">
										<span>26558</span>
									</div>
									<div class="monsterinsights-page-insights-tabs-content__tab-item__title">
										<?php _e( 'Page Views', 'google-analytics-for-wordpress' ); ?>
									</div>
								</div>
								<div class="monsterinsights-page-insights-tabs-content__tab-item">
									<div class="monsterinsights-page-insights-tabs-content__tab-item__result">
										<span>13428</span>
									</div>
									<div class="monsterinsights-page-insights-tabs-content__tab-item__title">
										<?php _e( 'Exits', 'google-analytics-for-wordpress' ); ?>
									</div>
								</div>

							</div>
						</div>
					</div>

					<a class="button" href="#" id="monsterinsights_hide_page_insights">
						<?php _e( 'Hide Page Insights', 'google-analytics-for-wordpress' ); ?>
					</a>
				</div>

			</div>
			<?php
		}
	}

	MonsterInsights_MetaBoxes::instance()->register_hooks();
}
