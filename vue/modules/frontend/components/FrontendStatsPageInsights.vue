<template>
	<div class="monsterinsights-frontend-stats-inner">
		<frontend-stats-column :label="text_insights_for" :value="page_title"
			extra-class="monsterinsights-stats-column-title"
		/>
		<frontend-stats-column :label="text_page_views" :value="displayData.pageviews" />
		<frontend-stats-column :label="text_time_on_page" :value="displayData.timeonpage" />
		<frontend-stats-column :label="text_bounce_rate" :value="displayData.bouncerate" />
		<frontend-stats-column :label="text_entrances" :value="displayData.entrances" />
		<frontend-stats-column :label="text_exits" :value="displayData.exits" />
	</div>
</template>
<script>
	import FrontendStatsColumn from "./FrontendStatsColumn";
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';

	export default {
		name: 'FrontendStatsPageInsights',
		components: { FrontendStatsColumn },
		data() {
			return {
				text_insights_for: __( 'Last 30 Days Insights for:', process.env.VUE_APP_TEXTDOMAIN ),
				text_bounce_rate: __( 'Bounce Rate', process.env.VUE_APP_TEXTDOMAIN ),
				text_entrances: __( 'Entrances', process.env.VUE_APP_TEXTDOMAIN ),
				text_page_views: __( 'Page Views', process.env.VUE_APP_TEXTDOMAIN ),
				text_time_on_page: __( 'Time on Page', process.env.VUE_APP_TEXTDOMAIN ),
				text_page_load_time: __( 'Page Load Time', process.env.VUE_APP_TEXTDOMAIN ),
				text_exits: __( 'Exits', process.env.VUE_APP_TEXTDOMAIN ),
				text_30days: __( 'Last 30 Days', process.env.VUE_APP_TEXTDOMAIN ),
				text_yesterday: __( 'Yesterday', process.env.VUE_APP_TEXTDOMAIN ),
				interval: '30days',
				page_title: this.$mi.page_title,
			};
		},
		computed: {
			...mapGetters( {
				reportdata: '$_frontend/pageinsights',
			} ),
			displayData() {
				let displayData = {
					bouncerate: '0',
					entrances: '0',
					pageviews: '0',
					timeonpage: '0',
					pageloadtime: '0',
					exits: '0',
				};
				if ( this.reportdata[this.interval] ) {
					displayData = this.reportdata[this.interval];
				}

				return displayData;
			},
		},
		mounted() {
			if ( ! this.$mi.authed ) {
				this.$store.commit( '$_frontend/ENABLE_NOAUTH' );
				return;
			}
			if ( Object.keys(this.reportdata).length !== 0 && this.reportdata.constructor === Object ) {
				return;
			}
			this.$store.dispatch( '$_frontend/getReportData' ).then( () => {
				this.$store.commit( '$_frontend/UPDATE_LOADED', true );
			} );
		},
	};
</script>
