<?php

class Yoast_GA_Tracking_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Tracking
	 */
	private $class_instance;

	public function setUp() {
		$this->class_instance = $this->getMockForAbstractClass('Yoast_GA_Tracking');
	}

	/**
	 * Check the do_tracking function, it must be true
	 *
	 * @covers Yoast_GA_Tracking::do_tracking()
	 */
	public function test_do_tracking() {
		$tracking = $this->class_instance->do_tracking();

		$this->assertTrue( $tracking );
	}

	/**
	 * Test output for and return the full html link
	 *
	 * @covers Yoast_GA_Tracking::output_add_onclick()
	 */
	public function test_output_add_onclick() {
		// Case 1
		$link_attribute = '<a href="/test.html" onclick="alert(\'Test\');">link content</a>';
		$onclick_add    = 'dofunction();';

		$this->assertEquals( $this->class_instance->output_add_onclick( $link_attribute, $onclick_add ), '<a href="/test.html" onclick="alert(\'Test\'); dofunction();">link content</a>' );

		// Case 2
		$link_attribute = '<a href="/test.html" onclick="alert(\'Test\');" data-title="test title">link content</a>';
		$onclick_add    = 'dofunction();';

		$this->assertEquals( $this->class_instance->output_add_onclick( $link_attribute, $onclick_add ), '<a href="/test.html" onclick="alert(\'Test\'); dofunction();" data-title="test title">link content</a>' );
	}

	/**
	 * Create a few urls from a dataset (multiple cases)
	 *
	 * @covers Yoast_GA_Tracking::make_full_url()
	 */
	public function test_make_full_url() {
		// Case 1
		$link = new stdClass();
		$link->type = 'download';
		$link->protocol = 'https';
		$link->original_url = 'yoast.com';


		$this->assertEquals( $this->class_instance->make_full_url( $link ), 'https://yoast.com' );

		// Case 2
		$link = new stdClass();
		$link->type = 'internal';
		$link->protocol = 'http';
		$link->original_url = 'yoast.com';

		$this->assertEquals( $this->class_instance->make_full_url( $link ), 'http://yoast.com' );

		// Case 3
		$link = new stdClass();
		$link->type = 'internal-as-outbound';
		$link->protocol = 'https';
		$link->original_url = 'yoast.com/out/test';

		$this->assertEquals( $this->class_instance->make_full_url( $link ), 'https://yoast.com/out/test' );

		// Case 4
		$link = new stdClass();
		$link->type = 'email';
		$link->protocol = 'mailto';
		$link->original_url = 'peter@yoast.com';

		$this->assertEquals( $this->class_instance->make_full_url( $link ), 'mailto:peter@yoast.com' );

		// Case 5
		$link = new StdClass();
		$link->type = 'internal-as-outbound';
		$link->protocol = '';
		$link->original_url = '/out/test';

		$this->assertEquals ( $this->class_instance->make_full_url( $link ), '/out/test' );
	}
}