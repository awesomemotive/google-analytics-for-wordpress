<?php

class Yoast_GA_Admin_Settings_Registrar_Double extends Yoast_GA_Admin_Settings_Registrar {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @return array
	 */
	public function get_userroles(){
		return parent::get_userroles();
	}

	/**
	 * @return array
	 */
	public function track_download_types(){
		return parent::track_download_types();
	}

	/**
	 * @return array
	 */
	public function get_track_full_url(){
		return parent::get_track_full_url();
	}

}


class Yoast_GA_Admin_Settings_Registrar_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Admin
	 */
	private $class_instance;

	public function __construct() {
		parent::__construct();

		$this->class_instance = new Yoast_GA_Admin_Settings_Registrar_Double();
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
		} else {
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
		} else {
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
		} else {
			$this->assertTrue( $track_options_result );
		}

	}

}