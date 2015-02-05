<?php

class Options_Double {

	public $options;

	/**
	 * Holds the settings for the GA plugin and possible subplugins
	 *
	 * @var string
	 */
	public $option_name = 'yst_ga';

	/**
	 * Holds the prefix we use within the option to save settings
	 *
	 * @var string
	 */
	public $option_prefix = 'ga_general';

	/**
	 * Holds the path to the main plugin file
	 *
	 * @var string
	 */
	public $plugin_path;

	/**
	 * Holds the URL to the main plugin directory
	 *
	 * @var string
	 */
	public $plugin_url;

	public function __construct( $options ) {
		//parent::__construct();

		$this->options = $options;
	}

	public function get_tracking_code() {
		return $this->options['manual_ua_code_field'];
	}
}

class Universal_Double extends Yoast_GA_Universal {

	/**
	 * @var
	 */
	private $test_options;

	/**
	 * construct
	 *
	 * @param $test_options
	 */
	public function __construct( $test_options ) {
		$this->test_options = $test_options;

		parent::__construct();
	}

	/**
	 * Get the options class
	 *
	 * @return object|Yoast_GA_Options
	 */
	public function get_options_class() {
		return new Options_Double( $this->test_options );
	}

}

class Yoast_GA_Universal_Test extends GA_UnitTestCase {

	/**
	 * @var Yoast_GA_Universal
	 */
	private $class_instance;

	/**
	 * @var int
	 */
	private $manual_ua_code = 1;

	/**
	 * @var string
	 */
	private $manual_ua_code_field = 'UA-123345-12';

	/**
	 * @var int
	 */
	private $enable_universal = 1;

	/**
	 * @var int
	 */
	private $enhanced_link_attribution = 0;

	/**
	 * @var int
	 */
	private $track_internal_as_outbound = 0;

	/**
	 * @var int
	 */
	private $track_internal_as_label = 0;

	/**
	 * @var int
	 */
	private $track_outbound = 0;

	/**
	 * @var int
	 */
	private $anonymous_data = 0;

	/**
	 * @var int
	 */
	private $demographics = 0;

	/**
	 * @var int
	 */
	private $anonymize_ips = 0;

	/**
	 * @var string
	 */
	private $track_download_as = 'event';

	/**
	 * Set the options
	 *
	 * @return array
	 */
	private function options() {
		return array(
			'manual_ua_code'             => $this->manual_ua_code,
			'manual_ua_code_field'       => $this->manual_ua_code_field,
			'enable_universal'           => $this->enable_universal,
			'enhanced_link_attribution'  => $this->enhanced_link_attribution,
			'track_internal_as_outbound' => $this->track_internal_as_outbound,
			'track_internal_as_label'    => $this->track_internal_as_label,
			'track_outbound'             => $this->track_outbound,
			'anonymous_data'             => $this->anonymous_data,
			'demographics'               => $this->demographics,
			'ignore_users'               => array( 'editor' ),
			'dashboards_disabled'        => 0,
			'anonymize_ips'              => $this->anonymize_ips,
			'track_download_as'          => $this->track_download_as,
			'extensions_of_files'        => 'doc,exe,js,pdf,ppt,tgz,zip,xls',
			'track_full_url'             => 'domain',
			'subdomain_tracking'         => null,
			'tag_links_in_rss'           => 0,
			'allow_anchor'               => 0,
			'add_allow_linker'           => 0,
			'custom_code'                => null,
			'debug_mode'                 => 0,
		);
	}

	/**
	 * Test the tracking with a manual UA code
	 */
	public function test_tracking() {
		$this->manual_ua_code       = 1;
		$this->manual_ua_code_field = 'UA-1234567-89';

		$this->class_instance = new Universal_Double( $this->options() );
		$tracking_data        = $this->class_instance->tracking( true );
		$tracking_data_type   = is_array( $tracking_data );

		if ( $tracking_data_type ) {
			$this->assertTrue( in_array( "'create', 'UA-1234567-89', 'auto'", $tracking_data ) );
			$this->assertTrue( in_array( "'send','pageview'", $tracking_data ) );
		} else {
			$this->assertTrue( $tracking_data_type );
		}
	}

	/**
	 * Test the tracking with a manual UA code with Enhanced link attribution
	 */
	public function test_tracking_WITH_enhanced_link_attribtion() {
		$this->enhanced_link_attribution = 1;

		$this->class_instance = new Universal_Double( $this->options() );
		$tracking_data        = $this->class_instance->tracking( true );
		$tracking_data_type   = is_array( $tracking_data );

		if ( $tracking_data_type ) {
			var_dump( $tracking_data );
			$this->assertTrue( in_array( "'require', 'linkid', 'linkid.js'", $tracking_data ) );
			$this->assertTrue( in_array( "'send','pageview'", $tracking_data ) );
		} else {
			$this->assertTrue( $tracking_data_type );
		}
	}

