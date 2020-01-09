<script>
	import Chart from 'chart.js';
	import { generateChart, mixins } from 'vue-chartjs';

	Chart.defaults.LineWithLine = Chart.defaults.line;
	Chart.controllers.LineWithLine = Chart.controllers.line.extend({
		draw: function( ease ) {
			Chart.controllers.line.prototype.draw.call( this, ease );

			if ( this.chart.tooltip._active && this.chart.tooltip._active.length ) {
				var activePoint = this.chart.tooltip._active[0],
					ctx = this.chart.ctx,
					x = activePoint.tooltipPosition().x,
					topY = this.chart.scales['y-axis-0'].top,
					bottomY = this.chart.scales['y-axis-0'].bottom;

				// draw line
				ctx.save();
				ctx.beginPath();
				ctx.moveTo( x, topY );
				ctx.lineTo( x, bottomY );
				ctx.lineWidth = 1;
				ctx.strokeStyle = '#6db0e9';
				ctx.setLineDash( [ 10, 10 ] );
				ctx.stroke();
				ctx.restore();
			}
		},
	});

	const CustomLine = generateChart('custom-line', 'LineWithLine');

	const { reactiveProp } = mixins;

	export default {
		name: 'ReportOverviewLineChart',
		extends: CustomLine,
		mixins: [ reactiveProp ],
		props: {
			tooltip: String,
			customOptions: Object,
			id: String,
		},
		computed: {
			options() {
				const self = this;
				let options = {
					legend: {
						display: false,
					},
					hover: {
						intersect: true,
					},
					tooltips: {
						enabled: false,
						yAlign: 'top',
						xAlign: 'top',
						intersect: false,
						custom: this.$miOverviewTooltips,
						callbacks: {
							title: function( tooltipItem, data ) {
								tooltipItem = tooltipItem[0];
								var label = data.datasets[0].labels[tooltipItem.index];
								var value = data.datasets[0].data[tooltipItem.index];
								var change = data.datasets[0].trend[tooltipItem.index];
								return [ label, value, change, self.tooltip, self.id ];
							},
							label: function() {
								return '';
							},
						},
					},
					scales: {
						xAxes: [
							{
								spanGaps: true,
								position: 'bottom',
								gridLines: {
									show: true,
									color: '#f2f6fa',
								},
								ticks: {
									fontColor: '#7f8591',
								},
							},
						],
						yAxes: [
							{
								gridLines: {
									show: true,
									color: '#d4e2ef',
								},
								ticks: {
									fontColor: '#7f8591',
									callback: function( value ) {
										if ( value % 1 === 0 ) {
											return value;
										}
									},
								},
							},
						],
					},
					animation: false,
					responsive: true,
					maintainAspectRatio: false,
					borderWidth: 1,
				};

				for ( let key in this.customOptions ) {
					if ( this.customOptions.hasOwnProperty(key) ) {
						options[key] = this.customOptions[key];
					}
				}

				return options;
			},
			tooltipId() {
				return 'monsterinsights-chartjs-line-' + this.id + '-tooltip';
			},
		},
		mounted() {
			this.renderChart( this.chartData, this.options );
		},
	};
</script>
