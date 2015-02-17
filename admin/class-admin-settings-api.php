<?php

/**
 * This class is for options/settings in the admin forms
 */
class Yoast_GA_Admin_Settings_API {

	/**
	 * Construct the new admin settings api forms
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init_yst_ga_settings_general' ) );
	}

	/**
	 * Init the general settings tab
	 */
	public function init_yst_ga_settings_general() {
		register_setting( 'yst_ga_settings_form', 'yst_ga_settings' );

		add_settings_section(
			'yst_ga_settings_form_section',
			__( 'General settings', 'google-analytics-for-wordpress' ),
			null,
			'yst_ga_settings_form'
		);

		/**
		 * Dummy field
		 */
		add_settings_field(
			'yst_ga_text_field_0',
			__( 'Settings field description', 'test' ),
			array( $this, 'yst_ga_form_text_field' ),
			'yst_ga_settings_form',
			'yst_ga_settings_form_section',
			array( 'name' => 'name', 'value' => 'value', 'description' => 'Full description with some help' )
		);

		add_settings_field(
			'track_outbound',
			__( 'Track outbound click and downloads', 'google-analytics-for-wordpress' ) . ':',
			array( $this, 'yst_ga_form_input_field' ),
			'yst_ga_settings_form',
			'yst_ga_settings_form_section',
			array( 'name' => 'name', 'value' => 'value', 'description' => 'Full description with some help' )
		);
		add_settings_field(
			'anonymous_data',
			__( 'Allow tracking of anonymous data', 'google-analytics-for-wordpress' ) . ':',
			array( $this, 'yst_ga_form_input_field' ),
			'yst_ga_settings_form',
			'yst_ga_settings_form_section',
			array( 'name' => 'name', 'value' => 'value', 'description' => 'Full description with some help' )
		);
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
	public function yst_ga_form_input_field( $args ) {
		echo $this->show_help( 'id-' . $args['name'], $args['description'] ) . '<input type="checkbox" name="yst_ga_settings[' . $args['name'] . ']" value="1" ' . checked($args['value'], 1 ) . '>';
	}

	/**
	 * Show a question mark with help
	 *
	 * @param string $id
	 * @param string $description
	 *
	 * @return string
	 */
	public function show_help( $id, $description ) {
		$help = '<img src="' . plugins_url( 'assets/img/question-mark.png', GAWP_FILE ) . '" class="alignleft yoast_help" id="' . esc_attr( $id . 'help' ) . '" alt="' . esc_attr( $description ) . '" />';

		return $help;
	}

}