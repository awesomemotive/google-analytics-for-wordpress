<?php
/**
 * This class is the backend ga.js class, extends the basic admin class
 */

if ( ! class_exists( 'Yoast_GA_Admin_GA_JS' ) ) {

	class Yoast_GA_Admin_GA_JS extends Yoast_GA_Admin {

		public function __construct() {

		}

	}

	global $yoast_ga_admin_ga_js;
	$yoast_ga_admin_ga_js = new Yoast_GA_Admin_GA_JS;
}