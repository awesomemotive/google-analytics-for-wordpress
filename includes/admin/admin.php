<?php
/**
 * Admin class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Admin
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register menu items for MonsterInsights.
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_admin_menu() {

    // Get the base class object.
    $base = MonsterInsights();
    
    $dashboards_disabled = monsterinsights_get_option( 'dashboards_disabled', false );
    $is_authed           = ( MonsterInsights()->auth->is_authed() || MonsterInsights()->auth->is_network_authed() );

    $hook = 'monsterinsights_settings';

    if ( $dashboards_disabled || ! $is_authed || ( current_user_can( 'monsterinsights_save_settings' ) && ! current_user_can( 'monsterinsights_view_dashboard' ) ) ) {
        // If dashboards disabled, first settings page
        add_menu_page( __( 'Settings:', 'google-analytics-for-wordpress' ), __( 'Insights', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_settings', 'monsterinsights_settings_page',  plugins_url( 'assets/css/images/menu-icon@2x.png', $base->file ), '100.00013467543' );
        $hook = 'monsterinsights_settings';

        add_submenu_page( $hook, __( 'Settings:', 'google-analytics-for-wordpress' ), __( 'Settings', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_settings' );
        add_submenu_page( $hook, __( 'Settings - Tracking:', 'google-analytics-for-wordpress' ), __( 'Settings - Tracking', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_tracking', 'monsterinsights_tracking_page' );
    } else {
        // if dashboards enabled, first dashboard
        add_menu_page( __( 'General:', 'google-analytics-for-wordpress' ), __( 'Insights', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'monsterinsights_reports', 'monsterinsights_reports_page',  plugins_url( 'assets/css/images/menu-icon@2x.png', $base->file ), '100.00013467543' );

        $hook = 'monsterinsights_reports';

        add_submenu_page( $hook, __( 'General Reports:', 'google-analytics-for-wordpress' ), __( 'Reports', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'monsterinsights_reports', 'monsterinsights_reports_page' );

        // then settings page
        add_submenu_page( $hook, __( 'Settings:', 'google-analytics-for-wordpress' ), __( 'Settings', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_settings', 'monsterinsights_settings_page' );

        add_submenu_page( $hook, __( 'Settings - Tracking:', 'google-analytics-for-wordpress' ), __( 'Settings - Tracking', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_tracking', 'monsterinsights_tracking_page' );

    }
    
    // then tools
    add_submenu_page( $hook, __( 'Tools:', 'google-analytics-for-wordpress' ), __( 'Tools', 'google-analytics-for-wordpress' ), 'manage_options', 'monsterinsights_tools', 'monsterinsights_tools_page' );
    
    // then addons
    $network_key = MonsterInsights()->license->get_network_license_key();
    if ( ! monsterinsights_is_network_active() || ( monsterinsights_is_network_active() && empty( $network_key ) ) ) {
        add_submenu_page( $hook, __( 'Addons:', 'google-analytics-for-wordpress' ), '<span style="color:#7cc048"> ' . __( 'Addons', 'google-analytics-for-wordpress' ) . '</span>', 'monsterinsights_save_settings', 'monsterinsights_addons', 'monsterinsights_addons_page' );
    }
}
add_action( 'admin_menu', 'monsterinsights_admin_menu' );



function monsterinsights_network_admin_menu() {
    // Get the base class object.
    $base = MonsterInsights();

    // First, let's see if this is an MS network enabled plugin. If it is, we should load the license 
    // menu page and the updater on the network panel
    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

    $plugin = plugin_basename( MONSTERINSIGHTS_PLUGIN_FILE );
    if ( ! is_plugin_active_for_network( $plugin ) ) {
        return;
    }

    $hook = 'monsterinsights_network';
    add_menu_page( __( 'Network Settings:', 'google-analytics-for-wordpress' ), __( 'Insights', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_network', 'monsterinsights_network_page',  plugins_url( 'assets/css/images/menu-icon@2x.png', $base->file ), '100.00013467543' );

    add_submenu_page( $hook, __( 'Network Settings:', 'google-analytics-for-wordpress' ), __( 'Network Settings', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_network', 'monsterinsights_network_page' );

    add_submenu_page( $hook, __( 'General Reports:', 'google-analytics-for-wordpress' ), __( 'Reports', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'monsterinsights_reports', 'monsterinsights_reports_page' );

    // then addons
    add_submenu_page( $hook, __( 'Addons:', 'google-analytics-for-wordpress' ), '<span style="color:#7cc048"> ' . __( 'Addons', 'google-analytics-for-wordpress' ) . '</span>', 'monsterinsights_save_settings', 'monsterinsights_addons', 'monsterinsights_addons_page' );
}
add_action( 'network_admin_menu', 'monsterinsights_network_admin_menu', 5 );

/**
 * Adds one or more classes to the body tag in the dashboard.
 *
 * @param  String $classes Current body classes.
 * @return String          Altered body classes.
 */
