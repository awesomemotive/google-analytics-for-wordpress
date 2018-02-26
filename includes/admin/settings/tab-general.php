<?php
/**
 * General Settings Tab.
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

/**
 * Callback for displaying the UI for general settings tab.
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_settings_general_tab() {
    $automatic_updates           = monsterinsights_get_option( 'automatic_updates', false );
    $anon_tracking               = monsterinsights_get_option( 'anonymous_data', false );
    ?>
    <div id="monsterinsights-settings-general">
        <div class="monsterinsights-tab-settings-notices">
        <?php 
        // Output any notices now
        /** 
         * Developer Alert:
         *
         * Per the README, this is considered an internal hook and should
         * not be used by other developers. This hook's behavior may be modified
         * or the hook may be removed at any time, without warning.
         */
        do_action( 'monsterinsights_settings_general_tab_notice' );
        ?>
        </div>
        <table class="form-table">
            <tbody>
                <?php if ( monsterinsights_is_pro_version() ) {
                    $license_key  = MonsterInsights()->license->get_site_license_key();
                    $license_key  = $license_key ? $license_key : MonsterInsights()->license->get_network_license_key();
                    $license_key  = $license_key ? $license_key : MonsterInsights()->license->get_default_license_key();

                    $license_type = MonsterInsights()->license->get_site_license_type();
                    ?>
                    <tr id="monsterinsights-settings-key-box">
                        <th scope="row">
                            <label for="monsterinsights-settings-key"><?php esc_html_e( 'License Key', 'google-analytics-for-wordpress' ); ?></label>
                        </th>
                        <td>
                            <form id="monsterinsights-settings-verify-key" method="post">
                                <input type="password" name="monsterinsights-license-key" id="monsterinsights-settings-key" value="<?php echo $license_key; ?>" />
                                <?php wp_nonce_field( 'monsterinsights-key-nonce', 'monsterinsights-key-nonce' ); ?>
                                <?php if ( MonsterInsights()->license->get_site_license_key() ) { ?>
                                    <?php submit_button( esc_html__( 'Verify Key', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-verify-submit', false ); ?>
                                    <?php submit_button( esc_html__( 'Deactivate Key', 'google-analytics-for-wordpress' ), 'button-danger', 'monsterinsights-deactivate-submit', false ); ?>
                                <?php } else { ?>
                                    <?php submit_button( esc_html__( 'Activate Key', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-verify-submit', false ); ?>
                                <?php } ?> 
                                <?php if ( MonsterInsights()->license->is_network_licensed() ) { ?>
                                    <p class="description"><?php esc_html_e( 'Your website is network licensed for MonsterInsights. Enter a license above only if you want to use a different Google Analytics profile for the reports and tracking on this subsite.', 'google-analytics-for-wordpress' ); ?></p>
                                <?php } else { ?>
                                    <p class="description"><?php esc_html_e( 'The license key is used to enable updates for MonsterInsights Pro & addons, as well enable the ability to view reports. Deactivate your license if you want to use it on another WordPress site.', 'google-analytics-for-wordpress' ); ?></p>
                                <?php } ?>
                            </form>
                            <?php if ( MonsterInsights()->license->site_license_has_error() ) { ?>
                                <?php echo monsterinsights_get_message( 'error', MonsterInsights()->license->get_site_license_error() ); ?>
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
                                    <?php if ( is_multisite() ) { ?>
                                        <span class="monsterinsights-license-type"><?php esc_html_e( 'No license key activated on this subsite.', 'google-analytics-for-wordpress' ); ?>
                                    <?php } else { ?>
                                        <span class="monsterinsights-license-type"><?php esc_html_e( 'No license key activated.', 'google-analytics-for-wordpress' ); ?>
                                    <?php } ?>
                                <?php } ?>
                                <input type="hidden" name="monsterinsights-license-key" value="<?php echo $license_key; ?>" />
                                <?php wp_nonce_field( 'monsterinsights-key-nonce', 'monsterinsights-key-nonce' ); ?>
                                <?php submit_button( esc_html__( 'Refresh Key', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-refresh-submit', false ); ?>
                                <p class="description"><?php esc_html_e( 'Click refresh if your license has been upgraded or the type is incorrect.', 'google-analytics-for-wordpress' ); ?></p>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                <tr id="monsterinsights-google-authenticate-box">
                    <th scope="row">
                        <?php if ( MonsterInsights()->auth->get_viewname() && MonsterInsights()->auth->get_ua() ) { ?>
                            <label for="monsterinsights-google-authenticate"><?php esc_html_e( 'Analytics Profile', 'google-analytics-for-wordpress' ); ?></label>
                        <?php } else { ?>
                            <label for="monsterinsights-google-authenticate"><?php esc_html_e( 'Google Authentication', 'google-analytics-for-wordpress' ); ?></label>
                        <?php } ?>
                    </th>
                    <td>
                        <form id="monsterinsights-google-authenticate" method="post">
                            <?php if ( MonsterInsights()->auth->get_viewname() && MonsterInsights()->auth->get_ua() ) { ?>
                                <?php if ( monsterinsights_is_pro_version() && ! MonsterInsights()->license->is_site_licensed() ) { ?>
                                    <p><?php echo esc_html__( 'Please activate MonsterInsights Pro with an active, valid license key in order to use MonsterInsights Pro.' , 'google-analytics-for-wordpress' ); ?></p>
                                <?php } else { ?>
                                    <p><?php echo esc_html__( 'Profile Active: ', 'google-analytics-for-wordpress' ) . MonsterInsights()->auth->get_viewname(); ?></p>
                                    <p><?php wp_nonce_field( 'monsterinsights-google-authenticated-nonce', 'monsterinsights-google-authenticated-nonce' ); ?>
                                    <?php submit_button( esc_html__( 'Re-Authenticate with your Google Account', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-google-reauthenticate-submit', false ); ?>
                                    <?php submit_button( esc_html__( 'Verify Credentials', 'google-analytics-for-wordpress' ), 'button-primary', 'monsterinsights-google-verify-submit', false ); ?>
                                    <?php submit_button( esc_html__( 'Deauthenticate', 'google-analytics-for-wordpress' ), 'button-danger', 'monsterinsights-google-deauthenticate-submit', false ); ?></p>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if ( monsterinsights_is_pro_version() && ! MonsterInsights()->license->is_site_licensed() ) { ?>
                                    <p><?php echo esc_html__( 'Please activate MonsterInsights Pro with an active, valid license key in order to use MonsterInsights Pro.' , 'google-analytics-for-wordpress' ); ?></p>
                                <?php } else { ?>
                                    <?php wp_nonce_field( 'monsterinsights-google-authenticate-nonce', 'monsterinsights-google-authenticate-nonce' ); ?>
                                    <?php submit_button( esc_html__( 'Authenticate with your Google account', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-google-authenticate-submit', false ); ?>
                                    <p class="description"><?php printf( esc_html__( 'Already have a Google Account, but don’t know if you’ve setup Google Analytics? %s Click here to login and find out%s.', 'google-analytics-for-wordpress' ), '<a href="https://www.monsterinsights.com/docs/find-out-if-you-already-have-a-google-analytics-account/?utm_source=monsterinsights-settings&utm_medium=alert&utm_campaign=authenticate">', '</a>' ); ?>
                                    <br />
                                    <?php printf( esc_html__( 'Having issues automatically authenticating? %s Click here to authenticate manually%s.', 'google-analytics-for-wordpress' ), '<a href="javascript:monsterinsights_show_manual()" data-action="show-manual-ua-box">', '</a>' ); ?></p>
                                <?php } ?>
                            <?php } ?>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- <hr /> -->

        <!-- Settings Form -->
        <form id="monsterinsights-general-tab" method="post">
            <table class="form-table">
                <tbody>
                    <?php if ( ! MonsterInsights()->auth->get_viewname() ) { ?>
                    <!-- Manual UA -->
                    <tr id="monsterinsights-google-ua-box" <?php echo ( ! MonsterInsights()->auth->get_manual_ua() ? 'class="monsterinsights-hideme"' : ''); ?> >
                        <th scope="row">
                            <label for="monsterinsights-google-ua"><?php esc_html_e( 'Manually enter your UA code', 'google-analytics-for-wordpress' ); ?></label>
                        </th>
                        <td>
                            <input type="text" id="monsterinsights-google-ua" name="manual_ua_code" value="<?php echo esc_html( MonsterInsights()->auth->get_manual_ua() ); ?>" />
                            <p class="description"><?php esc_html_e( 'Warning: If you use a manual UA code, you won\'t be able to use the reports.', 'google-analytics-for-wordpress' ); ?></p>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php 
                    $manual_ua = MonsterInsights()->auth->get_manual_ua();
                    $auth_ua   = MonsterInsights()->auth->get_ua();

                    if ( empty( $manual_ua ) && empty( $auth_ua ) && monsterinsights_get_network_ua() ) { ?>
                        <!-- If we don't have a manual or auth UA but we have a valid default set (from network), explain that -->
                        <tr id="monsterinsights-default-google-authenticate-box">
                            <th scope="row">
                                <label><?php esc_html_e( 'Default Configuration:', 'google-analytics-for-wordpress' ); ?></label>
                            </th>
                            <td>
                                <p class="description"><?php printf( esc_html__( 'If you do not authenticate with MonsterInsights above, the network default %s will be used as the Google UA code.', 'google-analytics-for-wordpress' ), monsterinsights_get_network_ua() ); ?></p>
                            </td>
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

                    <!-- Disable Dashboard -->
                    <?php
                    $title       = esc_html__( 'Disable Reports', 'google-analytics-for-wordpress' );
                    $description = esc_html__( 'Hide the reports page.', 'google-analytics-for-wordpress' );
                    echo monsterinsights_make_checkbox( 'dashboards_disabled', $title, $description );
                    ?>

                    <?php if ( $automatic_updates !== 'all' && $automatic_updates !== 'minor' ){  ?>
                    <?php $automatic_updates = $automatic_updates ? $automatic_updates : 'none'; ?>
                    <tr id="monsterinsights-automatic-updates-mode">
                        <th scope="row">
                            <label for="monsterinsights-automatic-updates-mode"><?php esc_html_e( 'Automatic Updates', 'google-analytics-for-wordpress' ); ?></label>
                        </th>
                        <td>
                            <label><input type="radio" name="automatic_updates" value="all" <?php checked( $automatic_updates, 'all' ); ?> ><?php esc_html_e('Yes (Recommended) - Get the latest features, bugfixes, and security updates as they are released.', 'google-analytics-for-wordpress'); ?> </label>
                            <label><input type="radio" name="automatic_updates" value="minor" <?php checked( $automatic_updates, 'minor' ); ?> ><?php esc_html_e( 'Minor Only - Only get bugfixes and security updates, but not major features.', 'google-analytics-for-wordpress'); ?> </label>
                            <label><input type="radio" name="automatic_updates" value="none" <?php checked( $automatic_updates, 'none' ); ?> ><?php esc_html_e( 'None - Manually update everything.', 'google-analytics-for-wordpress'); ?> </label>
                        </td>
                    </tr>
                    <?php } ?>

                    <!-- Tracking -->
                    <?php
                    $title       = esc_html__( 'Allow Usage Tracking', 'google-analytics-for-wordpress' );
                    $description = esc_html__( 'By allowing us to track usage data we can better help you, because we know with which WordPress configurations, themes and plugins we should test.', 'google-analytics-for-wordpress' );
                    if ( ( ! $anon_tracking || monsterinsights_is_debug_mode() ) && ! monsterinsights_is_pro_version() ){
                        echo monsterinsights_make_checkbox( 'anonymous_data', $title, $description );
                    }
                    ?>

                    <?php 
                    /** 
                     * Developer Alert:
                     *
                     * Per the README, this is considered an internal hook and should
                     * not be used by other developers. This hook's behavior may be modified
                     * or the hook may be removed at any time, without warning.
                     */
                    do_action( 'monsterinsights_settings_general_box' ); 
                    ?>
                </tbody>
            </table>
            <input type="hidden" name="monsterinsights_settings_tab" value="general"/>
            <?php wp_nonce_field( 'monsterinsights-settings-nonce', 'monsterinsights-settings-nonce' ); ?>
            <?php submit_button( esc_html__( 'Save Changes', 'google-analytics-for-wordpress' ), 'primary', 'monsterinsights-settings-submit', false ); ?>
        </form>
    </div>
    <?php
}
add_action( 'monsterinsights_tab_settings_general', 'monsterinsights_settings_general_tab' );

/**
 * Callback for saving the general settings tab.
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_settings_save_general() {
    $throw_notice       = false;
    $manual_ua_code     = isset( $_POST['manual_ua_code'] ) ? $_POST['manual_ua_code'] : '';
    $manual_ua_code     = monsterinsights_is_valid_ua( $manual_ua_code ); // also sanitizes the string
    $manual_ua_code_old = MonsterInsights()->auth->get_manual_ua();
    
    if ( $manual_ua_code && $manual_ua_code_old && $manual_ua_code_old === $manual_ua_code ) {
        // Same code we had before
            // Do nothing
    } else if ( $manual_ua_code && $manual_ua_code_old && $manual_ua_code_old !== $manual_ua_code ) {
        // Different UA code
        MonsterInsights()->auth->set_manual_ua( $manual_ua_code );
    } else if ( $manual_ua_code && empty( $manual_ua_code_old ) ) {
        // Move to manual
        MonsterInsights()->auth->set_manual_ua( $manual_ua_code );
    } else if ( empty( $manual_ua_code ) && $manual_ua_code_old ) {
        // Deleted manual
        MonsterInsights()->auth->delete_manual_ua();
    } else if ( isset( $_POST['manual_ua_code'] ) && empty( $manual_ua_code ) ) {
        $throw_notice = true;
    } else {
        // Not UA before or after
            // Do nothing
    }

    $dashboards_disabled     = isset( $_POST['dashboards_disabled'] ) ? 1 : 0;
    $dashboards_disabled_old = monsterinsights_get_option( 'dashboards_disabled', false );
    if ( $dashboards_disabled && ! $dashboards_disabled_old ) {
        do_action( 'monsterinsights_reports_delete_aggregate_data' );
    }
    monsterinsights_update_option( 'dashboards_disabled', $dashboards_disabled );

    monsterinsights_update_option( 'tracking_mode', 'analytics' );

    $automatic_updates = isset( $_POST['automatic_updates'] ) && in_array( $_POST['automatic_updates'], array( 'all', 'minor', 'none' ) ) ? $_POST['automatic_updates'] : false;
    if ( $automatic_updates ) {
        monsterinsights_update_option( 'automatic_updates', $automatic_updates );
    }

    $anonymous_data = isset( $_POST['anonymous_data'] ) ? 1 : 0;
    if ( $anonymous_data ) {
        if ( monsterinsights_is_pro_version() ) {
             monsterinsights_update_option( 'anonymous_data', 1 );
        } else {
            monsterinsights_update_option( 'anonymous_data', $anonymous_data );
        }
    }

    /** 
     * Developer Alert:
     *
     * Per the README, this is considered an internal hook and should
     * not be used by other developers. This hook's behavior may be modified
     * or the hook may be removed at any time, without warning.
     */
    do_action( 'monsterinsights_settings_save_general_end' );

    // Output an admin notice so the user knows what happened
    if ( $throw_notice ) {
        add_action( 'monsterinsights_settings_general_tab_notice', 'monsterinsights_invalid_ua_code' );
    } else {
        add_action( 'monsterinsights_settings_general_tab_notice', 'monsterinsights_updated_settings' );
    }
}
add_action( 'monsterinsights_settings_save_general', 'monsterinsights_settings_save_general', 11 );