<?php
/**
 * This is the frontend class for the GA Universal code
 */

if ( ! class_exists( 'Yoast_GA_Universal' ) ) {

	class Yoast_GA_Universal extends Yoast_GA_Frontend {

		public function __construct() {

		}

	}

	global $yoast_ga_universal;
	$yoast_ga_universal = new Yoast_GA_Universal;
}