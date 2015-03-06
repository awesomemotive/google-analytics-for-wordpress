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

	/**
	 * Test user roles, we should get a few standard roles here. We also check if the role name is not empty
	 *
	 * @covers Yoast_GA_Admin::get_userroles()
	 */
	public function test_get_userroles() {
		$roles        = $this->class_instance->get_userroles();
		$roles_result = is_array( $roles );

		if ( $roles_result ) {
			foreach ( $roles as $values ) {
				$this->assertArrayHasKey( 'id', $values );
				$this->assertArrayHasKey( 'name', $values );
				if ( isset( $values['name'] ) ) {
					$this->assertNotEmpty( $values['name'] );
				}
			}
		}
		else {
			$this->assertTrue( $roles_result );
		}

	}

	/**
	 * Test download types
	 *
	 * @covers Yoast_GA_Admin::track_download_types()
	 */
	public function test_track_download_types() {
		$download_types        = $this->class_instance->track_download_types();
		$download_types_result = is_array( $download_types );

		if ( $download_types_result ) {
			foreach ( $download_types as $values ) {
				$this->assertArrayHasKey( 'id', $values );
				$this->assertArrayHasKey( 'name', $values );
				if ( isset( $values['name'] ) ) {
					$this->assertNotEmpty( $values['name'] );
				}
			}
		}
		else {
			$this->assertTrue( $download_types_result );
		}

	}

	/**
	 * Test track full url option
	 *
	 * @covers Yoast_GA_Admin::get_track_full_url()
	 */
	public function test_get_track_full_url() {
		$track_options        = $this->class_instance->get_track_full_url();
		$track_options_result = is_array( $track_options );

		if ( $track_options ) {
			foreach ( $track_options as $values ) {
				$this->assertArrayHasKey( 'id', $values );
				$this->assertArrayHasKey( 'name', $values );
				if ( isset( $values['name'] ) ) {
					$this->assertNotEmpty( $values['name'] );
				}
			}
		}
		else {
			$this->assertTrue( $track_options_result );
		}

	}

}