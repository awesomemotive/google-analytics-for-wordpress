<?php
/**
 * Addons class.
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

function monsterinsights_is_addons_page() {
    $current_screen = get_current_screen();
    global $admin_page_hooks;
   
    if ( ! is_object( $current_screen ) || empty( $current_screen->id ) || empty( $admin_page_hooks ) ) {
        return false;
    }

    $settings_page = false;
    if ( ! empty( $admin_page_hooks['monsterinsights_dashboard'] ) && $current_screen->id === $admin_page_hooks['monsterinsights_dashboard'] . '_page_monsterinsights_addons' ) {
        $settings_page = true;
    }

    if ( ! empty( $admin_page_hooks['monsterinsights_settings'] ) && $current_screen->id === $admin_page_hooks['monsterinsights_settings'] . '_page_monsterinsights_addons' ) {
        $settings_page = true;
    }

    if ( ! empty( $admin_page_hooks['monsterinsights_network'] ) && $current_screen->id === $admin_page_hooks['monsterinsights_network'] . '_page_monsterinsights_addons-network' ) {
        $settings_page = true;
    }

    return $settings_page;
}

/**
 * Maybe refreshes the addons page.
 *
 * @since 6.0.0
 *
 * @return null Return early if not refreshing the addons.
 */
function monsterinsights_maybe_refresh_addons() {
    if ( ! monsterinsights_is_addons_page() ) {
        return;
    }


    if ( ! monsterinsights_is_refreshing_addons() ) {
        return;
    }

    if ( ! monsterinsights_refresh_addons_action() ) {
        return;
    }

    monsterinsights_get_addons_data( monsterinsights_get_license_key() );

}
add_action( 'current_screen', 'monsterinsights_maybe_refresh_addons' );

/**
 * Callback to output the MonsterInsights addons page.
 *
 * @since 6.0.0
 */
function monsterinsights_addons_page() {
    /** 
     * Developer Alert:
     *
     * Per the README, this is considered an internal hook and should
     * not be used by other developers. This hook's behavior may be modified
     * or the hook may be removed at any time, without warning.
     */
    do_action('monsterinsights_head');
    ?>

    <div id="monsterinsights-addon-heading" class="monsterinsights-addons-subheading monsterinsights-clearfix-after">
        <h1><?php esc_html_e( 'MonsterInsights Addons', 'google-analytics-for-wordpress' ); ?></h1>
        <form id="add-on-search">
            <span class="spinner"></span>
            <input id="add-on-searchbox" name="monsterinsights-addon-search" value="" placeholder="<?php esc_attr_e( 'Search MI Addons', 'google-analytics-for-wordpress' ); ?>" />
            <select id="monsterinsights-filter-select">
                <option value="asc"><?php esc_html_e( 'Sort Ascending (A-Z)', 'google-analytics-for-wordpress' ); ?></option>
                <option value="desc"><?php esc_html_e( 'Sort Descending (Z-A)', 'google-analytics-for-wordpress' ); ?></option>
            </select>
        </form>
    </div>

    <div id="monsterinsights-addons" class="wrap">
        <div class="monsterinsights-clear">
            <?php
            /** 
             * Developer Alert:
             *
             * Per the README, this is considered an internal hook and should
             * not be used by other developers. This hook's behavior may be modified
             * or the hook may be removed at any time, without warning.
             */
            ?>
            <?php do_action( 'monsterinsights_addons_section' ); ?>
        </div>
    </div>
    <?php

}

/**
 * Callback for displaying the UI for Addons.
 *
 * @since 6.0.0
 */
