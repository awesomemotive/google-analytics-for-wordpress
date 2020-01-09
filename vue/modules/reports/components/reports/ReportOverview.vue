<template>
	<main class="monsterinsights-report monsterinsights-report-overview">
		<report-overview-upsell-mobile />
		<div class="monsterinsights-report-top">
			<h2 v-text="text_overview"></h2>
			<report-overview-date-picker />
		</div>
		<div v-if="showChart()" class="monsterinsights-report-tabs monsterinsights-report-row">
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
		<report-overview-upsell />
		<div class="monsterinsights-report-row monsterinsights-report-flex">
			<report-overview-pie-chart id="newvsreturning" :chartData="newVsReturningData"
				:title="text_new_vs_returning" :tooltip="text_pie_tooltip_newvsreturning"
			/>
			<report-overview-pie-chart id="devices" :chartData="devicesData" :title="text_device_breakdown"
				:tooltip="text_pie_tooltip_devices"
			/>
		</div>
		<div class="monsterinsights-report-row monsterinsights-report-flex monsterinsights-report-2-columns">
			<report-list-box :title="text_countries" :rows="countriesData" :tooltip="text_countries_tooltip">
				<a v-if="gaLinks" slot="button" :href="overview.galinks.countries"
					class="monsterinsights-button" target="_blank" v-text="text_countries_button"
				></a>
			</report-list-box>
			<report-list-box :title="text_referrals" :rows="referralsData" :tooltip="text_referral_tooltip">
				<a v-if="gaLinks" slot="button" :href="overview.galinks.referrals"
					class="monsterinsights-button" target="_blank" v-text="text_referral_button"
				></a>
			</report-list-box>
		</div>
		<div class="monsterinsights-report-row">
			<report-list-box :title="text_top_posts" :rows="topPostsData" :tooltip="text_top_posts_tooltip">
				<a v-if="gaLinks" slot="button" :href="overview.galinks.topposts"
					class="monsterinsights-button" target="_blank" v-text="text_top_posts_button"
				></a>
			</report-list-box>
		</div>
	</main>
