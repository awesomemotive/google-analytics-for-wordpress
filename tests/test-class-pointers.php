<?php

class Yoast_GA_Pointers_Double extends Yoast_GA_Pointers {

	/**
	 * @var array Holds the admin pages we have pointers for and the callback that generates the pointers content
	 */
	private $admin_pages = array(
		'yst_ga_settings'   => 'settings_pointer',
		'yst_ga_dashboard'  => 'dashboard_pointer',
		'yst_ga_extensions' => 'extensions_pointer',
	);

	public function get_localize_script() {
		return $this->localize_script();
	}

	public function get_prepare_pointer() {
		$this->prepare_pointer();
	}

}


class Yoast_GA_Pointers_Test extends GA_UnitTestCase {

	/**
	 * This variable is instantiated in setUp() and is a mock object. This is used for future use in the tests.
	 *
	 * @var class
	 */
	private $class_instance;

	/**
	 * @var array Holds the buttons to be put out
	 */
	private $button_array;

	/**
	 * @var array Holds the default buttons
	 */
	private $button_array_defaults = array(
		'primary_button' => array(
			'text'     => false,
			'function' => '',
		),
		'previous_button' => array(
			'text'     => false,
			'function' => '',
		),
	);

	/**
	 * @var array Holds the options such as content and position.
	 */
	private $options_array;

	/**
	 * Mocks Yoast_GA_Pointers class for future use.
	 */
	public function setUp() {
		parent::setUp();

		$this->class_instance = $this->getMock( 'Yoast_GA_Pointers_Double', array( 'prepare_page_pointer', 'prepare_tour_pointer', 'get_current_page', 'get_ignore_url' ) );
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
	 * @covers Yoast_GA_Pointers::prepare_pointer
	 */
	public function test_localize_script_CALLS_prepare_page_pointer() {
		// Overwrite global $pagenow with 'admin.php' to fake we're on 'admin.php' page.
		global $pagenow;

		$pagenow = 'admin.php';

		$this->class_instance
			->expects( $this->once() )
			->method( 'get_current_page' )
			->will( $this->returnValue( 'yst_ga_settings' ) );

		$this->class_instance
			->expects( $this->once() )
			->method( 'prepare_page_pointer' );

		$this->class_instance->get_prepare_pointer();
	}

	/**
	 * Check that prepare_tour_pointer is called when the user is not on one of the google analytics pages.
	 *
	 * @covers Yoast_GA_Pointers::prepare_pointer
	 */
	public function test_localize_script_CALLS_prepare_tour_pointer() {
		$this->class_instance
			->expects( $this->once() )
			->method( 'get_current_page' )
			->will( $this->returnValue( '' ) );

		$this->class_instance
			->expects( $this->once() )
			->method( 'prepare_tour_pointer' );

		$this->class_instance->get_prepare_pointer();
	}

	/**
	 * Make sure localize_script returns the right information in its array
	 *
	 * @covers Yoast_GA_Pointers::localize_script
	 */
	public function test_localize_script_PREPARES_TOUR_POINTER() {
		$content  = '<h3>' . __( 'Congratulations!', 'google-analytics-for-wordpress' ) . '</h3>'
		            . '<p>' . __( 'You\'ve just installed Google Analytics by Yoast! Click "Start tour" to view a quick introduction of this plugin\'s core functionality.', 'google-analytics-for-wordpress' ) . '</p>';

		$this->selector = 'li#toplevel_page_yst_ga_dashboard';

		$this->options_array  = array(
			'content'  => $content,
			'position' => array( 'edge' => 'top', 'align' => 'center' ),
		);

		$this->button_array['primary_button'] = array(
			'text'     => __( 'Start tour', 'google-analytics-for-wordpress' ),
			'location' => admin_url( 'admin.php?page=yst_ga_settings' ),
		);

		$this->button_array = wp_parse_args( $this->button_array, $this->button_array_defaults );

		$yoast_ga_pointers = new Yoast_GA_Pointers_Double();

		$actual = $yoast_ga_pointers->get_localize_script();

		$this->assertContains( $this->selector, $actual );
		$this->assertContains( $this->options_array, $actual );
		$this->assertContains( $this->button_array, $actual );
		$this->assertContains( __( 'Close', 'google-analytics-for-wordpress' ), $actual );
	}

	/**
	 * Check if get_ignore_url is called by localize_script.
	 *
	 * @covers Yoast_GA_Pointers::localize_script
	 */
	public function test_localize_script_CALLS_get_ignore_url() {
		$this->class_instance
			->expects( $this->once() )
			->method( 'get_ignore_url' );

		$this->class_instance->get_localize_script();

	}

}
