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
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Register menu items for MonsterInsights.
 *
 * @return void
 * @since 6.0.0
 * @access public
 *
 */
function monsterinsights_admin_menu()
{
	$hook             = monsterinsights_get_menu_hook();
	$menu_icon_inline = monsterinsights_get_inline_menu_icon();
	$new_indicator     = sprintf(
		'<span class="monsterinsights-menu-new-indicator">&nbsp;%s</span>',
		__( 'NEW', 'google-analytics-for-wordpress' )
	);

	$menu_notification_indicator = MonsterInsights()->notifications->get_menu_count();

	if ( MonsterInsights()->license->license_expired() ) {
		$menu_notification_indicator = MonsterInsights()->notifications->get_license_expired_indicator();
	}

	if ($hook === 'monsterinsights_settings') {
		// If dashboards disabled, first settings page
		add_menu_page(__('MonsterInsights', 'google-analytics-for-wordpress'), __('Insights', 'google-analytics-for-wordpress') . $menu_notification_indicator, 'monsterinsights_save_settings', 'monsterinsights_settings', 'monsterinsights_settings_page', $menu_icon_inline, '100.00013467543');
		$hook = 'monsterinsights_settings';

		add_submenu_page($hook, __('MonsterInsights', 'google-analytics-for-wordpress'), __('Settings', 'google-analytics-for-wordpress'), 'monsterinsights_save_settings', 'monsterinsights_settings');
	} else {
		// if dashboards enabled, first dashboard
		add_menu_page(__('General:', 'google-analytics-for-wordpress'), __('Insights', 'google-analytics-for-wordpress') . $menu_notification_indicator, 'monsterinsights_view_dashboard', 'monsterinsights_reports', 'monsterinsights_reports_page', $menu_icon_inline, '100.00013467543');

		if ( ! MonsterInsights()->setup_checklist->is_dismissed() ) {
			add_submenu_page( $hook, __( 'Setup Checklist', 'google-analytics-for-wordpress' ), __( 'Setup Checklist', 'google-analytics-for-wordpress' ) . MonsterInsights()->setup_checklist->get_menu_count(), 'monsterinsights_save_settings', 'monsterinsights_settings#/setup-checklist', 'monsterinsights_settings_page' );
		}

		add_submenu_page( $hook, __( 'General Reports:', 'google-analytics-for-wordpress' ), __( 'Reports', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'monsterinsights_reports', 'monsterinsights_reports_page' );

		// then settings page
		add_submenu_page( $hook, __( 'MonsterInsights', 'google-analytics-for-wordpress' ), __( 'Settings', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', 'monsterinsights_settings', 'monsterinsights_settings_page' );

		// Add dashboard submenu.
		add_submenu_page( 'index.php', __( 'General Reports:', 'google-analytics-for-wordpress' ), __( 'Insights', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'admin.php?page=monsterinsights_reports' );

		// If the setup checklist is not dismissed, remove the own submenu of `Insights` main menu that we added on line 52.
		// This way the Checklist will be the first submenu which is an important thing for onboarding.
		if ( ! MonsterInsights()->setup_checklist->is_dismissed() ) {

			// Check if the user has the capability to save settings and view dashboard.
			// We should skip this for editors that have only view capability have only item in the submenu, removing that would break the menu.
			if ( ! ( ! current_user_can( 'monsterinsights_save_settings' ) && current_user_can( 'monsterinsights_view_dashboard' ) ) ) {
				// Remove own submenu of `Insights` main menu.
				remove_submenu_page( 'monsterinsights_reports', 'monsterinsights_reports' );
			}
		}
	}

	$submenu_base = add_query_arg('page', 'monsterinsights_settings', admin_url('admin.php'));

    //  Site Notes
	add_submenu_page( $hook, __( 'Site Notes:', 'google-analytics-for-wordpress' ), __( 'Site Notes', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', $submenu_base . '#/site-notes' );

	//  AI Insights
	// translators: Icon
	add_submenu_page( $hook, __( 'AI Insights:', 'google-analytics-for-wordpress' ), sprintf( __( '%s AI Insights', 'google-analytics-for-wordpress' ), monsterinsights_get_ai_menu_icon() ), 'monsterinsights_save_settings', 'admin.php?page=monsterinsights_reports#/ai-insights' );

	$license_type = MonsterInsights()->license->get_license_type();

	//  AI Chat
	if ( ! monsterinsights_is_pro_version() || 'plus' === $license_type ) {
		// translators: Placeholder adds an svg icon
		add_submenu_page( $hook, __( 'Conversations AI:', 'google-analytics-for-wordpress' ), sprintf( __( '%s Conversations AI', 'google-analytics-for-wordpress' ), monsterinsights_get_ai_menu_icon() ), 'monsterinsights_save_settings', 'admin.php?page=monsterinsights_reports#/ai-insights/chat' );
	}

	// Add Popular Posts menu item.
	add_submenu_page( $hook, __( 'Popular Posts:', 'google-analytics-for-wordpress' ), __( 'Popular Posts', 'google-analytics-for-wordpress' ), 'monsterinsights_save_settings', $submenu_base . '#/popular-posts' );

	// Add submenu under `Insights` main menu for user journey report.
	add_submenu_page( $hook, __( 'User Journey:', 'google-analytics-for-wordpress' ), __( 'User Journey', 'google-analytics-for-wordpress' ), 'monsterinsights_view_dashboard', 'admin.php?page=monsterinsights_reports#/user-journey-report' );

	if ( function_exists( 'aioseo' ) ) {
		$seo_url = monsterinsights_aioseo_dashboard_url();
	} else {
		$seo_url = $submenu_base . '#/seo';
	}
	// then SEO
	add_submenu_page($hook, __('SEO', 'google-analytics-for-wordpress'), __('SEO', 'google-analytics-for-wordpress'), 'manage_options', $seo_url);

	// then tools
	add_submenu_page($hook, __('Tools:', 'google-analytics-for-wordpress'), __('Tools', 'google-analytics-for-wordpress'), 'manage_options', $submenu_base . '#/tools');

	// then addons
	$network_key = monsterinsights_is_pro_version() ? MonsterInsights()->license->get_network_license_key() : '';
	if (!monsterinsights_is_network_active() || (monsterinsights_is_network_active() && empty($network_key))) {
		add_submenu_page($hook, __('Addons:', 'google-analytics-for-wordpress'), '<span style="color:' . monsterinsights_menu_highlight_color() . '"> ' . __('Addons', 'google-analytics-for-wordpress') . '</span>', 'monsterinsights_save_settings', $submenu_base . '#/addons');
	}

	add_submenu_page(
		$hook,
		__('UserFeedback:', 'google-analytics-for-wordpress'),
		__('UserFeedback', 'google-analytics-for-wordpress') . $new_indicator,
		'manage_options',
		$submenu_base . '#/userfeedback'
	);

	// then About Us page.
	add_submenu_page($hook, __('About Us:', 'google-analytics-for-wordpress'), __('About Us', 'google-analytics-for-wordpress'), 'manage_options', $submenu_base . '#/about');

	if (!monsterinsights_is_pro_version() && !strstr(plugin_basename(__FILE__), 'dashboard-for')) {
		// automated promotion
		monsterinsights_automated_menu($hook);
	}

	add_submenu_page($hook, __('Growth Tools:', 'google-analytics-for-wordpress'), __('Growth Tools', 'google-analytics-for-wordpress'), 'manage_options', $submenu_base . '#/growth-tools');

	// then Upgrade To Pro.
	if (!monsterinsights_is_pro_version()) {
		add_submenu_page($hook, __('Upgrade to Pro:', 'google-analytics-for-wordpress'), '<span class="monsterinsights-upgrade-submenu"> ' . __('Upgrade to Pro', 'google-analytics-for-wordpress') . '</span>', 'monsterinsights_save_settings', monsterinsights_get_upgrade_link('admin-menu', 'submenu', "https://www.monsterinsights.com/lite/"));
	}

	if ( class_exists( 'WooCommerce' ) ) {
		// Show the Payments submenu only when WooCommerce is active.
		add_submenu_page(
			$hook,
			__('Payments:', 'google-analytics-for-wordpress'),
			__('Payments', 'google-analytics-for-wordpress'),
			'manage_options',
			$submenu_base . '#/payments'
		);
	}
}

add_action('admin_menu', 'monsterinsights_admin_menu');


function monsterinsights_automated_menu($hook){
	$display = false;

	$now = apply_filters('monsterinsights_automated_promotion_date', wp_date('M d, Y h:i:s a'));
	$conditions = [
		[
			'title' => __('Earth Day', 'google-analytics-for-wordpress'),
			'start_time' => wp_date('M d, Y h:i:s a', strtotime('Apr 17, 2023')),
			'end_time' => wp_date('M d, Y h:i:s a', strtotime('Apr 18, 2023')),
			'utm_campaign' => 'lite-promo',
			'utm_source' => 'liteplugin',
			'utm_medium' => 'earth-day',
		],
		[
			'title' => __('Cinco De Mayo!', 'google-analytics-for-wordpress'),
			'start_time' => wp_date('M d, Y h:i:s a', strtotime('May 01, 2023')),
			'end_time' => wp_date('M d, Y h:i:s a', strtotime('May 08, 2023')),
			'utm_campaign' => 'lite-promo',
			'utm_source' => 'liteplugin',
			'utm_medium' => 'cinco-de-mayo',
		],
		[
			'title' => __('Upgrade to GA4', 'google-analytics-for-wordpress'),
			'start_time' => wp_date('M d, Y h:i:s a', strtotime('Jun 01, 2023')),
			'end_time' => wp_date('M d, Y h:i:s a', strtotime('July 01, 2023')),
			'utm_campaign' => 'lite-promo',
			'utm_source' => 'liteplugin',
			'utm_medium' => 'goodbye-ga3',
		],
		[
			'title' => __('Summer Sale', 'google-analytics-for-wordpress'),
			'start_time' => wp_date('M d, Y h:i:s a', strtotime('Jul 29, 2023')),
			'end_time' => wp_date('M d, Y h:i:s a', strtotime('Aug 05, 2023')),
			'utm_campaign' => 'lite-promo',
			'utm_source' => 'liteplugin',
			'utm_medium' => 'dogdays',
		],
		[
			'title' => __('Fortune Cookie Day', 'google-analytics-for-wordpress'),
			'start_time' => wp_date('M d, Y h:i:s a', strtotime('Sep 12, 2023')),
			'end_time' => wp_date('M d, Y h:i:s a', strtotime('Sep 13, 2023')),
			'utm_campaign' => 'lite-promo',
			'utm_source' => 'liteplugin',
			'utm_medium' => 'fortune-cookie',
		],
		[
			'title' => __('Halloween Sale', 'google-analytics-for-wordpress'),
			'start_time' => wp_date('M d, Y h:i:s a', strtotime('Oct 26, 2023')),
			'end_time' => wp_date('M d, Y h:i:s a', strtotime('Nov 04, 2023')),
			'utm_campaign' => 'lite-promo',
			'utm_source' => 'liteplugin',
			'utm_medium' => 'halloween',
		],
	];

	foreach($conditions as $key => $condition){
		if(strtotime($now) >= strtotime($condition['start_time']) && strtotime($now) <= strtotime($condition['end_time'])){
			add_submenu_page($hook, $condition['title'], '<span class="monsterinsights-automated-submenu"> ' . $condition['title'] . '</span>', 'monsterinsights_save_settings', monsterinsights_get_upgrade_link($condition['utm_medium'], $condition['utm_campaign'], "https://www.monsterinsights.com/lite-promo/"));
			break;
		}
	}
}

/**
 * Add this separately so all the Woo menu items are loaded and the position parameter works correctly.
 */
function monsterinsights_woocommerce_menu_item()
{
	if (class_exists('WooCommerce')) {
		// Add "Insights" sub menu item for WooCommerce Analytics menu
		if (class_exists('MonsterInsights_eCommerce')) {
			add_submenu_page('wc-admin&path=/analytics/overview', __('Insights', 'google-analytics-for-wordpress'), __('Insights', 'google-analytics-for-wordpress'), 'monsterinsights_view_dashboard', admin_url('admin.php?page=monsterinsights_reports#/ecommerce'), '', 2);
		} else {
			$submenu_base = add_query_arg('page', 'monsterinsights_settings', admin_url('admin.php'));
			add_submenu_page('wc-admin&path=/analytics/overview', __('Insights', 'google-analytics-for-wordpress'), __('Insights', 'google-analytics-for-wordpress'), 'manage_options', $submenu_base . '#/woocommerce-insights', '', 1);
		}		
	}
}
add_action('admin_menu', 'monsterinsights_woocommerce_menu_item', 11);

function monsterinsights_get_menu_hook()
{
	$dashboards_disabled = monsterinsights_get_option('dashboards_disabled', false);
	if ($dashboards_disabled || (current_user_can('monsterinsights_save_settings') && !current_user_can('monsterinsights_view_dashboard'))) {
		return 'monsterinsights_settings';
	} else {
		return 'monsterinsights_reports';
	}
}

function monsterinsights_network_admin_menu()
{
	// Get the base class object.
	$base = MonsterInsights();

	// First, let's see if this is an MS network enabled plugin. If it is, we should load the license
	// menu page and the updater on the network panel
	if (!function_exists('is_plugin_active_for_network')) {
		require_once(ABSPATH . '/wp-admin/includes/plugin.php');
	}

	$plugin = plugin_basename(MONSTERINSIGHTS_PLUGIN_FILE);
	if (!is_plugin_active_for_network($plugin)) {
		return;
	}

	$menu_notification_indicator = MonsterInsights()->notifications->get_menu_count();

	if ( MonsterInsights()->license->license_expired() ) {
		$menu_notification_indicator = MonsterInsights()->notifications->get_license_expired_indicator();
	}

	$menu_icon_inline = monsterinsights_get_inline_menu_icon();
	$hook             = 'monsterinsights_network';
	$submenu_base     = add_query_arg('page', 'monsterinsights_network', network_admin_url('admin.php'));
	add_menu_page(__('Network Settings:', 'google-analytics-for-wordpress'), __('Insights', 'google-analytics-for-wordpress') . $menu_notification_indicator, 'monsterinsights_save_settings', 'monsterinsights_network', 'monsterinsights_network_page', $menu_icon_inline, '100.00013467543');

	add_submenu_page($hook, __('Network Settings:', 'google-analytics-for-wordpress'), __('Network Settings', 'google-analytics-for-wordpress'), 'monsterinsights_save_settings', 'monsterinsights_network', 'monsterinsights_network_page');

	add_submenu_page($hook, __('General Reports:', 'google-analytics-for-wordpress'), __('Reports', 'google-analytics-for-wordpress'), 'monsterinsights_view_dashboard', 'monsterinsights_reports', 'monsterinsights_reports_page');

	if (function_exists('aioseo')) {
		$seo_url = monsterinsights_aioseo_dashboard_url();
	} else {
		$seo_url = $submenu_base . '#/seo';
	}
	// then seo
	add_submenu_page($hook, __('SEO:', 'google-analytics-for-wordpress'), __('SEO', 'google-analytics-for-wordpress'), 'manage_options', $seo_url, 'monsterinsights_seo_page');

	// then addons
	add_submenu_page($hook, __('Addons:', 'google-analytics-for-wordpress'), '<span style="color:' . monsterinsights_menu_highlight_color() . '"> ' . __('Addons', 'google-analytics-for-wordpress') . '</span>', 'monsterinsights_save_settings', $submenu_base . '#/addons');

	$submenu_base = add_query_arg('page', 'monsterinsights_network', network_admin_url('admin.php'));

	// Add About us page.
	add_submenu_page($hook, __('About Us:', 'google-analytics-for-wordpress'), __('About Us', 'google-analytics-for-wordpress'), 'manage_options', $submenu_base . '#/about');
}

add_action('network_admin_menu', 'monsterinsights_network_admin_menu', 5);

/**
 * Adds one or more classes to the body tag in the dashboard.
 *
 * @param String $classes Current body classes.
 *
 * @return String          Altered body classes.
 */
function monsterinsights_add_admin_body_class($classes)
{
	$screen = function_exists('get_current_screen') ? get_current_screen() : false;
	if (empty($screen) || empty($screen->id) || strpos($screen->id, 'monsterinsights') === false) {
		return $classes;
	}

	return "$classes monsterinsights_page ";
}

add_filter('admin_body_class', 'monsterinsights_add_admin_body_class', 10, 1);

/**
 * Adds one or more classes to the body tag in the dashboard.
 *
 * @param String $classes Current body classes.
 *
 * @return String          Altered body classes.
 */
function monsterinsights_add_admin_body_class_tools_page($classes)
{
	$screen = function_exists('get_current_screen') ? get_current_screen() : false;

	if (empty($screen) || empty($screen->id) || strpos($screen->id, 'monsterinsights_tools') === false || 'insights_page_monsterinsights_tools' === $screen->id) {
		return $classes;
	}

	return "$classes insights_page_monsterinsights_tools ";
}

add_filter('admin_body_class', 'monsterinsights_add_admin_body_class_tools_page', 10, 1);

/**
 * Adds one or more classes to the body tag in the dashboard.
 *
 * @param String $classes Current body classes.
 *
 * @return String          Altered body classes.
 */
function monsterinsights_add_admin_body_class_addons_page($classes)
{
	$screen = function_exists('get_current_screen') ? get_current_screen() : false;
	if (empty($screen) || empty($screen->id) || strpos($screen->id, 'monsterinsights_addons') === false || 'insights_page_monsterinsights_addons' === $screen->id) {
		return $classes;
	}

	return "$classes insights_page_monsterinsights_addons ";
}

add_filter('admin_body_class', 'monsterinsights_add_admin_body_class_addons_page', 10, 1);

/**
 * Add a link to the settings page to the plugins list
 *
 * @param array $links array of links for the plugins, adapted when the current plugin is found.
 *
 * @return array $links
 */
function monsterinsights_add_action_links($links)
{
	$docs = '<a title="' . esc_attr__('MonsterInsights Knowledge Base', 'google-analytics-for-wordpress') . '" href="' . monsterinsights_get_url('all-plugins', 'kb-link', "https://www.monsterinsights.com/docs/") . '">' . esc_html__('Documentation', 'google-analytics-for-wordpress') . '</a>';
	array_unshift($links, $docs);

	// If Lite, support goes to forum. If pro, it goes to our website
	if (monsterinsights_is_pro_version()) {
		$support = '<a title="MonsterInsights Pro Support" href="' . monsterinsights_get_url('all-plugins', 'pro-support-link', "https://www.monsterinsights.com/my-account/support/") . '">' . esc_html__('Support', 'google-analytics-for-wordpress') . '</a>';
		array_unshift($links, $support);
	} else {
		$support = '<a title="MonsterInsights Lite Support" href="' . monsterinsights_get_url('all-plugins', 'lite-support-link', "https://www.monsterinsights.com/lite-support/") . '">' . esc_html__('Support', 'google-analytics-for-wordpress') . '</a>';
		array_unshift($links, $support);
	}

	if (is_network_admin()) {
		$settings_link = '<a href="' . esc_url(network_admin_url('admin.php?page=monsterinsights_network')) . '">' . esc_html__('Network Settings', 'google-analytics-for-wordpress') . '</a>';
	} else {
		$settings_link = '<a href="' . esc_url(admin_url('admin.php?page=monsterinsights_settings')) . '">' . esc_html__('Settings', 'google-analytics-for-wordpress') . '</a>';
	}

	array_unshift($links, $settings_link);

	// If lite, show a link where they can get pro from
	if (!monsterinsights_is_pro_version()) {
		$get_pro = '<a title="' . esc_attr__('Get MonsterInsights Pro', 'google-analytics-for-wordpress') . '" target="_blank" rel="noopener" href="' . monsterinsights_get_upgrade_link('all-plugins', 'upgrade-link', "https://www.monsterinsights.com/lite/") . '" style="font-weight:700; color: #1da867;">' . esc_html__('Get MonsterInsights Pro', 'google-analytics-for-wordpress') . '</a>';
		array_unshift($links, $get_pro);
	}

	return $links;
}

add_filter('plugin_action_links_' . plugin_basename(MONSTERINSIGHTS_PLUGIN_FILE), 'monsterinsights_add_action_links');
add_filter('network_admin_plugin_action_links_' . plugin_basename(MONSTERINSIGHTS_PLUGIN_FILE), 'monsterinsights_add_action_links');

/**
 * Loads a partial view for the Administration screen
 *
 * @access public
 *
 * @param string $template PHP file at includes/admin/partials, excluding file extension
 * @param array $data Any data to pass to the view
 *
 * @return  void
 * @since 6.0.0
 *
 */
function monsterinsights_load_admin_partial($template, $data = array())
{

	if (monsterinsights_is_pro_version()) {
		$dir = trailingslashit(plugin_dir_path(MonsterInsights()->file) . 'pro/includes/admin/partials');

		if (file_exists($dir . $template . '.php')) {
			require_once($dir . $template . '.php');

			return true;
		}
	} else {
		$dir = trailingslashit(plugin_dir_path(MonsterInsights()->file) . 'lite/includes/admin/partials');

		if (file_exists($dir . $template . '.php')) {
			require_once($dir . $template . '.php');

			return true;
		}
	}

	$dir = trailingslashit(plugin_dir_path(MonsterInsights()->file) . 'includes/admin/partials');

	if (file_exists($dir . $template . '.php')) {
		require_once($dir . $template . '.php');

		return true;
	}

	return false;
}

/**
 * When user is on a MonsterInsights related admin page, display footer text
 * that graciously asks them to rate us.
 *
 * @param string $text
 *
 * @return string
 * @since 6.0.0
 */
function monsterinsights_admin_footer($text)
{
	global $current_screen;
	if (
		! empty( $current_screen->id )
		&& strpos( $current_screen->id, 'monsterinsights' ) !== false
		&& ! monsterinsights_is_pro_version()
	) {
		$url = 'https://wordpress.org/support/view/plugin-reviews/google-analytics-for-wordpress?filter=5';
		// Translators: Placeholders add a link to the wordpress.org repository.
		$text = sprintf(esc_html__('Please rate %1$sMonsterInsights%2$s on %3$s %4$sWordPress.org%5$s to help us spread the word. Thank you from the MonsterInsights team!', 'google-analytics-for-wordpress'), '<strong>', '</strong>', '<a class="monsterinsights-no-text-decoration" href="' . $url . '" target="_blank" rel="noopener noreferrer"><i class="monstericon-star"></i><i class="monstericon-star"></i><i class="monstericon-star"></i><i class="monstericon-star"></i><i class="monstericon-star"></i></a>', '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">', '</a>');
	}

	return $text;
}

add_filter('admin_footer_text', 'monsterinsights_admin_footer', 1, 2);

function monsterinsights_admin_setup_notices()
{
	// Make sure they have the permissions to do something
	if (!current_user_can('monsterinsights_save_settings')) {
		return;
	}

	// Priority:
    // 0. UA sunset
	// 1. Google Analytics not authenticated
	// 2. License key not entered for pro
	// 3. License key not valid/okay for pro
	// 4. WordPress + PHP min versions
	// 5. (old) Optin setting not configured
	// 6. Manual UA code
	// 7. Automatic updates not configured
	// 8. Woo upsell
	// 9. EDD upsell

    //  0. UA sunset supported alert
    $profile = is_network_admin() ? MonsterInsights()->auth->get_network_analytics_profile() : MonsterInsights()->auth->get_analytics_profile();

    if ( !empty($profile['ua']) && empty($profile['v4']) && !monsterinsights_is_own_admin_page() ) {
        $title = __('Urgent: Your Website is Not Tracking Any Google Analytics Data!', 'google-analytics-for-wordpress');
        $message = __('Google Analytics 3 (UA) and support was sunset on July 1, 2023. Your website is currently NOT tracking any analytics. </br>Create or connect a new Google Analytics 4 property immediately to start tracking.', 'google-analytics-for-wordpress');

        $wizard_url     = admin_url('admin.php?page=monsterinsights-onboarding');

        echo '<div class="notice notice-error is-dismissible monsterinsights-notice" data-notice="monsterinsights_ua_sunset">';
        echo '<p><strong>' . $title . '</strong></p>';
        echo '<p>' . $message . '</p>';
        echo '<p>';
        echo '<a href="https://www.monsterinsights.com/docs/connect-google-analytics/"
                   target="_blank" rel="noopener noreferrer">' .
            __( 'Learn How to Create a GA4 Property', 'google-analytics-for-wordpress' ) .
            '</a><br>';
        echo '<a href="' . $wizard_url . '">' .
            __( 'Connect a Property', 'google-analytics-for-wordpress' ) .
            '</a><br>';
        echo '</p>';
        echo '</div>';

        return;
    }

	$is_plugins_page = 'plugins' === get_current_screen()->id;

	// 1. Google Analytics not authenticated
	if ( ! is_network_admin() && ! monsterinsights_get_v4_id() && ! defined( 'MONSTERINSIGHTS_DISABLE_TRACKING' ) && ! monsterinsights_is_own_admin_page() ) {

		$submenu_base = is_network_admin() ? add_query_arg( 'page', 'monsterinsights_network', network_admin_url( 'admin.php' ) ) : add_query_arg( 'page', 'monsterinsights_settings', admin_url( 'admin.php' ) );
		$title        = esc_html__( 'Please Setup Website Analytics to See Audience Insights', 'google-analytics-for-wordpress' );
		$primary      = esc_html__( 'Please Connect Your Website to MonsterInsights', 'google-analytics-for-wordpress' );
		$urlone       = is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights-onboarding' ) : admin_url( 'admin.php?page=monsterinsights-onboarding' );
		$secondary    = esc_html__( 'Learn More', 'google-analytics-for-wordpress' );
		$urltwo       = $submenu_base . '#/about/getting-started';
		$message      = esc_html__( 'MonsterInsights, the #1 WordPress Analytics Plugin, helps you easily connect your website to Google Analytics, so that you can see how people find and use your website. Over 3 million website owners use MonsterInsights to see the stats that matter and grow their business.', 'google-analytics-for-wordpress' );
		echo '<div class="notice notice-info"><p style="font-weight:700">' . $title . '</p><p>' . $message . '</p><p><a href="' . $urlone . '" class="button-primary">' . $primary . '</a>&nbsp;&nbsp;&nbsp;<a href="' . $urltwo . '" class="button-secondary">' . $secondary . '</a></p></div>';

		return;
	}

	// 2. License key not entered for pro
	$key = monsterinsights_is_pro_version() ? MonsterInsights()->license->get_license_key() : '';
	if (monsterinsights_is_pro_version() && empty($key)) {
		$page = is_network_admin() ? network_admin_url('admin.php?page=monsterinsights_network') : admin_url('admin.php?page=monsterinsights_settings');
		// Translators: Adds a link to retrieve the license.
		$message = sprintf(esc_html__('Warning: No valid license key has been entered for MonsterInsights. You are currently not getting updates, and are not able to view reports. %1$sPlease click here to enter your license key and begin receiving updates and reports.%2$s', 'google-analytics-for-wordpress'), '<a href="' . esc_url($page) . '">', '</a>');
		echo '<div class="error"><p>' . $message . '</p></div>'; // phpcs:ignore

		return;
	}

	// 3. License key not valid/okay for pro
	if (monsterinsights_is_pro_version()) {
		$message = '';
		if (MonsterInsights()->license->get_site_license_key()) {
			if (MonsterInsights()->license->site_license_expired()) {
				// Translators: Adds a link to the license renewal.
				$message = sprintf(esc_html__('Your license key for MonsterInsights has expired. %1$sPlease click here to renew your license key.%2$s', 'google-analytics-for-wordpress'), '<a href="' . monsterinsights_get_url('admin-notices', 'expired-license', "https://www.monsterinsights.com/login/") . '" target="_blank" rel="noopener noreferrer" referrer="no-referrer">', '</a>');
			} else if (MonsterInsights()->license->site_license_disabled()) {
				$message = esc_html__('Your license key for MonsterInsights has been disabled. Please use a different key.', 'google-analytics-for-wordpress');
			} else if (MonsterInsights()->license->site_license_invalid()) {
				$message = esc_html__('Your license key for MonsterInsights is invalid. The key no longer exists or the user associated with the key has been deleted. Please use a different key.', 'google-analytics-for-wordpress');
			}
		} else if (MonsterInsights()->license->get_network_license_key()) {
			if (MonsterInsights()->license->network_license_expired()) {
				// Translators: Adds a link to renew license.
				$message = sprintf(esc_html__('Your network license key for MonsterInsights has expired. %1$sPlease click here to renew your license key.%2$s', 'google-analytics-for-wordpress'), '<a href="' . monsterinsights_get_url('admin-notices', 'expired-license', "https://www.monsterinsights.com/login/") . '" target="_blank" rel="noopener noreferrer" referrer="no-referrer">', '</a>');
			} else if (MonsterInsights()->license->network_license_disabled()) {
				$message = esc_html__('Your network license key for MonsterInsights has been disabled. Please use a different key.', 'google-analytics-for-wordpress');
			} else if (MonsterInsights()->license->network_license_invalid()) {
				$message = esc_html__('Your network license key for MonsterInsights is invalid. The key no longer exists or the user associated with the key has been deleted. Please use a different key.', 'google-analytics-for-wordpress');
			}
		}
		if (!empty($message)) {
			echo '<div class="error"><p>' . $message . '</p></div>'; // phpcs:ignore

			return;
		}
	}

	// 4. Notices for PHP/WP version deprecations
	if (current_user_can('update_core')) {
		global $wp_version;

		$compatible_php_version = apply_filters('monsterinsights_compatible_php_version', false);
		$compatible_wp_version  = apply_filters('monsterinsights_compatible_wp_version', false);

		$url = monsterinsights_get_url('global-notice', 'settings-page', 'https://www.monsterinsights.com/docs/update-php/');

		$message = false;
		if (version_compare(phpversion(), $compatible_php_version['required'], '<')) {
			// Translators: Placeholders add the PHP version, a link to the MonsterInsights blog and a line break.
			$message = sprintf(esc_html__('Your site is running an outdated, insecure version of PHP (%1$s), which could be putting your site at risk for being hacked.%4$sWordPress stopped supporting your PHP version in April, 2019.%4$sUpdating PHP only takes a few minutes and will make your website significantly faster and more secure.%4$s%2$sLearn more about updating PHP%3$s', 'google-analytics-for-wordpress'), phpversion(), '<a href="' . $url . '" target="_blank">', '</a>', '<br>');
		} else if (version_compare(phpversion(), $compatible_php_version['warning'], '<')) {
			// Translators: Placeholders add the PHP version, a link to the MonsterInsights blog and a line break.
			$message = sprintf(esc_html__('Your site is running an outdated, insecure version of PHP (%1$s), which could be putting your site at risk for being hacked.%4$sWordPress stopped supporting your PHP version in November, 2019.%4$sUpdating PHP only takes a few minutes and will make your website significantly faster and more secure.%4$s%2$sLearn more about updating PHP%3$s', 'google-analytics-for-wordpress'), phpversion(), '<a href="' . $url . '" target="_blank">', '</a>', '<br>');
		} else if (version_compare(phpversion(), $compatible_php_version['recommended'], '<')) {
			// Translators: Placeholders add the PHP version, a link to the MonsterInsights blog and a line break.
			$message = sprintf(esc_html__('Your site is running an outdated, insecure version of PHP (%1$s), which could be putting your site at risk for being hacked.%4$sWordPress is working towards discontinuing support for your PHP version.%4$sUpdating PHP only takes a few minutes and will make your website significantly faster and more secure.%4$s%2$sLearn more about updating PHP%3$s', 'google-analytics-for-wordpress'), phpversion(), '<a href="' . $url . '" target="_blank">', '</a>', '<br>');
		}

		if ($message) {
			echo '<div class="error"><p>' . wp_kses($message, [
				'br' => array(),
				'b' => array(),
				'strong' => array(),
				'i' => array(),
				'a' => array(
					'href' => array(),
					'target' => array(),
					'title' => array(),
				),
			]) . '</p></div>';
			return;
		}

		// WordPress 4.9
		/* else if ( version_compare( $wp_version, '5.0', '<' ) ) {
			$url = monsterinsights_get_url( 'global-notice', 'settings-page', 'https://www.monsterinsights.com/docs/update-wordpress/' );
			// Translators: Placeholders add the current WordPress version and links to the MonsterInsights blog
			$message = sprintf( esc_html__( 'Your site is running an outdated version of WordPress (%1$s).%4$sMonsterInsights will stop supporting WordPress versions lower than 5.0 in 2021.%4$sUpdating WordPress takes just a few minutes and will also solve many bugs that exist in your WordPress install.%4$s%2$sLearn more about updating WordPress%3$s', 'google-analytics-for-wordpress' ), $wp_version, '<a href="' . $url . '" target="_blank">', '</a>', '<br>' );
			echo '<div class="error"><p>'. $message.'</p></div>';
			return;
		} */
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

	$notices = get_option('monsterinsights_notices');
	if (!is_array($notices)) {
		$notices = array();
	}

	// 6. Authenticate, not manual
	$authed  = MonsterInsights()->auth->is_authed() || MonsterInsights()->auth->is_network_authed();
	$url     = is_network_admin() ? network_admin_url('admin.php?page=monsterinsights_network') : admin_url('admin.php?page=monsterinsights_settings');
	$tracking_code = monsterinsights_get_v4_id_to_output();
	// Translators: Placeholders add links to the settings panel.
	$manual_text = sprintf(esc_html__('Important: You are currently using manual GA4 Measurement ID output. We highly recommend %1$sauthenticating with MonsterInsights%2$s so that you can access our new reporting area and take advantage of new MonsterInsights features.', 'google-analytics-for-wordpress'), '<a href="' . $url . '">', '</a>');
	$migrated    = monsterinsights_get_option('gadwp_migrated', 0);
	if ($migrated > 0) {
		$url = admin_url('admin.php?page=monsterinsights-getting-started&monsterinsights-migration=1');
		// Translators: Placeholders add links to the settings panel.
		$text        = esc_html__('Click %1$shere%2$s to reauthenticate to be able to access reports. For more information why this is required, see our %3$sblog post%4$s.', 'google-analytics-for-wordpress');
		$manual_text = sprintf($text, '<a href="' . esc_url($url) . '">', '</a>', '<a href="' . monsterinsights_get_url('notice', 'manual-ua', 'https://www.exactmetrics.com/why-did-we-implement-the-new-google-analytics-authentication-flow-challenges-explained/') . '" target="_blank">', '</a>');
	}

	if (empty($authed) && !isset($notices['monsterinsights_auth_not_manual']) && !empty($tracking_code)) {
		echo '<div class="notice notice-info is-dismissible monsterinsights-notice" data-notice="monsterinsights_auth_not_manual">';
		echo '<p>';
		echo $manual_text; // phpcs:ignore
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
	if (!monsterinsights_is_pro_version() && class_exists('WooCommerce') && $is_plugins_page) {
		if (!isset($notices['monsterinsights_woocommerce_tracking_available'])) {
			$woo_notice_template = '<div class="notice notice-success is-dismissible monsterinsights-notice monsterinsights-wooedd-upsell-row" data-notice="monsterinsights_woocommerce_tracking_available">
				%1$s
				<div class="monsterinsights-wooedd-upsell-left">
					<p><strong>%2$s</strong></p>
					<p>%3$s</p>
					<p>%4$s</p>
					<p>%5$s</p>
					<p>%6$s</p>
					%7$s
					%8$s
				</div>
			</div>';

			$woo_notice_button = sprintf(
				// Translators: Placeholders add a link to the MonsterInsights website.
				esc_html__('%1$sGet MonsterInsights Pro%2$s', 'google-analytics-for-wordpress'),
				'<a class="button button-primary button-hero" target="_blank" href="' . esc_url(monsterinsights_get_upgrade_link('admin-notices', 'woocommerce-upgrade')) . '">',
				' &raquo;</a>'
			);

			$woo_notice_offer = sprintf(
				'<div class="monsterinsights-wooedd-upsell-offer">%1$s</div>',
				__('Save <span>50%</span> Off MonsterInsights Pro', 'google-analytics-for-wordpress')
			);

			$woo_notice_bg = esc_url(trailingslashit(MONSTERINSIGHTS_PLUGIN_URL)) . 'assets/images/upsell/monsterinsights-woo-edd-upsell.svg';
			$woo_notice_offer_icon = esc_url(trailingslashit(MONSTERINSIGHTS_PLUGIN_URL)) . 'assets/images/upsell/woo-offer-icon.svg';
			$woo_notice_style = "<style>.monsterinsights-wooedd-upsell-left .button-hero,.monsterinsights-wooedd-upsell-offer{width:270px;margin-bottom:20px;text-align:center}.monsterinsights-wooedd-upsell-row{display:flex;background-image:url($woo_notice_bg);background-repeat:no-repeat;background-position:96% bottom}.monsterinsights-wooedd-upsell-left{margin-left:20px}.monsterinsights-wooedd-upsell-offer{background:#fafeb0;padding:6px 0;position:relative;font-weight:700;font-size:15px;line-height:28px}.monsterinsights-wooedd-upsell-offer span{color:#338eef}.monsterinsights-wooedd-upsell-offer:before{content:url('$woo_notice_offer_icon');position:absolute;left:-23px;bottom:-30px}@media (max-width:1300px){.monsterinsights-wooedd-upsell-row{background-size:60%}}@media (max-width:900px){.monsterinsights-wooedd-upsell-row{background-image:none}.monsterinsights-wooedd-upsell-left,.monsterinsights-wooedd-upsell-left .button-hero,.monsterinsights-wooedd-upsell-offer{width:100%}}</style>";

			echo sprintf(
				$woo_notice_template,
				$woo_notice_style,
				__('Add eCommerce Analytics to your WooCommerce Store', 'google-analytics-for-wordpress'),
				__('Unlock all of our advanced eCommerce features specifically designed to help your store make more money..', 'google-analytics-for-wordpress'),
				__('MonsterInsights Pro users instantly gain access to valuable insights such as average order value, conversion rates, as well as marketing performance with UTM tracking.', 'google-analytics-for-wordpress'),
				__('And by upgrading, Pro users also get enhanced tracking for Forms, User Journeys, PPC Pixels, Custom UserID tracking, SEO Reports, and much more.', 'google-analytics-for-wordpress'),
				__('Start making better data-driven decisions today!', 'google-analytics-for-wordpress'),
				$woo_notice_offer,
				$woo_notice_button
			);
			return;
		}
	}

	// 9. EDDUpsell
	if (!monsterinsights_is_pro_version() && class_exists('Easy_Digital_Downloads') && $is_plugins_page) {
		if (!isset($notices['monsterinsights_edd_tracking_available'])) {
			echo '<div class="notice notice-success is-dismissible monsterinsights-notice monsterinsights-wooedd-upsell-row" data-notice="monsterinsights_edd_tracking_available">';
			echo '<div class="monsterinsights-wooedd-upsell-left">';
			echo '<p><strong>';
			echo esc_html('Enhanced Ecommerce Analytics for Your Easy Digital Downloads Store', 'google-analytics-for-wordpress');
			echo '</strong></p>';
			echo '<img class="monsterinsights-wooedd-upsell-image monsterinsights-wooedd-upsell-image-small" src="' . esc_url(trailingslashit(MONSTERINSIGHTS_PLUGIN_URL)) . 'assets/images/upsell/woo-edd-upsell.png">';
			echo '<p>';
			echo esc_html('MonsterInsights Pro gives you detailed stats and insights about your customers.', 'google-analytics-for-wordpress');
			echo '</p>';
			echo '<p>';
			echo esc_html('This helps you make data-driven decisions about your content, and marketing strategy so you can increase your website traffic, leads, and sales.', 'google-analytics-for-wordpress');
			echo '</p>';
			echo '<p>';
			echo esc_html('Pro customers also get Form Tracking, Custom Dimensions Tracking, UserID Tracking and much more.', 'google-analytics-for-wordpress');
			echo '</p>';
			echo '<p>';
			echo esc_html('Start making data-driven decisions to grow your business.', 'google-analytics-for-wordpress');
			echo '</p>';
			echo sprintf(esc_html__('%1$sGet MonsterInsights Pro%2$s', 'google-analytics-for-wordpress'), '<a class="button button-primary button-hero" target="_blank" href="' . esc_url(monsterinsights_get_upgrade_link('admin-notices', 'edd-upgrade')) . '">', ' &raquo;</a>');
			echo '</p>';
			echo '</div><div class="monsterinsights-wooedd-upsell-right">';
			echo '<img class="monsterinsights-wooedd-upsell-image monsterinsights-wooedd-upsell-image-large" src="' . esc_url(trailingslashit(MONSTERINSIGHTS_PLUGIN_URL)) . 'assets/images/upsell/woo-edd-upsell.png">';
			echo '</div>';
			echo '</div>';

			return;
		}
	}


	if (isset($notices['monsterinsights_cross_domains_extracted']) && false === $notices['monsterinsights_cross_domains_extracted']) {
		$page = is_network_admin() ? network_admin_url('admin.php?page=monsterinsights_network') : admin_url('admin.php?page=monsterinsights_settings');
		$page = $page . '#/advanced';
		// Translators: Adds a link to the settings panel.
		$message = sprintf(esc_html__('Warning: MonsterInsights found cross-domain settings in the custom code field and converted them to the new settings structure.  %1$sPlease click here to review and remove the code no longer needed.%2$s', 'google-analytics-for-wordpress'), '<a href="' . esc_url($page) . '">', '</a>');
		echo '<div class="notice notice-success is-dismissible monsterinsights-notice" data-notice="monsterinsights_cross_domains_extracted"><p>' . $message . '</p></div>'; // phpcs:ignore

		return;
	}
}

add_action('admin_notices', 'monsterinsights_admin_setup_notices');
add_action('network_admin_notices', 'monsterinsights_admin_setup_notices');


// AM Notices
function monsterinsights_am_notice_optout($super_admin)
{
	if (monsterinsights_get_option('hide_am_notices', false) || monsterinsights_get_option('network_hide_am_notices', false)) {
		return false;
	}

	return $super_admin;
}

add_filter("am_notifications_display", 'monsterinsights_am_notice_optout', 10, 1);

/**
 * Inline critical css for the menu to prevent breaking the layout when our scripts get blocked by browsers.
 */
function monsterinsights_admin_menu_inline_styles()
{
?>
	<style>
		#toplevel_page_monsterinsights_reports .wp-menu-image img,
		#toplevel_page_monsterinsights_settings .wp-menu-image img,
		#toplevel_page_monsterinsights_network .wp-menu-image img {
			width: 18px;
			height: auto;
			padding-top: 7px;
		}

		#toplevel_page_monsterinsights_reports .wp-submenu li a {
			display: flex;
			align-items: center;
		}

		#toplevel_page_monsterinsights_reports .wp-submenu .monsterinsights-sidebar-icon {
			padding-right: 6px;
		}
	</style>
<?php
}

add_action('admin_head', 'monsterinsights_admin_menu_inline_styles', 300);

/**
 * Display notice in admin when measurement protocol is left blank
 */
function monsterinsights_empty_measurement_protocol_token()
{
	if (!class_exists('MonsterInsights_eCommerce') && !class_exists('MonsterInsights_Forms')) {
		return;
	}

	$page = is_network_admin()
		? network_admin_url('admin.php?page=monsterinsights_network')
		: admin_url('admin.php?page=monsterinsights_settings');

	$api_secret = is_network_admin()
		? MonsterInsights()->auth->get_network_measurement_protocol_secret()
		: MonsterInsights()->auth->get_measurement_protocol_secret();

	$current_code = monsterinsights_get_v4_id_to_output();

	if (empty($current_code) || !empty($api_secret)) {
		return;
	}

	$message = sprintf(
		/* translators: Placeholders add a link to an article. */
		esc_html__(
			'Your Measurement Protocol API Secret is currently left blank. To see more advanced analytics please enter a Measurement API Secret. %1$sLearn how to find your API Secret%2$s.',
			'google-analytics-for-wordpress'
		),
		'<a target="_blank" href="' . monsterinsights_get_url('notice', 'empty-measurement-protocol-secret', 'https://www.monsterinsights.com/docs/how-to-create-your-measurement-protocol-api-secret-in-ga4/') . '">',
		'</a>'
	);
	echo '<div class="error"><p>' . $message . '</p></div>'; // phpcs:ignore
}

add_action( 'admin_notices', 'monsterinsights_empty_measurement_protocol_token' );
add_action( 'network_admin_notices', 'monsterinsights_admin_setup_notices' );

/**
 * Check if the plugin is MI Lite.
 *
 * @return bool
 */
function check_is_it_monsterinsights_lite() {
    return 'googleanalytics.php' == basename( MONSTERINSIGHTS_PLUGIN_FILE );
}

/**
 * Add EEA Compliance file.
 */
require_once __DIR__ . '/eea-compliance.php';
