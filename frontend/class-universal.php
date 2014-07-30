<?php
/**
 * This is the frontend class for the GA Universal code
 */

if ( ! class_exists( 'Yoast_GA_Universal' ) ) {

	class Yoast_GA_Universal extends Yoast_GA_Frontend {

		public function __construct() {

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

				if( !isset($options['allowanchor']) ){
					$options['allowanchor'] = false;
				}

				// Set tracking code here
				if ( ! empty( $options['manual_ua_code_field'] ) ) {
					if ( $options['add_allow_linker'] && !$options['allowanchor']  ) {
						$gaq_push[] = "'create', '" . $options['manual_ua_code_field'] . "', '".$domain."', {'allowLinker': true}";
					} else if ( $options['allowanchor'] && !$options['add_allow_linker'] ){
						$gaq_push[] = "'create', '" . $options['manual_ua_code_field'] . "', '".$domain."', {'allowAnchor': true}";
					} else if ( $options['allowanchor'] && $options['add_allow_linker'] ){
						$gaq_push[] = "'create', '" . $options['manual_ua_code_field'] . "', '".$domain."', {'allowAnchor': true, 'allowLinker': true}";
					} else {
						$gaq_push[] = "'create', '" . $options['manual_ua_code_field'] . "', '".$domain."'";
					}
				}

				// Anonymous data
				if ( $options['force_ssl'] == 1 ) {
					$gaq_push[] = "'set', 'forceSSL', true";
				}

				// Anonymous data
				if ( $options['anonymize_ips'] == 1 ) {
					$gaq_push[] = "'set', 'anonymizeIp', true";
				}

				// add _setAllowLinker
				if ( $options['demographics'] ) {
					$gaq_push[] = "'require', 'displayfeatures'";
				}

				if ( isset( $options['allowhash'] ) && $options['allowhash'] ) {
					$gaq_push[] = "'_setAllowHash',false";
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
				if( $options['debug_mode'] == 1){
					require( GAWP_PATH . 'frontend/views/tracking_debug.php' );
				}
				else{
					require( GAWP_PATH . 'frontend/views/tracking_ga_js.php' );
				}
			}
		}
	}

	global $yoast_ga_universal;
	$yoast_ga_universal = new Yoast_GA_Universal;
}