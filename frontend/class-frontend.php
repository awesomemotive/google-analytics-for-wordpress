<?php
/**
 * The basic frontend class for the GA plugin, extendable for the children
 */

if ( ! class_exists( 'Yoast_GA_Frontend' ) ) {

	class Yoast_GA_Frontend {

		public static $options = array();

		public function __construct() {
			self::$options = get_option( 'yst_ga' );

			if ( isset( self::$options['ga_general']['tag_links_in_rss'] ) && self::$options['ga_general']['tag_links_in_rss'] == 1 ) {
				add_filter( 'the_permalink_rss', array( $this, 'rsslinktagger' ), 99 );
			}

			// Check if the customer is running Universal or not (Enable in GA Settings -> Universal)
			if ( isset( self::$options['ga_general']['enable_universal'] ) && self::$options['ga_general']['enable_universal'] == 1 ) {
				require_once GAWP_PATH . 'frontend/class-universal.php';
			} else {
				require_once GAWP_PATH . 'frontend/class-ga-js.php';
			}
		}

		public function get_options() {
			return self::$options;
		}

		/**
		 * Check if we need to show an actual tracking code
		 * @return bool
		 */
		public static function do_tracking() {
			global $current_user;
			$options = self::$options['ga_general'];

			get_currentuserinfo();

			if ( 0 == $current_user->ID ) {
				return true;
			}

			if ( isset( $options['ignore_users'] ) ) {
				if ( in_array( $current_user->roles[0], $options['ignore_users'] ) ) {
					return false;
				} else {
					return true;
				}
			} else {
				return true;
			}
		}

		/**
		 * Hook a Google Analytics Javascript to track downloads and outbound links
		 */
		public function add_ga_javascript() {
			wp_enqueue_script( 'yst_ga', GAWP_URL . 'frontend/js/yst_ga.js', array(), false, true );
		}

		/**
		 * Parse the domain
		 *
		 * @param $uri
		 *
		 * @return array|bool
		 */
		public function yoast_ga_get_domain( $uri ) {
			$hostPattern     = "/^(http:\/\/)?([^\/]+)/i";
			$domainPatternUS = "/[^\.\/]+\.[^\.\/]+$/";
			$domainPatternUK = "/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/";

			$matching = preg_match( $hostPattern, $uri, $matches );
			if ( $matching ) {
				$host = $matches[2];
				if ( preg_match( "/.*\..*\..*\..*$/", $host ) ) {
					preg_match( $domainPatternUK, $host, $matches );
				} else {
					preg_match( $domainPatternUS, $host, $matches );
				}

				if ( isset( $matches[0] ) ) {
					return array( "domain" => $matches[0], "host" => $host );
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		/**
		 * Add the UTM source parameters in the RSS feeds to track traffic
		 *
		 * @param $guid
		 *
		 * @return string
		 */
		function rsslinktagger( $guid ) {
			global $post;
			if ( is_feed() ) {
				if ( self::$options['ga_general']['allow_anchor'] ) {
					$delimiter = '#';
				} else {
					$delimiter = '?';
					if ( strpos( $guid, $delimiter ) > 0 ) {
						$delimiter = '&amp;';
					}
				}

				return $guid . $delimiter . 'utm_source=rss&amp;utm_medium=rss&amp;utm_campaign=' . urlencode( $post->post_name );
			}

			return $guid;
		}

		/**
		 * Return the target with a lot of parameters
		 *
		 * @param $category
		 * @param $matches
		 *
		 * @return array
		 */
		public function get_target( $category, $matches ) {
			$protocol            = $matches[2];
			$original_url        = $matches[3];
			$domain              = $this->yoast_ga_get_domain( $matches[3] );
			$origin              = $this->yoast_ga_get_domain( $_SERVER['HTTP_HOST'] );
			$options             = self::$options['ga_general'];
			$download_extensions = explode( ",", str_replace( '.', '', $options['extensions_of_files'] ) );
			$extension           = substr( strrchr( $original_url, '.' ), 1 );

			// Break out immediately if the link is not an http or https link.
			if ( $protocol !== 'http' && $protocol !== 'https' && $protocol !== 'mailto' ) {
				$type = null;
			} else {
				if ( ( $protocol == 'mailto' ) ) {
					$type = 'email';
				} elseif ( in_array( $extension, $download_extensions ) ) {
					$type = 'download';
				} else {
					if ( $domain['domain'] == $origin['domain'] ) {
						$outlinks = explode( ',', $options['track_internal_as_outbound'] );

						if ( count( $outlinks ) >= 1 ) {
							foreach ( $outlinks as $out ) {
								if ( strpos( $original_url, $domain['domain'] . $out ) !== false ) {
									$type = 'internal-as-outbound';
								}
							}
						}

						if ( ! isset( $type ) ) {
							$type = 'internal';
						}
					} elseif ( $domain['domain'] != $origin['domain'] ) {
						$type = 'outbound';
					}
				}
			}

			return array(
				'category'        => $category,
				'type'            => $type,
				'protocol'        => $protocol,
				'domain'          => $domain['domain'],
				'host'            => $domain['host'],
				'origin_domain'   => $origin['domain'],
				'origin_host'     => $origin['host'],
				'extension'       => $extension,
				'link_attributes' => rtrim( $matches[1] . ' ' . $matches[4] ),
				'link_text'       => $matches[5],
				'original_url'    => $original_url
			);
		}

		/**
		 * Merge the existing onclick with a new one and append it
		 *
		 * @param $link_attribute
		 * @param $onclick
		 *
		 * @return string
		 */
		public function output_add_onclick( $link_attribute, $onclick ) {
			if ( preg_match( '/onclick=[\'\"](.*?;)[\'\"]/i', $link_attribute, $matches ) > 0 ) {
				$js_snippet_single = "onclick='" . $matches[1] . " " . $onclick . "'";
				$js_snippet_double = 'onclick="' . $matches[1] . ' ' . $onclick . '"';

				$link_attribute = str_replace( 'onclick="' . $matches[1] . '"', $js_snippet_double, $link_attribute );
				$link_attribute = str_replace( "onclick='" . $matches[1] . "'", $js_snippet_single, $link_attribute );

				return $link_attribute;
			} else {
				if ( ! is_null( $onclick ) ) {
					return 'onclick="' . $onclick . '" '.$link_attribute;
				} else {
					return $link_attribute;
				}
			}
		}

		/**
		 * Generate the full URL
		 *
		 * @param $link
		 *
		 * @return string
		 */
		public function make_full_url( $link ) {
			switch ( $link['type'] ) {
				case "download":
				case "internal":
				case "internal-as-outbound":
				case "outbound":
					return $link['protocol'] . '://' . $link['original_url'];
					break;
				case "email":
					return 'mailto:' . $link['original_url'];
					break;
			}
		}
	}

	global $yoast_ga_frontend;
	$yoast_ga_frontend = new Yoast_GA_Frontend;
}