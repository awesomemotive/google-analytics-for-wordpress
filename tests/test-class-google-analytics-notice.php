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
		$expected = '<div class="error"><p>Data is not up-to-date, there was an error in retrieving the data from Google Analytics. This error could be caused by several issues. If the error persists, please see <a href="http://yoa.st/2p">this page</a>.</p></div>';

		$this->assertEquals( $output, $expected );
	}

	/**
	 * Test the warning fetching data to make sure we have one
	 */
	public function test_warning_fetching_data_authenticate() {
		ob_start();
		Yoast_Google_Analytics_Notice::warning_fetching_data_authenticate();
		$output   = ob_get_clean();
		$expected = '<div class="error"><p>It seems the authentication for the plugin has expired, please <a href="' . admin_url( 'admin.php?page=yst_ga_settings' ) . '">re-authenticate</a> with Google Analytics to allow the plugin to fetch data.</p></div>';

		$this->assertEquals( $output, $expected );
	}


}