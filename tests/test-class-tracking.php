<?php

class Yoast_GA_Tracking_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Frontend
	 */
	private $class_instance;

	public function setUp() {
		$this->class_instance = $this->getMockForAbstractClass('Yoast_GA_Tracking');
	}

	/**
	 * Check the do_tracking function, it must be true
	 *
	 * @covers Yoast_GA_Frontend::do_tracking()
	 */
	public function test_do_tracking() {
		$tracking = $this->class_instance->do_tracking();

		$this->assertTrue( $tracking );
	}

	/**
	 * Test if the domain and host are set
	 *
	 * @covers Yoast_GA_Frontend::yoast_ga_get_domain()
	 */
	public function test_yoast_ga_get_domain() {
		// Case 1 - HTTP
		$domain        = $this->class_instance->yoast_ga_get_domain( 'http://yoast.com' );
		$domain_result = is_array( $domain );

		if ( $domain_result ) {
			$this->assertArrayHasKey( 'domain', $domain );
			$this->assertArrayHasKey( 'host', $domain );

			$this->assertEquals( 'yoast.com', $domain['host'] );
		} else {
			$this->assertTrue( $domain_result );
		}

		// Case 2 - HTTPS
		$domain        = $this->class_instance->yoast_ga_get_domain( 'https://yoast.com' );
		$domain_result = is_array( $domain );

		if ( $domain_result ) {
			$this->assertArrayHasKey( 'domain', $domain );
			$this->assertArrayHasKey( 'host', $domain );

			$this->assertEquals( 'yoast.com', $domain['host'] );
		} else {
			$this->assertTrue( $domain_result );
		}
	}

	/**
	 * Test output for and return the full html link
	 *
	 * @covers Yoast_GA_Frontend::output_add_onclick()
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
	 * @covers Yoast_GA_Frontend::make_full_url()
	 */
	public function test_make_full_url() {
		// Case 1
		$link = array(
			'type'         => 'download',
			'protocol'     => 'https',
			'original_url' => 'yoast.com'
		);

		$this->assertEquals( $this->class_instance->make_full_url( $link ), 'https://yoast.com' );

		// Case 2
		$link = array(
			'type'         => 'internal',
			'protocol'     => 'http',
			'original_url' => 'yoast.com'
		);

		$this->assertEquals( $this->class_instance->make_full_url( $link ), 'http://yoast.com' );

		// Case 3
		$link = array(
			'type'         => 'internal-as-outbound',
			'protocol'     => 'https',
			'original_url' => 'yoast.com/out/test'
		);

		$this->assertEquals( $this->class_instance->make_full_url( $link ), 'https://yoast.com/out/test' );

		// Case 4
		$link = array(
			'type'         => 'email',
			'protocol'     => 'mailto',
			'original_url' => 'peter@yoast.com'
		);

		$this->assertEquals( $this->class_instance->make_full_url( $link ), 'mailto:peter@yoast.com' );

	}
}