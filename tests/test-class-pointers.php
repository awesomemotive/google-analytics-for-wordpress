<?php

class Yoast_GA_Pointers_Test extends GA_UnitTestCase {

	/**
	 * This variable is instantiated in setUp() and is a mock object. This is used for future use in the tests.
	 *
	 * @var class
	 */
	private $class_instance;

	/**
	 * Mocks Yoast_GA_Pointers class for future use.
	 */
	public function setUp() {
		parent::setUp();

		$this->class_instance = $this->getMock( 'Yoast_GA_Pointers', array( 'do_page_pointer', 'start_tour_pointer', 'get_current_page' ) );
	}



	/**
	 * Checks if function intro_tour is called when the tour is not ignored.
	 *
	 * @covers Yoast_GA_Pointers::__construct
	 */
	public function test_ga_ignore_tour_IS_FALSE() {

	}

	/**
	 * Checks if function intro_tour is not called when the tour is ignored.
	 *
	 * @covers Yoast_GA_Pointers::__construct
	 */
	public function test_ga_ignore_tour_IS_TRUE() {

	}

	/**
	 * Check that page pointer is called when the user is on one of the admin pages and the tour is active.
	 *
	 * @covers Yoast_GA_Pointers::intro_tour
	 */
	public function test_tour_page_pointer() {
		// Overwrite global $pagenow with 'admin.php' to fake we're on 'admin.php'.
		global $pagenow;
		$pagenow = 'admin.php';

		$this->class_instance
			->expects( $this->once() )
			->method( 'get_current_page' )
			->willReturn( 'settings' );

		$this->class_instance
			->expects( $this->once() )
			->method( 'do_page_pointer' );

		$this->class_instance->intro_tour();
	}

	/**
	 *
	 * @covers Yoast_GA_Pointers::intro_tour
	 */
	public function test_tour_start_pointer() {

	}

	/**
	 *
	 * @covers Yoast_GA_Pointers::print_scripts
	 */
	public function test_print_scripts() {

	}

}