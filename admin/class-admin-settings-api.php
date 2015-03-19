<?php

/**
 * This class is for options/settings in the admin forms
 */
class Yoast_GA_Admin_Settings_API extends Yoast_GA_Admin {

	/**
	 * The website GA settings
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * Current section of the form fields
	 *
	 * @var string
	 */
	private $current_section;

	/**
	 * @var array
	 */
	private $default_options = array(
		'tracking_code'        => array(),
		'user_roles'           => array(),
		'track_download_types' => array(),
		'track_full_url'       => array(),
		'analytics_profile'    => array(),
	);

	/**
	 * The slug of this settings page, used in the Settings API
	 *
	 * @var string
	 */
	private $settings_api_page = 'yst_ga_settings_api';

	/**
	 * Construct the new admin settings api forms
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init_default_options' ) );
		add_action( 'admin_init', array( $this, 'yst_ga_settings_init_ua_code' ) );
		add_action( 'admin_init', array( $this, 'yst_ga_settings_init_general' ) );
		add_action( 'admin_init', array( $this, 'yst_ga_settings_init_universal' ) );
		add_action( 'admin_init', array( $this, 'yst_ga_settings_init_advanced' ) );
		add_action( 'admin_init', array( $this, 'yst_ga_settings_init_debug' ) );

		if ( isset( $_GET['settings-updated'] ) ) {
			add_action( 'admin_init', array( $this, 'update_ga_tracking_from_profile' ) );

			$this->add_notification( 'ga_notifications', array(
				'type'        => 'success',
				'description' => __( 'Settings saved.', 'google-analytics-for-wordpress' ),
			) );
		}
	}

	/**
	 * Update the UA tracking code if we have a profile selected in the dropdown in the settings field
	 */
	public function update_ga_tracking_from_profile() {
		$tracking_code = get_option( 'yst_ga' );
		if ( $tracking_code['ga_general']['analytics_profile'] !== '' && ( $tracking_code['ga_general']['manual_ua_code'] === '0' || $tracking_code['ga_general']['manual_ua_code'] === 0 ) ) {
			$tracking_code['ga_general']['analytics_profile_code'] = $this->get_ua_code_from_profile( $tracking_code['ga_general']['analytics_profile'] );

			unset( $tracking_code['ga_general']['ga_general'] ); // cleanup old keys
			update_option( 'yst_ga', $tracking_code );
		}
	}

	/**
	 * Init the UA code block
	 */
	public function yst_ga_settings_init_ua_code() {
		register_setting( $this->settings_api_page . '_ua_code', 'yst_ga' );

		$this->create_section(
			'ua_code'
		);

		if ( ! empty( $this->default_options['analytics_profile'] ) && count( $this->default_options['analytics_profile'] ) >= 1 ) {
			$this->add_field(
				'analytics_profile',
				__( 'Google Analytics profile', 'google-analytics-for-wordpress' ),
				'select_profile',
				array(
					'key'        => 'analytics_profile',
					'help'       => __( 'Select an analytics profile from your Google account to use for the tracking on this website.', 'google-analytics-for-wordpress' ),
					'attributes' => ' class="chosen"',
					'options'    => $this->default_options['analytics_profile'],
				)
			);
		}

		$this->add_field(
			'manual_ua_code',
			__( 'Use a manual UA code', 'google-analytics-for-wordpress' ),
			'checkbox',
			array(
				'key'  => 'manual_ua_code',
				'help' => __( 'You can use the manual UA code field to enter your UA code manually, instead of using the Google Authenticator.', 'google-analytics-for-wordpress' ),
			)
		);

		$this->add_field(
			'manual_ua_code_field',
			__( 'Enter your UA code here', 'google-analytics-for-wordpress' ),
			'text',
			array(
				'key'  => 'manual_ua_code_field',
				'help' => __( 'Enter the UA code (e.g.: UA-1234567-89) here, you can find the correct UA code in your Google Analaytics dashboard.', 'google-analytics-for-wordpress' ),
			)
		);
	}

