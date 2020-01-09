<template>
	<div v-if="overview.infobox" class="monsterinsights-frontend-stats-inner">
		<frontend-stats-column :label="text_insights_for" :value="text_your_website" />
		<frontend-stats-column :label="text_sessions" :value="overview.infobox.sessions.value" />
		<frontend-stats-column :label="text_pageviews" :value="overview.infobox.pageviews.value" />
		<frontend-stats-column :label="text_session_duration" :value="overview.infobox.duration.value" />
		<frontend-stats-column :label="text_bounce_rate" :value="overview.infobox.bounce.value" />
		<frontend-stats-column :label="text_upsell_title">
			<frontend-upsell />
		</frontend-stats-column>
	</div>
</template>
<script>
	import FrontendStatsColumn from "./FrontendStatsColumn";
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import FrontendUpsell from "./FrontendUpsell-MI_VERSION";

	export default {
		name: 'FrontendStatsGeneral',
		components: { FrontendUpsell, FrontendStatsColumn },
		data() {
			return {
				text_insights_for: __( 'Last 30 Days Insights for:', process.env.VUE_APP_TEXTDOMAIN ),
				text_your_website: __( 'Your Website', process.env.VUE_APP_TEXTDOMAIN ),
				text_sessions: __( 'Sessions', process.env.VUE_APP_TEXTDOMAIN ),
				text_pageviews: __( 'Pageviews', process.env.VUE_APP_TEXTDOMAIN ),
				text_session_duration: __( 'Avg. Duration', process.env.VUE_APP_TEXTDOMAIN ),
				text_bounce_rate: __( 'Bounce Rate', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				overview: '$_reports/overview',
			} ),
			text_upsell_title() {
				if ( this.$mi.is_admin ) {
					return __( 'More data is available', process.env.VUE_APP_TEXTDOMAIN );
				}
				return __( 'Want to see page-specific stats?', process.env.VUE_APP_TEXTDOMAIN );
			},
		},
		mounted() {
			if ( ! this.$mi.authed ) {
				this.$store.commit( '$_frontend/ENABLE_NOAUTH' );
				return;
			}
			this.$store.dispatch( '$_reports/getReportData', 'overview' ).then( () => {
				this.$store.commit( '$_frontend/UPDATE_LOADED', true );
			} );
		},
	};
</script>
