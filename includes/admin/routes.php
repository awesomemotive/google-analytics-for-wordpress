<?php
/**
 * Routes for VUE are registered here.
 *
 * @package monsterinsights
 */

/**
 * Class MonsterInsights_Rest_Routes
 */
class MonsterInsights_Rest_Routes {

	/**
	 * MonsterInsights_Rest_Routes constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_monsterinsights_vue_get_license', array( $this, 'get_license' ) );
		add_action( 'wp_ajax_monsterinsights_vue_get_profile', array( $this, 'get_profile' ) );
		add_action( 'wp_ajax_monsterinsights_vue_get_settings', array( $this, 'get_settings' ) );
		add_action( 'wp_ajax_monsterinsights_vue_update_settings', array( $this, 'update_settings' ) );
		add_action( 'wp_ajax_monsterinsights_vue_get_addons', array( $this, 'get_addons' ) );
		add_action( 'wp_ajax_monsterinsights_update_manual_ua', array( $this, 'update_manual_ua' ) );

		add_action( 'admin_notices', array( $this, 'hide_old_notices' ), 0 );
	}

	/**
	 * Ajax handler for grabbing the license
	 */
	public function get_license() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		$site_license    = array(
			'key'         => MonsterInsights()->license->get_site_license_key(),
			'type'        => MonsterInsights()->license->get_site_license_type(),
			'is_disabled' => MonsterInsights()->license->site_license_disabled(),
			'is_expired'  => MonsterInsights()->license->site_license_expired(),
			'is_invalid'  => MonsterInsights()->license->site_license_invalid(),
		);
		$network_license = array(
			'key'         => MonsterInsights()->license->get_network_license_key(),
			'type'        => MonsterInsights()->license->get_network_license_type(),
			'is_disabled' => MonsterInsights()->license->network_license_disabled(),
			'is_expired'  => MonsterInsights()->license->network_license_expired(),
			'is_invalid'  => MonsterInsights()->license->network_license_disabled(),
		);

