<?php
/**
 * Class that handles the output for the Device Breakdown scorecard.
 *
 * Class MonsterInsights_SiteInsights_Template_Scorecard_Device
 */
class MonsterInsights_SiteInsights_Template_Scorecard_Device extends MonsterInsights_SiteInsights_Metric_Template {

	protected $metric = 'device';

	protected $type = 'scorecard';

	public function output(){
		$json_data = $this->get_json_data();

		if (empty($json_data)) {
			return false;
		}

		return "<div class='monsterinsights-graph-item monsterinsights-graph-{$this->metric}'>
			<script type='application/json'>{$json_data}</script>
		</div>";
	}

	protected function get_options() {
		if (empty($this->data['devices'])) {
			return false;
		}

		$primaryColor = $this->attributes['primaryColor'];
		$secondaryColor = $this->attributes['secondaryColor'];
		$textColor = $this->attributes['textColor'];
		$title = __( 'Device Breakdown', 'google-analytics-for-wordpress' );

		$data = $this->data['devices'];

		$series = array_values( $data );
		$labels = array_keys( $data );

		$options = array(
			'series' => array(
				array(
					'data' => $series
				)
			),
			'title' => array(
				'text' => $title,
				'style' => array(
					'color' => $textColor,
					'fontSize' => '20px',
				)
			),
			'chart' => array(
				'type' => 'bar',
				'height' => 240,
				'width' => "90%",
				'toolbar' => array(
					'show' => false
				)
			),
			'colors' => array( $primaryColor, $secondaryColor ),
			'tooltip' => array(
				'enabled' => false
			),
			'grid' => array(
				'show' => false
			),
			'plotOptions' => array(
				'bar' => array(
					'borderRadius' => 5,
					'horizontal' => true,
					'columnWidth' => '33%',
					'dataLabels' => array(
						'position' => 'center',
					)
				)
			),
			'dataLabels' => array(
				'style' => array(
					'colors' => array( $textColor )
				),
				'formatter' => array(
					'args' => 'value',
					'body' => 'return value + "%"',
				)
			),
			'xaxis' => array(
				'show' => false,
				'categories' => $labels,
				'axisBorder' => array(
					'show' => false
				),
				'labels' => array(
					'show' => false
				),
				'axisTicks' => array(
					'show' => false
				)
			),
			'yaxis' => array(
				'show' => true,
				'labels' => array(
					'show' => true
				),
				'axisTicks' => array(
					'show' => false
				)
			),
		);

		return $options;
	}
}