<?php
/**
 * Parent Class for MonsterInsights Notification Event
 *
 * @since 7.12.3
 *
 * @package MonsterInsights
 */

class MonsterInsights_Notification_Event {

	/**
	 * Generate unique notification id
	 *
	 * @var string
	 *
	 * @since 7.12.3
	 */
	public $notification_id;

	/**
	 * Notification hook name for cron schedule
	 *
	 * @var string
	 *
	 * @since 7.12.3
	 */
	public $notification_cron_hook_name;

	/**
	 * Unique recurrence name to set up the notification interval
	 * Only accept numeric value
	 *
	 * @var string
	 *
	 * @since 7.12.3
	 */
	public $notification_recurrence_name;

	/**
	 * When the notification will run for the first time
	 * Value should be readable time, (e.g: +30 day) to run the notification after 30 days from now
	 *
	 * @var string
	 *
	 * @since 7.12.3
	 */
	public $notification_first_run_time;

	/**
	 * When the notification will repeat (e.g: 7) here `7` to repeat the notification after each 7 days
	 * Only accept numeric value
	 *
	 * @var number
	 *
	 * @since 7.12.3
	 */
	public $notification_interval;

	/**
	 * When the notification will active, default: now
	 *
	 * @var string
	 *
	 * @since 7.12.3
	 */
	public $notification_active_from;

	/**
	 * For how many days notification will be active
	 *
	 * @var string
	 *
	 * @since 7.12.3
	 */
	public $notification_active_for;

	/**
	 * Which type of license is allowed to view this notification
	 *
	 * @var array
	 *
	 * @since 7.12.3
	 */
	public $notification_type;

	/**
	 * Report start date if required e.g: "-15 day"(Readable Time)
	 *
	 * @var string
	 *
	 * @since 7.12.3
	 */
	public $report_start_from;

	/**
	 * Report end date if required e.g: "-1 day"(Readable Time)
	 *
	 * @var string
	 *
	 * @since 7.12.3
	 */
	public $report_end_to;

	/**
	 * Notification icon to display with content
	 *
	 * @var string
	 *
	 * @since 7.12.3
	 */
	public $notification_icon;

	/**
	 * Constructor
	 *
	 * @since 7.12.3
	 */
	public function __construct() {
		$this->notification_active_from = date( "m/d/Y g:i a", strtotime( "now" ) );
		$this->report_end_to            = "-1 day"; // yesterday

		if ( ! empty( $this->notification_id ) && ! empty( $this->notification_interval ) ) {

			$this->notification_cron_hook_name  = $this->notification_id . '_cron';
			$this->notification_recurrence_name = $this->notification_id . '_' . $this->notification_interval . '_days';
			$this->notification_id              = $this->notification_id . '_' . time();
			$this->notification_active_for      = date( "m/d/Y", strtotime( "+" . ( $this->notification_interval - 2 ) . " day" ) );
			$this->report_start_from            = "-". $this->notification_interval ." day";

			if( ! isset( $this->notification_first_run_time ) || empty( $this->notification_first_run_time ) ) {
				$this->notification_first_run_time = "+". $this->notification_interval ." day";
			}

			if( ! isset( $this->notification_icon ) || empty( $this->notification_icon ) ) {
				$this->notification_icon        = '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<circle cx="16" cy="16" r="16" fill="#D3F8EA"/>
<path d="M21.8634 18.6429C21.8634 18.8571 21.7831 19.0268 21.6224 19.1518C21.5688 19.3482 21.542 19.6786 21.542 20.1429C21.542 20.6071 21.5688 20.9375 21.6224 21.1339C21.7831 21.2768 21.8634 21.4464 21.8634 21.6429V22.0714C21.8634 22.25 21.8009 22.4018 21.6759 22.5268C21.5509 22.6518 21.3992 22.7143 21.2206 22.7143H12.4349C11.7206 22.7143 11.1134 22.4643 10.6134 21.9643C10.1134 21.4643 9.86345 20.8571 9.86345 20.1429V11.5714C9.86345 10.8571 10.1134 10.25 10.6134 9.75C11.1134 9.25 11.7206 9 12.4349 9H21.2206C21.3992 9 21.5509 9.0625 21.6759 9.1875C21.8009 9.3125 21.8634 9.46429 21.8634 9.64286V18.6429ZM13.292 12.5893V13.125C13.292 13.2321 13.3456 13.2857 13.4527 13.2857H19.1313C19.2384 13.2857 19.292 13.2321 19.292 13.125V12.5893C19.292 12.4821 19.2384 12.4286 19.1313 12.4286H13.4527C13.3456 12.4286 13.292 12.4821 13.292 12.5893ZM13.292 14.3036V14.8393C13.292 14.9464 13.3456 15 13.4527 15H19.1313C19.2384 15 19.292 14.9464 19.292 14.8393V14.3036C19.292 14.1964 19.2384 14.1429 19.1313 14.1429H13.4527C13.3456 14.1429 13.292 14.1964 13.292 14.3036ZM20.0688 21C20.0152 20.4286 20.0152 19.8571 20.0688 19.2857H12.4349C12.2027 19.2857 11.9974 19.375 11.8188 19.5536C11.6581 19.7143 11.5777 19.9107 11.5777 20.1429C11.5777 20.375 11.6581 20.5804 11.8188 20.7589C11.9974 20.9196 12.2027 21 12.4349 21H20.0688Z" fill="#1EC185"/>
</svg>';
			}

			$this->hooks();

			if ( ! wp_next_scheduled( $this->notification_cron_hook_name ) ) {
				wp_schedule_event( $this->get_first_cron_date(), $this->notification_recurrence_name, $this->notification_cron_hook_name );
			}

		}
	}

