<?php
/**
 * Settings class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Settings
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function monsterinsights_is_settings_page() {
    $current_screen = get_current_screen();
    global $admin_page_hooks;
   
    if ( ! is_object( $current_screen ) || empty( $current_screen->id ) || empty( $admin_page_hooks ) ) {
        return false;
    }

    $settings_page = false;
    if ( ! empty( $admin_page_hooks['monsterinsights_dashboard'] ) && $current_screen->id === $admin_page_hooks['monsterinsights_dashboard'] . '_page_monsterinsights_settings' ) {
        $settings_page = true;
    }

    if ( $current_screen->id === 'toplevel_page_monsterinsights_settings' ) {
        $settings_page = true;
    }

    return $settings_page;
}


/**
 * Callback to output the MonsterInsights settings page.
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_settings_page() {
    /** 
     * Developer Alert:
     *
     * Per the README, this is considered an internal hook and should
     * not be used by other developers. This hook's behavior may be modified
     * or the hook may be removed at any time, without warning.
     */
    do_action( 'monsterinsights_head' );
    ?>
    <?php echo monsterinsights_ublock_notice(); ?>

    <!-- Tabs -->
    <h1 id="monsterinsights-settings-page-main-nav" class="monsterinsights-main-nav-container monsterinsights-nav-container" data-container="#monsterinsights-settings-pages">
        <a class="monsterinsights-main-nav-item monsterinsights-nav-item monsterinsights-spacing-item" href="#">&nbsp;</a>

        <a class="monsterinsights-main-nav-item monsterinsights-nav-item monsterinsights-active" href="#monsterinsights-main-tab-general" title="<?php echo esc_attr( __( 'General', 'google-analytics-for-wordpress' ) ); ?>">
            <?php echo esc_html__( 'General', 'google-analytics-for-wordpress' ); ?>
        </a>

        <a class="monsterinsights-main-nav-item monsterinsights-nav-item" href="#monsterinsights-main-tab-tracking" title="<?php echo esc_attr( __( 'Tracking', 'google-analytics-for-wordpress' ) ); ?>">
            <?php echo esc_html__( 'Tracking', 'google-analytics-for-wordpress' ); ?>
        </a>
        <!--
        <a class="monsterinsights-main-nav-item monsterinsights-nav-item" href="#monsterinsights-main-tab-status" title="<?php //echo esc_attr( __( 'Status', 'google-analytics-for-wordpress' ) ); ?>">
            <?php //echo esc_html__( 'Status', 'google-analytics-for-wordpress' ); ?>
        </a>

        <a class="monsterinsights-main-nav-item monsterinsights-nav-item" href="#monsterinsights-main-tab-support" title="<?php //echo esc_attr( __( 'Support', 'google-analytics-for-wordpress' ) ); ?>">
            <?php //echo esc_html__( 'Support', 'google-analytics-for-wordpress' ); ?>
        </a>
         -->
    </h1>


    <!-- Tab Panels -->
    <div id="monsterinsights-settings-pages" class="monsterinsights-main-nav-tabs monsterinsights-nav-tabs wrap" data-navigation="#monsterinsights-settings-page-main-nav">
        <h1 class="monsterinsights-hideme"></h1><!-- so wp notices are below the nav bar -->
         <div id="monsterinsights-main-tab-general" class="monsterinsights-main-nav-tab monsterinsights-nav-tab monsterinsights-active">
            <?php monsterinsights_settings_general_tab(); ?>
        </div>
         <div id="monsterinsights-main-tab-tracking" class="monsterinsights-main-nav-tab monsterinsights-nav-tab">
            <?php monsterinsights_settings_tracking_tab(); ?>
        </div>
        <!--
         <div id="monsterinsights-main-tab-status" class="monsterinsights-main-nav-tab monsterinsights-nav-tab">
            <?php //monsterinsights_settings_status_tab(); ?>
        </div>
         <div id="monsterinsights-main-tab-support" class="monsterinsights-main-nav-tab monsterinsights-nav-tab">
            <?php //monsterinsights_settings_support_tab(); ?>
        </div>
        -->
    </div>
    <?php
}

/**
 * Saves Settings
 *
 * @since 6.0.0
 * @access public
 *
 * @return null Return early if not fixing the broken migration
 */
