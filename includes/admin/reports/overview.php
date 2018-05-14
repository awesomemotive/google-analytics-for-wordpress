<?php
/**
 * Overview Report  
 *
 * Ensures all of the reports have a uniform class with helper functions.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Reports
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_Report_Overview extends MonsterInsights_Report {

	public $title;
	public $class   = 'MonsterInsights_Report_Overview';
	public $name    = 'overview';
	public $version = '1.0.0';
	public $level   = 'lite';

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 6.0.0
	 */
	public function __construct() {
		$this->title = __( 'Overview', 'google-analytics-for-wordpress' );
		parent::__construct();
	}

	// Outputs the report.
	protected function get_report_html( $data = array() ){
		ob_start();
		if ( ! empty( $data['overviewgraph']['count'] ) && $data['overviewgraph']['count'] > 0 ) {
			?>
			<div class="monsterinsights-overview-report-overview-graph-panel panel panel-default chart-panel">
				<ul class="monsterinsights-tabbed-nav nav nav-tabs nav-justified">
				  <li role="presentation" class="monsterinsights-tabbed-nav-tab-title monsterinsights-clear active" data-tab="sessions-tab">
					<a href="#sessions-tab"><span class="monsterinsights-user-icon"></span>
						<?php echo esc_html__( 'Sessions', 'google-analytics-for-wordpress' ); ?>
					</a>
				  </li>
				  <li role="presentation" class="monsterinsights-tabbed-nav-tab-title monsterinsights-clear" data-tab="pageviews-tab">			  	
					<a href="#pageviews-tab"><span class="monsterinsights-eye-icon"> </span>
						<?php echo esc_html__( 'Page Views', 'google-analytics-for-wordpress' ); ?>
					</a>
				</ul>

				<div class="monsterinsights-tabbed-nav-panel sessions-tab">
				  <div class="panel-body">
					<?php echo $this->get_overview_report_js( 'sessions', $data['overviewgraph'] ); ?>
				  </div>
				</div>
				<div class="monsterinsights-tabbed-nav-panel pageviews-tab">
				  <div class="panel-body">
					<?php echo $this->get_overview_report_js( 'pageviews', $data['overviewgraph'] ); ?>
				  </div>
				</div>
			<div id="monsterinsights-chartjs-tooltip" style="opacity: 0"></div>
			</div>
		<?php }

		if ( ! empty( $data['infobox'] ) ) {
			$up         = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/up.png';
			$up2x       = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/up@2x.png';
			$down       = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/down.png';
			$down2x     = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/down@2x.png';
			$upred      = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/up-red.png';
			$upred2x    = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/up-red@2x.png';
			$downgrn    = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/down-green.png';
			$downgrn2x  = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/down-green@2x.png';
			$uplabel    = esc_attr__( 'Up', 'google-analytics-for-wordpress' );
			$downlabel  = esc_attr__( 'Down', 'google-analytics-for-wordpress' );
			?>
			<div class="monsterinsights-overview-report-infobox-panel panel row container-fluid">
			  <div class="monsterinsights-reports-infobox col-md-3 col-xs-6">
				<div class="monsterinsights-reports-infobox-title">
					<?php echo esc_html__( 'Sessions', 'google-analytics-for-wordpress' ); ?>
				</div>
				<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Session', 'google-analytics-for-wordpress' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'A session is the browsing session of a single user to your site.', 'google-analytics-for-wordpress' ) ); ?>"></div>
				<div class="monsterinsights-reports-infobox-number">
					<?php echo esc_html( number_format_i18n( $data['infobox']['sessions']['value'] ) ); ?>
				</div>
				<?php if ( empty( $data['infobox']['sessions']['prev'] ) ) { ?>
				<div class="monsterinsights-reports-infobox-prev">
					<?php echo esc_html__( 'No change', 'google-analytics-for-wordpress' ); ?>
				</div>
				<?php } else if ( $data['infobox']['sessions']['prev'] > 0 ) { ?>
				<div class="monsterinsights-reports-infobox-prev">
					<img src="<?php echo $up; ?>" srcset="<?php echo $up2x; ?> 2x" alt="<?php echo $uplabel; ?>"/>
					<?php echo esc_html( $data['infobox']['sessions']['prev'] ) . '%'; ?>
				</div>
				<?php } else  { ?>
				<div class="monsterinsights-reports-infobox-prev">
					<img src="<?php echo $down; ?>" srcset="<?php echo $down2x; ?> 2x" alt="<?php echo $downlabel; ?>"/>
					<?php echo esc_html( absint( $data['infobox']['sessions']['prev'] ) ) . '%'; ?>
				</div>
				<?php } ?>
				<div class="monsterinsights-reports-infobox-compare">
					<?php echo sprintf( esc_html__( 'vs. Previous %s Days', 'google-analytics-for-wordpress' ), $data['infobox']['range'] ); ?>
				</div>
			  </div>
			  <div class="monsterinsights-reports-infobox col-md-3 col-xs-6">
				<div class="monsterinsights-reports-infobox-title">
					<?php echo esc_html__( 'Pageviews', 'google-analytics-for-wordpress' ); ?>
				</div>
				<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Pageviews', 'google-analytics-for-wordpress' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'A pageview is defined as a view of a page on your site that is being tracked by the Analytics tracking code. Each refresh of a page is also a new pageview.', 'google-analytics-for-wordpress' ) ); ?>"></div>
				<div class="monsterinsights-reports-infobox-number">
					<?php echo esc_html( number_format_i18n( $data['infobox']['pageviews']['value'] ) ); ?>
				</div>
				<?php if ( empty( $data['infobox']['pageviews']['prev'] ) ) { ?>
				<div class="monsterinsights-reports-infobox-prev">
					<?php echo esc_html__( 'No change', 'google-analytics-for-wordpress' ); ?>
				</div>
				<?php } else if ( $data['infobox']['pageviews']['prev'] > 0 ) { ?>
				<div class="monsterinsights-reports-infobox-prev">
					<img src="<?php echo $up; ?>" srcset="<?php echo $up2x; ?> 2x" alt="<?php echo $uplabel; ?>"/>
					<?php echo esc_html( $data['infobox']['pageviews']['prev'] ) . '%'; ?>
				</div>
				<?php } else  { ?>
				<div class="monsterinsights-reports-infobox-prev">
					<img src="<?php echo $down; ?>" srcset="<?php echo $down2x; ?> 2x" alt="<?php echo $downlabel; ?>"/>
					<?php echo esc_html( absint( $data['infobox']['pageviews']['prev'] ) ) . '%'; ?>
				</div>
				<?php } ?>
				<div class="monsterinsights-reports-infobox-compare">
					<?php echo sprintf( esc_html__( 'vs. Previous %s Days', 'google-analytics-for-wordpress' ), $data['infobox']['range'] ); ?>
				</div>
			  </div>
			  <div class="monsterinsights-reports-infobox col-md-3 col-xs-6">
				<div class="monsterinsights-reports-infobox-title">
					<?php echo esc_html__( 'Avg. Session Duration', 'google-analytics-for-wordpress' ); ?>
				</div>
				<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Average Session Duration', 'google-analytics-for-wordpress' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'Total duration of all sessions (in seconds) / number of sessions.', 'google-analytics-for-wordpress' ) ); ?>"></div>
				<div class="monsterinsights-reports-infobox-number">
					<?php echo esc_html( $data['infobox']['duration']['value'] ); ?>
				</div>
				<?php if ( empty( $data['infobox']['duration']['prev'] ) ) { ?>
				<div class="monsterinsights-reports-infobox-prev">
					<?php echo esc_html__( 'No change', 'google-analytics-for-wordpress' ); ?>
				</div>
				<?php } else if ( $data['infobox']['duration']['prev'] > 0 ) { ?>
				<div class="monsterinsights-reports-infobox-prev">
					<img src="<?php echo $up; ?>" srcset="<?php echo $up2x; ?> 2x" alt="<?php echo $uplabel; ?>"/>
					<?php echo esc_html( $data['infobox']['duration']['prev'] ) . '%'; ?>
				</div>
				<?php } else  { ?>
				<div class="monsterinsights-reports-infobox-prev">
					<img src="<?php echo $down; ?>" srcset="<?php echo $down2x; ?> 2x" alt="<?php echo $downlabel; ?>"/>
					<?php echo esc_html( absint(  $data['infobox']['duration']['prev'] ) ) . '%'; ?>
				</div>
				<?php } ?>
				<div class="monsterinsights-reports-infobox-compare">
					<?php echo sprintf( esc_html__( 'vs. Previous %s Days', 'google-analytics-for-wordpress' ), $data['infobox']['range'] ); ?>
				</div>
			  </div>
			  <div class="monsterinsights-reports-infobox col-md-3 col-xs-6">
				<div class="monsterinsights-reports-infobox-title">
					<?php echo esc_html__( 'Bounce Rate', 'google-analytics-for-wordpress' ); ?>
				</div>
				<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Bounce Rate', 'google-analytics-for-wordpress' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'Percentage of single page visits (or web sessions). It is the number of visits in which a person leaves your website from the landing page without browsing any further.', 'google-analytics-for-wordpress' ) ); ?>"></div>
				<div class="monsterinsights-reports-infobox-number">
					<?php echo esc_html( $data['infobox']['bounce']['value'] ) . '%'; ?>
				</div>
				<?php if ( empty( $data['infobox']['bounce']['prev'] ) ) { ?>
				<div class="monsterinsights-reports-infobox-prev">
					<?php echo esc_html__( 'No change', 'google-analytics-for-wordpress' ); ?>
				</div>
				<?php } else if ( $data['infobox']['bounce']['prev'] > 0 ) { ?>
				<div class="monsterinsights-reports-infobox-prev">
					<img src="<?php echo $upred; ?>" srcset="<?php echo $upred2x; ?> 2x" alt="<?php echo $uplabel; ?>"/>
					<?php echo esc_html( $data['infobox']['bounce']['prev'] ) . '%'; ?>
				</div>
				<?php } else  { ?>
				<div class="monsterinsights-reports-infobox-prev">
					<img src="<?php echo $downgrn; ?>" srcset="<?php echo $downgrn2x; ?> 2x" alt="<?php echo $downlabel; ?>"/>
					<?php echo esc_html( absint( $data['infobox']['bounce']['prev'] ) ) . '%'; ?>
				</div>
				<?php } ?>
				<div class="monsterinsights-reports-infobox-compare">
					<?php echo sprintf( esc_html__( 'vs. Previous %s Days', 'google-analytics-for-wordpress' ), $data['infobox']['range'] ); ?>
				</div>
			  </div>
			</div>
		<?php } ?>
		<?php if ( ! empty( $data['newvsreturn'] ) &&  ! empty( $data['devices'] ) ) { ?>
			<div class="monsterinsights-reports-2-column-container row">
			  <div class="monsterinsights-reports-2-column-item col-md-6">
				<div class="monsterinsights-reports-2-column-panel panel monsterinsights-pie-chart-panel chart-panel">
					<div class="monsterinsights-reports-panel-title">
						<?php echo esc_html__( 'New vs. Returning Visitors', 'google-analytics-for-wordpress' );?>
					</div>
					<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'New vs. Returning Visitors', 'google-analytics-for-wordpress' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'This graph shows what percent of your user sessions come from new versus repeat visitors.', 'google-analytics-for-wordpress' ) ); ?>"></div>
					<div class="monsterinsights-reports-pie-graph monsterinsights-clear">
						<div id="monsterinsights-chartjs-pie-returning-tooltip" style="opacity: 0"></div>
						<canvas id="monsterinsights-reports-returning-visitor-chart" width="200px" height="200px" style="max-width:200px;max-height:200px"></canvas>
						<script type="text/javascript">
						jQuery(document).ready(function() {
							if ( window.uorigindetected != null){

							var pieTooltips = function(tooltip) {
							  // Tooltip Element
							  var tooltipEl = jQuery('#monsterinsights-chartjs-pie-returning-tooltip');
							  if (!tooltipEl[0]) {
								jQuery('body').append('<div id="monsterinsights-chartjs-pie-returning-tooltip" style="padding:10px;"></div>');
								tooltipEl = jQuery('#monsterinsights-chartjs-pie-returning-tooltip');
							  }
							  // Hide if no tooltip
							  if (!tooltip.opacity) {
								tooltipEl.css({
								  opacity: 0
								});
								jQuery('.chartjs-wrap canvas').each(function(index, el) {
								  jQuery(el).css('cursor', 'default');
								});
								return;
							  }
							  jQuery(this._chart.canvas).css('cursor', 'pointer');

							  // Set caret Position
							  tooltipEl.removeClass('above below no-transform');
							  if (tooltip.yAlign) {
								tooltipEl.addClass(tooltip.yAlign);
							  } else {
								tooltipEl.addClass('no-transform');
							  }
						
							var label  = tooltip.title[0];
							var value  = tooltip.title[1];

							var html  = '<div class="monsterinsights-reports-overview-datagraph-tooltip-container">';
								html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-title">' + label + '</div>';
								html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-number">' + value + '%</div>';
								html += '</div>';

							tooltipEl.html(html);
							  
							  // Find Y Location on page
							  var top = 0;
							  
							  if (tooltip.yAlign) {
								var ch = 0;
								if (tooltip.caretHeight) {
								  ch = tooltip.caretHeight;
								}
								if (tooltip.yAlign == 'above') {
								  top = tooltip.y - ch - tooltip.caretPadding;
								} else {
								  top = tooltip.y + ch + tooltip.caretPadding;
								}
							  }
							  // Display, position, and set styles for font
							  tooltipEl.css({
								opacity: 1,
								width: tooltip.width ? (tooltip.width + 'px') : 'auto',
								left: tooltip.x - 50 + 'px',
								top: top - 40 +'px',
								fontFamily: tooltip._fontFamily,
								fontSize: tooltip.fontSize,
								fontStyle: tooltip._fontStyle,
								padding: tooltip.yPadding + 'px ' + tooltip.xPadding + 'px',
								'z-index': 99999,
							  });
							};

								var config = {
									type: 'doughnut',
									data: {
										datasets: [{
											data: [
												<?php echo esc_js( $data['newvsreturn']['new'] ); ?>,
												<?php echo esc_js( $data['newvsreturn']['returning'] ); ?>,
											],
											backgroundColor: [
												"#2077c4",
												"#52a7f4"
											],
										}],
										values: [<?php echo esc_js( $data['newvsreturn']['new'] ); ?>,<?php echo esc_js( $data['newvsreturn']['returning'] ); ?> ],
										labels: [
											"<?php echo esc_js( __('New', 'google-analytics-for-wordpress' ) ); ?>",
											"<?php echo esc_js( __('Returning', 'google-analytics-for-wordpress' ) ); ?>",
										]
									},
									options: {
										responsive: true,
										maintainAspectRatio: false,
										tooltips: {
											enabled: false,
											yAlign: 'top',
											xAlign: 'top',
											intersect: true,
											custom: pieTooltips,
											callbacks: {
												  title: function(tooltipItem, data) {
													  tooltipItem    = tooltipItem[0];
													  var label      = data.labels[tooltipItem.index];
													  var value      = data.datasets[0].data[tooltipItem.index];
													  return [label,value];
												  },
												  label: function(tooltipItem, data) {
													 return '';
												  }
											}
										},
										animation: false,
										legendCallback: function (chart) {
											var text = [];
											text.push('<ul class="' + chart.id + '-legend" style="list-style:none">');
											for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
												text.push('<li style="color: #23282d;font-size: 16px;font-weight: 400;"><div style="color: #23282d;width:12px;height:12px;display:inline-block;background:' + chart.data.datasets[0].backgroundColor[i] + '" />&nbsp;');
												if ( typeof(chart) != 'undefined' && typeof(chart.data) != 'undefined' && typeof(chart.data.labels) != 'undefined' && typeof(chart.data.labels[i] ) != 'undefined' ) {
													text.push(chart.data.labels[i]);
												} 

												if (  typeof(chart) != 'undefined' && typeof(chart.data) != 'undefined' && typeof(chart.data.values) != 'undefined' && typeof(chart.data.values[i] ) != 'undefined' ) {
													text.push('<span class="monsterinsights-pie-chart-legend-number">' + chart.data.values[i] + '%</span>');
												}
												text.push('</li>');
											}
											text.push('</ul>');

											return text.join('');
										},
										legend: {display: false},
									}
								};
								var overviewreturning = new Chart( document.getElementById( "monsterinsights-reports-returning-visitor-chart").getContext("2d"), config);
								jQuery(".monsterinsights-reports-pie-graph-key").html(overviewreturning.generateLegend()); 
							}
						});
						</script>
					</div>
					<div class="monsterinsights-reports-pie-graph-key"></div>
				</div>
			</div>
			<div class="monsterinsights-reports-2-column-item col-md-6">
				<div class="monsterinsights-reports-2-column-panel panel monsterinsights-pie-chart-panel chart-panel">
					<div class="monsterinsights-reports-panel-title">
						<?php echo esc_html__( 'Device Breakdown', 'google-analytics-for-wordpress' );?>
					</div>
					<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Device Breakdown', 'google-analytics-for-wordpress' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'This graph shows what percent of your visitor sessions are done using a traditional computer or laptop, tablet or mobile device to view your site.', 'google-analytics-for-wordpress' ) ); ?>"></div>
					<div class="monsterinsights-reports-pie-graph monsterinsights-clear">
						<div id="monsterinsights-chartjs-pie-devices-tooltip" style="opacity: 0"></div>
						<canvas id="monsterinsights-reports-devices-chart" width="200px" height="200px" style="max-width:200px;max-height:200px"></canvas>
						<script type="text/javascript">
						jQuery(document).ready(function() {
							if ( window.uorigindetected != null){

							var pieTooltips = function(tooltip) {
							  // Tooltip Element
							  var tooltipEl = jQuery('#monsterinsights-chartjs-pie-devices-tooltip');
							  if (!tooltipEl[0]) {
								jQuery('body').append('<div id="monsterinsights-chartjs-pie-devices-tooltip" style="padding:10px;"></div>');
								tooltipEl = jQuery('#monsterinsights-chartjs-pie-devices-tooltip');
							  }
							  // Hide if no tooltip
							  if (!tooltip.opacity) {
								tooltipEl.css({
								  opacity: 0
								});
								jQuery('.chartjs-wrap canvas').each(function(index, el) {
								  jQuery(el).css('cursor', 'default');
								});
								return;
							  }
							  jQuery(this._chart.canvas).css('cursor', 'pointer');

							  // Set caret Position
							  tooltipEl.removeClass('above below no-transform');
							  if (tooltip.yAlign) {
								tooltipEl.addClass(tooltip.yAlign);
							  } else {
								tooltipEl.addClass('no-transform');
							  }
						
							var label  = tooltip.title[0];
							var value  = tooltip.title[1];

							var html  = '<div class="monsterinsights-reports-overview-datagraph-tooltip-container">';
								html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-title">' + label + '</div>';
								html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-number">' + value + '%</div>';
								html += '</div>';

							tooltipEl.html(html);
							  
							  // Find Y Location on page
							  var top = 0;
							  
							  if (tooltip.yAlign) {
								var ch = 0;
								if (tooltip.caretHeight) {
								  ch = tooltip.caretHeight;
								}
								if (tooltip.yAlign == 'above') {
								  top = tooltip.y - ch - tooltip.caretPadding;
								} else {
								  top = tooltip.y + ch + tooltip.caretPadding;
								}
							  }
							  // Display, position, and set styles for font
							  tooltipEl.css({
								opacity: 1,
								width: tooltip.width ? (tooltip.width + 'px') : 'auto',
								left: tooltip.x - 50 + 'px',
								top: top - 40 +'px',
								fontFamily: tooltip._fontFamily,
								fontSize: tooltip.fontSize,
								fontStyle: tooltip._fontStyle,
								padding: tooltip.yPadding + 'px ' + tooltip.xPadding + 'px',
								'z-index': 99999,
							  });
							};

								var config = {
									type: 'doughnut',
									data: {
										datasets: [{
											data: [
												<?php echo esc_js( $data['devices']['desktop'] ); ?>,
												<?php echo esc_js( $data['devices']['tablet'] ); ?>,
												<?php echo esc_js( $data['devices']['mobile'] ); ?>,
											],
											backgroundColor: [
												"#2077c4",
												"#52a7f4",
												"#afd9ff"
											],
										}],
										values: [<?php echo esc_js( $data['devices']['desktop'] ); ?>,<?php echo esc_js( $data['devices']['tablet'] ); ?>,<?php echo esc_js( $data['devices']['mobile'] ); ?>  ],
										labels: [
											"<?php echo esc_js( __('Desktop', 'google-analytics-for-wordpress' ) ); ?>",
											"<?php echo esc_js( __('Tablet', 'google-analytics-for-wordpress' ) ); ?>",
											"<?php echo esc_js( __('Mobile', 'google-analytics-for-wordpress' ) ); ?>",
										]
									},
									options: {
										responsive: true,
										maintainAspectRatio: false,
										tooltips: {
											enabled: false,
											yAlign: 'top',
											xAlign: 'top',
											intersect: true,
											custom: pieTooltips,
											callbacks: {
												  title: function(tooltipItem, data) {
													  tooltipItem    = tooltipItem[0];
													  var label      = data.labels[tooltipItem.index];
													  var value      = data.datasets[0].data[tooltipItem.index];
													  return [label,value];
												  },
												  label: function(tooltipItem, data) {
													 return '';
												  }
											}
										},
										animation: false,
										legendCallback: function (chart) {
											var text = [];
											text.push('<ul class="' + chart.id + '-legend" style="list-style:none">');
											for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
												text.push('<li style="color: #23282d;font-size: 16px;font-weight: 400;"><div style="color: #23282d;width:12px;height:12px;display:inline-block;background:' + chart.data.datasets[0].backgroundColor[i] + '" />&nbsp;');
												if ( typeof(chart) != 'undefined' && typeof(chart.data) != 'undefined' && typeof(chart.data.labels) != 'undefined' && typeof(chart.data.labels[i] ) != 'undefined' ) {
													text.push(chart.data.labels[i]);
												} 

												if (  typeof(chart) != 'undefined' && typeof(chart.data) != 'undefined' && typeof(chart.data.values) != 'undefined' && typeof(chart.data.values[i] ) != 'undefined' ) {
													text.push('<span class="monsterinsights-pie-chart-legend-number">' + chart.data.values[i] + '%</span>');
												}
												text.push('</li>');
											}
											text.push('</ul>');

											return text.join('');
										},
										legend: {display: false},
									}
								};
								var devicebreakdown = new Chart( document.getElementById( "monsterinsights-reports-devices-chart").getContext("2d"), config);
								jQuery(".monsterinsights-reports-pie-visitors-graph-key").html(devicebreakdown.generateLegend()); 
							}
						});
						</script>
					</div>
					<div class="monsterinsights-reports-pie-visitors-graph-key"></div>
				</div>
			  </div>
			</div>
		<?php } ?>

		<?php if ( ! empty( $data['countries'] ) &&  ! empty( $data['referrals'] ) ) { ?>
			<div class="monsterinsights-reports-2-column-container row">
			  <div class="monsterinsights-reports-2-column-item col-md-6 list-has-icons">
				<div class="monsterinsights-reports-2-column-panel panel nopadding">
					<div class="monsterinsights-reports-panel-title">
						<?php echo esc_html__( 'Top 10 Countries', 'google-analytics-for-wordpress' );?>
					</div>
					<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Top 10 Countries', 'google-analytics-for-wordpress' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'This list shows the top countries your website visitors are from.', 'google-analytics-for-wordpress' ) ); ?>"></div>
					<div class="monsterinsights-reports-list">
						<ul class="monsterinsights-reports-country-list list-group">
						<?php 
						$countries = monsterinsights_get_country_list( true );
						$i = 1;
						foreach( $data['countries'] as $icountry => $countrydata ) {
							if ( ! empty( $countries[ $countrydata['iso'] ] ) ) {
								echo '<li class="list-group-item"><span class="monsterinsights-reports-list-count">'. $i .'.</span><span class="monsterinsights-reports-country-flag monsterinsights-flag-icon monsterinsights-flag-icon-' . strtolower( $countrydata['iso'] ) . ' "></span><span class="monsterinsights-reports-list-text">' . $countries[ $countrydata['iso'] ] . '</span><span class="monsterinsights-reports-list-number">' . number_format_i18n( $countrydata['sessions'] ) . '</span></li>';
							} else { 
								echo '<li class="list-group-item"><span class="monsterinsights-reports-list-count">'. $i .'</span><span class="monsterinsights-flag-icon monsterinsights-flag-icon-' . strtolower( $countrydata['iso'] ) . ' "></span><span class="monsterinsights-reports-list-text">' . $countrydata['iso'] . '</span><span class="monsterinsights-reports-list-number">' . number_format_i18n( $countrydata['sessions'] ) . '</span></li>';
							}
							$i++;
						}
						?>
						</ul>
					</div>
					<?php 
					$referral_url = 'https://analytics.google.com/analytics/web/#report/visitors-geo/'. MonsterInsights()->auth->get_referral_url() . $this->get_ga_report_range( $data );
					?>
					<div class="monsterinsights-reports-panel-footer">
						<a href="<?php echo $referral_url; ?>" target="_blank"  title="<?php echo esc_html__( 'View Full Countries Report', 'google-analytics-for-wordpress' );?>" class="monsterinsights-reports-panel-footer-button"><?php echo esc_html__( 'View All Countries Report', 'google-analytics-for-wordpress' );?></a>
					</div>
				</div>
			  </div>
			  <div class="monsterinsights-reports-2-column-item col-md-6 list-has-icons">
				<div class="monsterinsights-reports-2-column-panel panel nopadding">
					<div class="monsterinsights-reports-panel-title">
						<?php echo esc_html__( 'Referrals', 'google-analytics-for-wordpress' );?>
					</div>
					<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Referrals', 'google-analytics-for-wordpress' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'This list shows the top websites that send your website traffic, known as "referral traffic".', 'google-analytics-for-wordpress' ) ); ?>"></div>
					<div class="monsterinsights-reports-list">
						<ul class="monsterinsights-reports-referral-list list-group">
						<?php
						$i = 1;
						foreach( $data['referrals'] as $ireferrals => $referralsdata ) {
								echo '<li class="list-group-item">'.
										'<span class="monsterinsights-reports-list-count">'
											. $i .
										'</span>'.
										'<img class="monsterinsights-reports-referral-icon"  src="https://www.google.com/s2/favicons?domain=' . $referralsdata['url'] . '" width="16px" height="16px" />'.
										'<span class="monsterinsights-reports-list-text">' 
											. $referralsdata['url'] . 
										'</span>
										<span class="monsterinsights-reports-list-number">'
											. number_format_i18n( $referralsdata['sessions'] ) . 
										'</span>'.
									'</li>';
							$i++;
						}
						?>
						</ul>
					</div>
					<?php 
					$referral_url = 'https://analytics.google.com/analytics/web/#report/trafficsources-referrals/'. MonsterInsights()->auth->get_referral_url() . $this->get_ga_report_range( $data );
					?>
					<div class="monsterinsights-reports-panel-footer">
						<a href="<?php echo $referral_url; ?>" target="_blank" title="<?php echo esc_html__( 'View All Referral Sources', 'google-analytics-for-wordpress' );?>" class="monsterinsights-reports-panel-footer-button"><?php echo esc_html__( 'View All Referral Sources', 'google-analytics-for-wordpress' );?></a>
					</div>
				</div>
			  </div>
			</div>
		<?php } ?>

		<?php if ( ! empty( $data['toppages'] ) ) { ?>
			<div class="monsterinsights-reports-1-column-row panel row container-fluid nopadding list-no-icons" style="position: relative;">
				<div class="monsterinsights-reports-panel-title">
					<?php echo esc_html__( 'Top Posts / Pages', 'google-analytics-for-wordpress' );?>
				</div>
				
				<div class="monsterinsights-reports-uright-tooltip" data-tooltip-title="<?php echo esc_attr( __( 'Top Posts / Pages', 'google-analytics-for-wordpress' ) ); ?>" data-tooltip-description="<?php echo esc_attr( __( 'This list shows the most viewed posts and pages on your website.', 'google-analytics-for-wordpress' ) ); ?>"></div>
				<div id="monsterinsights-report-top-page-list" class="monsterinsights-reports-list">
					<ul class="monsterinsights-reports-pages-list list-group">
						<?php
						$i = 1;
						foreach( $data['toppages'] as $itoppages => $toppagesdata ) {
								$hide     = $i > 10 ? ' style="display: none;" ': '';
								$protocol = is_ssl() ? 'https://' : 'http://';
								$opening  = ! empty( $toppagesdata['url'] ) && ! empty( $toppagesdata['hostname'] ) ? '<a href="' . $protocol . esc_attr( $toppagesdata['hostname'] . $toppagesdata['url'] ) .'" target="_blank">' : '';
								$closing = ! empty( $opening ) ? '</a>' : '';
								echo '<li class="list-group-item  monsterinsights-listing-table-row"'. $hide . '>'.
										'<span class="monsterinsights-reports-list-count">'
											. $i .
										'. </span>&nbsp;'.
										'<span class="monsterinsights-reports-list-text">' 
											. $opening
											. $toppagesdata['title']
											. $closing .
										'</span>'.
										'<span class="monsterinsights-reports-list-number">' . 
											number_format_i18n( $toppagesdata['sessions'] ) . 
										'</span>'.
									'</li>';
							$i++;
						}
						?>
					</ul>
				</div>
				<?php 
				$referral_url = 'https://analytics.google.com/analytics/web/#report/content-pages/'. MonsterInsights()->auth->get_referral_url() . $this->get_ga_report_range( $data );
				?>
				<div class="monsterinsights-reports-panel-footer monsterinsights-reports-panel-footer-large">
					<?php echo esc_html__( 'Show', 'google-analytics-for-wordpress' );?>&nbsp;
					<div class="monsterinsights-reports-show-selector-group btn-group" role="group" aria-label="<?php echo esc_html__( 'How many to show', 'google-analytics-for-wordpress' );?>">
						 <button type="button" data-tid="monsterinsights-report-top-page-list" class="monsterinsights-reports-show-selector-button ten btn btn-default active" disabled="disabled">10</button>
						 <button type="button" data-tid="monsterinsights-report-top-page-list" class="monsterinsights-reports-show-selector-button twentyfive btn btn-default">25</button>
						 <button type="button" data-tid="monsterinsights-report-top-page-list" class="monsterinsights-reports-show-selector-button fifty btn btn-default">50</button>
					</div>
					<a href="<?php echo $referral_url; ?>" target="_blank" title="<?php echo esc_html__( 'View Full Post/Page Report', 'google-analytics-for-wordpress' );?>" class="monsterinsights-reports-panel-footer-button alignright" style="margin-right: 20px;"><?php echo esc_html__( 'View Full Post/Page Report', 'google-analytics-for-wordpress' );?></a>
				</div>
			</div>
		<?php } ?>
		<?php
		$html = ob_get_clean();
		return $html;
	}

	public function get_overview_report_js( $class = 'sessions', $data ) {
		$up         = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/up.png';
		$up2x       = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/up@2x.png';
		$down       = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/down.png';
		$down2x     = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/down@2x.png';
		$uplabel    = esc_attr__( 'UP', 'google-analytics-for-wordpress' );
		$downlabel  = esc_attr__( 'DOWN', 'google-analytics-for-wordpress' );
		$descriptor = $class === 'sessions' ? esc_js( __( 'Unique', 'google-analytics-for-wordpress' ) ) . '<br />' . esc_js( __( 'Sessions', 'google-analytics-for-wordpress' ) ) : esc_js( __( 'Unique', 'google-analytics-for-wordpress' ) ) . '<br />' . esc_js( __( 'Page Views', 'google-analytics-for-wordpress' ) );
		$descriptor = "'" . $descriptor . "'";

		$labels     = array();
		foreach ( $data['timestamps'] as $timestamp ) {
			$labels[] = "'" . esc_js( date_i18n( 'j M', $timestamp ) ) . "'";
		}

		$datapoints = array();
		foreach ( $data[$class]['datapoints'] as $datapoint ) {
			$datapoints[] = esc_js( $datapoint );
		}

		$trendpoints = array();
		foreach ( $data[$class]['trendpoints'] as $trendpoint ) {
			$trendpoints[] = esc_js( $trendpoint );
		}


		ob_start(); ?>
		<div class="monsterinsights-reports-box-datagraph" style="position:relative;">
			<canvas id="monsterinsights-overview-<?php echo $class;?>" width="400px" height="400px"></canvas>
			<script>
				jQuery(document).ready(function() {
					if ( window.uorigindetected != null){

						Chart.defaults.LineWithLine = Chart.defaults.line;
						Chart.controllers.LineWithLine = Chart.controllers.line.extend({
						   draw: function(ease) {
							  Chart.controllers.line.prototype.draw.call(this, ease);

							  if (this.chart.tooltip._active && this.chart.tooltip._active.length) {
								 var activePoint = this.chart.tooltip._active[0],
									 ctx = this.chart.ctx,
									 x = activePoint.tooltipPosition().x,
									 topY = this.chart.scales['y-axis-0'].top,
									 bottomY = this.chart.scales['y-axis-0'].bottom;

								 // draw line
								 ctx.save();
								 ctx.beginPath();
								 ctx.moveTo(x, topY);
								 ctx.lineTo(x, bottomY);
								 ctx.lineWidth = 1;
								 ctx.strokeStyle = '#6db0e9';
								 ctx.setLineDash([10, 10]);
								 ctx.stroke();
								 ctx.restore();
							  }
						   }
						});
						
						var ctx = document.getElementById("monsterinsights-overview-<?php echo $class;?>");
						var data = {
							labels: [<?php echo implode( ', ', $labels ); ?>],
							datasets: [
								{
									lineTension: 0, // ChartJS doesn't make nice curves like in the PSD so for now leaving straight on
									borderColor: "#5fa6e7",

									backgroundColor: "rgba(	109, 176, 233, 0.2)",
									fillOpacity: 0.2,
									fillColor: "rgba(	109, 176, 233, 0.2)",

									pointRadius: 4,
									pointBorderColor : "#3783c4",
									pointBackgroundColor : "#FFF",

									
									hoverRadius: 1,
									
									pointHoverBackgroundColor: "#FFF",// Point background color when hovered.
									pointHoverBorderColor: "#3783c4",//Point border color when hovered.
									pointHoverBorderWidth: 4,//Border width of point when hovered.
									pointHoverRadius: 6,//The radius of the point when hovered.


									labels: [<?php echo implode( ', ', $labels );   ?>],
									data: [<?php echo implode( ', ', $datapoints );   ?>],
									trend: [<?php echo implode( ', ', $trendpoints ); ?>],
								},
							]
						};

						var overviewTooltips = function(tooltip) {
						  // Tooltip Element
						  var tooltipEl = jQuery('#monsterinsights-chartjs-tooltip');
						  if (!tooltipEl[0]) {
							jQuery('body').append('<div id="monsterinsights-chartjs-tooltip" style="padding:10px;"></div>');
							tooltipEl = jQuery('#monsterinsights-chartjs-tooltip');
						  }
						  // Hide if no tooltip
						  if (!tooltip.opacity) {
							tooltipEl.css({
							  opacity: 0
							});
							jQuery('.chartjs-wrap canvas').each(function(index, el) {
							  jQuery(el).css('cursor', 'default');
							});
							return;
						  }
						  jQuery(this._chart.canvas).css('cursor', 'pointer');

						  // Set caret Position
						  tooltipEl.removeClass('above below no-transform');
						  if (tooltip.yAlign) {
							tooltipEl.addClass(tooltip.yAlign);
						  } else {
							tooltipEl.addClass('no-transform');
						  }
					
						var label  = tooltip.title[0];
						var value  = tooltip.title[1];
						var change = tooltip.title[2];
						var trend  = '';
						if ( change == 0 ) {
							trend += '0%';
						} else if ( change > 0 ) {
							trend += '<img src="<?php echo $up; ?>" srcset="<?php echo $up2x; ?> 2x" alt="<?php echo $uplabel; ?>" style="margin-right:6px"/>' + change + '%';
						} else {
							trend += '<img src="<?php echo $down; ?>" srcset="<?php echo $down2x; ?> 2x" alt="<?php echo $downlabel; ?>" style="margin-right:6px"/>' + Math.abs( change ) + '%';
						}

						var html  = '<div class="monsterinsights-reports-overview-datagraph-tooltip-container">';
							html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-title">' + label + '</div>';
							html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-number">' + value + '</div>';
							html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-descriptor">' + <?php echo $descriptor; ?> + '</div>';
							html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-trend">' + trend + '</div>';
							html += '</div>';

						tooltipEl.html(html);
						  
						  // Find Y Location on page
						  var top = 0;
						  
						  if (tooltip.yAlign) {
							var ch = 0;
							if (tooltip.caretHeight) {
							  ch = tooltip.caretHeight;
							}
							if (tooltip.yAlign == 'above') {
							  top = tooltip.y - ch - tooltip.caretPadding;
							} else {
							  top = tooltip.y + ch + tooltip.caretPadding;
							}
						  }
						  // Display, position, and set styles for font
						  tooltipEl.css({
							opacity: 1,
							width: tooltip.width ? (tooltip.width + 'px') : 'auto',
							left: tooltip.x - 50 + 'px',
							top: top + 68 +'px',
							fontFamily: tooltip._fontFamily,
							fontSize: tooltip.fontSize,
							fontStyle: tooltip._fontStyle,
							padding: tooltip.yPadding + 'px ' + tooltip.xPadding + 'px',
							'z-index': 99999,
						  });
						};

						var MonsterInsightsOverview<?php echo time();?> = new Chart(ctx, {
							type: 'LineWithLine',
							data: data,
							plugins: [{
								afterRender: function () {
									var parent = jQuery(".monsterinsights-overview-report-overview-graph-panel .monsterinsights-tabbed-nav-tab-title[data-tab='pageviews-tab']").hasClass('active');
									if ( ! parent && '<?php echo $class; ?>' == 'pageviews' ) {
										jQuery(".monsterinsights-tabbed-nav-panel.pageviews-tab").hide();
									}
								},
							  }],
							options: {
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
									custom: overviewTooltips,
									callbacks: {
										  title: function(tooltipItem, data) {
											  tooltipItem    = tooltipItem[0];
											  var label      = data.datasets[0].labels[tooltipItem.index];
											  var value      = data.datasets[0].data[tooltipItem.index];
											  var change     = data.datasets[0].trend[tooltipItem.index];
											  return [label,value,change];
										  },
										  label: function(tooltipItem, data) {
											 return '';
										  }
									}
								},
								scales: {
									xAxes: [{
										spanGaps: true,
										position: 'bottom',
										gridLines: {
											show: true,
											color: "#f2f6fa",
										},
										ticks: {
											fontColor: "#7f8591",
										}
									}],
									yAxes: [{
										gridLines: {
											show: true,
											color: "#d4e2ef",
										},
										ticks: {
											fontColor: "#7f8591",
											callback: function(value) {if (value % 1 === 0) {return value;}}
										}
									}]
								},
								animation: false,
								legend : {
									display: false,
								},
								responsive: true,
								maintainAspectRatio: false,
								borderWidth: 1,
							}
						});
					}
				});
			</script>
		</div>
		<?php
		$html = ob_get_clean();
		return $html;
	}
}
