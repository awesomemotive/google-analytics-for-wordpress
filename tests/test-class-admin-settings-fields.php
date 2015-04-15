<?php

class Yoast_GA_Admin_Form_Fields_Test extends GA_UnitTestCase {

	/**
	 * @covers Yoast_GA_Admin_Settings_Fields::yst_ga_text_field
	 */
	public function test_yst_ga_text_field_WITH_no_value() {
		$args     = array(
			'key'       => 'test-field',
			'label_for' => 'ga_form_test-field',
		);
		$expected = '<input type="text" name="yst_ga[ga_general][' . $args['key'] . ']" id="' . $args['label_for'] . '" value="" class="ga-form-text">';

		$this->assertEquals( $expected, $this->helper_field_output( 'yst_ga_text_field', $args ) );
	}

	/**
	 * @covers Yoast_GA_Admin_Settings_Fields::yst_ga_textarea_field
	 */
	public function test_yst_ga_textarea_field_WITH_no_value() {
		$args     = array(
			'key'       => 'test-field',
			'label_for' => 'ga_form_test-field',
		);
		$expected = '<textarea name="yst_ga[ga_general][' . $args['key'] . ']" id="' . $args['label_for'] . '" rows="5" cols="60"></textarea>';

		$this->assertEquals( $expected, $this->helper_field_output( 'yst_ga_textarea_field', $args ) );
	}

	/**
	 * @covers Yoast_GA_Admin_Settings_Fields::yst_ga_checkbox_field
	 */
	public function test_yst_ga_checkbox_field_WITH_no_value() {
		$args     = array(
			'key'       => 'test-field',
			'label_for' => 'ga_form_test-field',
		);
		$expected = '<input type="checkbox" name="yst_ga[ga_general][' . $args['key'] . ']" id="' . $args['label_for'] . '" value="1" >';

		$this->assertEquals( $expected, $this->helper_field_output( 'yst_ga_checkbox_field', $args ) );
	}

	/**
	 * Helper function to catch the fields when they're getting generated.
	 *
	 * @param $method
	 * @param $args
	 *
	 * @return mixed
	 */
	private function helper_field_output( $method, $args ) {
		ob_start();
		Yoast_GA_Admin_Settings_Fields::$method( $args );
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

}