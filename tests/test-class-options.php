<?php

class Yoast_GA_Options_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Options
	 */
	private $class_instance;

	public function __construct() {
		$this->class_instance = new Yoast_GA_Options;
	}

	/**
	 * Test the return array of get_options
	 *
	 * @covers Yoast_GA_Options::get_options()
	 */
	public function test_get_options() {
		$options      = $this->class_instance->get_options();
		$options_type = is_array( $options );

		if ( is_array( $options_type ) ) {
			// Test a few keys
			$this->assertTrue( isset( $options['manual_ua_code'] ) );
			$this->assertTrue( isset( $options['manual_ua_code_field'] ) );
			$this->assertTrue( isset( $options['track_outbound'] ) );
			$this->assertTrue( isset( $options['allow_anchor'] ) );
			$this->assertTrue( isset( $options['version'] ) );
		} else {
			$this->assertTrue( $options_type );
		}
	}

	/**
	 * Update an option and check the result
	 *
	 * @covers Yoast_GA_Options::update_option()
	 */
	public function test_update_option() {
		$options = $this->class_instance->get_options();

		if ( is_array( $options ) ) {
			// Set the manual UA code and update the option
			$options['manual_ua_code']       = 1;
			$options['manual_ua_code_field'] = 'UA-9876543-21';

			$this->class_instance->update_option( $options );

			// Get the new options
			$options = $this->class_instance->get_options();

			$this->assertEquals( $options['manual_ua_code_field'], 'UA-9876543-21' );
		}
	}

	/**
	 * Test the return value of the tracking code
	 *
	 * @covers Yoast_GA_Options::get_tracking_code()
	 */
	public function test_get_tracking_code() {
		$this->test_update_option();
		$tracking_code = $this->class_instance->get_tracking_code();

		$this->assertEquals( $tracking_code, 'UA-9876543-21' );
	}

	/**
	 * Test the defaults
	 *
	 * @covers Yoast_GA_Options::default_ga_values()
	 */
	public function test_default_ga_values() {
		$defaults = $this->class_instance->default_ga_values();

		$this->assertTrue( is_array( $defaults ) );
	}

	/**
	 * Check the options check function, we need at least the current dataset
	 *
	 * @covers Yoast_GA_Options::check_options()
	 */
	public function test_check_options() {
		$options     = $this->class_instance->get_options();
		$new_options = $this->class_instance->check_options( $options );

		$this->assertEquals( count( $options ), count( $new_options ) );

	}

}