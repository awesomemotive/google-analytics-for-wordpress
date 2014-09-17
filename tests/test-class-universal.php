<?php

// include file
require_once dirname( __FILE__ ) . '/../frontend/class-universal.php';

class Yoast_GA_Universal_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Universal
	 */
	private $class_instance;

	public function __construct() {
		global $yoast_ga_universal;

		$this->class_instance = $yoast_ga_universal;
	}

	/**
	 * Test is the class is created successfully
	 *
	 * @covers Yoast_GA_Universal
	 */
	public function test_class() {
		$yoast_ga_universal_class = class_exists( 'Yoast_GA_Universal' );

		$this->assertTrue( $yoast_ga_universal_class );
	}

	/**
	 * Test if we need to track, expected is true
	 *
	 * @covers Yoast_GA_Universal::do_tracking()
	 */
	public function test_do_tracking() {
		$this->assertTrue( $this->class_instance->do_tracking() );
	}

	/**
	 * Test a nav menu
	 *
	 * @covers Yoast_GA_Universal::nav_menu()
	 */
	public function test_nav_menu() {
		$test_string = '<a href="' . get_site_url() . '/test">Linking text</a>';

		$this->assertEquals( $this->class_instance->nav_menu( $test_string ), "<a href=\"" . get_site_url() . "/test\" onclick=\"ga('send', 'event', 'outbound-menu-int', 'http://example.org/test', 'Linking text');\" >Linking text</a>" );
	}

	/**
	 * Test a text string
	 *
	 * @covers Yoast_GA_Universal::comment_text()
	 */
	public function test_comment_text() {
		$test_string = 'Lorem ipsum dolor sit amet, consectetur <a href="' . get_site_url() . '/test">adipiscing elit</a>. Etiam tincidunt ullamcorper porttitor. Nam dapibus tincidunt posuere. Proin dignissim nisl at posuere fringilla.';

		$this->assertEquals( $this->class_instance->comment_text( $test_string ), "Lorem ipsum dolor sit amet, consectetur <a href=\"http://example.org/test\" onclick=\"ga('send', 'event', 'outbound-comment-int', '" . get_site_url() . "/test', 'adipiscing elit');\" >adipiscing elit</a>. Etiam tincidunt ullamcorper porttitor. Nam dapibus tincidunt posuere. Proin dignissim nisl at posuere fringilla." );
	}


}