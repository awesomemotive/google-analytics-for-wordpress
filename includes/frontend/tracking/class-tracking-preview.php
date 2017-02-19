<?php
/**
 * Tracking debug class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class MonsterInsights_Tracking_Preview extends MonsterInsights_Tracking_Abstract {

    /**
     * Holds the base class object.
     *
     * @since 6.0.0
     * @access public
     *
     * @var object $base Base class object.
     */
    public $base;
    
    /**
     * Holds the name of the tracking type.
     *
     * @since 6.0.0
     * @access public
     *
     * @var string $name Name of the tracking type.
     */
    public $name = 'preview';

    /**
     * Version of the tracking class.
     *
     * @since 6.0.0
     * @access public
     *
     * @var string $version Version of the tracking class.
     */
    public $version = '1.0.0';

    /**
     * Primary class constructor.
     *
     * @since 6.0.0
     * @access public
     */
    public function __construct() {
        $this->base = MonsterInsights();
    }

    /**
     * Get frontend tracking options.
     *
     * This function is used to return an array of parameters
     * for the frontend_output() function to output. These are 
     * generally dimensions and turned on GA features.
     *
     * @since 6.0.0
     * @access public
     *
     * @return array Array of the options to use.
     */
    public function frontend_tracking_options( ) {
        return array();
    }

    /**
     * Get frontend output.
     *
     * This function is used to return the Javascript
     * to output in the head of the page for the given
     * tracking method.
     *
     * @since 6.0.0
     * @access public
     *
     * @return string Javascript to output.
     */
    public function frontend_output( ) {
        $output  = '<!-- This site uses the Google Analytics by MonsterInsights plugin v ' . MONSTERINSIGHTS_VERSION .' - https://www.monsterinsights.com/ -->';
        $output .=  '<!-- ' . esc_html__( "You are currently in a preview window. MonsterInsights doesn't track preview window traffic to avoid false visit reports.", 'google-analytics-for-wordpress' ) . ' -->';
        $output .=  '<!-- / Google Analytics by MonsterInsights -->';
        return $output;
    }
}