	/**
	 * Init the general tab
	 */
	public function yst_ga_settings_init_general() {
		register_setting( $this->settings_api_page . '_general', 'yst_ga' );

		$this->create_section(
			'general'
		);

		$this->add_field(
			'track_outbound',
			__( 'Track outbound click and downloads', 'google-analytics-for-wordpress' ),
			'checkbox',
			array(
				'key'  => 'track_outbound',
				'help' => __( 'Clicks and downloads will be tracked as events, you can find these under Content &#xBB; Event Tracking in your Google Analytics reports.', 'google-analytics-for-wordpress' ),
			)
		);

		$this->add_field(
			'anonymous_data',
			__( 'Allow tracking of anonymous data', 'google-analytics-for-wordpress' ),
			'checkbox',
			array(
				'key'  => 'anonymous_data',
				'help' => __( 'By allowing us to track anonymous data we can better help you, because we know with which WordPress configurations, themes and plugins we should test. No personal data will be submitted.', 'google-analytics-for-wordpress' ),
			)
		);

		$this->add_field(
			'anonymize_ips',
			__( 'Anonymize IPs', 'google-analytics-for-wordpress' ),
			'checkbox',
			array(
				'key'  => 'anonymize_ips',
				'help' => sprintf( __( 'This adds %1$s, telling Google Analytics to anonymize the information sent by the tracker objects by removing the last octet of the IP address prior to its storage.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApi_gat?csw=1#_gat._anonymizeIp" target="_blank"><code>_anonymizeIp</code></a>' ),
			)
		);

		$this->add_field(
			'ignore_users',
			__( 'Ignore users', 'google-analytics-for-wordpress' ),
			'select',
			array(
				'key'        => 'ignore_users',
				'help'       => __( 'Users of the role you select will be ignored, so if you select Editor, all Editors will be ignored.', 'google-analytics-for-wordpress' ),
				'attributes' => ' multiple="true" style="width: 365px;" class="chosen"',
				'options'    => $this->default_options['user_roles'],
			)
		);

		$this->add_field(
			'dashboards_disabled',
			__( 'Disable analytics dashboard', 'google-analytics-for-wordpress' ),
			'checkbox',
			array(
				'key'  => 'dashboards_disabled',
				'help' => __( 'This will completely disable the dashboard and stop the plugin from fetching the latest analytics data.', 'google-analytics-for-wordpress' ),
			)
		);
	}

	/**
	 * Init the universal tab
	 */
	public function yst_ga_settings_init_universal() {
		register_setting( $this->settings_api_page . '_universal', 'yst_ga' );

		$this->create_section(
			'universal'
		);

		$this->add_field(
			'enable_universal',
			__( 'Enable universal', 'google-analytics-for-wordpress' ),
			'checkbox',
			array(
				'key'  => 'enable_universal',
				'help' => sprintf( __( 'First enable Universal tracking in your Google Analytics account. Please read %1$sthis guide%2$s to learn how to do that.', 'google-analytics-for-wordpress' ), '<a href="http://kb.yoast.com/article/125-universal-analytics#utm_medium=kb-link&utm_source=gawp-config&utm_campaign=wpgaplugin" target="_blank">', '</a>' ),
			)
		);

		$this->add_field(
			'demographics',
			__( 'Enable Demographics and Interest Reports', 'google-analytics-for-wordpress' ),
			'checkbox',
			array(
				'key'  => 'demographics',
				'help' => sprintf( __( 'You have to enable the Demographics in Google Analytics before you can see the tracking data. We have a knowledge base article in our %1$sknowledge base%2$s about this feature.', 'google-analytics-for-wordpress' ), '<a href="http://kb.yoast.com/article/154-enable-demographics-and-interests-report-in-google-analytics/#utm_medium=kb-link&amp;utm_source=gawp-config&amp;utm_campaign=wpgaplugin" target="_blank">', '</a>' ),
			)
		);

		$this->add_field(
			'enhanced_link_attribution',
			__( 'Enhanced Link Attribution', 'google-analytics-for-wordpress' ),
			'checkbox',
			array(
				'key'  => 'enhanced_link_attribution',
				'help' => sprintf( __( 'Add %1$sEnhanced Link Attribution%2$s to your tracking code.', 'google-analytics-for-wordpress' ), '<a href="https://support.google.com/analytics/answer/2558867" target="_blank">', ' </a>' )
			)
		);

	}

	/**
	 * Init the advanced tab
	 */
	public function yst_ga_settings_init_advanced() {
		register_setting( $this->settings_api_page . '_advanced', 'yst_ga' );

		$this->create_section(
			'advanced'
		);

		$this->add_field(
			'track_download_as',
			__( 'Track downloads as', 'google-analytics-for-wordpress' ),
			'select',
			array(
				'key'        => 'track_download_as',
				'help'       => __( 'Not recommended, as this would skew your statistics, but it does make it possible to track downloads as goals.', 'google-analytics-for-wordpress' ),
				'options'    => $this->default_options['track_download_types'],
				'attributes' => ' class="chosen"',
			)
		);

		$this->add_field(
			'extensions_of_files',
			__( 'Extensions of files to track as downloads', 'google-analytics-for-wordpress' ),
			'text',
			array(
				'key'  => 'extensions_of_files',
				'help' => __( 'Please separate extensions using commas', 'google-analytics-for-wordpress' ),
			)
		);

		$this->add_field(
			'track_full_url',
			__( 'Track full URL of outbound clicks or just the domain', 'google-analytics-for-wordpress' ),
			'select',
			array(
				'key'        => 'track_full_url',
				'help'       => __( 'How should we track your outbound clicks?', 'google-analytics-for-wordpress' ),
				'options'    => $this->default_options['track_full_url'],
				'attributes' => ' class="chosen"',
			)
		);

		$this->add_field(
			'subdomain_tracking',
			__( 'Subdomain tracking', 'google-analytics-for-wordpress' ),
			'text',
			array(
				'key'  => 'subdomain_tracking',
				'help' => sprintf( __( 'This allows you to set the domain that\'s set by %1$s for tracking subdomains.<br/>If empty, this will not be set.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiDomainDirectory#_gat.GA_Tracker_._setDomainName" target="_blank"><code>_setDomainName</code></a>' ),
			)
		);

		$this->add_field(
			'track_internal_as_outbound',
			__( 'Set path for internal links to track as outbound links', 'google-analytics-for-wordpress' ),
			'text',
			array(
				'key'  => 'track_internal_as_outbound',
				'help' => sprintf( __( 'If you want to track all internal links that begin with %1$s, enter %1$s in the box above. If you have multiple prefixes you can separate them with comma\'s: %2$s', 'google-analytics-for-wordpress' ), '<code>/out/</code>', '<code>/out/,/recommends/</code>' ),
			)
		);

		$this->add_field(
			'track_internal_as_label',
			__( 'Label for those links', 'google-analytics-for-wordpress' ),
			'text',
			array(
				'key'  => 'track_internal_as_outbound',
				'help' => __( 'The label to use for these links, this will be added to where the click came from, so if the label is "aff", the label for a click from the content of an article becomes "outbound-article-aff".', 'google-analytics-for-wordpress' ),
			)
		);

		$this->add_field(
			'tag_links_in_rss',
			__( 'Tag links in RSS feed with campaign variables', 'google-analytics-for-wordpress' ),
			'checkbox',
			array(
				'key'  => 'tag_links_in_rss',
				'help' => __( 'Do not use this feature if you use FeedBurner, as FeedBurner can do this automatically and better than this plugin can. Check <a href="https://support.google.com/feedburner/answer/165769?hl=en&amp;ref_topic=13075" target="_blank">this help page</a> for info on how to enable this feature in FeedBurner.', 'google-analytics-for-wordpress' ),
			)
		);

		$this->add_field(
			'allow_anchor',
			__( 'Tag links in RSS feed with campaign variables', 'google-analytics-for-wordpress' ),
			'checkbox',
			array(
				'key'  => 'allow_anchor',
				'help' => sprintf( __( 'This adds a %1$s call to your tracking code, and makes RSS link tagging use a %2$s as well.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiCampaignTracking?csw=1#_gat.GA_Tracker_._setAllowAnchor" target="_blank"><code>_setAllowAnchor</code></a>', '<code>#</code>' ),
			)
		);

		$this->add_field(
			'add_allow_linker',
			__( 'Add <code>_setAllowLinker</code>', 'google-analytics-for-wordpress' ),
			'checkbox',
			array(
				'key'  => 'add_allow_linker',
				'help' => sprintf( __( 'This adds a %1$s call to your tracking code, allowing you to use %2$s and related functions.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiDomainDirectory?csw=1#_gat.GA_Tracker_._setAllowLinker" target="_blank"><code>_setAllowLinker</code></a>', ' <code>_link</code>' ),
			)
		);

		$this->add_field(
			'custom_code',
			__( 'Custom code', 'google-analytics-for-wordpress' ),
			'textarea',
			array(
				'key'  => 'custom_code',
				'help' => sprintf( __( 'Not for the average user: this allows you to add a line of code, to be added before the %1$s call.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiBasicConfiguration#_gat.GA_Tracker_._trackPageview" target="_blank"><code>_trackPageview</code></a>' ),
			)
		);

	}

	/**
	 * Init the debug tab
	 */
	public function yst_ga_settings_init_debug() {
		register_setting( $this->settings_api_page . '_debug', 'yst_ga' );

		$this->create_section(
			'debug'
		);

		$this->add_field(
			'debug_mode',
			__( 'Enable debug mode', 'google-analytics-for-wordpress' ),
			'checkbox',
			array(
				'key'  => 'debug_mode',
				'help' => __( 'Not recommended, as this would skew your statistics, but it does make it possible to track downloads as goals.', 'google-analytics-for-wordpress' ),
			)
		);
	}

	/**
	 * Set the default options, for now, it is in the admin class (Needs to be hooked at admin_init)
	 */
	public function init_default_options() {
		$this->default_options = array(
			'tracking_code'        => $this->get_tracking_code(),
			'user_roles'           => $this->get_userroles(),
			'track_download_types' => $this->track_download_types(),
			'track_full_url'       => $this->get_track_full_url(),
			'analytics_profile'    => $this->get_profiles(),
		);
	}

	/**
	 * Create a new settings section
	 *
	 * @param $tab
	 */
	private function create_section( $tab ) {
		$this->current_section = $tab;

		add_settings_section(
			'yst_ga_settings_api_' . $tab,
			'',
			'',
			$this->settings_api_page . '_' . $tab
		);
	}

	/**
	 * Add a settings field
	 *
	 * @param $id
	 * @param $title
	 * @param $type
	 * @param $args
	 */
	private function add_field( $id, $title, $type, $args ) {
		add_settings_field(
			'yst_ga_' . $id,
			$title,
			array( 'Yoast_GA_Admin_Settings_Fields', 'yst_ga_' . $type . '_field' ),
			$this->settings_api_page . '_' . $this->current_section,
			$this->settings_api_page . '_' . $this->current_section,
			$args
		);
	}

}