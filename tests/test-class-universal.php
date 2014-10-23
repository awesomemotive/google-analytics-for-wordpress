<?php

class Yoast_GA_Universal_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Universal
	 */
	private $class_instance;

	public function __construct() {
		$this->class_instance = new Yoast_GA_Universal();
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
	 * Test tracking
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_tracking() {
		// Update the options
		$options                         = $this->class_instance->get_options();
		$options['enable_universal']     = 1;
		$options['allowanchor']          = 1;
		$options['manual_ua_code']       = 1;
		$options['manual_ua_code_field'] = 'UA-1234567-89';
		$this->class_instance->update_option( $options );

		// create and go to post
		$post_id = $this->factory->post->create();
		$this->go_to( get_permalink( $post_id ) );

		// Get tracking code
		$tracking_data      = $this->class_instance->tracking( true );
		$tracking_data_type = is_array( $tracking_data );

		if ( $tracking_data_type ) {
			$this->assertTrue( in_array( "'create', 'UA-1234567-89', 'auto'", $tracking_data ) );
			$this->assertTrue( in_array( "'send','pageview'", $tracking_data ) );
		} else {
			$this->assertTrue( $tracking_data_type );
		}
	}

	/**
	 * Test the custom code in the tracking
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_custom_code() {
		$options                = $this->class_instance->get_options();
		$options['custom_code'] = '__custom_code[\"test\"]';
		$this->class_instance->update_option( $options );

		// create and go to post
		$post_id = $this->factory->post->create();
		$this->go_to( get_permalink( $post_id ) );

		// Get tracking code
		$tracking_data = $this->class_instance->tracking( true );

		if ( is_array( $tracking_data ) ) {
			foreach ( $tracking_data as $row ) {
				if(is_array($row)){
					if($row['type']=='custom_code'){
						$this->assertEquals( $row['value'], '__custom_code["test"]' );
					}
				}
			}
		}
	}

	/**
	 * Test some content
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
	public function test_the_content() {
		$test_string = 'Lorem ipsum dolor sit amet, <a href="' . get_site_url() . '/test">Linking text</a> Lorem ipsum dolor sit amet';

		$this->assertEquals( $this->class_instance->the_content( $test_string ), "Lorem ipsum dolor sit amet, <a href=\"http://example.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-article-int', 'http://example.org/test', 'Linking text');\" >Linking text</a> Lorem ipsum dolor sit amet" );
	}

	/**
	 * Test some widget content
	 *
	 * @covers Yoast_GA_Universal::widget_content()
	 */
	public function test_widget_content() {
		$test_string = '<a href="' . get_site_url() . '/test">Linking text</a>';

		$this->assertEquals( $this->class_instance->widget_content( $test_string ), "<a href=\"http://example.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-widget-int', 'http://example.org/test', 'Linking text');\" >Linking text</a>" );
	}

	/**
	 * Test a nav menu
	 *
	 * @covers Yoast_GA_Universal::nav_menu()
	 */
	public function test_nav_menu() {
		$test_string = '<a href="' . get_site_url() . '/test">Linking text</a>';

		$this->assertEquals( $this->class_instance->nav_menu( $test_string ), "<a href=\"" . get_site_url() . "/test\" onclick=\"__gaTracker('send', 'event', 'outbound-menu-int', 'http://example.org/test', 'Linking text');\" >Linking text</a>" );
	}

	/**
	 * Test a text string
	 *
	 * @covers Yoast_GA_Universal::comment_text()
	 */
	public function test_comment_text() {
		$test_string = 'Lorem ipsum dolor sit amet, consectetur <a href="' . get_site_url() . '/test">adipiscing elit</a>. Etiam tincidunt ullamcorper porttitor. Nam dapibus tincidunt posuere. Proin dignissim nisl at posuere fringilla.';

		$this->assertEquals( $this->class_instance->comment_text( $test_string ), "Lorem ipsum dolor sit amet, consectetur <a href=\"http://example.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-comment-int', '" . get_site_url() . "/test', 'adipiscing elit');\" >adipiscing elit</a>. Etiam tincidunt ullamcorper porttitor. Nam dapibus tincidunt posuere. Proin dignissim nisl at posuere fringilla." );
	}


}