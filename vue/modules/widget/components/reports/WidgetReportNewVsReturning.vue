<template>
	<report-overview-pie-chart id="newvsreturning" :title="text_new_vs_returning" :chartData="newVsReturningData" :tooltip="text_pie_tooltip_newvsreturning" />
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import ReportOverviewPieChart from "../../../reports/components/reports-overview/ReportOverviewPieChart";

	export default {
		name: 'WidgetReportNewVsReturning',
		components: { ReportOverviewPieChart },
		data() {
			return {
				text_new_vs_returning: __( 'New vs. Returning Visitors', process.env.VUE_APP_TEXTDOMAIN ),
				text_pie_tooltip_newvsreturning: __( 'This graph shows what percent of your user sessions come from new versus repeat visitors.', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				overview: '$_reports/overview',
			} ),
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
		},
	};
</script>