function monsterinsights_save_general_settings_page() {

    if ( ! monsterinsights_is_settings_page() ) {
        return;
    }

    // Check if user pressed the 'Update' button and nonce is valid
    if ( ! isset( $_POST['monsterinsights-settings-submit'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['monsterinsights-settings-nonce'], 'monsterinsights-settings-nonce' ) ) {
        return;
    }

    if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
        return;
    }

    if ( ! empty( $_POST['monsterinsights_settings_tab'] ) && $_POST['monsterinsights_settings_tab'] === 'general' ) {
        /** 
         * Developer Alert:
         *
         * Per the README, this is considered an internal hook and should
         * not be used by other developers. This hook's behavior may be modified
         * or the hook may be removed at any time, without warning.
         */
        do_action( 'monsterinsights_settings_save_general' );
    }
}
add_action( 'current_screen', 'monsterinsights_save_general_settings_page' );

/**
 * Outputs a WordPress style notification to tell the user settings were saved
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_updated_settings() {
    echo monsterinsights_get_message( 'success', esc_html__( 'Settings saved successfully.', 'google-analytics-for-wordpress' ) );   
}

/**
 * Outputs a WordPress style notification to tell the user their UA code was bad.
 *
 * @since 6.0.3
 * @access public
 *
 * @return void
 */
function monsterinsights_invalid_ua_code() {
    echo monsterinsights_get_message( 'error', esc_html__( 'Invalid UA code.', 'google-analytics-for-wordpress' ) );   
}

