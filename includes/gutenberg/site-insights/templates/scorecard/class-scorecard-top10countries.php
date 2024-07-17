<?php
/**
 * Class that handles the output for the Top 10 countries scorecard.
 *
 * Class MonsterInsights_SiteInsights_Template_Scorecard_Top10countries
 */
class MonsterInsights_SiteInsights_Template_Scorecard_Top10countries extends MonsterInsights_SiteInsights_Metric_Template {

	protected $metric = 'top10countries';

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
		if ( empty($this->data['countries'])) {
			return false;
		}

		$primaryColor = $this->attributes['primaryColor'];
		$secondaryColor = $this->attributes['secondaryColor'];

		$data = $this->data['countries'];

		$rows = array();

		foreach ($data as $key => $country) {
			$rows[$key] = array( $country['name'], $country['sessions'] );
		}

		return array(
			'rows' => $rows,
			'headers' => array(
				__( 'Top 10 Countries', 'google-analytics-for-wordpress' ),
				__( 'Sessions', 'google-analytics-for-wordpress' )
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