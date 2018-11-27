<?php

if ( ! class_exists( 'AM_Notification', false ) ) {
	/**
	 * Awesome Motive Notifications
	 *
	 * This creates a custom post type (if it doesn't exist) and calls the API to
	 * retrieve notifications for this product.
	 *
	 * @package    AwesomeMotive
	 * @author     AwesomeMotive Team
	 * @license    GPL-2.0+
	 * @copyright  Copyright (c) 2018, Awesome Motive LLC
	 * @version    1.0.7
	 */
	class AM_Notification {

		/**
		 * The api url we are calling.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public $api_url = 'https://api.awesomemotive.com/v1/notification/';

		/**
		 * A unique slug for this plugin.
		 * (Not the WordPress plugin slug)
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public $plugin;

		/**
		 * The current plugin version.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public $plugin_version;

		/**
		 * Flag if a notice has been registered.
		 *
		 * @since 1.0.0
		 *
		 * @var bool
		 */
		public static $registered = false;

		/**
		 * Construct.
		 *
		 * @since 1.0.0
		 *
		 * @param string $plugin The plugin slug.
		 * @param mixed $version The version of the plugin.
		 */
		public function __construct( $plugin = '', $version = 0 ) {
			$this->plugin         = $plugin;
			$this->plugin_version = $version;

			add_action( 'init', array( $this, 'custom_post_type' ) );
			add_action( 'admin_init', array( $this, 'get_remote_notifications' ), 100 );
			add_action( 'admin_notices', array( $this, 'display_notifications' ) );
			add_action( 'wp_ajax_am_notification_dismiss', array( $this, 'dismiss_notification' ) );
		}

		/**
		 * Registers a custom post type.
		 *
		 * @since 1.0.0
		 */
		public function custom_post_type() {
			register_post_type( 'amn_' . $this->plugin, array(
				'label'           => $this->plugin . ' Announcements',
				'can_export'      => false,
				'supports'        => false,
				'capability_type' => 'manage_options',
			) );
		}

		/**
		 * Retrieve the remote notifications if the time has expired.
		 *
		 * @since 1.0.0
		 */
		public function get_remote_notifications() {
			if ( ! apply_filters( 'am_notifications_display', is_super_admin() ) ) {
				return;
			}

			$last_checked = get_option( '_amn_' . $this->plugin . '_last_checked', strtotime( '-1 week' ) );

			if ( $last_checked < strtotime( 'today midnight' ) ) {
				$plugin_notifications = $this->get_plugin_notifications( 1 );
				$notification_id      = null;

				if ( ! empty( $plugin_notifications ) ) {
					// Unset it from the array.
					$notification    = $plugin_notifications[0];
					$notification_id = get_post_meta( $notification->ID, 'notification_id', true );
				}

				$response = wp_remote_retrieve_body( wp_remote_post( $this->api_url, array(
					'body' => array(
						'slug'              => $this->plugin,
						'version'           => $this->plugin_version,
						'last_notification' => $notification_id,
					),
				) ) );

				$data = json_decode( $response );

				if ( ! empty( $data->id ) ) {
					$notifications = array();

					foreach ( (array) $data->slugs as $slug ) {
						$notifications = array_merge(
							$notifications,
							(array) get_posts(
								array(
									'post_type'   => 'amn_' . $slug,
									'post_status' => 'all',
									'meta_key'    => 'notification_id',
									'meta_value'  => $data->id,
								)
							)
						);
					}

					if ( empty( $notifications ) ) {
						$new_notification_id = wp_insert_post(
							array(
								'post_content' => wp_kses_post( $data->content ),
								'post_type'    => 'amn_' . $this->plugin,
							)
						);

						update_post_meta( $new_notification_id, 'notification_id', absint( $data->id ) );
						update_post_meta( $new_notification_id, 'type', sanitize_text_field( trim( $data->type ) ) );
						update_post_meta( $new_notification_id, 'dismissable', (bool) $data->dismissible ? 1 : 0 );
						update_post_meta( $new_notification_id, 'location', function_exists( 'wp_json_encode' ) ? wp_json_encode( $data->location ) : json_encode( $data->location ) );
						update_post_meta( $new_notification_id, 'version', sanitize_text_field( trim( $data->version ) ) );
						update_post_meta( $new_notification_id, 'viewed', 0 );
						update_post_meta( $new_notification_id, 'expiration', $data->expiration ? absint( $data->expiration ) : false );
						update_post_meta( $new_notification_id, 'plans', function_exists( 'wp_json_encode' ) ? wp_json_encode( $data->plans ) : json_encode( $data->plans ) );
					}
				}

				// Possibly revoke notifications.
				if ( ! empty( $data->revoked ) ) {
					$this->revoke_notifications( $data->revoked );
				}

				// Set the option now so we can't run this again until after 24 hours.
				update_option( '_amn_' . $this->plugin . '_last_checked', strtotime( 'today midnight' ) );
			}
		}

		/**
		 * Get local plugin notifications that have already been set.
		 *
		 * @since 1.0.0
		 *
		 * @param  integer $limit Set the limit for how many posts to retrieve.
		 * @param  array $args Any top-level arguments to add to the array.
		 *
		 * @return WP_Post[] WP_Post that match the query.
		 */
		public function get_plugin_notifications( $limit = - 1, $args = array() ) {
			return get_posts(
				array(
					'posts_per_page' => $limit,
					'post_type'      => 'amn_' . $this->plugin,
				) + $args
			);
		}

		/**
		 * Display any notifications that should be displayed.
		 *
		 * @since 1.0.0
		 */
		public function display_notifications() {
			if ( ! apply_filters( 'am_notifications_display', is_super_admin() ) ) {
				return;
			}

			$plugin_notifications = $this->get_plugin_notifications( - 1, array(
				'post_status' => 'all',
				'meta_key'    => 'viewed',
				'meta_value'  => '0',
			) );

			$plugin_notifications = $this->validate_notifications( $plugin_notifications );

			if ( ! empty( $plugin_notifications ) && ! self::$registered ) {
				foreach ( $plugin_notifications as $notification ) {
					$dismissable = get_post_meta( $notification->ID, 'dismissable', true );
					$type        = get_post_meta( $notification->ID, 'type', true );
					?>
					<div class="am-notification am-notification-<?php echo absint( $notification->ID ); ?> notice notice-<?php echo esc_attr( $type ); ?><?php echo $dismissable ? ' is-dismissible' : ''; ?>">
						<?php echo wp_kses_post( $notification->post_content ); ?>
					</div>
					<script type="text/javascript">
						jQuery( document ).ready( function ( $ ) {
							$( document ).on( 'click', '.am-notification-<?php echo absint( $notification->ID ); ?> button.notice-dismiss', function ( event ) {
								$.post( ajaxurl, {
									action: 'am_notification_dismiss',
									notification_id: '<?php echo absint( $notification->ID ); ?>'
								} );
							} );
						} );
					</script>
					<?php
				}

				self::$registered = true;
			}
		}

		/**
		 * Validate the notifications before displaying them.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $plugin_notifications An array of plugin notifications.
		 *
		 * @return array                       A filtered array of plugin notifications.
		 */
		public function validate_notifications( $plugin_notifications ) {
			global $pagenow;

			foreach ( $plugin_notifications as $key => $notification ) {
				// Location validation.
				$location = (array) json_decode( get_post_meta( $notification->ID, 'location', true ) );
				$continue = false;
				if ( ! in_array( 'everywhere', $location, true ) ) {
					if ( in_array( 'index.php', $location, true ) && 'index.php' === $pagenow ) {
						$continue = true;
					}

					if ( in_array( 'plugins.php', $location, true ) && 'plugins.php' === $pagenow ) {
						$continue = true;
					}

					if ( ! $continue ) {
						unset( $plugin_notifications[ $key ] );
					}
				}

				// Plugin validation (OR conditional).
				$plugins  = (array) json_decode( get_post_meta( $notification->ID, 'plugins', true ) );
				$continue = false;
				if ( ! empty( $plugins ) ) {
					foreach ( $plugins as $plugin ) {
						if ( is_plugin_active( $plugin ) ) {
							$continue = true;
						}
					}

					if ( ! $continue ) {
						unset( $plugin_notifications[ $key ] );
					}
				}

				// Theme validation.
				$theme    = get_post_meta( $notification->ID, 'theme', true );
				$continue = (string) wp_get_theme() === $theme;

				if ( ! empty( $theme ) && ! $continue ) {
					unset( $plugin_notifications[ $key ] );
				}

				// Version validation.
				$version  = get_post_meta( $notification->ID, 'version', true );
				$continue = false;
				if ( ! empty( $version ) ) {
					if ( version_compare( $this->plugin_version, $version, '<=' ) ) {
						$continue = true;
					}

					if ( ! $continue ) {
						unset( $plugin_notifications[ $key ] );
					}
				}

				// Expiration validation.
				$expiration = get_post_meta( $notification->ID, 'expiration', true );
				$continue   = false;
				if ( ! empty( $expiration ) ) {
					if ( $expiration > time() ) {
						$continue = true;
					}

					if ( ! $continue ) {
						unset( $plugin_notifications[ $key ] );
					}
				}

				// Plan validation.
				$plans    = (array) json_decode( get_post_meta( $notification->ID, 'plans', true ) );
				$continue = false;
				if ( ! empty( $plans ) ) {
					$level = $this->get_plan_level();
					if ( in_array( $level, $plans, true ) ) {
						$continue = true;
					}

					if ( ! $continue ) {
						unset( $plugin_notifications[ $key ] );
					}
				}
			}

			return $plugin_notifications;
		}

		/**
		 * Grab the current plan level.
		 *
		 * @since 1.0.0
		 *
		 * @return string The current plan level.
		 */
		public function get_plan_level() {
			// Prepare variables.
			$key   = '';
			$level = '';

			switch ( $this->plugin ) {
				case 'wpforms':
					$option = get_option( 'wpforms_license' );
					$key    = is_array( $option ) && isset( $option['key'] ) ? $option['key'] : '';
					$level  = is_array( $option ) && isset( $option['type'] ) ? $option['type'] : '';

					// Possibly check for a constant.
					if ( empty( $key ) && defined( 'WPFORMS_LICENSE_KEY' ) ) {
						$key = WPFORMS_LICENSE_KEY;
					}
					break;
				case 'mi-lite':
				case 'mi':
					if ( version_compare( MONSTERINSIGHTS_VERSION, '6.9.0', '>=' ) ) {
						if ( MonsterInsights()->license->get_site_license_type() ) {
							$key  = MonsterInsights()->license->get_site_license_key();
							$type = MonsterInsights()->license->get_site_license_type();
						} else if ( MonsterInsights()->license->get_network_license_type() ) {
							$key  = MonsterInsights()->license->get_network_license_key();
							$type = MonsterInsights()->license->get_network_license_type();
						}

						// Check key fallbacks
						if ( empty( $key ) ) {
							$key = MonsterInsights()->license->get_license_key();
						}
					} else {
						$option = get_option( 'monsterinsights_license' );
						$key    = is_array( $option ) && isset( $option['key'] ) ? $option['key'] : '';
						$level  = is_array( $option ) && isset( $option['type'] ) ? $option['type'] : '';

						// Possibly check for a constant.
						if ( empty( $key ) && defined( 'MONSTERINSIGHTS_LICENSE_KEY' ) && is_string( MONSTERINSIGHTS_LICENSE_KEY ) && strlen( MONSTERINSIGHTS_LICENSE_KEY ) > 10 ) {
							$key = MONSTERINSIGHTS_LICENSE_KEY;
						}
					}
					break;
				case 'om':
					$option = get_option( 'optin_monster_api' );
					$key    = is_array( $option ) && isset( $option['api']['apikey'] ) ? $option['api']['apikey'] : '';

					// Possibly check for a constant.
					if ( empty( $key ) && defined( 'OPTINMONSTER_REST_API_LICENSE_KEY' ) ) {
						$key = OPTINMONSTER_REST_API_LICENSE_KEY;
					}

					// If the key is still empty, check for the old legacy key.
					if ( empty( $key ) ) {
						$key = is_array( $option ) && isset( $option['api']['key'] ) ? $option['api']['key'] : '';
					}
					break;
			}

			// Possibly set the level to 'none' if the key is empty and no level has been set.
			if ( empty( $key ) && empty( $level ) ) {
				$level = 'none';
			}

			// Possibly set the level to 'unknown' if a key is entered, but no level can be determined (such as manually entered key)
			if ( ! empty( $key ) && empty( $level ) ) {
				$level = 'unknown';
			}	

			// Normalize the level.
			switch ( $level ) {
				case 'bronze':
				case 'personal':
					$level = 'basic';
					break;
				case 'silver':
				case 'multi':
					$level = 'plus';
					break;
				case 'gold':
				case 'developer':
					$level = 'pro';
					break;
				case 'platinum':
				case 'master':
					$level = 'ultimate';
					break;
			}

			// Return the plan level.
			return $level;
		}

		/**
		 * Dismiss the notification via AJAX.
		 *
		 * @since 1.0.0
		 */
		public function dismiss_notification() {
			if ( ! apply_filters( 'am_notifications_display', is_super_admin() ) ) {
				die;
			}

			$notification_id = intval( $_POST['notification_id'] );
			update_post_meta( $notification_id, 'viewed', 1 );
			die;
		}

		/**
		 * Revokes notifications.
		 *
		 * @since 1.0.0
		 *
		 * @param array $ids An array of notification IDs to revoke.
		 */
		public function revoke_notifications( $ids ) {
			// Loop through each of the IDs and find the post that has it as meta.
			foreach ( (array) $ids as $id ) {
				$notifications = $this->get_plugin_notifications( - 1, array( 'post_status' => 'all', 'meta_key' => 'notification_id', 'meta_value' => $id ) );
				if ( $notifications ) {
					foreach ( $notifications as $notification ) {
						update_post_meta( $notification->ID, 'viewed', 1 );
					}
				}
			}
		}
	}
}