		wp_send_json( array(
			'site'    => $site_license,
			'network' => $network_license,
		) );

	}

	/**
	 * Ajax handler for grabbing the license
	 */
	public function get_profile() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		wp_send_json( array(
			'ua'                => MonsterInsights()->auth->get_ua(),
			'viewname'          => MonsterInsights()->auth->get_viewname(),
			'manual_ua'         => MonsterInsights()->auth->get_manual_ua(),
			'network_ua'        => MonsterInsights()->auth->get_network_ua(),
			'network_viewname'  => MonsterInsights()->auth->get_network_viewname(),
			'network_manual_ua' => MonsterInsights()->auth->get_network_manual_ua(),
		) );

	}

	/**
	 * Ajax handler for grabbing the license
	 */
	public function get_settings() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		$options = monsterinsights_get_options();

		// Array fields are needed even if empty.
		$array_fields = array( 'view_reports', 'save_settings', 'ignore_users' );
		foreach ( $array_fields as $array_field ) {
			if ( ! isset( $options[ $array_field ] ) ) {
				$options[ $array_field ] = array();
			}
		}
		if ( isset( $options['custom_code'] ) ) {
			$options['custom_code'] = stripslashes( $options['custom_code'] );
		}

		wp_send_json( $options );

	}

	/**
	 * Ajax handler for grabbing the license
	 */
	public function update_settings() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( isset( $_POST['setting'] ) ) {
			$setting = sanitize_text_field( wp_unslash( $_POST['setting'] ) );
			if ( isset( $_POST['value'] ) ) {
				$value = $this->handle_sanitization( $setting, $_POST['value'] );
				monsterinsights_update_option( $setting, $value );
			} else {
				monsterinsights_update_option( $setting, false );
			}
		}

		wp_send_json_success();

	}

	/**
	 * Sanitization specific to each field.
	 *
	 * @param string $field The key of the field to sanitize.
	 * @param string $value The value of the field to sanitize.
	 *
	 * @return mixed The sanitized input.
	 */
	private function handle_sanitization( $field, $value ) {

		$value = wp_unslash( $value );

		// Textarea fields.
		$textarea_fields = array(
			'custom_code',
		);

		if ( in_array( $field, $textarea_fields, true ) ) {
			if ( function_exists( 'sanitize_textarea_field' ) ) {
				return sanitize_textarea_field( $value );
			} else {
				return wp_kses( $value, array() );
			}
		}

		$array_value = json_decode( $value, true );
		if ( is_array( $array_value ) ) {
			$value = $array_value;
			// Don't save empty values.
			foreach ( $value as $key => $item ) {
				if ( is_array( $item ) ) {
					$empty = true;
					foreach ( $item as $item_value ) {
						if ( ! empty( $item_value ) ) {
							$empty = false;
						}
					}
					if ( $empty ) {
						unset( $value[ $key ] );
					}
				}
			}

			// Reset array keys because JavaScript can't handle arrays with non-sequential keys.
			$value = array_values( $value );

			return $value;
		}

		return sanitize_text_field( $value );

	}

	/**
	 * Return the state of the addons ( installed, activated )
	 */
	public function get_addons() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		$addons_data       = monsterinsights_get_addons();
		$parsed_addons     = array();
		$installed_plugins = get_plugins();

		foreach ( $addons_data as $addons_type => $addons ) {
			foreach ( $addons as $addon ) {
				$slug      = 'monsterinsights-' . $addon->slug;
				if ( 'monsterinsights-ecommerce' === $slug ) {
					$addon = $this->get_addon( $installed_plugins, $addons_type, $addon, $slug );
					if ( empty( $addon->installed ) ) {
						$slug = 'ga-ecommerce';
						$addon = $this->get_addon( $installed_plugins, $addons_type, $addon, $slug );
					}
				} else {
					$addon = $this->get_addon( $installed_plugins, $addons_type, $addon, $slug );
				}
				$parsed_addons[ $addon->slug ] = $addon;
			}
		}

		// Include data about the plugins needed by some addons ( WooCommerce, EDD, Google AMP, CookieBot, etc ).
		// WooCommerce.
		$parsed_addons['woocommerce'] = array(
			'active' => class_exists( 'WooCommerce' ),
		);
		// Edd.
		$parsed_addons['easy_digital_downloads'] = array(
			'active' => class_exists( 'Easy_Digital_Downloads' ),
		);
		// MemberPress.
		$parsed_addons['memberpress'] = array(
			'active' => defined( 'MEPR_VERSION' ) && version_compare( MEPR_VERSION, '1.3.43', '>' ),
		);
		// Cookiebot.
		$parsed_addons['cookiebot'] = array(
			'active' => function_exists( 'cookiebot_active' ) && cookiebot_active(),
		);
		// Cookie Notice.
		$parsed_addons['cookie_notice'] = array(
			'active' => class_exists( 'Cookie_Notice' ),
		);
		// Fb Instant Articles.
		$parsed_addons['instant_articles'] = array(
			'active' => defined( 'IA_PLUGIN_VERSION' ) && version_compare( IA_PLUGIN_VERSION, '3.3.4', '>' ),
		);
		// Google AMP.
		$parsed_addons['google_amp'] = array(
			'active' => defined( 'AMP__FILE__' ),
		);
		// WPForms.
		$parsed_addons['wpforms'] = array(
			'active' => function_exists( 'wpforms' ),
		);
		// Gravity Forms.
		$parsed_addons['gravity_forms'] = array(
			'active' => class_exists( 'GFCommon' ),
		);
		// Formidable Forms.
		$parsed_addons['formidable_forms'] = array(
			'active' => class_exists( 'FrmHooksController' ),
		);

		wp_send_json( $parsed_addons );
	}

	public function get_addon( $installed_plugins, $addons_type, $addon, $slug ) {
		$active    = false;
		$installed = false;
		$plugin_basename = monsterinsights_get_plugin_basename_from_slug( $slug );

		if ( isset( $installed_plugins[ $plugin_basename ] ) ) {
			$installed = true;
			$ms_active = is_plugin_active_for_network( $plugin_basename );
			$ss_active = is_plugin_active( $plugin_basename );

			if ( is_multisite() && is_network_admin() ) {
				$active = is_plugin_active_for_network( $plugin_basename );
			} else {
				$active = is_plugin_active( $plugin_basename );
			}
		}
		if ( empty( $addon->url ) ) {
			$addon->url = '';
		}

		$addon->type                   = $addons_type;
		$addon->installed              = $installed;
		$addon->active                 = $active;
		$addon->basename               = $plugin_basename;
		return $addon;
	}

	/**
	 * Use custom notices in the Vue app on the Settings screen.
	 */
	public function hide_old_notices() {

		$screen = get_current_screen();
		// Bail if we're not on a MonsterInsights screen.
		if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
			return;
		}

		// Hide admin notices on the settings screen.
		if ( in_array( $screen->id, array(
			'insights_page_monsterinsights_settings',
			'toplevel_page_monsterinsights_settings',
			'toplevel_page_monsterinsights_network-network',
			'insights_page_monsterinsights_network',
		), true ) ) {
			remove_all_actions( 'admin_notices' );
		}

	}

	/**
	 * Update manual ua.
	 */
	public function update_manual_ua() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		$manual_ua_code     = isset( $_POST['manual_ua_code'] ) ? sanitize_text_field( wp_unslash( $_POST['manual_ua_code'] ) ) : '';
		$manual_ua_code     = monsterinsights_is_valid_ua( $manual_ua_code ); // Also sanitizes the string.
		$manual_ua_code_old = MonsterInsights()->auth->get_manual_ua();
		if ( ! empty( $_REQUEST['isnetwork'] ) && sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) ) {
			define( 'WP_NETWORK_ADMIN', true );
		}

		if ( $manual_ua_code && $manual_ua_code_old && $manual_ua_code_old === $manual_ua_code ) {
			// Same code we had before
			// Do nothing.
			wp_send_json_success();
		} else if ( $manual_ua_code && $manual_ua_code_old && $manual_ua_code_old !== $manual_ua_code ) {
			// Different UA code.
			if ( is_network_admin() ) {
				MonsterInsights()->auth->set_network_manual_ua( $manual_ua_code );
			} else {
				MonsterInsights()->auth->set_manual_ua( $manual_ua_code );
			}
		} else if ( $manual_ua_code && empty( $manual_ua_code_old ) ) {
			// Move to manual.
			if ( is_network_admin() ) {
				MonsterInsights()->auth->set_network_manual_ua( $manual_ua_code );
			} else {
				MonsterInsights()->auth->set_manual_ua( $manual_ua_code );
			}
		} else if ( empty( $manual_ua_code ) && $manual_ua_code_old ) {
			// Deleted manual.
			if ( is_network_admin() ) {
				MonsterInsights()->auth->delete_network_manual_ua();
			} else {
				MonsterInsights()->auth->delete_manual_ua();
			}
		} else if ( isset( $_POST['manual_ua_code'] ) && empty( $manual_ua_code ) ) {
			wp_send_json_error();
		}

		wp_send_json_success();
	}
}
