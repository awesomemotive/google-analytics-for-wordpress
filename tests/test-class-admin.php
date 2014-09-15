<?php

// include file because the test isn't runned in the admin panel
require_once dirname( __FILE__ ) . '/../admin/class-admin.php';

class Yoast_GA_Admin_Test extends GA_UnitTestCase {

	/**
	 * @var WPSEO_Twitter
	 */
	private $class_instance;

	public function __construct() {
		global $yoast_ga_admin;

		$this->class_instance = $yoast_ga_admin;
	}

	public static function setUpBeforeClass() {

	}

	/**
	 * @covers Yoast_GA_Admin->init_ga()
	 */
	public function test_init_ga() {
		$this->assertEquals( $this->class_instance->init_ga(), NULL );
	}

	/**
	 * @covers Yoast_GA_Admin->create_form()
	 */
	public function test_create_form() {
		$action = admin_url( 'admin.php' );

		$this->assertEquals( $this->class_instance->create_form( 'phpunit' ), '<form action="' . $action . '" method="post" id="yoast-ga-form-phpunit" class="yoast_ga_form">' . wp_nonce_field( 'save_settings', 'yoast_ga_nonce', null, false ) );
	}

	/**
	 * @covers Yoast_GA_Admin->end_form()
	 */
	public function test_end_form() {
		$output = null;
		$output .= '<div class="ga-form ga-form-input">';
		$output .= '<input type="submit" name="ga-form-submit" value="Save changes" class="button button-primary ga-form-submit" id="yoast-ga-form-submit-phpunit">';
		$output .= '</div></form>';

		$this->assertEquals( $this->class_instance->end_form(), $output );
	}

}