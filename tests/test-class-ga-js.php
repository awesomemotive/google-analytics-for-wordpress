<?php

// include file
require_once dirname( __FILE__ ) . '/../frontend/class-ga-js.php';

class Yoast_GA_JS_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_JS
	 */
	private $class_instance;

	public function __construct() {
		global $yoast_ga_js;

		$this->class_instance = $yoast_ga_js;
	}

	/**
	 * Test is the class is created successfully
	 *
	 * @covers Yoast_GA_JS
	 */
	public function test_class() {
		$yoast_ga_js_class = class_exists( 'Yoast_GA_JS' );

		$this->assertTrue( $yoast_ga_js_class );
	}

	/**
	 * Test if we need to track, expected is true
	 *
	 * @covers Yoast_GA_JS::do_tracking()
	 */
	public function test_do_tracking() {
		$this->assertTrue( $this->class_instance->do_tracking() );
	}

	/**
	 * Test tracking (Add output rendering?)
	 *
	 * @covers Yoast_GA_JS::tracking()
	 */
	public function test_tracking() {
		echo $this->class_instance->tracking();
	}

	/**
	 * Test a tracking prefix
	 *
	 * @covers Yoast_GA_JS::tracking_prefix()
	 */
	public function test_get_tracking_prefix() {
		$this->assertEquals( $this->class_instance->get_tracking_prefix(), '/yoast-ga/');
	}

	/**
	 * Test some content
	 *
	 * @covers Yoast_GA_JS::the_content()
	 */
	public function test_the_content() {
		$test_string = 'Lorem ipsum dolor sit amet, <a href="' . get_site_url() . '/test">Linking text</a> Lorem ipsum dolor sit amet';

		$this->assertEquals( $this->class_instance->the_content( $test_string ), "Lorem ipsum dolor sit amet, <a href=\"http://example.org/test\" onclick=\"_gaq.push(['_trackEvent', 'outbound-article-int', 'http://example.org/test', 'Linking text']);\" >Linking text</a> Lorem ipsum dolor sit amet" );
	}

	/**
	 * Test some widget content
	 *
	 * @covers Yoast_GA_JS::widget_content()
	 */
	public function test_widget_content() {
		$test_string = '<a href="' . get_site_url() . '/test">Linking text</a>';

		$this->assertEquals( $this->class_instance->widget_content( $test_string ), "<a href=\"http://example.org/test\" onclick=\"_gaq.push(['_trackEvent', 'outbound-widget-int', 'http://example.org/test', 'Linking text']);\" >Linking text</a>" );
	}

	/**
	 * Test a nav menu
	 *
	 * @covers Yoast_GA_JS::nav_menu()
	 */
	public function test_nav_menu() {
		$test_string = '<a href="' . get_site_url() . '/test">Linking text</a>';

		$this->assertEquals( $this->class_instance->nav_menu( $test_string ), "<a href=\"http://example.org/test\" onclick=\"_gaq.push(['_trackEvent', 'outbound-menu-int', 'http://example.org/test', 'Linking text']);\" >Linking text</a>" );
	}

	/**
	 * Test a text string
	 *
	 * @covers Yoast_GA_JS::comment_text()
	 */
	public function test_comment_text() {
		$test_string = 'Lorem ipsum dolor sit amet, consectetur <a href="' . get_site_url() . '/test">adipiscing elit</a>. Etiam tincidunt ullamcorper porttitor. Nam dapibus tincidunt posuere. Proin dignissim nisl at posuere fringilla.';

		$this->assertEquals( $this->class_instance->comment_text( $test_string ), "Lorem ipsum dolor sit amet, consectetur <a href=\"http://example.org/test\" onclick=\"_gaq.push(['_trackEvent', 'outbound-comment-int', 'http://example.org/test', 'adipiscing elit']);\" >adipiscing elit</a>. Etiam tincidunt ullamcorper porttitor. Nam dapibus tincidunt posuere. Proin dignissim nisl at posuere fringilla." );
	}


}