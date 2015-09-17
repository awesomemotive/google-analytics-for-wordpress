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

		$this->class_instance = $this->getMock( 'Yoast_GA_Pointers', array( 'prepare_page_pointer', 'prepare_tour_pointer', 'get_current_page' ) );
	}

	/**
	 * Checks if function intro_tour is not called when the tour is ignored.
	 *
	 * @covers Yoast_GA_Pointers::__construct
	 */
	public function test_ga_ignore_tour_IS_TRUE() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );

		$old_user_id = get_current_user_id();

		update_user_meta( $user_id, 'ga_ignore_tour', '1' );
		wp_set_current_user( $user_id );

		new Yoast_GA_Pointers();

		$has_script = wp_script_is( 'yoast_ga_pointer' );

		$this->assertFalse( $has_script );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * Checks if yoast_ga_pointer is called when the tour is not ignored.
	 *
	 * @covers Yoast_GA_Pointers::__construct
	 */
	public function test_ga_ignore_tour_IS_FALSE() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );

		$old_user_id = get_current_user_id();

		wp_set_current_user ($user_id);

		new Yoast_GA_Pointers();

		// Login user
		wp_set_current_user ($user_id);

		$has_script = wp_script_is( 'yoast_ga_pointer' );

		$this->assertTrue( $has_script );

		// Set current user back to old user id
		wp_set_current_user( $old_user_id );
	}

	/**
	 * Check that prepare_page_pointer is called when the user is on one of the admin pages and the tour is active.
	 *
	 * @covers Yoast_GA_Pointers::localize_script
	 */
	public function test_localize_script_CALLS_prepare_page_pointer() {
		// Overwrite global $pagenow with 'admin.php' to fake we're on 'admin.php' page.
		global $pagenow;

		$pagenow = 'admin.php';

		$this->class_instance
			->expects( $this->once() )
			->method( 'get_current_page' )
			->willReturn( 'settings' );

		$this->class_instance
			->expects( $this->once() )
			->method( 'prepare_page_pointer' );

		$this->class_instance->localize_script();
	}

	/**
	 * Check that preapre_tour_pointer is called when the user is not on one of the google analytics pages.
	 *
	 * @covers Yoast_GA_Pointers::localize_script
	 */
	public function test_localize_script_CALLS_prepare_tour_pointer() {
		$this->class_instance
			->expects( $this->once() )
			->method( 'get_current_page' )
			->willReturn( '' );

		$this->class_instance
			->expects( $this->once() )
			->method( 'prepare_tour_pointer' );

		$this->class_instance->localize_script();
	}
	
}