</template>
<script>

	import { __, sprintf } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import ReportOverviewLineChart from '../reports-overview/ReportOverviewLineChart';
	import ReportInfobox from "../ReportInfobox";
	import ReportOverviewPieChart from "../reports-overview/ReportOverviewPieChart";
	import ReportListBox from "../ReportListBox";
	import ReportOverviewUpsell from "../reports-overview/ReportOverviewUpsell-MI_VERSION";
	import ReportOverviewDatePicker from "../reports-overview/ReportOverviewDatePicker-MI_VERSION";
	import ReportOverviewUpsellMobile from "../reports-overview/ReportOverviewUpsellMobile-MI_VERSION";

	export default {
		name: 'ReportOverview',
		components: {
			ReportOverviewUpsellMobile,
			ReportOverviewDatePicker,
			ReportOverviewUpsell,
			ReportListBox, ReportOverviewPieChart, ReportInfobox, ReportOverviewLineChart,
		},
		computed: {
			...mapGetters( {
				overview: '$_reports/overview',
				date: '$_reports/date',
				license: '$_license/license',
				license_network: '$_license/license_network',
			} ),
			gaLinks() {
				return this.overview.galinks ? true : false;
			},
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
			newVsReturningData() {
				if ( this.overview.newvsreturn ) {
					return {
						datasets: [
							{
								data: [
									this.overview.newvsreturn.new,
									this.overview.newvsreturn.returning,
								],
								backgroundColor: [
									'#2679c1',
									'#57a9f1',
								],
							},
						],
						values: [
							this.overview.newvsreturn.new,
							this.overview.newvsreturn.returning,
						],
						labels: [
							__( 'New', process.env.VUE_APP_TEXTDOMAIN ),
							__( 'Returning', process.env.VUE_APP_TEXTDOMAIN ),
						],

					};
				}
				return false;
			},
			devicesData() {
				if ( this.overview.devices ) {
					return {
						datasets: [
							{
								data: [
									this.overview.devices.desktop,
									this.overview.devices.tablet,
									this.overview.devices.mobile,
								],
								backgroundColor: [
									'#2679c1',
									'#57a9f1',
									'#b1dafd',
								],
							},
						],
						values: [
							this.overview.devices.desktop,
							this.overview.devices.tablet,
							this.overview.devices.mobile,
						],
						labels: [
							__( 'Desktop', process.env.VUE_APP_TEXTDOMAIN ),
							__( 'Tablet', process.env.VUE_APP_TEXTDOMAIN ),
							__( 'Mobile', process.env.VUE_APP_TEXTDOMAIN ),
						],

					};
				}
				return false;
			},
			countriesData() {
				let countries = [];
				let number = 0;
				if ( this.overview.countries ) {
					this.overview.countries.forEach( function( country ) {
						number++;
						countries.push( {
							number: number + '.',
							text: '<span class="monsterinsights-flag monsterinsights-flag-' + country.iso.toLowerCase() + '"></span> ' + country.name,
							right: country.sessions,
						} );
					} );
				}
				return countries;
			},
			referralsData() {
				let referrals = [];
				let number = 0;
				if ( this.overview.referrals ) {
					this.overview.referrals.forEach( function( referral ) {
						number++;
						referrals.push( {
							number: number + '.',
							text: '<img src="https://www.google.com/s2/favicons?domain=http://' + referral.url + '" />' + referral.url,
							right: referral.sessions,
						} );
					} );
				}
				return referrals;
			},
			topPostsData() {
				let pages = [];
				let number = 0;
				if ( this.overview.toppages ) {
					this.overview.toppages.forEach( function( page ) {
						number++;
						let text = page.hostname ? '<a href="' + page.hostname + page.url + '" target="_blank" rel="noreferrer noopener">' + page.title + '</a>' : page.title;
						pages.push( {
							number: number + '.',
							text: text,
							right: page.sessions,
						} );
					} );
				}
				return pages;
			},
		},
		data() {
			return {
				text_overview: __( 'Overview Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_sessions: __( 'Sessions', process.env.VUE_APP_TEXTDOMAIN ),
				text_sessions_tooltip: sprintf( __( 'Unique %s Sessions', process.env.VUE_APP_TEXTDOMAIN ), '<br />' ),
				text_pageviews: __( 'Pageviews', process.env.VUE_APP_TEXTDOMAIN ),
				text_pageviews_tooltip: sprintf( __( 'Unique %s Pageviews', process.env.VUE_APP_TEXTDOMAIN ), '<br />' ),
				text_duration: __( 'Avg. Session Duration', process.env.VUE_APP_TEXTDOMAIN ),
				text_bounce: __( 'Bounce Rate', process.env.VUE_APP_TEXTDOMAIN ),
				text_new_vs_returning: __( 'New vs. Returning Visitors', process.env.VUE_APP_TEXTDOMAIN ),
				text_device_breakdown: __( 'Device Breakdown', process.env.VUE_APP_TEXTDOMAIN ),
				text_countries: __( 'Top 10 Countries', process.env.VUE_APP_TEXTDOMAIN ),
				text_countries_button: __( 'View Countries Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_referrals: __( 'Top 10 Referrals', process.env.VUE_APP_TEXTDOMAIN ),
				text_referral_button: __( 'View All Referral Sources', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_posts: __( 'Top Posts/Pages', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_posts_button: __( 'View Full Posts/Pages Report', process.env.VUE_APP_TEXTDOMAIN ),
				current_tab: 'sessions',
				text_infobox_tooltip_sessions: __( 'A session is the browsing session of a single user to your site.', process.env.VUE_APP_TEXTDOMAIN ),
				text_infobox_tooltip_pageviews: __( 'A pageview is defined as a view of a page on your site that is being tracked by the Analytics tracking code. Each refresh of a page is also a new pageview.', process.env.VUE_APP_TEXTDOMAIN ),
				text_infobox_tooltip_average: __( 'Total duration of all sessions (in seconds) / number of sessions.', process.env.VUE_APP_TEXTDOMAIN ),
				text_infobox_tooltip_bounce: __( 'Percentage of single-page visits (or web sessions). It is the number of visits in which a person leaves your website from the landing page without browsing any further.', process.env.VUE_APP_TEXTDOMAIN ),
				text_pie_tooltip_newvsreturning: __( 'This graph shows what percent of your user sessions come from new versus repeat visitors.', process.env.VUE_APP_TEXTDOMAIN ),
				text_pie_tooltip_devices: __( 'This graph shows what percent of your visitor sessions are done using a traditional computer or laptop, tablet or mobile device to view your site.', process.env.VUE_APP_TEXTDOMAIN ),
				text_countries_tooltip: __( 'This list shows the top countries your website visitors are from.', process.env.VUE_APP_TEXTDOMAIN ),
				text_referral_tooltip: __( 'This list shows the top websites that send your website traffic, known as referral traffic.', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_posts_tooltip: __( 'This list shows the most viewed posts and pages on your website.', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		methods: {
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
		},
		mounted() {
			this.$store.dispatch( '$_reports/getReportData', 'overview' );
		},
	};
</script>
