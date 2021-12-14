<?php
/**
 * Tracking gtag.js class.
 *
 * @since 7.15.0
 *
 * @author  Mircea Sandu
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class MonsterInsights_Tracking_Gtag extends MonsterInsights_Tracking_Abstract
{
    /**
     * Holds the name of the tracking type.
     *
     * @since 7.15.0
     *
     * @var string name of the tracking type
     */
    public $name = 'gtag';

    /**
     * Version of the tracking class.
     *
     * @since 7.15.0
     *
     * @var string version of the tracking class
     */
    public $version = '1.0.0';

    /**
     * Primary class constructor.
     *
     * @since 7.15.0
     */
    public function __construct()
    {
    }

    /**
     * Array of options that will be made persistent by setting them before the pageview.
     *
     * @see https://developers.google.com/analytics/devguides/collection/gtagjs/setting-values
     *
     * @return array options for persistent values, like custom dimensions
     *
     * @since 7.15.0
     */
    public function frontend_tracking_options_persistent()
    {
        return apply_filters('monsterinsights_frontend_tracking_options_persistent_gtag_before_pageview', []);
    }

    /**
     * Get frontend tracking options for the gtag script.
     *
     * This function is used to return an array of parameters
     * for the frontend_output() function to output. These are
     * generally dimensions and turned on GA features.
     *
     * @param bool  $encoded Whether to return a JavaScript object representation of the options
     * @param mixed $type
     *
     * @return array|string options for the gtag config
     *
     * @since 7.15.0
     */
    public function frontend_tracking_options($type = 'ua', $encoded = false)
    {
        global $wp_query;
        $options = [];

        $tracking_ids = monsterinsights_get_tracking_ids();
        if (empty($tracking_ids)) {
            return $encoded ? wp_json_encode($options) : $options;
        }

        $placeholder = '';

        if ($encoded) {
            $placeholder = '!@#';
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

        $cross_domains = monsterinsights_get_option('cross_domains', []);
        $allow_anchor = monsterinsights_get_option('allow_anchor', false);

        if ($allow_anchor) {
            $options['allow_anchor'] = 'true';
        }

        if (class_exists('MonsterInsights_AMP')) {
            $options['use_amp_client_id'] = 'true';
        }

        $options['forceSSL'] = 'true';

        // Anonymous data.
        if (monsterinsights_get_option('anonymize_ips', false)) {
            $options['anonymize_ip'] = 'true';
        }

        $options = apply_filters('monsterinsights_frontend_tracking_options_gtag_before_scripts', $options);

        // Add Enhanced link attribution.
        if (monsterinsights_get_option('link_attribution', false)) {
            $options['link_attribution'] = 'true';
        }

        // Add cross-domain tracking.
        if (is_array($cross_domains) && !empty($cross_domains)) {
            $linker_domains = [];
            foreach ($cross_domains as $cross_domain) {
                if (!empty($cross_domain['domain'])) {
                    $linker_domains[] = $cross_domain['domain'];
                }
            }
            $options['linker'] = [
                'domains' => $linker_domains,
            ];
        }

        if (monsterinsights_is_debug_mode()) {
            $options['debug_mode'] = true;
        }

        $options = apply_filters('monsterinsights_frontend_tracking_options_gtag_before_pageview', $options, $type);
        $options = apply_filters('monsterinsights_frontend_tracking_options_before_pageview', $options, $this->name, $this->version, $type);

        if (is_404()) {
            if (monsterinsights_get_option('hash_tracking', false)) {
                $options['page_path'] = "{$placeholder}'/404.html?page=' + document.location.pathname + document.location.search + location.hash + '&from=' + document.referrer{$placeholder}";
            } else {
                $options['page_path'] = "{$placeholder}'/404.html?page=' + document.location.pathname + document.location.search + '&from=' + document.referrer{$placeholder}";
            }
        } elseif ($wp_query->is_search) {
            $pushstr = "'/?s=";
            if (0 === (int) $wp_query->found_posts) {
                $options['page_path'] = $pushstr.'no-results:'.rawurlencode($wp_query->query_vars['s'])."&cat=no-results'";
            } elseif (1 === (int) $wp_query->found_posts) {
                $options['page_path'] = $pushstr.rawurlencode($wp_query->query_vars['s'])."&cat=1-result'";
            } elseif ($wp_query->found_posts > 1 && $wp_query->found_posts < 6) {
                $options['page_path'] = $pushstr.rawurlencode($wp_query->query_vars['s'])."&cat=2-5-results'";
            } else {
                $options['page_path'] = $pushstr.rawurlencode($wp_query->query_vars['s'])."&cat=plus-5-results'";
            }
        } elseif (monsterinsights_get_option('hash_tracking', false)) {
            $options['page_path'] = "{$placeholder}location.pathname + location.search + location.hash{$placeholder}";
        }

        $options = apply_filters('monsterinsights_frontend_tracking_options_gtag_end', $options, $type);

        if ($encoded) {
            return str_replace(
                ['"'.$placeholder, $placeholder.'"'],
                '',
                wp_json_encode($options)
            );
        }

        return $options;
    }

    /**
     * Get frontend output.
     *
     * This function is used to return the Javascript
     * to output in the head of the page for the given
     * tracking method.
     *
     * @return string javascript to output
     *
     * @since 7.15.0
     */
    public function frontend_output()
    {
        $options = $this->frontend_tracking_options('ua', true);
        $options_v4 = $this->frontend_tracking_options('v4', true);
        $persistent = $this->frontend_tracking_options_persistent();
        $connected_type = MonsterInsights()->auth->get_connected_type();
        $v4_id = monsterinsights_get_v4_id_to_output();
        $ua = monsterinsights_get_ua_to_output();
        $main_id = 'ua' === $connected_type ? $ua : $v4_id;
        $src = apply_filters('monsterinsights_frontend_output_gtag_src', '//www.googletagmanager.com/gtag/js?id='.$main_id);
        $compat_mode = apply_filters('monsterinsights_get_option_gtagtracker_compatibility_mode', true);
        $compat = $compat_mode ? 'window.gtag = __gtagTracker;' : '';
        $track_user = monsterinsights_track_user();
        $output = '';
        $reason = '';
        $attr_string = monsterinsights_get_frontend_analytics_script_atts();
        $gtag_async = apply_filters('monsterinsights_frontend_gtag_script_async', true) ? 'async' : '';
        ob_start(); ?>
		<!-- This site uses the Google Analytics by MonsterInsights plugin v<?php echo MONSTERINSIGHTS_VERSION; ?> - Using Analytics tracking - https://www.monsterinsights.com/ -->
		<?php if (!$track_user) {
            if (empty($v4_id) && empty($ua)) {
                $reason = __('Note: MonsterInsights is not currently configured on this site. The site owner needs to authenticate with Google Analytics in the MonsterInsights settings panel.', 'google-analytics-for-wordpress');
                $output .= '<!-- '.esc_html($reason).' -->'.PHP_EOL;
            } elseif (current_user_can('monsterinsights_save_settings')) {
                $reason = __('Note: MonsterInsights does not track you as a logged-in site administrator to prevent site owners from accidentally skewing their own Google Analytics data.'.PHP_EOL.'If you are testing Google Analytics code, please do so either logged out or in the private browsing/incognito mode of your web browser.', 'google-analytics-for-wordpress');
                $output .= '<!-- '.esc_html($reason).' -->'.PHP_EOL;
            } else {
                $reason = __('Note: The site owner has disabled Google Analytics tracking for your user role.', 'google-analytics-for-wordpress');
                $output .= '<!-- '.esc_html($reason).' -->'.PHP_EOL;
            }
            echo $output;
        } ?>
		<?php if (!empty($v4_id) || !empty($ua)) { ?>
			<script src="<?php echo esc_attr($src); ?>" <?php echo $attr_string; ?> <?php echo esc_attr($gtag_async); ?>></script>
			<script<?php echo $attr_string; ?>>
				var mi_version = '<?php echo MONSTERINSIGHTS_VERSION; ?>';
				var mi_track_user = <?php echo $track_user ? 'true' : 'false'; ?>;
				var mi_no_track_reason = <?php echo $reason ? "'".esc_js($reason)."'" : "''"; ?>;
				<?php do_action('monsterinsights_tracking_gtag_frontend_output_after_mi_track_user'); ?>

				<?php if ($this->should_do_optout()) { ?>
				var disableStrs = [
					<?php if (!empty($v4_id)) { ?>
					'ga-disable-<?php echo esc_js($v4_id); ?>',
					<?php } ?>
					<?php if (!empty($ua)) { ?>
					'ga-disable-<?php echo esc_js($ua); ?>',
					<?php } ?>
				];

				/* Function to detect opted out users */
				function __gtagTrackerIsOptedOut() {
					for ( var index = 0; index < disableStrs.length; index++ ) {
						if ( document.cookie.indexOf( disableStrs[ index ] + '=true' ) > -1 ) {
							return true;
						}
					}

					return false;
				}

				/* Disable tracking if the opt-out cookie exists. */
				if ( __gtagTrackerIsOptedOut() ) {
					for ( var index = 0; index < disableStrs.length; index++ ) {
						window[ disableStrs[ index ] ] = true;
					}
				}

				/* Opt-out function */
				function __gtagTrackerOptout() {
					for ( var index = 0; index < disableStrs.length; index++ ) {
						document.cookie = disableStrs[ index ] + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
						window[ disableStrs[ index ] ] = true;
					}
				}

				if ( 'undefined' === typeof gaOptout ) {
					function gaOptout() {
						__gtagTrackerOptout();
					}
				}
				<?php } ?>
				window.dataLayer = window.dataLayer || [];

				window.MonsterInsightsDualTracker = {
					helpers: {},
					trackers: {},
				};
				if ( mi_track_user ) {
					function __gtagDataLayer() {
						dataLayer.push( arguments );
					}

					function __gtagTracker( type, name, parameters ) {
						if (!parameters) {
							parameters = {};
						}

						if (parameters.send_to) {
							__gtagDataLayer.apply( null, arguments );
							return;
						}

						if ( type === 'event' ) {
							<?php if ($v4_id) { ?>
								parameters.send_to = monsterinsights_frontend.v4_id;
								var hookName = name;
								if ( typeof parameters[ 'event_category' ] !== 'undefined' ) {
									hookName = parameters[ 'event_category' ] + ':' + name;
								}

								if ( typeof MonsterInsightsDualTracker.trackers[ hookName ] !== 'undefined' ) {
									MonsterInsightsDualTracker.trackers[ hookName ]( parameters );
								} else {
									__gtagDataLayer( 'event', name, parameters );
								}
							<?php } ?>

							<?php if ($ua) { ?>
								parameters.send_to = monsterinsights_frontend.ua;
								__gtagDataLayer( type, name, parameters );
							<?php } ?>
						} else {
							__gtagDataLayer.apply( null, arguments );
						}
					}
					__gtagTracker( 'js', new Date() );
					__gtagTracker( 'set', {
						'developer_id.dZGIzZG' : true,
						<?php
                        if (!empty($persistent)) {
                            foreach ($persistent as $key => $value) {
                                echo "'".esc_js($key)."' : '".esc_js($value)."',";
                            }
                        }
                        ?>
					} );
					<?php if (!empty($v4_id)) { ?>
					__gtagTracker( 'config', '<?php echo esc_js($v4_id); ?>', <?php echo $options_v4; ?> );
					<?php } ?>
					<?php if (!empty($ua)) { ?>
					__gtagTracker( 'config', '<?php echo esc_js($ua); ?>', <?php echo $options; ?> );
					<?php
                    }
                    /*
                     * Extend or enhance the functionality by adding custom code to frontend
                     * tracking via this hook.
                     *
                     * @since 7.15.0
                     */
                    do_action('monsterinsights_frontend_tracking_gtag_after_pageview');
                    ?>
					<?php echo esc_js($compat); ?>
					<?php if (apply_filters('monsterinsights_tracking_gtag_frontend_gatracker_compatibility', true)) { ?>
						(function () {
							/* https://developers.google.com/analytics/devguides/collection/analyticsjs/ */
							/* ga and __gaTracker compatibility shim. */
							var noopfn = function () {
								return null;
							};
							var newtracker = function () {
								return new Tracker();
							};
							var Tracker = function () {
								return null;
							};
							var p = Tracker.prototype;
							p.get = noopfn;
							p.set = noopfn;
							p.send = function (){
								var args = Array.prototype.slice.call(arguments);
								args.unshift( 'send' );
								__gaTracker.apply(null, args);
							};
							var __gaTracker = function () {
								var len = arguments.length;
								if ( len === 0 ) {
									return;
								}
								var f = arguments[len - 1];
								if ( typeof f !== 'object' || f === null || typeof f.hitCallback !== 'function' ) {
									if ( 'send' === arguments[0] ) {
										var hitConverted, hitObject = false, action;
										if ( 'event' === arguments[1] ) {
											if ( 'undefined' !== typeof arguments[3] ) {
												hitObject = {
													'eventAction': arguments[3],
													'eventCategory': arguments[2],
													'eventLabel': arguments[4],
													'value': arguments[5] ? arguments[5] : 1,
												}
											}
										}
										if ( 'pageview' === arguments[1] ) {
											if ( 'undefined' !== typeof arguments[2] ) {
												hitObject = {
													'eventAction': 'page_view',
													'page_path' : arguments[2],
												}
											}
										}
										if ( typeof arguments[2] === 'object' ) {
											hitObject = arguments[2];
										}
										if ( typeof arguments[5] === 'object' ) {
											Object.assign( hitObject, arguments[5] );
										}
										if ( 'undefined' !== typeof arguments[1].hitType ) {
											hitObject = arguments[1];
											if ( 'pageview' === hitObject.hitType ) {
												hitObject.eventAction = 'page_view';
											}
										}
										if ( hitObject ) {
											action = 'timing' === arguments[1].hitType ? 'timing_complete' : hitObject.eventAction;
											hitConverted = mapArgs( hitObject );
											__gtagTracker( 'event', action, hitConverted );
										}
									}
									return;
								}

								function mapArgs( args ) {
									var arg, hit = {};
									var gaMap = {
										'eventCategory': 'event_category',
										'eventAction': 'event_action',
										'eventLabel': 'event_label',
										'eventValue': 'event_value',
										'nonInteraction': 'non_interaction',
										'timingCategory': 'event_category',
										'timingVar': 'name',
										'timingValue': 'value',
										'timingLabel': 'event_label',
										'page' : 'page_path',
										'location' : 'page_location',
										'title' : 'page_title',
									};
									for ( arg in args ) {
										<?php // Note: we do || instead of && because FBIA can't encode && properly.?>
										if ( ! ( ! args.hasOwnProperty(arg) || ! gaMap.hasOwnProperty(arg) ) ) {
											hit[gaMap[arg]] = args[arg];
										} else {
											hit[arg] = args[arg];
										}
									}
									return hit;
								}

								try {
									f.hitCallback();
								} catch ( ex ) {
								}
							};
							__gaTracker.create = newtracker;
							__gaTracker.getByName = newtracker;
							__gaTracker.getAll = function () {
								return [];
							};
							__gaTracker.remove = noopfn;
							__gaTracker.loaded = true;
							window['__gaTracker'] = __gaTracker;
						})();
					<?php } ?>
				} else {
					<?php if ($this->should_do_optout()) { ?>
					console.log( "<?php echo esc_js($reason); ?>" );
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

    public function should_do_optout()
    {
        return !(defined('MI_NO_TRACKING_OPTOUT') && MI_NO_TRACKING_OPTOUT);
    }
}
