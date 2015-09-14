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
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user ($user_id);

		$class_instance = new Yoast_GA_Pointers();

		// Login user
		wp_set_current_user ($user_id);

		$has_tracking_actions = has_action( 'admin_print_footer_scripts', array( $class_instance, 'intro_tour' ) );
		$has_tracking_actions = is_int( $has_tracking_actions );

		$this->assertTrue( $has_tracking_actions );
	}

	/**
	 * Checks if function intro_tour is not called when the tour is ignored.
	 *
	 * @covers Yoast_GA_Pointers::__construct
	 */
	public function test_ga_ignore_tour_IS_TRUE() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );

		wp_set_current_user ($user_id);
		update_user_meta( $user_id, 'ga_ignore_tour', '1' );

		$class_instance = new Yoast_GA_Pointers();

		// Login user
		wp_set_current_user ($user_id);

		$has_tracking_actions = has_action( 'admin_print_footer_scripts', array( $class_instance, 'intro_tour' ) );
		$has_tracking_actions = is_int( $has_tracking_actions );

		$this->assertFalse( $has_tracking_actions );
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
	 * Check that tour pointer is called when the user is not on one of the google analytics pages.
	 *
	 * @covers Yoast_GA_Pointers::intro_tour
	 */
	public function test_tour_start_pointer() {
		$this->class_instance
			->expects( $this->once() )
			->method( 'get_current_page' )
			->willReturn( '' );

		$this->class_instance
			->expects( $this->once() )
			->method( 'start_tour_pointer' );

		$this->class_instance->intro_tour();
	}

	/**
	 * Tests if print_scripts prints the correct message on the admin page.
	 *
	 * @covers Yoast_GA_Pointers::print_scripts
	 */
	public function test_print_scripts() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );

		$old_user_id = get_current_user_id();

		// Login user
		wp_set_current_user ($user_id);

		$this->go_to( admin_url() );

		$class_instance = new Yoast_GA_Pointers();

		$selector = 'li#toplevel_page_yst_ga_dashboard';
		$content  = '<h3>' . __( 'Congratulations!', 'google-analytics-for-wordpress' ) . '</h3>'
		            . '<p>' . __( 'You\'ve just installed Google Analytics by Yoast! Click "Start tour" to view a quick introduction of this plugin\'s core functionality.', 'google-analytics-for-wordpress' ) . '</p>';
		$opt_arr  = array(
			'content'  => $content,
			'position' => array( 'edge' => 'top', 'align' => 'center' ),
		);

		ob_start();
		$class_instance->print_scripts( $selector, $opt_arr );

		$output = ob_get_contents();
		ob_end_clean();

		$expected_output  = 'You\'ve just installed Google Analytics by Yoast!';

		$this->assertContains( $expected_output, $output );

		// Set current user back to old user id
		wp_set_current_user( $old_user_id );
	}

}