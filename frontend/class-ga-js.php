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
			$options  = parent::$options['ga_general'];
			$gaq_push = array();
			$hide_js  = false;

			// Set tracking code here
			if ( ! empty( $options['manual_ua_code_field'] ) ) {
				$gaq_push['_setAccount'] = $options['manual_ua_code_field'];
			}

			// Anonymous data
			if ( $options['anonymize_ips'] == 1 ) {
				$gaq_push['_gat._anonymizeIp'] = NULL;
			}
			$gaq_push['_trackPageview'] = NULL;

			// Include the tracking view
			require( GAWP_PATH . 'frontend/views/tracking_ga_js.php' );
		}

	}

	global $yoast_ga_js;
	$yoast_ga_js = new Yoast_GA_JS;
}