<?php
/**
 * The frontend JS class
 */

if ( ! class_exists( 'Yoast_GA_JS' ) ) {

	class Yoast_GA_JS extends Yoast_GA_Frontend {


		public function __construct() {
			add_action( 'wp_head', array( $this, 'tracking' ), 8 );
		}

		public function do_tracking() {
			global $current_user;
			$options = parent::$options['ga_general'];

			get_currentuserinfo();

			if ( 0 == $current_user->ID ) {
				return true;
			}

			if ( in_array( $current_user->roles[0], $options["ignore_users"] ) ) {
				return false;
			} else {
				return true;
			}
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
			if ( $this->do_tracking() && ! is_preview() ) {
				$gaq_push = array();

				if ( isset( $options['subdomain_tracking'] ) && $options['subdomain_tracking'] != "" ) {
					$domain = $options['subdomain_tracking'];
				}
				else{
					$domain = 'auto'; // Default domain value
				}

				// Set tracking code here
				if ( ! empty( $options['manual_ua_code_field'] ) ) {
					if ( $options['add_allow_linker'] ) {
						$gaq_push[] = "'create', '" . $options['manual_ua_code_field'] . "', '".$domain."', {'allowLinker': true}";
					} else {
						$gaq_push[] = "'create', '" . $options['manual_ua_code_field'] . "', '".$domain."'";
					}
				}

				// Anonymous data
				if ( $options['anonymize_ips'] == 1 ) {
					$gaq_push[] = "'set', 'anonymizeIp', true";
				}

//				if ( $options['allowanchor'] ){
//					$gaq_push[] = "'_setAllowAnchor',true";
//				}

				// add _setAllowLinker
				if ( $options['demographics'] ) {
					$gaq_push[] = "'require', 'displayfeatures'";
				}

				if ( isset( $options['allowhash'] ) && $options['allowhash'] ) {
					$gaq_push[] = "'_setAllowHash',false";
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
//							$push[] = "'_setCustomVar'," . $customvarslot . ",'post_type','" . $post_type . "',3";
//							$customvarslot++;
//						}
//					}
//				} else if ( is_singular() && !is_home() ) {
//					if ( $options['cv_post_type'] ) {
//						$post_type = get_post_type();
//						if ( $post_type ) {
//							$push[] = "'_setCustomVar'," . $customvarslot . ",'post_type','" . $post_type . "',3";
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
//							$push[]  = "'_setCustomVar',$customvarslot,'tags','" . $tagsstr . "',3";
//						}
//						$customvarslot++;
//					}
//					if ( is_singular() ) {
//						if ( $options['cv_year'] ) {
//							$push[] = "'_setCustomVar',$customvarslot,'year','" . get_the_time( 'Y' ) . "',3";
//							$customvarslot++;
//						}
//						if ( $options['cv_category'] && is_single() ) {
//							$cats = get_the_category();
//							if ( is_array( $cats ) && isset( $cats[0] ) )
//								$push[] = "'_setCustomVar',$customvarslot,'category','" . $cats[0]->slug . "',3";
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
//							$push[]  = "'_setCustomVar',$customvarslot,'categories','" . $catsstr . "',3";
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
				require( GAWP_PATH . 'frontend/views/tracking_ga_js.php' );
			}
		}

	}

	global $yoast_ga_js;
	$yoast_ga_js = new Yoast_GA_JS;
}