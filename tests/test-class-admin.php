<?php

class Yoast_GA_Admin_Test extends GA_UnitTestCase {

	/**
	 * @covers Yoast_GA_Admin->init_ga()
	 */
	public function test_init_ga() {
		$this->go_to_home();
		$this->assertTrue( true );
	}

	/**
	 * @covers Yoast_GA_Admin->add_aciton_links()
	 */
	public function test_add_action_links() {
		$links = array();

		$this->assertTrue( true );

	}

}