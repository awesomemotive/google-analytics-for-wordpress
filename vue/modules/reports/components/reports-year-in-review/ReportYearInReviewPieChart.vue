<template>
	<div v-if="chartData" class="monsterinsights-reports-pie-chart monsterinsights-reports-year-in-review-pie-chart">
		<div class="monsterinsights-reports-pie-chart-holder">
			<report-pie-chart :chart-data="chartData" class="monsterinsights-pie-chart"
				style="max-width: 176px; max-height: 176px;" :tooltipid="id"
			/>
			<div :id="tooltipId" class="monsterinsights-pie-chart-tooltip"></div>
		</div>
		<div class="monsterinsights-yir-reports-pie-chart-content">
			<h3 v-if="title" class="monsterinsights-report-title" v-text="title"></h3>
			<h3 v-if="subtitle" class="monsterinsights-report-subtitle" v-html="subtitle"></h3>
			<ul v-if="legend" class="monsterinsights-pie-chart-legend">
				<li v-for="(value,index) in chartData.values" :key="index">
					<span :style="labelBackground(chartData.datasets[0].backgroundColor[index])" class="monsterinsights-pie-chart-legend-color"></span>
					<span class="monsterinsights-pie-chart-legend-text" v-text="chartData.labels[index]"></span>
					<span class="monsterinsights-pie-chart-legend-value">({{ value }}%)</span>
				</li>
			</ul>
		</div>
	</div>
</template>
<script>
	import ReportPieChart from "./ReportPieChart";

	export default {
		name: 'ReportYearInReviewPieChart',
		components: { ReportPieChart },
		props: {
			chartData: [ Object, Boolean ],
			title: String,
			subtitle: String,
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
