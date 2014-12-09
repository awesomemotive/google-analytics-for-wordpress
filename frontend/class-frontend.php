<?php
/**
 * The basic frontend class for the GA plugin, extendable for the children
 */

if ( ! class_exists( 'Yoast_GA_Frontend' ) ) {

	class Yoast_GA_Frontend extends Yoast_GA_Options {

		public $link_regex;

		/**
		 * Class constructor
		 */
		public function __construct() {
			parent::__construct();

			if ( isset( $this->options['tag_links_in_rss'] ) && $this->options['tag_links_in_rss'] == 1 ) {
				add_filter( 'the_permalink_rss', array( $this, 'rsslinktagger' ), 99 );
			}

			// Check if the customer is running Universal or not (Enable in GA Settings -> Universal)
			if ( isset( $this->options['enable_universal'] ) && $this->options['enable_universal'] == 1 ) {
				global $yoast_ga_universal;
				$yoast_ga_universal = new Yoast_GA_Universal;
			} else {
				global $yoast_ga_js;
				$yoast_ga_js = new Yoast_GA_JS;
			}

		}

		/**
		 * Get the regex for Ga.js and universal tracking to detect links
		 *
		 * @return string Contains the regular expression for detecting links
		 */
		public function get_regex() {
			return '/<a\s+([^>]*?)href=[\'\"](.*?):(\/\/)?([^\'\"]+?)[\'\"]\s?(.*?)>(.*?)<\/a>/i';
		}

		/**
		 * Check if we need to show an actual tracking code
		 *
		 * @return bool
		 */
		public function do_tracking() {
			global $current_user;

			get_currentuserinfo();

			if ( 0 == $current_user->ID ) {
				return true;
			}

			if ( isset( $this->options['ignore_users'] ) ) {
				if ( ! empty( $current_user->roles ) && in_array( $current_user->roles[0], $this->options['ignore_users'] ) ) {
					return false;
				}
			}
			return true;
		}

		/**
		 * Parse the domain
		 *
		 * @param $uri
		 *
		 * @return array|bool
		 */
		public function yoast_ga_get_domain( $uri ) {
			$hostPattern     = '/^(https?:\/\/)?([^\/]+)/i';
			$domainPatternUS = '/[^\.\/]+\.[^\.\/]+$/';
			$domainPatternUK = '/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/';

			$matching = preg_match( $hostPattern, $uri, $matches );
			if ( $matching ) {
				$host = $matches[2];
				if ( preg_match( '/.*\..*\..*\..*$/', $host ) ) {
					preg_match( $domainPatternUK, $host, $matches );
				} else {
					preg_match( $domainPatternUS, $host, $matches );
				}

				if ( isset( $matches[0] ) ) {
					return array( 'domain' => $matches[0], 'host' => $host );
				}
			}
			return false;
		}

		/**
		 * Add the UTM source parameters in the RSS feeds to track traffic
		 *
		 * @param string $guid
		 *
		 * @return string
		 */
		public function rsslinktagger( $guid ) {
			global $post;
			if ( is_feed() ) {
				if ( $this->options['allow_anchor'] ) {
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
		 * @param string $category
		 * @param array  $matches
		 *
		 * @return array
		 */
		public function get_target( $category, $matches ) {
			$protocol            = $matches[2];
			$original_url        = $matches[4];
			$domain              = $this->yoast_ga_get_domain( $matches[4] );
			$origin              = $this->yoast_ga_get_domain( $_SERVER['HTTP_HOST'] );
			$download_extensions = explode( ',', str_replace( '.', '', $this->options['extensions_of_files'] ) );
			$extension           = substr( strrchr( $original_url, '.' ), 1 );

			// Break out immediately if the link is not an http or https link.
			$type = null;
			if ( $protocol !== 'http' && $protocol !== 'https' && $protocol !== 'mailto' ) {
				$type = null;
			} else {
				if ( ( $protocol == 'mailto' ) ) {
					$type = 'email';
				} elseif ( in_array( $extension, $download_extensions ) ) {
					$type = 'download';
				} else {
					if ( $domain['domain'] == $origin['domain'] ) {
						$out_links = explode( ',', $this->options['track_internal_as_outbound'] );

						if ( count( $out_links ) >= 1 ) {
							foreach ( $out_links as $out ) {
								if ( ! empty( $original_url ) && ! empty( $domain['domain'] ) ) {
									if ( strpos( $original_url, $domain['domain'] . $out ) !== false ) {
										$type = 'internal-as-outbound';
									}
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
				'link_attributes' => rtrim( $matches[1] . ' ' . $matches[5] ),
				'link_text'       => $matches[6],
				'original_url'    => $original_url,
			);
		}

		/**
		 * Merge the existing onclick with a new one and append it
		 *
		 * @param string $link_attribute
		 * @param string $onclick
		 *
		 * @return string
		 */
		public function output_add_onclick( $link_attribute, $onclick ) {
			if ( preg_match( '/onclick=[\'\"](.*?;)[\'\"]/i', $link_attribute, $matches ) > 0 ) {
				$js_snippet_single = 'onclick=\'' . $matches[1] . ' ' . $onclick . '\'';
				$js_snippet_double = 'onclick="' . $matches[1] . ' ' . $onclick . '"';

				$link_attribute = str_replace( 'onclick="' . $matches[1] . '"', $js_snippet_double, $link_attribute );
				$link_attribute = str_replace( "onclick='" . $matches[1] . "'", $js_snippet_single, $link_attribute );

				return $link_attribute;
			} else {
				if ( ! is_null( $onclick ) ) {
					return 'onclick="' . $onclick . '" ' . $link_attribute;
				} else {
					return $link_attribute;
				}
			}
		}

		/**
		 * Generate the full URL
		 *
		 * @param string $link
		 *
		 * @return string
		 */
		public function make_full_url( $link ) {
			switch ( $link['type'] ) {
				case 'download':
				case 'internal':
				case 'internal-as-outbound':
				case 'outbound':
					return $link['protocol'] . '://' . $link['original_url'];
					break;
				case 'email':
					return 'mailto:' . $link['original_url'];
					break;
			}
		}
	}

}
