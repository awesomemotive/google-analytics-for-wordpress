<?php

class Yoast_GA_Settings {

	/**
	 * Saving instance of it's own in this static var
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * The main GA options
	 *
	 * @var
	 */
	private $options;

	/**
	 * Set the options of Google Analytics
	 */
	public function __construct() {
		$this->options = Yoast_GA_Options::instance()->get_options();
	}

	/**
	 * Getting instance of this object. If instance doesn't exists it will be created.
	 *
	 * @return object|Yoast_GA_Settings
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Yoast_GA_Settings();
		}

		return self::$instance;
	}

	/**
	 * Dashboards disabled
	 *
	 * @return bool
	 */
	public function dashboards_disabled() {
		if ( isset( $this->options['dashboards_disabled'] ) && $this->options['dashboards_disabled'] == 1 ){
			return true;
		}

		return false;
	}

}