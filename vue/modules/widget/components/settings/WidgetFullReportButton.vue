<template>
	<a :href="getFullReportLink" class="monsterinsights-button monsterinsights-button-full-report" v-text="text_see_report"></a>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';

	export default {
		name: 'WidgetFullReportButton',
		data() {
			return {
				text_see_report: __( 'See Full Report', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				activeReport: '$_reports/activeReport',
			} ),
			getFullReportLink() {
				let reportSlug = this.activeReport;
				if ( 'publisher' === reportSlug ) {
					reportSlug += 's';
				}
				return this.$mi.reports_url + '#/' + reportSlug;
			},
		},
	};
</script>
