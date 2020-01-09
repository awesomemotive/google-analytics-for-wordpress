<template>
	<div v-if="chartData" class="monsterinsights-reports-pie-chart">
		<h3 v-if="title" class="monsterinsights-report-title" v-text="title"></h3>
		<settings-info-tooltip v-if="tooltip" :content="tooltip" />
		<div class="monsterinsights-reports-pie-chart-holder">
			<report-pie-chart :chart-data="chartData" class="monsterinsights-pie-chart"
				style="max-width: 200px; max-height: 200px;" :tooltipid="id"
			/>
			<ul v-if="legend" class="monsterinsights-pie-chart-legend">
				<li v-for="(value,index) in chartData.values" :key="index">
					<span :style="labelBackground(chartData.datasets[0].backgroundColor[index])"
						class="monsterinsights-pie-chart-legend-color"
					></span>
					<span class="monsterinsights-pie-chart-legend-text" v-text="chartData.labels[index]"></span>
					<span class="monsterinsights-pie-chart-legend-value">
						{{ value }}%
					</span>
				</li>
			</ul>
			<div :id="tooltipId" class="monsterinsights-pie-chart-tooltip"></div>
		</div>
	</div>
</template>
<script>
	import ReportPieChart from "../ReportPieChart";
	import SettingsInfoTooltip from "../../../settings/components/SettingsInfoTooltip";

	export default {
		name: 'ReportOverviewPieChart',
		components: { SettingsInfoTooltip, ReportPieChart },
		props: {
			chartData: [ Object, Boolean ],
			title: String,
			tooltip: String,
			legend: {
				type: Boolean,
				default: true,
			},
			id: String,
		},
		computed: {
			tooltipId() {
				return 'monsterinsights-chartjs-pie-' + this.id + '-tooltip';
			},
		},
		methods: {
			labelBackground( color ) {
				return 'background-color: ' + color + ';';
			},
		},
	};
</script>
