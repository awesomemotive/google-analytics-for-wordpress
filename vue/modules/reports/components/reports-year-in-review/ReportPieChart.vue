<script>
	import { Doughnut, mixins } from 'vue-chartjs';

	const { reactiveProp } = mixins;

	export default {
		name: 'ReportPieChart',
		extends: Doughnut,
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
					elements: {
						arc: {
							borderWidth: 0,
						},
					},
					cutoutPercentage: 65,
					tooltips: {
						enabled: false,
						yAlign: 'top',
						xAlign: 'top',
						intersect: true,
						custom: self.$miPieTooltips,
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
					animation: false,
					legend: { display: false },
				},
			};
		},
		mounted() {
			this.renderChart( this.chartData, this.options );
		},
	};
</script>
