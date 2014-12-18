<?php

// Load dependencies from API-Libs
require( dirname( __FILE__ ) . '/../../admin/api-libs/google/class-api-google.php' );

class Yoast_GA_Dashboards_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Dashboards
	 */
	private $class_instance;

	public function __construct() {
		$this->google_api     = new Yoast_Api_Google;
		$this->class_instance = Yoast_GA_Dashboards::get_instance();

		parent::__construct();
	}

	/**
	 * Expects no output
	 *
	 * @covers Yoast_GA_Dashboards::init_dashboards()
	 */
	public function test_init_dashboards() {
		$this->assertEquals( $this->class_instance->init_dashboards( 1 ), NULL );
	}

	/**
	 * Test extend dashboards
	 *
	 * @covers Yoast_GA_Dashboards::extend_dashboards()
	 */
	public function test_extend_dashboards() {
		$dashboards = array(
			'sessions' => array(
				'title' => __( 'Sessions', 'google-analytics-for-wordpress' ),
				'help'  => __( 'A session is a group of interactions that take place on your website within a given time frame. For example a single session can contain multiple screen or page views, events, social interactions, and ecommerce transactions. <a href="http://yoa.st/gasessions" target="_blank">[Learn more]</a>', 'google-analytics-for-wordpress' ),
				'type'  => 'graph',
				'tab'   => 'general',
			)
		);

		$result = $this->class_instance->extend_dashboards( $dashboards );

		$this->assertEquals( $result, $dashboards );
	}

	/**
	 * Check if the register items valid
	 *
	 * @covers Yoast_GA_Dashboards::register()
	 */
	public function test_register() {
		$register = array(
			'sessions',
			'bounceRate',
		);

		$result = $this->class_instance->register( $register );
		$this->assertTrue( $result );
	}

}