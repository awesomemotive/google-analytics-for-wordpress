<?php

/**
 * Notifications.
 *
 * @since 7.10.5
 */
class MonsterInsights_Notifications {

	/**
	 * Source of notifications content.
	 *
	 * @since {VERSION}
	 *
	 * @var string
	 */
	const SOURCE_URL = 'https://plugin-cdn.monsterinsights.com/notifications.json';

	/**
	 * Option value.
	 *
	 * @since {VERSION}
	 *
	 * @var bool|array
	 */
	public $option = false;

	/**
	 * The name of the option used to store the data.
	 *
	 * @var string
	 */
	public $option_name = 'monsterinsights_notifications';

	/**
	 * MonsterInsights_Notifications constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize class.
	 *
	 * @since {VERSION}
	 */
	public function init() {

		$this->hooks();
	}

	/**
	 * Register hooks.
	 *
	 * @since {VERSION}
	 */
	public function hooks() {
		add_action( 'wp_ajax_monsterinsights_notification_dismiss', array( $this, 'dismiss' ) );

		add_action( 'wp_ajax_monsterinsights_vue_get_notifications', array( $this, 'ajax_get_notifications' ) );

		add_action( 'monsterinsights_admin_notifications_update', array( $this, 'update' ) );

	}

	/**
	 * Check if user has access and is enabled.
	 *
	 * @return bool
	 * @since {VERSION}
	 *
	 */
	public function has_access() {

		$access = false;

		if ( current_user_can( 'monsterinsights_view_dashboard' ) && ! monsterinsights_get_option( 'hide_am_notices', false ) ) {
			$access = true;
		}

		return apply_filters( 'monsterinsights_admin_notifications_has_access', $access );
	}

	/**
	 * Get option value.
	 *
	 * @param bool $cache Reference property cache if available.
	 *
	 * @return array
	 * @since {VERSION}
	 *
	 */
	public function get_option( $cache = true ) {

		if ( $this->option && $cache ) {
			return $this->option;
		}

		$option = get_option( $this->option_name, array() );

		$this->option = array(
			'update'    => ! empty( $option['update'] ) ? $option['update'] : 0,
			'events'    => ! empty( $option['events'] ) ? $option['events'] : array(),
			'feed'      => ! empty( $option['feed'] ) ? $option['feed'] : array(),
			'dismissed' => ! empty( $option['dismissed'] ) ? $option['dismissed'] : array(),
		);

		return $this->option;
	}

	/**
	 * Fetch notifications from feed.
	 *
	 * @return array
	 * @since {VERSION}
	 *
	 */
	public function fetch_feed() {

		$res = wp_remote_get( self::SOURCE_URL );

		if ( is_wp_error( $res ) ) {
			return array();
		}

		$body = wp_remote_retrieve_body( $res );

		if ( empty( $body ) ) {
			return array();
		}

		return $this->verify( json_decode( $body, true ) );
	}

	/**
	 * Verify notification data before it is saved.
	 *
	 * @param array $notifications Array of notifications items to verify.
	 *
	 * @return array
	 * @since {VERSION}
	 *
	 */
	public function verify( $notifications ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$data = array();

		if ( ! is_array( $notifications ) || empty( $notifications ) ) {
			return $data;
		}

		$option = $this->get_option();

		foreach ( $notifications as $notification ) {

			// The message and license should never be empty, if they are, ignore.
			if ( empty( $notification['content'] ) || empty( $notification['type'] ) ) {
				continue;
			}

			// Ignore if license type does not match.
			$license_type = MonsterInsights()->license->get_license_type() ? MonsterInsights()->license->get_license_type() : 'lite';

			if ( ! in_array( $license_type, $notification['type'] ) ) {
				continue;
			}

			// Ignore if notification is not ready to display(based on start time).
			if ( ! empty( $notification['start'] ) && time() < strtotime( $notification['start'] ) ) {
				continue;
			}

			// Ignore if expired.
			if ( ! empty( $notification['end'] ) && time() > strtotime( $notification['end'] ) ) {
				continue;
			}

			// Ignore if notification has already been dismissed.
			$notification_already_dismissed = false;
			if ( is_array( $option['dismissed'] ) && ! empty( $option['dismissed'] ) ) {
				foreach ( $option['dismissed'] as $dismiss_notification ) {
					if ( $notification['id'] === $dismiss_notification['id'] ) {
						$notification_already_dismissed = true;
						break;
					}
				}
			}

			if ( true === $notification_already_dismissed ) {
				continue;
			}

			$data[] = $notification;
		}

		return $data;
	}

