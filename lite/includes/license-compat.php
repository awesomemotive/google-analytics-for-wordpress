<?php
/**
 * This class is used to prevent fatal errors in legacy code
 * that others have written based on testing we've done.
 *
 * @package MonsterInsights
 */

/**
 * Class MonsterInsights_License_Compat
 */
class MonsterInsights_License_Compat {

	/**
	 * MonsterInsights_License_Shim constructor.
	 */
	public function __construct() {}

	/**
	 * @return string
	 */
	public function get_license_type() {
		return 'lite';
	}

	/**
	 * @return string
	 */
	public function get_site_license_type() {
		return '';
	}

	/**
	 * @return string
	 */
	public function get_site_license_key() {
		return '';
	}

	/**
	 * @return string
	 */
	public function get_network_license_type() {
		return '';
	}

	/**
	 * @return string
	 */
	public function get_network_license_key() {
		return '';
	}

	/**
	 * @return string
	 */
	public function get_license_key() {
		return '';
	}

}
