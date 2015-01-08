<?php

class Output_Test extends GA_UnitTestCase {

	/**
	 * Create a new post and check if we get some correct output in
	 * the WP_Head. If there is an fatal error there should be no
	 * output in here.
	 */
	public function test_frontend_head() {
		$post_id = $this->factory->post->create();
		$this->go_to( get_permalink( $post_id ) );

		$regex = '/(link|meta)/i';
		$this->expectOutputRegex( $regex );
		do_action( 'wp_head' );
	}

	/**
	 * Run the admin test
	 */
	public function test_admin() {
		$this->go_to( get_admin_url( 'admin.php?page=yst_ga_settings' ) );

		$regex = '/(link|meta|script)/i';
		$this->expectOutputRegex( $regex );
		do_action( 'wp_head' );
		do_action( 'admin_enqueue_scripts' );
	}

}