<?php
/**
 * Support Tab.
 *
 * @since 6.1.0
 *
 * @package MonsterInsights
 * @subpackage Settings
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Callback for displaying the UI for support tab.
 *
 * @since 6.1.0
 * @access public
 *
 * @return void
 */
function monsterinsights_settings_support_tab() {
    ?>
    <div id="monsterinsights-settings-general">
        <?php 
        // Output any notices now
        do_action( 'monsterinsights_settings_support_tab_notice' );
        ?>
        <?php //Status page coming soon. ?>
        <!-- <hr /> -->
    </div>
    <?php
}
// add_action( 'monsterinsights_tab_settings_support', 'monsterinsights_settings_support_tab' );

/**
 * Callback for saving the general settings tab.
 *
 * @since 6.1.0
 * @access public
 *
 * @return void
 */
function monsterinsights_settings_save_support() {

}
// add_action( 'monsterinsights_settings_save_support', 'monsterinsights_settings_save_support' );