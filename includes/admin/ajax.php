<?php
/**
 * Handles all admin ajax interactions for the MonsterInsights plugin.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Ajax
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Stores a user setting for the logged-in WordPress User
 *
 * @access public
 * @since 6.0.0
 */
function monsterinsights_ajax_set_user_setting() {

    // Run a security check first.
    check_ajax_referer( 'monsterinsights-set-user-setting', 'nonce' );

    // Prepare variables.
    $name    = stripslashes( $_POST['name'] );
    $value   = stripslashes( $_POST['value'] );

    // Set user setting.
    set_user_setting( $name, $value );

    // Send back the response.
    wp_send_json_success();
    wp_die();

}
add_action( 'wp_ajax_monsterinsights_install_addon', 'monsterinsights_ajax_install_addon' );

/**
 * Installs a MonsterInsights addon.
 *
 * @access public
 * @since 6.0.0
 */
function monsterinsights_ajax_install_addon() {

    // Run a security check first.
    check_ajax_referer( 'monsterinsights-install', 'nonce' );

    if ( ! current_user_can( 'install_plugins' ) ) {
        echo json_encode( true );
    }

    // Install the addon.
    if ( isset( $_POST['plugin'] ) ) {
        $download_url = $_POST['plugin'];
        global $hook_suffix;

        // Set the current screen to avoid undefined notices.
        set_current_screen();

        // Prepare variables.
        $method = '';
        $url    = add_query_arg(
            array(
                'page' => 'monsterinsights-settings'
            ),
            admin_url( 'admin.php' )
        );
        $url = esc_url( $url );

        // Start output bufferring to catch the filesystem form if credentials are needed.
        ob_start();
        if ( false === ( $creds = request_filesystem_credentials( $url, $method, false, false, null ) ) ) {
            $form = ob_get_clean();
            echo json_encode( array( 'form' => $form ) );
            wp_die();
        }

        // If we are not authenticated, make it happen now.
        if ( ! WP_Filesystem( $creds ) ) {
            ob_start();
            request_filesystem_credentials( $url, $method, true, false, null );
            $form = ob_get_clean();
            echo json_encode( array( 'form' => $form ) );
            wp_die();
        }

        // We do not need any extra credentials if we have gotten this far, so let's install the plugin.
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        $base = MonsterInsights();
        require_once plugin_dir_path( $base->file ) . '/includes/admin/licensing/skin.php';

        // Create the plugin upgrader with our custom skin.
        $installer = new Plugin_Upgrader( $skin = new MonsterInsights_Skin() );
        $installer->install( $download_url );

        // Flush the cache and return the newly installed plugin basename.
        wp_cache_flush();
        if ( $installer->plugin_info() ) {
            $plugin_basename = $installer->plugin_info();
            echo json_encode( array( 'plugin' => $plugin_basename ) );
            wp_die();
        }
    }

    // Send back a response.
    echo json_encode( true );
    wp_die();

}

add_action( 'wp_ajax_monsterinsights_activate_addon', 'monsterinsights_ajax_activate_addon' );
/**
 * Activates a MonsterInsights addon.
 *
 * @access public
 * @since 6.0.0
 */
function monsterinsights_ajax_activate_addon() {

    // Run a security check first.
    check_ajax_referer( 'monsterinsights-activate', 'nonce' );

    if ( ! current_user_can( 'activate_plugins' ) ) {
        echo json_encode( true );
    }

    // Activate the addon.
    if ( isset( $_POST['plugin'] ) ) {
        if ( isset( $_POST['isnetwork'] ) &&  $_POST['isnetwork'] ) {
            $activate = activate_plugin( $_POST['plugin'], NULL, true );
        } else {
            $activate = activate_plugin( $_POST['plugin'] );
        }

        if ( is_wp_error( $activate ) ) {
            echo json_encode( array( 'error' => $activate->get_error_message() ) );
            wp_die();
        }
    }

    echo json_encode( true );
    wp_die();

}

add_action( 'wp_ajax_monsterinsights_deactivate_addon', 'monsterinsights_ajax_deactivate_addon' );
/**
 * Deactivates a MonsterInsights addon.
 *
 * @access public
 * @since 6.0.0
 */
function monsterinsights_ajax_deactivate_addon() {

    // Run a security check first.
    check_ajax_referer( 'monsterinsights-deactivate', 'nonce' );

    if ( ! current_user_can( 'activate_plugins' ) ) {
        echo json_encode( true );
    }

    // Deactivate the addon.
    if ( isset( $_POST['plugin'] ) ) {
        if ( isset( $_POST['isnetwork'] ) && $_POST['isnetwork'] ) {
            $deactivate = deactivate_plugins( $_POST['plugin'], false, true );
        } else {
            $deactivate = deactivate_plugins( $_POST['plugin'] );
        }
    }

    echo json_encode( true );
    wp_die();
}

/**
 * Called whenever a notice is dismissed in MonsterInsights or its Addons.
 *
 * Updates a key's value in the options table to mark the notice as dismissed,
 * preventing it from displaying again
 *
 * @access public
 * @since 6.0.0
 */
function monsterinsights_ajax_dismiss_notice() {

    // Run a security check first.
    check_ajax_referer( 'monsterinsights-dismiss-notice', 'nonce' );

    // Deactivate the notice
    if ( isset( $_POST['notice'] ) ) {
        // Init the notice class and mark notice as deactivated
        MonsterInsights()->notices->dismiss( $_POST['notice'] );

        // Return true
        echo json_encode( true );
        wp_die();
    }

    // If here, an error occurred
    echo json_encode( false );
    wp_die();

}
add_action( 'wp_ajax_monsterinsights_ajax_dismiss_notice', 'monsterinsights_ajax_dismiss_notice' );