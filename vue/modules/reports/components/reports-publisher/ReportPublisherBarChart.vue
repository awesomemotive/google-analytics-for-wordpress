<template>
	<div v-if="chartData" class="monsterinsights-reports-pie-chart">
		<h3 class="monsterinsights-report-title" v-text="title"></h3>
		<settings-info-tooltip v-if="tooltip" :content="tooltip" />
		<div class="monsterinsights-reports-bar-chart-holder">
			<div :id="tooltipId" class="monsterinsights-pie-chart-tooltip"></div>
			<report-bar-chart :chart-data="chartData" :tooltipid="id" style="height:200px" />
		</div>
	</div>
</template>
<script>
	import SettingsInfoTooltip from "../../../settings/components/SettingsInfoTooltip";
	import ReportBarChart from "../ReportBarChart";
	export default {
		name: 'ReportPublisherBarChart',
		components: { ReportBarChart, SettingsInfoTooltip },
		props: {
			chartData: [ Object, Boolean ],
			title: String,
			tooltip: String,
			id: String,
		},
		computed: {
			tooltipId() {
				return 'monsterinsights-chartjs-pie-' + this.id + '-tooltip';
			},
		},
	};
</script>
