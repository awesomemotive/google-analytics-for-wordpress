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

class MonsterInsights_Gtag_Events {

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
		add_action( 'wp_enqueue_scripts', array( $this, 'output_javascript' ), 9 );
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
		// Affiliate Links
		$inbound_paths = monsterinsights_get_option( 'affiliate_links', array() );
		if ( ! is_array( $inbound_paths ) ) {
			$inbound_paths = array();
		} else {
			foreach( $inbound_paths as $index => $pair ) {
				// if empty pair, unset and continue
				if ( empty( $pair['path'] ) ) {
					unset( $inbound_paths[$index] );
					continue;
				}

				// if path does not start with a /, start it with that
				$path                           = ! empty( $pair['path'] ) ? $pair['path'] : 'aff';
				$inbound_paths[$index]['path']  = trim( $path );

				// js escape the link label
				$label                          = ! empty( $pair['label'] ) ? $pair['label'] : 'aff';
				$inbound_paths[$index]['label'] = esc_js( trim( $label ) );
			}
		}

		$inbound_paths = wp_json_encode( $inbound_paths );

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

		$hash_tracking = monsterinsights_get_option( 'hash_tracking', false ) ? 'true' : 'false';

		$suffix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		if ( ! file_exists( MONSTERINSIGHTS_PLUGIN_DIR . 'assets/js/frontend-gtag.min.js' ) ) {
			$suffix = '';
		}
		wp_enqueue_script( 'monsterinsights-frontend-script', plugins_url( 'assets/js/frontend-gtag' . $suffix . '.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), false );
		wp_localize_script(
			'monsterinsights-frontend-script',
			'monsterinsights_frontend',
			array(
				'js_events_tracking'  => 'true',
				'download_extensions' => $download_extensions, /* Let's get the extensions to track */
				'inbound_paths'       => $inbound_paths, /* Let's get the internal paths to track */
				'home_url'            => home_url(), /* Let's get the url to compare for external/internal use */
				'hash_tracking'       => $hash_tracking, /* Should hash track */
				'ua'                  => monsterinsights_get_ua(), /* UA code used for tracking */
			)
		);
	}
}
