<?php
/**
 * Events JS class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage  Events
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MonsterInsights_Events_JS {

	/**
	 * Holds the base class object.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var object $base Base class object.
	 */
	public $base;
	
	/**
	 * Holds the name of the events type.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $name Name of the events type.
	 */
	public $name = 'js';

	/**
	 * Version of the events class.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $version Version of the events class.
	 */
	public $version = '1.0.0';

	/**
	 * Primary class constructor.
	 *
	 * @since 6.0.0
	 * @access public
	 */
	public function __construct() {
		$this->base     = MonsterInsights();
		$tracking       = monsterinsights_get_option( 'tracking_mode', 'analytics' );
		$events         = monsterinsights_get_option( 'events_mode', false );
		if ( $events === 'js' && $tracking === 'analytics' ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'output_javascript' ), 9 ); 
			//add_action( 'login_head', array( $this, 'output_javascript' ), 9 );
		}
	}

	/**
	 * Outputs the Javascript for JS tracking on the page.
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @return string
	 */
	public function output_javascript() {
		// What should we track downloads as?
		$track_download_as = monsterinsights_get_option( 'track_download_as', '' );
		$track_download_as = $track_download_as === 'pageview' ? 'pageview' : 'event';

		// What label should be used for internal links?
		$internal_label = monsterinsights_get_option( 'track_internal_as_label', 'int' );
		if ( ! empty( $internal_label ) && is_string( $internal_label ) ) {
			$internal_label = trim( $internal_label, ',' );
			$internal_label = trim( $internal_label );
		}

		// If the label is empty, set a default value
		if ( empty( $internal_label ) ) {
			$internal_label = 'int';
		}

		$internal_label = esc_js( $internal_label );

		// Get inbound as outbound to track
		$inbound_paths = monsterinsights_get_option( 'track_internal_as_outbound','' );
		$inbound_paths = explode( ',', $inbound_paths );
		if ( ! is_array( $inbound_paths ) ) {
			$inbound_paths = array( $inbound_paths );
		}
		$i = 0;
		foreach ( $inbound_paths as $path ){
			$inbound_paths[ $i ] = esc_js( trim( $path ) );
			$i++;
		}

		$inbound_paths = implode( ",", $inbound_paths );

		// Get download extensions to track
		$download_extensions = monsterinsights_get_option( 'extensions_of_files', '' );
		$download_extensions = explode( ',', str_replace( '.', '', $download_extensions ) );
		if ( ! is_array( $download_extensions ) ) {
			$download_extensions = array( $download_extensions );
		}
		$i = 0;
		foreach( $download_extensions as $extension ){
			$download_extensions[ $i ] = esc_js( trim( $extension ) );
			$i++;
		}

		$download_extensions = implode( ",", $download_extensions );

		$is_debug_mode     =  monsterinsights_is_debug_mode();
		if ( current_user_can( 'manage_options' ) && $is_debug_mode ) {
			$is_debug_mode = 'true';
		} else {
			$is_debug_mode = 'false';
		}

		$hash_tracking = monsterinsights_get_option( 'hash_tracking', false ) ? 'true' : 'false';

		$suffix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		if ( ! file_exists( MONSTERINSIGHTS_PLUGIN_DIR . 'assets/js/frontend.min.js' ) ) {
			$suffix = '';
		}
		wp_enqueue_script( MONSTERINSIGHTS_PLUGIN_SLUG . '-frontend-script', plugins_url( 'assets/js/frontend' . $suffix . '.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), false );
		wp_localize_script(
			MONSTERINSIGHTS_PLUGIN_SLUG . '-frontend-script',
			'monsterinsights_frontend',
			array(
				'js_events_tracking'  => 'true',
				'is_debug_mode' 	  => $is_debug_mode,
				'download_extensions' => $download_extensions, /* Let's get the extensions to track */
				'inbound_paths'       => $inbound_paths, /* Let's get the internal paths to track */
				'home_url'            => home_url(), /* Let's get the url to compare for external/internal use */
				'track_download_as'   => $track_download_as, /* should downloads be tracked as events or pageviews */
				'internal_label'      => $internal_label, /* What is the prefix for internal-as-external links */
				'hash_tracking'       => $hash_tracking, /* Should hash track */
			)
		);
	}
}