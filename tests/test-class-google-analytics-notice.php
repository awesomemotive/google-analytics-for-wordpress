<?php

class Yoast_Google_Analytics_Notice_Test extends GA_UnitTestCase {

	/**
	 * Test a part of the config warning to make sure we have one
	 */
	public function test_config_warning() {
		ob_start();
		Yoast_Google_Analytics_Notice::config_warning();
		$output   = ob_get_clean();
		$expected = '<div class="error"><p>Please configure your <a href="' . admin_url( 'admin.php?page=yst_ga_settings' ) . '">Google Analytics settings</a>!</p></div>';

		$this->assertEquals( $output, $expected );
	}

	/**
	 * Test the warning fetching data to make sure we have one
	 */
	public function test_warning_fetching_data() {
		ob_start();
		Yoast_Google_Analytics_Notice::warning_fetching_data();
		$output   = ob_get_clean();
		$expected = '<div class="error"><p>Failed to fetch the new data from Google Analytics. This might be caused by a problem with the Google service.</p></div>';

		$this->assertEquals( $output, $expected );
	}

	/**
	 * Test the warning fetching data to make sure we have one
	 */
	public function test_warning_fetching_data_authenticate() {
		ob_start();
		Yoast_Google_Analytics_Notice::warning_fetching_data_authenticate();
		$output   = ob_get_clean();
		$expected = '<div class="error"><p>Failed to fetch the new data from Google Analytics. You might need to <a href="' . admin_url( 'admin.php?page=yst_ga_settings' ) . '">reauthenticate</a>.</p></div>';

		$this->assertEquals( $output, $expected );
	}


}