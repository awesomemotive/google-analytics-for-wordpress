<?php
/**
 * Class that handles the output for the Age Breakdown graph.
 *
 * Class MonsterInsights_SiteInsights_Template_Graph_Age
 */
class MonsterInsights_SiteInsights_Template_Graph_Age extends MonsterInsights_SiteInsights_Metric_Template {

	protected $metric = 'age';

	protected $type = 'graph';

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
		if (empty($this->data['age'])) {
			return false;
		}

		$primaryColor = $this->attributes['primaryColor'];
		$secondaryColor = $this->attributes['secondaryColor'];
		$textColor = $this->attributes['textColor'];

		$data = $this->data['age'];

		$title = __( 'Age Breakdown', 'google-analytics-for-wordpress' );

		$series = array();
		$percentages = array();
		$labels = array();

		foreach ($data as $key => $country) {
			$series[$key] = (int) $country['sessions'];
			$labels[$key] = $country['age'];
			$percentages[$key] = $country['percent'];
		}

		$options = array(
			'series' => array(
				array(
					'name' => $title,
					'data' => $series,
				)
			),
			'chart' => array(
				'height' => 430,
				'type' => 'bar',
				'zoom' => array( 'enabled' => false ),
				'toolbar' => array( 'show' => false )
			),
			'colors' => array( $primaryColor, $secondaryColor ),
			'title' => array(
				'text' => $title,
				'align' => 'left',
				'style' => array(
					'color' => $textColor
				)
			),
			'plotOptions' => array(
				'bar' => array(
					'borderRadius' => 5,
					'borderRadiusApplication' => 'end',
					'dataLabels' => array(
						'position' => 'top'
					)
				)
			),
			'dataLabels' => array(
				'enabled' => true,
				'offsetY' => -20,
				'style' => array(
					'fontSize' => '12px',
					'colors' => array( $primaryColor )
				),
			),
			'xaxis' => array(
				'categories' => $labels
			)
		);

		return $options;
	}
}