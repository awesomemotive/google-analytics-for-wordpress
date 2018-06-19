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
function monsterinsights_settings_tracking_tab() {
    // Get settings
    ?>
    <div id="monsterinsights-settings-tracking" class="monsterinsights-sub-nav-area">
        <!-- Tabs -->
        <h1 id="monsterinsights-settings-page-sub-nav" class="monsterinsights-sub-nav-container monsterinsights-nav-container" data-container="#monsterinsights-settings-sub-pages">
            <?php 
            $i      = 0;
            $class  = '';
            $is_pro = monsterinsights_is_pro_version();
            foreach ( (array) monsterinsights_get_settings_tabs() as $id => $item ) {
                if ( isset( $item['comingsoon'] ) && $item['comingsoon'] || empty( $item['title'] ) ) {
                    continue;
                }
                $class = ( 0 === $i ? 'monsterinsights-active' : '' );
                ?>
                <a class="monsterinsights-sub-nav-item monsterinsights-nav-item monstericon-<?php echo esc_attr( $id ); ?> <?php echo esc_attr( $class ); ?>" href="#monsterinsights-main-tab-tracking?monsterinsights-sub-tab-<?php echo esc_attr( $id ); ?>" title="<?php echo esc_attr( $item['title'] ); ?>">
                    <?php echo esc_html( $item['title'] ); ?>
                </a>
                <?php 
                $i++; 
            }
            ?>
        </h1>

        <h1 class="monsterinsights-hideme"></h1><!-- so wp notices are below the nav bar -->

        <?php 
        // Output any notices now
        /** 
         * Developer Alert:
         *
         * Per the README, this is considered an internal hook and should
         * not be used by other developers. This hook's behavior may be modified
         * or the hook may be removed at any time, without warning.
         */
        do_action( 'monsterinsights_settings_tracking_tab_notice' );
        ?>

        <!-- Tab Panels -->
        <div id="monsterinsights-settings-sub-pages" class="monsterinsights-sub-nav-tabs monsterinsights-nav-tabs" data-navigation="#monsterinsights-settings-page-sub-nav">
            <?php 
            $i = 0; 
            foreach ( (array) monsterinsights_get_settings_tabs() as $id => $item ) {
                if ( isset( $item['comingsoon'] ) && $item['comingsoon'] || empty( $item['title'] ) ) {
                    continue;
                }
                $class = ( 0 === $i ? ' monsterinsights-active' : '' ); 
                $i++;
                ?>
                 <div id="monsterinsights-sub-tab-<?php echo esc_attr( $id ); ?>" class="monsterinsights-sub-nav-tab monsterinsights-nav-tab<?php echo esc_attr( $class ); ?>">
                    <?php if ( $item['level'] === 'lite' ||  $is_pro ) { ?>
                    <?php echo '<h2 class="monsterinsights-sub-tab-header">' . esc_html( $item['title'] ) . '</h2>'; ?>
                    <?php } ?>
                     <div class="monsterinsights-subtab-settings-notices">
                        <?php 
                        // Output any notices now
                        /** 
                         * Developer Alert:
                         *
                         * Per the README, this is considered an internal hook and should
                         * not be used by other developers. This hook's behavior may be modified
                         * or the hook may be removed at any time, without warning.
                         */
                        do_action( 'monsterinsights_tracking_' . $id . '_tab_notice' );
                        ?>
                    </div>
                    <!-- Settings Form -->
                    <?php $class = ( $item['level'] !== 'lite' && ! $is_pro ) ? 'monsterinsights-no-settings-shown' : ''; ?>
                    <form id="monsterinsights-tracking-<?php echo esc_attr( $id );?>-tab" class="<?php echo $class; ?>" method="post">
                        <table class="form-table">
                            <tbody>
                                <?php
                                //do_action( 'monsterinsights_settings_tab_top_' . $id  );
                                echo monsterinsights_get_section_settings( $id, 'tracking' );
                                //do_action( 'monsterinsights_settings_tab_bottom_' . $id  );
                                ?>
                            </tbody>
                        </table>
                        <?php echo monsterinsights_render_submit_field( $id, 'tracking' ); ?>
                        <?php if ( $item['level'] === 'lite' && !$is_pro ) { ?>
                            <div class="monsterinsights-upsell-under-box">
                                <h2><?php esc_html_e( "Want even more fine tuned control over your website analytics?", 'google-analytics-for-wordpress' ); ?></h2>
                                <p class="monsterinsights-upsell-lite-text"><?php esc_html_e( "By upgrading to MonsterInsights Pro, you get access to numerous addons and tools that help you better understand what people are doing on your website, so you can keep doing more of what's working. Some of the features include: Ecommerce tracking, Author tracking, Post Type tracking, Ads tracking, Google AMP tracking, Performance optimization, and so much more!", 'google-analytics-for-wordpress' ); ?></p>
                                <p class="monsterinsights-upsell-button-par"><a href="<?php echo monsterinsights_get_upgrade_link( 'settings-page', 'settings-page-bottom-cta' );?>" class="button button-primary"><?php esc_html_e( "Click here to Upgrade", 'google-analytics-for-wordpress' ); ?></a></p></div>
                        <?php } ?>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}
add_action( 'monsterinsights_tab_settings_tracking', 'monsterinsights_settings_tracking_tab' );