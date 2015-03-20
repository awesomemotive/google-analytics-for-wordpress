<?php

class Yoast_GA_Admin_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Admin
	 */
	private $class_instance;

	public function __construct() {
		parent::__construct();

		$this->class_instance = new Yoast_GA_Admin();
	}

	/**
	 * Test is the class is created successfully
	 *
	 * @covers Yoast_GA_Admin
	 */
	public function test_class() {
		$yoast_ga_admin_class = class_exists( 'Yoast_GA_Admin' );

		$this->assertTrue( $yoast_ga_admin_class );
	}

	/**
	 * We shouldn't expect output here
	 *
	 * @covers Yoast_GA_Admin::init_ga()
	 */
	public function test_init_ga() {
		$this->assertEquals( $this->class_instance->init_ga(), NULL );
	}

	/**
	 * Call init_settings so the private functions get called too
	 * We don't expect output here.
	 *
	 * @covers Yoast_GA_Admin::init_settings()
	 */
	public function init_settings() {
		$this->assertEquals( $this->class_instance->init_settings(), NULL );
	}

}