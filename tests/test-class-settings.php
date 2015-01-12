<?php

class Yoast_GA_Settings_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Settings
	 */
	private $class_instance;

	public function __construct() {
		$this->class_instance = Yoast_GA_Settings::get_instance();

		$this->class_options = Yoast_GA_Options::instance();
	}

	/**
	 * Test if the dashboards are enabled (default setting)
	 *
	 * @covers Yoast_GA_Settings::dashboards_disabled()
	 */
	public function test_dashboards_disabled() {
		$this->assertFalse( $this->class_instance->dashboards_disabled() );
	}

	/**
	 * Test if the dashboards are disabled after updating the option
	 *
	 * @covers Yoast_GA_Settings::dashboards_disabled()
	 */
	public function test_dashboards_disabled_AND_update_option() {
		$options = $this->class_options->get_options();
		$options['dashboards_disabled'] = 1;
		$this->class_options->update_option( $options );

		// Get the new options by re-instantiate the settings class
		$this->class_instance = Yoast_GA_Settings::get_instance();

		$this->assertTrue( $this->class_instance->dashboards_disabled() );
	}

}