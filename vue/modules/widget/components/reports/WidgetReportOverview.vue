<template>
	<div>
		<div v-if="showChart()" :key="chartKey" class="monsterinsights-report-tabs monsterinsights-report-row">
			<div class="monsterinsights-report-tabs-navigation">
				<button :class="activeTabButtonClass('sessions')" v-on:click="switchTab('sessions')">
					<i class="monstericon-user"></i>
					<span v-text="text_sessions"></span>
				</button>
				<button :class="activeTabButtonClass('pageviews')" v-on:click="switchTab('pageviews')">
					<i class="monstericon-eye"></i>
					<span v-text="text_pageviews"></span>
				</button>
			</div>
			<div v-if="'sessions'===current_tab" class="monsterinsights-report-tabs-content">
				<report-overview-line-chart id="overview" :chart-data="sessionsData()"
					:tooltip="text_sessions_tooltip"
				/>
			</div>
			<div v-if="'pageviews'===current_tab" class="monsterinsights-report-tabs-content">
				<report-overview-line-chart id="overview" :chart-data="pageviewsData()"
					:tooltip="text_pageviews_tooltip"
				/>
			</div>
		</div>
		<div class="monsterinsights-report-row monsterinsights-report-infobox-row">
			<report-infobox :title="text_sessions" :value="infoboxSessionsData.value"
				:change="infoboxSessionsData.change" :color="infoboxSessionsData.color"
				:direction="infoboxSessionsData.direction" :days="infoboxRange"
				:tooltip="text_infobox_tooltip_sessions"
			/>
			<report-infobox :title="text_pageviews" :value="infoboxPageviewsData.value"
				:change="infoboxPageviewsData.change" :color="infoboxPageviewsData.color"
				:direction="infoboxPageviewsData.direction" :days="infoboxRange"
				:tooltip="text_infobox_tooltip_pageviews"
			/>
			<report-infobox :title="text_duration" :value="infoboxDurationData.value"
				:change="infoboxDurationData.change" :color="infoboxDurationData.color"
				:direction="infoboxDurationData.direction" :days="infoboxRange"
				:tooltip="text_infobox_tooltip_average"
			/>
			<report-infobox :title="text_bounce" :value="infoboxBounceData.value"
				:change="infoboxBounceData.change" :color="infoboxBounceData.color"
				:direction="infoboxBounceData.direction" :days="infoboxRange"
				:tooltip="text_infobox_tooltip_bounce"
			/>
		</div>
		<WidgetTips />
	</div>