function monsterinsights_add_admin_body_class( $classes ) {
    $screen = get_current_screen(); 
    if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
        return $classes;
    }
       
    return "$classes monsterinsights_page ";
}
add_filter( 'admin_body_class', 'monsterinsights_add_admin_body_class', 10, 1 );

/**
 * Add a link to the settings page to the plugins list
 *
 * @param array $links array of links for the plugins, adapted when the current plugin is found.
 *
 * @return array $links
 */
function monsterinsights_add_action_links( $links ) {
    $docs = '<a title="' . esc_html__( 'MonsterInsights Knowledge Base', 'google-analytics-for-wordpress' ) . '" href="'. monsterinsights_get_url( 'all-plugins', 'kb-link', "https://www.monsterinsights.com/docs/" ) .'"">' . esc_html__( 'Documentation', 'google-analytics-for-wordpress' ) . '</a>';
    array_unshift( $links, $docs );

    // If lite, show a link where they can get pro from
    if ( ! monsterinsights_is_pro_version() ) {
        $get_pro = '<a title="' . esc_html__( 'Get MonsterInsights Pro', 'google-analytics-for-wordpress' ) .'" href="'. monsterinsights_get_upgrade_link( 'all-plugins', 'upgrade-link', "https://www.monsterinsights.com/docs/" ) .'">' . esc_html__( 'Get MonsterInsights Pro', 'google-analytics-for-wordpress' ) . '</a>';
        array_unshift( $links, $get_pro );
    }

    // If Lite, support goes to forum. If pro, it goes to our website
    if ( monsterinsights_is_pro_version() ) {
        $support = '<a title="MonsterInsights Pro Support" href="'. monsterinsights_get_url( 'all-plugins', 'pro-support-link', "https://www.monsterinsights.com/my-account/support/" ) .'">' . esc_html__( 'Support', 'google-analytics-for-wordpress' ) . '</a>';
        array_unshift( $links, $support );
    } else {
        $support = '<a title="MonsterInsights Lite Support" href="'. monsterinsights_get_url( 'all-plugins', 'lite-support-link', "https://www.monsterinsights.com/lite-support/" ) .'">' . esc_html__( 'Support', 'google-analytics-for-wordpress' ) . '</a>';
        array_unshift( $links, $support );
    }

    $settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=monsterinsights_settings' ) ) . '">' . esc_html__( 'Settings', 'google-analytics-for-wordpress' ) . '</a>';
    array_unshift( $links, $settings_link );

    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( MONSTERINSIGHTS_PLUGIN_FILE ), 'monsterinsights_add_action_links' );



/**
 * Loads a partial view for the Administration screen
 * 
 * @access public
 * @since 6.0.0
 *
 * @param   string  $template   PHP file at includes/admin/partials, excluding file extension
 * @param   array   $data       Any data to pass to the view
 * @return  void
 */
