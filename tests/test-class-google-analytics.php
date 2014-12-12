<?php

class Yoast_Google_Analytics_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Frontend
	 */
	private $class_instance;

	public function __construct() {
		parent::__construct();

		$this->class_instance = Yoast_Google_Analytics::get_instance();
	}

	/**
	 * Check if singleton is an instance of Yoast_Google_Analytics
	 *
	 * @covers Yoast_Google_Analytics::instance()
	 */
	public function test_instance() {
		$instance = Yoast_Google_Analytics::get_instance();

		$this->assertTrue( $instance instanceof Yoast_Google_Analytics );
	}

	/**
	 * Check if the get_profiles will be loaded.
	 *
	 * Doing a mock on do_request as if it is calling an external api call to Google
	 *
	 * @covers Yoast_Google_Analytics::get_profiles()
	 */
	public function test_get_profiles() {

		$stub = $this->getMock( 'Yoast_Google_Analytics', array( 'do_request' ) );

		// Configure the stub.
		$stub->expects( $this->exactly(2) )
			->method( 'do_request' );

		$stub->get_profiles();


	}


}