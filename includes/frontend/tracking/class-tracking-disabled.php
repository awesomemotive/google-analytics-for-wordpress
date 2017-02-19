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

class MonsterInsights_Tracking_Disabled extends MonsterInsights_Tracking_Abstract {

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
    public $name = 'disabled';

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
        $output  = PHP_EOL . '<!-- This site uses the Google Analytics by MonsterInsights plugin v ' . MONSTERINSIGHTS_VERSION .' - https://www.monsterinsights.com/ -->' . PHP_EOL;

        if ( current_user_can( 'monsterinsights_save_settings' ) ) {
            $output .=  '<!-- ' . sprintf( esc_html__( '@Webmaster, normally you will find the Google Analytics tracking code here, but you are in the disabled user groups. To change this, navigate to Insights %1$s Settings %1$s Ignore Users', 'google-analytics-for-wordpress' ), '->' ) . ' -->' . PHP_EOL;
        } else {
            $output .=  '<!-- ' . esc_html__( 'Normally you will find the Google Analytics tracking code here, but the webmaster disabled your user group.', 'google-analytics-for-wordpress' ) . ' -->' . PHP_EOL;
        }
        $output .=  '<!-- / Google Analytics by MonsterInsights -->' . PHP_EOL . PHP_EOL;
        return $output;
    }
}