	/**
	 * Register hooks.
	 *
	 * @since 7.12.3
	 */
	public function hooks() {

		add_filter( $this->notification_id, array( $this, 'prepare_notification_data' ) );

		add_filter( 'cron_schedules', array( $this, 'add_cron_schedule' ) );

		add_action( $this->notification_cron_hook_name, array( $this, 'add_notification' ) );

	}

	/**
	 * Cron to add notification
	 *
	 * @param   array  $schedules  WP cron schedules.
	 *
	 * @return  array  $schedules  WP cron schedules.
	 *
	 * @since 7.12.3
	 */
	public function add_cron_schedule( $schedules ) {
		$schedules[$this->notification_recurrence_name] = array(
			'interval' => DAY_IN_SECONDS * $this->notification_interval,
			// Translators: notification cron interval
			'display'  => sprintf( __( '%s Days', 'google-analytics-for-wordpress' ), $this->notification_interval ),
		);

		return $schedules;
	}

	/**
	 * get date
	 *
	 * @param string $readable_time readable time to convert to date
	 *
	 * @return string date, format: Y-m-d
	 *
	 * @since 7.12.3
	 */
	public function get_formatted_date( $readable_time ) {
		return date( "Y-m-d", strtotime( $readable_time ) );
	}

	/**
	 * Get the upgrade URL for pro plugin
	 *
	 * @return string
	 */
	public function get_upgrade_url() {
		return wp_specialchars_decode( monsterinsights_get_upgrade_link( 'monsterinsights-notifications-sidebar', 'notifications', 'https://www.monsterinsights.com/lite/' ) );
	}

	/**
	 * Build external link by including UTM data
	 *
	 * @return string
	 */
	public function build_external_link( $url ) {
		return wp_specialchars_decode( monsterinsights_get_url( 'monsterinsights-notifications-sidebar', 'notifications', $url ) );
	}

	/**
	 * Get next cron occurrence date.
	 *
	 * (e:g +30 day) to run after 30 days from now
	 *
	 * @return int $date to run the first cron
	 *
	 * @since 7.12.3
	 */
	public function get_first_cron_date() {
		$schedule           = array();
		$schedule['day']    = rand( 0, 1 );
		$schedule['hour']   = rand( 0, 23 );
		$schedule['minute'] = rand( 0, 59 );
		$schedule['second'] = rand( 0, 59 );
		$schedule['offset'] = ( $schedule['day'] * DAY_IN_SECONDS ) +
		                      ( $schedule['hour'] * HOUR_IN_SECONDS ) +
		                      ( $schedule['minute'] * MINUTE_IN_SECONDS ) +
		                      $schedule['second'];
		$date               = strtotime( $this->notification_first_run_time ) + $schedule['offset'];

		return $date;
	}

	/**
	 * Get the URL for the page where users can see/read notifications.
	 *
	 * @return string
	 */
	public function get_view_url() {
		return MonsterInsights()->notifications->get_view_url();
	}

	/**
	 * Build Notification
	 *
	 * @param array $data
	 *
	 * @return array $notification notification is ready to add
	 *
	 * @since 7.12.3
	 */
	public function add_notification() {
		$notification            = array();
		$notification['id']      = $this->notification_id;
		$notification['icon']    = $this->notification_icon;
		$notification['title']   = '';
		$notification['content'] = '';
		$notification['type']    = $this->notification_type;
		$notification['btns']    = array();
		$notification['start']   = $this->notification_active_from;
		$notification['end']     = $this->notification_active_for;

		$notification_data =  apply_filters( $this->notification_id, $notification );

		if ( is_array( $notification_data ) && ! empty( $notification_data ) ) {
			MonsterInsights()->notifications->add( $notification_data );
		}
	}

	/**
	 * Get report
	 *
	 * @param   string  $report_name  report name, default overview report
	 * @param   string  $report_start_from  report start date, default -30 days/last 30 days
	 * @param   string  $report_end_to  report end date, default -1 day/yesterday
	 *
	 * @return array $data Overview data
	 *
	 * @since 7.12.3
	 */
	public function get_report( $report_name="overview", $report_start_from="-30 day", $report_end_to="-1 day" ) {
		// get overview report data
		$data      = array();
		$report    = MonsterInsights()->reporting->get_report( $report_name );
		$isnetwork = ! empty( $_REQUEST['isnetwork'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) : '';
		$args = array(
			'start' => $this->get_formatted_date( $report_start_from ),
			'end'   => $this->get_formatted_date( $report_end_to ),
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
}
