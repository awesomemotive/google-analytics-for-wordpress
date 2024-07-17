<?php
/**
 * Class that handles the output for the Top Interests scorecard.
 *
 * Class MonsterInsights_SiteInsights_Template_Scorecard_Topinterests
 */
class MonsterInsights_SiteInsights_Template_Scorecard_Topinterests extends MonsterInsights_SiteInsights_Metric_Template {

	protected $metric = 'topinterests';

	protected $type = 'scorecard';

	public function output(){
		$data = $this->get_options();

		if (empty($data)) {
			return false;
		}

		$content = $this->get_table_template(
			$data['headers'],
			$data['rows']
		);

		return sprintf(
			"<div class=\"monsterinsights-table-scorecard with-2-columns\">%s</div>",
			$content
		);
	}

	/**
	 * Returns data needed for this block.
	 *
	 * @return array|false
	 */
	protected function get_options() {
		if ( empty($this->data['interest'])) {
			return false;
		}

		$primaryColor = $this->attributes['primaryColor'];
		$secondaryColor = $this->attributes['secondaryColor'];

		$data = $this->data['interest'];

		$rows = array();

		foreach ($data as $key => $item) {
			$rows[$key] = array(
				$item['interest'],
				$item['percent'] . '%'
			);
		}

		return array(
			'rows' => $rows,
			'headers' => array(
				__( 'Categories', 'google-analytics-for-wordpress' ),
				__( '% of Interest', 'google-analytics-for-wordpress' )
			),
		);
	}

	private function get_table_template( $headers, $rows ) {
		$headers_output = '';
		$countries_output = '';

		foreach ( $headers as $key => $head ) {
			$headers_output .= sprintf( '<div class="monsterinsights-scorecard-table-head">%s</div>', $head );
		}

		foreach ( $rows as $key => $row ) {
			$items = '';

			foreach ( $row as $i => $column ) {
				$items .= sprintf( '<div class="monsterinsights-scorecard-table-column">%s</div>', $column );
			}

			$countries_output .= sprintf( '<div class="monsterinsights-scorecard-table-row">%s</div>', $items );
		}

		$header = sprintf(
			'<div class="monsterinsights-scorecard-table-header">%s</div>',
			$headers_output
		);

		$content = sprintf(
			'<div class="monsterinsights-scorecard-table-rows">%s</div>',
			$countries_output
		);

		$format = '<div class="monsterinsights-scorecard-table">%s</div>';

		return sprintf(
			$format,
			$header . $content
		);
	}
}