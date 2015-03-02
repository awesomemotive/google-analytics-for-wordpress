<?php

/**
 * This class is for options/settings in the admin forms
 */
class Yoast_GA_Admin_Settings_API {

	/**
	 * Temporary storage of the fields created for a specific form section
	 *
	 * @var array
	 */
	private $fields = array();

	/**
	 * The website GA settings
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * @var array
	 */
	private $defualt_options = array();

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
		//add_action( 'admin_init', array( $this, 'init_yst_ga_settings_general_tracking' ) );
		add_action( 'admin_init', array( $this, 'init_yst_ga_settings_general' ) );
		//add_action( 'admin_init', array( $this, 'init_yst_ga_settings_universal' ) );
		//add_action( 'admin_init', array( $this, 'init_yst_ga_settings_advanced' ) );
		//add_action( 'admin_init', array( $this, 'init_yst_ga_settings_debug' ) );

		$this->settings = Yoast_GA_Options::instance()->get_options();
	}

	/**
	 * Set the default options, for now, it is in the admin class
	 */
	public function init_default_options() {
		global $yoast_ga_admin;

		$this->defualt_options = array(
			'tracking_code'        => $yoast_ga_admin->get_tracking_code(),
			'user_roles'           => $yoast_ga_admin->get_userroles(),
			'track_download_types' => $yoast_ga_admin->track_download_types(),
			'track_full_url'       => $yoast_ga_admin->get_track_full_url(),
		);
	}

	/**
	 * Init the general settings tab
	 */
	public function init_yst_ga_settings_general_tracking() {
		$fields = array();
		$fields[] = array(
			'name'        => 'manual_ua',
			'title'       => __( 'Track outbound click and downloads', 'google-analytics-for-wordpress' ),
			'description' => __( 'Clicks and downloads will be tracked as events, you can find these under Content &#xBB; Event Tracking in your Google Analytics reports.', 'google-analytics-for-wordpress' ),
			'type'        => 'checkbox',
		);

		$this->register_settings( 'yst_ga_settings_form_general_tracking', $fields );
	}

	/**
	 * Init the general settings tab
	 */
	public function init_yst_ga_settings_general() {
		$fields = array();
		$fields[] = array(
			'name'        => 'track_outbound',
			'title'       => __( 'Track outbound click and downloads', 'google-analytics-for-wordpress' ),
			'description' => __( 'Clicks and downloads will be tracked as events, you can find these under Content &#xBB; Event Tracking in your Google Analytics reports.', 'google-analytics-for-wordpress' ),
			'type'        => 'checkbox',
		);
		$fields[] = array(
			'name'        => 'anonymous_data',
			'title'       => __( 'Allow tracking of anonymous data', 'google-analytics-for-wordpress' ),
			'description' => __( 'By allowing us to track anonymous data we can better help you, because we know with which WordPress configurations, themes and plugins we should test. No personal data will be submitted.', 'google-analytics-for-wordpress' ),
			'type'        => 'checkbox',
		);
		$fields[] = array(
			'name'        => 'anonymize_ips',
			'title'       => __( 'Anonymize IPs', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'This adds %1$s, telling Google Analytics to anonymize the information sent by the tracker objects by removing the last octet of the IP address prior to its storage.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApi_gat?csw=1#_gat._anonymizeIp" target="_blank"><code>_anonymizeIp</code></a>' ),
			'type'        => 'checkbox',
		);
		$fields[] = array(
			'name'        => 'ignore_users',
			'title'       => __( 'Ignore users', 'google-analytics-for-wordpress' ),
			'description' => __( 'Users of the role you select will be ignored, so if you select Editor, all Editors will be ignored.', 'google-analytics-for-wordpress' ),
			'type'        => 'select',
			'class'       => 'chosen',
			'attributes'  => ' multiple="true"',
			'options'     => $this->defualt_options['user_roles'],
		);
		$fields[] = array(
			'name'        => 'dashboards_disabled',
			'title'       => __( 'Disable analytics dashboard', 'google-analytics-for-wordpress' ),
			'description' => __( 'This will completely disable the dashboard and stop the plugin from fetching the latest analytics data.', 'google-analytics-for-wordpress' ),
			'type'        => 'checkbox',
		);
		$this->register_settings( 'yst_ga_settings_form_general', $fields );

		$this->build_settings_section( 'general', __( 'General settings', 'google-analytics-for-wordpress' ) );
	}

	/**
	 * Init the universal settings tab
	 */
	public function init_yst_ga_settings_universal() {
		$fields = array();

		$fields[] = array(
			'name'        => 'enable_universal',
			'title'       => __( 'Enable Universal tracking', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'First enable Universal tracking in your Google Analytics account. Please read %1$sthis guide%2$s to learn how to do that.', 'google-analytics-for-wordpress' ), '<a href="http://kb.yoast.com/article/125-universal-analytics#utm_medium=kb-link&utm_source=gawp-config&utm_campaign=wpgaplugin" target="_blank">', '</a>' ),
			'type'        => 'checkbox',
		);
		$fields[] = array(
			'name'        => 'enhanced_link_attribution',
			'title'       => __( 'Enhanced Link Attribution', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'Add %1$sEnhanced Link Attribution%2$s to your tracking code.', 'google-analytics-for-wordpress' ), '<a href="https://support.google.com/analytics/answer/2558867" target="_blank">', ' </a>' ),
			'type'        => 'checkbox',
		);
		$fields[] = array(
			'name'        => 'demographics',
			'title'       => __( 'Enable Demographics and Interest Reports', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'You have to enable the Demographics in Google Analytics before you can see the tracking data. We have a knowledge base article in our %1$sknowledge base%2$s about this feature.', 'google-analytics-for-wordpress' ), '<a href="http://kb.yoast.com/article/154-enable-demographics-and-interests-report-in-google-analytics/#utm_medium=kb-link&amp;utm_source=gawp-config&amp;utm_campaign=wpgaplugin" target="_blank">', '</a>' ),
			'type'        => 'checkbox',
		);
		$this->register_settings( 'yst_ga_settings_form_universal', $fields );

		$this->build_settings_section( 'universal', __( 'Universal settings', 'google-analytics-for-wordpress' ) );
	}

	/**
	 * Build the advanced tab
	 */
	public function init_yst_ga_settings_advanced() {
		$fields = array();
		$fields[] = array(
			'name'        => 'track_download_as',
			'title'       => __( 'Track downloads as', 'google-analytics-for-wordpress' ),
			'description' => __( 'Not recommended, as this would skew your statistics, but it does make it possible to track downloads as goals.', 'google-analytics-for-wordpress' ),
			'type'        => 'select',
			'options'     => $this->defualt_options['track_download_types'],
		);
		$fields[] = array(
			'name'        => 'extensions_of_files',
			'title'       => __( 'Extensions of files to track as downloads', 'google-analytics-for-wordpress' ),
			'description' => __( 'Please separate extensions using commas', 'google-analytics-for-wordpress' ),
			'type'        => 'text',
		);
		$fields[] = array(
			'name'        => 'track_full_url',
			'title'       => __( 'Track full URL of outbound clicks or just the domain', 'google-analytics-for-wordpress' ),
			'description' => null,
			'type'        => 'select',
			'options'     => $this->defualt_options['track_full_url'],
		);
		$fields[] = array(
			'name'        => 'subdomain_tracking',
			'title'       => __( 'Subdomain tracking', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'This allows you to set the domain that\'s set by %1$s for tracking subdomains.<br/>If empty, this will not be set.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiDomainDirectory#_gat.GA_Tracker_._setDomainName" target="_blank"><code>_setDomainName</code></a>' ),
			'type'        => 'text',
		);
		$fields[] = array(
			'name'        => 'track_internal_as_outbound',
			'title'       => __( 'Set path for internal links to track as outbound links', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'If you want to track all internal links that begin with %1$s, enter %1$s in the box above. If you have multiple prefixes you can separate them with comma\'s: %2$s', 'google-analytics-for-wordpress' ), '<code>/out/</code>', '<code>/out/,/recommends/</code>' ),
			'type'        => 'text',
		);
		$fields[] = array(
			'name'        => 'track_internal_as_label',
			'title'       => __( 'Label for those links', 'google-analytics-for-wordpress' ),
			'description' => __( 'The label to use for these links, this will be added to where the click came from, so if the label is "aff", the label for a click from the content of an article becomes "outbound-article-aff".', 'google-analytics-for-wordpress' ),
			'type'        => 'text',
		);
		$fields[] = array(
			'name'        => 'tag_links_in_rss',
			'title'       => __( 'Tag links in RSS feed with campaign variables', 'google-analytics-for-wordpress' ),
			'description' => __( 'Do not use this feature if you use FeedBurner, as FeedBurner can do this automatically and better than this plugin can. Check <a href="https://support.google.com/feedburner/answer/165769?hl=en&amp;ref_topic=13075" target="_blank">this help page</a> for info on how to enable this feature in FeedBurner.', 'google-analytics-for-wordpress' ),
			'type'        => 'checkbox',
		);
		$fields[] = array(
			'name'        => 'allow_anchor',
			'title'       => __( 'Allow anchor', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'This adds a %1$s call to your tracking code, and makes RSS link tagging use a %2$s as well.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiCampaignTracking?csw=1#_gat.GA_Tracker_._setAllowAnchor" target="_blank"><code>_setAllowAnchor</code></a>', '<code>#</code>' ),
			'type'        => 'checkbox',
		);
		$fields[] = array(
			'name'        => 'add_allow_linker',
			'title'       => __( 'Add <code>_setAllowLinker</code>', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'This adds a %1$s call to your tracking code, allowing you to use %2$s and related functions.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiDomainDirectory?csw=1#_gat.GA_Tracker_._setAllowLinker" target="_blank"><code>_setAllowLinker</code></a>', ' <code>_link</code>' ),
			'type'        => 'checkbox',
		);
		$fields[] = array(
			'name'        => 'custom_code',
			'title'       => __( 'Custom code', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'Not for the average user: this allows you to add a line of code, to be added before the %1$s call.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiBasicConfiguration#_gat.GA_Tracker_._trackPageview" target="_blank"><code>_trackPageview</code></a>' ),
			'type'        => 'textarea',
		);
		$this->register_settings( 'yst_ga_settings_form_advanced', $fields );

		$this->build_settings_section( 'advanced', __( 'Advanced settings', 'google-analytics-for-wordpress' ) );
	}

	/**
	 * Build the debug tab
	 */
	public function init_yst_ga_settings_debug() {
		$fields   = array();
		$fields[] = array(
			'name'        => 'debug_mode',
			'title'       => __( 'Enable debug mode', 'google-analytics-for-wordpress' ),
			'description' => null,
			'type'        => 'checkbox',
		);
		$this->register_settings( 'yst_ga_settings_form_debug', $fields );

		$this->build_settings_section( 'debug', __( 'Debug settings', 'google-analytics-for-wordpress' ) );
	}

	/**
	 * Render a text field
	 *
	 * @param array $args
	 */
	public function yst_ga_form_text_field( $args ) {
		echo $this->show_help( 'id-' . $args['name'], $args['description'] ) . '<input type="text" name="' . $args['name'] . '" value="' . $args['value'] . '" style="width: 60%;">';
	}

	/**
	 * Render an input field
	 *
	 * @param $args
	 */
	public function yst_ga_form_checkbox_field( $args ) {
		echo $this->show_help( 'id-' . $args['name'], $args['description'] ) . '<input type="checkbox" name="' . $args['name'] . '" value="1" ' . checked( $args['value'], 1, false ) . '>';
	}

	/**
	 * Render a textarea field
	 *
	 * @param $args
	 */
	public function yst_ga_form_textarea_field( $args ) {
		echo $this->show_help( 'id-' . $args['name'], $args['description'] ) . '<textarea name="' . $args['name'] . '" rows="5" style="width: 60%;">' . $args['value'] . '</textarea>';
	}

	/**
	 * Render a select field
	 *
	 * @param $args
	 */
	public function yst_ga_form_select_field( $args ) {
		$options    = null;
		$class      = null;
		$attributes = null;

		if ( isset( $args['class'] ) ) {
			$class = ' class="' . $args['class'] . '"';
		}

		if ( isset( $args['attributes'] ) ) {
			$attributes = $args['attributes'];
		}

		foreach ( $args['options'] as $option ) {
			if ( is_array( $args['value'] ) ) {
				if ( in_array( $option['id'], $args['value'] ) ) {
					$options .= '<option value="' . $option['id'] . '" selected="selected">' . $option['name'] . '</option>';
					continue;
				}

				$options .= '<option value="' . $option['id'] . '">' . $option['name'] . '</option>';
				continue;
			}

			$options .= '<option value="' . $option['id'] . '" ' . selected( $option['id'], $args['value'], false ) . '>' . $option['name'] . '</option>';
		}

		echo $this->show_help( 'id-' . $args['name'], $args['description'] ) . '<select name="' . $args['name'] . '"' . $class . $attributes . '>' . $options . '</select>';
	}

	/**
	 * Register new settings and reset the form fields
	 *
	 * @param $group
	 * @param $fields
	 */
	private function register_settings( $group, $fields ) {
		$this->fields = $fields;
	}

	/**
	 * Show a question mark with help
	 *
	 * @param string $id
	 * @param string $description
	 *
	 * @return string
	 */
	private function show_help( $id, $description ) {
		if ( is_null( $description ) ) {
			return;
		}

		$help = '<img src="' . plugins_url( 'assets/img/question-mark.png', GAWP_FILE ) . '" class="alignleft yoast_help" id="' . esc_attr( $id . 'help' ) . '" alt="' . esc_attr( $description ) . '" />';

		return $help;
	}

	/**
	 * Build the settings section from the given fields
	 *
	 * @param string $tab
	 * @param string $title
	 */
	private function build_settings_section( $tab, $title ) {
		add_settings_section(
			'yst_ga_settings_form_' . $tab,
			$title,
			null,
			$this->settings_api_page
		);

		foreach ( $this->fields as $field ) {
			if ( isset( $this->settings[$field['name']] ) ) {
				$field['value'] = $this->settings[$field['name']];
			}

			add_settings_field(
				$field['name'], // ID
				$field['title'] . ':',
				array( $this, 'yst_ga_form_' . $field['type'] . '_field' ),
				$this->settings_api_page,
				'yst_ga_settings_form_' . $tab,
				$field
			);

			register_setting( 'yst_ga_settings_form_' . $tab, $field['name'] );
		}
	}

}