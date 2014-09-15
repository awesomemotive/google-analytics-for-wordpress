<?php
/**
 * This is the frontend class for the GA Universal code
 */

if ( ! class_exists( 'Yoast_GA_Universal' ) ) {

	class Yoast_GA_Universal extends Yoast_GA_Frontend {
		public $link_regex;

		public function __construct() {
			parent::__construct();

			$this->link_regex = '`<a (.*?)href=[\'\"](.*?):/*([^\'\"]+)[\'\"](.*?)>(.*?)</a>`i';

			add_action( 'wp_head', array( $this, 'tracking' ), 8 );

			if ( $this->options['track_outbound'] == 1 ) {
				// Check for outbound option
				add_filter( 'the_content', array( $this, 'the_content' ), 99 );
				add_filter( 'widget_text', array( $this, 'widget_content' ), 99 );
				add_filter( 'the_excerpt', array( $this, 'the_content' ), 99 );
				add_filter( 'comment_text', array( $this, 'comment_text' ), 99 );
			}
		}

		/**
		 * Function to output the GA Tracking code in the wp_head()
		 *
		 * @todo, add the tracking code and remove this test output
		 */
		public function tracking() {
			global $wp_query;

			if ( $this->do_tracking() && ! is_preview() ) {
				$gaq_push = array();

				if ( isset( $this->options['subdomain_tracking'] ) && $this->options['subdomain_tracking'] != '' ) {
					$domain = $this->options['subdomain_tracking'];
				} else {
					$domain = 'auto'; // Default domain value
				}

				if ( ! isset( $this->options['allowanchor'] ) ) {
					$this->options['allowanchor'] = false;
				}

				$ua_code = $this->get_tracking_code();
				if ( is_null( $ua_code ) ) {
					return;
				}

				// Set tracking code here
				if ( ! empty( $ua_code ) ) {
					if ( $this->options['add_allow_linker'] && ! $this->options['allowanchor'] ) {
						$gaq_push[] = "'create', '" . $ua_code . "', '" . $domain . "', {'allowLinker': true}";
					} else {
						if ( $this->options['allowanchor'] && ! $this->options['add_allow_linker'] ) {
							$gaq_push[] = "'create', '" . $ua_code . "', '" . $domain . "', {'allowAnchor': true}";
						} else {
							if ( $this->options['allowanchor'] && $this->options['add_allow_linker'] ) {
								$gaq_push[] = "'create', '" . $ua_code . "', '" . $domain . "', {'allowAnchor': true, 'allowLinker': true}";
							} else {
								$gaq_push[] = "'create', '" . $ua_code . "', '" . $domain . "'";
							}
						}
					}
				}

				$gaq_push[] = "'set', 'forceSSL', true";

				if ( ! empty( $this->options['custom_code'] ) ) {
					// Add custom code to the view
					$gaq_push[] = array(
						'type'  => 'custom_code',
						'value' => $this->options['custom_code'],
					);
				}

				// Anonymous data
				if ( $this->options['anonymize_ips'] == 1 ) {
					$gaq_push[] = "'set', 'anonymizeIp', true";
				}

				// add demographics
				if ( $this->options['demographics'] ) {
					$gaq_push[] = "'require', 'displayfeatures'";
				}

				if ( isset( $this->options['allowhash'] ) && $this->options['allowhash'] ) {
					$gaq_push[] = "'_setAllowHash',false";
				}

				if ( is_404() ) {
					$gaq_push[] = "'send','pageview','/404.html?page=' + document.location.pathname + document.location.search + '&from=' + document.referrer";
				} else {
					if ( $wp_query->is_search ) {
						$pushstr = "'send','pageview','/?s=";
						if ( $wp_query->found_posts == 0 ) {
							$gaq_push[] = $pushstr . 'no-results:' . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=no-results'";
						} else {
							if ( $wp_query->found_posts == 1 ) {
								$gaq_push[] = $pushstr . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=1-result'";
							} else {
								if ( $wp_query->found_posts > 1 && $wp_query->found_posts < 6 ) {
									$gaq_push[] = $pushstr . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=2-5-results'";
								} else {
									$gaq_push[] = $pushstr . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=plus-5-results'";
								}
							}
						}
					} else {
						$gaq_push[] = "'send','pageview'";
					}
				}

				/**
				 * Filter: 'yoast-ga-push-array-universal' - Allows filtering of the commands to push
				 *
				 * @api array $gaq_push
				 */
				$gaq_push = apply_filters( 'yoast-ga-push-array-universal', $gaq_push );

				$ga_settings = $this->options; // Assign the settings to the javascript include view

				// Include the tracking view
				if ( $this->options['debug_mode'] == 1 ) {
					require( 'views/tracking-debug.php' );
				} else {
					require( 'views/tracking-universal.php' );
				}
			} else {
				require( 'views/tracking-usergroup.php' );
			}
		}

		/**
		 * Ouput tracking link
		 *
		 * @param string $label
		 * @param array  $matches
		 *
		 * @return mixed
		 */
		private function output_parse_link( $label, $matches ) {
			$link = $this->get_target( $label, $matches );

			// bail early for links that we can't handle
			if ( is_null( $link['type'] ) || 'internal' === $link['type'] ) {
				return $matches[0];
			}

			$onclick  = null;
			$full_url = $this->make_full_url( $link );

			switch ( $link['type'] ) {
				case 'download':
					if ( $this->options['track_download_as'] == 'pageview' ) {
						$onclick = "ga('send', 'pageview', '" . esc_attr( $full_url ) . "');";
					} else {
						$onclick = "ga('send', 'event', 'download', '" . esc_attr( $full_url ) . "');";
					}

					break;
				case 'email':
					$onclick = "ga('send', 'event', 'mailto', '" . esc_attr( $link['original_url'] ) . "');";

					break;
				case 'internal-as-outbound':
					if ( ! is_null( $this->options['track_internal_as_label'] ) && ! empty( $this->options['track_internal_as_label'] ) ) {
						$label = $this->options['track_internal_as_label'];
					} else {
						$label = 'int';
					}

					$onclick = "ga('send', 'event', '" . esc_attr( $link['category'] ) . '-' . esc_attr( $label ) . "', '" . esc_attr( $full_url ) . "', '" . esc_attr( strip_tags( $link['link_text'] ) ) . "');";

					break;
				case 'outbound':
					if ( $this->options['track_outbound'] == 1 ) {
						$onclick = "ga('send', 'event', '" . esc_attr( $link['category'] ) . "', '" . esc_attr( $full_url ) . "', '" . esc_attr( strip_tags( $link['link_text'] ) ) . "');";
					}

					break;
			}

			$link['link_attributes'] = $this->output_add_onclick( $link['link_attributes'], $onclick );

			return '<a href="' . $full_url . '" ' . $link['link_attributes'] . '>' . $link['link_text'] . '</a>';

		}

		/**
		 * Parse article link
		 *
		 * @param $matches
		 *
		 * @return mixed
		 */
		public function parse_article_link( $matches ) {
			return $this->output_parse_link( 'outbound-article', $matches );
		}

		/**
		 * Parse comment link
		 *
		 * @param $matches
		 *
		 * @return mixed
		 */
		public function parse_comment_link( $matches ) {
			return $this->output_parse_link( 'outbound-comment', $matches );
		}

		/**
		 * Parse widget link
		 *
		 * @param $matches
		 *
		 * @return mixed
		 */
		public function parse_widget_link( $matches ) {
			return $this->output_parse_link( 'outbound-widget', $matches );
		}

		/**
		 * Parse menu link
		 *
		 * @param $matches
		 *
		 * @return mixed
		 */
		public function parse_nav_menu( $matches ) {
			return $this->output_parse_link( 'outbound-menu', $matches );
		}

		/**
		 * Parse the_content or the_excerpt for links
		 *
		 * @param $text
		 *
		 * @return mixed
		 */
		public function the_content( $text ) {
			if ( false == $this->do_tracking() ) {
				return $text;
			}

			if ( ! is_feed() ) {
				$text = preg_replace_callback( $this->link_regex, array( $this, 'parse_article_link' ), $text );
			}

			return $text;
		}

		/**
		 * Parse the widget content for links
		 *
		 * @param $text
		 *
		 * @return mixed
		 */
		public function widget_content( $text ) {
			if ( ! $this->do_tracking() ) {
				return $text;
			}
			$text = preg_replace_callback( $this->link_regex, array( $this, 'parse_widget_link' ), $text );

			return $text;
		}

		/**
		 * Parse the nav menu for links
		 *
		 * @param $text
		 *
		 * @return mixed
		 */
		public function nav_menu( $text ) {
			if ( ! $this->do_tracking() ) {
				return $text;
			}

			if ( ! is_feed() ) {
				$text = preg_replace_callback( $this->link_regex, array( $this, 'parse_nav_menu' ), $text );
			}

			return $text;
		}

		/**
		 * Parse comment text for links
		 *
		 * @param $text
		 *
		 * @return mixed
		 */
		public function comment_text( $text ) {
			if ( ! $this->do_tracking() ) {
				return $text;
			}

			if ( ! is_feed() ) {
				$text = preg_replace_callback( $this->link_regex, array( $this, 'parse_comment_link' ), $text );
			}

			return $text;
		}
	}

	global $yoast_ga_universal;
	$yoast_ga_universal = new Yoast_GA_Universal;
}