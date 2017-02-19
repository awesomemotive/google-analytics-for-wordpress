<?php
/**
 * Tracking ga.js class.
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

class MonsterInsights_Tracking_GA extends MonsterInsights_Tracking_Abstract {

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
	public $name = 'ga';

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
			$options['_setAccount'] = "'_setAccount', '" . monsterinsights_get_ua_to_output() . "'";
		} else {
			return $options;
		}

		if ( monsterinsights_get_option( 'subdomain_tracking', false ) ) {
			$options['_setDomainName'] = "'_setDomainName', '" . esc_js( monsterinsights_get_option( 'subdomain_tracking', '' ) ) . "'";
		}

		if ( monsterinsights_get_option( 'allow_anchor', false ) ) {
			$options['_setAllowAnchor'] = "'_setAllowAnchor', true";
		}

		if ( monsterinsights_get_option( 'add_allow_linker', false ) ) {
			$options['_setAllowLinker'] = "'_setAllowLinker', true";
		}

		// SSL data
		$options['_forceSSL'] = "'_gat._forceSSL'";

		if ( monsterinsights_get_option( 'custom_code', false ) ) {
			// Add custom code to the view
			$options['custom_code'] = array(
				'type'  => 'custom_code',
				'value' => esc_js( stripslashes( monsterinsights_get_option( 'custom_code', '' ) ) ),
			);
		}

		// Anonymous data
		if ( monsterinsights_get_option( 'anonymize_ips', false ) && ! monsterinsights_get_option( 'allowhash', false ) ) {
			$options['anonymize_ips'] = "'_gat._anonymizeIp'";
		}

		if ( monsterinsights_get_option( 'allowhash', false ) ) {
			$options['allowhash'] = "'_gat._anonymizeIp',true";
		}

		$options = apply_filters( 'monsterinsights_frontend_tracking_options_ga_before_pageview', $options );
		$options = apply_filters( 'monsterinsights_frontend_tracking_options_before_pageview', $options, $this->name, $this->version );

		if ( is_404() ) {
			$options['_trackPageview'] = "'_trackPageview','/404.html?page=' + document.location.pathname + document.location.search + '&from=' + document.referrer";
		} else if ( $wp_query->is_search ) {
			$pushstr = "'_trackPageview','/?s=";
			if ( (int) $wp_query->found_posts === 0 ) {
				$options['_trackPageview'] = $pushstr . 'no-results:' . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=no-results'";
			} else if ( (int) $wp_query->found_posts === 1 ) {
				$options['_trackPageview'] = $pushstr . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=1-result'";
			} else if ( $wp_query->found_posts > 1 && $wp_query->found_posts < 6 ) {
				$options['_trackPageview'] = $pushstr . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=2-5-results'";
			} else {
				$options['_trackPageview'] = $pushstr . rawurlencode( $wp_query->query_vars['s'] ) . "&cat=plus-5-results'";
			}
		} else {
			$options['_trackPageview'] = "'_trackPageview'";
		}

		$options = apply_filters( 'monsterinsights_frontend_tracking_options_ga_end', $options );
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
		 $options = $this->frontend_tracking_options();
		 $src     = apply_filters( 'monsterinsights_frontend_output_ga_src', "('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';\n" );
		 ob_start();
		 ?>
 <!-- This site uses the Google Analytics by MonsterInsights plugin v<?php echo MONSTERINSIGHTS_VERSION; ?> - Using ga.js tracking - https://www.monsterinsights.com/ -->
<?php if ( monsterinsights_get_ua() ) { ?>
<script type="text/javascript" data-cfasync="false">

		var _gaq = _gaq || [];
	<?php
	if ( count( $options ) >= 1 ) {
		foreach ( $options as $item ) {
			if ( ! is_array( $item ) ) {
				echo '  _gaq.push([' . $item . "]);\n";
			} else if ( isset( $item['value'] ) ) {
				echo '  '.$item['value'] . "\n";
			}
		}
	}
	?>

		(function () {
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = <?php echo $src;?>
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		})();
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
