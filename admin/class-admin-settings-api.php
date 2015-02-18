<?php

/**
 * This class is for options/settings in the admin forms
 */
class Yoast_GA_Admin_Settings_API {

	private $fields = array();

	/**
	 * Construct the new admin settings api forms
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init_yst_ga_settings_general' ) );
		add_action( 'admin_init', array( $this, 'init_yst_ga_settings_universal' ) );
		add_action( 'admin_init', array( $this, 'init_yst_ga_settings_advanced' ) );
		add_action( 'admin_init', array( $this, 'init_yst_ga_settings_debug' ) );
	}

	/**
	 * Init the general settings tab
	 */
	public function init_yst_ga_settings_general() {
		$this->register_setting( 'yst_ga_settings_form_general', 'yst_ga_settings' );

		$this->add_field( array(
			'name'        => 'track_outbound',
			'title'       => __( 'Track outbound click and downloads', 'google-analytics-for-wordpress' ),
			'description' => __( 'Clicks and downloads will be tracked as events, you can find these under Content &#xBB; Event Tracking in your Google Analytics reports.', 'google-analytics-for-wordpress' ),
			'value'       => 1,
			'type'        => 'checkbox',
		) );
		$this->add_field( array(
			'name'        => 'anonymous_data',
			'title'       => __( 'Allow tracking of anonymous data', 'google-analytics-for-wordpress' ),
			'description' => __( 'By allowing us to track anonymous data we can better help you, because we know with which WordPress configurations, themes and plugins we should test. No personal data will be submitted.', 'google-analytics-for-wordpress' ),
			'value'       => 1,
			'type'        => 'checkbox',
		) );
		$this->add_field( array(
			'name'        => 'anonymize_ips',
			'title'       => __( 'Anonymize IPs', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'This adds %1$s, telling Google Analytics to anonymize the information sent by the tracker objects by removing the last octet of the IP address prior to its storage.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApi_gat?csw=1#_gat._anonymizeIp" target="_blank"><code>_anonymizeIp</code></a>' ),
			'value'       => 1,
			'type'        => 'checkbox',
		) );
		$this->add_field( array(
			'name'        => 'ignore_users',
			'title'       => __( 'Ignore users', 'google-analytics-for-wordpress' ),
			'description' => __( 'Users of the role you select will be ignored, so if you select Editor, all Editors will be ignored.', 'google-analytics-for-wordpress' ),
			'value'       => 1,
			'type'        => 'checkbox',
		) );
		$this->add_field( array(
			'name'        => 'dashboards_disabled',
			'title'       => __( 'Disable analytics dashboard', 'google-analytics-for-wordpress' ),
			'description' => __( 'This will completely disable the dashboard and stop the plugin from fetching the latest analytics data.', 'google-analytics-for-wordpress' ),
			'value'       => 1,
			'type'        => 'checkbox',
		) );

		$this->build_settings_section( 'general', __( 'General settings', 'google-analytics-for-wordpress' ) );
	}

