<?php
/**
 * The frontend JS class
 */

if ( ! class_exists( 'Yoast_GA_JS' ) ) {

	class Yoast_GA_JS extends Yoast_GA_Frontend {
		public $link_regex;

		public function __construct() {
			$this->link_regex = '/<a (.*?)href=[\'\"](.*?):\/*([^\'\"]+?)[\'\"](.*?)>(.*?)<\/a>/i';

			add_action( 'wp_head', array( $this, 'tracking' ), 8 );

			// Check for outbound
			add_filter( 'the_content', array( $this, 'the_content' ), 99 );
			add_filter( 'widget_text', array( $this, 'widget_content' ), 99 );
			add_filter( 'the_excerpt', array( $this, 'the_content' ), 99 );
			add_filter( 'comment_text', array( $this, 'comment_text' ), 99 );
		}

		/**
		 * Function to output the GA Tracking code in the wp_head()
		 *
		 * @todo, add the tracking code and remove this test output
		 */
		public function tracking() {
			global $wp_query, $current_user;

			// Make sure $current_user is filled.
			get_currentuserinfo();

			$options  = parent::$options['ga_general'];
			$gaq_push = array();

			/**
			 * The order of custom variables is very, very important: custom vars should always take up the same slot to make analysis easy.
			 */
			$customvarslot = 1;
			if ( parent::do_tracking() && ! is_preview() ) {
				$gaq_push = array();

				if ( isset( $options['subdomain_tracking'] ) && $options['subdomain_tracking'] != "" ) {
					$domain = $options['subdomain_tracking'];
				} else {
					$domain = NULL; // Default domain value
				}

				if ( ! isset( $options['allowanchor'] ) ) {
					$options['allowanchor'] = false;
				}

				$ua_code = '';
				if ( ! empty( $options['analytics_profile'] ) ) {
					$ua_code = $options['analytics_profile'];
				}

				if ( ! empty( $options['manual_ua_code_field'] ) && ! empty( $options['manual_ua_code'] ) ) {
					$ua_code = $options['manual_ua_code_field'];
				}

				$gaq_push[] = "'_setAccount', '" . $ua_code . "'";

				if ( ! is_null( $domain ) ) {
					$gaq_push[] = "'_setDomainName', '" . $domain . "'";
				}

				if ( $options['add_allow_linker'] && ! $options['allowanchor'] ) {
					$gaq_push[] = "'_setAllowAnchor', true";
				}

				// @todo, check for AllowLinker in GA.js? Universal only?

				// SSL data
				$gaq_push[] = "'_gat._forceSSL'";

				// Anonymous data
				if ( $options['anonymize_ips'] == 1 ) {
					$gaq_push[] = "'_gat._anonymizeIp'";
				}

				if ( isset( $options['allowhash'] ) && $options['allowhash'] ) {
					$gaq_push[] = "'_gat._anonymizeIp',true";
				}

				if ( is_404() ) {
					$gaq_push[] = "'send','pageview','/404.html?page=' + document.location.pathname + document.location.search + '&from=' + document.referrer";
				} else {
					if ( $wp_query->is_search ) {
						$pushstr = "'send','pageview','/?s=";
						if ( $wp_query->found_posts == 0 ) {
							$gaq_push[] = $pushstr . "no-results:" . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=no-results'";
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

				//$push = apply_filters( 'yoast-ga-push-after-pageview', $push );
				$ga_settings = $options; // Assign the settings to the javascript include view

				// Include the tracking view
				if ( $options['debug_mode'] == 1 ) {
					require( GAWP_PATH . 'frontend/views/tracking_debug.php' );
				} else {
					require( GAWP_PATH . 'frontend/views/tracking_ga_js.php' );
				}
			}
			else{
				require( GAWP_PATH . 'frontend/views/tracking_usergroup.php' );
			}
		}

		/**
		 * Get tracking prefix
		 *
		 * @return string
		 */
		public function get_tracking_prefix() {
			return ( empty( $this->options['trackprefix'] ) ) ? '/yoast-ga/' : $this->options['trackprefix'];
		}

		/**
		 * Ouput tracking link
		 *
		 * @param $link
		 *
		 * @return mixed
		 */
		private function output_parse_link( $link ) {
			$onclick  = NULL;
			$options  = $this->get_options();
			$options  = $options['ga_general'];
			$full_url = $this->make_full_url( $link );

			switch ( $link['type'] ) {
				case 'download':
					if ( $options['track_download_as'] == 'pageview' ) {
						$onclick = "_gaq.push(['_trackPageview','download/" . esc_js( $full_url ) . "']);";
					} else {
						$onclick = "_gaq.push(['_trackEvent','download/" . esc_js( $full_url ) . "']);";
					}

					break;
				case 'email':
					$onclick = "_gaq.push(['_trackEvent','mailto','" . esc_js( $link['original_url'] ) . "']);";

					break;
				case 'internal-as-outbound':
					if ( ! is_null( $options['track_internal_as_label'] ) ) {
						$label = $options['track_internal_as_label'];
					} else {
						$label = 'int';
					}

					$onclick = "_gaq.push(['_trackEvent', '" . $link['category'] . "-" . $label . "', '" . $full_url . "', '" . $link['link_text'] . "']);";

					break;
				case 'internal':
					$onclick = NULL;

					break;
				case 'outbound':
					if ( $options['track_outbound'] == 1 ) {
						$onclick = "_gaq.push(['_trackEvent', '" . $link['category'] . "', '" . $full_url . "', '" . $link['link_text'] . "']);";
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
			return $this->output_parse_link( $this->get_target( 'outbound-article', $matches ) );
		}

		/**
		 * Parse comment link
		 *
		 * @param $matches
		 *
		 * @return mixed
		 */
		public function parse_comment_link( $matches ) {
			return $this->output_parse_link( $this->get_target( 'outbound-comment', $matches ) );
		}

		/**
		 * Parse widget link
		 *
		 * @param $matches
		 *
		 * @return mixed
		 */
		public function parse_widget_link( $matches ) {
			return $this->output_parse_link( $this->get_target( 'outbound-widget', $matches ) );
		}

		/**
		 * Parse menu link
		 *
		 * @param $matches
		 *
		 * @return mixed
		 */
		public function parse_nav_menu( $matches ) {
			return $this->output_parse_link( $this->get_target( 'outbound-menu', $matches ) );
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
			if ( ! $this->do_tracking() )
				return $text;

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
			if ( ! $this->do_tracking() )
				return $text;

			if ( ! is_feed() ) {
				$text = preg_replace_callback( $this->link_regex, array( $this, 'parse_comment_link' ), $text );
			}

			return $text;
		}

	}

	global $yoast_ga_js;
	$yoast_ga_js = new Yoast_GA_JS;
}