<?php

class Yoast_GA_Dashboards_Data_Test extends GA_UnitTestCase {

	/**
	 * Set some test data
	 */
	public function test_set_AND_get_data() {
		$dataobject = array(
			'object' => array(
				'test' => 'Value!',
			),
		);
		$type       = 'phpunit_test_values';
		$start_date = strtotime( '-7 days' );
		$end_date   = time();
		$store_as   = 'table';

		// Set the data object
		$set = Yoast_GA_Dashboards_Data::set( $type, $dataobject, $start_date, $end_date, $store_as );
		$this->assertTrue( $set );

		// Get the data object
		$result   = Yoast_GA_Dashboards_Data::get( $type );
		$expected = array(
			'store_as'   => $store_as,
			'type'       => $type,
			'start_date' => $start_date,
			'end_date'   => $end_date,
			'value'      => $dataobject
		);

		$this->assertEquals( $result, $expected );
	}

}