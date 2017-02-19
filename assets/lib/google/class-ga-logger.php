<?php
/**
 * Future logger that will log to `monsterinsights_logs`
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class MonsterInsights_GA_Logger extends MonsterInsights_GA_Lib_Logger_Abstract {
	
	/**
	 * {@inheritdoc}
	 */
	public function shouldHandle( $level ) {
		return true; // always log errors
	}

	/**
	 * {@inheritdoc}
	 */
	protected function write( $message ) {
		// @todo log to option in future
	}
}