	/**
	 * Verify saved notification data for active notifications.
	 *
	 * @param array $notifications Array of notifications items to verify.
	 *
	 * @return array
	 * @since {VERSION}
	 *
	 */
	public function verify_active( $notifications ) {

		if ( ! is_array( $notifications ) || empty( $notifications ) ) {
			return array();
		}

		$license_type = MonsterInsights()->license->get_license_type() ? MonsterInsights()->license->get_license_type() : 'lite';

		// Remove notifications that are not active, or if the license type not exists
		foreach ( $notifications as $key => $notification ) {
			if (
				( ! empty( $notification['start'] ) && time() < strtotime( $notification['start'] ) ) ||
				( ! empty( $notification['end'] ) && time() > strtotime( $notification['end'] ) ) ||
				( ! empty( $notification['type'] ) && ! in_array( $license_type, $notification['type'] ) )
			) {
				unset( $notifications[ $key ] );
			}
		}

		return $notifications;
	}

	/**
	 * Get notification data.
	 *
	 * @return array
	 * @since {VERSION}
	 *
	 */
	public function get() {

		if ( ! $this->has_access() ) {
			return array();
		}

		$option = $this->get_option();

		// Update notifications using async task.
		if ( empty( $option['update'] ) || time() > $option['update'] + DAY_IN_SECONDS ) {
			if ( false === wp_next_scheduled( 'monsterinsights_admin_notifications_update' ) ) {
				wp_schedule_single_event( time(), 'monsterinsights_admin_notifications_update' );
			}
		}

		$events = ! empty( $option['events'] ) ? $this->verify_active( $option['events'] ) : array();
		$feed   = ! empty( $option['feed'] ) ? $this->verify_active( $option['feed'] ) : array();

		$notifications              = array();
		$notifications['active']    = array_merge( $events, $feed );
		$notifications['active']    = $this->get_notifications_with_human_readeable_start_time( $notifications['active'] );
		$notifications['active']    = $this->get_notifications_with_formatted_content( $notifications['active'] );
		$notifications['dismissed'] = ! empty( $option['dismissed'] ) ? $option['dismissed'] : array();
		$notifications['dismissed'] = $this->get_notifications_with_human_readeable_start_time( $notifications['dismissed'] );
		$notifications['dismissed'] = $this->get_notifications_with_formatted_content( $notifications['dismissed'] );

		return $notifications;
	}

	/**
	 * Improve format of the content of notifications before display. By default just runs wpautop.
	 *
	 * @param array $notifications The notifications to be parsed.
	 *
	 * @return mixed
	 */
	public function get_notifications_with_formatted_content( $notifications ) {
		if ( ! is_array( $notifications ) || empty( $notifications ) ) {
			return $notifications;
		}

		foreach ( $notifications as $key => $notification ) {
			if ( ! empty( $notification['content'] ) ) {
				$notifications[ $key ]['content'] = wpautop( $notification['content'] );
				$notifications[ $key ]['content'] = apply_filters( 'monsterinsights_notification_content_display', $notifications[ $key ]['content'] );
			}
		}

		return $notifications;
	}

	/**
	 * Get notifications start time with human time difference
	 *
	 * @return array $notifications
	 *
	 * @since 7.12.3
	 */
	public function get_notifications_with_human_readeable_start_time( $notifications ) {
		if ( ! is_array( $notifications ) || empty( $notifications ) ) {
			return;
		}

		foreach ( $notifications as $key => $notification ) {
			if ( ! isset( $notification['start'] ) || empty( $notification['start'] ) ) {
				continue;
			}

			// Translators: Readable time to display
			$modified_start_time            = sprintf( __( '%1$s ago', 'google-analytics-for-wordpress' ), human_time_diff( strtotime( $notification['start'] ), current_time( 'timestamp' ) ) );
			$notifications[ $key ]['start'] = $modified_start_time;
		}

		return $notifications;
	}

