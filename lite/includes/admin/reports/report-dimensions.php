<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MonsterInsights_Lite_Report_Dimensions extends MonsterInsights_Report {

	public $title;
	public $class   = 'MonsterInsights_Report_Dimensions';
	public $name    = 'dimensions';
	public $version = '1.0.0';
	public $level   = 'pro';

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 6.0.0
	 */
	public function __construct() {
		$this->title = __( 'Dimensions', 'google-analytics-for-wordpress' );
		parent::__construct();
	}

	protected function get_report_html( $data = array() ){
		return $this->get_upsell_notice();
	}
}