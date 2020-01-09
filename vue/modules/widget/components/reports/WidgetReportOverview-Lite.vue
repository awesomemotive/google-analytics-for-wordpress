<template>
	<div>
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
	import ReportInfobox from "../../../reports/components/ReportInfobox";
	import WidgetTips from "../WidgetTips";

	export default {
		name: 'WidgetReportOverview',
		components: { WidgetTips, ReportInfobox },
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
	};
</script>
