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

	public function do_tracking() {
		return true;
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
	 * @var int
	 */
	private $add_allow_linker = 0;

	/**
	 * @var int
	 */
	private $debug_mode = 0;

	/**
	 * @var null|string
	 */
	private $custom_code = null;

	/**
	 * @var int
	 */
	private $allow_anchor = 0;

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
			'custom_code'                => $this->custom_code,
			'debug_mode'                 => $this->debug_mode,
			'add_allow_linker'           => $this->add_allow_linker,
			'allow_anchor'               => $this->allow_anchor,
		);
	}

	/**
	 * Prepare function for tracking data
	 *
	 * @return array
	 */
	private function prepare_tracking() {
		$this->class_instance = new Universal_Double( $this->options() );
		$tracking_data        = $this->class_instance->tracking( true );

		return array(
			'data'     => $tracking_data,
			'is_array' => is_array( $tracking_data ),
		);
	}

	/**
	 * Helper to replace links in the_content, widget_content, nav_menu and the comments section
	 *
	 * @param string $url
	 * @param string $expected_url
	 * @param string $method
	 * @param null   $link_attributes
	 */
	private function helper_replace_links( $url, $expected_url, $method, $link_attributes = null ) {
		$this->class_instance = new Universal_Double( $this->options() );
		$test_string          = 'Lorem ipsum dolor sit amet, <a href="' . $url . '" ' . $link_attributes . '>Linking text</a> Lorem ipsum dolor sit amet';

		$this->assertEquals( "Lorem ipsum dolor sit amet, " . $expected_url . " Lorem ipsum dolor sit amet", $this->class_instance->$method( $test_string ) );
	}

	/**
	 * Test the tracking with a manual UA code
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_tracking() {
		$this->manual_ua_code       = 1;
		$this->manual_ua_code_field = 'UA-1234567-89';

		$tracking = $this->prepare_tracking();

		if ( $tracking['is_array'] ) {
			$this->assertTrue( in_array( "'create', 'UA-1234567-89', 'auto'", $tracking['data'] ) );
			$this->assertTrue( in_array( "'send','pageview'", $tracking['data'] ) );
		}
		else {
			$this->assertTrue( $tracking['is_array'] );
		}
	}

	/**
	 * Test the tracking with a manual UA code with Enhanced link attribution
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_tracking_WITH_enhanced_link_attribution() {
		$this->enhanced_link_attribution = 1;

		$tracking = $this->prepare_tracking();

		if ( $tracking['is_array'] ) {
			$this->assertTrue( in_array( "'require', 'linkid', 'linkid.js'", $tracking['data'] ) );
			$this->assertTrue( in_array( "'send','pageview'", $tracking['data'] ) );
		}
		else {
			$this->assertTrue( $tracking['is_array'] );
		}
	}

	/**
	 * Test the tracking with a manual UA code with allow anchor
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_tracking_WITH_allow_anchor() {
		$this->manual_ua_code       = 1;
		$this->manual_ua_code_field = 'UA-1234567-89';
		$this->allow_anchor         = 1;

		$tracking = $this->prepare_tracking();

		if ( $tracking['is_array'] ) {
			$this->assertTrue( in_array( "'create', 'UA-1234567-89', 'auto', {'allowAnchor': true}", $tracking['data'] ) );
			$this->assertTrue( in_array( "'send','pageview'", $tracking['data'] ) );
		}
		else {
			$this->assertTrue( $tracking['is_array'] );
		}
	}

	/**
	 * Test the tracking with a manual UA code with add allow linker
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_tracking_WITH_add_allow_linker() {
		$this->manual_ua_code       = 1;
		$this->manual_ua_code_field = 'UA-1234567-89';
		$this->add_allow_linker     = 1;

		$tracking = $this->prepare_tracking();

		if ( $tracking['is_array'] ) {
			$this->assertTrue( in_array( "'create', 'UA-1234567-89', 'auto', {'allowLinker': true}", $tracking['data'] ) );
			$this->assertTrue( in_array( "'send','pageview'", $tracking['data'] ) );
		}
		else {
			$this->assertTrue( $tracking['is_array'] );
		}
	}

	/**
	 * Test the tracking with a manual UA code with allow anchor and add allow linker
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_tracking_WITH_allow_anchor_AND_add_allow_linker() {
		$this->manual_ua_code       = 1;
		$this->manual_ua_code_field = 'UA-1234567-89';
		$this->allow_anchor         = 1;
		$this->add_allow_linker     = 1;

		$tracking = $this->prepare_tracking();

		if ( $tracking['is_array'] ) {
			$this->assertTrue( in_array( "'create', 'UA-1234567-89', 'auto', {'allowAnchor': true, 'allowLinker': true}", $tracking['data'] ) );
			$this->assertTrue( in_array( "'send','pageview'", $tracking['data'] ) );
		}
		else {
			$this->assertTrue( $tracking['is_array'] );
		}
	}

	/**
	 * Test the tracking with a manual UA code with anonymize ips
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_tracking_WITH_anonymize_ips() {
		$this->anonymize_ips = 1;

		$tracking = $this->prepare_tracking();

		if ( $tracking['is_array'] ) {
			$this->assertTrue( in_array( "'set', 'anonymizeIp', true", $tracking['data'] ) );
			$this->assertTrue( in_array( "'send','pageview'", $tracking['data'] ) );
		}
		else {
			$this->assertTrue( $tracking['is_array'] );
		}
	}

	/**
	 * Test the tracking with a manual UA code with demographics
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_tracking_WITH_demographics() {
		$this->enable_universal = 1;
		$this->demographics     = 1;

		$tracking = $this->prepare_tracking();

		if ( $tracking['is_array'] ) {
			$this->assertTrue( in_array( "'require', 'displayfeatures'", $tracking['data'] ) );
			$this->assertTrue( in_array( "'send','pageview'", $tracking['data'] ) );
		}
		else {
			$this->assertTrue( $tracking['is_array'] );
		}
	}

	/**
	 * Test the custom code in the tracking
	 *
	 * @covers Yoast_GA_JS::tracking()
	 */
	public function test_tracking_WITH_debug_mode() {
		$this->custom_code = '__custom_code[\"test\"]';

		$tracking = $this->prepare_tracking();

		if ( $tracking['is_array'] ) {
			foreach ( $tracking['data'] as $row ) {
				if ( is_array( $row ) ) {
					if ( $row['type'] == 'custom_code' ) {
						$this->assertEquals( $row['value'], '__custom_code["test"]' );
					}
				}
			}
		}
		else {
			$this->assertTrue( $tracking['is_array'] );
		}
	}

	/**
	 * Test some content with an outbound link
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
	public function test_the_content_WITH_outbound_link() {
		$this->track_outbound = 1;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-article', 'http://examples.org/test', 'Linking text');\">Linking text</a>", 'the_content' );
	}

	/**
	 * Test some content without link tracking
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
	public function test_the_content_WITH_outbound_link_without_tracking() {
		$this->track_outbound = 0;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\">Linking text</a>", 'the_content' );
	}

	/**
	 * Test some content with an internal link as outbound
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
	public function test_the_content_WITH_interal_link_as_outbound_link() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-article-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'the_content' );
	}

	/**
	 * Test some content as internal link as outbound and track as pageview
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
	public function test_the_content_WITH_interal_link_as_outbound_link_as_pageview() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';
		$this->track_download_as          = 'pageview';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'pageview', 'outbound-article-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'the_content' );
	}

	/**
	 * Test some content with outbound links and link attributes
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
	public function test_the_content_WITH_outbound_link_WITH_link_attributes() {
		$this->track_outbound = 1;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-article', 'http://examples.org/test', 'Linking text');\"  title=\"test\" style=\"color: #fff;\">Linking text</a>", 'the_content', 'title="test" style="color: #fff;"' );
	}

	/**
	 * Test some content with an outbound link
	 *
	 * @covers Yoast_GA_Universal::widget_content()
	 */
	public function test_widget_content_WITH_outbound_link() {
		$this->track_outbound = 1;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-widget', 'http://examples.org/test', 'Linking text');\">Linking text</a>", 'widget_content' );
	}

	/**
	 * Test some content without link tracking
	 *
	 * @covers Yoast_GA_Universal::widget_content()
	 */
	public function test_widget_content_WITH_outbound_link_without_tracking() {
		$this->track_outbound = 0;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\">Linking text</a>", 'widget_content' );
	}

	/**
	 * Test some content with an internal link as outbound
	 *
	 * @covers Yoast_GA_Universal::widget_content()
	 */
	public function test_widget_content_WITH_interal_link_as_outbound_link() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-widget-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'widget_content' );
	}

	/**
	 * Test some content as internal link as outbound and track as pageview
	 *
	 * @covers Yoast_GA_Universal::widget_content()
	 */
	public function test_widget_content_WITH_interal_link_as_outbound_link_as_pageview() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';
		$this->track_download_as          = 'pageview';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'pageview', 'outbound-widget-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'widget_content' );
	}

	/**
	 * Test some content with outbound links and link attributes
	 *
	 * @covers Yoast_GA_Universal::widget_content()
	 */
	public function test_widget_content_WITH_outbound_link_WITH_link_attributes() {
		$this->track_outbound = 1;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-widget', 'http://examples.org/test', 'Linking text');\"  title=\"test\" style=\"color: #fff;\">Linking text</a>", 'widget_content', 'title="test" style="color: #fff;"' );
	}

	/**
	 * Test some content with an outbound link
	 *
	 * @covers Yoast_GA_Universal::nav_menu()
	 */
	public function test_nav_menu_WITH_outbound_link() {
		$this->track_outbound = 1;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-menu', 'http://examples.org/test', 'Linking text');\">Linking text</a>", 'nav_menu' );
	}

	/**
	 * Test some content without link tracking
	 *
	 * @covers Yoast_GA_Universal::nav_menu()
	 */
	public function test_nav_menu_WITH_outbound_link_without_tracking() {
		$this->track_outbound = 0;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\">Linking text</a>", 'nav_menu' );
	}

	/**
	 * Test some content with an internal link as outbound
	 *
	 * @covers Yoast_GA_Universal::nav_menu()
	 */
	public function test_nav_menu_WITH_interal_link_as_outbound_link() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-menu-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'nav_menu' );
	}

	/**
	 * Test some content as internal link as outbound and track as pageview
	 *
	 * @covers Yoast_GA_Universal::nav_menu()
	 */
	public function test_nav_menu_WITH_interal_link_as_outbound_link_as_pageview() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';
		$this->track_download_as          = 'pageview';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'pageview', 'outbound-menu-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'nav_menu' );
	}

	/**
	 * Test some content with outbound links and link attributes
	 *
	 * @covers Yoast_GA_Universal::nav_menu()
	 */
	public function test_nav_menu_WITH_outbound_link_WITH_link_attributes() {
		$this->track_outbound = 1;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-menu', 'http://examples.org/test', 'Linking text');\"  title=\"test\" style=\"color: #fff;\">Linking text</a>", 'nav_menu', 'title="test" style="color: #fff;"' );
	}

	/**
	 * Test some content with an outbound link
	 *
	 * @covers Yoast_GA_Universal::comment_text()
	 */
	public function test_comment_text_WITH_outbound_link() {
		$this->track_outbound = 1;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-comment', 'http://examples.org/test', 'Linking text');\">Linking text</a>", 'comment_text' );
	}

	/**
	 * Test some content without link tracking
	 *
	 * @covers Yoast_GA_Universal::comment_text()
	 */
	public function test_comment_text_WITH_outbound_link_without_tracking() {
		$this->track_outbound = 0;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\">Linking text</a>", 'comment_text' );
	}

	/**
	 * Test some content with an internal link as outbound
	 *
	 * @covers Yoast_GA_Universal::comment_text()
	 */
	public function test_comment_text_WITH_interal_link_as_outbound_link() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-comment-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'comment_text' );
	}

	/**
	 * Test some content as internal link as outbound and track as pageview
	 *
	 * @covers Yoast_GA_Universal::comment_text()
	 */
	public function test_comment_text_WITH_interal_link_as_outbound_link_as_pageview() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';
		$this->track_download_as          = 'pageview';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'pageview', 'outbound-comment-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'comment_text' );
	}

	/**
	 * Test some content with outbound links and link attributes
	 *
	 * @covers Yoast_GA_Universal::comment_text()
	 */
	public function test_comment_text_WITH_outbound_link_WITH_link_attributes() {
		$this->track_outbound = 1;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-comment', 'http://examples.org/test', 'Linking text');\"  title=\"test\" style=\"color: #fff;\">Linking text</a>", 'comment_text', 'title="test" style="color: #fff;"' );
	}

}