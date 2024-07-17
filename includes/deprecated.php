<?php
/**
 * Deprecated functions.
 *
 * Contains the functions used to deprecate functions and
 * hooks in MonsterInsights, as well as the deprecated functions
 * and hooks themselves, where possible.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Deprecated
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fires functions attached to a deprecated filter hook.
 *
 * When a filter hook is deprecated, the apply_filters() call is replaced with
 * apply_filters_deprecated(), which triggers a deprecation notice and then fires
 * the original filter hook. Note, this is a copy of WordPress core's _apply_filters_deprecated
 * function, that we've copied into MonsterInsights so that we can use it on WordPress
 * versions older than 6.0.0 (when it was introduced to core). If we ever bump our
 * minimum WP version requirements above 6.0.0, we'll remove this function.
 *
 * @param string $tag The name of the filter hook.
 * @param array $args Array of additional function arguments to be passed to apply_filters().
 * @param string $version The version of WordPress that deprecated the hook.
 * @param string $message Optional. A message regarding the change. Default null.
 *
 * @since 6.0.0
 * @access private
 *
 * @see _apply_filters_deprecated()
 *
 */
function _monsterinsights_apply_filters_deprecated( $tag, $args, $version, $message = null ) {
	if ( ! has_filter( $tag ) ) {
		return $args[0];
	}

	_monsterinsights_deprecated_hook( $tag, $version, $message );

	return apply_filters_ref_array( $tag, $args );
}

/**
 * Fires functions attached to a deprecated action hook.
 *
 * When an action hook is deprecated, the do_action() call is replaced with
 * do_action_deprecated(), which triggers a deprecation notice and then fires
 * the original hook. Note, this is a copy of WordPress core's _do_action_deprecated
 * function, that we've copied into MonsterInsights so that we can use it on WordPress
 * versions older than 6.0.0 (when it was introduced to core). If we ever bump our
 * minimum WP version requirements above 6.0.0, we'll remove this function.
 *
 * @param string $tag The name of the action hook.
 * @param array $args Array of additional function arguments to be passed to do_action().
 * @param string $version The version of WordPress that deprecated the hook.
 * @param string $message Optional. A message regarding the change.
 *
 * @since 6.0.0
 * @access private
 *
 * @see _do_action_deprecated()
 *
 */
function _monsterinsights_do_action_deprecated( $tag, $args, $version, $message = null ) {
	if ( ! has_action( $tag ) ) {
		return;
	}

	_monsterinsights_deprecated_hook( $tag, $version, $message );

	do_action_ref_array( $tag, $args );
}

/**
 * Marks a deprecated action or filter hook as deprecated and throws a notice.
 *
 * Use the {@see 'deprecated_hook_run'} action to get the backtrace describing where
 * the deprecated hook was called.
 *
 * Default behavior is to trigger a user error if `WP_DEBUG` is true.
 *
 * This function is called by the do_action_deprecated() and apply_filters_deprecated()
 * functions, and so generally does not need to be called directly.
 *
 * Note, this is a copy of WordPress core's _deprecated_hook
 * function, that we've copied into MonsterInsights so that we can use it on WordPress
 * versions older than 6.0.0 (when it was introduced to core). If we ever bump our
 * minimum WP version requirements above 6.0.0, we'll remove this function.
 *
 * @param string $hook The hook that was used.
 * @param string $version The version of WordPress that deprecated the hook.
 * @param string $message Optional. A message regarding the change.
 *
 * @since 6.0.0
 * @access private
 *
 */
