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
    $hook             = monsterinsights_get_menu_hook();
    $menu_icon_inline = monsterinsights_get_inline_menu_icon();

    if ( $hook === 'monsterinsights_settings' ) {
        // If dashboards disabled, first settings page
        add_menu_page( __( 'MonsterInsights', 'google-analytics-for-wordpress' ), __( 'Insights', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_settings', 'monsterinsights_settings_page',  $menu_icon_inline, '100.00013467543' );
        $hook = 'monsterinsights_settings';

        add_submenu_page( $hook, __( 'MonsterInsights', 'google-analytics-for-wordpress' ), __( 'Settings', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_settings' );
    } else {
        // if dashboards enabled, first dashboard
        add_menu_page( __( 'General:', 'google-analytics-for-wordpress' ), __( 'Insights', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'monsterinsights_reports', 'monsterinsights_reports_page',  $menu_icon_inline, '100.00013467543' );

        add_submenu_page( $hook, __( 'General Reports:', 'google-analytics-for-wordpress' ), __( 'Reports', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'monsterinsights_reports', 'monsterinsights_reports_page' );

        // then settings page
        add_submenu_page( $hook, __( 'MonsterInsights', 'google-analytics-for-wordpress' ), __( 'Settings', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_settings', 'monsterinsights_settings_page' );

        // Add dashboard submenu.
        add_submenu_page( 'index.php', __( 'General Reports:', 'google-analytics-for-wordpress' ), __( 'Insights', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'admin.php?page=monsterinsights_reports' );
    }

    $submenu_base = add_query_arg( 'page', 'monsterinsights_settings', admin_url( 'admin.php' ) );

    // then tools
    add_submenu_page( $hook, __( 'Tools:', 'google-analytics-for-wordpress' ), __( 'Tools', 'google-analytics-for-wordpress' ), 'manage_options', $submenu_base . '#/tools' );

    // then addons
    $network_key = monsterinsights_is_pro_version() ? MonsterInsights()->license->get_network_license_key() : '';
    if ( ! monsterinsights_is_network_active() || ( monsterinsights_is_network_active() && empty( $network_key ) ) ) {
        add_submenu_page( $hook, __( 'Addons:', 'google-analytics-for-wordpress' ), '<span style="color:' . monsterinsights_menu_highlight_color() . '"> ' . __( 'Addons', 'google-analytics-for-wordpress' ) . '</span>', 'monsterinsights_save_settings', $submenu_base . '#/addons' );
    }

    // Add About us page.
    add_submenu_page( $hook, __( 'About Us:', 'google-analytics-for-wordpress' ), __( 'About Us', 'google-analytics-for-wordpress' ), 'manage_options', $submenu_base . '#/about' );
}
add_action( 'admin_menu', 'monsterinsights_admin_menu' );

function monsterinsights_get_menu_hook() {
    $dashboards_disabled = monsterinsights_get_option( 'dashboards_disabled', false );
    if ( $dashboards_disabled || ( current_user_can( 'monsterinsights_save_settings' ) && ! current_user_can( 'monsterinsights_view_dashboard' ) ) ) {
        return 'monsterinsights_settings';
    } else {
        return 'monsterinsights_reports';
    }
}

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

    $menu_icon_inline = monsterinsights_get_inline_menu_icon();
    $hook = 'monsterinsights_network';
    $submenu_base = add_query_arg( 'page', 'monsterinsights_network', network_admin_url( 'admin.php' ) );
    add_menu_page( __( 'Network Settings:', 'google-analytics-for-wordpress' ), __( 'Insights', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_network', 'monsterinsights_network_page',  $menu_icon_inline, '100.00013467543' );

    add_submenu_page( $hook, __( 'Network Settings:', 'google-analytics-for-wordpress' ), __( 'Network Settings', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_network', 'monsterinsights_network_page' );

    add_submenu_page( $hook, __( 'General Reports:', 'google-analytics-for-wordpress' ), __( 'Reports', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'monsterinsights_reports', 'monsterinsights_reports_page' );

    // then addons
    add_submenu_page( $hook, __( 'Addons:', 'google-analytics-for-wordpress' ), '<span style="color:' . monsterinsights_menu_highlight_color() . '"> ' . __( 'Addons', 'google-analytics-for-wordpress' ) . '</span>', 'monsterinsights_save_settings', $submenu_base . '#/addons' );

    $submenu_base = add_query_arg( 'page', 'monsterinsights_network', network_admin_url( 'admin.php' ) );

    // Add About us page.
    add_submenu_page( $hook, __( 'About Us:', 'google-analytics-for-wordpress' ), __( 'About Us', 'google-analytics-for-wordpress' ), 'manage_options', $submenu_base . '#/about' );
}
add_action( 'network_admin_menu', 'monsterinsights_network_admin_menu', 5 );

/**
 * Adds one or more classes to the body tag in the dashboard.
 *
 * @param  String $classes Current body classes.
 * @return String          Altered body classes.
 */
function monsterinsights_add_admin_body_class( $classes ) {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
    if ( empty( $screen ) || empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
        return $classes;
    }

    return "$classes monsterinsights_page ";
}
add_filter( 'admin_body_class', 'monsterinsights_add_admin_body_class', 10, 1 );

/**
 * Adds one or more classes to the body tag in the dashboard.
 *
 * @param  String $classes Current body classes.
 * @return String          Altered body classes.
 */
function monsterinsights_add_admin_body_class_tools_page( $classes ) {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;

    if ( empty( $screen ) || empty( $screen->id ) || strpos( $screen->id, 'monsterinsights_tools' ) === false || 'insights_page_monsterinsights_tools' === $screen->id  ) {
        return $classes;
    }

    return "$classes insights_page_monsterinsights_tools ";
}
add_filter( 'admin_body_class', 'monsterinsights_add_admin_body_class_tools_page', 10, 1 );

/**
 * Adds one or more classes to the body tag in the dashboard.
 *
 * @param  String $classes Current body classes.
 * @return String          Altered body classes.
 */
function monsterinsights_add_admin_body_class_addons_page( $classes ) {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
    if ( empty( $screen ) || empty( $screen->id ) || strpos( $screen->id, 'monsterinsights_addons' ) === false || 'insights_page_monsterinsights_addons' === $screen->id  ) {
        return $classes;
    }

    return "$classes insights_page_monsterinsights_addons ";
}
add_filter( 'admin_body_class', 'monsterinsights_add_admin_body_class_addons_page', 10, 1 );

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
        $text = sprintf( esc_html__( 'Please rate %sMonsterInsights%s %s on %sWordPress.org%s to help us spread the word. Thank you from the MonsterInsights team!', 'google-analytics-for-wordpress' ), '<strong>', '</strong>', '<a class="monsterinsights-no-text-decoration" href="' .  $url . '" target="_blank" rel="noopener noreferrer"><i class="monstericon-star"></i><i class="monstericon-star"></i><i class="monstericon-star"></i><i class="monstericon-star"></i><i class="monstericon-star"></i></a>', '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">', '</a>' );
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
    // 4. WordPress + PHP min versions
    // 5. (old) Optin setting not configured
    // 6. Manual UA code
    // 7. Automatic updates not configured
    // 8. Woo upsell
    // 9. EDD upsell


    // 1. Google Analytics not authenticated
    if ( ! is_network_admin() && ! monsterinsights_get_ua() ) {

        $submenu_base = is_network_admin() ? add_query_arg( 'page', 'monsterinsights_network', network_admin_url( 'admin.php' ) ) : add_query_arg( 'page', 'monsterinsights_settings', admin_url( 'admin.php' ) );
        $title     = esc_html__( 'Please Setup Website Analytics to See Audience Insights', 'google-analytics-for-wordpress' );
        $primary   = esc_html__( 'Connect MonsterInsights and Setup Website Analytics', 'google-analytics-for-wordpress' );
        $urlone    = is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights-onboarding' ) : admin_url( 'admin.php?page=monsterinsights-onboarding' );
        $secondary = esc_html__( 'Learn More', 'google-analytics-for-wordpress' );
        $urltwo    = $submenu_base . '#/about/getting-started';
        $message   = esc_html__( 'MonsterInsights, WordPress analytics plugin, helps you connect your website with Google Analytics, so you can see how people find and use your website. Over 2 million website owners use MonsterInsights to see the stats that matter and grow their business.', 'google-analytics-for-wordpress' );
        echo '<div class="notice notice-info"><p style="font-weight:700">'. $title .'</p><p>'. $message.'</p><p><a href="'. $urlone .'" class="button-primary">'. $primary .'</a>&nbsp;&nbsp;&nbsp;<a href="'. $urltwo .'" class="button-secondary">'. $secondary .'</a></p></div>';
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

    // 4. Notices for PHP/WP version deprecations
    if ( current_user_can( 'update_core' ) ) {
        global $wp_version;

        // PHP 5.2/5.3
        if ( version_compare( phpversion(), '5.4', '<' ) ) {
            $url = monsterinsights_get_url( 'global-notice', 'settings-page', 'https://www.monsterinsights.com/docs/update-php/' );
            $message = sprintf( esc_html__( 'Your site is running an outdated, insecure version of PHP (%1$s), which could be putting your site at risk for being hacked.%4$sWordPress will stop supporting your PHP version in April, 2019.%4$sUpdating PHP only takes a few minutes and will make your website significantly faster and more secure.%4$s%2$sLearn more about updating PHP%3$s', 'google-analytics-for-wordpress' ), phpversion(), '<a href="' . $url . '" target="_blank">', '</a>', '<br>' );
            echo '<div class="error"><p>'. $message.'</p></div>';
            return;
        }
        // WordPress 3.0 - 4.5
        else if ( version_compare( $wp_version, '4.6', '<' ) ) {
            $url = monsterinsights_get_url( 'global-notice', 'settings-page', 'https://www.monsterinsights.com/docs/update-wordpress/' );
            $message = sprintf( esc_html__( 'Your site is running an outdated version of WordPress (%1$s).%4$sMonsterInsights will stop supporting WordPress versions lower than 4.6 in April, 2019.%4$sUpdating WordPress takes just a few minutes and will also solve many bugs that exist in your WordPress install.%4$s%2$sLearn more about updating WordPress%3$s', 'google-analytics-for-wordpress' ), $wp_version, '<a href="' . $url . '" target="_blank">', '</a>', '<br>' );
            echo '<div class="error"><p>'. $message.'</p></div>';
            return;
        }
        // PHP 5.4/5.5
        // else if ( version_compare( phpversion(), '5.6', '<' ) ) {
        //  $url = monsterinsights_get_url( 'global-notice', 'settings-page', 'https://www.monsterinsights.com/docs/update-php/' );
        //  $message = sprintf( esc_html__( 'Your site is running an outdated, insecure version of PHP (%1$s), which could be putting your site at risk for being hacked.%4$sWordPress will stop supporting your PHP version in April, 2019.%4$sUpdating PHP only takes a few minutes and will make your website significantly faster and more secure.%4$s%2$sLearn more about updating PHP%3$s', 'google-analytics-for-wordpress' ), phpversion(), '<a href="' . $url . '" target="_blank">', '</a>', '<br>' );
        //  echo '<div class="error"><p>'. $message.'</p></div>';
        //  return;
        // }
        // // WordPress 4.6 - 4.8
        // else if ( version_compare( $wp_version, '4.9', '<' ) ) {
        //  $url = monsterinsights_get_url( 'global-notice', 'settings-page', 'https://www.monsterinsights.com/docs/update-wordpress/' );
        //  $message = sprintf( esc_html__( 'Your site is running an outdated version of WordPress (%1$s).%4$sMonsterInsights will stop supporting WordPress versions lower than 4.9 in October, 2019.%4$sUpdating WordPress takes just a few minutes and will also solve many bugs that exist in your WordPress install.%4$s%2$sLearn more about updating WordPress%3$s', 'google-analytics-for-wordpress' ), $wp_version, '<a href="' . $url . '" target="_blank">', '</a>', '<br>' );
        //  echo '<div class="error"><p>'. $message.'</p></div>';
        //  return;
        // }
    }

    // 5. Optin setting not configured
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

    // 6. Authenticate, not manual
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

    // 7. Automatic updates not configured
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

    // 8. WooUpsell
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

    // 9. EDDUpsell
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

    if ( isset( $notices['monsterinsights_cross_domains_extracted'] ) && false === $notices['monsterinsights_cross_domains_extracted'] ) {
        $page = is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_network' ) : admin_url( 'admin.php?page=monsterinsights_settings' );
        $page = $page . '#/advanced';
        $message = sprintf( esc_html__( 'Warning: MonsterInsights found cross-domain settings in the custom code field and converted them to the new settings structure.  %1$sPlease click here to review and remove the code no longer needed.%2$s', 'google-analytics-for-wordpress' ), '<a href="'. esc_url( $page ) . '">', '</a>' );
        echo '<div class="notice notice-success is-dismissible monsterinsights-notice" data-notice="monsterinsights_cross_domains_extracted"><p>'. $message.'</p></div>';
        return;
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