/**
 * Outputs a checkbox for settings. 
 *
 * Do not use this in other plugins. We may remove this at any time
 * without forwarning and without consideration for backwards compatibility.
 *
 * This is to be considered a private function, for MonsterInsights use only.
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_make_checkbox( $option_id, $title = '', $description = '' ) {
    $option_value = monsterinsights_get_option( $option_id, 0 );
    $option_class = str_replace( '_', '-', $option_id );
    ob_start();
    ?>
    <tr id="monsterinsights-input-<?php echo esc_attr( $option_class ); ?>">
        <?php if ( !empty ( $title ) ) { ?>
        <th scope="row">
            <label for="monsterinsights-<?php echo esc_attr( $option_class ); ?>"><?php echo $title; ?></label>
        </th>
         <?php } ?>
        <td>
            <input type="checkbox" id="monsterinsights-<?php echo esc_attr( $option_class ); ?>" name="<?php echo esc_attr( $option_id ); ?>" <?php checked( $option_value, 1 ); ?> />
            <?php if ( ! empty ( $description ) ) { ?>
            <p class="description">
                <?php echo $description; ?>
            </p>
            <?php } ?>
        </td>
    </tr>
    <?php
    $input_field = ob_get_contents();
    ob_end_clean();
    return $input_field;
}

function monsterinsights_switch_to_analyticsjs() {
    $nonce = '';
    if ( ! empty( $_REQUEST['monsterinsights-switch-to-analyticsjs-nonce'] ) ) {
        $nonce = 'monsterinsights-switch-to-analyticsjs-nonce';
    } else if ( ! empty( $_REQUEST['_wpnonce'] ) ) {
        $nonce = '_wpnonce';
    }
    
    if ( empty( $nonce ) ) {
         return;
    }
    
    if ( ! wp_verify_nonce( $_REQUEST[$nonce], 'monsterinsights-switch-to-analyticsjs-nonce' ) ) {
        return;
    }

    if ( empty( $_REQUEST['monsterinsights-action'] ) || $_REQUEST['monsterinsights-action'] !== 'switch_to_analyticsjs' ) {
        return;
    }

    if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
        wp_die( esc_html__( 'You do not have permission to manage MonsterInsights settings', 'google-analytics-for-wordpress' ), esc_html__( 'Error', 'google-analytics-for-wordpress' ), array( 'response' => 403 ) );
    }

    $return       = '';
    if ( ! empty( $_REQUEST['return'] ) && monsterinsights_is_settings_tab( $_REQUEST['return'] ) ) {
        $return = admin_url( 'admin.php?page=monsterinsights_settings&monsterinsights-message=tracking_mode_switched#monsterinsights-main-tab-tracking?monsterinsights-sub-tab-') . $_REQUEST['return'];
        $return = add_query_arg( 'return', $_REQUEST['return'], $return );
    } else {
        $return = admin_url( 'admin.php?page=monsterinsights_settings&monsterinsights-message=tracking_mode_switched');
    }
    monsterinsights_update_option( 'tracking_mode', 'analytics' );
    wp_safe_redirect( $return );exit;
}
add_action( 'admin_init', 'monsterinsights_switch_to_analyticsjs',9 );

function monsterinsights_switched_to_analyticsjs() {
    echo monsterinsights_get_message( 'success', esc_html__( 'Successfully migrated to Universal Analytics (analytics.js)!', 'google-analytics-for-wordpress' ) );
}

function monsterinsights_switch_to_analyticsjs_show_notice() {
    if ( empty( $_REQUEST['monsterinsights-message'] ) || $_REQUEST['monsterinsights-message'] !== 'tracking_mode_switched' ) {
        return;
    }
    
    if ( ! empty( $_REQUEST['return'] ) && monsterinsights_is_settings_tab( $_REQUEST['return'] ) ) {
        add_action( 'monsterinsights_tracking_' . $_REQUEST['return'] . '_tab_notice', 'monsterinsights_switched_to_analyticsjs' );
    } else {
        add_action( 'monsterinsights_settings_general_tab_notice', 'monsterinsights_switched_to_analyticsjs' );
    }
}
add_action( 'admin_init', 'monsterinsights_switch_to_analyticsjs_show_notice', 11 ); 



function monsterinsights_switch_to_jsevents() {
    $nonce = '';
    if ( ! empty( $_REQUEST['monsterinsights-switch-to-jsevents-nonce'] ) ) {
        $nonce = 'monsterinsights-switch-to-jsevents-nonce';
    } else if ( ! empty( $_REQUEST['_wpnonce'] ) ) {
        $nonce = '_wpnonce';
    }
    
    if ( empty( $nonce ) ) {
         return;
    }
    
    if ( ! wp_verify_nonce( $_REQUEST[$nonce], 'monsterinsights-switch-to-jsevents-nonce' ) ) {
        return;
    }

    if ( empty( $_REQUEST['monsterinsights-action'] ) || $_REQUEST['monsterinsights-action'] !== 'switch_to_jsevents' ) {
        return;
    }

    if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
        wp_die( esc_html__( 'You do not have permission to manage MonsterInsights settings', 'google-analytics-for-wordpress' ), esc_html__( 'Error', 'google-analytics-for-wordpress' ), array( 'response' => 403 ) );
    }

    $return       = '';
    if ( ! empty( $_REQUEST['return'] ) && monsterinsights_is_settings_tab( $_REQUEST['return'] ) ) {
        $return = admin_url( 'admin.php?page=monsterinsights_settings&monsterinsights-message=jsvents_mode_switched#monsterinsights-main-tab-tracking?monsterinsights-sub-tab-') . $_REQUEST['return'];
        $return = add_query_arg( 'return', $_REQUEST['return'], $return );
    } else {
        $return = admin_url( 'admin.php?page=monsterinsights_settings&monsterinsights-message=jsvents_mode_switched');
    }
    monsterinsights_update_option( 'events_mode', 'js' );
    wp_safe_redirect( $return );exit;
}
add_action( 'admin_init', 'monsterinsights_switch_to_jsevents',9 );

function monsterinsights_switched_to_jsevents() {
    echo monsterinsights_get_message( 'success', esc_html__( 'Successfully migrated to JS events tracking!', 'google-analytics-for-wordpress' ) );
}

function monsterinsights_switch_to_jsevents_show_notice() {
    if ( empty( $_REQUEST['monsterinsights-message'] ) || $_REQUEST['monsterinsights-message'] !== 'jsvents_mode_switched' ) {
        return;
    }
    
    $allowed_tabs = array( 'engagement', 'performance', 'ecommerce', 'demographics', 'dimensions', 'goptimize' );
    if ( ! empty( $_REQUEST['return'] ) && in_array( $_REQUEST['return'], $allowed_tabs ) ) {
        add_action( 'monsterinsights_tracking_' . $_REQUEST['return'] . '_tab_notice', 'monsterinsights_switched_to_jsevents' );
    } else {
        add_action( 'monsterinsights_settings_general_tab_notice', 'monsterinsights_switched_to_jsevents' );
    }
}
add_action( 'admin_init', 'monsterinsights_switch_to_jsevents_show_notice', 11 ); 