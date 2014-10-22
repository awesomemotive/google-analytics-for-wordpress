<?php

class Yoast_Google_Analytics_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Frontend
	 */
	private $class_instance;

	public function __construct() {
		parent::__construct();

		$this->class_instance = new Yoast_Google_Analytics();
	}

	/**
	 * Check if singleton is an instance of Yoast_Google_Analytics
	 *
	 * @covers Yoast_Google_Analytics::instance()
	 */
	public function test_instance() {
		$instance = Yoast_Google_Analytics::instance();

		$this->assertTrue( $instance instanceof Yoast_Google_Analytics );
	}

	/**
	 * Test if a token isn't set.
	 *
	 * @covers Yoast_Google_Analytics::has_token
	 */
	public function test_has_token_NO_manipulation() {
		$this->assertFalse( $this->class_instance->has_token() );
	}

	/**
	 * Setting a token manually to fake the has_token function. Because with no call, there won't be a token.
	 *
	 * @covers Yoast_Google_Analytics::has_token()
	 */
	public function test_has_token_DO_manipulate() {
		$old_options = get_option( 'yst_ga_api' );

		update_option(
			'yst_ga_api',
			array(
				'ga_token' => 'custom',
				'ga_oauth' => array(
					'access_token' => array(
						'oauth_token'        => 123,
						'oauth_token_secret' => 321
					)
				)
			)
		);

		$instance = new Yoast_Google_Analytics();

		$this->assertTrue( $instance->has_token() );

		update_option( 'yst_ga_api', $old_options );

		unset( $instance );
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
		$stub->expects( $this->once() )
			->method( 'do_request' );

		$stub->get_profiles();


	}


}