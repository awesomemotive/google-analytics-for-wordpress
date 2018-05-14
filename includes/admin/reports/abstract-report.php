<?php
/**
 * Report Abstract
 *
 * Ensures all of the reports have a uniform class with helper functions.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Reports
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MonsterInsights_Report {

	public $title;
	public $class;
	public $name;
	public $version = '1.0.0';

	/**
	 * Primary class constructor.
	 *
	 * @access public
	 * @since 6.0.0
	 */
	public function __construct() {
		add_filter( 'monsterinsights_reports_abstract_get_data_pre_cache', array( $this, 'requirements' ), 10 , 3 );
	}

	// Let's get the HTML to output for a particular report. This is not the AJAX endpoint. Args can hold things (generally start/end date range)
	protected function get_report_html( $args = array() ) { 
		/* Defined in the report class */ 
		// For ajax, args start, end, and data will be set with the data to use. Else call $this->get_data( array( 'default' => true ) )
		return ''; 
	}

	public function additional_data(){
		return array();
	}

	public function requirements( $error = false, $args = array(), $name = '' ) {
		return $error;
	}

	public function show_report( $args = array() ) {

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			return monsterinsights_get_message( 'error', esc_html__( 'Access denied' , 'google-analytics-for-wordpress' ) );
		}

		if ( monsterinsights_get_option( 'dashboard_disabled', false ) ) { 
			if ( current_user_can( 'monsterinsights_save_settings' ) ) {
				$url = is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_settings' ) : admin_url( 'admin.php?page=monsterinsights_settings' );
				return monsterinsights_get_message( 'error',
					sprintf(
						 esc_html__( 'Please %1$senable the dashboard%2$s to see report data.', 'google-analytics-for-wordpress' ),
							'<a href="' . $url . '">',
							'</a>'
						)
				);
			} else {
				return monsterinsights_get_message( 'error', esc_html__( 'The dashboard is disabled.', 'google-analytics-for-wordpress' ) );
			}
		}

		if ( monsterinsights_is_pro_version() ){
			if ( ! MonsterInsights()->license->has_license() ) {
				$url = is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_settings' ) : admin_url( 'admin.php?page=monsterinsights_settings' );
				return monsterinsights_get_message( 'error', esc_html__( 'You do not have an active license. Please %1$scheck your license configuration.%2$s', 'google-analytics-for-wordpress' ),'<a href="' . $url . '">','</a>' );
			} else if ( MonsterInsights()->license->license_has_error() ) {
				return monsterinsights_get_message( 'error', $this->get_license_error() );
			}
		}

		if ( ! ( MonsterInsights()->auth->is_authed() || MonsterInsights()->auth->is_network_authed() ) ) {
			if ( current_user_can( 'monsterinsights_save_settings' ) ) {
				$url = is_network_admin() ? network_admin_url( 'admin.php?page=monsterinsights_settings' ) : admin_url( 'admin.php?page=monsterinsights_settings' );
				return monsterinsights_get_message( 'error',
					sprintf(
						esc_html__( 'Please %1$sauthenticate %2$swith Google Analytics to allow the plugin to fetch data.', 'google-analytics-for-wordpress' ),
							'<a href="' . $url . '">',
							'</a>'
					)
				);
			} else {
				return monsterinsights_get_message( 'error', esc_html__( 'The Google oAuth authentication needs to be re-authenticated to view data.', 'google-analytics-for-wordpress' ) );
			}
		}

		if ( ! MonsterInsights()->license->license_can( $this->level ) ) {
			return $this->get_upsell_notice();
		}

		$error = $this->requirements( false, array(), $this->name );

		if ( ! empty( $error ) ) {
			return monsterinsights_get_message( 'error', $error );
		}

		if ( ! empty( $args['error'] ) ) {
			return monsterinsights_get_message( 'error', $args['error'] );
		}

		if ( empty( $args['data' ] ) || ! is_array( $args['data' ] ) ) {
			if ( monsterinsights_is_pro_version() ) {
				return '';
			} else {
				// Try to get default data.
				$args = $this->get_data( array( 'default' => true ) );
				if ( empty( $args['data'] ) || is_array( $args['data' ] ) ) {
					return monsterinsights_get_message( 'error', __( 'No data found', 'google-analytics-for-wordpress' ) );
				}

				if ( ! empty( $args['error'] ) ) {
					return monsterinsights_get_message( 'error', $data['error'] );
				}
			}
		}

		return $this->get_report_html( $args['data'] );
	}

	// Deletes the report data from the cache
	public function delete_cache( $where = 'site' ) {

		if ( $where === 'site' || $where === 'both' ) {
			delete_option( 'monsterinsights_report_data_' . $this->name );
		}
	
		if ( $where === 'network' || $where === 'both' ) {
			delete_option( 'monsterinsights_network_report_data_' . $this->name );
		}
	}

	// Get report data
	public function get_data( $args = array() ) {

		if ( ! empty( $args['default'] ) ) {
			$args['start']   = $this->default_start_date();
			$args['end']     = $this->default_end_date();
		}
		
		$start = ! empty( $args['start'] ) && $this->is_valid_date( $args['start'] ) ? $args['start'] : '';
		$end   = ! empty( $args['end'] )   && $this->is_valid_date( $args['end'] )   ? $args['end']   : '';

		if ( ! MonsterInsights()->license->license_can( $this->level ) ) {
			return array(
				'success' => true,
				'upgrade' => true,
				'data'    => array(),
			);
		}

		if ( ! $this->is_valid_date_range( $start, $end ) ) {
			return array(
				'success' => false,
				'error'   => __( 'Invalid date range.', 'google-analytics-for-wordpress' ),
				'data'    => array(),
			);
		}

		if ( ( $start !== $this->default_start_date() || $end !== $this->default_end_date() ) && ! monsterinsights_is_pro_version() ) {
			return array(
				'success' => false,
				'error'   => __( 'Please upgrade to MonsterInsights Pro to use custom date ranges.', 'google-analytics-for-wordpress' ),
				'data'    => array(),
			);
		}

		$error = apply_filters( 'monsterinsights_reports_abstract_get_data_pre_cache', false, $args, $this->name );
		if ( $error ) {
			return array(
				'success' => false,
				'error'   => $error,
				'data'    => array(),
			);
		}

		$check_cache = ( $start === $this->default_start_date() && $end === $this->default_end_date() );
		$site_auth   = MonsterInsights()->auth->get_viewname();
		$ms_auth     = is_multisite() && MonsterInsights()->auth->get_network_viewname();
		$transient   = 'monsterinsights_report_' . $this->name . '_' . $start . '_' . $end;
		// Set to same time as MI cache. MI caches same day to 15 and others to 1 day, so there's no point pinging MI before then.
		$expiration  = $end === date( 'Y-m-d' ) ? 15 * MINUTE_IN_SECONDS : DAY_IN_SECONDS;

		// Default date range, check
		if ( $site_auth || $ms_auth ) {
			// Single site or MS with auth at subsite
			$option_name  = $site_auth ? 'monsterinsights_report_data_' . $this->name : 'monsterinsights_network_report_data_' . $this->name;
			$p            = $site_auth ? MonsterInsights()->auth->get_viewid() : MonsterInsights()->auth->get_network_viewid();

			$data = array();
			if ( $check_cache ) {
				$data = ! $site_auth && $ms_auth ? get_site_option( $option_name, array() ) : get_option( $option_name, array() );
			} else {
				$data = ! $site_auth && $ms_auth ? get_site_transient( $transient ) : get_transient( $transient );
			}

			if ( ! empty( $data )             &&
				 ! empty( $data['expires'] )  &&
				   $data['expires'] >= time() &&
				 ! empty( $data['data'] )     && 
				 ! empty( $data['p'] )        && 
				   $data['p'] === $p
			) {
				return array(
					'success' => true,
					'data'    => $data['data'],
				);
			}

			// Nothing in cache, either not saved before, expired or mismatch. Let's grab from API
			$api_options = array( 'start' => $start, 'end' => $end);
			if ( ! $site_auth && $ms_auth ) {
				$api_options['network'] = true;
			}
			
			$api   = new MonsterInsights_API_Request( 'analytics/reports/' . $this->name . '/', $api_options, 'GET' );

			$additional_data = $this->additional_data();
			
			if ( ! empty( $additional_data ) ) {
				$api->set_additional_data( $additional_data );
			}
			
			$ret   = $api->request();
			//echo print_r( $ret['data']);wp_die();
			
			if ( is_wp_error( $ret ) ) {
				return array(
					'success' => false,
					'error'   => $ret->get_error_message(),
					'data'    => array(),
				);
			} else {
				// Success
				$data = array( 
					'expires' => $expiration,
					'p'       => $p,
					'data'    => $ret['data'],
				);
				if ( $check_cache ) {
					! $site_auth && $ms_auth ? update_site_option( $option_name, $data ) : update_option( $option_name, $data );
				} else {
					! $site_auth && $ms_auth ? set_site_transient( $option_name, $data, $expiration ) : set_transient( $option_name, $data, $expiration );
				}
				
				return array(
					'success' => true,
					'data'    => $ret['data'],
				);
			}

		} else {
			return array(
				'success' => false,
				'error'   => __( 'You must authenticate with MonsterInsights to use reports.', 'google-analytics-for-wordpress' ),
				'data'    => array(),
			);
		}
	}

	public function default_start_date() {
		return date( 'Y-m-d', strtotime( '-30 days' ) );
	}

	public function default_end_date() {
		return date( 'Y-m-d', strtotime( '-1 day' ) );
	}

	// Checks to see if date range is valid. Should be 30-yesterday always for lite & any valid date range to today for Pro.
	public function is_valid_date_range( $start, $end ) {
		$start = strtotime( $start );
		$end   = strtotime( $end );

		if ( $start > strtotime( 'now' ) || $end > strtotime( 'now' ) || $start < strtotime( '01 January 2005' ) || $end < strtotime( '01 January 2005' ) ) {
			return false;
		}

		// return false if the start date is after the end date
		return ( $start > $end ) ? false : true;
	}

	// Is a valid date value
	public function is_valid_date( $date = '' ) {
		$d = MonsterInsightsDateTime::createFromFormat( 'Y-m-d', $date );
		return $d && $d->format('Y-m-d') === $date;
	}

	/**
	 * Do not use the functions below this. They are unused and are just here so people
	 * with out of date MonsterInsights addons won't get fatal errors.
	 */
	protected function get_api_max_limit() {return 300;}
	protected function get_date_range() {return array();}

	public function get_upsell_notice() {
		$has_level = MonsterInsights()->license->get_license_type();
		$has_level = $has_level ? $has_level : 'lite';
		$message = sprintf( __( 'You currently have a %s level license, but this report requires at least a %s level license to view the %s. Please upgrade to view this report.', 'google-analytics-for-wordpress' ), $has_level, $this->level, $this->title );
		ob_start();?>
		<div class="monsterinsights-upsell-report-container monsterinsights-upsell-report-<?php echo $this->name;?>-bg">
			<div class="monsterinsights-upsell-container">
		  		<div class="row justify-content-center">
					<div class="col-lg-10 col-lg-offset-1 align-self-center">
						<div class="monsterinsights-upsell-card">
							  <img class="monsterinsights-upgrade-mascot" src="<?php echo trailingslashit( MONSTERINSIGHTS_PLUGIN_URL );?>assets/css/images/mascot.png" srcset="<?php echo trailingslashit( MONSTERINSIGHTS_PLUGIN_URL );?>assets/css/images/mascot@2x.png 2x" alt="">
								<div class="monsterinsights-upsell-card-card-content">
								  <span class="monsterinsights-upsell-card-title"><?php esc_html_e( 'Ready to Get Analytics Super-Powers?', 'google-analytics-for-wordpress' );?></span>
								  <p class="monsterinsights-upsell-card-subtitle"><strong><?php esc_html_e( '(And Crush Your Competition?)', 'google-analytics-for-wordpress' );?></strong></p> &nbsp;
								  <?php if ( monsterinsights_is_pro_version() ) { ?>
									  <p ><?php echo sprintf( esc_html__( "Hey there! It looks like you've got the %s license installed on your site.
									  That's awesome! %s",'google-analytics-for-wordpress'), $has_level, '<span class="dashicons dashicons-smiley"></span>' ); ?></p>
									   &nbsp;
									  <p><?php echo sprintf( esc_html__( "Do you want to access to %s reporting right now%s in your WordPress Dashboard? That comes with the <strong>%s level%s</strong> of our paid packages. You'll need to upgrade your license to get instant access.",'google-analytics-for-wordpress'), '<strong>' . $this->title, '</strong>','<a href="https://monsterinsights.com/my-account/">' . $this->level,'</a>' ); ?></p>
									   &nbsp;<p><?php echo sprintf( esc_html__( "It's easy! To upgrade, navigate to %sMy Account%s on MonsterInsights.com, go to the licenses tab, and click upgrade. We also have a %sstep by step guide%s with pictures of this process.",'google-analytics-for-wordpress'), '<a href="https://monsterinsights.com/my-account/?utm_source=wpdashboard&utm_campaign=reportupsellpro"><strong>','</strong></a>', '<a href="https://www.monsterinsights.com/docs/upgrade-monsterinsights-license/" style="text-decoration:underline !important">', '</a>' ); ?></p>
									   &nbsp;<p><?php esc_html_e( "If you have any questions, don't hesitate to reach out. We're here to help.", 'google-analytics-for-wordpress');?></p>
									<?php } else { ?>
									   <p><?php echo sprintf( esc_html__( "Hey there! %s It looks like you've got the free version of MonsterInsights installed on your site.
									  That's awesome!",'google-analytics-for-wordpress'), '<span class="dashicons dashicons-smiley"></span>' ); ?></p>
									   &nbsp;
									  <p><?php echo sprintf( esc_html__( "Do you you want to access to %s reporting right now%s in your WordPress Dashboard? That comes with %s level%s of our paid packages. To get instant access, you'll want to buy a MonsterInsights license, which also gives you access to powerful addons, expanded reporting (including the ability to use custom date ranges), comprehensive tracking features (like UserID tracking) and access to our world-class support team.",'google-analytics-for-wordpress'), '<strong>' . $this->title, '</strong>','<a href="https://monsterinsights.com/lite/">' . $this->level,'</a>' ); ?></p>
									   &nbsp;<p><?php echo sprintf( esc_html__( "Upgrading is easy! To upgrade, navigate to %sour pricing page%s, purchase the required license, and then follow the %sinstructions in the email receipt%s to upgrade. It only takes a few minutes to unlock the most powerful, yet easy to use analytics tracking system for WordPress.",'google-analytics-for-wordpress'), '<a href="https://monsterinsights.com/lite/?utm_source=wpdashboard&utm_campaign=reportupselllite"><strong>', '</strong></a>','<a style="text-decoration:underline !important" href="https://www.monsterinsights.com/docs/go-lite-pro/">', '</a>' ); ?></p>
									   &nbsp;<p><?php esc_html_e( "If you have any questions, don't hesitate to reach out. We're here to help.", 'google-analytics-for-wordpress');?></p>
									<?php } ?>
								</div>
								<div class="monsterinsights-upsell-card-action">
									 <?php if ( monsterinsights_is_pro_version() ) { ?>
								  	<a href="https://monsterinsights.com/my-account/?utm_source=wpdashboard&utm_campaign=reportupsellpro" class="monsterinsights-upsell-card-button"><?php esc_html_e( 'Upgrade Now', 'google-analytics-for-wordpress' ); ?></a>
								  	<?php } else { ?>
								  	<a href="https://monsterinsights.com/lite/?utm_source=wpdashboard&utm_campaign=reportupselllite" class="monsterinsights-upsell-card-button"><?php esc_html_e( 'Get MonsterInsights Pro', 'google-analytics-for-wordpress' ); ?></a>
								  	<?php } ?>
									</div>
								  </div>
		                         </div>
		                   </div>
		               </div>
		           </div>
		 </div>
		<?php
		return ob_get_clean();
	}

    function get_ga_report_range( $data = array() ) {
    	if ( empty( $data['reportcurrentrange'] ) || empty( $data['reportcurrentrange']['startDate'] ) || empty( $data['reportcurrentrange']['endDate'] ) ) {
    		return '';
    	} else {
    		if ( ! empty( $data['reportprevrange'] ) && ! empty( $data['reportprevrange']['startDate'] ) && ! empty( $data['reportprevrange']['endDate'] ) ) {
    			return '%3F_u.date00%3D' . str_replace( '-', '', $data['reportcurrentrange']['startDate'] ) .'%26_u.date01%3D' . str_replace( '-', '', $data['reportcurrentrange']['endDate'] ) . '%26_u.date10%3D' . str_replace( '-', '', $data['reportprevrange']['startDate'] ) .'%26_u.date11%3D' . str_replace( '-', '', $data['reportprevrange']['endDate'] ) . '/';
    		} else {
    			return '%3F_u.date00%3D' . str_replace( '-', '', $data['reportcurrentrange']['startDate'] ) .'%26_u.date01%3D' . str_replace( '-', '', $data['reportcurrentrange']['endDate'] ) . '/';
    		}
    	}
    }
}

if ( ! class_exists( 'MonsterInsightsDateTime' ) ) {
	class MonsterInsightsDateTime extends DateTime {
		public static function createFromFormat( $format, $time, $timezone = null ) {
			if ( ! $timezone ) { 
				$timezone = new DateTimeZone( date_default_timezone_get() );
			}
			if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
				return parent::createFromFormat( $format, $time, $timezone );
			}
			return new DateTime( date( $format, strtotime( $time ) ), $timezone );
		}
	}
}