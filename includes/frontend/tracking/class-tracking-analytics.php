<?php
/**
 * Tracking analytics.js class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MonsterInsights_Tracking_Analytics extends MonsterInsights_Tracking_Abstract {

	/**
	 * Holds the name of the tracking type.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $name Name of the tracking type.
	 */
	public $name = 'analytics';

	/**
	 * Version of the tracking class.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $version Version of the tracking class.
	 */
	public $version = '1.0.0';

	/**
	 * Primary class constructor.
	 *
	 * @since 6.0.0
	 * @access public
	 */
	public function __construct() {

	}

	/**
	 * Get frontend tracking options.
	 *
	 * This function is used to return an array of parameters
	 * for the frontend_output() function to output. These are
	 * generally dimensions and turned on GA features.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @return array Array of the options to use.
	 */
	public function frontend_tracking_options( ) {
		global $wp_query;
		$options = array();

		$ua_code = monsterinsights_get_ua_to_output();
		if ( empty( $ua_code ) ) {
			return $options;
		}

		$track_user = monsterinsights_track_user();

		if ( ! $track_user ) {
			$options['create'] = "'create', '" . esc_js( $ua_code ) . "', '" . esc_js( 'auto' ) . "'";
			$options['forceSSL'] = "'set', 'forceSSL', true";
			$options['send'] = "'send','pageview'";
			return $options;
		}

		$domain = esc_attr( monsterinsights_get_option( 'subdomain_tracking', 'auto' ) );

		$cross_domains = monsterinsights_get_option( 'cross_domains', array() );
		$allow_anchor  = monsterinsights_get_option( 'allow_anchor', false );


		$create = array();
		if ( $allow_anchor ) {
			$create['allowAnchor'] = true;
		}

		if ( is_array( $cross_domains ) && ! empty( $cross_domains ) ) {
			$create['allowLinker'] = true;
		}

		if ( class_exists( 'MonsterInsights_AMP' ) ) {
			$create['useAmpClientId'] = true;
		}

		$create = apply_filters( 'monsterinsights_frontend_tracking_options_analytics_create', $create );

		if ( $create && ! empty( $create ) && is_array( $create ) ) {
			$create = json_encode( $create );
			$create = str_replace( '"', "'",  $create );
			$options['create'] = "'create', '" . esc_js( $ua_code ). "', '" . esc_js( $domain ) . "', " . $create;
		} else {
			$options['create'] = "'create', '" . esc_js( $ua_code ) . "', '" . esc_js( $domain ) . "'";
		}

		$options['forceSSL'] = "'set', 'forceSSL', true";

		$code = monsterinsights_get_option( 'custom_code', false );
		if ( ! empty( $code ) ) {
			// Add custom code to the view
			$options['custom_code'] = array(
				'type'  => 'custom_code',
				'value' => stripslashes( $code ),
			);
		}

		// Anonymous data
		if ( monsterinsights_get_option( 'anonymize_ips', false ) ) {
			$options['anonymize_ips'] = "'set', 'anonymizeIp', true";
		}

		$options = apply_filters( 'monsterinsights_frontend_tracking_options_analytics_before_scripts', $options );

		// add demographics
		if ( monsterinsights_get_option( 'demographics', false ) ) {
			$options['demographics'] = "'require', 'displayfeatures'";
		}

		// Add Enhanced link attribution.
		if ( monsterinsights_get_option( 'link_attribution', false ) ) {
			$options['enhanced_link_attribution'] = "'require', 'linkid', 'linkid.js'";
		}

		// Add cross-domain tracking.
		if ( is_array( $cross_domains ) && ! empty( $cross_domains ) ) {
			$options['cross_domain_tracking'] = "'require', 'linker'";
			$cross_domains_strings = array();
			foreach ( $cross_domains as $cross_domain ) {
				if ( ! isset( $cross_domain['domain'] ) ) {
					continue;
				}
				$cross_domains_strings[] = '\'' . $cross_domain['domain'] . '\'';
			}
			if ( ! empty( $cross_domains_strings ) ) {
				$cross_domains_strings    = implode( ',', $cross_domains_strings );
				$options['cross_domains'] = "'linker:autoLink', [$cross_domains_strings]";
			}
		}

		$options = apply_filters( 'monsterinsights_frontend_tracking_options_analytics_before_pageview', $options );
		$options = apply_filters( 'monsterinsights_frontend_tracking_options_before_pageview', $options, $this->name, $this->version );

		if ( is_404() ) {
			if ( monsterinsights_get_option( 'hash_tracking', false ) ) {
				$options['send'] = "'send','pageview','/404.html?page=' + document.location.pathname + document.location.search + location.hash + '&from=' + document.referrer";
			} else {
				$options['send'] = "'send','pageview','/404.html?page=' + document.location.pathname + document.location.search + '&from=' + document.referrer";
			}
		} else if ( $wp_query->is_search ) {
			$pushstr = "'send','pageview','/?s=";
			if ( (int) $wp_query->found_posts === 0 ) {
				$options['send'] = $pushstr . 'no-results:' . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=no-results'";
			} else if ( (int) $wp_query->found_posts === 1 ) {
				$options['send'] = $pushstr . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=1-result'";
			} else if ( $wp_query->found_posts > 1 && $wp_query->found_posts < 6 ) {
				$options['send'] = $pushstr . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=2-5-results'";
			} else {
				$options['send'] = $pushstr . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=plus-5-results'";
			}
		} else if ( monsterinsights_get_option( 'hash_tracking', false ) ) {
			$options['send'] = "'send','pageview', location.pathname + location.search + location.hash";
		} else {
			$options['send'] = "'send','pageview'";
		}

		$options = apply_filters( 'monsterinsights_frontend_tracking_options_analytics_end', $options );
		return $options;
	}

	/**
	 * Get frontend output.
	 *
	 * This function is used to return the Javascript
	 * to output in the head of the page for the given
	 * tracking method.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @return string Javascript to output.
	 */
	public function frontend_output( ) {
		$options        = $this->frontend_tracking_options();
		$src     	    = apply_filters( 'monsterinsights_frontend_output_analytics_src', '//www.google-analytics.com/analytics.js' );
		$compat_mode     = monsterinsights_get_option( 'gatracker_compatibility_mode', false );
		$compat    	 	= $compat_mode ? 'window.ga = __gaTracker;' : '';
		$track_user 	= monsterinsights_track_user();
		$ua         	= monsterinsights_get_ua();
		$output     	= '';
		$reason     	= '';
		$attributes     = apply_filters( 'monsterinsights_tracking_analytics_script_attributes', array( 'type' => "text/javascript", 'data-cfasync' => 'false'  ) );
		$attr_string    = '';
		if ( ! empty( $attributes ) ) {
			foreach( $attributes as $attr_name => $attr_value ) {
	 			if ( ! empty( $attr_name ) ) {
	 				$attr_string .= ' ' . sanitize_key( $attr_name ) . '="' . esc_attr( $attr_value ) . '"';
	 			} else {
	 				$attr_string .= ' ' . sanitize_key( $attr_value );
	 			}
			}
		}
		ob_start();
		?>
<!-- This site uses the Google Analytics by MonsterInsights plugin v<?php echo MONSTERINSIGHTS_VERSION; ?> - Using Analytics tracking - https://www.monsterinsights.com/ -->
<?php if ( ! $track_user ) {
	if ( empty( $ua ) ) {
		$reason = __( 'Note: MonsterInsights is not currently configured on this site. The site owner needs to authenticate with Google Analytics in the MonsterInsights settings panel.', 'google-analytics-for-wordpress' );
	    $output .=  '<!-- ' . esc_html( $reason ) . ' -->' . PHP_EOL;
	} else if ( current_user_can( 'monsterinsights_save_settings' ) ) {
		$reason = __( 'Note: MonsterInsights does not track you as a logged-in site administrator to prevent site owners from accidentally skewing their own Google Analytics data.'. PHP_EOL . 'If you are testing Google Analytics code, please do so either logged out or in the private browsing/incognito mode of your web browser.', 'google-analytics-for-wordpress' );
	    $output .=  '<!-- ' . esc_html( $reason ) . ' -->' . PHP_EOL;
	} else {
		$reason = __( 'Note: The site owner has disabled Google Analytics tracking for your user role.', 'google-analytics-for-wordpress' );
	    $output .=  '<!-- ' . esc_html( $reason ) . ' -->' . PHP_EOL;
	}
	echo $output;
} ?>
<?php if ( $ua ) { ?>
<script<?php echo $attr_string;?>>
	var mi_version         = '<?php echo MONSTERINSIGHTS_VERSION; ?>';
	var mi_track_user      = <?php echo ( $track_user ? 'true' : 'false' ); ?>;
	var mi_no_track_reason = <?php echo ( $reason ? "'" . esc_js( $reason)  . "'": "''" ); ?>;
	<?php do_action( 'monsterinsights_tracking_analytics_frontend_output_after_mi_track_user' ); ?>

<?php if ( $this->should_do_optout() ) { ?>
	var disableStr = 'ga-disable-<?php echo monsterinsights_get_ua(); ?>';

	/* Function to detect opted out users */
	function __gaTrackerIsOptedOut() {
		return document.cookie.indexOf(disableStr + '=true') > -1;
	}

	/* Disable tracking if the opt-out cookie exists. */
	if ( __gaTrackerIsOptedOut() ) {
		window[disableStr] = true;
	}

	/* Opt-out function */
	function __gaTrackerOptout() {
	  document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
	  window[disableStr] = true;
	}

	if ( 'undefined' === typeof gaOptout ) {
		function gaOptout() {
			__gaTrackerOptout();
		}
	}
	<?php } ?>

	if ( mi_track_user ) {
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','<?php echo $src; ?>','__gaTracker');

<?php
	echo $compat;

	if ( count( $options ) >= 1 ) {
		foreach ( $options as $item ) {
			if ( ! is_array( $item ) ) {
				echo '		__gaTracker(' . $item . ");\n";
			} else if ( ! empty ( $item['value'] ) ) {
				echo '	' . $item['value'] . "\n";
			}
		}
	}
	if ( $compat_mode ) {
		// Ensure that GA is fully loaded and assign to ga.
		echo "		__gaTracker( function() { window.ga = __gaTracker; } );\n";
	}
	?>
	} else {
<?php if ( $this->should_do_optout() ) { ?>
		console.log( "<?php echo esc_js( $reason );?>" );
		(function() {
			/* https://developers.google.com/analytics/devguides/collection/analyticsjs/ */
			var noopfn = function() {
				return null;
			};
			var noopnullfn = function() {
				return null;
			};
			var Tracker = function() {
				return null;
			};
			var p = Tracker.prototype;
			p.get = noopfn;
			p.set = noopfn;
			p.send = noopfn;
			var __gaTracker = function() {
				var len = arguments.length;
				if ( len === 0 ) {
					return;
				}
				var f = arguments[len-1];
				if ( typeof f !== 'object' || f === null || typeof f.hitCallback !== 'function' ) {
					console.log( '<?php echo esc_js( __("Not running function", "google-analytics-for-wordpress" ) );?> __gaTracker(' + arguments[0] + " ....) <?php echo esc_js( __( "because you are not being tracked.", 'google-analytics-for-wordpress' ) );?> " + mi_no_track_reason );
					return;
				}
				try {
					f.hitCallback();
				} catch (ex) {

				}
			};
			__gaTracker.create = function() {
				return new Tracker();
			};
			__gaTracker.getByName = noopnullfn;
			__gaTracker.getAll = function() {
				return [];
			};
			__gaTracker.remove = noopfn;
			window['__gaTracker'] = __gaTracker;
			<?php echo $compat; ?>
		})();
	<?php } ?>
	}
</script>
<?php } else {  ?>
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
