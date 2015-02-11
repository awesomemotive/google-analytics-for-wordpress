<?php

// Load dependencies from API-Libs
require( dirname( __FILE__ ) . '/../../vendor/yoast/api-libs/google/class-api-google.php' );

class Yoast_GA_Dashboards_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Dashboards
	 */
	private $class_instance;

	public function __construct() {
		new Yoast_Api_Google;

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
				'title' => 'Sessions',
				'help'  => 'Sessions helptext',
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