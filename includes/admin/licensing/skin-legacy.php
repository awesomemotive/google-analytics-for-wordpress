<?php
/**
 * Skin class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage  Upgrader Skin
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MonsterInsights_Skin extends WP_Upgrader_Skin {

	/**
	 * Primary class constructor.
	 *
	 * @param array $args Empty array of args (we will use defaults).
	 *
	 * @since 6.0.0
	 *
	 */
	public function __construct( $args = array() ) {

		parent::__construct();

	}

	/**
	 * Set the upgrader object and store it as a property in the parent class.
	 *
	 * @param object $upgrader The upgrader object (passed by reference).
	 *
	 * @since 6.0.0
	 *
	 */
	public function set_upgrader( &$upgrader ) {

		if ( is_object( $upgrader ) ) {
			$this->upgrader =& $upgrader;
		}

	}

	/**
	 * Set the upgrader result and store it as a property in the parent class.
	 *
	 * @param object $result The result of the install process.
	 *
	 * @since 6.0.0
	 *
	 */
	public function set_result( $result ) {

		$this->result = $result;

	}

	/**
	 * Empty out the header of its HTML content and only check to see if it has
	 * been performed or not.
	 *
	 * @since 6.0.0
	 */
	public function header() {
	}

	/**
	 * Empty out the footer of its HTML contents.
	 *
	 * @since 6.0.0
	 */
	function footer() {
	}

	/**
	 * Instead of outputting HTML for errors, json_encode the errors and send them
	 * back to the Ajax script for processing.
	 *
	 * @param array $errors Array of errors with the install process.
	 *
	 * @since 6.0.0
	 *
	 */
	function error( $errors ) {
		if ( ! empty( $errors ) ) {
			// Translators: Support link tag starts with url and Support link tag ends.
			$error_message = sprintf(
				esc_html__( 'There was an error installing the addon. Please try again. If you are still having issues, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'error-installing-addons', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			if ( is_wp_error( $errors ) ) {
				/**
				 * @var WP_Error $errors
				 */
				$message = $errors->get_error_message();

				if ( ! empty( $message ) ) {
					// Translators: The name of the addon that can't be installed, Support link tag starts with url and Support link tag ends.
					$error_message = sprintf(
						esc_html__( 'There was an error installing the addon, %1$s. Please try again. If you are still having issues, please %2$scontact our support%3$s team. ', 'google-analytics-for-wordpress' ),
						esc_html( $message ),
						'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'error-installing-addons', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
						'</a>'
					);
				}
			}

			wp_send_json( array( 'error' => $error_message ) );
		}

	}

	/**
	 * Empty out the feedback method to prevent outputting HTML strings as the install
	 * is progressing.
	 *
	 * @param string $string The feedback string.
	 *
	 * @since 6.0.0
	 *
	 */
	function feedback( $string ) {

	}

}