function _monsterinsights_deprecated_hook( $hook, $version, $message = null ) {
	/**
	 * Fires when a deprecated hook is called.
	 *
	 * @param string $hook The hook that was called.
	 * @param string $version The version of MonsterInsights that deprecated the hook used.
	 * @param string $message A message regarding the change.
	 *
	 * @since 6.0.0
	 *
	 */
	do_action( 'deprecated_hook_run', $hook, $version, $message );

	/**
	 * Filters whether to trigger deprecated hook errors.
	 *
	 * @param bool $trigger Whether to trigger deprecated hook errors. Requires
	 *                      `WP_DEBUG` to be defined true.
	 *
	 * @since 6.0.0
	 *
	 */
	if ( ( WP_DEBUG && apply_filters( 'deprecated_hook_trigger_error', true ) ) || monsterinsights_is_debug_mode() ) {
		$message = empty( $message ) ? '' : ' ' . $message;
		// Translators: Placeholders add the hook name, plugin version and bold text.
		trigger_error( sprintf( esc_html__( '%1$s is %3$sdeprecated%4$s since MonsterInsights version %2$s!', 'google-analytics-for-wordpress' ), $hook, $version, '<strong>', '</strong>' ) . $message ); // phpcs:ignore
	}
}

/**
 * Marks a function as deprecated and informs when it has been used.
 *
 * There is a hook monsterinsights_deprecated_function_run that will be called that can be used
 * to get the backtrace up to what file and function called the deprecated
 * function. Based on the one in EDD core.
 *
 * The current behavior is to trigger a user error if WP_DEBUG is true.
 *
 * This function is to be used in every function that is deprecated.
 *
 * @param string $function The function that was called
 * @param string $version The version of WordPress that deprecated the function
 * @param array $backtrace Optional. Contains stack backtrace of deprecated function
 *
 * @return void
 * @uses do_action() Calls 'monsterinsights_deprecated_function_run' and passes the function name, what to use instead,
 *   and the version the function was deprecated in.
 * @uses apply_filters() Calls 'monsterinsights_deprecated_function_trigger_error' and expects boolean value of true to do
 *   trigger or false to not trigger error.
 *
 * @since 6.0.0
 * @access private
 *
 */
function _monsterinsights_deprecated_function( $function, $version, $backtrace = null ) {

	/**
	 * Deprecated Function Action.
	 *
	 * Allow plugin run an action on the use of a
	 * deprecated function. This could be used to
	 * feed into an error logging program or file.
	 *
	 * @param string $function The function that was called.
	 * @param string $version The version of WordPress that deprecated the function.
	 * @param array $backtrace Optional. Contains stack backtrace of deprecated function.
	 *
	 * @since 6.0.0
	 *
	 */
	do_action( 'deprecated_function_run', $function, $version, $backtrace );

	/**
	 * Filters whether to trigger an error for deprecated functions.
	 *
	 * @param bool $trigger Whether to trigger the error for deprecated functions. Default true.
	 *
	 * @since 6.0.0
	 *
	 */
	if ( ( WP_DEBUG && apply_filters( 'deprecated_function_trigger_error', true ) ) || monsterinsights_is_debug_mode() ) {
		// Translators: Placeholders add the hook name, plugin version and bold text.
		trigger_error( sprintf( esc_html__( '%1$s is %3$sdeprecated%4$s since MonsterInsights version %2$s.', 'google-analytics-for-wordpress' ), $function, $version, '<strong>', '</strong>' ) );
		// Limited to previous 1028 characters, but since we only need to move back 1 in stack that should be fine.
		trigger_error( print_r( $backtrace, 1 ) ); // phpcs:ignore 
		// Alternatively we could dump this to a file.
	}
}

/**
 * Marks something as deprecated.
 *
 * The current behavior is to trigger a user error if WP_DEBUG is true.
 *
 * @param string $message Deprecation message shown.
 *
 * @return void
 * @since 6.0.0
 * @access private
 *
 * @uses apply_filters() Calls 'monsterinsights_deprecated_trigger_error' and expects boolean value of true to do
 *   trigger or false to not trigger error.
 *
 */
