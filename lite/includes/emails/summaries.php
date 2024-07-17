<?php

/**
 * Email Summaries main class.
 *
 * @since 8.19.0
 */
class MonsterInsights_Email_Summaries {

	/**
	 * Email template to use for this class.
	 *
	 * @since 8.19.0
	 *
	 * @var string
	 */
	private $email_template = 'summaries';

	/**
	 * Test email template
	 *
	 * @since 8.19.0
	 *
	 * @var string
	 */
	private $test_email_template = 'summaries-test';

	/**
	 * Email options
	 *
	 * @since 8.19.0
	 *
	 * @var string
	 */
	private $email_options;

	/**
	 * Constructor.
	 *
	 * @since 8.19.0
	 */
	public function __construct() {
		$options                              = array();
		$email_summaries                      = monsterinsights_get_option( 'email_summaries', 'on' );
		$options['email_summaries']           = $email_summaries;
		$options['summaries_html_template']   = monsterinsights_get_option( 'summaries_html_template', 'yes' );
		$options['summaries_carbon_copy']     = 'no';
		$options['summaries_email_addresses'] = array(get_option('admin_email'));
		$options['summaries_header_image']    = false;

		$this->email_options = $options;
		$this->hooks();

		// Remove weekly cron job.
		wp_clear_scheduled_hook( 'monsterinsights_email_summaries_weekly' );

		// Schedule or clear Monthly cron job.
		if ( ! empty( $email_summaries ) && 'on' !== $email_summaries && wp_next_scheduled( 'monsterinsights_email_summaries_cron' ) ) {
			wp_clear_scheduled_hook( 'monsterinsights_email_summaries_cron' );
		}

		if ( ! empty( $email_summaries ) && 'on' === $email_summaries && ! wp_next_scheduled( 'monsterinsights_email_summaries_cron' ) ) {
			wp_schedule_event( $this->get_first_cron_date(), 'monsterinsights_email_summaries_monthly', 'monsterinsights_email_summaries_cron' );
		}
	}

