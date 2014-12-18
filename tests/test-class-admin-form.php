<?php

class Yoast_GA_Admin_Form_Test extends GA_UnitTestCase {

	/**
	 * Create a form, receives the html output
	 *
	 * @covers Yoast_GA_Admin_Form::create_form()
	 */
	public function test_create_form() {
		$action = admin_url( 'admin.php' );

		$this->assertEquals( Yoast_GA_Admin_Form::create_form( 'phpunit' ), '<form action="' . $action . '" method="post" id="yoast-ga-form-phpunit" class="yoast_ga_form">' . wp_nonce_field( 'save_settings', 'yoast_ga_nonce', null, false ) );
	}

	/**
	 * End a form, receives the html output
	 *
	 * @covers Yoast_GA_Admin_Form::end_form()
	 */
	public function test_end_form() {
		Yoast_GA_Admin_Form::create_form( 'phpunit' );

		$output = null;
		$output .= '<div class="ga-form ga-form-input">';
		$output .= '<input type="submit" name="ga-form-submit" value="Save changes" class="button button-primary ga-form-submit" id="yoast-ga-form-submit-phpunit">';
		$output .= '</div></form>';

		$this->assertEquals( Yoast_GA_Admin_Form::end_form(), $output );
	}

	/**
	 * Create a test input field
	 *
	 * @covers Yoast_GA_Admin_Form:input()
	 */
	public function test_input() {
		Yoast_GA_Admin_Form::create_form( 'phpunit' );

		$output = null;
		$output .= '<div class="ga-form ga-form-input">';
		$output .= '<input type="text" id="yoast-ga-form-text-phpunit-" name="" class="ga-form ga-form-text " value="" />';
		$output .= '</div>';

		$this->assertEquals( Yoast_GA_Admin_Form::input( 'text' ), $output );
	}

}