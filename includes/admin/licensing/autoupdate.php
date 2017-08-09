<?php 
/**
 * Filters the auto update plugin routine to allow MonsterInsights to be
 * automatically updated.
 *
 * @since 6.3.0
 *
 * @param bool $update  Flag to update the plugin or not.
 * @param array $item   Update data about a specific plugin.
 * @return bool $update The new update state.
 */
function monsterinsights_automatic_updates( $update, $item ) {

    // If this is multisite and is not on the main site, return early.
    if ( is_multisite() && ! is_main_site() ) {
        return $update;
    }
    
    // If we don't have everything we need, return early.
    $item = (array) $item;
    if ( ! isset( $item['new_version'] ) || ! isset( $item['slug'] ) ) {
        return $update;
    }

    // If the plugin isn't ours, return early.
    $is_free = 'google-analytics-for-wordpress' === $item['slug'];
    $is_paid = isset( $item['monsterinsights_plugin'] ); // see updater class
    if ( ! $is_free && ! $is_paid ) {
        return $update;
    }

    $version           = $is_free ? MONSTERINSIGHTS_LITE_VERSION : $item['old_version'];
    $automatic_updates = monsterinsights_get_option( 'automatic_updates', false );
    $current_major     = monsterinsights_get_major_version( $version );
    $new_major         = monsterinsights_get_major_version( $item['new_version'] );
    
    // If the opt in update allows major updates but there is no major version update, return early.
    if ( $current_major < $new_major ) {
        if ( $automatic_updates === 'all' ) {
            return true;
        } else {
            return $update;
        }
    }
    
    // If the opt in update allows minor updates but there is no minor version update, return early.
    if ( $current_major == $new_major ) {
        if ( $automatic_updates === 'all' || $automatic_updates === 'minor' ) {
            return true;
        } else {
            return $update;
        }
    }

    // All our checks have passed - this plugin can be updated!
    return true;
}

add_filter( 'auto_update_plugin', 'monsterinsights_automatic_updates', 10, 2 );
/**
 * Notes about autoupdater:
 * This runs on the normal WordPress auto-update sequence:
 * 1. In wp-includes/update.php, wp_version_check() is called by the WordPress update cron (every 8 or 12 hours; can be overriden to be faster/long or turned off by plugins)
 * 2. In wp-includes/update.php, wp_version_check() ends with a action call to do_action( 'wp_maybe_auto_update' ) if cron is running
 * 3. In wp-includes/update.php, wp_maybe_auto_update() hooks into wp_maybe_auto_update action, creates a new WP_Automatic_Updater instance and calls WP_Automatic_Updater->run
 * 4. In wp-admin/includes/class-wp-automatic-updater.php $this->run() checks to make sure we're on the main site if on a network, and also if the autoupdates are disabled (by plugin, by being on a version controlled site, etc )
 * 5. In wp-admin/includes/class-wp-automatic-updater.php $this->run() then checks to see which plugins have new versions (version/update check)
 * 6. In wp-admin/includes/class-wp-automatic-updater.php $this->run() then calls $this->update() for each plugin installed who has an upgrade.
 * 7 In wp-admin/includes/class-wp-automatic-updater.php $this->update() double checks filesystem access and then installs the plugin if able
 *
 * Notes:
 * - This autoupdater only works if WordPress core detects no version control. If you want to test this, do it on a new WP site without any .git folders anywhere.
 * - This autoupdater only works if the file access is able to be written to
 * - This autoupdater only works if a new version has been detected, and will run not the second the update is released, but whenever the cron for wp_version_check is next released. This is generally run every 8-12 hours.
 * - However, that cron can be disabled, the autoupdater can be turned off via constant or filter, version control or file lock can be detected, and other plugins can be installed (incl in functions of theme) that turn off all
 *      all automatic plugin updates.
 * - If you want to test this is working, you have to manually run the wp_version_check cron. Install the WP Crontrol plugin or Core Control plugin, and run the cron manually using it.
 * - Again, because you skimmed over it the first time, if you want to test this manually you need to test this on a new WP install without version control for core, plugins, etc, without file lock, with license key entered (for pro only)
 *        and use the WP Crontrol or Core Control plugin to run wp_version_check
 * - You may have to manually remove an option called "auto_update.lock" from the WP options table
 * - You may need to run wp_version_check multiple times (note though that they must be spaced at least 60 seconds apart)
 * - Because WP's updater asks the OS if the file is writable, make sure you do not have any files/folders for the plugin you are trying to autoupdate open when testing.
 * - You may need to delete the plugin info transient to get it to hard refresh the plugin info.
 */


function monsterinsights_get_major_version( $version ) {
    $exploded_version = explode( '.', $version );
    if ( isset( $exploded_version[2] ) ) {
        return $exploded_version[0] . '.' . $exploded_version[1] . '.' . $exploded_version[2];
    } else {
        return $exploded_version[0] . '.' . $exploded_version[1] . '.0';
    }
}