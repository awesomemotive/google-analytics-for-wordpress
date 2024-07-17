<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_Lite_Report_Realtime extends MonsterInsights_Report {

	public $title;
	public $class = 'MonsterInsights_Lite_Report_Realtime';
	public $name = 'queries';
	public $version = '1.0.0';
	public $level = 'plus';

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 6.0.0
	 */
	public function __construct() {
		$this->title = __( 'Real Time', 'google-analytics-for-wordpress' );
		parent::__construct();
	}

	protected function get_report_html( $data = array() ) {
		return $this->get_upsell_notice();
	}
}
