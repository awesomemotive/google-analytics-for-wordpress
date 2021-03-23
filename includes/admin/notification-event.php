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

		$this->notification_active_from = date( 'm/d/Y g:i a', strtotime( 'now' ) );
		$this->report_end_to            = '-1 day'; // Yesterday.

		if ( ! empty( $this->notification_id ) && ! empty( $this->notification_interval ) ) {

			// Register notification in our custom runner.
			monsterinsights_notification_event_runner()->register_notification( $this );

			$this->notification_active_for = date( 'm/d/Y', strtotime( '+' . ( $this->notification_interval - 2 ) . ' day' ) );
			$this->report_start_from       = '-' . $this->notification_interval . ' day';

			if ( ! isset( $this->notification_icon ) || empty( $this->notification_icon ) ) {
				$this->notification_icon = '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<circle cx="16" cy="16" r="16" fill="#D3F8EA"/>
<path d="M21.8634 18.6429C21.8634 18.8571 21.7831 19.0268 21.6224 19.1518C21.5688 19.3482 21.542 19.6786 21.542 20.1429C21.542 20.6071 21.5688 20.9375 21.6224 21.1339C21.7831 21.2768 21.8634 21.4464 21.8634 21.6429V22.0714C21.8634 22.25 21.8009 22.4018 21.6759 22.5268C21.5509 22.6518 21.3992 22.7143 21.2206 22.7143H12.4349C11.7206 22.7143 11.1134 22.4643 10.6134 21.9643C10.1134 21.4643 9.86345 20.8571 9.86345 20.1429V11.5714C9.86345 10.8571 10.1134 10.25 10.6134 9.75C11.1134 9.25 11.7206 9 12.4349 9H21.2206C21.3992 9 21.5509 9.0625 21.6759 9.1875C21.8009 9.3125 21.8634 9.46429 21.8634 9.64286V18.6429ZM13.292 12.5893V13.125C13.292 13.2321 13.3456 13.2857 13.4527 13.2857H19.1313C19.2384 13.2857 19.292 13.2321 19.292 13.125V12.5893C19.292 12.4821 19.2384 12.4286 19.1313 12.4286H13.4527C13.3456 12.4286 13.292 12.4821 13.292 12.5893ZM13.292 14.3036V14.8393C13.292 14.9464 13.3456 15 13.4527 15H19.1313C19.2384 15 19.292 14.9464 19.292 14.8393V14.3036C19.292 14.1964 19.2384 14.1429 19.1313 14.1429H13.4527C13.3456 14.1429 13.292 14.1964 13.292 14.3036ZM20.0688 21C20.0152 20.4286 20.0152 19.8571 20.0688 19.2857H12.4349C12.2027 19.2857 11.9974 19.375 11.8188 19.5536C11.6581 19.7143 11.5777 19.9107 11.5777 20.1429C11.5777 20.375 11.6581 20.5804 11.8188 20.7589C11.9974 20.9196 12.2027 21 12.4349 21H20.0688Z" fill="#1EC185"/>
</svg>';
			}

		}
	}

	/**
	 * Get the formatted date.
	 *
	 * @param string $readable_time Readable time to convert to date.
	 *
	 * @return string date, format: Y-m-d
	 *
	 * @since 7.12.3
	 */
	public function get_formatted_date( $readable_time ) {
		return date( 'Y-m-d', strtotime( $readable_time ) );
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
		$build_url   = wp_specialchars_decode( monsterinsights_get_url( 'monsterinsights-notifications-sidebar', 'notifications', $url ) );
		$host        = parse_url( $build_url, PHP_URL_HOST );
		$domain_name = preg_replace( '/^www\./', '', $host );

		if ( 'monsterinsights.com' != $domain_name ) {
			parse_str( parse_url( $build_url, PHP_URL_QUERY ), $queries );

			if ( isset( $queries['utm_source'] ) ) {
				$queries['utm_source'] = 'monsterinsights';
			}

			$build_url = add_query_arg(
				$queries,
				trailingslashit( $url )
			);
		}

		return $build_url;
	}

	/**
	 * Get the URL for the page where users can see/read notifications.
	 *
	 * @return string
	 */
	public function get_view_url( $scroll_to, $page, $tab='' ) {
		return MonsterInsights()->notifications->get_view_url( $scroll_to, $page, $tab );
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function prepare_notification_data( $data ) {
		return $data;
	}

	/**
	 * Add Notification not the notifications instance.
	 *
	 * @since 7.12.3
	 */
	public function add_notification() {
		$notification            = array();
		$notification['id']      = $this->notification_id . '_' . date( 'Ymd' ); // Make sure we never add the same notification on the same day.
		$notification['icon']    = $this->notification_icon;
		$notification['title']   = '';
		$notification['content'] = '';
		$notification['type']    = $this->notification_type;
		$notification['btns']    = array();
		$notification['start']   = $this->notification_active_from;
		$notification['end']     = $this->notification_active_for;
		$notification_data       = $this->prepare_notification_data( $notification );

		if ( is_array( $notification_data ) && ! empty( $notification_data ) ) {
			MonsterInsights()->notifications->add( $notification_data );
		}
	}

	/**
	 * Get report
	 *
	 * @param string $report_name report name, default overview report.
	 * @param string $report_start_from report start date, default -30 days/last 30 days.
	 * @param string $report_end_to report end date, default -1 day/yesterday.
	 *
	 * @return array $data Overview data
	 *
	 * @since 7.12.3
	 */
	public function get_report( $report_name = 'overview', $report_start_from = '-30 day', $report_end_to = '-1 day' ) {

		$report = MonsterInsights()->reporting->get_report( $report_name );
		if ( $report ) {
			// Mark the report request as coming from Notifications.
			$report->set_report_source( 'notifications' );
		}
		$isnetwork = ! empty( $_REQUEST['isnetwork'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) : '';
		$args      = array(
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
			$data = $report->get_data( $args );
		}

		return $data;
	}
}
