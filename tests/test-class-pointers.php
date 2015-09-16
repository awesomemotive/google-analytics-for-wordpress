<?php

class Yoast_GA_Pointers_Test extends GA_UnitTestCase {



	/**
	 * Checks if function intro_tour is not called when the tour is ignored.
	 *
	 * @covers Yoast_GA_Pointers::__construct
	 */
	public function test_ga_ignore_tour_IS_TRUE() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );

		$old_user_id = get_current_user_id();

		update_user_meta( $user_id, 'ga_ignore_tour', '1' );
		wp_set_current_user( $user_id );

		new Yoast_GA_Pointers();

		$has_script = wp_script_is( 'yoast_ga_pointer' );

		$this->assertFalse( $has_script );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * Checks if yoast_ga_pointer is called when the tour is not ignored.
	 *
	 * @covers Yoast_GA_Pointers::__construct
	 */
	public function test_ga_ignore_tour_IS_FALSE() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );

		$old_user_id = get_current_user_id();

		wp_set_current_user ($user_id);

		new Yoast_GA_Pointers();

		// Login user
		wp_set_current_user ($user_id);

		$has_script = wp_script_is( 'yoast_ga_pointer' );

		$this->assertTrue( $has_script );

		// Set current user back to old user id
		wp_set_current_user( $old_user_id );
	}

}
