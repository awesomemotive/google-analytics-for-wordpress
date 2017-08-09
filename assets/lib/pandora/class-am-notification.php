<?php
if ( ! class_exists( 'AM_Notification' ) ) {
	/**
	 * Awesome Motive Notifications
	 *
	 * This creates a custom post type (if it doesn't exist) and calls the API to
	 * retrieve notifications for this product.
	 *
	 * @package    AwesomeMotive
	 * @author     Benjamin Rojas
	 * @license    GPL-2.0+
	 * @copyright  Copyright (c) 2017, Retyp LLC
	 * @version    1.0.0
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
		 * The list of installed plugins.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		public $plugin_list = array();

		/**
		 * The list of installed themes.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public $theme_list = array();

		/**
		 * Construct.
		 *
		 * @since 1.0.0
		 *
		 * @param string $plugin  The plugin slug.
		 * @param string $version The version of the plugin.
		 */
		public function __construct( $plugin = '', $version = 0 ) {
			$this->plugin         = $plugin;
			$this->plugin_version = $version;

			add_action( 'init', array( $this, 'custom_post_type' ) );
			add_action( 'init', array( $this, 'get_remote_notifications' ), 100 );
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
				'supports' => false
			) );
		}

		/**
		 * Retrieve the remote notifications if the time has expired.
		 *
		 * @since 1.0.0
		 */
		public function get_remote_notifications() {
			$last_checked  = get_option( '_amn_' . $this->plugin . '_last_checked', strtotime( '-1 week' ) );

			if ( $last_checked < strtotime( 'today midnight' ) ) {

				$plugin_notifications = $this->get_plugin_notifications( 1 );

				$notification_id = null;
				if ( ! empty( $plugin_notifications) ) {
					// Unset it from the array.
					$notification    = $plugin_notifications[0];
					$notification_id = get_post_meta( $notification->ID, 'notification_id', true );
				}

				$response = wp_remote_retrieve_body( wp_remote_post( $this->api_url, array(
					'sslverify' => false,
					'body' => array(
						'slug'              => $this->plugin,
						'version'           => $this->plugin_version,
						'last_notification' => $notification_id,
						'plugins'           => $this->get_plugins_list(),
						'themes'            => $this->get_themes_list()
					)
				) ) );

				$data = json_decode( $response );

				if ( ! empty( $data->id ) ) {

					$notifications = array();
					foreach ( (array) $data->slugs as $slug ) {
						$notifications = array_merge( $notifications, (array) get_posts( array(
							'post_type'  => 'amn_' . $slug,
							'post_status' => 'all',
							'meta_key' => 'notification_id',
							'meta_value' => $data->id
						) ) );
					}

					if ( empty( $notifications ) ) {

						$new_notification_id = wp_insert_post( array(
							'post_content' => $data->content,
							'post_type'    => 'amn_' . $this->plugin
						) );

						update_post_meta( $new_notification_id, 'notification_id', $data->id );
						update_post_meta( $new_notification_id, 'type', $data->type );
						update_post_meta( $new_notification_id, 'dismissable', (bool) $data->dismissible ? 1 : 0 );
						update_post_meta( $new_notification_id, 'location', wp_json_encode( $data->location ) );
						update_post_meta( $new_notification_id, 'plugins', wp_json_encode( $data->plugins ) );
						update_post_meta( $new_notification_id, 'theme', $data->theme );
						update_post_meta( $new_notification_id, 'version', $data->version );
						update_post_meta( $new_notification_id, 'viewed', 0 );
					}

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
		 * @param  array   $args  Any top-level arguments to add to the array.
		 * @return object         WP_Posts that match the query.
		 */
		public function get_plugin_notifications( $limit = -1, $args = array() ) {
			return get_posts( array(
					'showposts' => $limit,
					'post_type' => 'amn_' . $this->plugin
			) + $args );
		}

		/**
		 * Retrieve a list of plugins that are currently installed.
		 *
		 * @since 1.0.0
		 *
		 * @return array An array of plugins that are currently installed.
		 */
		public function get_plugins_list() {
			if ( ! empty( $this->plugin_list ) ) {
				return $this->plugin_list;
			}

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugins = get_plugins();

			foreach ( $plugins as $slug => $plugin ) {
				$this->plugin_list[ $slug ] = array(
					'slug'    => $slug,
					'name'    => $plugin['Name'],
					'version' => $plugin['Version'],
					'active'  => is_plugin_active( $slug )
				);
			}

			return $this->plugin_list;
		}

		/**
		 * Retrieve a list of themes that are currently installed.
		 *
		 * @since 1.0.0
		 *
		 * @return array An array of themes that are currently installed.
		 */
		public function get_themes_list() {
			if ( ! empty( $this->theme_list ) ) {
				return $this->theme_list;
			}

			$themes = wp_get_themes();

			foreach ( $themes as $slug => $theme ) {
				$this->theme_list[ $slug ] = array(
					'slug'    => $slug,
					'name'    => $theme->Name,
					'version' => $theme->Version,
					'active'  => (string) wp_get_theme() == $theme->Name
				);
			}

			return $this->theme_list;
		}

		/**
		 * Display any notifications that should be displayed.
		 *
		 * @since 1.0.0
		 */
		public function display_notifications() {

			if ( ! current_user_can( apply_filters( 'am_notifications_display', 'manage_options' ) ) ) {
				return;
			}

			$plugin_notifications = $this->get_plugin_notifications( -1, array(
				'post_status' => 'all',
				'meta_key' => 'viewed',
				'meta_value' => '0'
			) );

			$plugin_notifications = $this->validate_notifications( $plugin_notifications );

			if ( ! empty( $plugin_notifications ) ) {
				foreach ( $plugin_notifications as $notification ) {
					$dismissable = get_post_meta( $notification->ID, 'dismissable', true );
					$type        = get_post_meta( $notification->ID, 'type', true );
					?>
					<div class="am-notification am-notification-<?php echo $notification->ID; ?> notice notice-<?php echo $type; ?><?php echo $dismissable ? ' is-dismissible' : ''; ?>">
						<?php echo $notification->post_content; ?>
					</div>
					<script type="text/javascript">
						jQuery(document).ready( function($) {
							$(document).on('click', '.am-notification-<?php echo $notification->ID; ?> button.notice-dismiss', function( event ) {
								$.post( ajaxurl, {
									action: 'am_notification_dismiss',
									notification_id: '<?php echo $notification->ID; ?>'
								});
							});
						});
					</script>
				<?php }
			}
		}

		/**
		 * Validate the notifications before displaying them.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $plugin_notifications An array of plugin notifications.
		 * @return array                       A filtered array of plugin notifications.
		 */
		public function validate_notifications( $plugin_notifications ) {
			global $pagenow;
			foreach ( $plugin_notifications as $key => $notification ) {
				// Location validation.
				$location = (array) json_decode( get_post_meta( $notification->ID, 'location', true ) );
				$continue = false;
				if ( ! in_array( 'everywhere', $location ) ) {
					if ( in_array( 'index.php', $location ) && 'index.php' == $pagenow ) {
						$continue = true;
					}

					if ( in_array( 'plugins.php', $location ) && 'plugins.php' == $pagenow ) {
						$continue = true;
					}

					if ( ! $continue ) {
						unset( $plugin_notifications[ $key ] );
					}
				}

				// Plugin validation (OR conditional)
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
				$continue = (string) wp_get_theme() == $theme;

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

			}

			return $plugin_notifications;
		}

		/**
		 * Dismiss the notification via AJAX.
		 *
		 * @since 1.0.0
		 */
		public function dismiss_notification() {
			$notification_id = intval( $_POST['notification_id'] );
			update_post_meta( $notification_id, 'viewed', 1 );
			die;
		}

	}
}