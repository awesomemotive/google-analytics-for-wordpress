<script>
	import { Bar, mixins } from 'vue-chartjs';

	const { reactiveProp } = mixins;

	export default {
		name: 'ReportYearInReviewBarChart',
		extends: Bar,
		mixins: [ reactiveProp ],
		props: {
			tooltipid: String,
		},
		data() {
			const self = this;
			return {
				options: {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [ {
							gridLines: {
								display: false,
							},
							ticks: {
								fontSize: 14,
								fontColor: '#828282',
								fontFamily: 'Lato',
							},
						} ],
						yAxes: [ {
							ticks: {
								min: 0,
								stepSize: 25000,
							},
							gridLines: {
								drawBorder: false,
								lineWidth: 2,
								color: "#F2F2F2",
							},
						} ],
					},
					animation: false,
					legend: { display: false },
					tooltips: {
						enabled: false,
						yAlign: 'top',
						xAlign: 'top',
						intersect: false,
						custom: this.$miyearInReviewTooltips,
						callbacks: {
							title: function( tooltipItem, data ) {
								tooltipItem = tooltipItem[0];
								let label = data.labels[tooltipItem.index];
								let value = parseFloat(data.datasets[0].data[tooltipItem.index]).toLocaleString('en');
								return [ label, value, self.tooltipid ];
							},
							label: function() {
								return '';
							},
						},
					},
				},
			};
		},
		mounted() {
			this.renderChart( this.chartData, this.options );
		},
	};
</script>
