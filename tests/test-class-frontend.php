<?php

class Yoast_GA_Frontend_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Frontend
	 */
	private $class_instance;

	public function __construct() {
		global $yoast_ga_frontend;

		$this->class_instance = $yoast_ga_frontend;
	}

	public function test_output_add_onclick() {
		// Case 1
		$link_attribute = '<a href="/test.html" onclick="alert(\'Test\');">link content</a>';
		$onclick_add = 'dofunction();';

		$this->assertEquals( $this->class_instance->output_add_onclick( $link_attribute, $onclick_add ), '<a href="/test.html" onclick="alert(\'Test\'); dofunction();">link content</a>' );

		// Case 2
		$link_attribute = '<a href="/test.html" onclick="alert(\'Test\');" data-title="test title">link content</a>';
		$onclick_add = 'dofunction();';

		$this->assertEquals( $this->class_instance->output_add_onclick( $link_attribute, $onclick_add ), '<a href="/test.html" onclick="alert(\'Test\'); dofunction();" data-title="test title">link content</a>' );
	}

	/**
	 * @covers Yoast_GA_Frontend->make_full_url()
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
			'type'         => 'email',
			'protocol'     => 'mailto',
			'original_url' => 'peter@yoast.com'
		);

		$this->assertEquals( $this->class_instance->make_full_url( $link ), 'mailto:peter@yoast.com' );

	}

}