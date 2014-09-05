<?php

class Yoast_GA_Admin_Test extends GA_UnitTestCase {


	public function test_init_ga(){

		$this->go_to_home();
		$this->assertTrue( true );

	}

}