function monsterinsights_load_admin_partial( $template, $data = array() ) {

    if ( monsterinsights_is_pro_version() ) {
        $dir = trailingslashit( plugin_dir_path( MonsterInsights()->file ) . 'pro/includes/admin/partials' );
    
        if ( file_exists( $dir . $template . '.php' ) ) {
            require_once(  $dir . $template . '.php' );
            return true;
        }
    } else {
        $dir = trailingslashit( plugin_dir_path( MonsterInsights()->file ) . 'lite/includes/admin/partials' );
    
        if ( file_exists( $dir . $template . '.php' ) ) {
            require_once(  $dir . $template . '.php' );
            return true;
        }   
    }
        
    $dir = trailingslashit( plugin_dir_path( MonsterInsights()->file ) . 'includes/admin/partials' );

    if ( file_exists( $dir . $template . '.php' ) ) {
        require_once(  $dir . $template . '.php' );
        return true;
    }
                
    return false;
}


/**
 * Outputs the MonsterInsights Header.
 *
 * @since 6.0.0
 */
function monsterinsights_admin_header() {
    // Get the current screen, and check whether we're viewing a MonsterInsights screen;
    $screen = get_current_screen(); 
    if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
        return;
    }

    // If here, we're on an MonsterInsights screen, so output the header.
    monsterinsights_load_admin_partial( 'header', array(
        'mascot'   => plugins_url( 'assets/css/images/mascot.png', MonsterInsights()->file ),
        'logo'     => plugins_url( 'assets/css/images/logo.png', MonsterInsights()->file ),
        '2xmascot' => plugins_url( 'assets/css/images/mascot@2x.png', MonsterInsights()->file ),
        '2xlogo'   => plugins_url( 'assets/css/images/logo@2x.png', MonsterInsights()->file ),
    ) );
}
add_action( 'in_admin_header','monsterinsights_admin_header', 100 );

function monsterinsights_welcome_redirect() {
    // Bail if no activation redirect
    if ( ! get_transient( '_monsterinsights_activation_redirect' ) ) {
        return;
    }

    // Delete the redirect transient
    delete_transient( '_monsterinsights_activation_redirect' );

    // Bail if activating from network, or bulk
    if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
        return;
    }

    $upgrade = get_option( 'monsterinsights_version_upgraded_from' );
    if( ! $upgrade ) { // First time install
        //wp_safe_redirect( admin_url( 'admin.php?page=monsterinsights_settings#monsterinsights-main-tab-general' ) ); exit;
    } else { // Update
        return;
        //wp_safe_redirect( admin_url( 'admin.php?page=monsterinsights_settings#monsterinsights-main-tab-general' ) ); exit;
    }
}
//add_action( 'admin_init', 'monsterinsights_welcome_redirect', 11 ); @todo: Investigate

/**
 * When user is on a MonsterInsights related admin page, display footer text
 * that graciously asks them to rate us.
 *
 * @since 6.0.0
 * @param string $text
 * @return string
 */
function monsterinsights_admin_footer( $text ) {
    global $current_screen;
    if ( ! empty( $current_screen->id ) && strpos( $current_screen->id, 'monsterinsights' ) !== false ) {
        $url  = 'https://wordpress.org/support/view/plugin-reviews/google-analytics-for-wordpress?filter=5';
        $text = sprintf( esc_html__( 'Please rate %sMonsterInsights%s %s on %sWordPress.org%s to help us spread the word. Thank you from the MonsterInsights team!', 'google-analytics-for-wordpress' ), '<strong>', '</strong>', '<a class="monsterinsights-no-text-decoration" href="' .  $url . '" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a>', '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">', '</a>' );
    }
    return $text;
}
add_filter( 'admin_footer_text', 'monsterinsights_admin_footer', 1, 2 );