</template>
<script>
	import { mapGetters } from 'vuex';
	import { __, sprintf } from '@wordpress/i18n';
	import ReportOverviewLineChart from "../../../reports/components/reports-overview/ReportOverviewLineChart";
	import ReportInfobox from "../../../reports/components/ReportInfobox";
	import WidgetTips from "../WidgetTips";

	export default {
		name: 'WidgetReportOverview',
		components: { WidgetTips, ReportInfobox, ReportOverviewLineChart },
		computed: {
			...mapGetters( {
				overview: '$_reports/overview',
				widget_width: '$_widget/width',
			} ),
			infoboxRange() {
				return this.overview.infobox && this.overview.infobox.range ? this.overview.infobox.range : 0;
			},
			infoboxSessionsData() {
				return this.infoboxData( 'sessions' );
			},
			infoboxPageviewsData() {
				return this.infoboxData( 'pageviews' );
			},
			infoboxDurationData() {
				return this.infoboxData( 'duration' );
			},
			infoboxBounceData() {
				return this.infoboxData( 'bounce', true );
			},
		},
		data() {
			return {
				chartKey: 0,
				current_tab: 'sessions',
				text_sessions: __( 'Sessions', process.env.VUE_APP_TEXTDOMAIN ),
				text_sessions_tooltip: sprintf( __( 'Unique %s Sessions', process.env.VUE_APP_TEXTDOMAIN ), '<br />' ),
				text_pageviews: __( 'Pageviews', process.env.VUE_APP_TEXTDOMAIN ),
				text_pageviews_tooltip: sprintf( __( 'Unique %s Pageviews', process.env.VUE_APP_TEXTDOMAIN ), '<br />' ),
				text_infobox_tooltip_sessions: __( 'A session is the browsing session of a single user to your site.', process.env.VUE_APP_TEXTDOMAIN ),
				text_infobox_tooltip_pageviews: __( 'A pageview is defined as a view of a page on your site that is being tracked by the Analytics tracking code. Each refresh of a page is also a new pageview.', process.env.VUE_APP_TEXTDOMAIN ),
				text_infobox_tooltip_average: __( 'Total duration of all sessions (in seconds) / number of sessions.', process.env.VUE_APP_TEXTDOMAIN ),
				text_infobox_tooltip_bounce: __( 'Percentage of single page visits (or web sessions). It is the number of visits in which a person leaves your website from the landing page without browsing any further.', process.env.VUE_APP_TEXTDOMAIN ),
				text_duration: __( 'Avg. Session Duration', process.env.VUE_APP_TEXTDOMAIN ),
				text_bounce: __( 'Bounce Rate', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		methods: {
			switchTab( tab ) {
				this.current_tab = tab;
			},
			activeTabButtonClass( tab ) {
				return tab === this.current_tab ? 'monsterinsights-active-tab-button' : '';
			},
			sessionsData() {
				if ( this.overview.overviewgraph ) {
					return {
						labels: this.overview.overviewgraph.labels,
						datasets: [
							{
								lineTension: 0, // ChartJS doesn't make nice curves like in the PSD so for now leaving straight on
								borderColor: '#5fa6e7',
								backgroundColor: 'rgba(	109, 176, 233, 0.2)',
								fillOpacity: 0.2,
								fillColor: 'rgba(	109, 176, 233, 0.2)',
								pointRadius: 4,
								pointBorderColor: '#3783c4',
								pointBackgroundColor: '#FFF',
								hoverRadius: 1,
								pointHoverBackgroundColor: '#FFF', // Point background color when hovered.
								pointHoverBorderColor: '#3783c4', //Point border color when hovered.
								pointHoverBorderWidth: 4, //Border width of point when hovered.
								pointHoverRadius: 6, //The radius of the point when hovered.
								labels: this.overview.overviewgraph.labels,
								data: this.overview.overviewgraph.sessions.datapoints,
								trend: this.overview.overviewgraph.sessions.trendpoints,
							},
						],
					};
				}

				return {};
			},
			pageviewsData() {
				if ( this.overview.overviewgraph ) {
					return {
						labels: this.overview.overviewgraph.labels,
						datasets: [
							{
								lineTension: 0, // ChartJS doesn't make nice curves like in the PSD so for now leaving straight on
								borderColor: '#5fa6e7',
								backgroundColor: 'rgba(	109, 176, 233, 0.2)',
								fillOpacity: 0.2,
								fillColor: 'rgba(	109, 176, 233, 0.2)',
								pointRadius: 4,
								pointBorderColor: '#3783c4',
								pointBackgroundColor: '#FFF',
								hoverRadius: 1,
								pointHoverBackgroundColor: '#FFF', // Point background color when hovered.
								pointHoverBorderColor: '#3783c4', //Point border color when hovered.
								pointHoverBorderWidth: 4, //Border width of point when hovered.
								pointHoverRadius: 6, //The radius of the point when hovered.
								labels: this.overview.overviewgraph.labels,
								data: this.overview.overviewgraph.pageviews.datapoints,
								trend: this.overview.overviewgraph.pageviews.trendpoints,
							},
						],
					};
				}

				return {};
			},
			showChart() {
				let show = true;

				if ( this.overview.overviewgraph && 0 === this.overview.overviewgraph.count ) {
					show = false;
				}

				return show;
			},
			infoboxData( type, reversed = false ) {
				let preparedData = {};
				if ( this.overview.infobox && this.overview.infobox[type] ) {
					preparedData.change = this.overview.infobox[type]['prev'];
					preparedData.value = this.overview.infobox[type]['value'].toString();
					if ( 0 === this.overview.infobox[type]['prev'] ) {
						preparedData.direction = '';
					} else if ( this.overview.infobox[type]['prev'] > 0 ) {
						preparedData.direction = 'up';
						preparedData.color = 'green';
					} else {
						preparedData.direction = 'down';
						preparedData.color = 'red';
					}
				}

				if ( reversed ) {
					if ( 'down' === preparedData.direction ) {
						preparedData.color = 'green';
					} else {
						preparedData.color = 'red';
					}
				}

				return preparedData;
			},
			forceRerender() {
				this.chartKey += 1;
			},
		},
		created() {
			const self = this;
			const widgets_container = document.getElementById( 'dashboard-widgets' );
			if ( jQuery ) {
				jQuery( widgets_container ).on( 'sortstop', function() {
					self.forceRerender();
				} );
			}
		},
		watch: {
			widget_width: function() {
				this.forceRerender();
			},
		},
	};
</script>
