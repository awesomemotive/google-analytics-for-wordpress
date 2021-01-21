<?php
/**
 * Tracking gtag.js class.
 *
 * @since 7.15.0
 *
 * @package MonsterInsights
 * @author  Mircea Sandu
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MonsterInsights_Tracking_Gtag extends MonsterInsights_Tracking_Abstract {

	/**
	 * Holds the name of the tracking type.
	 *
	 * @since 7.15.0
	 * @access public
	 *
	 * @var string $name Name of the tracking type.
	 */
	public $name = 'gtag';

	/**
	 * Version of the tracking class.
	 *
	 * @since 7.15.0
	 * @access public
	 *
	 * @var string $version Version of the tracking class.
	 */
	public $version = '1.0.0';

	/**
	 * Primary class constructor.
	 *
	 * @since 7.15.0
	 * @access public
	 */
	public function __construct() {

	}

	/**
	 * Array of options that will be made persistent by setting them before the pageview.
	 *
	 * @see https://developers.google.com/analytics/devguides/collection/gtagjs/setting-values
	 * @return array Options for persistent values, like custom dimensions.
	 * @since 7.15.0
	 * @access public
	 */
	public function frontend_tracking_options_persistent() {
		$options = apply_filters( 'monsterinsights_frontend_tracking_options_persistent_gtag_before_pageview', array() );

		return $options;
	}

	/**
	 * Get frontend tracking options for the gtag script.
	 *
	 * This function is used to return an array of parameters
	 * for the frontend_output() function to output. These are
	 * generally dimensions and turned on GA features.
	 *
	 * @return array Options for the gtag config.
	 * @since 7.15.0
	 * @access public
	 *
	 */
	public function frontend_tracking_options() {
		global $wp_query;
		$options = array();

		$ua_code = monsterinsights_get_ua_to_output();
		if ( empty( $ua_code ) ) {
			return $options;
		}

//		$track_user = monsterinsights_track_user();
//
//		if ( ! $track_user ) {
//			$options['create']   = "'create', '" . esc_js( $ua_code ) . "', '" . esc_js( 'auto' ) . "'";
//			$options['forceSSL'] = "'set', 'forceSSL', true";
//			$options['send']     = "'send','pageview'";
//
//			return $options;
//		}

		$cross_domains = monsterinsights_get_option( 'cross_domains', array() );
		$allow_anchor  = monsterinsights_get_option( 'allow_anchor', false );

		if ( $allow_anchor ) {
			$options['allow_anchor'] = 'true';
		}

		if ( class_exists( 'MonsterInsights_AMP' ) ) {
			$options['use_amp_client_id'] = 'true';
		}


		$options['forceSSL'] = 'true';

		// Anonymous data.
		if ( monsterinsights_get_option( 'anonymize_ips', false ) ) {
			$options['anonymize_ip'] = 'true';
		}

		$options = apply_filters( 'monsterinsights_frontend_tracking_options_gtag_before_scripts', $options );

		// Add Enhanced link attribution.
		if ( monsterinsights_get_option( 'link_attribution', false ) ) {
			$options['link_attribution'] = 'true';
		}

		// Add cross-domain tracking.
		if ( is_array( $cross_domains ) && ! empty( $cross_domains ) ) {
			$options['linker'] = array( $cross_domains );
		}

		$options = apply_filters( 'monsterinsights_frontend_tracking_options_gtag_before_pageview', $options );
		$options = apply_filters( 'monsterinsights_frontend_tracking_options_before_pageview', $options, $this->name, $this->version );

		if ( is_404() ) {
			if ( monsterinsights_get_option( 'hash_tracking', false ) ) {
				$options['page_path'] = "'/404.html?page=' + document.location.pathname + document.location.search + location.hash + '&from=' + document.referrer";
			} else {
				$options['page_path'] = "'/404.html?page=' + document.location.pathname + document.location.search + '&from=' + document.referrer";
			}
		} else if ( $wp_query->is_search ) {
			$pushstr = "'/?s=";
			if ( 0 === (int) $wp_query->found_posts ) {
				$options['page_path'] = $pushstr . 'no-results:' . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=no-results'";
			} else if ( (int) $wp_query->found_posts === 1 ) {
				$options['page_path'] = $pushstr . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=1-result'";
			} else if ( $wp_query->found_posts > 1 && $wp_query->found_posts < 6 ) {
				$options['page_path'] = $pushstr . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=2-5-results'";
			} else {
				$options['page_path'] = $pushstr . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=plus-5-results'";
			}
		} else if ( monsterinsights_get_option( 'hash_tracking', false ) ) {
			$options['page_path'] = 'location.pathname + location.search + location.hash';
		}

		$options = apply_filters( 'monsterinsights_frontend_tracking_options_gtag_end', $options );

		return $options;
	}

	/**
	 * Get frontend output.
	 *
	 * This function is used to return the Javascript
	 * to output in the head of the page for the given
	 * tracking method.
	 *
	 * @return string Javascript to output.
	 * @since 7.15.0
	 * @access public
	 *
	 */
	public function frontend_output() {
		$options     = $this->frontend_tracking_options();
		$persistent  = $this->frontend_tracking_options_persistent();
		$ua          = monsterinsights_get_ua();
		$src         = apply_filters( 'monsterinsights_frontend_output_gtag_src', '//www.googletagmanager.com/gtag/js?id=' . $ua );
		$compat_mode = monsterinsights_get_option( 'gtagtracker_compatibility_mode', true );
		$compat      = $compat_mode ? 'window.gtag = __gtagTracker;' : '';
		$track_user  = monsterinsights_track_user();
		$output      = '';
		$reason      = '';
		$attr_string = monsterinsights_get_frontend_analytics_script_atts();
		ob_start();
		?>
		<!-- This site uses the Google Analytics by MonsterInsights plugin v<?php echo MONSTERINSIGHTS_VERSION; ?> - Using Analytics tracking - https://www.monsterinsights.com/ -->
		<?php if ( ! $track_user ) {
			if ( empty( $ua ) ) {
				$reason = __( 'Note: MonsterInsights is not currently configured on this site. The site owner needs to authenticate with Google Analytics in the MonsterInsights settings panel.', 'google-analytics-for-wordpress' );
				$output .= '<!-- ' . esc_html( $reason ) . ' -->' . PHP_EOL;
			} else if ( current_user_can( 'monsterinsights_save_settings' ) ) {
				$reason = __( 'Note: MonsterInsights does not track you as a logged-in site administrator to prevent site owners from accidentally skewing their own Google Analytics data.' . PHP_EOL . 'If you are testing Google Analytics code, please do so either logged out or in the private browsing/incognito mode of your web browser.', 'google-analytics-for-wordpress' );
				$output .= '<!-- ' . esc_html( $reason ) . ' -->' . PHP_EOL;
			} else {
				$reason = __( 'Note: The site owner has disabled Google Analytics tracking for your user role.', 'google-analytics-for-wordpress' );
				$output .= '<!-- ' . esc_html( $reason ) . ' -->' . PHP_EOL;
			}
			echo $output;
		} ?>
		<?php if ( $ua ) { ?>
			<script src="<?php echo esc_attr( $src ); ?>" <?php echo $attr_string; ?>></script>
			<script<?php echo $attr_string; ?>>
				var mi_version = '<?php echo MONSTERINSIGHTS_VERSION; ?>';
				var mi_track_user = <?php echo( $track_user ? 'true' : 'false' ); ?>;
				var mi_no_track_reason = <?php echo( $reason ? "'" . esc_js( $reason ) . "'" : "''" ); ?>;
				<?php do_action( 'monsterinsights_tracking_gtag_frontend_output_after_mi_track_user' ); ?>

				<?php if ( $this->should_do_optout() ) { ?>
				var disableStr = 'ga-disable-<?php echo monsterinsights_get_ua(); ?>';

				/* Function to detect opted out users */
				function __gtagTrackerIsOptedOut() {
					return document.cookie.indexOf( disableStr + '=true' ) > - 1;
				}

				/* Disable tracking if the opt-out cookie exists. */
				if ( __gtagTrackerIsOptedOut() ) {
					window[disableStr] = true;
				}

				/* Opt-out function */
				function __gtagTrackerOptout() {
					document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
					window[disableStr] = true;
				}

				if ( 'undefined' === typeof gaOptout ) {
					function gaOptout() {
						__gtagTrackerOptout();
					}
				}
				<?php } ?>
				window.dataLayer = window.dataLayer || [];
				if ( mi_track_user ) {
					function __gtagTracker() {
						dataLayer.push( arguments );
					}
					__gtagTracker( 'js', new Date() );
					__gtagTracker( 'set', {
						'developer_id.dZGIzZG' : true,
						<?php
						if ( ! empty( $persistent ) ) {
							foreach ( $persistent as $key => $value ) {
								echo "'" . esc_js( $key ) . "' : '" . stripslashes( $value ) . "',";
							}
						}
						?>
                    });
					__gtagTracker( 'config', '<?php echo esc_js( $ua ); ?>', {
						<?php
						foreach ( $options as $key => $value ) {
							echo esc_js( $key ) . ':' . stripslashes( $value ) . ',';
						}
						?>
					} );
					<?php echo esc_js( $compat ); ?>
					<?php if ( apply_filters( 'monsterinsights_tracking_gtag_frontend_gatracker_compatibility', true ) ) { ?>
					(
						function () {
							/* https://developers.google.com/analytics/devguides/collection/analyticsjs/ */
							/* ga and __gaTracker compatibility shim. */
							var noopfn = function () {
								return null;
							};
							var noopnullfn = function () {
								return null;
							};
							var Tracker = function () {
								return null;
							};
							var p = Tracker.prototype;
							p.get = noopfn;
							p.set = noopfn;
							p.send = noopfn;
							var __gaTracker = function () {
								var len = arguments.length;
								if ( len === 0 ) {
									return;
								}
								var f = arguments[len - 1];
								if ( typeof f !== 'object' || f === null || typeof f.hitCallback !== 'function' ) {
									if ( 'send' === arguments[0] ) {
										if ( 'event' === arguments[1] ) {
											__gtagTracker( 'event', arguments[3], {
												'event_category': arguments[2],
												'event_label': arguments[4],
												'value': 1
											} );
											return;
										}
										if ( 'undefined' !== typeof ( arguments[1].hitType ) ) {
											var hitDetails = {};
											var gagtag_map = {
												'eventCategory': 'event_category',
												'eventAction': 'event_action',
												'eventLabel': 'event_label',
												'eventValue': 'event_value',
												'nonInteraction': 'non_interaction',
												'timingCategory': 'event_category',
												'timingVar': 'name',
												'timingValue': 'value',
												'timingLabel': 'event_label',
											};
											var gaKey;
											for ( gaKey in gagtag_map ) {
												if ( 'undefined' !== typeof arguments[1][gaKey] ) {
													hitDetails[gagtag_map[gaKey]] = arguments[1][gaKey];
												}
											}
											var action = 'timing' === arguments[1].hitType ? 'timing_complete' : arguments[1].eventAction;
											__gtagTracker( 'event', action, hitDetails );
										}
									}
									return;
								}
								try {
									f.hitCallback();
								} catch ( ex ) {
								}
							};
							__gaTracker.create = function () {
								return new Tracker();
							};
							__gaTracker.getByName = noopnullfn;
							__gaTracker.getAll = function () {
								return [];
							};
							__gaTracker.remove = noopfn;
							__gaTracker.loaded = true;
							window['__gaTracker'] = __gaTracker;
						}
					)();
					<?php } ?>
				} else {
					<?php if ( $this->should_do_optout() ) { ?>
					console.log( "<?php echo esc_js( $reason );?>" );
					( function () {
						function __gtagTracker() {
							return null;
						}
						window['__gtagTracker'] = __gtagTracker;
						window['gtag'] = __gtagTracker;
					} )();
					<?php } ?>
				}
			</script>
		<?php } else { ?>
			<!-- No UA code set -->
		<?php } ?>
		<!-- / Google Analytics by MonsterInsights -->
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	public function should_do_optout() {
		return ! ( defined( 'MI_NO_TRACKING_OPTOUT' ) && MI_NO_TRACKING_OPTOUT );
	}
}