	/**
	 * Test tracking with the Enhanced link attribution enabled
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
//	public function test_tracking_WITH_enhanced_link_attribution() {
//		$options = array(
//			'manual_ua_code'            => 1,
//			'manual_ua_code_field'      => 'UA-1234567-89',
//			'enable_universal'          => 1,
//			'enhanced_link_attribution' => 1,
//		);
//
//		// Configure the options in the options stub
//		$stub = $this->getMockForAbstractClass( 'Yoast_GA_Tracking' );
//		$stub->method( 'get_enhanced_link_attribution' )
//			->willReturn( $options['enhanced_link_attribution'] );
//
//		//$result = $stub->tracking( true );
//
//		$stub_universal = $this->getMockBuilder( 'Yoast_GA_Universal' )
//			->getMock();
//
//		$result = $stub_universal->tracking( true );
//
//		var_dump( $result );
//	}

	/**
	 * Test some content
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
//	public function test_the_content() {
//		$test_string = 'Lorem ipsum dolor sit amet, <a href="' . get_site_url() . '/test">Linking text</a> Lorem ipsum dolor sit amet';
//
//		$this->assertEquals( $this->class_instance->the_content( $test_string ), "Lorem ipsum dolor sit amet, <a href=\"http://example.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-article-int', 'http://example.org/test', 'Linking text');\" >Linking text</a> Lorem ipsum dolor sit amet" );
//	}

	/**
	 * Test some widget content
	 *
	 * @covers Yoast_GA_Universal::widget_content()
	 */
//	public function test_widget_content() {
//		$test_string = '<a href="' . get_site_url() . '/test">Linking text</a>';
//
//		$this->assertEquals( $this->class_instance->widget_content( $test_string ), "<a href=\"http://example.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-widget-int', 'http://example.org/test', 'Linking text');\" >Linking text</a>" );
//	}

	/**
	 * Test a nav menu
	 *
	 * @covers Yoast_GA_Universal::nav_menu()
	 */
//	public function test_nav_menu() {
//		$test_string = '<a href="' . get_site_url() . '/test">Linking text</a>';
//
//		$this->assertEquals( $this->class_instance->nav_menu( $test_string ), "<a href=\"" . get_site_url() . "/test\" onclick=\"__gaTracker('send', 'event', 'outbound-menu-int', 'http://example.org/test', 'Linking text');\" >Linking text</a>" );
//	}

	/**
	 * Test a text string
	 *
	 * @covers Yoast_GA_Universal::comment_text()
	 */
//	public function test_comment_text() {
//		$test_string = 'Lorem ipsum dolor sit amet, consectetur <a href="' . get_site_url() . '/test">adipiscing elit</a>. Etiam tincidunt ullamcorper porttitor. Nam dapibus tincidunt posuere. Proin dignissim nisl at posuere fringilla.';
//
//		$this->assertEquals( $this->class_instance->comment_text( $test_string ), "Lorem ipsum dolor sit amet, consectetur <a href=\"http://example.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-comment-int', '" . get_site_url() . "/test', 'adipiscing elit');\" >adipiscing elit</a>. Etiam tincidunt ullamcorper porttitor. Nam dapibus tincidunt posuere. Proin dignissim nisl at posuere fringilla." );
//	}

	/**
	 * Test the custom code in the tracking
	 *
	 * @covers Yoast_GA_JS::tracking()
	 */
//	public function test_custom_code() {
//		$options_singleton      = Yoast_GA_Options::instance();
//		$options                = $options_singleton->get_options();
//		$options['custom_code'] = '__custom_code[\"test\"]';
//		$options_singleton->update_option( $options );
//
//		// create and go to post
//		$post_id = $this->factory->post->create();
//		$this->go_to( get_permalink( $post_id ) );
//
//		// Get tracking code
//		$tracking_data = $this->class_instance->tracking( true );
//
//		if ( is_array( $tracking_data ) ) {
//			foreach ( $tracking_data as $row ) {
//				if ( is_array( $row ) ) {
//					if ( $row['type'] == 'custom_code' ) {
//						$this->assertEquals( $row['value'], '__custom_code["test"]' );
//					}
//				}
//			}
//		}
//	}
}