	/**
	 * Email Summaries hooks.
	 *
	 * @since 8.19.0
	 */
	public function hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		if ( ! empty( $this->email_options['email_summaries'] ) && 'on' === $this->email_options['email_summaries'] ) {
			add_action( 'init', array( $this, 'preview' ) );
			add_filter( 'monsterinsights_email_template_paths', array( $this, 'add_email_template_path' ) );
			add_filter( 'monsterinsights_emails_templates_set_initial_args', array( $this, 'set_template_args' ) );
			add_filter( 'cron_schedules', array( $this, 'add_monthly_cron_schedule' ) );
			add_action( 'monsterinsights_email_summaries_cron', array( $this, 'cron' ) );
			add_action( 'wp_ajax_monsterinsights_send_test_email', array( $this, 'send_test_email' ) );
			add_action( 'monsterinsights_after_update_settings', array(
				$this,
				'reset_email_summaries_options'
			), 10, 2 );
		}

	}

	/**
	 * Load required scripts for email summaries features
	 *
	 * @return void
	 * @since 8.19.0
	 *
	 */
	public function admin_scripts() {
		if ( monsterinsights_is_settings_page() ) {
			// This will load the required dependencies for the WordPress media uploader
			wp_enqueue_media();
		}
	}

	/**
	 * Check if Email Summaries are enabled in settings.
	 *
	 * @return bool
	 * @since 8.19.0
	 *
	 */
	protected function is_enabled() {
		if ( ! isset( $this->is_enabled ) ) {
			$this->is_enabled = false;

			if ( ! $this->is_preview() ) {

				$info_block      = new MonsterInsights_Summaries_InfoBlocks();
				$info_block      = $info_block->fetch_data();
				$email_addresses = $this->get_email_addresses();

				if ( ! empty( $info_block ) ) {
					if ( 'on' === $this->email_options['email_summaries'] && ! empty( $email_addresses ) && true === $info_block['status'] ) {
						$this->is_enabled = true;
					}
				}
			}
		}

		return apply_filters( 'monsterinsights_emails_summaries_is_enabled', $this->is_enabled );
	}

	/**
	 * Preview Email Summary.
	 *
	 * @since 8.19.0
	 */
	public function preview() {

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( ! $this->is_preview() ) {
			return;
		}

		// initiate email class.
		$emails = new MonsterInsights_WP_Emails( $this->email_template );

		// check if html template option is enabled
		if ( ! $this->is_enabled_html_template() ) {
			$emails->__set( 'html', false );
		}

		$content = $emails->build_email();

		if ( ! $this->is_enabled_html_template() ) {
			$content = wpautop( $content );
		}

		echo $content; // phpcs:ignore

		exit;
	}

	/**
	 * Check whether it's in preview mode
	 *
	 * @return boolean
	 * @since 8.19.0
	 *
	 */
	public function is_preview() {
		if ( isset( $_GET['monsterinsights_email_preview'], $_GET['monsterinsights_email_template'] ) && 'summary' === $_GET['monsterinsights_email_template'] ) { // phpcs:ignore
			return true;
		}

		return false;
	}

	/**
	 * Get the email header image.
	 *
	 * @return string The email from address.
	 * @since 8.19.0
	 *
	 */
	public function get_header_image() {
		// set default header image
		$img = array(
			'url' => plugins_url( "lite/assets/img/emails/summaries/logo-MonsterInsights.png", MONSTERINSIGHTS_PLUGIN_FILE ),
			'2x'  => plugins_url( "lite/assets/img/emails/summaries/logo-MonsterInsights@2x.png", MONSTERINSIGHTS_PLUGIN_FILE ),
		);

		if ( ! empty( $this->email_options['summaries_header_image'] ) ) {
			$img['url'] = $this->email_options['summaries_header_image'];
			$img['2x']  = '';
		}

		return apply_filters( 'monsterinsights_email_header_image', $img );
	}

	/**
	 * Get next cron occurrence date.
	 *
	 * @return int
	 * @since 8.19.0
	 *
	 */
	protected function get_first_cron_date() {
		$schedule           = array();
		$schedule['day']    = wp_rand( 0, 1 );
		$schedule['hour']   = wp_rand( 0, 23 );
		$schedule['minute'] = wp_rand( 0, 59 );
		$schedule['second'] = wp_rand( 0, 59 );
		$schedule['offset'] = ( $schedule['day'] * DAY_IN_SECONDS ) +
		                      ( $schedule['hour'] * HOUR_IN_SECONDS ) +
		                      ( $schedule['minute'] * MINUTE_IN_SECONDS ) +
		                      $schedule['second'];
		$date               = strtotime( 'next saturday' ) + $schedule['offset'];

		return $date;
	}

	/**
	 * Add summaries email template path
	 *
	 * @param array $schedules WP cron schedules.
	 *
	 * @return array
	 * @since 8.19.0
	 *
	 */
	public function add_email_template_path( $file_paths ) {
		$file_paths['1000'] = MONSTERINSIGHTS_PLUGIN_DIR . 'lite/includes/emails/templates';

		return $file_paths;
	}

	/**
	 * Add custom Email Summaries cron schedule.
	 *
	 * @param array $schedules WP cron schedules.
	 *
	 * @return array
	 * @since 8.19.0
	 *
	 */
	public function add_monthly_cron_schedule( $schedules ) {
		$schedules['monsterinsights_email_summaries_monthly'] = array(
			'interval' => MONTH_IN_SECONDS,
			'display'  => esc_html__( 'Monthly MonsterInsights Email Summaries', 'google-analytics-for-wordpress' ),
		);

		return $schedules;
	}

	/**
	 * Get email subject
	 *
	 * @since 8.19.0
	 */
	public function get_email_subject() {

		$site_url        = get_site_url();
		$site_url_parsed = parse_url( $site_url );// Can't use wp_parse_url as that was added in WP 4.4 and we still support 3.8.
		$site_url        = isset( $site_url_parsed['host'] ) ? $site_url_parsed['host'] : $site_url;

		// Translators: The domain of the site is appended to the subject.
		$subject = sprintf( __( 'MonsterInsights Summary - %s', 'google-analytics-for-wordpress' ), $site_url );

		return apply_filters( 'monsterinsights_emails_summaries_cron_subject', $subject );
	}

	/**
	 * Get email addresses to send
	 *
	 * @since 8.19.0
	 */
	public function get_email_addresses() {
		$emails          = $this->email_options['summaries_email_addresses'];
		return apply_filters( 'monsterinsights_email_addresses_to_send', $emails );
	}

	/**
	 * check if carbon copy option is enabled
	 *
	 * @since 8.19.0
	 */
	public function is_cc_enabled() {
		$value = false;
		if ( 'yes' === $this->email_options['summaries_carbon_copy'] ) {
			$value = true;
		}

		return apply_filters( 'monsterinsights_email_cc_enabled', $value, $this );
	}

	/**
	 * Check if html template option is turned on
	 *
	 * @return bool
	 * @since 8.19.0
	 *
	 */
	public function is_enabled_html_template() {
		$value = true;
		if ( 'no' === $this->email_options['summaries_html_template'] ) {
			$value = false;
		}

		return apply_filters( 'monsterinsights_email_html_template', $value, $this );
	}

	/**
	 * Email Summaries cron callback.
	 *
	 * @since 8.19.0
	 */
	public function cron() {

		if ( ! $this->is_enabled() ) {
			return;
		}

		if( !monsterinsights_is_authed() ){
			return;
		}

		$email            = array();
		$email['subject'] = $this->get_email_subject();
		$email['address'] = $this->get_email_addresses();
		$email['address'] = array_map( 'sanitize_email', $email['address'] );

		// Create new email.
		$emails = new MonsterInsights_WP_Emails( $this->email_template );

		// Maybe include CC.
		if ( $this->is_cc_enabled() ) {
			$emails->__set( 'cc', implode( ',', $this->get_email_addresses() ) );
		}

		// check if html template option is enabled
		if ( ! $this->is_enabled_html_template() ) {
			$emails->__set( 'html', false );
		}

		$info_blocks = new MonsterInsights_Summaries_InfoBlocks();
		$next_block  = $info_blocks->get_next();

		// Go.
		if( !empty( $email['address'] ) ){
			foreach ( $email['address'] as $address ) {
				$sent = $emails->send( trim( $address ), $email['subject'] );

				if ( true === $sent && ! empty( $next_block ) ) {
					$info_blocks->register_sent( $next_block );
				}
			}
		}
	}

	/**
	 * Send test email
	 *
	 * @since 8.19.0
	 */
	public function send_test_email() {
		// Run a security check first.
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		$email            = array();
		$email['subject'] = '[Test email] MonsterInsights Summary';
		$email['address'] = $this->get_email_addresses();
		$email['address'] = array_map( 'sanitize_email', $email['address'] );

		// Create new email.
		$emails = new MonsterInsights_WP_Emails( $this->test_email_template );

		// Maybe include CC.
		if ( $this->is_cc_enabled() ) {
			$emails->__set( 'cc', implode( ',', $this->get_email_addresses() ) );
		}

		// check if html template option is enabled
		if ( ! $this->is_enabled_html_template() ) {
			$emails->__set( 'html', false );
		}

		// Go.
		if(!empty($email['address'])){
			foreach ( $email['address'] as $address ) {
				if ( ! $emails->send( trim( $address ), $email['subject'] ) ) {
					wp_send_json_error();
				}
			}
		}
		wp_send_json_success();
	}

	/**
	 * Email summaries template arguments
	 *
	 * @since 8.19.0
	 */
	public function set_template_args( $args ) {
		if ( $this->is_enabled_html_template() ) {
			$args['header']['header_image'] = $this->get_header_image();
		}

		$args['body']['title']       = esc_html__( 'Website Traffic Summary', 'google-analytics-for-wordpress' );
		$args['body']['description'] = esc_html__( 'Let’s take a look at how your website traffic performed in the past month.', 'google-analytics-for-wordpress' );
		$args['body']['summaries']   = $this->get_summaries();
		$args['body']['startDate']   = $this->get_summaries_start_date();
		$args['body']['endDate']     = $this->get_summaries_end_date();

		$info_blocks = new MonsterInsights_Summaries_InfoBlocks();
		$next_block  = $info_blocks->get_next();

		if ( ! empty( $next_block ) ) {
			$args['body']['info_block'] = $next_block;
		}

		$args['body']['settings_tab_url']   = esc_url( admin_url( 'admin.php?page=monsterinsights_settings#/advanced' ) );
		$args['footer']['settings_tab_url'] = esc_url( admin_url( 'admin.php?page=monsterinsights_settings#/advanced' ) );

		$args['body']['summaries']['data']['galinks']['topposts']  = admin_url( 'admin.php?page=monsterinsights_reports#/' );

		return apply_filters( 'monsterinsights_email_summaries_template_args', $args );
	}

	/**
	 * get the start date from the last month
	 *
	 * @since 8.19.0
	 */
	public function get_summaries_start_date() {
		return date( "Y-m-d", strtotime( "first day of last month" ) ); // first day of last month
	}

	/**
	 * get the end date from the last week
	 *
	 * @since 8.19.0
	 */
	public function get_summaries_end_date() {
		return date( "Y-m-d", strtotime( "last day of last month" ) ); // last day of last month
	}

	/**
	 * data for email template
	 *
	 * @since 8.19.0
	 */
	public function get_summaries() {
		$data = array();

		// get overview report data for email summaries template
		$report_name = 'summaries';
		$report      = MonsterInsights()->reporting->get_report( $report_name );

		$isnetwork = ! empty( $_REQUEST['isnetwork'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) : '';

		// get the data of last month
		$args = array(
			'start' => $this->get_summaries_start_date(),
			'end'   => $this->get_summaries_end_date(),
		);

		if ( $isnetwork ) {
			$args['network'] = true;
		}

		if ( monsterinsights_is_pro_version() && ! MonsterInsights()->license->license_can( $report->level ) ) {
			$data = array(
				'success' => false,
				'message' => __( "You don't have permission to view MonsterInsights reports.", 'google-analytics-for-wordpress' ),
			);
		} else {
			$data = apply_filters( 'monsterinsights_vue_reports_data', $report->get_data( $args ), $report_name, $report );
		}

		return $data;
	}


	/**
	 * reset email summaries options
	 *
	 * @since 8.19.0
	 */
	public function reset_email_summaries_options( $key, $value ) {
		if ( isset( $key ) && $key === 'email_summaries' && isset( $value ) && $value === 'off' ) {
			$default_email = array(
				'email' => get_option( 'admin_email' ),
			);
			monsterinsights_update_option( 'summaries_email_addresses', array( $default_email ) );
			monsterinsights_update_option( 'summaries_header_image', '' );
		}
	}
}
