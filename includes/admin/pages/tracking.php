<?php
/**
 * Settings class.
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
 * Callback to output the MonsterInsights settings page.
 *
 * @since 6.0.0
 * @access public
 *
 * @return void
 */
function monsterinsights_tracking_page() {
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

    <!-- Tabs -->
    <h1 id="monsterinsights-settings-page-main-nav" class="monsterinsights-main-nav-container monsterinsights-nav-container" data-container="#monsterinsights-settings-pages">
        <a class="monsterinsights-main-nav-item monsterinsights-nav-item monsterinsights-spacing-item" href="#">&nbsp;</a>

        <a class="monsterinsights-main-nav-item monsterinsights-nav-item" href="<?php echo admin_url('admin.php?page=monsterinsights_settings');?>" title="<?php echo esc_attr( __( 'General', 'google-analytics-for-wordpress' ) ); ?>">
            <?php echo esc_html__( 'General', 'google-analytics-for-wordpress' ); ?>
        </a>

        <a class="monsterinsights-main-nav-item monsterinsights-nav-item monsterinsights-active" href="#monsterinsights-main-tab-tracking" title="<?php echo esc_attr( __( 'Tracking', 'google-analytics-for-wordpress' ) ); ?>">
            <?php echo esc_html__( 'Tracking', 'google-analytics-for-wordpress' ); ?>
        </a>
    </h1>


    <!-- Tab Panels -->
    <div id="monsterinsights-settings-pages" class="monsterinsights-main-nav-tabs monsterinsights-nav-tabs wrap" data-navigation="#monsterinsights-settings-page-main-nav">
        <h1 class="monsterinsights-hideme"></h1><!-- so wp notices are below the nav bar -->
         <div id="monsterinsights-main-tab-tracking" class="monsterinsights-main-nav-tab monsterinsights-nav-tab  monsterinsights-active">
            <?php monsterinsights_settings_tracking_tab(); ?>
        </div>
    </div>
    <?php
}