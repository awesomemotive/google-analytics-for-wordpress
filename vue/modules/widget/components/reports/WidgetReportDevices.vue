<template>
	<report-overview-pie-chart id="devices" :chartData="devicesData" :title="text_device_breakdown"
		:tooltip="text_pie_tooltip_devices"
	/>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import ReportOverviewPieChart from "../../../reports/components/reports-overview/ReportOverviewPieChart";

	export default {
		name: 'WidgetReportDevices',
		components: { ReportOverviewPieChart },
		data() {
			return {
				text_device_breakdown: __( 'Device Breakdown', process.env.VUE_APP_TEXTDOMAIN ),
				text_pie_tooltip_devices: __( 'This graph shows what percent of your visitor sessions are done using a traditional computer or laptop, tablet or mobile device to view your site.', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				overview: '$_reports/overview',
			} ),
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
		},
	};
</script>
