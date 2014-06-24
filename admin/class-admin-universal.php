<?php
/**
 * This class is the backend universal class, extends the basic admin class
 */

if ( ! class_exists( 'Yoast_GA_Admin_Universal' ) ) {

	class Yoast_GA_Admin_Universal extends Yoast_GA_Admin {

		public function __construct() {

		}

	}

	global $yoast_ga_admin_universal;
	$yoast_ga_admin_universal = new Yoast_GA_Admin_Universal;
}