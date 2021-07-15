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
				$this->notification_icon = 'default';
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
