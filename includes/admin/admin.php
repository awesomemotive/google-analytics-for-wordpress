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

    $hook = 'monsterinsights_settings';

    if ( $dashboards_disabled || ( current_user_can( 'monsterinsights_save_settings' ) && ! current_user_can( 'monsterinsights_view_dashboard' ) ) ) {
        // If dashboards disabled, first settings page
        add_menu_page( __( 'Settings:', 'google-analytics-for-wordpress' ), __( 'Insights', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_settings', 'monsterinsights_settings_page',  plugins_url( 'assets/css/images/menu-icon@2x.png', $base->file ), '100.00013467543' );
        $hook = 'monsterinsights_settings';

        add_submenu_page( $hook, __( 'Settings:', 'google-analytics-for-wordpress' ), __( 'Settings', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_settings' );
    } else {
        // if dashboards enabled, first dashboard
        add_menu_page( __( 'Dashboard:', 'google-analytics-for-wordpress' ), __( 'Insights', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'monsterinsights_dashboard', 'monsterinsights_dashboard_page',  plugins_url( 'assets/css/images/menu-icon@2x.png', $base->file ), '100.00013467543' );

        $hook = 'monsterinsights_dashboard';

        add_submenu_page( $hook, __( 'Dashboard:', 'google-analytics-for-wordpress' ), __( 'Dashboard', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'monsterinsights_dashboard' );

        // then settings page
        add_submenu_page( $hook, __( 'Settings:', 'google-analytics-for-wordpress' ), __( 'Settings', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_settings', 'monsterinsights_settings_page' );
    }

    if ( ! $dashboards_disabled ) {
        // then reports
        add_submenu_page( $hook, __( 'Reports:', 'google-analytics-for-wordpress' ), __( 'Reports', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'monsterinsights_reports', 'monsterinsights_reports_page' );
    }
    
    // then tools
    //add_submenu_page( $hook, __( 'Tools:', 'google-analytics-for-wordpress' ), __( 'Tools', 'google-analytics-for-wordpress' ), 'manage_options', 'monsterinsights_tools', 'monsterinsights_tools_page' );
    
    // then addons
    $network_license = get_site_option( 'monsterinsights_license' );
    if ( ! monsterinsights_is_network_active() || ( monsterinsights_is_network_active() && empty( $network_license ) ) ) {
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
    add_menu_page( __( 'Network Settings', 'google-analytics-for-wordpress' ), __( 'Insights', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_network', 'monsterinsights_network_page',  plugins_url( 'assets/css/images/menu-icon@2x.png', $base->file ), '100.00013467543' );

    add_submenu_page( $hook, __( 'Network Settings', 'google-analytics-for-wordpress' ), __( 'Network Settings', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_network', 'monsterinsights_network_page' );

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


function monsterinsights_menu_icon_css() {
    // Force correct sizing for retina vs non-retina display
    ?>
    <style type="text/css">
        #toplevel_page_monsterinsights_dashboard .wp-menu-image img,
        #toplevel_page_monsterinsights_settings .wp-menu-image img,
        #toplevel_page_monsterinsights_network .wp-menu-image img { 
            width: 18px; 
            height: 18px;
            padding-top: 7px;
        }
    </style>
    <?php
}
add_action( 'admin_head', 'monsterinsights_menu_icon_css' );

/**
 * Add a link to the settings page to the plugins list
 *
 * @param array $links array of links for the plugins, adapted when the current plugin is found.
 *
 * @return array $links
 */
function monsterinsights_add_action_links( $links ) {
    $docs = '<a title="MonsterInsights Knowledge Base" href="https://www.monsterinsights.com/docs/">' . esc_html__( 'Documentation', 'google-analytics-for-wordpress' ) . '</a>';
    array_unshift( $links, $docs );

    // If lite, show a link where they can get pro from
    if ( ! monsterinsights_is_pro_version() ) {
        $get_pro = '<a title="Get MonsterInsights Pro" href="https://www.monsterinsights.com/upgrade-to-pro/">' . esc_html__( 'Get MonsterInsights Pro', 'google-analytics-for-wordpress' ) . '</a>';
        array_unshift( $links, $get_pro );
    }   
    
    // If Lite, support goes to forum. If pro, it goes to our website
    if ( monsterinsights_is_pro_version() ) {
        $support = '<a title="MonsterInsights Pro Support" href="https://www.monsterinsights.com/my-account/support/">' . esc_html__( 'Support', 'google-analytics-for-wordpress' ) . '</a>';
        array_unshift( $links, $support );
    } else {
        $support = '<a title="MonsterInsights Lite Support" href="https://wordpress.org/support/plugin/google-analytics-for-wordpress">' . esc_html__( 'Support', 'google-analytics-for-wordpress' ) . '</a>';
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
    // Get the base class object.
    $base = MonsterInsights();

    if ( monsterinsights_is_pro_version() ) {
        $dir = trailingslashit( plugin_dir_path( $base->file ) . 'pro/includes/admin/partials' );
    
        if ( file_exists( $dir . $template . '.php' ) ) {
            require_once(  $dir . $template . '.php' );
            return true;
        }
    } else {
        $dir = trailingslashit( plugin_dir_path( $base->file ) . 'lite/includes/admin/partials' );
    
        if ( file_exists( $dir . $template . '.php' ) ) {
            require_once(  $dir . $template . '.php' );
            return true;
        }   
    }
        
    $dir = trailingslashit( plugin_dir_path( $base->file ) . 'includes/admin/partials' );

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
    // Get the base class object.
    $base = MonsterInsights();
    
    // Get the current screen, and check whether we're viewing a MonsterInsights screen;
    $screen = get_current_screen(); 
    if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
        return;
    }

    // If here, we're on an MonsterInsights screen, so output the header.
    monsterinsights_load_admin_partial( 'header', array(
        'mascot'   => plugins_url( 'assets/css/images/mascot.png', $base->file ),
        'logo'     => plugins_url( 'assets/css/images/logo.png', $base->file ),
        '2xmascot' => plugins_url( 'assets/css/images/mascot@2x.png', $base->file ),
        '2xlogo'   => plugins_url( 'assets/css/images/logo@2x.png', $base->file ),
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
 * When user is on a WPForms related admin page, display footer text
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