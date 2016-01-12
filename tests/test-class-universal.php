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
	 * @var array
	 */
	private $ignore_users = array();

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
			'ignore_users'               => $this->ignore_users,
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
			//$this->assertTrue( in_array( "'create', 'UA-1234567-89', 'auto'", $tracking['data'] ) );

			$this->assertTrue( in_array( '\'create\', {"trackingId":"' . $this->manual_ua_code_field . '","cookieDomain":"auto"}', $tracking['data'] ) );
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
			$this->assertTrue( in_array( '\'create\', {"trackingId":"' . $this->manual_ua_code_field . '","cookieDomain":"auto","allowAnchor":true}', $tracking['data'] ) );
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
			$this->assertTrue( in_array( '\'create\', {"trackingId":"' . $this->manual_ua_code_field . '","cookieDomain":"auto","allowLinker":true}', $tracking['data'] ) );
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
			$this->assertTrue( in_array( '\'create\', {"trackingId":"' . $this->manual_ua_code_field . '","cookieDomain":"auto","allowLinker":true,"allowAnchor":true}', $tracking['data'] ) );
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
	public function test_the_content_WITH_outbound_link_AND_full_URL() {
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
	 * Test some content with an internal link as outbound and full URL
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
	public function test_the_content_WITH_internal_link_as_outbound_link_AND_full_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-article-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'the_content' );
	}

	/**
	 * Test some content with an internal link as outbound and relative URL
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
	public function test_the_content_WITH_internal_link_as_outbound_link_AND_relative_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links('/out/outbound', "<a href=\"/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-article-test-label', '/out/outbound', 'Linking text');\">Linking text</a>", 'the_content' );
	}

	/**
	 * Test some content with an internal link as outbound and link attributes
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
	public function test_the_content_WITH_internal_link_as_outbound_link_AND_link_attributes() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-article-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\" target=\"_blank\">Linking text</a>", 'the_content', 'target="_blank"' );
	}

	/**
	 * Test some content as internal link as outbound and track as pageview with full URL
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
	public function test_the_content_WITH_internal_link_as_outbound_link_as_pageview_WITH_full_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';
		$this->track_download_as          = 'pageview';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'pageview', 'outbound-article-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'the_content' );
	}

	/**
	 * Test some content as internal link as outbound and track as pageview with relative URL
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
	public function test_the_content_WITH_internal_link_as_outbound_link_as_pageview_WITH_relative_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';
		$this->track_download_as          = 'pageview';

		$this->helper_replace_links( '/out/outbound', "<a href=\"/out/outbound\" onclick=\"__gaTracker('send', 'pageview', 'outbound-article-test-label', '/out/outbound', 'Linking text');\">Linking text</a>", 'the_content' );
	}

	/**
	 * Test some content with outbound links and link attributes
	 *
	 * @covers Yoast_GA_Universal::the_content()
	 */
	public function test_the_content_WITH_outbound_link_WITH_link_attributes() {
		$this->track_outbound = 1;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-article', 'http://examples.org/test', 'Linking text');\" title=\"test\" style=\"color: #fff;\">Linking text</a>", 'the_content', 'title="test" style="color: #fff;"' );
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
	 * Test some content with an internal link as outbound with full URL
	 *
	 * @covers Yoast_GA_Universal::widget_content()
	 */
	public function test_widget_content_WITH_internal_link_as_outbound_link_WITH_full_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-widget-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'widget_content' );
	}

	/**
	 * Test some content with an internal link as outbound with relative URL
	 *
	 * @covers Yoast_GA_Universal::widget_content()
	 */
	public function test_widget_content_WITH_internal_link_as_outbound_link_WITH_relative_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links( '/out/outbound', "<a href=\"/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-widget-test-label', '/out/outbound', 'Linking text');\">Linking text</a>", 'widget_content' );
	}

	/**
	 * Test some content as internal link as outbound and track as pageview
	 *
	 * @covers Yoast_GA_Universal::widget_content()
	 */
	public function test_widget_content_WITH_internal_link_as_outbound_link_as_pageview_WITH_full_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';
		$this->track_download_as          = 'pageview';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'pageview', 'outbound-widget-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'widget_content' );
	}

	/**
	 * Test some content as internal link as outbound and track as pageview
	 *
	 * @covers Yoast_GA_Universal::widget_content()
	 */
	public function test_widget_content_WITH_internal_link_as_outbound_link_as_pageview_WITH_relative_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';
		$this->track_download_as          = 'pageview';

		$this->helper_replace_links( '/out/outbound', "<a href=\"/out/outbound\" onclick=\"__gaTracker('send', 'pageview', 'outbound-widget-test-label', '/out/outbound', 'Linking text');\">Linking text</a>", 'widget_content' );
	}

	/**
	 * Test some content with outbound links and link attributes
	 *
	 * @covers Yoast_GA_Universal::widget_content()
	 */
	public function test_widget_content_WITH_outbound_link_WITH_link_attributes() {
		$this->track_outbound = 1;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-widget', 'http://examples.org/test', 'Linking text');\" title=\"test\" style=\"color: #fff;\">Linking text</a>", 'widget_content', 'title="test" style="color: #fff;"' );
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
	public function test_nav_menu_WITH_internal_link_as_outbound_link_WITH_full_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-menu-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'nav_menu' );
	}

	/**
	 * Test some content with an internal link as outbound
	 *
	 * @covers Yoast_GA_Universal::nav_menu()
	 */
	public function test_nav_menu_WITH_internal_link_as_outbound_link_WITH_relative_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links( '/out/outbound', "<a href=\"/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-menu-test-label', '/out/outbound', 'Linking text');\">Linking text</a>", 'nav_menu' );
	}

	/**
	 * Test some content as internal link as outbound and track as pageview
	 *
	 * @covers Yoast_GA_Universal::nav_menu()
	 */
	public function test_nav_menu_WITH_internal_link_as_outbound_link_as_pageview_WITH_full_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';
		$this->track_download_as          = 'pageview';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'pageview', 'outbound-menu-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'nav_menu' );
	}

	/**
	 * Test some content as internal link as outbound and track as pageview
	 *
	 * @covers Yoast_GA_Universal::nav_menu()
	 */
	public function test_nav_menu_WITH_internal_link_as_outbound_link_as_pageview_WITH_relative_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';
		$this->track_download_as          = 'pageview';

		$this->helper_replace_links( '/out/outbound', "<a href=\"/out/outbound\" onclick=\"__gaTracker('send', 'pageview', 'outbound-menu-test-label', '/out/outbound', 'Linking text');\">Linking text</a>", 'nav_menu' );
	}

	/**
	 * Test some content with outbound links and link attributes
	 *
	 * @covers Yoast_GA_Universal::nav_menu()
	 */
	public function test_nav_menu_WITH_outbound_link_WITH_link_attributes() {
		$this->track_outbound = 1;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-menu', 'http://examples.org/test', 'Linking text');\" title=\"test\" style=\"color: #fff;\">Linking text</a>", 'nav_menu', 'title="test" style="color: #fff;"' );
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
	public function test_comment_text_WITH_internal_link_as_outbound_link_WITH_full_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-comment-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'comment_text' );
	}

	/**
	 * Test some content with an internal link as outbound
	 *
	 * @covers Yoast_GA_Universal::comment_text()
	 */
	public function test_comment_text_WITH_internal_link_as_outbound_link_WITH_relative_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';

		$this->helper_replace_links( '/out/outbound', "<a href=\"/out/outbound\" onclick=\"__gaTracker('send', 'event', 'outbound-comment-test-label', '/out/outbound', 'Linking text');\">Linking text</a>", 'comment_text' );
	}

	/**
	 * Test some content as internal link as outbound and track as pageview
	 *
	 * @covers Yoast_GA_Universal::comment_text()
	 */
	public function test_comment_text_WITH_internal_link_as_outbound_link_as_pageview_WITH_full_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';
		$this->track_download_as          = 'pageview';

		$this->helper_replace_links( get_site_url() . '/out/outbound', "<a href=\"" . get_site_url() . "/out/outbound\" onclick=\"__gaTracker('send', 'pageview', 'outbound-comment-test-label', '" . get_site_url() . "/out/outbound', 'Linking text');\">Linking text</a>", 'comment_text' );
	}

	/**
	 * Test some content as internal link as outbound and track as pageview
	 *
	 * @covers Yoast_GA_Universal::comment_text()
	 */
	public function test_comment_text_WITH_internal_link_as_outbound_link_as_pageview_WITH_relative_URL() {
		$this->track_internal_as_outbound = '/out/';
		$this->track_internal_as_label    = 'test-label';
		$this->track_download_as          = 'pageview';

		$this->helper_replace_links( '/out/outbound', "<a href=\"/out/outbound\" onclick=\"__gaTracker('send', 'pageview', 'outbound-comment-test-label', '/out/outbound', 'Linking text');\">Linking text</a>", 'comment_text' );
	}

	/**
	 * Test some content with outbound links and link attributes
	 *
	 * @covers Yoast_GA_Universal::comment_text()
	 */
	public function test_comment_text_WITH_outbound_link_WITH_link_attributes() {
		$this->track_outbound = 1;

		$this->helper_replace_links( 'http://examples.org/test', "<a href=\"http://examples.org/test\" onclick=\"__gaTracker('send', 'event', 'outbound-comment', 'http://examples.org/test', 'Linking text');\" title=\"test\" style=\"color: #fff;\">Linking text</a>", 'comment_text', 'title="test" style="color: #fff;"' );
	}

	/**
	 * Test if source outputs message when debug mode is on and user is not admin.
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_debug_mode_IS_ON_and_user_IS_NOT_admin() {
		$post_id = $this->factory->post->create();
		$this->go_to( get_permalink( $post_id ) );

		$this->debug_mode = 1;

		$class_instance = new Universal_Double( $this->options() );

		ob_start();
		$class_instance->tracking();
		$output = ob_get_contents();
		ob_end_clean();

		$expected_output = '<!-- This site uses the Google Analytics by Yoast plugin version ' . GAWP_VERSION .  ' - https://yoast.com/wordpress/plugins/google-analytics/ --><!-- Normally you will find the Google Analytics tracking code here, but the webmaster has enabled the Debug Mode. --><!-- / Google Analytics by Yoast -->';

		$this->assertContains( $expected_output, $output );
	}

	/**
	 * Test if source outputs message when debug mode is on and user is admin.
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_debug_mode_IS_ON_and_user_IS_admin() {
		$post_id = $this->factory->post->create();

		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );

		// Login user
		wp_set_current_user ($user_id);

		// Get old user id
		$old_user_id = get_current_user_id();

		$this->go_to( get_permalink( $post_id ) );

		$this->debug_mode = 1;

		$class_instance = new Universal_Double( $this->options() );

		ob_start();
		$class_instance->tracking();
		$output = ob_get_contents();
		ob_end_clean();

		$expected_output  = '<!-- This site uses the Google Analytics by Yoast plugin version ' . GAWP_VERSION . ' - https://yoast.com/wordpress/plugins/google-analytics/ --><!-- @Webmaster, normally you will find the Google Analytics tracking code here, but the Debug Mode is enabled. To change this, navigate to Analytics -> Settings -> (Tab) Debug Mode and disable Debug Mode to enable tracking of your site. --><!-- / Google Analytics by Yoast -->';

		$this->assertContains( $expected_output, $output );

		// Set current user back to old user id so the tests won't fail in test-output.php
		wp_set_current_user( $old_user_id );
	}

	/**
	 * When tracking is off for the admin, the tracking code should not be displayed for an admin
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_tracking_IS_OFF_FOR_ADMIN_and_user_IS_ADMIN() {
		$post_id = $this->factory->post->create();

		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );

		// Get old user id
		$old_user_id = get_current_user_id();

		// Login user
		wp_set_current_user ($user_id);

		$this->go_to( get_permalink( $post_id ) );

		$this->ignore_users = array( translate_user_role('administrator') );

		$class_instance = new Universal_Double( $this->options() );

		ob_start();
		$class_instance->tracking();
		$output = ob_get_contents();
		ob_end_clean();

		$expected_output  = '<!-- This site uses the Google Analytics by Yoast plugin version ' . GAWP_VERSION . ' - https://yoast.com/wordpress/plugins/google-analytics/ --><!-- @Webmaster, normally you will find the Google Analytics tracking code here, but you are in the disabled user groups. To change this, navigate to Analytics -> Settings (Ignore usergroups) --><!-- / Google Analytics by Yoast -->';

		$this->assertContains( $expected_output, $output );

		// Set current user back to old user id so the tests won't fail in test-output.php
		wp_set_current_user( $old_user_id );
	}

	/**
	 * When tracking is off for admin, the tracking code should still be displayed for other users that are not administrator
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_tracking_IS_OFF_FOR_ADMIN_and_user_IS_EDITOR() {
		$post_id = $this->factory->post->create();

		$user_id = $this->factory->user->create( array( 'role' => 'editor' ) );

		// Get old user id
		$old_user_id = get_current_user_id();

		// Login user
		wp_set_current_user ($user_id);

		$this->go_to( get_permalink( $post_id ) );

		$this->ignore_users = array( translate_user_role('administrator') );

		$class_instance = new Universal_Double( $this->options() );

		ob_start();
		$class_instance->tracking();
		$output = ob_get_contents();
		ob_end_clean();

		$expected_output = '__gaTracker(\'create\', {"trackingId":"' . $this->manual_ua_code_field . '","cookieDomain":"auto"})';

		$this->assertContains( $expected_output, $output );

		// Set current user back to old user id so the tests won't fail in test-output.php
		wp_set_current_user( $old_user_id );
	}

	/**
	 * When tracking is off for editor, the tracking code should not be displayed for an editor
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_tracking_IS_OFF_FOR_EDITOR_and_user_IS_EDITOR() {
		$post_id = $this->factory->post->create();

		$user_id = $this->factory->user->create( array( 'role' => 'editor' ) );

		// Get old user id
		$old_user_id = get_current_user_id();

		// Login user
		wp_set_current_user ($user_id);

		$this->go_to( get_permalink( $post_id ) );

		$this->ignore_users = array( translate_user_role('editor') );

		$class_instance = new Universal_Double( $this->options() );

		ob_start();
		$class_instance->tracking();
		$output = ob_get_contents();
		ob_end_clean();

		$expected_output  = '<!-- This site uses the Google Analytics by Yoast plugin version ' . GAWP_VERSION . ' - https://yoast.com/wordpress/plugins/google-analytics/ --><!-- Normally you will find the Google Analytics tracking code here, but the webmaster disabled your user group. --><!-- / Google Analytics by Yoast -->';
		$this->assertContains( $expected_output, $output );

		// Set current user back to old user id so the tests won't fail in test-output.php
		wp_set_current_user( $old_user_id );
	}

	/**
	 * When tracking is off for admin, the tracking code should still be displayed for visitors who are not logged in
	 *
	 * @covers Yoast_GA_Universal::tracking()
	 */
	public function test_tracking_IS_OFF_FOR_ADMIN_and_user_IS_NOT_LOGGED_IN() {
		$post_id = $this->factory->post->create();

		$this->go_to( get_permalink( $post_id ) );

		$this->ignore_users = array( translate_user_role('administrator') );

		$class_instance = new Universal_Double( $this->options() );

		ob_start();
		$class_instance->tracking();
		$output = ob_get_contents();
		ob_end_clean();

		$expected_output = '__gaTracker(\'create\', {"trackingId":"' . $this->manual_ua_code_field . '","cookieDomain":"auto"})';

		$this->assertContains( $expected_output, $output );
	}
}