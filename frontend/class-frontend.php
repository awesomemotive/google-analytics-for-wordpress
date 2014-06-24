<?php
/**
 * The basic frontend class for the GA plugin, extendable for the children
 */

if( !class_exists('Yoast_GA_Frontend') ){

	class Yoast_GA_Frontend {

		public function __construct(){

		}

	}

	global $yoast_ga_frontend;
	$yoast_ga_frontend	=	new Yoast_GA_Frontend;
}