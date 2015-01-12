<?php

class Yoast_GA_Utils_Test extends GA_UnitTestCase {

	/**
	 * Test the difference output from hours_between functionality
	 */
	public function test_hours_between() {
		$diff  = 4;
		$start = strtotime( '-' . $diff . ' hours' );
		$end   = time();

		$this->assertEquals( Yoast_GA_Utils::hours_between( $start, $end ), $diff );
	}

}