	/**
	 * Init the universal settings tab
	 */
	public function init_yst_ga_settings_universal() {
		$this->register_setting( 'yst_ga_settings_form_universal', 'yst_ga_settings' );

		$this->add_field( array(
			'name'        => 'enable_universal',
			'title'       => __( 'Enable Universal tracking', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'First enable Universal tracking in your Google Analytics account. Please read %1$sthis guide%2$s to learn how to do that.', 'google-analytics-for-wordpress' ), '<a href="http://kb.yoast.com/article/125-universal-analytics#utm_medium=kb-link&utm_source=gawp-config&utm_campaign=wpgaplugin" target="_blank">', '</a>' ),
			'value'       => 1,
			'type'        => 'checkbox',
		) );
		$this->add_field( array(
			'name'        => 'demographics',
			'title'       => __( 'Enable Demographics and Interest Reports', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'You have to enable the Demographics in Google Analytics before you can see the tracking data. We have a knowledge base article in our %1$sknowledge base%2$s about this feature.', 'google-analytics-for-wordpress' ), '<a href="http://kb.yoast.com/article/154-enable-demographics-and-interests-report-in-google-analytics/#utm_medium=kb-link&amp;utm_source=gawp-config&amp;utm_campaign=wpgaplugin" target="_blank">', '</a>' ),
			'value'       => 1,
			'type'        => 'checkbox',
		) );
		$this->add_field( array(
			'name'        => 'enhanced_link_attribution',
			'title'       => __( 'Enhanced Link Attribution', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'Add %1$sEnhanced Link Attribution%2$s to your tracking code.', 'google-analytics-for-wordpress' ), '<a href="https://support.google.com/analytics/answer/2558867" target="_blank">', ' </a>' ),
			'value'       => 1,
			'type'        => 'checkbox',
		) );

		$this->build_settings_section( 'universal', __( 'Universal settings', 'google-analytics-for-wordpress' ) );
	}

	/**
	 * Build the advanced tab
	 */
	public function init_yst_ga_settings_advanced() {
		$this->register_setting( 'yst_ga_settings_form_advanced', 'yst_ga_settings' );

		$this->add_field( array(
			'name'        => 'enable_universal',
			'title'       => __( 'Enable Universal tracking', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'First enable Universal tracking in your Google Analytics account. Please read %1$sthis guide%2$s to learn how to do that.', 'google-analytics-for-wordpress' ), '<a href="http://kb.yoast.com/article/125-universal-analytics#utm_medium=kb-link&utm_source=gawp-config&utm_campaign=wpgaplugin" target="_blank">', '</a>' ),
			'value'       => 1,
			'type'        => 'checkbox',
		) );

		$this->build_settings_section( 'advanced', __( 'Advanced settings', 'google-analytics-for-wordpress' ) );
	}

	/**
	 * Build the debug tab
	 */
	public function init_yst_ga_settings_debug() {
		$this->register_setting( 'yst_ga_settings_form_debug', 'yst_ga_settings' );

		$this->add_field( array(
			'name'        => 'enable_universal',
			'title'       => __( 'Enable Universal tracking', 'google-analytics-for-wordpress' ),
			'description' => sprintf( __( 'First enable Universal tracking in your Google Analytics account. Please read %1$sthis guide%2$s to learn how to do that.', 'google-analytics-for-wordpress' ), '<a href="http://kb.yoast.com/article/125-universal-analytics#utm_medium=kb-link&utm_source=gawp-config&utm_campaign=wpgaplugin" target="_blank">', '</a>' ),
			'value'       => 1,
			'type'        => 'checkbox',
		) );

		$this->build_settings_section( 'debug', __( 'Debug settings', 'google-analytics-for-wordpress' ) );
	}

	/**
	 * Render a text field
	 *
	 * @param array $args
	 */
	public function yst_ga_form_text_field( $args ) {
		echo $this->show_help( 'id-' . $args['name'], $args['description'] ) . '<input type="text" name="yst_ga_settings[' . $args['name'] . ']" value="' . $args['value'] . '">';
	}

	/**
	 * Render an input field
	 *
	 * @param $args
	 */
	public function yst_ga_form_checkbox_field( $args ) {
		echo $this->show_help( 'id-' . $args['name'], $args['description'] ) . '<input type="checkbox" name="yst_ga_settings[' . $args['name'] . ']" value="1" ' . checked( $args['value'], 1, false ) . '>';
	}

	/**
	 * Register new setting and reset the form fields
	 *
	 * @param $id
	 * @param $name
	 */
	private function register_setting( $id, $name ) {
		$this->fields = array();

		register_setting( $id, $name );
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
		$help = '<img src="' . plugins_url( 'assets/img/question-mark.png', GAWP_FILE ) . '" class="alignleft yoast_help" id="' . esc_attr( $id . 'help' ) . '" alt="' . esc_attr( $description ) . '" />';

		return $help;
	}

	/**
	 * Add a field to the fields array
	 *
	 * @param $args
	 */
	private function add_field( $args ) {
		$this->fields[] = $args;
	}

	/**
	 * Build the settings section from the given fields
	 *
	 * @param string $tab
	 * @param string $title
	 */
	private function build_settings_section( $tab, $title ) {
		add_settings_section(
			'yst_ga_settings_form_section',
			$title,
			null,
			'yst_ga_settings_form_' . $tab
		);

		foreach ( $this->fields as $field ) {
			add_settings_field(
				$field['name'],
				$field['title'] . ':',
				array( $this, 'yst_ga_form_' . $field['type'] . '_field' ),
				'yst_ga_settings_form_' . $tab,
				'yst_ga_settings_form_section',
				$field
			);
		}
	}

}