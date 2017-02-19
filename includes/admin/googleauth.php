<?php
/**
 * Google oAuth Flow.
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

function monsterinsights_google_auth_start_view( $reauth = false ) {
    $class = ( $reauth ) ? 'monsterinsights_google_auth_reauth_start_view' : 'monsterinsights_google_auth_start_view' ;
    ob_start(); 
    ?>
    <div class="<?php echo $class; ?>">
        <?php if ( $reauth ) { ?>
        <div id="monsterinsights_google_auth_box_header">
                <div class="monsterinsights_google_auth_box_header_reauth">
                    <?php esc_html_e( 'This process will replace your current Google Analytics connection.', 'google-analytics-for-wordpress' ); ?> 
                    <a href="" class="monsterinsights_google_auth_box_cancel" title="<?php esc_attr_e( 'Cancel', 'google-analytics-for-wordpress' ); ?>" > 
                        <?php esc_html_e( 'Cancel', 'google-analytics-for-wordpress' ); ?>
                    </a> 
                </div>
        </div>
         <?php } ?>
         <div id="monsterinsights_google_auth_box_contents" <?php if ( $reauth ) { echo 'class="monsterinsights_google_auth_reauth"'; } ?> >
            <?php if ( $reauth ) { ?>
                <div class="monsterinsights_google_auth_view_title monsterinsights_google_auth_start_view_title"><?php esc_html_e( 'RE-AUTHENTICATE', 'google-analytics-for-wordpress' ); ?> </div>
                <div class="monsterinsights_google_auth_view_description monsterinsights_google_auth_start_view_description"><?php esc_html_e( 'Re-authenticate your Google Analytics account with MonsterInsights', 'google-analytics-for-wordpress' ); ?> </div>
            <?php } else {  ?>
                <div class="monsterinsights_google_auth_view_title monsterinsights_google_auth_view_title monsterinsights_google_auth_start_view_title"><?php esc_html_e( 'AUTHENTICATION', 'google-analytics-for-wordpress' ); ?> </div>
                <div class="monsterinsights_google_auth_view_description monsterinsights_google_auth_start_view_description"><?php esc_html_e( 'Authenticate your Google Analytics account with MonsterInsights', 'google-analytics-for-wordpress' ); ?> </div>
            <?php } ?>
            <input type="hidden" id="monsterinsightsreauth" name="monsterinsightsreauth" value="<?php echo ( $reauth ) ? 'true' : 'false';?>">
            <input type="hidden" id="monsterinsightsview" name="monsterinsightsview" value="<?php echo 'start';?>">
        </div>
        <div id="monsterinsights_google_auth_box_footer">
            <div id="monsterinsights_google_auth_box_footer_left">
                <a href="#" class="monsterinsights_google_auth_box_cancel" title="<?php esc_attr_e( 'Cancel', 'google-analytics-for-wordpress' ); ?>" > <?php esc_html_e( 'CANCEL', 'google-analytics-for-wordpress' ); ?></a> 
            </div>
            <div id="monsterinsights_google_auth_box_footer_center">
                &nbsp;
            </div>
            <div id="monsterinsights_google_auth_box_footer_right">
                <a href="#" id="monsterinsights_google_auth_box_next" class="monsterinsights_google_auth_box_next" title="<?php esc_attr_e( 'Next', 'google-analytics-for-wordpress' ); ?>" >
                    <div id="monsterinsights_google_auth_box_footer_right_next">
                         <?php esc_html_e( 'NEXT', 'google-analytics-for-wordpress' ); ?> 
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
}
function monsterinsights_google_auth_enterkey_view( $reauth = false, $auth_url = '', $error = '' ) {
    $class    = ( $reauth ) ? 'monsterinsights_google_auth_reauth_enterkey_view' : 'monsterinsights_google_auth_enterkey_view' ;
    $auth_url = esc_js( esc_url( $auth_url ) );
    ob_start(); 
    ?>
    <div class="<?php echo $class; ?>">
        <?php if ( $reauth ) { ?>
        <div id="monsterinsights_google_auth_box_header">
                <div class="monsterinsights_google_auth_box_header_reauth">
                    <?php esc_html_e( 'This process will replace your current Google Analytics connection.', 'google-analytics-for-wordpress' ); ?> 
                    <a href="" class="monsterinsights_google_auth_box_cancel" title="<?php esc_attr_e( 'Cancel', 'google-analytics-for-wordpress' ); ?>" > 
                        <?php esc_html_e( 'Cancel', 'google-analytics-for-wordpress' ); ?>
                    </a> 
                </div>
        </div>
         <?php } ?>
         <div id="monsterinsights_google_auth_box_contents" <?php if ( $reauth ) { echo 'class="monsterinsights_google_auth_reauth"'; } ?> >
            <div class="monsterinsights_google_auth_view_title monsterinsights_google_auth_enterkey_view_title"><?php esc_html_e( 'ENTER GOOGLE CODE', 'google-analytics-for-wordpress' ); ?> </div>
            <a id="monsterinsights-google-oauth-window" class="button" onclick="monsterinsights_popupwindow('<?php echo esc_js( esc_attr( esc_url( $auth_url ) ) );  ?>',500,500);"><?php esc_html_e( 'Click To Get Google Code', 'google-analytics-for-wordpress' ); ?> </a>
            <label for="monsterinsights_step_data" class="monsterinsights_google_auth_step_data_label_enterkey_view"><?php esc_html_e( 'Copy the Google code into the box below and click next', 'google-analytics-for-wordpress' ); ?> </label>
            <input type="text"   id="monsterinsights_step_data" class="monsterinsights_google_auth_step_data_enterkey_view" name="monsterinsights_step_data" placeholder="<?php esc_html_e( 'Paste Google Code here', 'google-analytics-for-wordpress'); ?>">
            <input type="hidden" id="monsterinsightsreauth" name="monsterinsightsreauth" value="<?php echo ($reauth) ? 'true' : 'false';?>">
            <input type="hidden" id="monsterinsightsview" name="monsterinsightsview" value="<?php echo 'enterkey';?>">
            <?php if ( ! empty( $error ) ) { ?>
                <div class="monsterinsights_google_auth_enterkey_error notice notice-warning" style="margin:0px;padding:0px;"><p><?php esc_html_e( $error ); ?><p></div>
            <?php } ?>
        </div>
        <div id="monsterinsights_google_auth_box_footer">
            <div id="monsterinsights_google_auth_box_footer_left">
                <a href="#" class="monsterinsights_google_auth_box_cancel" title="<?php esc_attr_e( 'Cancel', 'google-analytics-for-wordpress' ); ?>" > <?php esc_html_e( 'CANCEL', 'google-analytics-for-wordpress' ); ?></a> 
            </div>
            <div id="monsterinsights_google_auth_box_footer_center">
                &nbsp;
            </div>
            <div id="monsterinsights_google_auth_box_footer_right">
                <a href="#" id="monsterinsights_google_auth_box_next" class="monsterinsights_google_auth_box_next" title="<?php esc_attr_e( 'Next', 'google-analytics-for-wordpress' ); ?>" >
                    <div id="monsterinsights_google_auth_box_footer_right_next">
                         <?php esc_html_e( 'NEXT', 'google-analytics-for-wordpress' ); ?> 
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;    
}
function monsterinsights_google_auth_selectprofile_view( $reauth = false, $selectprofile = '', $error = '' ) {
    $class = ( $reauth ) ? 'monsterinsights_google_auth_reauth_selectprofile_view' : 'monsterinsights_google_auth_selectprofile_view' ;
    ob_start(); 
    ?>
    <div class="<?php echo $class; ?>">
        <?php if ( $reauth ) { ?>
        <div id="monsterinsights_google_auth_box_header">
                <div class="monsterinsights_google_auth_box_header_reauth">
                    <?php esc_html_e( 'This process will replace your current Google Analytics connection.', 'google-analytics-for-wordpress' ); ?> 
                    <a href="" class="monsterinsights_google_auth_box_cancel" title="<?php esc_attr_e( 'Cancel', 'google-analytics-for-wordpress' ); ?>" > 
                        <?php esc_html_e( 'Cancel', 'google-analytics-for-wordpress' ); ?>
                    </a> 
                </div>
        </div>
         <?php } ?>
         <div id="monsterinsights_google_auth_box_contents" <?php if ( $reauth ) { echo 'class="monsterinsights_google_auth_reauth"'; } ?> >

            <div class="monsterinsights_google_auth_view_title monsterinsights_google_auth_start_view_title"><?php esc_html_e( 'SELECT PROFILE', 'google-analytics-for-wordpress' ); ?> </div>
            <div class="monsterinsights_google_auth_view_description monsterinsights_google_auth_start_view_description"><?php esc_html_e( 'Select the Google Analytics Profile to Use', 'google-analytics-for-wordpress' ); ?> </div>
            <?php echo $selectprofile; ?>
            <input type="hidden" id="monsterinsightsreauth" name="monsterinsightsreauth" value="<?php echo ( $reauth) ? 'true' : 'false';?>">
            <input type="hidden" id="monsterinsightsview" name="monsterinsightsview" value="<?php echo 'selectprofile';?>">
            <?php if ( ! empty( $error ) ) { ?>
                <div class="monsterinsights_google_auth_selectprofile_error notice notice-warning" style="margin:0px;padding:0px;"><p><?php esc_html_e( $error ); ?><p></div>
            <?php } ?>
        </div>
        <div id="monsterinsights_google_auth_box_footer">
            <div id="monsterinsights_google_auth_box_footer_left">
                <a href="#" class="monsterinsights_google_auth_box_cancel" title="<?php esc_attr_e( 'Cancel', 'google-analytics-for-wordpress' ); ?>" > <?php esc_html_e( 'CANCEL', 'google-analytics-for-wordpress' ); ?></a> 
            </div>
            <div id="monsterinsights_google_auth_box_footer_center">
                &nbsp;
            </div>
            <div id="monsterinsights_google_auth_box_footer_right">
                <a href="#" id="monsterinsights_google_auth_box_next" class="monsterinsights_google_auth_box_next" title="<?php esc_attr_e( 'Next', 'google-analytics-for-wordpress' ); ?>" >
                    <div id="monsterinsights_google_auth_box_footer_right_next">
                         <?php esc_html_e( 'NEXT', 'google-analytics-for-wordpress' ); ?> 
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;  
}
function monsterinsights_google_auth_done_view( $reauth = false ) {
    $class = ( $reauth ) ? 'monsterinsights_google_auth_reauth_done_view' : 'monsterinsights_google_auth_done_view' ;
    ob_start(); 
    ?>
    <div class="<?php echo $class; ?>">
        <?php if ( $reauth ) { ?>
        <div id="monsterinsights_google_auth_box_header">
                <div class="monsterinsights_google_auth_box_header_reauth">
                    <?php esc_html_e( 'Your Google connection has been replaced.', 'google-analytics-for-wordpress' ); ?> 
                </div>
        </div>
         <?php } ?>
         <div id="monsterinsights_google_auth_box_contents" <?php if ( $reauth ) { echo 'class="monsterinsights_google_auth_reauth"'; } ?> >
            <div class="monsterinsights_google_auth_view_title monsterinsights_google_auth_start_view_title"><?php esc_html_e( 'DONE', 'google-analytics-for-wordpress' ); ?> </div>
            <div class="monsterinsights_google_auth_view_description monsterinsights_google_auth_start_view_description"><?php esc_html_e( 'You\'re all set!', 'google-analytics-for-wordpress' ); ?> </div>
            <input type="hidden" id="monsterinsightsreauth" name="monsterinsightsreauth" value="<?php echo ( $reauth) ? 'true' : 'false';?>">
            <input type="hidden" id="monsterinsightsview" name="monsterinsightsview" value="<?php echo 'done';?>">
        </div>
        <div id="monsterinsights_google_auth_box_footer">
            <div id="monsterinsights_google_auth_box_footer_left">
                &nbsp;
            </div>
            <div id="monsterinsights_google_auth_box_footer_center">
                &nbsp;
            </div>
            <div id="monsterinsights_google_auth_box_footer_right">
                <a href="#" id="monsterinsights_google_auth_box_done" class="monsterinsights_google_auth_box_done" title="<?php esc_attr_e( 'Save and close', 'google-analytics-for-wordpress' ); ?>" >
                    <div id="monsterinsights_google_auth_box_footer_right_next">
                         <?php esc_html_e( 'CLOSE', 'google-analytics-for-wordpress' ); ?> 
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;  
}

function monsterinsights_google_auth_error_view( $reauth = false, $error = '' ) {
    $class = ( $reauth ) ? 'monsterinsights_google_auth_reauth_exit_view' : 'monsterinsights_google_auth_exit_view' ;
    ob_start(); 
    ?>
    <div class="<?php echo $class; ?>">
        <?php if ( $reauth ) { ?>
        <div id="monsterinsights_google_auth_box_header">
                <div class="monsterinsights_google_auth_box_header_reauth">
                    <?php esc_html_e( 'An error has occured.', 'google-analytics-for-wordpress' ); ?> 
                    <a href="" class="monsterinsights_google_auth_box_cancel" title="<?php esc_attr_e( 'Exit', 'google-analytics-for-wordpress' ); ?>" > 
                        <?php esc_html_e( 'Exit', 'google-analytics-for-wordpress' ); ?>
                    </a> 
                </div>
        </div>
         <?php } ?>
         <div id="monsterinsights_google_auth_box_contents" <?php if ( $reauth ) { echo 'class="monsterinsights_google_auth_reauth"'; } ?> >
            <div class="monsterinsights_google_auth_view_title monsterinsights_google_auth_exit_view_title"><?php esc_html_e( 'ERROR', 'google-analytics-for-wordpress' ); ?> </div>
            <div class="monsterinsights_google_auth_view_description monsterinsights_google_auth_exit_view_description"><?php echo $error; ?> </div>
            <input type="hidden" id="monsterinsightsreauth" name="monsterinsightsreauth" value="<?php echo ( $reauth) ? 'true' : 'false';?>">
            <input type="hidden" id="monsterinsightsview" name="monsterinsightsview" value="<?php echo 'done';?>">
        </div>
        <div id="monsterinsights_google_auth_box_footer">
            <div id="monsterinsights_google_auth_box_footer_left">
                &nbsp;
            </div>
            <div id="monsterinsights_google_auth_box_footer_center">
                &nbsp;
            </div>
            <div id="monsterinsights_google_auth_box_footer_right">
                <a href="#" id="monsterinsights_google_auth_box_cancel_error" class="monsterinsights_google_auth_box_cancel_error" title="<?php esc_attr_e( 'Exit', 'google-analytics-for-wordpress' ); ?>" >
                    <div id="monsterinsights_google_auth_box_footer_right_next">
                         <?php esc_html_e( 'EXIT', 'google-analytics-for-wordpress' ); ?> 
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;  
}