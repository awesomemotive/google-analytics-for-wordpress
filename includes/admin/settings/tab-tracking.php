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
                $upgrade_span = ! $is_pro && $item['level'] !== 'lite' ? '<span class="monsterinsights-upgrade-menu-icon">' . esc_html__( 'Upgrade', 'google-analytics-for-wordpress' ) . '</span>' : '';
                ?>
                <a class="monsterinsights-sub-nav-item monsterinsights-nav-item monsterinsights-active monstericon-<?php echo esc_attr( $id ); ?> <?php echo esc_attr( $class ); ?>" href="#monsterinsights-main-tab-tracking?monsterinsights-sub-tab-<?php echo esc_attr( $id ); ?>" title="<?php echo esc_attr( $item['title'] ); ?>">
                    <?php echo esc_html( $item['title'] ) . $upgrade_span; ?>
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
                ?>
                 <div id="monsterinsights-sub-tab-<?php echo esc_attr( $id ); ?>" class="monsterinsights-sub-nav-tab monsterinsights-nav-tab<?php echo esc_attr( $class ); ?>">
                    <?php
                    if ( has_action( 'monsterinsights_tab_settings_tracking_' . $id ) ) {
                        /** 
                         * Developer Alert:
                         *
                         * This internal use action will be removed soon. DO NOT USE.
                         */
                        do_action( 'monsterinsights_tab_settings_tracking_' . $id  );
                    } else { 
                        ?>
                         <?php echo '<h2 class="monsterinsights-sub-tab-header">' . esc_html( $item['title'] ) . '</h2>'; ?>
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
                        <form id="monsterinsights-tracking-<?php echo esc_attr( $id );?>-tab" method="post">
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
                        </form>
                    <?php } ?>
                    </div>
                <?php
                $i++;
            }
            ?>
        </div>
    </div>
    <?php
}
add_action( 'monsterinsights_tab_settings_tracking', 'monsterinsights_settings_tracking_tab' );