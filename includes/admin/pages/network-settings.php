<?php
/**
 * Network class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage network
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Callback to output the MonsterInsights network page.
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_network_page() {
    /** 
     * Developer Alert:
     *
     * Per the README, this is considered an internal hook and should
     * not be used by other developers. This hook's behavior may be modified
     * or the hook may be removed at any time, without warning.
     */
    do_action( 'monsterinsights_head' );
    $network_license             = get_site_option( 'monsterinsights_license' );
    $license_key                 = ! empty( $network_license['key'] ) ? esc_attr( $network_license['key'] ) : '';
    $license_key_type            = ! empty( $network_license['type'] ) ? esc_html( $network_license['type'] ) : '';
    $network_ua_code             = get_site_option('monsterinsights_network_manual_ua_code', false );
    ?>
    <?php echo monsterinsights_ublock_notice(); ?>
    <div id="monsterinsights-settings" class="wrap">
        <div id="monsterinsights-settings-pages" class="monsterinsights-main-nav-tabs monsterinsights-nav-tabs wrap" data-navigation="#monsterinsights-network-settings-page-main-nav">
            <div id="monsterinsights-main-tab-general" class="monsterinsights-main-nav-tab monsterinsights-nav-tab monsterinsights-active">
                <div id="monsterinsights-network-settings-general">
                    <?php 
                    // Output any notices now
                    /** 
                     * Developer Alert:
                     *
                     * Per the README, this is considered an internal hook and should
                     * not be used by other developers. This hook's behavior may be modified
                     * or the hook may be removed at any time, without warning.
                     */
                    do_action( 'monsterinsights_network_settings_general_tab_notice' );
                    ?>
                    <h1><?php esc_html_e( 'Network Settings', 'google-analytics-for-wordpress'); ?></h1>
                    <p><?php esc_html_e( 'Activate your MonsterInsights license key on this panel to hide the license key settings and addon pages for subsites.', 'google-analytics-for-wordpress'); ?></p>
                    <table class="form-table">
                        <tbody>
                            <?php if ( monsterinsights_is_pro_version() ) : ?>
                            <tr id="monsterinsights-settings-key-box">
                                <th scope="row">
                                    <label for="monsterinsights-settings-key"><?php esc_html_e( 'License Key', 'google-analytics-for-wordpress' ); ?></label>
                                </th>
                                <td>
                                    <form id="monsterinsights-settings-verify-key" method="post">
                                        <input type="password" name="monsterinsights-license-key" id="monsterinsights-settings-key" value="<?php echo esc_attr( $license_key ); ?>" />
                                        <?php wp_nonce_field( 'monsterinsights-key-nonce', 'monsterinsights-key-nonce' ); ?>
                                        <?php submit_button( esc_html__( 'Verify Key', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-verify-submit', false ); ?>
                                        <?php submit_button( esc_html__( 'Deactivate Key', 'google-analytics-for-wordpress' ), 'button-danger', 'monsterinsights-deactivate-submit', false ); ?>
                                        <p class="description"><?php esc_html_e( 'License key to enable automatic updates for MonsterInsights Pro & addons. Deactivate your license if you want to use it on another WordPress site.', 'google-analytics-for-wordpress' ); ?></p>
                                    </form>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php if ( ! empty( $license_key_type ) && monsterinsights_is_pro_version() ) : ?>
                            <tr id="monsterinsights-settings-key-type-box">
                                <th scope="row">
                                    <label for="monsterinsights-settings-key-type"><?php esc_html_e( 'License Key Type', 'google-analytics-for-wordpress' ); ?></label>
                                </th>
                                <td>
                                    <form id="monsterinsights-settings-key-type" method="post">
                                        <span class="monsterinsights-license-type"><?php printf( esc_html__( 'Your license key type for this site is %s.', 'google-analytics-for-wordpress' ), '<strong>' . $license_key_type . '</strong>' ); ?>
                                        <input type="hidden" name="monsterinsights-license-key" value="<?php echo esc_attr( $license_key ); ?>" />
                                        <?php wp_nonce_field( 'monsterinsights-key-nonce', 'monsterinsights-key-nonce' ); ?>
                                        <?php submit_button( esc_html__( 'Refresh Key', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-refresh-submit', false ); ?>
                                        <p class="description"><?php esc_html_e( 'Your license key type (handles updates and Addons). Click refresh if your license has been upgraded or the type is incorrect.', 'google-analytics-for-wordpress' ); ?></p>
                                    </form>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <form id="monsterinsights-network-general-tab" method="post">
                                    <th scope="row">
                                        <label for="monsterinsights-google-ua"><?php esc_html_e( 'Network UA code', 'google-analytics-for-wordpress' ); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" id="monsterinsights-network-ua-code" name="network_manual_ua_code" value="<?php echo $network_ua_code; ?>" /><?php submit_button( esc_html__( 'Save Network UA code', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-network-settings-submit', false ); ?>
                                        <p class="description"><?php esc_html_e( 'This is the default UA code to use for subsites of a network (can be overriden at the single site level).', 'google-analytics-for-wordpress' ); ?></p>
                                        <?php wp_nonce_field( 'monsterinsights-network-settings-nonce', 'monsterinsights-network-settings-nonce' ); ?>
                                        
                                    </td>
                                </form>
                            </tr>
                        </tbody>
                    </table>
                </div>
             </div>
         </div>
    </div>
    <?php
}

/**
 * Callback for saving the general settings tab.
 *
 * @since 6.1.0
 * @access public
 *
 * @return void
 */
function monsterinsights_network_settings_save_general() {

    // Check if user pressed the 'Update' button and nonce is valid
    if ( ! isset( $_POST['monsterinsights-network-settings-submit'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['monsterinsights-network-settings-nonce'], 'monsterinsights-network-settings-nonce' ) ) {
        return;
    }

    if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
        return;
    }

    $throw_notice    = false;
    $network_manual_ua_code = isset( $_POST['network_manual_ua_code'] ) ? $_POST['network_manual_ua_code'] : '';
    $network_manual_ua_code = monsterinsights_is_valid_ua( $network_manual_ua_code ); // also sanitizes the string

    if ( $network_manual_ua_code ) {
        update_site_option( 'monsterinsights_network_manual_ua_code', $network_manual_ua_code );
    } else {
        if ( empty ( $network_manual_ua_code ) && isset( $_POST['network_manual_ua_code'] ) ) {
             $throw_notice = true;
        }
        update_site_option( 'monsterinsights_network_manual_ua_code', '' );
    }

    // Output an admin notice so the user knows what happened
    if ( $throw_notice ) {
        add_action( 'monsterinsights_network_settings_general_tab_notice', 'monsterinsights_invalid_ua_code' );
    } else {
        add_action( 'monsterinsights_network_settings_general_tab_notice', 'monsterinsights_updated_settings' );
    }
}
add_action( 'admin_init', 'monsterinsights_network_settings_save_general', 11 );