function _monsterinsights_deprecated( $message ) {

	/**
	 * Deprecated Message Filter.
	 *
	 * Allow plugin to filter the deprecated message.
	 *
	 * @param string $message Error message.
	 *
	 * @since 6.0.0
	 *
	 */
	do_action( 'monsterinsights_deprecated_run', $message );

	$show_errors = current_user_can( 'manage_options' );

	/**
	 * Deprecated Error Trigger.
	 *
	 * Allow plugin to filter the output error trigger.
	 *
	 * @param bool $show_errors Whether to show errors.
	 *
	 * @since 6.0.0
	 *
	 */
	$show_errors = apply_filters( 'monsterinsights_deprecated_trigger_error', $show_errors );
	if ( ( WP_DEBUG && $show_errors ) || monsterinsights_is_debug_mode() ) {
		trigger_error( esc_html( $message ) );
	}
}

/**
 * Check installed deprecated addons.
 *
 * @return void
 * @since 8.19.0
 */
function _monsterinsights_check_deprecated_addons() {
	// Check facebook-instant-articles
	if (
		in_array(
			'monsterinsights-facebook-instant-articles/monsterinsights-facebook-instant-articles.php',
			apply_filters(
				'active_plugins',
				get_option( 'active_plugins' )
			)
		)
	) {
		// Deprecated addon is activated, add a notice.
		add_action( 'admin_notices', '_monsterinsights_notice_deprecated_facebook_instant_articles' );
	}

	// Check google-optimize
	if (
		in_array(
			'monsterinsights-google-optimize/monsterinsights-google-optimize.php',
			apply_filters(
				'active_plugins',
				get_option( 'active_plugins' )
			)
		)
	) {
		// Deprecated addon is activated, add a notice.
		add_action( 'admin_notices', '_monsterinsights_notice_deprecated_google_optimize' );
	}
}

/**
 * Admin notice for deprecated Facebook Instant Articles addon
 *
 * @access public
 * @return void
 * @since 8.19.0
 *
 */
function _monsterinsights_notice_deprecated_facebook_instant_articles()
{
	?>
	<div data-dismissible="deprecated-addon-facebook-instant-articles" class="notice notice-error is-dismissible">
		<p>
			<?php echo __( 'Facebook Instant Article support ended in April 2023. You may deactivate and delete the MonsterInsights addon at your earliest convenience.', 'ga-premium' ); ?>
		</p>
	</div>
	<?php
}

/**
 * Admin notice for deprecated Google Optimize addon
 *
 * @access public
 * @return void
 * @since 8.20.0
 *
 */
function _monsterinsights_notice_deprecated_google_optimize()
{
	?>
	<div data-dismissible="deprecated-addon-facebook-instant-articles" class="notice notice-error is-dismissible">
		<p>
			<?php echo __( 'Google Optimize and Optimize 360 support ended in September 2023. You may deactivate and delete the MonsterInsights addon at your earliest convenience.', 'ga-premium' ); ?>
		</p>
	</div>
	<?php
}

if (!function_exists('monsterinsights_get_ua')) {
    function monsterinsights_get_ua() {
        return '';
    }
}

if (!function_exists('monsterinsights_get_network_ua')) {
    function monsterinsights_get_network_ua() {
        return '';
    }
}

if (!function_exists('monsterinsights_mp_track_event_call')) {
    function monsterinsights_mp_track_event_call() {
        return '';
    }
}

if (!function_exists('monsterinsights_mp_api_call')) {
    function monsterinsights_mp_api_call() {
        return '';
    }
}

if (!function_exists('monsterinsights_get_mp_api_url')) {
    function monsterinsights_get_mp_api_url() {
        return '';
    }
}

if (!function_exists('monsterinsights_get_tracking_ids')) {
    function monsterinsights_get_tracking_ids() {
        return '';
    }
}

if (!function_exists('monsterinsights_is_valid_ua')) {
    function monsterinsights_is_valid_ua() {
        return false;
    }
}

if (!function_exists('monsterinsights_get_ua_to_output')) {
    function monsterinsights_get_ua_to_output() {
        return '';
    }
}