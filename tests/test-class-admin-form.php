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
		$output .= '<input type="submit" name="ga-form-submit" value="Save changes" class="button button-primary ga-form-submit" id="yoast-ga-form-submit-phpunit" />';
		$output .= '</div></form>';

		$this->assertEquals( Yoast_GA_Admin_Form::end_form(), $output );
	}

	/**
	 * End a form, receives the HTML output with an onclick action
	 */
	public function test_end_form_WITH_onclick() {
		Yoast_GA_Admin_Form::create_form( 'phpunit' );

		$output = null;
		$output .= '<div class="ga-form ga-form-input">';
		$output .= '<input type="submit" name="ga-form-submit" value="Save changes" class="button button-primary ga-form-submit" id="yoast-ga-form-submit-phpunit" onclick="test();" />';
		$output .= '</div></form>';

		$this->assertEquals( Yoast_GA_Admin_Form::end_form('Save changes', 'submit', 'test();'), $output );
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
		$output .= '<div class="ga-form ga-form-input"><label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-label-select-phpunit-' . $name . '">';
		$output .= $title . ':</label><select data-placeholder="" name="' . $name . '" id="yoast-ga-form-select-phpunit-' . $name . '">';
		$output .= '<option value="1" >PHP Unit</option><option value="2" >Tests</option></select></div>';

		$this->assertEquals( Yoast_GA_Admin_Form::select( $title, $name, $values ), $output );
	}

	/**
	 * Create a test textarea field
	 *
	 * @covers Yoast_GA_Admin_Form:select()
	 */
	public function test_textarea() {
		Yoast_GA_Admin_Form::create_form( 'phpunit' );

		$title  = 'Test textarea';
		$name   = 'test_textarea';
		$output = null;
		$output .= '<div class="ga-form ga-form-input"><label class="ga-form ga-form-select-label ga-form-label-left" id="yoast-ga-form-label-select-phpunit-ga_general_' . $name . '">';
		$output .= $title . ':</label><textarea rows="5" cols="60" name="' . $name . '" id="yoast-ga-form-textarea-phpunit-ga_general_' . $name . '"></textarea></div>';

		$this->assertEquals( Yoast_GA_Admin_Form::textarea( $title, $name ), $output );
	}

	/**
	 * Render a help icon
	 *
	 * @covers Yoast_GA_Admin_Form::show_help()
	 */
	public function test_show_help() {
		$id          = 'test_id';
		$description = 'Test id description';
		$output      = null;
		$output .= '<img src="' . GAWP_URL . 'assets/img/question-mark.png" class="alignleft yoast_help" id="' . $id . 'help" alt="' . $description . '" />';
		
		$this->assertEquals( Yoast_GA_Admin_Form::show_help( $id, $description ), $output );

	}

	/**
	 * Test parse optgroups function
	 *
	 * @covers Yoast_GA_Admin_Form::parse_optgroups()
	 */
	public function test_parse_optgroups() {
		$optgroups   = array();
		$optgroups[] = array(
			'name'  => 'Name group 1',
			'items' => array( array(
				'name'  => 'Name_group_1',
				'items' => array( array(
					'id'   => 1,
					'name' => 'Profile 1'
				) ),
			) ),
		);
		$optgroups[] = array(
			'name'  => 'Name group 1',
			'items' => array( array(
				'name'  => 'Name_group_2',
				'items' => array( array(
					'id'   => 2,
					'name' => 'Profile 2'
				) ),
			) ),
		);
		$result = Yoast_GA_Admin_Form::parse_optgroups( $optgroups );
		$output = array(
			'Name_group_1'	=>	array(
				'items'	=>	array(
					0	=>	array(
						'id'	=>	1,
						'name'	=> 'Profile 1',
					),
				),
			),
			'Name_group_2'	=>	array(
				'items'	=>	array(
					0	=>	array(
						'id'	=>	2,
						'name'	=> 'Profile 2',
					),
				),),
		);

		$this->assertEquals( $result, $output );
	}

}