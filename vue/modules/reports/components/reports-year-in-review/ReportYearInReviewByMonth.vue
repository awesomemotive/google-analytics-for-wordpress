<template>
	<div v-if="chartData" class="monsterinsights-reports-visitorbymonth-chart">
		<div class="monsterinsights-reports-visitorbymonth-header">
			<div class="monsterinsights-reports-visitorbymonth-title">
				<h3 class="monsterinsights-report-title" v-text="title"></h3>
			</div>
			<div class="monsterinsights-reports-visitorbymonth-subtitle">
				<h4 class="monsterinsights-report-subtitle" v-text="subTitle"></h4>
			</div>
		</div>
		<div class="monsterinsights-reports-visitorbymonth-chart-content">
			<div class="monsterinsights-reports-bar-chart-holder">
				<div :id="tooltipId" class="monsterinsights-chart-tooltip"></div>
				<report-year-in-review-bar-chart :chart-data="chartData" :tooltipid="id" style="height:200px" />
			</div>
		</div>
	</div>
</template>
<script>
	import ReportYearInReviewBarChart from "./ReportYearInReviewBarChart";
	export default {
		name: 'ReportYearInReviewByMonth',
		components: { ReportYearInReviewBarChart },
		props: {
			chartData: [ Object, Boolean ],
			title: String,
			subTitle: String,
			id: String,
		},
		computed: {
			tooltipId() {
				return 'monsterinsights-chartjs-pie-' + this.id + '-tooltip';
			},
		},
	};
</script>