function monsterinsights_addons_content() {

    // If error(s) occured during license key verification, display them and exit now.
    if ( false !== monsterinsights_get_license_key_errors() ) {
        ?>
        <div class="error below-h2">
            <p>
                <?php esc_html_e( 'In order to get access to Addons, you need to resolve your license key errors.', 'google-analytics-for-wordpress' ); ?>
            </p>
        </div>
        <?php
        return;
    }

    // Get Addons
    $addons = monsterinsights_get_addons();

    // If no Addon(s) were returned, our API call returned an error.
    // Show an error message with a button to reload the page, which will trigger another API call.
    if ( ! $addons ) {
        ?>
        <form id="monsterinsights-addons-refresh-addons-form" method="post">
            <p>
                <?php esc_html_e( 'There was an issue retrieving the addons for this site. Please click on the button below the refresh the addons data.', 'google-analytics-for-wordpress' ); ?>
            </p>
            <p>
                <a href="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" class="button button-primary"><?php esc_html_e( 'Refresh Addons', 'google-analytics-for-wordpress' ); ?></a>
            </p>
        </form>
        <?php
        return;
    }

    // If here, we have Addons to display, so let's output them now.
    // Get installed plugins and upgrade URL
    $installed_plugins = get_plugins();
    $upgrade_url = monsterinsights_get_upgrade_link();
    ?>
    <div id="monsterinsights-addons">
        <?php
        // Output Addons the User is licensed to use.
        if ( count( $addons['licensed'] )> 0 ) {
            ?>
            <div class="monsterinsights-addons-area licensed" class="monsterinsights-clear">
                <h3><?php esc_html_e( 'Available Addons:', 'google-analytics-for-wordpress' ); ?></h3>
                
                <div id="monsterinsights-addons-licensed" class="monsterinsights-addons">
                    <!-- list container class required for list.js -->
                    <div class="list">
                        <?php
                        foreach ( (array) $addons['licensed'] as $i => $addon ) {
                            monsterinsights_get_addon_card( $addon, $i, true, $installed_plugins );
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        } // Close licensed addons

        // Output Addons the User isn't licensed to use.
        if ( count( $addons['unlicensed'] ) > 0 ) {
            ?>
            <div class="monsterinsights-addons-area unlicensed" class="monsterinsights-clear">
                <h3><?php esc_html_e( 'Unlock More Addons', 'google-analytics-for-wordpress' ); ?></h3>
                <p><?php echo sprintf( esc_html__( '%1$sWant even more addons?%2$sUpgrade your MonsterInsights account%3$s and unlock the following addons:', 'google-analytics-for-wordpress' ), '<strong>', '</strong> <a href="' . $upgrade_url. '">', '</a>' ); ?></p>
                
                <div id="monsterinsights-addons-unlicensed" class="monsterinsights-addons">
                    <!-- list container class required for list.js -->
                    <div class="list">
                        <?php
                        foreach ( (array) $addons['unlicensed'] as $i => $addon ) {
                            monsterinsights_get_addon_card( $addon, $i, false, $installed_plugins );
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        } // Close unlicensed addons
        ?>
    </div>
    <?php

}
add_action( 'monsterinsights_addons_section', 'monsterinsights_addons_content' );

/**
 * Retrieves addons from the stored transient or remote server.
 *
 * @since 6.0.0
 *
 * @return bool | array    false | Array of licensed and unlicensed Addons.
 */
function monsterinsights_get_addons() {

    // Get license key and type.
    $key = monsterinsights_get_license_key();
    $type = monsterinsights_get_license_key_type();
    
    // Get addons data from transient or perform API query if no transient.
    if ( false === ( $addons = get_transient( '_monsterinsights_addons' ) ) ) {
        $addons = monsterinsights_get_addons_data( $key );
    }

    // If no Addons exist, return false
    if ( ! $addons ) {
        return false;
    }

    // Iterate through Addons, to build two arrays: 
    // - Addons the user is licensed to use,
    // - Addons the user isn't licensed to use.
    $results = array(
        'licensed'  => array(),
        'unlicensed'=> array(),
    );
    foreach ( (array) $addons as $i => $addon ) {

        // Determine whether the user is licensed to use this Addon or not.
        if ( 
            empty( $type ) ||
            ( in_array( 'Pro', $addon->categories ) && ( $type != 'pro' && $type != 'master' ) ) ||
            ( in_array( 'Plus', $addon->categories ) && $type != 'plus' && $type != 'pro' && $type != 'master' ) ||
            ( in_array( 'Basic', $addon->categories ) && ( $type != 'basic' && $type != 'plus' && $type != 'pro' && $type != 'master' ) )
        ) {
            // Unlicensed
            $results['unlicensed'][] = $addon;
            continue;
        }

        // Licensed
        $results['licensed'][] = $addon;

    }

    // Return Addons, split by licensed and unlicensed.
    return $results;

}

/**
 * Pings the remote server for addons data.
 *
 * @since 6.0.0
 *
 * @param   string      $key    The user license key.
 * @return  array               Array of addon data otherwise.
 */
function monsterinsights_get_addons_data( $key ) {
    // Get the base class object.
    $base = MonsterInsights();
    $type = monsterinsights_get_license_key_type();
    
    // Get Addons
    // If the key is valid, we'll get personalised upgrade URLs for each Addon (if necessary) and plugin update information.
    if ( $key && $type !== 'basic' ) {
        $addons = $base->license->perform_remote_request( 'get-addons-data-v600', array( 'tgm-updater-key' => $key ) ); 
    } else {
        $addons = $base->license->perform_remote_request( 'get-all-addons-data', array() ); 
    }
    
    // If there was an API error, set transient for only 10 minutes.
    if ( ! $addons ) {
        set_transient( '_monsterinsights_addons', false, 10 * MINUTE_IN_SECONDS );
        return false;
    }

    // If there was an error retrieving the addons, set the error.
    if ( isset( $addons->error ) ) {
        set_transient( '_monsterinsights_addons', false, 10 * MINUTE_IN_SECONDS );
        return false;
    }

    // Otherwise, our request worked. Save the data and return it.
    set_transient( '_monsterinsights_addons', $addons, 4 * HOUR_IN_SECONDS );
    return $addons;

}

/**
 * Flag to determine if addons are being refreshed.
 *
 * @since 6.0.0
 *
 * @return bool True if being refreshed, false otherwise.
 */
function monsterinsights_is_refreshing_addons() {
    return isset( $_POST['google-analytics-for-wordpress-refresh-addons-submit'] );
}

/**
 * Verifies nonces that allow addon refreshing.
 *
 * @since 6.0.0
 *
 * @return bool True if nonces check out, false otherwise.
 */
function monsterinsights_refresh_addons_action() {
    return isset( $_POST['google-analytics-for-wordpress-refresh-addons-submit'] ) && wp_verify_nonce( $_POST['google-analytics-for-wordpress-refresh-addons'], 'google-analytics-for-wordpress-refresh-addons' );
}

/**
 * Retrieve the plugin basename from the plugin slug.
 *
 * @since 6.0.0
 *
 * @param string $slug The plugin slug.
 * @return string      The plugin basename if found, else the plugin slug.
 */
function monsterinsights_get_plugin_basename_from_slug( $slug ) {
    $keys = array_keys( get_plugins() );

    foreach ( $keys as $key ) {
        if ( preg_match( '|^' . $slug . '|', $key ) ) {
            return $key;
        }
    }

    return $slug;

}

/**
 * Outputs the addon "box" on the addons page.
 *
 * @since 6.0.0
 *
 * @param   object  $addon              Addon data from the API / transient call
 * @param   int     $counter            Index of this Addon in the collection
 * @param   bool    $is_licensed        Whether the Addon is licensed for use
 * @param   array   $installed_plugins  Installed WordPress Plugins
 */
function monsterinsights_get_addon_card( $addon, $counter = 0, $is_licensed = false, $installed_plugins = false ) {

    // Setup some vars
    $slug = str_replace( 'monsterinsights-', '', $addon->slug );
    $slug = 'monsterinsights-' . $addon->slug;
    if ( $slug === 'monsterinsights-ecommerce' ) {
        $slug = 'ga-ecommerce';
    } 

    $plugin_basename   = monsterinsights_get_plugin_basename_from_slug( $slug );
    $categories = implode( ',', $addon->categories );
    if ( ! $installed_plugins ) {
        $installed_plugins = get_plugins();
    }
   
    // If the Addon doesn't supply an upgrade_url key, it's because the user hasn't provided a license
    // get_upgrade_link() will return the Lite or Pro link as necessary for us.
    if ( ! isset( $addon->upgrade_url ) ) {
        $addon->upgrade_url = monsterinsights_get_upgrade_link();
    }

    // Link user to doc to install MI pro to install addons
    if ( ! monsterinsights_is_pro_version() && $is_licensed && ! isset( $installed_plugins[ $plugin_basename ] ) ) {
        $addon->url = 'https://www.monsterinsights.com/docs/install-monsterinsights-pro-to-use-addons';
    }

    // Output the card
    ?>
    <div class="monsterinsights-addon">
        <h3 class="monsterinsights-addon-title"><?php echo esc_html( $addon->title ); ?></h3>
        <?php
        if ( ! empty( $addon->image ) ) {
            ?>
            <img class="monsterinsights-addon-thumb" src="<?php echo esc_attr( esc_url( $addon->image ) ); ?>" alt="<?php echo esc_attr( $addon->title ); ?>" />
            <?php
        }
        ?>

        <p class="monsterinsights-addon-excerpt"><?php echo esc_html( $addon->excerpt ); ?></p>

        <?php
        // If the Addon is unlicensed, show the upgrade button 
        if ( ! $is_licensed ) {
            ?>
            <div class="monsterinsights-addon-active monsterinsights-addon-message">
                <div class="interior">
                    <div class="monsterinsights-addon-upgrade">
                        <a href="<?php echo esc_attr( esc_url( $addon->upgrade_url ) ); ?>" target="_blank" rel="noopener noreferrer" referrer="no-referrer" class="button button-primary monsterinsights-addon-upgrade-button"  rel="<?php echo esc_attr( $plugin_basename ); ?>">
                            <?php esc_html_e( 'Upgrade Now', 'google-analytics-for-wordpress' ); ?>
                        </a>
                        <span class="spinner monsterinsights-spinner"></span>
                    </div>
                </div>
            </div>
            <?php
        } else {
            // Addon is licensed

            // If the plugin is not installed, display an install message and button.
            if ( ! isset( $installed_plugins[ $plugin_basename ] ) ) {
                if ( empty( $addon->url ) ) {
                    $addon->url = '';
                }
                ?>
                <div class="monsterinsights-addon-not-installed monsterinsights-addon-message">
                    <div class="interior">
                         <?php if ( monsterinsights_is_pro_version() ) { ?>
                            <span class="addon-status"><?php echo sprintf( esc_html__( 'Status: %1$sNot Installed%2$s', 'google-analytics-for-wordpress' ), '<span>', '</span>' ); ?></span>
                         <?php } ?>
                        <div class="monsterinsights-addon-action">
                                <?php if ( monsterinsights_is_pro_version() ) { ?>
                                    <a class="button button-primary monsterinsights-addon-action-button monsterinsights-install-addon" href="#" rel="<?php echo esc_attr( esc_url( $addon->url ) ); ?>">
                                        <i class="monsterinsights-cloud-download"></i>
                                        <?php esc_html_e( 'Install', 'google-analytics-for-wordpress' ); ?> 
                                    </a>
                                <?php } else { ?>
                                    <a class="button button-primary monsterinsights-addon-action-button" href="<?php echo esc_url( $addon->url ); ?>" rel="noopener noreferrer" referrer="no-referrer" target="_blank">
                                        <i class="monsterinsights-cloud-download"></i>
                                        <?php esc_html_e( "Why can't I install addons?", 'google-analytics-for-wordpress' ); ?> 
                                    </a>
                                <?php } ?>
                            <span class="spinner monsterinsights-spinner"></span>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                // Plugin is installed.
                
                $active = false;
                $ms_active = is_plugin_active_for_network( $plugin_basename );
                $ss_active = is_plugin_active( $plugin_basename );

                if ( is_multisite() && is_network_admin() ) {
                    $active = is_plugin_active_for_network( $plugin_basename );
                } else {
                    $active = is_plugin_active( $plugin_basename );
                }

                if ( $active ) {
                    // Plugin is active. Display the active message and deactivate button.
                    ?>
                    <div class="monsterinsights-addon-active monsterinsights-addon-message">
                        <div class="interior">
                            <?php if ( $ms_active ) { ?>
                            <span class="addon-status"><?php echo sprintf( esc_html__( 'Status: %1$sNetwork Active%2$s', 'google-analytics-for-wordpress'), '<span>', '</span>' ); ?></span>
                            <?php } else { ?>
                            <span class="addon-status"><?php echo sprintf( esc_html__( 'Status: %1$sActive%2$s', 'google-analytics-for-wordpress'), '<span>', '</span>' ); ?></span>
                            <?php } ?> 
                            <?php if ( ( is_multisite() && is_network_admin() && $ms_active ) || ! is_multisite() || ( is_multisite() && !is_network_admin() && !$ms_active && $ss_active ) ) { ?>
                            <div class="monsterinsights-addon-action">
                                <a class="button button-primary monsterinsights-addon-action-button monsterinsights-deactivate-addon" href="#" rel="<?php echo esc_attr( $plugin_basename ); ?>">
                                    <i class="monsterinsights-toggle-on"></i>
                                    <?php if ( is_multisite() && is_network_admin() && $ms_active ) { ?>
                                        <?php esc_html_e( 'Network deactivate', 'google-analytics-for-wordpress' ); ?> 
                                    <?php } else if ( is_multisite() && !is_network_admin() && !$ms_active && $ss_active ) { ?>
                                        <?php esc_html_e( 'Deactivate', 'google-analytics-for-wordpress' ); ?> 
                                    <?php } else { ?>
                                        <?php esc_html_e( 'Deactivate', 'google-analytics-for-wordpress' ); ?> 
                                    <?php } ?> 
                                </a>
                                <span class="spinner google-analytics-for-wordpress-spinner"></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                } else {
                    // Plugin is inactivate. Display the inactivate mesage and activate button.
                    ?>
                    <div class="monsterinsights-addon-inactive monsterinsights-addon-message">
                        <div class="interior">
                            <?php if ( $ms_active ) { ?>
                            <span class="addon-status"><?php echo sprintf( esc_html__( 'Status: %1$sNetwork Inactive%2$s', 'google-analytics-for-wordpress'), '<span>', '</span>' ); ?></span>
                            <?php } else { ?>
                            <span class="addon-status"><?php echo sprintf( esc_html__( 'Status: %1$sInactive%2$s', 'google-analytics-for-wordpress'), '<span>', '</span>' ); ?></span>
                            <?php } ?> 
                            <div class="monsterinsights-addon-action">
                                <a class="button button-primary monsterinsights-addon-action-button monsterinsights-activate-addon" href="#" rel="<?php echo esc_attr( $plugin_basename ); ?>">
                                    <i class="monsterinsights-toggle-on"></i>
                                    <?php if ( is_multisite() && is_network_admin() && ! $ms_active ) { ?>
                                        <?php esc_html_e( 'Network activate', 'google-analytics-for-wordpress' ); ?> 
                                    <?php } else { ?>
                                        <?php esc_html_e( 'Activate', 'google-analytics-for-wordpress' ); ?> 
                                    <?php } ?>
                                </a>
                                <span class="spinner monsterinsights-spinner"></span>
                            </div>

                        </div>
                    </div>
                    <?php
                }
            }
        }
        ?>
    </div>
    <?php
}