function monsterinsights_admin_setup_notices() {

    // Don't show on MonsterInsights pages
    $screen = get_current_screen(); 
    if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) !== false ) {
        return;
    }

    // Make sure they have the permissions to do something
    if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
        return;
    }

    // Priority:
    // 1. Google Analytics not authenticated
    // 2. License key not entered for pro
    // 3. License key not valid/okay for pro
    // 4. Optin setting not configured
    // 5. Automatic updates not configured


    // 1. Google Analytics not authenticated
    if ( ! is_network_admin() && ! monsterinsights_get_ua() ) {
        $page = admin_url( 'admin.php?page=monsterinsights_settings' );
        $message = sprintf( esc_html__( 'Please configure your %1$sGoogle Analytics settings%2$s!', 'google-analytics-for-wordpress' ),'<a href="' . $page . '">', '</a>' );
        echo '<div class="error"><p>'. $message.'</p></div>';
        return;
    }

    // 2. License key not entered for pro
    $key = monsterinsights_is_pro_version() ? MonsterInsights()->license->get_license_key() : '';
    if ( monsterinsights_is_pro_version() && empty( $key ) ) {
        $page = is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_network' ) : admin_url( 'admin.php?page=monsterinsights_settings' );
        $message = sprintf( esc_html__( 'Warning: No valid license key has been entered for MonsterInsights. You are currently not getting updates, and are not able to view reports. %1$sPlease click here to enter your license key and begin receiving updates and reports.%2$s', 'google-analytics-for-wordpress' ), '<a href="'. esc_url( $page ) . '">', '</a>' );
        echo '<div class="error"><p>'. $message.'</p></div>';
        return;
    }

    // 3. License key not valid/okay for pro
    if ( monsterinsights_is_pro_version() ) {
        $message = '';
        if ( MonsterInsights()->license->get_site_license_key() ){
            if ( MonsterInsights()->license->site_license_expired() ) {
                $message = sprintf( esc_html__( 'Your license key for MonsterInsights has expired. %1$sPlease click here to renew your license key.%2$s', 'google-analytics-for-wordpress' ), '<a href="'. monsterinsights_get_url( 'admin-notices', 'expired-license', "https://www.monsterinsights.com/login/" ) .'" target="_blank" rel="noopener noreferrer" referrer="no-referrer">', '</a>' );
            } else if ( MonsterInsights()->license->site_license_disabled() ) {
                $message = esc_html__( 'Your license key for MonsterInsights has been disabled. Please use a different key.', 'google-analytics-for-wordpress' );
            } else if ( MonsterInsights()->license->site_license_invalid() ) {
                $message = esc_html__( 'Your license key for MonsterInsights is invalid. The key no longer exists or the user associated with the key has been deleted. Please use a different key.', 'google-analytics-for-wordpress' );
            }
        } else if ( MonsterInsights()->license->get_network_license_key() ) {
            if ( MonsterInsights()->license->network_license_expired() ) {
                $message = sprintf( esc_html__( 'Your network license key for MonsterInsights has expired. %1$sPlease click here to renew your license key.%2$s', 'google-analytics-for-wordpress' ), '<a href="'. monsterinsights_get_url( 'admin-notices', 'expired-license', "https://www.monsterinsights.com/login/" ) .'" target="_blank" rel="noopener noreferrer" referrer="no-referrer">', '</a>' );
            } else if ( MonsterInsights()->license->network_license_disabled() ) {
                $message = esc_html__( 'Your network license key for MonsterInsights has been disabled. Please use a different key.', 'google-analytics-for-wordpress' );
            } else if ( MonsterInsights()->license->network_license_invalid() ) {
                $message = esc_html__( 'Your network license key for MonsterInsights is invalid. The key no longer exists or the user associated with the key has been deleted. Please use a different key.', 'google-analytics-for-wordpress' );
            }
        }
        if ( ! empty( $message ) ) {
            echo '<div class="error"><p>'. $message.'</p></div>';
            return;
        }
    }

    // 4. Optin setting not configured
    // if ( ! is_network_admin() ) {
    //     if ( ! get_option( 'monsterinsights_tracking_notice' ) ) {
    //         if ( ! monsterinsights_get_option( 'anonymous_data', false ) ) {
    //             if ( ! monsterinsights_is_dev_url( network_site_url( '/' ) ) ) {
    //                 if ( monsterinsights_is_pro_version() ) {
    //                     monsterinsights_update_option( 'anonymous_data', 1 );
    //                     return;
    //                 }
    //                 $optin_url  = add_query_arg( 'mi_action', 'opt_into_tracking' );
    //                 $optout_url = add_query_arg( 'mi_action', 'opt_out_of_tracking' );
    //                 echo '<div class="updated"><p>';
    //                 echo esc_html__( 'Allow MonsterInsights to track plugin usage? Opt-in to tracking and our newsletter to stay informed of the latest changes to MonsterInsights and help us ensure compatibility.', 'google-analytics-for-wordpress' );
    //                 echo '&nbsp;<a href="' . esc_url( $optin_url ) . '" class="button-secondary">' . __( 'Allow', 'google-analytics-for-wordpress' ) . '</a>';
    //                 echo '&nbsp;<a href="' . esc_url( $optout_url ) . '" class="button-secondary">' . __( 'Do not allow', 'google-analytics-for-wordpress' ) . '</a>';
    //                 echo '</p></div>';
    //                 return;
    //             } else {
    //                 // is testing site
    //                  update_option( 'monsterinsights_tracking_notice', '1' );
    //             }
    //         }
    //     }
    // }

    $notices   = get_option( 'monsterinsights_notices' );
    if ( ! is_array( $notices ) ) {
        $notices = array();
    }

    // 5. Authenticate, not manual
    $authed   = MonsterInsights()->auth->is_authed() || MonsterInsights()->auth->is_network_authed();
    $url      = is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_network' ) : admin_url( 'admin.php?page=monsterinsights_settings' );

    if ( empty( $authed ) && ! isset( $notices['monsterinsights_auth_not_manual' ] ) ) { 
        echo '<div class="notice notice-info is-dismissible monsterinsights-notice" data-notice="monsterinsights_auth_not_manual">';
            echo '<p>';
            echo sprintf( esc_html__( 'Important: You are currently using manual UA code output. We highly recommend %1$sauthenticating with MonsterInsights%2$s so that you can access our new reporting area and take advantage of new MonsterInsights features.', 'google-analytics-for-wordpress' ), '<a href="' . $url .'">', '</a>' ); 
            echo '</p>';
        echo '</div>';
        return;
    }

    // 6. Automatic updates not configured
    // if ( ! is_network_admin() ) {
    //     $updates   = monsterinsights_get_option( 'automatic_updates', false );
    //     $url       = admin_url( 'admin.php?page=monsterinsights_settings' );

    //     if ( empty( $updates) && ! isset( $notices['monsterinsights_automatic_updates' ] ) ) { 
    //         echo '<div class="notice notice-info is-dismissible monsterinsights-notice" data-notice="monsterinsights_automatic_updates">';
    //             echo '<p>';
    //             echo sprintf( esc_html__( 'Important: Please %1$sconfigure the Automatic Updates Settings%2$s in MonsterInsights.', 'google-analytics-for-wordpress' ), '<a href="' . $url .'">', '</a>' ); 
    //             echo '</p>';
    //         echo '</div>';
    //         return;
    //     }
    // }

    // 7. WooUpsell
    if ( ! monsterinsights_is_pro_version() && class_exists( 'WooCommerce' ) ) {
        if ( ! isset( $notices['monsterinsights_woocommerce_tracking_available' ] ) ) { 
            echo '<div class="notice notice-success is-dismissible monsterinsights-notice monsterinsights-wooedd-upsell-row" data-notice="monsterinsights_woocommerce_tracking_available">';
                echo '<div class="monsterinsights-wooedd-upsell-left">';
                    echo '<p><strong>';
                    echo esc_html( 'Enhanced Ecommerce Analytics for Your WooCommerce Store', 'google-analytics-for-wordpress' );
                    echo '</strong></p>';
                    echo '<img class="monsterinsights-wooedd-upsell-image monsterinsights-wooedd-upsell-image-small" src="' . trailingslashit( MONSTERINSIGHTS_PLUGIN_URL ) . 'assets/images/upsell/woo-edd-upsell.png">';
                    echo '<p>';
                    echo esc_html( 'MonsterInsights Pro gives you detailed stats and insights about your customers.', 'google-analytics-for-wordpress' );
                    echo '</p>';
                    echo '<p>';
                    echo esc_html( 'This helps you make data-driven decisions about your content, and marketing strategy so you can increase your website traffic, leads, and sales.', 'google-analytics-for-wordpress' );
                    echo '</p>';
                    echo '<p>';
                    echo esc_html( 'Pro customers also get Form Tracking, Custom Dimensions Tracking, UserID Tracking and much more.', 'google-analytics-for-wordpress' );
                    echo '</p>';
                    echo '<p>';
                    echo esc_html( 'Start making data-driven decisions to grow your business.', 'google-analytics-for-wordpress' );
                    echo '</p>';
                    echo sprintf( esc_html__( '%1$sGet MonsterInsights Pro%2$s', 'google-analytics-for-wordpress' ), '<a class="button button-primary button-hero" href="'. monsterinsights_get_upgrade_link( 'admin-notices', 'woocommerce-upgrade' ) .'">', ' &raquo;</a>' ); 
                    echo '</p>';
                echo '</div><div class="monsterinsights-wooedd-upsell-right">';
                    echo '<img class="monsterinsights-wooedd-upsell-image monsterinsights-wooedd-upsell-image-large" src="' . trailingslashit( MONSTERINSIGHTS_PLUGIN_URL ) . 'assets/images/upsell/woo-edd-upsell.png">';
                echo '</div>';
            echo '</div>';
            return;
        }
    }

    // 8. EDDUpsell
    if ( ! monsterinsights_is_pro_version() && class_exists( 'Easy_Digital_Downloads' ) ) {
        if ( ! isset( $notices['monsterinsights_edd_tracking_available' ] ) ) { 
            echo '<div class="notice notice-success is-dismissible monsterinsights-notice monsterinsights-wooedd-upsell-row" data-notice="monsterinsights_edd_tracking_available">';
                echo '<div class="monsterinsights-wooedd-upsell-left">';
                    echo '<p><strong>';
                    echo esc_html( 'Enhanced Ecommerce Analytics for Your Easy Digital Downloads Store', 'google-analytics-for-wordpress' );
                    echo '</strong></p>';
                    echo '<img class="monsterinsights-wooedd-upsell-image monsterinsights-wooedd-upsell-image-small" src="' . trailingslashit( MONSTERINSIGHTS_PLUGIN_URL ) . 'assets/images/upsell/woo-edd-upsell.png">';
                    echo '<p>';
                    echo esc_html( 'MonsterInsights Pro gives you detailed stats and insights about your customers.', 'google-analytics-for-wordpress' );
                    echo '</p>';
                    echo '<p>';
                    echo esc_html( 'This helps you make data-driven decisions about your content, and marketing strategy so you can increase your website traffic, leads, and sales.', 'google-analytics-for-wordpress' );
                    echo '</p>';
                    echo '<p>';
                    echo esc_html( 'Pro customers also get Form Tracking, Custom Dimensions Tracking, UserID Tracking and much more.', 'google-analytics-for-wordpress' );
                    echo '</p>';
                    echo '<p>';
                    echo esc_html( 'Start making data-driven decisions to grow your business.', 'google-analytics-for-wordpress' );
                    echo '</p>';
                    echo sprintf( esc_html__( '%1$sGet MonsterInsights Pro%2$s', 'google-analytics-for-wordpress' ), '<a class="button button-primary button-hero" href="'. monsterinsights_get_upgrade_link( 'admin-notices', 'edd-upgrade' ) .'">', ' &raquo;</a>' ); 
                    echo '</p>';
                echo '</div><div class="monsterinsights-wooedd-upsell-right">';
                    echo '<img class="monsterinsights-wooedd-upsell-image monsterinsights-wooedd-upsell-image-large" src="' . trailingslashit( MONSTERINSIGHTS_PLUGIN_URL ) . 'assets/images/upsell/woo-edd-upsell.png">';
                echo '</div>';
            echo '</div>';
            return;
        }
    }
}
add_action( 'admin_notices', 'monsterinsights_admin_setup_notices' );
add_action( 'network_admin_notices', 'monsterinsights_admin_setup_notices' );


// AM Notices
function monsterinsights_am_notice_optout( $super_admin ) {
    if ( monsterinsights_get_option( 'hide_am_notices', false ) || monsterinsights_get_option( 'network_hide_am_notices', false ) ) {
        return false;
    }
    return $super_admin;
}
add_filter( "am_notifications_display", 'monsterinsights_am_notice_optout', 10, 1 );