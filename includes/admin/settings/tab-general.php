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
    // Get settings
    $manual_ua_code              = monsterinsights_get_option( 'manual_ua_code', '' );
    $manual_ua_code              = esc_html( $manual_ua_code );

    $ua_code                     = monsterinsights_get_ua();
    $license_key_type            = '';

    $network_license             = get_site_option( 'monsterinsights_license' );
    if ( ! monsterinsights_is_network_active() || ( monsterinsights_is_network_active() && empty ( $network_license ) ) ) {
        $site_license                = get_option( 'monsterinsights_license' );
        $license_key                 = ! empty( $site_license['key'] ) ? esc_attr( $site_license['key'] ) : '';
        $license_key_type            = ! empty( $site_license['type'] ) ? esc_html( $site_license['type'] ) : '';
    }

    $profile_name                = monsterinsights_get_option( 'analytics_profile_name', '' );
    $profile_id                  = monsterinsights_get_option( 'analytics_profile', '' );
    $tracking_mode               = monsterinsights_get_option( 'tracking_mode', 'analytics' );
    $events_mode                 = monsterinsights_get_option( 'events_mode', 'js' );
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
                <?php if ( ( ! monsterinsights_is_network_active() || ( monsterinsights_is_network_active() && empty ( $network_license ) ) ) && monsterinsights_is_pro_version() ) { ?>
                    <tr id="monsterinsights-settings-key-box">
                        <th scope="row">
                            <label for="monsterinsights-settings-key"><?php esc_html_e( 'License Key', 'google-analytics-for-wordpress' ); ?></label>
                        </th>
                        <td>
                            <form id="monsterinsights-settings-verify-key" method="post">
                                <input type="password" name="monsterinsights-license-key" id="monsterinsights-settings-key" value="<?php echo $license_key; ?>" />
                                <?php wp_nonce_field( 'monsterinsights-key-nonce', 'monsterinsights-key-nonce' ); ?>
                                <?php submit_button( esc_html__( 'Verify Key', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-verify-submit', false ); ?>
                                <?php submit_button( esc_html__( 'Deactivate Key', 'google-analytics-for-wordpress' ), 'button-danger', 'monsterinsights-deactivate-submit', false ); ?>
                                <p class="description"><?php esc_html_e( 'License key to enable automatic updates for MonsterInsights Pro & addons. Deactivate your license if you want to use it on another WordPress site.', 'google-analytics-for-wordpress' ); ?></p>
                            </form>
                        </td>
                    </tr>
                    <?php if ( ! empty( $license_key_type ) && monsterinsights_is_pro_version() ) : ?>
                    <tr id="monsterinsights-settings-key-type-box">
                        <th scope="row">
                            <label for="monsterinsights-settings-key-type"><?php esc_html_e( 'License Key Type', 'google-analytics-for-wordpress' ); ?></label>
                        </th>
                        <td>
                            <form id="monsterinsights-settings-key-type" method="post">
                                <span class="monsterinsights-license-type"><?php printf( esc_html__( 'Your license key type for this site is %s.', 'google-analytics-for-wordpress' ), '<strong>' . $license_key_type . '</strong>' ); ?>
                                <input type="hidden" name="monsterinsights-license-key" value="<?php echo $license_key; ?>" />
                                <?php wp_nonce_field( 'monsterinsights-key-nonce', 'monsterinsights-key-nonce' ); ?>
                                <?php submit_button( esc_html__( 'Refresh Key', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-refresh-submit', false ); ?>
                                <p class="description"><?php esc_html_e( 'Your license key type (handles updates and Addons). Click refresh if your license has been upgraded or the type is incorrect.', 'google-analytics-for-wordpress' ); ?></p>
                            </form>
                        </td>
                    </tr>
                    <?php endif; ?>
                <?php } ?>

                <tr id="monsterinsights-google-authenticate-box">
                    <th scope="row">
                        <?php if ( $profile_name && $ua_code ) { ?>
                            <label for="monsterinsights-google-authenticate"><?php esc_html_e( 'Analytics Profile', 'google-analytics-for-wordpress' ); ?></label>
                        <?php } else { ?>
                            <label for="monsterinsights-google-authenticate"><?php esc_html_e( 'Google Authentication', 'google-analytics-for-wordpress' ); ?></label>
                        <?php } ?>
                    </th>
                    <td>
                        <form id="monsterinsights-google-authenticate" method="post">
                            <?php if ( $profile_name && $ua_code ) { ?>
                                <p><?php echo esc_html__( 'Profile Active: ', 'google-analytics-for-wordpress' ) . $profile_name; ?></p>
                                <p><?php wp_nonce_field( 'monsterinsights-google-authenticated-nonce', 'monsterinsights-google-authenticated-nonce' ); ?>
                                <?php submit_button( esc_html__( 'Re-Authenticate with your Google account', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-google-reauthenticate-submit', false ); ?>
                                <?php submit_button( esc_html__( 'Deauthenticate', 'google-analytics-for-wordpress' ), 'button-danger', 'monsterinsights-google-deauthenticate-submit', false ); ?></p>
                            <?php } else { ?>
                                <?php wp_nonce_field( 'monsterinsights-google-authenticate-nonce', 'monsterinsights-google-authenticate-nonce' ); ?>
                                <?php submit_button( esc_html__( 'Authenticate with your Google account', 'google-analytics-for-wordpress' ), 'button-action', 'monsterinsights-google-authenticate-submit', false ); ?>
                                <p class="description"><?php printf( esc_html__( 'Having issues automatically authenticating? %s Click here to authenticate manually %s.', 'google-analytics-for-wordpress' ), '<a href="javascript:monsterinsights_show_manual()" data-action="show-manual-ua-box">', '</a>' ); ?></p>
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
                    <?php if ( ! $profile_name ) { ?>
                    <!-- Manual UA -->
                    <tr id="monsterinsights-google-ua-box" <?php echo ( ! $ua_code ? 'class="monsterinsights-hideme"' : ''); ?> >
                        <th scope="row">
                            <label for="monsterinsights-google-ua"><?php esc_html_e( 'Manually enter your UA code', 'google-analytics-for-wordpress' ); ?></label>
                        </th>
                        <td>
                            <input type="text" id="monsterinsights-google-ua" name="manual_ua_code" value="<?php echo $manual_ua_code; ?>" />
                            <p class="description"><?php esc_html_e( 'Warning: If you use a manual UA code, you won\'t be able to use the dashboard or reports.', 'google-analytics-for-wordpress' ); ?></p>
                        </td>
                    </tr>
                    <?php } ?>

                    <!-- Disable Dashboard -->
                    <?php
                    $title       = esc_html__( 'Disable Dashboard', 'google-analytics-for-wordpress' );
                    $description = esc_html__( 'Hide the dashboard and reports pages.', 'google-analytics-for-wordpress' );
                    echo monsterinsights_make_checkbox( 'dashboards_disabled', $title, $description );
                    ?>

                    <?php if ( $tracking_mode === 'ga' || monsterinsights_is_debug_mode() ){  ?>
                    <tr id="monsterinsights-tracking-mode">
                        <th scope="row">
                            <label for="monsterinsights-tracking-mode"><?php esc_html_e( 'Pick Tracking Mode', 'google-analytics-for-wordpress' ); ?></label>
                        </th>
                        <td>
                            <?php
                            $url = esc_url( wp_nonce_url( add_query_arg( array( 'monsterinsights-action' => 'switch_to_analyticsjs', 'return' => 'general' ) ), 'monsterinsights-switch-to-analyticsjs-nonce' ) );
                            ?>
                            <label><input type="radio" name="tracking_mode" value="ga" <?php checked( $tracking_mode, 'ga' ); ?> ><?php esc_html_e('GA.js (Deprecated)', 'google-analytics-for-wordpress'); ?> </label>
                            <label><input type="radio" name="tracking_mode" value="analytics" <?php checked( $tracking_mode, 'analytics' ); ?> ><?php esc_html_e( 'Analytics.js (Universal Analytics)', 'google-analytics-for-wordpress'); ?> </label>
                            <?php if ($tracking_mode === 'ga' ) { ?>
                            <?php echo monsterinsights_get_message( 'error', sprintf( esc_html__( 'Warning: You\'re currently using deprecated ga.js tracking. We recommend switching to analytics.js, as it is significantly more accurate than ga.js, and allows for functionality (like the more accurate Javascript based events tracking we offer). Further Google Analytics has deprecated support for ga.js, and it may stop working at any time when Google decides to disable it from their server. To switch to using the newer Universal Analytics (analytics.js) %1$sclick here%2$s.', 'google-analytics-for-wordpress' ), '<a href="' . $url .'">', '</a>' ) );
                            ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if ( $tracking_mode !== 'ga' && $events_mode === 'php' ){  ?>
                    <tr id="monsterinsights-tracking-mode">
                        <th scope="row">
                            <label for="monsterinsights-tracking-mode"><?php esc_html_e( 'Switch to JS events tracking', 'google-analytics-for-wordpress' ); ?></label>
                        </th>
                        <td>
                            <?php
                            $url = esc_url( wp_nonce_url( add_query_arg( array( 'monsterinsights-action' => 'switch_to_jsevents', 'return' => 'general' ) ), 'monsterinsights-switch-to-jsevents-nonce' ) );
                            ?>
                            <?php echo monsterinsights_get_message( 'error', sprintf( esc_html__( 'Warning: You\'re currently using deprecated PHP based events tracking. We recommend switching to JS events tracking, as it is significantly more accurate than PHP based events tracking and we will eventually discontinue PHP based events tracking. To switch %1$sclick here%2$s.', 'google-analytics-for-wordpress' ), '<a href="' . $url .'">', '</a>' ) );
                         ?>
                        </td>
                    </tr>
                    <?php } ?>

                    <!-- Tracking -->
                    <?php
                    $title       = esc_html__( 'Allow Usage Tracking', 'google-analytics-for-wordpress' );
                    $description = esc_html__( 'By allowing us to track usage data we can better help you, because we know with which WordPress configurations, themes and plugins we should test.', 'google-analytics-for-wordpress' );
                    if ( ! $anon_tracking || monsterinsights_is_debug_mode() ){
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
    $throw_notice    = false;
    $manual_ua_code = isset( $_POST['manual_ua_code'] ) ? $_POST['manual_ua_code'] : '';
    $manual_ua_code = monsterinsights_is_valid_ua( $manual_ua_code ); // also sanitizes the string
    
    if ( $manual_ua_code ) {
        monsterinsights_update_option( 'manual_ua_code', $manual_ua_code );
    } else {
        if ( empty ( $manual_ua_code ) && ! empty( $_POST['manual_ua_code'] ) ) {
             $throw_notice = true;
        }
        monsterinsights_update_option( 'manual_ua_code', '' );
    }

    $dashboards_disabled = isset( $_POST['dashboards_disabled'] ) ? 1 : 0;
    monsterinsights_update_option( 'dashboards_disabled', $dashboards_disabled );

    $old_tracking_mode = monsterinsights_get_option( 'tracking_mode', 'analytics' );
    $tracking_mode     = isset( $_POST['tracking_mode'] ) ? $_POST['tracking_mode'] : 'analytics';

    if ( $old_tracking_mode === 'ga' || monsterinsights_is_debug_mode() ) {
        if ( $tracking_mode !== 'analytics' && $tracking_mode !== 'ga' ) {
            /** 
             * Developer Alert:
             *
             * Per the README, this is considered an internal hook and should
             * not be used by other developers. This hook's behavior may be modified
             * or the hook may be removed at any time, without warning.
             */
            $tracking_mode = apply_filters( 'monsterinsights_settings_save_general_tracking_mode', 'analytics' );
        }
        monsterinsights_update_option( 'tracking_mode', $tracking_mode );
    } else {
        if ( $tracking_mode !== 'analytics' ) {
            /** 
             * Developer Alert:
             *
             * Per the README, this is considered an internal hook and should
             * not be used by other developers. This hook's behavior may be modified
             * or the hook may be removed at any time, without warning.
             */
            $tracking_mode = apply_filters( 'monsterinsights_settings_save_general_tracking_mode', 'analytics' );
        }
        monsterinsights_update_option( 'tracking_mode', $tracking_mode );
    }

    $anonymous_data = isset( $_POST['anonymous_data'] ) ? 1 : 0;
    if ( $anonymous_data ) {
        monsterinsights_update_option( 'anonymous_data', $anonymous_data );
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