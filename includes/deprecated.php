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
 * @since 6.0.0
 * @access private
 *
 * @see _apply_filters_deprecated()
 *
 * @param string $tag         The name of the filter hook.
 * @param array  $args        Array of additional function arguments to be passed to apply_filters().
 * @param string $version     The version of WordPress that deprecated the hook.
 * @param string $message     Optional. A message regarding the change. Default null.
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
 * @since 6.0.0
 * @access private
 *
 * @see _do_action_deprecated()
 *
 * @param string $tag         The name of the action hook.
 * @param array  $args        Array of additional function arguments to be passed to do_action().
 * @param string $version     The version of WordPress that deprecated the hook.
 * @param string $message     Optional. A message regarding the change.
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
 * @since 6.0.0
 * @access private
 *
 * @param string $hook        The hook that was used.
 * @param string $version     The version of WordPress that deprecated the hook.
 * @param string $message     Optional. A message regarding the change.
 */
function _monsterinsights_deprecated_hook( $hook, $version, $message = null ) {
	/**
	 * Fires when a deprecated hook is called.
	 *
	 * @since 6.0.0
	 *
	 * @param string $hook        The hook that was called.
	 * @param string $version     The version of MonsterInsights that deprecated the hook used.
	 * @param string $message     A message regarding the change.
	 */
	do_action( 'deprecated_hook_run', $hook, $version, $message );
 
	/**
	 * Filters whether to trigger deprecated hook errors.
	 *
	 * @since 6.0.0
	 *
	 * @param bool $trigger Whether to trigger deprecated hook errors. Requires
	 *                      `WP_DEBUG` to be defined true.
	 */
	if ( ( WP_DEBUG && apply_filters( 'deprecated_hook_trigger_error', true ) ) || monsterinsights_is_debug_mode() ) {
		$message = empty( $message ) ? '' : ' ' . $message;
		trigger_error( sprintf( esc_html__( '%1$s is %3$sdeprecated%4$s since MonsterInsights version %2$s!', 'google-analytics-for-wordpress' ), $hook, $version, '<strong>', '</strong>' ) . esc_html ( $message ) );
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
 * @since 6.0.0
 * @access private
 *
 * @uses do_action() Calls 'monsterinsights_deprecated_function_run' and passes the function name, what to use instead,
 *   and the version the function was deprecated in.
 * @uses apply_filters() Calls 'monsterinsights_deprecated_function_trigger_error' and expects boolean value of true to do
 *   trigger or false to not trigger error.
 *   
 * @param string  $function    The function that was called
 * @param string  $version     The version of WordPress that deprecated the function
 * @param array   $backtrace   Optional. Contains stack backtrace of deprecated function
 * @return void
 */
function _monsterinsights_deprecated_function( $function, $version, $backtrace = null ) {

	/**
	 * Deprecated Function Action.
	 *
	 * Allow plugin run an action on the use of a 
	 * deprecated function. This could be used to
	 * feed into an error logging program or file.
	 *
	 * @since 6.0.0
	 * 
	 * @param string  $function    The function that was called.
	 * @param string  $version     The version of WordPress that deprecated the function.
	 * @param array   $backtrace   Optional. Contains stack backtrace of deprecated function.
	 */	
	do_action( 'deprecated_function_run', $function, $version, $backtrace );

	/**
	 * Filters whether to trigger an error for deprecated functions.
	 *
	 * @since 6.0.0
	 *
	 * @param bool $trigger Whether to trigger the error for deprecated functions. Default true.
	 */
	if ( ( WP_DEBUG && apply_filters( 'deprecated_function_trigger_error', true ) ) || monsterinsights_is_debug_mode() ) {
		trigger_error( sprintf( esc_html__( '%1$s is %3$sdeprecated%4$s since MonsterInsights version %2$s.', 'google-analytics-for-wordpress' ), $function, $version, '<strong>', '</strong>' ) );
		trigger_error( print_r( $backtrace, 1 ) );// Limited to previous 1028 characters, but since we only need to move back 1 in stack that should be fine.
		// Alternatively we could dump this to a file.
	}
}

/**
 * Marks something as deprecated.
 *
 * The current behavior is to trigger a user error if WP_DEBUG is true.
 *
 * @since 6.0.0
 * @access private
 *
 * @uses apply_filters() Calls 'monsterinsights_deprecated_trigger_error' and expects boolean value of true to do
 *   trigger or false to not trigger error.
 *
 * @param string  $message     Deprecation message shown.
 * @return void
 */
function _monsterinsights_deprecated( $message ) {

	/**
	 * Deprecated Message Filter.
	 *
	 * Allow plugin to filter the deprecated message.
	 *
	 * @since 6.0.0
	 * 
	 * @param string $message Error message.
	 */	
	do_action( 'monsterinsights_deprecated_run', $message );

	$show_errors = current_user_can( 'manage_options' );

	/**
	 * Deprecated Error Trigger.
	 *
	 * Allow plugin to filter the output error trigger.
	 *
	 * @since 6.0.0
	 * 
	 * @param bool $show_errors Whether to show errors.
	 */
	$show_errors = apply_filters( 'monsterinsights_deprecated_trigger_error', $show_errors );
	if ( ( WP_DEBUG && $show_errors ) || monsterinsights_is_debug_mode() ) {
		trigger_error( esc_html( $message ) );
	}
}


/**
 * Start Deprecated Actions & Filters. 
 *
 * These backwards compatibility fixes may be removed at any time.
 * Users/Developers are encouraged to update their code as soon as possible.
 */

