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

	/**
	 * Create a test select field
	 *
	 * @covers Yoast_GA_Admin_Form:select()
	 */
	public function test_select() {
		Yoast_GA_Admin_Form::create_form( 'phpunit' );

		$title    = 'Test select';
		$name     = 'test_select';
		$values   = array();
		$values[] = array( 'id' => 1, 'name' => 'PHP Unit' );
		$values[] = array( 'id' => 2, 'name' => 'Tests' );

		$output = null;
		$output .= '<div class="ga-form ga-form-input"><label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-label-select-phpunit-test_select" />';
		$output .= 'Test select:</label><select data-placeholder="" name="test_select" id="yoast-ga-form-select-phpunit-test_select">';
		$output .= '<option value="1" >PHP Unit</option><option value="2" >Tests</option></select></div>';

		$this->assertEquals( Yoast_GA_Admin_Form::select( $title, $name, $values ), $output );
	}

}