	/**
	 * Get active notifications.
	 *
	 * @return array $notifications['active'] active notifications
	 *
	 * @since 7.12.3
	 */
	public function get_active_notifications() {
		$notifications = $this->get();

		// Show only 5 active notifications plus any that has a priority of 1
		$all_active = isset( $notifications['active'] ) ? $notifications['active'] : array();
		$displayed  = array();

		foreach ( $all_active as $notification ) {
			if ( ( isset( $notification['priority'] ) && $notification['priority'] === 1 ) || count( $displayed ) < 5 ) {
				$displayed[] = $notification;
			}
		}

		return $displayed;
	}

	/**
	 * Get dismissed notifications.
	 *
	 * @return array $notifications['dismissed'] dismissed notifications
	 *
	 * @since 7.12.3
	 */
	public function get_dismissed_notifications() {
		$notifications = $this->get();

		return isset( $notifications['dismissed'] ) ? $notifications['dismissed'] : array();
	}

	/**
	 * Get notification count.
	 *
	 * @return int
	 * @since {VERSION}
	 *
	 */
	public function get_count() {

		return count( $this->get_active_notifications() );
	}

	/**
	 * Check if a notification has been dismissed before
	 *
	 * @param $notification
	 *
	 * @return bool
	 */
	public function is_dismissed( $notification ) {
		if ( empty( $notification['id'] ) ) {
			return true;
		}

		$option = $this->get_option();

		foreach ( $option['dismissed'] as $item ) {
			if ( $item['id'] === $notification['id'] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Add a manual notification event.
	 *
	 * @param array $notification Notification data.
	 *
	 * @since {VERSION}
	 *
	 */
	public function add( $notification ) {

		if ( empty( $notification['id'] ) || $this->is_dismissed( $notification ) ) {
			return false;
		}

		$option = $this->get_option( false );

		$current_notifications = $option['events'];

		foreach ( $current_notifications as $item ) {
			if ( $item['id'] === $notification['id'] ) {
				return false;
			}
		}

		$notification = $this->verify( array( $notification ) );

		$notifications = array_merge( $notification, $current_notifications );

		//  Sort notifications by priority
		usort( $notifications, function ( $a, $b ) {
			if ( ! isset( $a['priority'] ) || ! isset( $b['priority'] ) ) {
				return 0;
			}

			if ( $a['priority'] == $b['priority'] ) {
				return 0;
			}

			return $a['priority'] < $b['priority'] ? - 1 : 1;
		} );

		update_option(
			$this->option_name,
			array(
				'update'    => $option['update'],
				'feed'      => $option['feed'],
				'events'    => $notifications,
				'dismissed' => $option['dismissed'],
			),
			false
		);

		return true;
	}

	/**
	 * Update notification data from feed.
	 *
	 * @param array $option (Optional) Added @since 7.13.2
	 *
	 * @since {VERSION}
	 */
	public function update() {

		$feed   = $this->fetch_feed();
		$option = $this->get_option();

		update_option(
			$this->option_name,
			array(
				'update'    => time(),
				'feed'      => $feed,
				'events'    => $option['events'],
				'dismissed' => array_slice( $option['dismissed'], 0, 30 ), // Limit dismissed notifications to last 30.
			),
			false
		);
	}

	/**
	 * Dismiss notification via AJAX.
	 *
	 * @since {VERSION}
	 */
	public function dismiss() {
		// Run a security check.
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		// Check for access and required param.
		if ( ! $this->has_access() || empty( $_POST['id'] ) ) {
			wp_send_json_error();
		}

		$id     = sanitize_text_field( wp_unslash( $_POST['id'] ) );
		$option = $this->get_option();

		// Dismiss all notifications and add them to dissmiss array.
		if ( 'all' === $id ) {
			if ( is_array( $option['feed'] ) && ! empty( $option['feed'] ) ) {
				foreach ( $option['feed'] as $key => $notification ) {
					array_unshift( $option['dismissed'], $notification );
					unset( $option['feed'][ $key ] );
				}
			}
			if ( is_array( $option['events'] ) && ! empty( $option['events'] ) ) {
				foreach ( $option['events'] as $key => $notification ) {
					array_unshift( $option['dismissed'], $notification );
					unset( $option['events'][ $key ] );
				}
			}
		}

		$type = is_numeric( $id ) ? 'feed' : 'events';

		// Remove notification and add in dismissed array.
		if ( is_array( $option[ $type ] ) && ! empty( $option[ $type ] ) ) {
			foreach ( $option[ $type ] as $key => $notification ) {
				if ( $notification['id'] == $id ) { // phpcs:ignore WordPress.PHP.StrictComparisons
					// Add notification to dismissed array.
					array_unshift( $option['dismissed'], $notification );
					// Remove notification from feed or events.
					unset( $option[ $type ][ $key ] );
					break;
				}
			}
		}

		update_option( $this->option_name, $option, false );

		wp_send_json_success();
	}

	/**
	 * This generates the markup for the notifications indicator if needed.
	 *
	 * @return string
	 */
	public function get_menu_count() {

		if ( $this->get_count() > 0 ) {
			return '<span class="monsterinsights-menu-notification-indicator update-plugins">' . $this->get_count() . '</span>';
		}

		return '';

	}

	/**
	 * Retrieve the notifications via an ajax call.
	 */
	public function ajax_get_notifications() {

		// Run a security check.
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$notifications_data = array(
			'notifications' => $this->get_active_notifications(),
			'dismissed'     => $this->get_dismissed_notifications(),
			'view_url'      => $this->get_view_url( 'monsterinsights-report-overview', 'monsterinsights_reports' ),
			'sidebar_url'   => $this->get_sidebar_url(),
		);

		wp_send_json_success( $notifications_data );
	}

	/**
	 * Get the URL for the page where users can see/read notifications.
	 *
	 * @return string
	 */
	public function get_view_url( $scroll_to, $page, $tab = '' ) {
		$disabled = monsterinsights_get_option( 'dashboards_disabled', false );

		$url = add_query_arg( array(
			'page'                      => $page,
			'monsterinsights-scroll'    => $scroll_to,
			'monsterinsights-highlight' => $scroll_to,
		), admin_url( 'admin.php' ) );

		if ( ! empty( $tab ) ) {
			$url .= '#/' . $tab;
		}

		if ( false !== $disabled ) {
			$url = is_multisite() ? network_admin_url( 'admin.php?page=monsterinsights_network' ) : admin_url( 'admin.php?page=monsterinsights_settings' );
		}

		return $url;

	}

	/**
	 * Get the notification sidebar URL for the page where users can see/read notifications.
	 *
	 * @return string
	 */
	public function get_sidebar_url() {

		$disabled = monsterinsights_get_option( 'dashboards_disabled', false );

		$url = add_query_arg(
			array(
				'page' => 'monsterinsights_reports',
				'open' => 'monsterinsights_notification_sidebar',
			),
			admin_url( 'admin.php' )
		);

		if ( false !== $disabled ) {
			$url = is_multisite() ? network_admin_url( 'admin.php?page=monsterinsights_network' ) : admin_url( 'admin.php?page=monsterinsights_settings' );
		}

		return $url;
	}

	/**
	 * Delete the notification options.
	 */
	public function delete_notifications_data() {

		delete_option( $this->option_name );

		// Delete old notices option.
		delete_option( 'monsterinsights_notices' );

		monsterinsights_notification_event_runner()->delete_data();

	}

	/**
	 * This generates the markup for the notifications indicator for expired license.
	 *
	 * @return string
	 */
	public function get_license_expired_indicator() {
			return '<span class="monsterinsights-menu-notification-indicator expired-license">!</span>';
	}
}
