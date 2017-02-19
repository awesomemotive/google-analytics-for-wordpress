<?php
/**
 * License class.
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

final class MonsterInsights_License {

    /**
     * Holds the base class object.
     *
     * @since 6.0.0
     *
     * @var object
     */
    public $base;

    /**
     * Holds the license key.
     *
     * @since 6.0.0
     *
     * @var string
     */
    public $key;

    /**
     * Holds any license error messages.
     *
     * @since 6.0.0
     *
     * @var array
     */
    public $errors = array();

    /**
     * Holds any license success messages.
     *
     * @since 6.0.0
     *
     * @var array
     */
    public $success = array();

    /**
     * Primary class constructor.
     *
     * @since 6.0.0
     */
    public function __construct() {
        // Load the base class object.
        $this->base = MonsterInsights();
        add_action( 'admin_init', array( $this, 'admin_init' ) );
    }
    
    public function admin_init() {
        // Possibly verify the key.
        $this->maybe_verify_key();

        // Add potential admin notices for actions around the admin.
        add_action( 'admin_notices', array( $this, 'monsterinsights_notices' ) );
        add_action( 'network_admin_notices', array( $this, 'monsterinsights_notices' ) );

        // Grab the license key. If it is not set (even after verification), return early.
        $this->key = monsterinsights_get_license_key();
        if ( ! $this->key ) {
            return;
        }

        // Possibly handle validating, deactivating and refreshing license keys.
        $this->maybe_validate_key();
        $this->maybe_deactivate_key();
        $this->maybe_refresh_key();
    }

    /**
     * Maybe verifies a license key entered by the user.
     *
     * @since 6.0.0
     *
     * @return null Return early if the key fails to be verified.
     */
    public function maybe_verify_key() {
        
        if ( ! $this->is_verifying_key() ) {
            return;
        }

        if ( ! $this->verify_key_action() ) {
            return;
        }

        $this->verify_key();

    }

    /**
     * Verifies a license key entered by the user.
     *
     * @since 6.0.0
     */
    public function verify_key() {

        // Perform a request to verify the key.
        $verify = $this->perform_remote_request( 'verify-key', array( 'tgm-updater-key' => trim( $_POST['monsterinsights-license-key'] ) ) );

        // If it returns false, send back a generic error message and return.
        if ( ! $verify ) {
            $this->errors[] = esc_html__( 'There was an error connecting to the remote key API. Please try again later.', 'google-analytics-for-wordpress' );
            return;
        }

        // If an error is returned, set the error and return.
        if ( ! empty( $verify->error ) ) {
            $this->errors[] = $verify->error;
            return;
        }

        // Otherwise, our request has been done successfully. Update the option and set the success message.
        if ( is_multisite() && is_network_admin() ) {
            $option                = get_site_option( 'monsterinsights_license' );
            $option['key']         = trim( $_POST['monsterinsights-license-key'] );
            $option['type']        = isset( $verify->type ) ? $verify->type : $option['type'];
            $option['is_expired']  = false;
            $option['is_disabled'] = false;
            $option['is_invalid']  = false;
            $this->success[]       = isset( $verify->success ) ? $verify->success : esc_html__( 'Congratulations! This site is now receiving automatic updates.', 'google-analytics-for-wordpress' );
            update_site_option( 'monsterinsights_license', $option );
        } else {
            $option                = get_option( 'monsterinsights_license' );
            $option['key']         = trim( $_POST['monsterinsights-license-key'] );
            $option['type']        = isset( $verify->type ) ? $verify->type : $option['type'];
            $option['is_expired']  = false;
            $option['is_disabled'] = false;
            $option['is_invalid']  = false;
            $this->success[]       = isset( $verify->success ) ? $verify->success : esc_html__( 'Congratulations! This site is now receiving automatic updates.', 'google-analytics-for-wordpress' );
            update_option( 'monsterinsights_license', $option );        
        }

    }

    /**
     * Flag to determine if a key is being verified.
     *
     * @since 6.0.0
     *
     * @return bool True if being verified, false otherwise.
     */
    public function is_verifying_key() {

        return isset( $_POST['monsterinsights-license-key'] ) && isset( $_POST['monsterinsights-verify-submit'] );

    }

    /**
     * Verifies nonces that allow key verification.
     *
     * @since 6.0.0
     *
     * @return bool True if nonces check out, false otherwise.
     */
    public function verify_key_action() {

        return isset( $_POST['monsterinsights-verify-submit'] ) && wp_verify_nonce( $_POST['monsterinsights-key-nonce'], 'monsterinsights-key-nonce' );

    }

    /**
     * Maybe validates a license key entered by the user.
     *
     * @since 6.0.0
     *
     * @return null Return early if the transient has not expired yet.
     */
    public function maybe_validate_key() {

        // Only run every 12 hours.
        $timestamp = get_option( 'monsterinsights_license_updates' );
        if ( ! $timestamp ) {
             $timestamp = strtotime( '+8 hours' );
             update_option( 'monsterinsights_license_updates', $timestamp );
             $this->validate_key();
        } else {
            $current_timestamp = time();
            if ( $current_timestamp < $timestamp ) {
                return;
            } else {
                update_option( 'monsterinsights_license_updates', strtotime( '+8 hours' ) );
                $this->validate_key();
            }
        }
    }

    /**
     * Validates a license key entered by the user.
     *
     * @since 6.0.0
     *
     * @param bool $forced Force to set contextual messages (false by default).
     */
    public function validate_key( $forced = false ) {

        $validate = $this->perform_remote_request( 'validate-key', array( 'tgm-updater-key' => $this->key ) );

        // If there was a basic API error in validation, only set the transient for 10 minutes before retrying.
        if ( ! $validate ) {
            // If forced, set contextual success message.
            if ( $forced ) {
                $this->errors[] = esc_html__( 'There was an error connecting to the remote key API. Please try again later.', 'google-analytics-for-wordpress' );
            }
            
            return;
        }

        // If a key or author error is returned, the license no longer exists or the user has been deleted, so reset license.
        if ( isset( $validate->key ) || isset( $validate->author ) ) {
            $option = array();
            if ( is_multisite() && is_network_admin() ) {
                $option  = get_site_option( 'monsterinsights_license' );
            } else {
                $option = get_option( 'monsterinsights_license' );
            }
            $option['is_expired']  = false;
            $option['is_disabled'] = false;
            $option['is_invalid']  = true;
            if ( is_multisite() && is_network_admin() ) {
                update_site_option( 'monsterinsights_license', $option );
            } else {
                update_option( 'monsterinsights_license', $option );
            }
            return;
        }

        // If the license has expired, set the transient and expired flag and return.
        if ( isset( $validate->expired ) ) {
            $option = array();
            if ( is_multisite() && is_network_admin() ) {
                $option  = get_site_option( 'monsterinsights_license' );
            } else {
                $option = get_option( 'monsterinsights_license' );
            }
            $option['is_expired']  = true;
            $option['is_disabled'] = false;
            $option['is_invalid']  = false;
            if ( is_multisite() && is_network_admin() ) {
                update_site_option( 'monsterinsights_license', $option );
            } else {
                update_option( 'monsterinsights_license', $option );
            }
            return;
        }

        // If the license is disabled, set the transient and disabled flag and return.
        if ( isset( $validate->disabled ) ) {
            $option = array();
            if ( is_multisite() && is_network_admin() ) {
                $option  = get_site_option( 'monsterinsights_license' );
            } else {
                $option = get_option( 'monsterinsights_license' );
            }
            $option['is_expired']  = false;
            $option['is_disabled'] = true;
            $option['is_invalid']  = false;
            if ( is_multisite() && is_network_admin() ) {
                update_site_option( 'monsterinsights_license', $option );
            } else {
                update_option( 'monsterinsights_license', $option );
            }
            return;
        }

        // If forced, set contextual success message.
        if ( ( ! empty( $validate->key ) || ! empty( $this->key ) ) && $forced ) {
            $key = '';
            if ( ! empty( $validate->key ) ) {
                $key = $validate->key;
            } else {
                $key = $this->key;
            }
            delete_transient( '_monsterinsights_addons' );
            monsterinsights_get_addons_data( $key );
            $this->success[] = esc_html__( 'Congratulations! Your key has been refreshed successfully.', 'google-analytics-for-wordpress' );
        }

        $option = array();
        if ( is_multisite() && is_network_admin() ) {
            $option  = get_site_option( 'monsterinsights_license' );
        } else {
            $option = get_option( 'monsterinsights_license' );
        }
        $option['type']        = isset( $validate->type ) ? $validate->type : $option['type'];
        $option['is_expired']  = false;
        $option['is_disabled'] = false;
        $option['is_invalid']  = false;
        if ( is_multisite() && is_network_admin() ) {
            update_site_option( 'monsterinsights_license', $option );
        } else {
            update_option( 'monsterinsights_license', $option );
        }

    }

    /**
     * Maybe deactivates a license key entered by the user.
     *
     * @since 6.0.0
     *
     * @return null Return early if the key fails to be deactivated.
     */
    public function maybe_deactivate_key() {

        if ( ! $this->is_deactivating_key() ) {
            return;
        }

        if ( ! $this->deactivate_key_action() ) {
            return;
        }

        $this->deactivate_key();

    }

    /**
     * Deactivates a license key entered by the user.
     *
     * @since 6.0.0
     */
    public function deactivate_key() {

        // Perform a request to deactivate the key.
        $deactivate = $this->perform_remote_request( 'deactivate-key', array( 'tgm-updater-key' => $_POST['monsterinsights-license-key'] ) );

        // If it returns false, send back a generic error message and return.
        if ( ! $deactivate ) {
            $this->errors[] = esc_html__( 'There was an error connecting to the remote key API. Please try again later.', 'google-analytics-for-wordpress' );
            return;
        }

        // If an error is returned, set the error and return.
        if ( ! empty( $deactivate->error ) ) {
            $this->errors[] = $deactivate->error;
            return;
        }

        // Otherwise, our request has been done successfully. Reset the option and set the success message.
        $this->success[] = isset( $deactivate->success ) ? $deactivate->success : esc_html__( 'Congratulations! You have deactivated the key from this site successfully.', 'google-analytics-for-wordpress' );
        update_option( 'monsterinsights_license', array() );
        if ( is_multisite() && is_network_admin() ) {
            update_site_option( 'monsterinsights_license', array() );
        } else {
            update_option( 'monsterinsights_license', array() );
        }

    }

    /**
     * Flag to determine if a key is being deactivated.
     *
     * @since 6.0.0
     *
     * @return bool True if being verified, false otherwise.
     */
    public function is_deactivating_key() {

        return isset( $_POST['monsterinsights-license-key'] ) && isset( $_POST['monsterinsights-deactivate-submit'] );

    }

    /**
     * Verifies nonces that allow key deactivation.
     *
     * @since 6.0.0
     *
     * @return bool True if nonces check out, false otherwise.
     */
    public function deactivate_key_action() {

        return isset( $_POST['monsterinsights-deactivate-submit'] ) && wp_verify_nonce( $_POST['monsterinsights-key-nonce'], 'monsterinsights-key-nonce' );

    }

    /**
     * Maybe refreshes a license key.
     *
     * @since 6.0.0
     *
     * @return null Return early if the key fails to be refreshed.
     */
    public function maybe_refresh_key() {

        if ( ! $this->is_refreshing_key() ) {
            return;
        }

        if ( ! $this->refresh_key_action() ) {
            return;
        }

        // Refreshing is simply a word alias for validating a key. Force true to set contextual messages.
        $this->validate_key( true );

    }

    /**
     * Flag to determine if a key is being refreshed.
     *
     * @since 6.0.0
     *
     * @return bool True if being refreshed, false otherwise.
     */
    public function is_refreshing_key() {

        return isset( $_POST['monsterinsights-license-key'] ) && isset( $_POST['monsterinsights-refresh-submit'] );

    }

    /**
     * Verifies nonces that allow key refreshing.
     *
     * @since 6.0.0
     *
     * @return bool True if nonces check out, false otherwise.
     */
    public function refresh_key_action() {

        return isset( $_POST['monsterinsights-refresh-submit'] ) && wp_verify_nonce( $_POST['monsterinsights-key-nonce'], 'monsterinsights-key-nonce' );

    }

    /**
     * Outputs any notices generated by the class.
     *
     * @since 6.0.0
     */
    public function monsterinsights_notices() {

        // Grab the option and output any nag dealing with license keys.
        $key    = monsterinsights_get_license_key();

        $option = array();
        if ( is_multisite() && is_network_admin() ) {
            $option = get_site_option( 'monsterinsights_license' );
        } else {
            $option = get_option( 'monsterinsights_license' );
        }

        if ( is_multisite() && is_network_admin() ) {
            // If a key has expired, output nag about renewing the key.
            if ( isset( $option['is_expired'] ) && $option['is_expired'] ) :
            ?>
            <div class="error">
                <p><?php printf( esc_html__( 'Your license key for MonsterInsights has expired. %1$sPlease click here to renew your license key and continue receiving automatic updates.%2$s', 'google-analytics-for-wordpress' ), '<a href="https://www.monsterinsights.com/login/" target="_blank" rel="noopener noreferrer" referrer="no-referrer">', '</a>' ); ?></p>
            </div>
            <?php
            endif;

            // If a key has been disabled, output nag about using another key.
            if ( isset( $option['is_disabled'] ) && $option['is_disabled'] ) :
            ?>
            <div class="error">
                <p><?php esc_html_e( 'Your license key for MonsterInsights has been disabled. Please use a different key to continue receiving automatic updates.', 'google-analytics-for-wordpress' ); ?></p>
            </div>
            <?php
            endif;

            // If a key is invalid, output nag about using another key.
            if ( isset( $option['is_invalid'] ) && $option['is_invalid'] ) :
            ?>
            <div class="error">
                <p><?php esc_html_e( 'Your license key for MonsterInsights is invalid. The key no longer exists or the user associated with the key has been deleted. Please use a different key to continue receiving automatic updates.', 'google-analytics-for-wordpress' ); ?></p>
            </div>
            <?php
            endif;

            // If there are any license errors, output them now.
            if ( ! empty( $this->errors ) ) :
            ?>
            <div class="error">
                <p><?php echo implode( '<br>', $this->errors ); ?></p>
            </div>
            <?php
            endif;

            // If there are any success messages, output them now.
            if ( ! empty( $this->success ) ) :
            ?>
            <div class="updated">
                <p><?php echo implode( '<br>', $this->success ); ?></p>
            </div>
            <?php
            endif;
        } else {
            // If there is no license key, output nag about ensuring key is set for automatic updates.
            if ( ! $key ) :
                if ( ! monsterinsights_is_pro_version() ) { 
                    return;
                }
                $screen = get_current_screen(); 
                if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) !== false ) {
                    return;
                }
            ?>
            <div class="error">
                <p><?php printf( esc_html__( 'No valid license key has been entered, so automatic updates for MonsterInsights have been turned off. %1$sPlease click here to enter your license key and begin receiving automatic updates.%2$s', 'google-analytics-for-wordpress' ), '<a href="'. esc_url( add_query_arg( array( 'page' => 'monsterinsights_settings' ), admin_url( 'admin.php' ) ) ) . '" target="_blank" rel="noopener noreferrer">', '</a>' ); ?></p>
            </div>
            <?php
            endif;

            // If a key has expired, output nag about renewing the key.
            if ( isset( $option['is_expired'] ) && $option['is_expired'] ) :
            ?>
            <div class="error">
                <p><?php printf( esc_html__( 'Your license key for MonsterInsights has expired. %1$sPlease click here to renew your license key and continue receiving automatic updates.%2$s', 'google-analytics-for-wordpress' ), '<a href="https://www.monsterinsights.com/login/" target="_blank" rel="noopener noreferrer">', '</a>' ); ?></p>
            </div>
            <?php
            endif;

            // If a key has been disabled, output nag about using another key.
            if ( isset( $option['is_disabled'] ) && $option['is_disabled'] ) :
            ?>
            <div class="error">
                <p><?php esc_html_e( 'Your license key for MonsterInsights has been disabled. Please use a different key to continue receiving automatic updates.', 'google-analytics-for-wordpress' ); ?></p>
            </div>
            <?php
            endif;

            // If a key is invalid, output nag about using another key.
            if ( isset( $option['is_invalid'] ) && $option['is_invalid'] ) :
            ?>
            <div class="error">
                <p><?php esc_html_e( 'Your license key for MonsterInsights is invalid. The key no longer exists or the user associated with the key has been deleted. Please use a different key to continue receiving automatic updates.', 'google-analytics-for-wordpress' ); ?></p>
            </div>
            <?php
            endif;

            // If there are any license errors, output them now.
            if ( ! empty( $this->errors ) ) :
            ?>
            <div class="error">
                <p><?php echo implode( '<br>', $this->errors ); ?></p>
            </div>
            <?php
            endif;

            // If there are any success messages, output them now.
            if ( ! empty( $this->success ) ) :
            ?>
            <div class="updated">
                <p><?php echo implode( '<br>', $this->success ); ?></p>
            </div>
            <?php
            endif;
        }
    }

    /**
     * Queries the remote URL via wp_remote_post and returns a json decoded response.
     *
     * @since 6.0.0
     *
     * @param string $action        The name of the $_POST action var.
     * @param array $body           The content to retrieve from the remote URL.
     * @param array $headers        The headers to send to the remote URL.
     * @param string $return_format The format for returning content from the remote URL.
     * @return string|bool          Json decoded response on success, false on failure.
     */
    public function perform_remote_request( $action, $body = array(), $headers = array(), $return_format = 'json' ) {

        // Build the body of the request.
        $body = wp_parse_args(
            $body,
            array(
                'tgm-updater-action'     => $action,
                'tgm-updater-key'        => $this->key,
                'tgm-updater-wp-version' => get_bloginfo( 'version' ),
                'tgm-updater-referer'    => site_url(),
                'tgm-updater-mi-version' => MONSTERINSIGHTS_VERSION,
                'tgm-updater-is-pro'     => monsterinsights_is_pro_version(),
            )
        );
        $body = http_build_query( $body, '', '&' );

        // Build the headers of the request.
        $headers = wp_parse_args(
            $headers,
            array(
                'Content-Type'   => 'application/x-www-form-urlencoded',
                'Content-Length' => strlen( $body )
            )
        );

        // Setup variable for wp_remote_post.
        $post = array(
            'headers' => $headers,
            'body'    => $body
        );

        // Perform the query and retrieve the response.
        $response      = wp_remote_post( 'https://www.monsterinsights.com', $post );
        $response_code = wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );

        // Bail out early if there are any errors.
        if ( 200 != $response_code || is_wp_error( $response_body ) ) {
            return false;
        }

        // Return the json decoded content.
        return json_decode( $response_body );

    }
}