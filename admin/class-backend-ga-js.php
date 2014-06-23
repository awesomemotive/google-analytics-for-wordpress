<?php
/**
 * This class is the backend ga.js class, extends the basic admin class
 */

if( !class_exists('Yoast_GA_Backend_GA_JS') ){

	class Yoast_GA_Backend_GA_JS extends Yoast_GA_Admin {

		public function __construct(){

		}

	}

	$Yoast_GA_Backend_GA_JS	=	new Yoast_GA_Backend_GA_JS;
}