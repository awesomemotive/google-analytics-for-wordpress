<?php
/**
 * Class that handles the output for the Device graph.
 *
 * Class MonsterInsights_SiteInsights_Template_Graph_Device
 */
class MonsterInsights_SiteInsights_Template_Graph_Device extends MonsterInsights_SiteInsights_Metric_Template {

	protected $metric = 'device';

	protected $type = 'graph';

	public function output(){
		$json_data = $this->get_json_data();

		if (empty($json_data)) {
			return false;
		}

		return "<div class='monsterinsights-graph-item monsterinsights-donut-chart monsterinsights-graph-{$this->metric}'>
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
		$data = $this->data['devices'];
		$labels = array();
		$series = array_values($data);

		foreach ($data as $key => $value){
			$labels[] = ucfirst($key);
		}

		$title = __( 'Device Breakdown', 'google-analytics-for-wordpress' );

		$options = array(
			'series' => $series,
			'chart' => array(
				'width' => "100%",
				'height' => 'auto',
				'type' => 'donut',
			),
			'colors' => array( $primaryColor, $secondaryColor ),
			'title' => array(
				'text' => $title,
				'align' => 'left',
				'style' => array(
					'color' => $this->get_color_value($textColor),
					'fontSize' => '20px'
				)
			),
			'plotOptions' => array(
				'plotOptions' => array(
					'pie' => array(
						'donut' => array( 'size' => '75%' )
					)
				)
			),
			'legend' => array(
				'position' => 'right',
				'horizontalAlign' => 'center',
				'floating' => false,
				'fontSize' => '17px',
				'height' => '100%',
				'markers' => array(
					'width' => 30,
					'height' => 30,
					'radius' => 30
				),
				'formatter' => array(
					'args' => 'seriesName, opts',
					'body' => 'return [seriesName, "<strong> " + opts.w.globals.series[opts.seriesIndex] + "%</strong>"]'
				)
			),
			'dataLabels' => array(
				'enabled' => false
			),
			'labels' => $labels,
			'responsive' => array(
				0 => array(
					'breakpoint' => 767,
					'options' => array(
						'legend' => array(
							'show' => false
						)
					)
				)
			)
		);

		return $options;
	}
}