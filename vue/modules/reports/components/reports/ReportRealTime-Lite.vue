<template>
	<main class="monsterinsights-report monsterinsights-report-realtime">
		<div class="monsterinsights-report-top">
			<h2 v-text="text_realtime"></h2>
		</div>
		<div class="monsterinsights-report-row monsterinsights-report-flex monsterinsights-report-2-columns">
			<div class="monsterinsights-report-box">
				<h3 v-text="text_right_now"></h3>
				<settings-info-tooltip :content="text_right_now_tooltip" />
				<div class="monsterinsights-realtime-box-content">
					<template>
						<div class="monsterinsights-realtime-large" v-text="0"></div>
						<div class="monsterinsights-realtime-active" v-text="text_active"></div>
					</template>
					<p v-text="text_active_explainer"></p>
					<p>
						<span v-text="text_refresh_explainer"></span>
						<span v-text="sprintf( text_refresh_ago, seconds )"></span>
						<span v-text="text_refresh_explainer_2"></span>
						<span v-text="text_refresh_explainer_3"></span>
					</p>
				</div>
			</div>
			<div class="monsterinsights-report-box">
				<h3 v-text="text_graph_title"></h3>
				<settings-info-tooltip :content="text_pageviews_tooltip" />
				<div class="monsterinsights-realtime-box-content">
				</div>
			</div>
		</div>
		<div class="monsterinsights-report-row">
			<report-table-box :title="text_top_pages" :headers="top_pages_headers" :rows="[]"
				:emptytext="text_top_pages_empty" :tooltip="text_top_pages_tooltip"
			>
			</report-table-box>
		</div>
		<report-upsell-overlay report="realtime" />
	</main>
</template>
<script>
	import { __, sprintf } from '@wordpress/i18n';
	import ReportTableBox from "../ReportTableBox";
	import SettingsInfoTooltip from "../../../settings/components/SettingsInfoTooltip";
	import ReportUpsellOverlay from "../upsells/ReportUpsellOverlay";

	export default {
		name: 'ReportRealTime',
		components: { ReportUpsellOverlay, SettingsInfoTooltip, ReportTableBox },
		data() {
			return {
				text_realtime: __( 'Real-Time Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_right_now: __( 'Right Now', process.env.VUE_APP_TEXTDOMAIN ),
				text_active: __( 'Active users on site', process.env.VUE_APP_TEXTDOMAIN ),
				text_graph_not_available: __( 'The real-time graph of visitors over time is not currently available for this site. Please try again later.', process.env.VUE_APP_TEXTDOMAIN ),
				text_active_explainer: __( 'Important: this only includes users who are tracked in real-time. Not all users are tracked in real-time including (but not limited to) logged-in site administrators, certain mobile users, and users who match a Google Analytics filter.', process.env.VUE_APP_TEXTDOMAIN ),
				text_refresh_explainer: __( 'The real-time report automatically updates approximately every 60 seconds.', process.env.VUE_APP_TEXTDOMAIN ),
				text_refresh_ago: __( 'The real-time report was last updated %s seconds ago.', process.env.VUE_APP_TEXTDOMAIN ),
				text_refresh_explainer_2: __( 'The latest data will be automatically shown on this page when it becomes available.', process.env.VUE_APP_TEXTDOMAIN ),
				text_refresh_explainer_3: __( 'There is no need to refresh the browser (doing so won\'t have any effect).', process.env.VUE_APP_TEXTDOMAIN ),
				text_graph_title: __( 'Pageviews Per Minute', process.env.VUE_APP_TEXTDOMAIN ),
				text_chart_tooltip: sprintf( __( 'Unique %s Pageviews', process.env.VUE_APP_TEXTDOMAIN ), '<br />' ),
				text_top_pages: __( 'Top Pages', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_pages_empty: __( 'No pageviews currently.', process.env.VUE_APP_TEXTDOMAIN ),
				top_pages_headers: [
					__( 'Page', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Pageview Count', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Percent of Total', process.env.VUE_APP_TEXTDOMAIN ),
				],

				text_right_now_tooltip: __( 'This is the number of active users currently on your site.', process.env.VUE_APP_TEXTDOMAIN ),
				text_pageviews_tooltip: __( 'This graph shows the number of pageviews for each of the last 30 minutes.', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_pages_tooltip: __( 'This list shows the top pages users are currently viewing on your site.', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_pages_button: __( 'View All Real-Time Pageviews', process.env.VUE_APP_TEXTDOMAIN ),
				text_referrals_button: __( 'View All Real-Time Traffic Sources', process.env.VUE_APP_TEXTDOMAIN ),
				text_countries_button: __( 'View All Real-Time Traffic by Country', process.env.VUE_APP_TEXTDOMAIN ),
				text_city_button: __( 'View All Real-Time Traffic by City', process.env.VUE_APP_TEXTDOMAIN ),
				seconds: 12,
			};
		},
		mounted() {
			this.$store.commit( '$_reports/ENABLE_BLUR' );
		},
		methods: {
			sprintf,
		},
	};
</script>
