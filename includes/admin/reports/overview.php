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

	public $report_name;

	public $report_hook;

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 6.0.0
	 */
	public function __construct() {
		$this->report_name = __( 'Overview', 'google-analytics-for-wordpress' );
		$this->report_hook = 'overview';

		parent::__construct();
	}

	// Adds/Refreshes the data
	public function add_report_data( $client, $id ){
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			return false;
		}

		$dates = $this->get_date_range();

		// Pageviews
		$pageviews = $client->do_request( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $id . '&start-date=' . $dates['start']  . '&end-date=' . $dates['end']  . '&metrics=ga%3Apageviews&dimensions=ga%3Adate&max-results=' . $this->get_api_max_limit() );
		$pageviews = $this->parse_request( $pageviews );
		update_option( 'monsterinsights_report_overview_pageviews', $pageviews );
		
		// Top posts and pages
		$top_content = $client->do_request( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $id . '&start-date=' . $dates['start']  . '&end-date=' . $dates['end']  . '&metrics=ga%3Apageviews&dimensions=ga%3ApagePath&sort=-ga%3Apageviews&max-results=' . $this->get_api_max_limit() );
		$top_content = $this->parse_request( $top_content );
		update_option( 'monsterinsights_report_overview_top_content', $top_content );

		// Top sources
		$top_sources = $client->do_request( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $id . '&start-date=' . $dates['start']  . '&end-date=' . $dates['end']  . '&metrics=ga%3Apageviews&dimensions=ga%3Asource&sort=-ga%3Apageviews&max-results=' . $this->get_api_max_limit() );
		$top_sources = $this->parse_request( $top_sources );
		update_option( 'monsterinsights_report_overview_top_sources', $top_sources );

		// Top countries
		$countries = $client->do_request( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $id . '&start-date=' . $dates['start']  . '&end-date=' . $dates['end']  . '&metrics=ga%3Apageviews&dimensions=ga%3AcountryIsoCode&sort=-ga%3Apageviews&max-results=' . $this->get_api_max_limit() );
		$countries = $this->parse_request( $countries );
		update_option( 'monsterinsights_report_overview_countries', $countries );

		monsterinsights_update_option( 'cron_last_run', time() );
	}

	// Gets the data
	public function get_report_data( ){
		$data = array();
		$data['pageviews'] = get_option( 'monsterinsights_report_overview_pageviews', array() );
		$data['top-content'] = get_option( 'monsterinsights_report_overview_top_content', array() );
		$data['top-sources'] = get_option( 'monsterinsights_report_overview_top_sources', array() );
		$data['countries'] = get_option( 'monsterinsights_report_overview_countries', array() );
		return $data;
	}

	// Removes report data
	public function delete_report_data(){
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			return false;
		}

		delete_option( 'monsterinsights_report_overview_pageviews' );
		delete_option( 'monsterinsights_report_overview_top_content' );
		delete_option( 'monsterinsights_report_overview_top_sources' );
		delete_option( 'monsterinsights_report_overview_countries' );
		return true;
	}

	// Outputs the report.
	public function show_report( ){
		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			return false;
		}
		$data = $this->get_report_data();

		$pageviews = ! empty( $data['pageviews'] ) ? $data['pageviews'] : false;
		$top 	   = ! empty( $data['top-content'] ) ? $data['top-content'] : false;
		$sources   = ! empty( $data['top-sources'] ) ? $data['top-sources'] : false;
		$countries = ! empty( $data['countries'] ) ? $data['countries'] : false;
		?>
		<?php
		if ( empty( $pageviews ) && empty( $top ) && empty( $sources ) && empty( $countries ) ) {
			echo MonsterInsights()->notices->display_inline_notice( 'monsterinsights_standard_notice', '', __( 'If you\'ve just installed MonsterInsights, data may take up to 24 hours to populate here. Check back soon!','google-analytics-for-wordpress'), 'notice', false, array() );
		}

		if ( ! empty( $pageviews ) ) {
			$pageviews_labels = array();
			$pageviews_datapoints = array();
			$max = 0;
			foreach ( $pageviews['data'] as $pageviews_index => $pageviews_values ) {
				$pageviews_labels[] = "'" . esc_js( date_i18n( 'j M', strtotime( $pageviews_values[0] ) ) ) . "'"; 
				$pageviews_datapoints[] = esc_js( $pageviews_values[1] );
				if ( $max < $pageviews_values[1] ) {
					$max = $pageviews_values[1];
				}
			}

			if ( $max >= 1 ) { 
			?>
			<div class="monsterinsights-grid">
				<div class="monsterinsights-col-1-1 monsterinsights-grid-grey-bg monsterinsights-grid-border">
					<div class="monsterinsights-reports-box-title">
						<?php esc_html_e( 'PAGE VIEWS', 'google-analytics-for-wordpress' ) ?>
					</div>
					<div class="monsterinsights-reports-box-datagraph" style="position:relative;">
						<canvas id="monsterinsights-overview-pageviews" width="400px" height="400px"></canvas>
						<?php
							$up         = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/up.png';
							$up2x       = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/up@2x.png';
							$down       = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/down.png';
							$down2x     = MONSTERINSIGHTS_PLUGIN_URL . 'assets/images/down@2x.png';
							$uplabel    = esc_attr__( 'UP', 'google-analytics-for-wordpress' );
							$downlabel  = esc_attr__( 'DOWN', 'google-analytics-for-wordpress' );
							?>
						<script>
							jQuery(document).ready(function() {
								if ( window.uorigindetected != null){
									var ctx = document.getElementById("monsterinsights-overview-pageviews");
									var data = {
										labels: [<?php echo implode( ', ', $pageviews_labels ); ?>],
										datasets: [
											{
												lineTension: 0,
												backgroundColor: 'rgba(236,249,246,.5)',
												borderColor: "#47c2a5",
												fillColor : "#ecf9f6",
												pointRadius: 5,
												pointHoverRadius: 5,
												pointBorderColor : "#fff",
												pointBackgroundColor : "#489be8",
												data: [<?php echo implode( ', ', $pageviews_datapoints ); ?>],
												
											}
										]
									};

									Chart.defaults.global.tooltips.custom = function(tooltip) {
									  // Tooltip Element
									  var tooltipEl = jQuery('#monsterinsights-chartjs-tooltip');
									  if (!tooltipEl[0]) {
										jQuery('body').append('<div id="monsterinsights-chartjs-tooltip"></div>');
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
									// Set Text
									var title = '';
									if ( tooltip.title[0] == 0 ) {
										title = '0%';
									} else if ( tooltip.title[0] > 0 ) {
										title = '<img src="<?php echo $up; ?>" srcset="<?php echo $up2x; ?> 2x" alt="<?php echo $uplabel; ?>" style="margin-right:6px"/>' + tooltip.title[1];
									} else {
										title = '<img src="<?php echo $down; ?>" srcset="<?php echo $down2x; ?> 2x" alt="<?php echo $downlabel; ?>" style="margin-right:6px"/>' + Math.abs( tooltip.title[1] );
									}
									tooltipEl.html(title);
									  
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
										left: 30 + tooltip.x + 'px',
										top: top - 8 +'px',
										fontFamily: tooltip._fontFamily,
										fontSize: tooltip.fontSize,
										fontStyle: tooltip._fontStyle,
										padding: tooltip.yPadding + 'px ' + tooltip.xPadding + 'px',
									  });
									};

									var MonsterInsightsOverviewPageviews = new Chart(ctx, {
										type: 'line',
										data: data,
										options: {
											legend: {
												 display: false,
											},
											tooltips: {
												enabled: false,
												yAlign: 'above',
											  callbacks: {
												  title: function(tooltipItem, data) {
													  tooltipItem = tooltipItem[0];
													  var prevvalue = 0;
													  if ( tooltipItem.index != 0 ) {
														prevvalue = data.datasets[0].data[tooltipItem.index - 1]
													  }
													  
													  var value     = data.datasets[0].data[tooltipItem.index];
													  var change    = 0;
													  if ( prevvalue == 0 && value == 0 ) {
														change = 0;
													  } else if ( prevvalue == 0 ) {
														change = 100;
													  } else if ( value == 0 ) {
														change = -100;
													  } else {
														change = ((value - prevvalue) / prevvalue) * 100;
													  }
													  change   = Math.round( change );
													  return new Array(change, value);
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
														color: "#f1f1f1",
													},
													ticks: {
														fontColor: "#7f8591",
													}
												}],
												yAxes: [{
													gridLines: {
														show: true,
														color: "#f1f1f1",
													},
													ticks: {
														fontColor: "#7f8591",
														callback: function(value) {if (value % 1 === 0) {return value;}}
													}
												}]
											},
											animation: {
												duration: 5000,
											},
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
						<div id="monsterinsights-chartjs-tooltip"></div>
					</div>
				</div>
			</div>
			<?php }	?>
		<?php }	?>
			<?php
			if ( ! empty( $top ) && ! empty( $sources ) ) {
			?>
			<div class="monsterinsights-grid">
				<div class="monsterinsights-col-1-2">
					<div class="monsterinsights-datalist-box-title">
						<?php esc_html_e( 'TOP POSTS AND PAGES', 'google-analytics-for-wordpress' ); ?>
					</div>
					<div class="monsterinsights-grid-border">
						<div class="monsterinsights-col-1-1 monsterinsights-reports-box-title">
							<div class="monsterinsights-col-7-8">
								<?php esc_html_e( 'TITLE', 'google-analytics-for-wordpress' ); ?>
							</div>
							<div class="monsterinsights-col-1-8">
								<?php esc_html_e( 'VIEWS', 'google-analytics-for-wordpress' ); ?>
							</div>
						</div>
						<div class="monsterinsights-reports-box-datalist">
							<?php 
								$i = 0;
								foreach ( $top['data'] as $top_index => $top_values ) { ?>
								<?php
									if ( $i === 10 ) { // Limit to 10 max
										break;
									} else {
										$i++;
									}
									$title = isset( $top_values[0] ) ? esc_html( $top_values[0] ) : esc_html__( 'Unknown Page or Post', 'google-analytics-for-wordpress' );
									$views = isset( $top_values[1] ) ? absint( $top_values[1] ) : 0;
								?>
								<div class="monsterinsights-data-row">
									<div class="monsterinsights-col-7-8"><?php echo $title ?></div>
									<div class="monsterinsights-col-1-8"><?php echo $views ?></div>
								</div>
							<?php } 
								for ( $i; $i < 10; $i++ ) { // if we have less than 10, make empty rows
									?>
									<div class="monsterinsights-data-row">
										<div class="monsterinsights-col-7-8">&nbsp;</div>
										<div class="monsterinsights-col-1-8">&nbsp;</div>
									</div>
									<?php
								}
							?>
						</div>
						<div style="clear: both;"></div>
					</div>
				</div>

				<div class="monsterinsights-col-1-2">
					<div class="monsterinsights-datalist-box-title">
						<?php esc_html_e( 'TOP TRAFFIC SOURCES', 'google-analytics-for-wordpress' ) ?>
					</div>
					<div class="monsterinsights-grid-border">
						<div class="monsterinsights-col-1-1 monsterinsights-reports-box-title">
							<div class="monsterinsights-col-7-8">
								<?php esc_html_e( 'SITE', 'google-analytics-for-wordpress' ); ?>
							</div>
							<div class="monsterinsights-col-1-8">
								<?php esc_html_e( 'VIEWS', 'google-analytics-for-wordpress' ); ?>
							</div>
						</div>
						<div class="monsterinsights-reports-box-datalist">
							<?php 
								$i = 0;
								foreach ( $sources['data'] as $sources_index => $sources_values ) { ?>
								<?php
									if ( $i === 10 ) { // Limit to 10 max
										break;
									} else {
										$i++;
									}
									$title = isset( $sources_values[0] ) ? esc_html( $sources_values[0] ) : esc_html__( 'Unknown Source', 'google-analytics-for-wordpress' );
									$views = isset( $sources_values[1] ) ? absint( $sources_values[1] ) : 0;
								?>
								<div class="monsterinsights-data-row">
									<div class="monsterinsights-col-7-8"><?php echo $title ?></div>
									<div class="monsterinsights-col-1-8"><?php echo $views ?></div>
								</div>
							<?php } 
								for ( $i; $i < 10; $i++ ) { // if we have less than 10, make empty rows
									?>
									<div class="monsterinsights-data-row">
										<div class="monsterinsights-col-7-8">&nbsp;</div>
										<div class="monsterinsights-col-1-8">&nbsp;</div>
									</div>
									<?php
								}
								?>
						</div>
						<div style="clear: both;"></div>
					</div>
				</div>
			</div>
			<?php }	?>
			<?php
			if ( ! empty( $countries ) ) {
			?>
			<div class="monsterinsights-grid">
				<div class="monsterinsights-col-1-1 monsterinsights-grid-border monsterinsights-clearfix">
					<div class="monsterinsights-col-1-1 monsterinsights-reports-box-title">
						<div class="monsterinsights-col-1-1">
							<?php esc_html_e( 'TOP COUNTRIES', 'google-analytics-for-wordpress' ); ?>
						</div>
					</div>
					<div class="monsterinsights-col-1-1 monsterinsights-no-padding-right">
						<div id="monsterinsights-reports-country-map" style="height: 400px;" ></div>
						<script type="text/javascript">
							jQuery(function(){
								if ( window.uorigindetected != null) {
								  var viewname = <?php echo "' " . __( 'views', 'google-analytics-for-wordpress' ) . "'"; ?>;
								  var monsterinsights_countries = <?php echo $this->get_countries_array( $countries ) ?>;
								  jQuery('#monsterinsights-reports-country-map').vectorMap({
									map: 'world_mill',
									series: {
									  regions: [{
										values: monsterinsights_countries,
										scale: ['#FFFFFF', '#0071A4'],
										normalizeFunction: 'polynomial'
									  }]
									},
									onRegionTipShow: function(e, el, code){
									  el.html(el.html()+' ('+ monsterinsights_countries[code] + viewname +')');
									}
								  });
								}
							});
						</script>
					</div>
					<div class="monsterinsights-col-1-1 monsterinsights-reports-box-title monsterinsights-clearfix">
						<div class="monsterinsights-col-1-2">
							<?php esc_html_e( 'COUNTRY', 'google-analytics-for-wordpress' ); ?>
						</div>
						<div class="monsterinsights-col-1-4">
							<?php esc_html_e( 'VIEWS', 'google-analytics-for-wordpress' ); ?>
						</div>
						<div class="monsterinsights-col-1-4">
							<?php esc_html_e( '%', 'google-analytics-for-wordpress' ); ?>
						</div>
					</div>

					<div class="monsterinsights-reports-box-datalist">
						<?php 
							$i = 0;
							$list_of_countries = monsterinsights_get_country_list( true );
							foreach ( $countries['data'] as $countries_index => $countries_values ) { ?>
							<?php
								if ( $i === 10 ) { // Limit to 10 max
									break;
								} else {
									$i++;
								}
								$title   = isset( $countries_values[0] ) ? esc_html( $countries_values[0] ) : __( 'Country not set', 'google-analytics-for-wordpress' );
								$title   = isset( $list_of_countries[ $title ] ) ? esc_html( $list_of_countries[ $title ] )  : $title;
								$views   = isset( $countries_values[1] ) ? absint( $countries_values[1] ) : 0;
								$percent = ! empty( $countries['total']['ga:pageviews'] ) &&  absint( $countries['total']['ga:pageviews'] ) > 0 ? $views / absint( $countries['total']['ga:pageviews'] ) : 0;
								$percent = number_format( $percent * 100, 2 ) . '%';
							?>
							<div class="monsterinsights-data-row">
								<div class="monsterinsights-col-1-2"><?php echo $title ?></div>
								<div class="monsterinsights-col-1-4"><?php echo $views ?></div>
								<div class="monsterinsights-col-1-4"><?php echo $percent ?></div>
							</div>
						<?php }
							?>
					</div>
				</div>
			</div>
			<?php }	?>
		<?php
	}

	public function parse_request( $request ) {
		$to_save = array();
		if ( ! empty( $request['response'] ) && ! empty( $request['response']['code'] ) && 200 == $request['response']['code'] ){
			$dates = $this->get_date_range();
			$to_save['start-date'] = $dates['start'];
			$to_save['end-date']   = $dates['end'];
			$to_save['total'] 	   = isset( $request['body']['totalsForAllResults'] ) ? $request['body']['totalsForAllResults'] : 0 ;
			$to_save['data'] 	   = isset( $request['body']['rows'] ) ? $request['body']['rows'] : array();
		} else {
			monsterinsights_update_option( 'cron_failed', true );
		}
		return $to_save;
	}

	private function get_countries_array( $countries ) {
		$js_countries = array( 
			  "AF" => 0,
			  "AL" => 0,
			  "DZ" => 0,
			  "AO" => 0,
			  "AG" => 0,
			  "AR" => 0,
			  "AM" => 0,
			  "AU" => 0,
			  "AT" => 0,
			  "AZ" => 0,
			  "BS" => 0,
			  "BH" => 0,
			  "BD" => 0,
			  "BB" => 0,
			  "BY" => 0,
			  "BE" => 0,
			  "BZ" => 0,
			  "BJ" => 0,
			  "BT" => 0,
			  "BO" => 0,
			  "BA" => 0,
			  "BW" => 0,
			  "BR" => 0,
			  "BN" => 0,
			  "BG" => 0,
			  "BF" => 0,
			  "BI" => 0,
			  "KH" => 0,
			  "CM" => 0,
			  "CA" => 0,
			  "CV" => 0,
			  "CF" => 0,
			  "TD" => 0,
			  "CL" => 0,
			  "CN" => 0,
			  "CO" => 0,
			  "KM" => 0,
			  "CD" => 0,
			  "CG" => 0,
			  "CR" => 0,
			  "CI" => 0,
			  "HR" => 0,
			  "CY" => 0,
			  "CZ" => 0,
			  "DK" => 0,
			  "DJ" => 0,
			  "DM" => 0,
			  "DO" => 0,
			  "EC" => 0,
			  "EG" => 0,
			  "SV" => 0,
			  "GQ" => 0,
			  "ER" => 0,
			  "EE" => 0,
			  "ET" => 0,
			  "FJ" => 0,
			  "FI" => 0,
			  "FR" => 0,
			  "GA" => 0,
			  "GL" => 0,
			  "GM" => 0,
			  "GE" => 0,
			  "DE" => 0,
			  "GH" => 0,
			  "GR" => 0,
			  "GD" => 0,
			  "GT" => 0,
			  "GN" => 0,
			  "GW" => 0,
			  "GY" => 0,
			  "HT" => 0,
			  "HN" => 0,
			  "HK" => 0,
			  "HU" => 0,
			  "IS" => 0,
			  "IN" => 0,
			  "ID" => 0,
			  "IR" => 0,
			  "IQ" => 0,
			  "IE" => 0,
			  "IL" => 0,
			  "IT" => 0,
			  "JM" => 0,
			  "JP" => 0,
			  "JO" => 0,
			  "KZ" => 0,
			  "KE" => 0,
			  "KI" => 0,
			  "KR" => 0,
			  "KW" => 0,
			  "KG" => 0,
			  "LA" => 0,
			  "LV" => 0,
			  "LB" => 0,
			  "LS" => 0,
			  "LR" => 0,
			  "LY" => 0,
			  "LT" => 0,
			  "LU" => 0,
			  "MK" => 0,
			  "MG" => 0,
			  "MW" => 0,
			  "MY" => 0,
			  "MV" => 0,
			  "ML" => 0,
			  "MT" => 0,
			  "MR" => 0,
			  "MU" => 0,
			  "MX" => 0,
			  "MD" => 0,
			  "MN" => 0,
			  "ME" => 0,
			  "MA" => 0,
			  "MZ" => 0,
			  "MM" => 0,
			  "NA" => 0,
			  "NP" => 0,
			  "NL" => 0,
			  "NZ" => 0,
			  "NI" => 0,
			  "NE" => 0,
			  "NG" => 0,
			  "NO" => 0,
			  "OM" => 0,
			  "PK" => 0,
			  "PA" => 0,
			  "PG" => 0,
			  "PY" => 0,
			  "PE" => 0,
			  "PH" => 0,
			  "PL" => 0,
			  "PT" => 0,
			  "QA" => 0,
			  "RO" => 0,
			  "RU" => 0,
			  "RW" => 0,
			  "WS" => 0,
			  "ST" => 0,
			  "SA" => 0,
			  "SN" => 0,
			  "RS" => 0,
			  "SC" => 0,
			  "SL" => 0,
			  "SG" => 0,
			  "SK" => 0,
			  "SI" => 0,
			  "SB" => 0,
			  "ZA" => 0,
			  "ES" => 0,
			  "LK" => 0,
			  "KN" => 0,
			  "LC" => 0,
			  "VC" => 0,
			  "SD" => 0,
			  "SR" => 0,
			  "SZ" => 0,
			  "SE" => 0,
			  "CH" => 0,
			  "SY" => 0,
			  "TW" => 0,
			  "TJ" => 0,
			  "TZ" => 0,
			  "TH" => 0,
			  "TL" => 0,
			  "TG" => 0,
			  "TO" => 0,
			  "TT" => 0,
			  "TN" => 0,
			  "TR" => 0,
			  "TM" => 0,
			  "UG" => 0,
			  "UA" => 0,
			  "AE" => 0,
			  "GB" => 0,
			  "US" => 0,
			  "UY" => 0,
			  "UZ" => 0,
			  "VU" => 0,
			  "VE" => 0,
			  "VN" => 0,
			  "YE" => 0,
			  "ZM" => 0,
			  "ZW" => 0,
			  "KP" => 0,
			  "CU" => 0,
			  "PR" => 0,
			  "FK" => 0,
			  "SO" => 0,
			  "SS" => 0,
			  "EH" => 0,
			  "XK" => 0,
			  "XS" => 0,
			  "NC" => 0,
			  "PS" => 0,
			);

		if ( empty( $countries ) || ! is_array( $countries ) || empty( $countries['data'] ) || empty( $countries['total']['ga:pageviews'] ) ) {
			// continue
		} else {
			$list_of_countries = array_flip( monsterinsights_get_country_list() );
			foreach ( $countries['data'] as $countries_index => $countries_values ) {
				$title   = isset( $countries_values[0] ) ? esc_html( $countries_values[0] ) : false;
				if ( ! $title ) {
					continue;
				}

				if ( isset( $js_countries[ $title ] ) ) {
					$views   = isset( $countries_values[1] ) ? absint( $countries_values[1] ) : 0;
					$js_countries[ $title ] = $views;
				} else if ( isset( $list_of_countries[ $title ] ) ) {
					$views   = isset( $countries_values[1] ) ? absint( $countries_values[1] ) : 0;
					$js_countries[ $list_of_countries[ $title ] ] = $views;				
				} else {
					continue;
				}
			}
		}

		$to_return = "{" . PHP_EOL;
		foreach ( $js_countries as $country => $value ) {
			$to_return .= '"' . esc_js( $country ) . '": ' . esc_js(  $value ) . "," . PHP_EOL;
		}
		$to_return .= "}" . PHP_EOL;
		return $to_return;
	}
}
new MonsterInsights_Report_Overview();