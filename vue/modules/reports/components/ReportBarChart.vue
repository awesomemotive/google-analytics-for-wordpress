<script>
	import { Bar, mixins } from 'vue-chartjs';

	const { reactiveProp } = mixins;

	export default {
		name: 'ReportBarChart',
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
						yAxes: [
							{
								ticks: {
									min: 0,
									max: 100,
								},
							},
						],
					},
					animation: false,
					legend: { display: false },
					tooltips: {
						enabled: false,
						yAlign: 'top',
						xAlign: 'top',
						intersect: true,
						custom: this.$miPieTooltips,
						callbacks: {
							title: function( tooltipItem, data ) {
								tooltipItem = tooltipItem[0];
								let label = data.labels[tooltipItem.index];
								let value = data.datasets[0].data[tooltipItem.index];
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
