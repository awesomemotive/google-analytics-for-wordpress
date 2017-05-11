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
 * Users are encouraged to update their code as soon as possible.
 */


/**
 * Deprecated Filter: 'yst-ga-filter-api-limit'.
 * 
 * Allow people to change the max results value in the API calls. Default value is 1000 results per call.
 *
 * @param int $limit Number of rows to request at most in API calls. Default 300.
 * @return int Number of rows to request at most in API calls.
 */
function monsterinsights_yst_ga_filter_api_limit( $limit ) {
	$message = sprintf( __( 'Use %s instead.', 'google-analytics-for-wordpress' ), '<code>monsterinsights_reporting_get_max_api_limit</code>' );
	return _monsterinsights_apply_filters_deprecated( 'yst-ga-filter-api-limit', array( $limit ), '6.0.0', $message );
}
add_filter( 'monsterinsights_reporting_get_max_api_limit', 'monsterinsights_yst_ga_filter_api_limit' );

/**
 * Deprecated Filter: 'yst_ga_track_super_admin'.
 * 
 * Allows filtering if the Super admin should be tracked in a multi-site setup. Default false.
 *
 * @param bool $track Whether to track super admins. Default false.
 * @return bool Whether to track super admins. Default false.
 */
function monsterinsights_yst_ga_track_super_admin( $track ) {
	$track = ! $track; // invert track as in Yoast it defaulted to track super admins
	$message = sprintf( __( 'Use %s instead.', 'google-analytics-for-wordpress' ), '<code>monsterinsights_track_super_admins</code>' );
	return _monsterinsights_apply_filters_deprecated( 'yst_ga_track_super_admin', array( $track ), '6.0.0', $message );
}
add_filter( 'monsterinsights_track_super_admins', 'monsterinsights_yst_ga_track_super_admin' );


/**
 * Deprecated Action: 'yst_tracking'.
 * 
 * Allows output before the analytics and ga.js tracking output.
 */
function monsterinsights_yst_tracking() {
	$message = sprintf( __( 'Use %s instead.', 'google-analytics-for-wordpress' ), '<code>monsterinsights_tracking_before{_$mode}</code>' );
	_monsterinsights_do_action_deprecated( 'yst_tracking', array(), '6.0.0', $message );
}
add_action( 'monsterinsights_tracking_before_ga', 'monsterinsights_yst_tracking' );
add_action( 'monsterinsights_tracking_before_analytics', 'monsterinsights_yst_tracking' );

/**
 * Deprecated Filter: 'yoast-ga-push-array-ga-js'.
 * 
 * Allows filtering of the commands to push.
 *
 * @param array $options GA.js options.
 * @return array GA.js options.
 */
function monsterinsights_yoast_ga_push_array_ga_js( $options ) {
	$message = sprintf( __( 'Use %s instead.', 'google-analytics-for-wordpress' ), '<code>monsterinsights_frontend_tracking_options_analytics_end</code>' );
	return _monsterinsights_apply_filters_deprecated( 'yoast-ga-push-array-ga-js', array( $options ), '6.0.0', $message );
}
add_filter( 'monsterinsights_frontend_tracking_options_ga_end', 'monsterinsights_yoast_ga_push_array_ga_js' );

/**
 * Deprecated Filter: 'yoast-ga-push-array-universal'.
 * 
 * Allows filtering of the commands to push.
 *
 * @param array $options analytics.js options.
 * @return array Analytics.js options.
 */
function monsterinsights_yoast_ga_push_array_universal( $options ) {
	$message = sprintf( __( 'Use %s instead.', 'google-analytics-for-wordpress' ), '<code>monsterinsights_frontend_tracking_options_analytics_end</code>' );
	return _monsterinsights_apply_filters_deprecated( 'yoast-ga-push-array-universal', array( $options ), '6.0.0', $message );
}
add_filter( 'monsterinsights_frontend_tracking_options_analytics_end', 'monsterinsights_yoast_ga_push_array_universal' );

/**
 * Deprecated Filter: 'yst_ga_filter_push_vars'.
 * 
 * Allow adding to the $options variables before scripts are required.
 *
 * @param array $options analytics.js options.
 * @return array Analytics.js options.
 */
function monsterinsights_yst_ga_filter_push_vars( $options ) {
	if ( ! has_filter('yst_ga_filter_push_vars' ) ) {
		return $options;
	} else {
		$message = sprintf( __( 'Use %s instead.', 'google-analytics-for-wordpress' ), '<code>monsterinsights_frontend_tracking_options_analytics_before_scripts</code>' );
		_monsterinsights_deprecated_hook( 'yst_ga_filter_push_vars', '6.0.0', $message );

		$i = 0;
		while ( true ) {
			if ( empty( $options[ 'yst_ga_filter_push_vars_' . $i ] ) ) {
				$options[ 'yst_ga_filter_push_vars_' . $i ] = apply_filters( 'yst_ga_filter_push_vars', $options ); 
				break;
			} else {
				$i++;
			}
		}
		return $options;
	}
}
add_filter( 'monsterinsights_frontend_tracking_options_analytics_before_scripts', 'monsterinsights_yst_ga_filter_push_vars' );


/**
 * Deprecated Filter: 'yst-ga-filter-ga-config'.
 * 
 * Allow filtering of the GA app.
 *
 * @param array $config GA App config.
 * @return array GA App config.
 */
function monsterinsights_yst_ga_filter_ga_config( $config ) {
	$message = sprintf( __( 'Use %s instead.', 'google-analytics-for-wordpress' ), '<code>monsterinsights_{lite/pro}_google_app_config</code>' );
	return _monsterinsights_apply_filters_deprecated( 'yst-ga-filter-ga-config', array( $config ), '6.0.0', $message );
}
add_filter( 'monsterinsights_lite_google_app_config', 'monsterinsights_yst_ga_filter_ga_config' );
add_filter( 'monsterinsights_pro_google_app_config', 'monsterinsights_yst_ga_filter_ga_config' );

function monsterinsights_disabled_user_group(){
	return ! monsterinsights_track_user();
}