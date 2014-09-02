<?php
/**
 * The frontend JS class
 */

if ( ! class_exists( 'Yoast_GA_JS' ) ) {

	class Yoast_GA_JS extends Yoast_GA_Frontend {

		public function __construct() {
			add_action( 'wp_head', array( $this, 'tracking' ), 8 );

			// Check for outbound option
			add_filter( 'the_content', array( $this, 'the_content' ), 99 );
			add_filter( 'the_excerpt', array( $this, 'the_content' ), 99 );
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
				}
				else{
					$domain = NULL; // Default domain value
				}

				if( !isset($options['allowanchor']) ){
					$options['allowanchor'] = false;
				}

				$ua_code = '';
				if ( ! empty( $options['analytics_profile'] ) ) {
					$ua_code = $options['analytics_profile'];
				}

				if ( ! empty( $options['manual_ua_code_field'] ) && ! empty( $options['manual_ua_code'] ) ) {
					$ua_code = $options['manual_ua_code_field'];
				}

				$gaq_push[]	=	"'_setAccount', '".$ua_code."'";

				if(!is_null($domain)){
					$gaq_push[]	=	"'_setDomainName', '".$domain."'";
				}

				if($options['add_allow_linker'] && !$options['allowanchor']){
					$gaq_push[]	=	"'_setAllowAnchor', true";
				}

				// @todo, check for AllowLinker in GA.js? Universal only?

				// SSL data
				if ( $options['force_ssl'] == 1 ) {
					$gaq_push[] = "'_gat._forceSSL'";
				}

				// Anonymous data
				if ( $options['anonymize_ips'] == 1 ) {
					$gaq_push[] = "'_gat._anonymizeIp'";
				}

				if ( isset( $options['allowhash'] ) && $options['allowhash'] ) {
					$gaq_push[] = "'_gat._anonymizeIp',true";
				}

//				if ( $options['cv_loggedin'] ) {
//					if ( $current_user && $current_user->ID != 0 )
//						$gaq_push[] = "'_setCustomVar',$customvarslot,'logged-in','" . $current_user->roles[0] . "',1";
//					// Customvar slot needs to be upped even when the user is not logged in, to make sure the variables below are always in the same slot.
//					$customvarslot++;
//				}

//				if ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {
//					if ( $options['cv_post_type'] ) {
//						$post_type = get_post_type();
//						if ( $post_type ) {
//							$gaq_push[] = "'_setCustomVar'," . $customvarslot . ",'post_type','" . $post_type . "',3";
//							$customvarslot++;
//						}
//					}
//				} else if ( is_singular() && !is_home() ) {
//					if ( $options['cv_post_type'] ) {
//						$post_type = get_post_type();
//						if ( $post_type ) {
//							$gaq_push[] = "'_setCustomVar'," . $customvarslot . ",'post_type','" . $post_type . "',3";
//							$customvarslot++;
//						}
//					}
//					if ( $options['cv_authorname'] ) {
//						$push[] = "'_setCustomVar',$customvarslot,'author','" . $this->str_clean( get_the_author_meta( 'display_name', $wp_query->post->post_author ) ) . "',3";
//						$customvarslot++;
//					}
//					if ( $options['cv_tags'] ) {
//						$i = 0;
//						if ( get_the_tags() ) {
//							$tagsstr = '';
//							foreach ( get_the_tags() as $tag ) {
//								if ( $i > 0 )
//									$tagsstr .= ' ';
//								$tagsstr .= $tag->slug;
//								$i++;
//							}
//							// Max 64 chars for value and label combined, hence 64 - 4
//							$tagsstr = substr( $tagsstr, 0, 60 );
//							$gaq_push[]  = "'_setCustomVar',$customvarslot,'tags','" . $tagsstr . "',3";
//						}
//						$customvarslot++;
//					}
//					if ( is_singular() ) {
//						if ( $options['cv_year'] ) {
//							$gaq_push[] = "'_setCustomVar',$customvarslot,'year','" . get_the_time( 'Y' ) . "',3";
//							$customvarslot++;
//						}
//						if ( $options['cv_category'] && is_single() ) {
//							$cats = get_the_category();
//							if ( is_array( $cats ) && isset( $cats[0] ) )
//								$gaq_push[] = "'_setCustomVar',$customvarslot,'category','" . $cats[0]->slug . "',3";
//							$customvarslot++;
//						}
//						if ( $options['cv_all_categories'] && is_single() ) {
//							$i       = 0;
//							$catsstr = '';
//							foreach ( (array) get_the_category() as $cat ) {
//								if ( $i > 0 )
//									$catsstr .= ' ';
//								$catsstr .= $cat->slug;
//								$i++;
//							}
//							// Max 64 chars for value and label combined, hence 64 - 10
//							$catsstr = substr( $catsstr, 0, 54 );
//							$gaq_push[]  = "'_setCustomVar',$customvarslot,'categories','" . $catsstr . "',3";
//							$customvarslot++;
//						}
//					}
//				}

//				$gaq_push = apply_filters( 'yoast-ga-custom-vars', $gaq_push, $customvarslot );
//
//				$gaq_push = apply_filters( 'yoast-ga-push-before-pageview', $gaq_push );

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
				if( $options['debug_mode'] == 1){
					require( GAWP_PATH . 'frontend/views/tracking_debug.php' );
				}
				else{
					require( GAWP_PATH . 'frontend/views/tracking_ga_js.php' );
				}
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
		private function output_parse_link( $link ){

			echo '<pre>';
			print_r($link);
			echo '</pre>';

			$onclick = NULL;

			switch( $link['type'] ){
				case 'download':

					if( $link['action'] == 'pageview' ){
						$onclick = "['_trackPageview','download/" . esc_js( esc_url( $link['target'] ) ) . "']";
					}
					else{
						$onclick = "['_trackEvent','mailto','" . esc_js( esc_url( $link['target'] ) ) . "']";
					}

					break;
				case 'mailto':

					break;
			}

			if(strpos($link['link_attributes'], 'onclick')){
				$link['link_attributes']	.=	$this->output_add_onclick($link['link_attributes'], $onclick);
			}
			else{
				$link['link_attributes']	.=	' onclick="' . $onclick . '"';
			}

			return '<a href="' . $link['protocol'] . '://' . $link['original_url'] . '" ' . $link['link_attributes'] . '>' . $link['link_text'] . '</a>';

		}

		/**
		 * Parse article link
		 * @param $matches
		 *
		 * @return mixed
		 */
		public function parse_article_link( $matches ) {
			return $this->output_parse_link( $this->get_target( 'outbound-article', $matches ) );
		}

		/**
		 * Parse the_content or the_excerpt for links
		 * @param $text
		 *
		 * @return mixed
		 */
		public function the_content( $text ) {
			if ( false == $this->do_tracking() ){
				return $text;
			}

			if ( !is_feed() ) {
				static $anchorPattern = '/<a (.*?)href=[\'\"](.*?):\/*([^\'\"]+?)[\'\"](.*?)>(.*?)<\/a>/i';
				$text = preg_replace_callback( $anchorPattern, array( $this, 'parse_article_link' ), $text );
			}
			return $text;
		}

	}

	global $yoast_ga_js;
	$yoast_ga_js = new Yoast_GA_JS;
}