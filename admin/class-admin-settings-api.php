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
			__( 'Your section description', 'test' ),
			array( $this, 'yst_ga_settings_section_callback' ),
			'yst_ga_settings_form'
		);

		add_settings_field(
			'yst_ga_text_field_0',
			__( 'Settings field description', 'test' ),
			array( $this, 'yst_ga_form_text_field' ),
			'yst_ga_settings_form',
			'yst_ga_settings_form_section',
			array( 'name' => 'name', 'value' => 'value' )
		);
	}

	/**
	 * Render a text field
	 *
	 * @param array $args
	 */
	public function yst_ga_form_text_field( $args ) {
		echo '<input type="text" name="yst_ga_settings[' . $args['name'] . ']" value="' . $args['value'] . '">';
	}

	/**
	 * Shows a description
	 */
	public function yst_ga_settings_section_callback() {
		echo __( 'This section description', 'test' );
	}

}