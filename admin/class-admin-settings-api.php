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
	}

	/**
	 * Init the general settings tab
	 */
	public function init_yst_ga_settings_general() {
		register_setting( 'yst_ga_settings_form', 'yst_ga_settings' );

		$this->add_field( array(
			'name'        => 'track_outbound',
			'title'       => __( 'Track outbound click and downloads', 'google-analytics-for-wordpress' ),
			'description' => 'The help icon description',
			'value'       => 123,
			'type'        => 'checkbox',
		) );
		$this->add_field( array(
			'name'        => 'anonymous_data',
			'title'       => __( 'Allow tracking of anonymous data', 'google-analytics-for-wordpress' ),
			'description' => 'The help icon description',
			'value'       => 123,
			'type'        => 'checkbox',
		) );

		$this->build_settings_section( __( 'General settings', 'google-analytics-for-wordpress' ) );
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
		echo $this->show_help( 'id-' . $args['name'], $args['description'] ) . '<input type="checkbox" name="yst_ga_settings[' . $args['name'] . ']" value="1" ' . checked( $args['value'], 1 ) . '>';
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
	 * @param string $title
	 */
	private function build_settings_section( $title ) {
		add_settings_section(
			'yst_ga_settings_form_section',
			$title,
			null,
			'yst_ga_settings_form'
		);

		foreach ( $this->fields as $field ) {
			add_settings_field(
				$field['name'],
				$field['title'] . ':',
				array( $this, 'yst_ga_form_' . $field['type'] . '_field' ),
				'yst_ga_settings_form',
				'yst_ga_settings_form_section',
				$field
			);
		}
	}

}