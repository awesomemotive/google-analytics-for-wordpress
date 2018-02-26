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
                            <?php if ( monsterinsights_is_pro_version() ) { 

                                    $license_key  = MonsterInsights()->license->get_network_license_key();
                                    $license_key  = $license_key ? $license_key : MonsterInsights()->license->get_default_license_key();

                                    $license_type = MonsterInsights()->license->get_network_license_type();

                                ?>
                                <tr id="monsterinsights-settings-key-box">
                                    <th scope="row">
                                        <label for="monsterinsights-settings-key"><?php esc_html_e( 'License Key', 'google-analytics-for-wordpress' ); ?></label>
                                    </th>
                                    <td>
                                        <form id="monsterinsights-settings-verify-key" method="post">
                                            <input type="password" name="monsterinsights-license-key" id="monsterinsights-settings-key" value="<?php echo esc_attr( $license_key ); ?>" />
                                            <?php wp_nonce_field( 'monsterinsights-key-nonce', 'monsterinsights-key-nonce' ); ?>
                                             <?php if ( MonsterInsights()->license->get_network_license_key() ) { ?>
                                                <?php submit_button( esc_html__( 'Verify Key', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-verify-submit', false ); ?>
                                                <?php submit_button( esc_html__( 'Deactivate Key', 'google-analytics-for-wordpress' ), 'button-danger', 'monsterinsights-deactivate-submit', false ); ?>
                                            <?php } else { ?>
                                                <?php submit_button( esc_html__( 'Activate Key', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-verify-submit', false ); ?>
                                             <?php } ?> 
                                            <p class="description"><?php esc_html_e( 'The license key is used to enable updates for MonsterInsights Pro & addons, as well enable the ability to view reports. Deactivate your license if you want to use it on another WordPress site.', 'google-analytics-for-wordpress' ); ?></p>
                                        </form>
                                        <?php if ( MonsterInsights()->license->network_license_has_error() ) { ?>
                                            <?php echo monsterinsights_get_message( 'error', MonsterInsights()->license->get_network_license_error() ); ?>
                                         <?php } ?>
                                    </td>
                                </tr>
                                <tr id="monsterinsights-settings-key-type-box">
                                    <th scope="row">
                                        <label for="monsterinsights-settings-key-type"><?php esc_html_e( 'License Key Type', 'google-analytics-for-wordpress' ); ?></label>
                                    </th>
                                    <td>
                                        <form id="monsterinsights-settings-key-type" method="post">
                                            <?php if ( $license_type ) { ?>
                                            <span class="monsterinsights-license-type"><?php printf( esc_html__( 'Your license key type for this site is %s.', 'google-analytics-for-wordpress' ), '<strong>' . $license_type . '</strong>' ); ?>
                                            <?php } else { ?>
                                                <span class="monsterinsights-license-type"><?php esc_html_e( 'No license key activated.', 'google-analytics-for-wordpress' ); ?>
                                            <?php } ?>
                                            <input type="hidden" name="monsterinsights-license-key" value="<?php echo esc_attr( $license_key ); ?>" />
                                            <?php wp_nonce_field( 'monsterinsights-key-nonce', 'monsterinsights-key-nonce' ); ?>
                                            <?php submit_button( esc_html__( 'Refresh Key', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-refresh-submit', false ); ?>
                                            <p class="description"><?php esc_html_e( 'Click refresh if your license has been upgraded or the type is incorrect.', 'google-analytics-for-wordpress' ); ?></p>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr id="monsterinsights-google-authenticate-box">
                                <th scope="row">
                                    <?php if ( MonsterInsights()->auth->get_network_viewname() && MonsterInsights()->auth->get_network_ua() ) { ?>
                                        <label for="monsterinsights-google-authenticate"><?php esc_html_e( 'Analytics Profile', 'google-analytics-for-wordpress' ); ?></label>
                                    <?php } else { ?>
                                        <label for="monsterinsights-google-authenticate"><?php esc_html_e( 'Google Authentication', 'google-analytics-for-wordpress' ); ?></label>
                                    <?php } ?>
                                </th>
                                <td>
                                    <form id="monsterinsights-google-authenticate" method="post">
                                        <?php if ( MonsterInsights()->auth->get_network_viewname() && MonsterInsights()->auth->get_network_ua() ) { ?>
                                            <?php if ( monsterinsights_is_pro_version() && ! MonsterInsights()->license->is_network_licensed() ) { ?>
                                                <p><?php echo esc_html__( 'Please activate MonsterInsights Pro with an active, valid license key in order to use MonsterInsights Pro.' , 'google-analytics-for-wordpress' ); ?></p>
                                            <?php } else { ?>
                                                <p><?php echo esc_html__( 'Profile Active: ', 'google-analytics-for-wordpress' ) . MonsterInsights()->auth->get_network_viewname(); ?></p>
                                                <p><?php wp_nonce_field( 'monsterinsights-google-authenticated-nonce', 'monsterinsights-google-authenticated-nonce' ); ?>
                                                <?php submit_button( esc_html__( 'Re-Authenticate with your Google Account', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-google-reauthenticate-submit', false ); ?>
                                                <?php submit_button( esc_html__( 'Verify Credentials', 'google-analytics-for-wordpress' ), 'button-primary', 'monsterinsights-google-verify-submit', false ); ?>
                                                <?php submit_button( esc_html__( 'Deauthenticate', 'google-analytics-for-wordpress' ), 'button-danger', 'monsterinsights-google-deauthenticate-submit', false ); ?></p>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php if ( monsterinsights_is_pro_version() && ! MonsterInsights()->license->is_network_licensed() ) { ?>
                                                <p><?php echo esc_html__( 'Please activate MonsterInsights Pro with an active, valid license key in order to use MonsterInsights Pro.' , 'google-analytics-for-wordpress' ); ?></p>
                                            <?php } else { ?>
                                                <?php wp_nonce_field( 'monsterinsights-google-authenticate-nonce', 'monsterinsights-google-authenticate-nonce' ); ?>
                                                <?php submit_button( esc_html__( 'Authenticate with your Google account', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-google-authenticate-submit', false ); ?>
                                                <p class="description"><?php printf( esc_html__( 'This is the default Google profile to use for subsites of a network (can be overriden at the single site level). Having issues automatically authenticating? %s Click here to authenticate manually %s.', 'google-analytics-for-wordpress' ), '<a href="javascript:monsterinsights_show_manual()" data-action="show-manual-ua-box">', '</a>' ); ?></p>
                                            <?php } ?>
                                        <?php } ?>
                                    </form>
                                </td>
                            </tr>

                            <?php if ( ! MonsterInsights()->auth->get_network_viewname() ) {
                                $network_ua = MonsterInsights()->auth->get_network_manual_ua();
                            ?>
                            <tr id="monsterinsights-google-ua-box" <?php echo (empty( $network_ua ) ? 'class="monsterinsights-hideme"' : ''); ?> >
                                <form id="monsterinsights-network-general-tab" method="post">
                                    <th scope="row">
                                        <label for="monsterinsights-google-ua"><?php esc_html_e( 'Network UA code', 'google-analytics-for-wordpress' ); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" id="monsterinsights-network-ua-code" name="network_manual_ua_code" value="<?php echo esc_html( MonsterInsights()->auth->get_network_manual_ua() ); ?>" /><?php submit_button( esc_html__( 'Save Network UA code', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-network-settings-submit', false ); ?>
                                        <p class="description"><?php esc_html_e( 'This is the default UA code to use for subsites of a network (can be overriden at the single site level).', 'google-analytics-for-wordpress' ); ?></p>
                                        <?php wp_nonce_field( 'monsterinsights-network-settings-nonce', 'monsterinsights-network-settings-nonce' ); ?>
                                    </td>
                                </form>
                            </tr>
                            <?php } ?>
                            <!-- Upgrade Doc -->
                            <?php if ( ! monsterinsights_is_pro_version() ) { ?>
                             <tr id="monsterinsights-upgrade-link">
                                <th scope="row">
                                    <label for="monsterinsights-upgrade-link"><?php esc_html_e( 'Unlock MonsterInsights Pro', 'google-analytics-for-wordpress' ); ?></label>
                                </th>
                                <td>
                                    <p>
                                        <?php echo sprintf( esc_html__( 'Already purchased an upgrade to MonsterInsights Pro? To unlock your Pro features and addons, %sfollow our upgrade guide%s to install MonsterInsights Pro.' ), '<a href="https://www.monsterinsights.com/docs/go-lite-pro/?utm_source=wpdashboard&utm_campaign=upgradedocinstall">', '</a>' ); ?>
                                    </p>
                                    <p>
                                        <?php echo sprintf( esc_html__( "Don't yet have a Pro license? %sVisit our website%s to upgrade and learn more about all the amazing features, expanded report and powerful addons you unlock when you go Pro." ), '<a href="https://www.monsterinsights.com/lite/?utm_source=wpdashboard&utm_campaign=upgradedocbuy">', '</a>' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <?php } ?>
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

    $throw_notice       = false;
    $manual_ua_code     = isset( $_POST['network_manual_ua_code'] ) ? $_POST['network_manual_ua_code'] : '';
    $manual_ua_code     = monsterinsights_is_valid_ua( $manual_ua_code ); // also sanitizes the string
    $manual_ua_code_old = MonsterInsights()->auth->get_network_manual_ua();
    
    if ( $manual_ua_code && $manual_ua_code_old && $manual_ua_code_old === $manual_ua_code ) {
        // Same code we had before
            // Do nothing
    } else if ( $manual_ua_code && $manual_ua_code_old && $manual_ua_code_old !== $manual_ua_code ) {
        // Different UA code
        MonsterInsights()->auth->set_network_manual_ua( $manual_ua_code );
    } else if ( $manual_ua_code && empty( $manual_ua_code_old ) ) {
        // Move to manual
        MonsterInsights()->auth->set_network_manual_ua( $manual_ua_code );
    } else if ( empty( $manual_ua_code ) && $manual_ua_code_old ) {
        // Deleted manual
        MonsterInsights()->auth->delete_network_manual_ua();
    } else if ( isset( $_POST['network_manual_ua_code'] ) && empty( $manual_ua_code ) ) {
        $throw_notice = true;
    } else {
        // Not UA before or after
            // Do nothing
    }

    // Output an admin notice so the user knows what happened
    if ( $throw_notice ) {
        add_action( 'monsterinsights_network_settings_general_tab_notice', 'monsterinsights_invalid_ua_code' );
    } else {
        add_action( 'monsterinsights_network_settings_general_tab_notice', 'monsterinsights_updated_settings' );
    }
}
add_action( 'admin_init', 'monsterinsights_network_settings_save_general', 11 );