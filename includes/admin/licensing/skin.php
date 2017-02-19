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
     * @since 6.0.0
     *
     * @param array $args Empty array of args (we will use defaults).
     */
    public function __construct( $args = array() ) {

        parent::__construct();

    }

    /**
     * Set the upgrader object and store it as a property in the parent class.
     *
     * @since 6.0.0
     *
     * @param object $upgrader The upgrader object (passed by reference).
     */
    public function set_upgrader( &$upgrader ) {

        if ( is_object( $upgrader ) ) {
            $this->upgrader =& $upgrader;
        }

    }

    /**
     * Set the upgrader result and store it as a property in the parent class.
     *
     * @since 6.0.0
     *
     * @param object $result The result of the install process.
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
    public function header() {}

    /**
     * Empty out the footer of its HTML contents.
     *
     * @since 6.0.0
     */
    function footer() {}

    /**
     * Instead of outputting HTML for errors, json_encode the errors and send them
     * back to the Ajax script for processing.
     *
     * @since 6.0.0
     *
     * @param array $errors Array of errors with the install process.
     */
    function error( $errors ) {

        if ( ! empty( $errors ) ) {
            echo json_encode( array( 'error' => esc_html__( 'There was an error installing the addon. Please try again.', 'google-analytics-for-wordpress' ) ) );
            die;
        }

    }

    /**
     * Empty out the feedback method to prevent outputting HTML strings as the install
     * is progressing.
     *
     * @since 6.0.0
     *
     * @param string $string The feedback string.
     */
    function feedback( $string ) {
        
    }

}