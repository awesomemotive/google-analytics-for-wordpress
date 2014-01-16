<?php
/**
 * Uninstaller
 * Code by philipwalton <http://wordpress.org/support/profile/philipwalton>
 */

if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

if ( is_multisite() ) {
	
    global $wpdb;
    $blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
	
    if ( $blogs ) {
        foreach( $blogs as $blog ) {
            switch_to_blog( $blog['blog_id'] );
            delete_option( 'Yoast_Google_Analytics' );
        }
        restore_current_blog();
    }
	
} else {
	
    delete_option( 'Yoast_Google_Analytics' );
	
}
