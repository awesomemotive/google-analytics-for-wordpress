<?php
/**
 * The frontend JS class
 */

if ( ! class_exists( 'Yoast_GA_JS' ) ) {

	class Yoast_GA_JS extends Yoast_GA_Frontend {

		public function __construct() {
			add_action( 'wp_head', array( $this, 'tracking' ) );
		}

		/**
		 * Function to output the GA Tracking code in the wp_head()
		 *
		 * @todo, add the tracking code and remove this test output
		 */
		public function tracking() {
			// Set tracking code here

			// Include the tracking view
			require( GAWP_PATH . 'frontend/views/tracking_ga_js.php' );
		}

	}

	global $yoast_ga_js;
	$yoast_ga_js = new Yoast_GA_JS;
}