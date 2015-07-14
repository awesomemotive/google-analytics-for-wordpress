<?php

class Yoast_Api_Googleanalytics_Test extends GA_UnitTestCase {

	/**
	 * Test the autoload functionality
	 */
	public function test_autoload(){
		$this->assertTrue( class_exists( 'Yoast_Googleanalytics_Reporting' ) );
	}

}