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
    if ( ! empty( $admin_page_hooks['monsterinsights_settings'] ) && $current_screen->id === $admin_page_hooks['monsterinsights_settings'] ) {
        $settings_page = true;
    }

    if ( ! empty( $admin_page_hooks['monsterinsights_reports'] ) && $current_screen->id === $admin_page_hooks['monsterinsights_reports'] ) {
        $settings_page = true;
    }

    if ( $current_screen->id === 'toplevel_page_monsterinsights_settings' ) {
        $settings_page = true;
    }

    if ( $current_screen->id === 'insights_page_monsterinsights_settings' ) {
        $settings_page = true;
    }

    if ( $current_screen->id === 'insights_page_monsterinsights_tracking' ) {
        $settings_page = true;
    }

    if ( ! empty( $current_screen->base ) && strpos( $current_screen->base, 'monsterinsights_network' ) !== false ) {
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

        <a class="monsterinsights-main-nav-item monsterinsights-nav-item" href="<?php echo admin_url('admin.php?page=monsterinsights_tracking#monsterinsights-main-tab-tracking?monsterinsights-sub-tab-engagement');?>" title="<?php echo esc_attr( __( 'Tracking', 'google-analytics-for-wordpress' ) ); ?>">
            <?php echo esc_html__( 'Tracking', 'google-analytics-for-wordpress' ); ?>
        </a>
    </h1>


    <!-- Tab Panels -->
    <div id="monsterinsights-settings-pages" class="monsterinsights-main-nav-tabs monsterinsights-nav-tabs wrap" data-navigation="#monsterinsights-settings-page-main-nav">
        <h1 class="monsterinsights-hideme"></h1><!-- so wp notices are below the nav bar -->
         <div id="monsterinsights-main-tab-general" class="monsterinsights-main-nav-tab monsterinsights-nav-tab monsterinsights-active">
            <?php monsterinsights_settings_general_tab(); ?>
        </div>
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