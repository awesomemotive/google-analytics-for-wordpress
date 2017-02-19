<?php
/**
 * Status Tab.
 *
 * @since 6.1.0
 *
 * @package MonsterInsights
 * @subpackage Settings
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Callback for displaying the UI for support tab.
 *
 * @since 6.1.0
 * @access public
 *
 * @return void
 */
function monsterinsights_settings_status_tab() {
    ?>
    <div id="monsterinsights-settings-general">
        <?php 
        ?>
        <?php //Status page coming soon. ?>
        <!-- <hr /> -->
    </div>
    <?php
}
add_action( 'monsterinsights_tab_settings_status', 'monsterinsights_settings_status_tab' );


/**
 * Output System Info file
 */
function monsterinsights_system_info() {
    if ( ! empty( $_REQUEST['monsterinsights-action'] ) && $_REQUEST['monsterinsights-action'] === 'download_sysinfo' ) {
        if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
            return;
        }
        nocache_headers();
        header( 'Content-Type: text/plain' );
        header( 'Content-Disposition: attachment; filename="monsterinsights-system-info.txt"' );
        echo wp_strip_all_tags( $_POST['monsterinsights-sysinfo'] );
        die();
    }
}
//add_action( 'admin_init',  'monsterinsights_system_info'  );