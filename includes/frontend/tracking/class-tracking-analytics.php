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
	 * Holds the base class object.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var object $base Base class object.
	 */
	public $base;
	
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
		$this->base = MonsterInsights();
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

		if ( monsterinsights_get_ua_to_output() ) {
			$ua_code = monsterinsights_get_ua_to_output();
		} else {
			return $options;
		}

		$domain = 'auto'; // Default domain value
		if ( monsterinsights_get_option( 'subdomain_tracking', false ) ) {
			$domain = esc_attr( monsterinsights_get_option( 'subdomain_tracking', '' ) );
		}

		$allow_linker = monsterinsights_get_option( 'add_allow_linker', false );
		$allow_anchor = monsterinsights_get_option( 'allow_anchor', false );


		$create = array();
		if ( $allow_anchor ) {
			$create['allowAnchor'] = true;
		}

		if ( $allow_linker ) {
			$create['allowLinker'] = true;
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

		if ( monsterinsights_get_option( 'custom_code', false ) ) {
			// Add custom code to the view
			$options['custom_code'] = array(
				'type'  => 'custom_code',
				'value' => stripslashes( monsterinsights_get_option( 'custom_code', '' ) ),
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

		// Check for Enhanced link attribution
		if ( monsterinsights_get_option( 'enhanced_link_attribution', false ) ) {
			$options['enhanced_link_attribution'] = "'require', 'linkid', 'linkid.js'";
		}

		$options = apply_filters( 'monsterinsights_frontend_tracking_options_analytics_before_pageview', $options );
		$options = apply_filters( 'monsterinsights_frontend_tracking_options_before_pageview', $options, $this->name, $this->version );

		if ( is_404() ) {
			$options['send'] = "'send','pageview','/404.html?page=' + document.location.pathname + document.location.search + '&from=' + document.referrer";
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
		$is_debug_mode  =  monsterinsights_is_debug_mode();
		$src     	    = apply_filters( 'monsterinsights_frontend_output_analytics_src', '//www.google-analytics.com/analytics.js' );
		if ( current_user_can( 'manage_options' ) && $is_debug_mode ) {
			$src     = apply_filters( 'monsterinsights_frontend_output_analytics_src', '//www.google-analytics.com/analytics_debug.js' );
		}
		$compat  = monsterinsights_get_option( 'gatracker_compatibility_mode', false );
		$compat  = $compat ? 'window.ga = __gaTracker;' : '';
		ob_start();
		?>
<!-- This site uses the Google Analytics by MonsterInsights plugin v<?php echo MONSTERINSIGHTS_VERSION; ?> - Using Analytics tracking - https://www.monsterinsights.com/ -->
<?php if ( monsterinsights_get_ua() ) { ?>
<script type="text/javascript" data-cfasync="false">
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','<?php echo $src; ?>','__gaTracker');

<?php
if ( current_user_can( 'manage_options' ) && $is_debug_mode ) {
	echo 'window.ga_debug = {trace: true};';
}
echo $compat;
if ( count( $options ) >= 1 ) {
	foreach ( $options as $item ) {
		if ( ! is_array( $item ) ) {
			echo '	__gaTracker(' . $item . ");\n";
		} else if ( ! empty ( $item['value'] ) ) {
			echo '	' . $item['value'] . "\n";
		}
	}
}
?>
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
}
