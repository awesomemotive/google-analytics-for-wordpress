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
		add_action( 'wp_ajax_monsterinsights_vue_update_settings_bulk', array( $this, 'update_settings_bulk' ) );
		add_action( 'wp_ajax_monsterinsights_vue_get_addons', array( $this, 'get_addons' ) );
		add_action( 'wp_ajax_monsterinsights_update_manual_v4', array( $this, 'update_manual_v4' ) );
		add_action( 'wp_ajax_monsterinsights_update_measurement_protocol_secret', array(
			$this,
			'update_measurement_protocol_secret'
		) );
		add_action( 'wp_ajax_monsterinsights_vue_get_report_data', array( $this, 'get_report_data' ) );
		add_action( 'wp_ajax_monsterinsights_vue_install_plugin', array( $this, 'install_plugin' ) );
		add_action( 'wp_ajax_monsterinsights_vue_notice_status', array( $this, 'get_notice_status' ) );
		add_action( 'wp_ajax_monsterinsights_vue_notice_dismiss', array( $this, 'dismiss_notice' ) );
		add_action( 'wp_ajax_monsterinsights_vue_grab_popular_posts_report', array(
			$this,
			'check_popular_posts_report'
		) );
		add_action( 'wp_ajax_monsterinsights_vue_popular_posts_update_theme_setting', array(
			$this,
			'update_popular_posts_theme_setting'
		) );

		add_action( 'wp_ajax_monsterinsights_get_posts', array( $this, 'get_posts' ) );

		// Search for taxonomies.
		add_action( 'wp_ajax_monsterinsights_get_terms', array( $this, 'get_taxonomy_terms' ) );

		add_action( 'wp_ajax_monsterinsights_get_post_types', array( $this, 'get_post_types' ) );

		add_action( 'wp_ajax_monsterinsights_handle_settings_import', array( $this, 'handle_settings_import' ) );

		add_action( 'admin_notices', array( $this, 'hide_old_notices' ), 0 );

		add_action( 'wp_ajax_monsterinsights_vue_dismiss_first_time_notice', array(
			$this,
			'dismiss_first_time_notice'
		) );
	}

	/**
	 * Ajax handler for grabbing the license
	 */
	public function get_license() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) || ! monsterinsights_is_pro_version() ) {
			return;
		}

		$site_license    = array(
			'key'         => MonsterInsights()->license->get_site_license_key(),
			'type'        => MonsterInsights()->license->get_site_license_type(),
			'is_disabled' => MonsterInsights()->license->site_license_disabled(),
			'is_expired'  => MonsterInsights()->license->site_license_expired(),
			'expiry_date' => MonsterInsights()->license->get_license_expiry_date(),
			'is_invalid'  => MonsterInsights()->license->site_license_invalid(),
			'is_agency'   => MonsterInsights()->license->site_is_agency(),
		);
		$network_license = array(
			'key'         => MonsterInsights()->license->get_network_license_key(),
			'type'        => MonsterInsights()->license->get_network_license_type(),
			'is_disabled' => MonsterInsights()->license->network_license_disabled(),
			'is_expired'  => MonsterInsights()->license->network_license_expired(),
			'expiry_date' => MonsterInsights()->license->get_license_expiry_date(),
			'is_invalid'  => MonsterInsights()->license->network_license_disabled(),
			'is_agency'   => MonsterInsights()->license->network_is_agency(),
		);

		wp_send_json( array(
			'site'    => $site_license,
			'network' => $network_license,
		) );

	}

	/**
	 * Ajax handler for grabbing the current authenticated profile.
	 */
	public function get_profile() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		$auth = MonsterInsights()->auth;

		wp_send_json( array(
			'v4'                                  => $auth->get_v4_id(),
			'viewname'                            => $auth->get_viewname(),
			'manual_v4'                           => $auth->get_manual_v4_id(),
			'measurement_protocol_secret'         => $auth->get_measurement_protocol_secret(),
			'network_v4'                          => $auth->get_network_v4_id(),
			'network_viewname'                    => $auth->get_network_viewname(),
			'network_manual_v4'                   => $auth->get_network_manual_v4_id(),
			'network_measurement_protocol_secret' => $auth->get_network_measurement_protocol_secret(),
		) );

	}

	/**
	 * Ajax handler for grabbing the settings.
	 */
	public function get_settings() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
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

		//add email summaries options
		if ( monsterinsights_is_pro_version() ) {
			$default_email = array(
				'email' => get_option( 'admin_email' ),
			);

			if ( ! isset( $options['email_summaries'] ) ) {
				$options['email_summaries'] = 'on';
			}

			if ( ! isset( $options['summaries_email_addresses'] ) ) {
				$options['summaries_email_addresses'] = array(
					$default_email,
				);
			}

			if ( ! isset( $options['summaries_html_template'] ) ) {
				$options['summaries_html_template'] = 'yes';
			}


			if ( ! isset( $options['summaries_carbon_copy'] ) ) {
				$options['summaries_carbon_copy'] = 'no';
			}


			if ( ! isset( $options['summaries_header_image'] ) ) {
				$options['summaries_header_image'] = '';
			}

			if ( ! isset( $options['local_gtag_file_modified_at'] ) ) {
				$options['local_gtag_file_modified_at'] = '';
			}
		}

		wp_send_json( $options );

	}

	/**
	 * Ajax handler for updating the settings.
	 */
	public function update_settings() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( isset( $_POST['setting'] ) ) {
			$setting = sanitize_text_field( wp_unslash( $_POST['setting'] ) );
			if ( isset( $_POST['value'] ) ) {
				$value = $this->handle_sanitization( $setting, $_POST['value'] ); // phpcs:ignore
				monsterinsights_update_option( $setting, $value );
				do_action( 'monsterinsights_after_update_settings', $setting, $value );
			} else {
				monsterinsights_update_option( $setting, false );
				do_action( 'monsterinsights_after_update_settings', $setting, false );
			}
		}

		wp_send_json_success();

	}

	/**
	 * Ajax handler for updating the settings.
	 */
	public function update_settings_bulk() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( isset( $_POST['settings'] ) ) {
			$settings = json_decode( sanitize_text_field( wp_unslash( $_POST['settings'] ) ), true );
			foreach ( $settings as $setting => $value ) {
				$value = $this->handle_sanitization( $setting, $value );
				monsterinsights_update_option( $setting, $value );
				do_action( 'monsterinsights_after_update_settings', $setting, $value );
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
		$textarea_fields = array();

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
		global $current_user;
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			return;
		}

		if ( isset( $_POST['network'] ) && intval( $_POST['network'] ) > 0 ) {
			define( 'WP_NETWORK_ADMIN', true );
		}

		$addons_data       = monsterinsights_get_addons();
		$parsed_addons     = array();
		$installed_plugins = get_plugins();

		if ( ! is_array( $addons_data ) ) {
			$addons_data = array();
		}

		foreach ( $addons_data as $addons_type => $addons ) {
			foreach ( $addons as $addon ) {
				$slug = 'monsterinsights-' . $addon->slug;
				if ( 'monsterinsights-ecommerce' === $slug && 'm' === $slug[0] ) {
					$addon = $this->get_addon( $installed_plugins, $addons_type, $addon, $slug );
					if ( empty( $addon->installed ) ) {
						$slug  = 'ga-ecommerce';
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
			'active'    => class_exists( 'Easy_Digital_Downloads' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-edd.png',
			'title'     => 'Easy Digital Downloads',
			'excerpt'   => __( 'The best WordPress eCommerce plugin for selling digital downloads. Start selling eBooks, software, music, digital art, and more within minutes. Accept payments, manage subscriptions, advanced access control, and more.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'easy-digital-downloads/easy-digital-downloads.php', $installed_plugins ) || array_key_exists( 'easy-digital-downloads-pro/easy-digital-downloads.php', $installed_plugins ),
			'basename'  => array_key_exists( 'easy-digital-downloads-pro/easy-digital-downloads.php', $installed_plugins ) ? 'easy-digital-downloads-pro/easy-digital-downloads.php' : 'easy-digital-downloads/easy-digital-downloads.php',
			'slug'      => 'easy-digital-downloads',
			'settings'  => admin_url( 'edit.php?post_type=download' ),
		);
		// Ajax activation works only if the key is the same with the slug. keeping the older one for backwards compatibility
		$parsed_addons['easy-digital-downloads'] = $parsed_addons['easy_digital_downloads'];
		// MemberPress.
		$parsed_addons['memberpress'] = array(
			'active' => defined( 'MEPR_VERSION' ) && version_compare( MEPR_VERSION, '1.3.43', '>' ),
		);
		// MemberMouse.
		$parsed_addons['membermouse'] = array(
			'active' => class_exists( 'MemberMouse' ),
		);
		// LifterLMS.
		$parsed_addons['lifterlms'] = array(
			'active' => function_exists( 'LLMS' ) && version_compare( LLMS()->version, '3.32.0', '>=' ),
		);
		// Restrict Content Pro.
		$parsed_addons['rcp'] = array(
			'active' => class_exists( 'Restrict_Content_Pro' ) && version_compare( RCP_PLUGIN_VERSION, '3.5.4', '>=' ),
		);
		// GiveWP.
		$parsed_addons['givewp'] = array(
			'active' => function_exists( 'Give' ),
		);

		// Charitable WP.
		$parsed_addons['charitable'] = array(
			'active'    => class_exists( 'Charitable' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-charitable.png',
			'title'     => 'Charitable',
			'excerpt'   => __('Top-rated WordPress donation and fundraising plugin. Over 10,000+ non-profit organizations and website owners use Charitable to create fundraising campaigns and raise more money online.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('charitable/charitable.php', $installed_plugins),
			'basename'  => 'charitable/charitable.php',
			'slug'      => 'charitable',
			'settings'  => admin_url('admin.php?page=charitable'),
		);

		// WPCode
		$parsed_addons['wpcode'] = array(
			'active'    => function_exists( 'WPCode' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-wpcode.png',
			'title'     => 'WPCode',
			'excerpt'   => __('Future proof your WordPress customizations with the most popular code snippet management plugin for WordPress. Trusted by over 1,500,000+ websites for easily adding code to WordPress right from the admin area.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('insert-headers-and-footers/ihaf.php', $installed_plugins),
			'basename'  => 'insert-headers-and-footers/ihaf.php',
			'slug'      => 'insert-headers-and-footers',
			'settings'  => admin_url('admin.php?page=wpcode-settings'),
		);

		// Duplicator
		$parsed_addons['duplicator'] = array(
			'active'    => defined( 'DUPLICATOR_VERSION' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-duplicator.png',
			'title'     => 'Duplicator',
			'excerpt'   => __('Leading WordPress backup & site migration plugin. Over 1,500,000+ smart website owners use Duplicator to make reliable and secure WordPress backups to protect their websites. It also makes website migration really easy.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('duplicator/duplicator.php', $installed_plugins),
			'basename'  => 'duplicator/duplicator.php',
			'slug'      => 'duplicator',
			'settings'  => admin_url('admin.php?page=duplicator-settings'),
		);

		// WishList Member.
		$parsed_addons['wishlist_member'] = array(
			'active' => function_exists( 'wishlistmember_instance' ),
		);
		// GiveWP Analytics.
		$parsed_addons['givewp_google_analytics'] = array(
			'active' => function_exists( 'Give_Google_Analytics' ),
		);
		// Cookiebot.
		$parsed_addons['cookiebot'] = array(
			'active' => function_exists( 'monsterinsights_is_cookiebot_active' ) && monsterinsights_is_cookiebot_active(),
		);
		// Cookie Notice.
		$parsed_addons['cookie_notice'] = array(
			'active' => class_exists( 'Cookie_Notice' ),
		);
		// Complianz.
		$parsed_addons['complianz'] = array(
			'active' => defined( 'cmplz_plugin' ) || defined( 'cmplz_premium' ),
		);
		// Cookie Yes
		$parsed_addons['cookie_yes'] = array(
			'active' => defined( 'CLI_SETTINGS_FIELD' ),
		);
		// Google AMP.
		$parsed_addons['google_amp'] = array(
			'active' => defined( 'AMP__FILE__' ),
		);
		// Yoast SEO.
		$parsed_addons['yoast_seo'] = array(
			'active' => defined( 'WPSEO_VERSION' ),
		);
		// EasyAffiliate.
		$parsed_addons['easy_affiliate'] = array(
			'active' => defined( 'ESAF_EDITION' ),
		);

		// AffiliateWP
		$parsed_addons['affiliate-wp'] = array(
			'active'    => class_exists('AffiliateWP_Core_Requirements_Check'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-affiliate-wp.png',
			'title'     => 'AffiliateWP',
			'excerpt'   => __('The #1 affiliate management plugin for WordPress. Easily create an affiliate program for your eCommerce store or membership site within minutes and start growing your sales with the power of referral marketing.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('affiliate-wp/affiliate-wp.php', $installed_plugins),
			'basename'  => 'affiliate-wp/affiliate-wp.php',
			'slug'      => 'affiliate-wp',
			'settings'  => admin_url('admin.php?page=affiliate-wp-settings'),
			'redirect'  => 'https://affiliatewp.com',
		);

		// WP Simple Pay
		$parsed_addons['wpsimplepay'] = array(
			'active'    => defined('SIMPLE_PAY_VERSION'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-wp-simple-pay.png',
			'title'     => 'WP Simple Pay',
			'excerpt'   => __('The #1 Stripe payments plugin for WordPress. Start accepting one-time and recurring payments on your WordPress site without setting up a shopping cart. No code required.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('stripe/stripe-checkout.php', $installed_plugins),
			'basename'  => 'stripe/stripe-checkout.php',
			'slug'      => 'stripe',
			'settings'  => admin_url('edit.php?post_type=simple-pay&page=simpay_settings'),
		);

		// Sugar Calendar
		$parsed_addons['sugarcalendar'] = array(
			'active'    => class_exists('Sugar_Calendar\\Requirements_Check'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-sugar-calendar.png',
			'title'     => 'Sugar Calendar',
			'excerpt'   => __('A simple & powerful event calendar plugin for WordPress that comes with all the event management features including payments, scheduling, timezones, ticketing, recurring events, and more.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('sugar-calendar-lite/sugar-calendar-lite.php', $installed_plugins),
			'basename'  => 'sugar-calendar-lite/sugar-calendar-lite.php',
			'slug'      => 'sugar-calendar-lite',
			'settings'  => admin_url('admin.php?page=sugar-calendar'),
		);

		// WPForms.
		$parsed_addons['wpforms-lite'] = array(
			'active'    => function_exists( 'wpforms' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-wpforms.png',
			'title'     => 'WPForms',
			'excerpt'   => __( 'The best drag & drop WordPress form builder. Easily create beautiful contact forms, surveys, payment forms, and more with our 1000+ form templates. Trusted by over 6 million websites as the best forms plugin.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'wpforms-lite/wpforms.php', $installed_plugins ) || array_key_exists( 'wpforms/wpforms.php', $installed_plugins ),
			'basename'  => 'wpforms-lite/wpforms.php',
			'slug'      => 'wpforms-lite',
			'settings'  => admin_url( 'admin.php?page=wpforms-overview' ),
		);

		// UserFeedback.
		$parsed_addons['userfeedback-lite'] = array(
			'active'    => function_exists( 'userfeedback' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugin-userfeedback.png',
			'title'     => 'UserFeedback',
			'excerpt'   => __( 'Ask visitors questions about how they use your website or what features can make you more money.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'userfeedback-lite/userfeedback.php', $installed_plugins ) || array_key_exists( 'userfeedback/userfeedback.php', $installed_plugins ),
			'basename'  => 'userfeedback-lite/userfeedback.php',
			'slug'      => 'userfeedback-lite',
			'settings'  => admin_url( 'admin.php?page=userfeedback_settings' ),
		);

		// AIOSEO.
		$parsed_addons['aioseo'] = array(
			'active'    => function_exists( 'aioseo' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-all-in-one-seo.png',
			'title'     => 'AIOSEO',
			'excerpt'   => __( 'The original WordPress SEO plugin and toolkit that improves your website’s search rankings. Comes with all the SEO features like Local SEO, WooCommerce SEO, sitemaps, SEO optimizer, schema, and more.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'all-in-one-seo-pack/all_in_one_seo_pack.php', $installed_plugins ) || array_key_exists( 'all-in-one-seo-pack-pro/all_in_one_seo_pack.php', $installed_plugins ),
			'basename'  => ( monsterinsights_is_installed_aioseo_pro() ) ? 'all-in-one-seo-pack-pro/all_in_one_seo_pack.php' : 'all-in-one-seo-pack/all_in_one_seo_pack.php',
			'slug'      => 'all-in-one-seo-pack',
			'settings'  => admin_url( 'admin.php?page=aioseo' ),
		);

		// FunnelKit Stripe Woo Payment Gateway.
		$parsed_addons['funnelkit-stripe-woo-payment-gateway'] = array(
			'active'    => class_exists( 'FKWCS_Gateway_Stripe' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-funnelkit-stripe-woo-payment-gateway.png',
			'title'     => 'Stripe Payment Gateway for WooCommerce',
			'excerpt'   => __( 'Stripe Payment Gateway for WooCommerce is an integrated solution that lets you accept payments on your online store for web and mobile.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'funnelkit-stripe-woo-payment-gateway/funnelkit-stripe-woo-payment-gateway.php', $installed_plugins ),
			'basename'  => 'funnelkit-stripe-woo-payment-gateway/funnelkit-stripe-woo-payment-gateway.php',
			'slug'      => 'funnelkit-stripe-woo-payment-gateway',
			'settings'  => admin_url( 'admin.php?page=wc-settings&tab=fkwcs_api_settings' ),
		);

		// Use the plugin dir name as the array key since AJAX activation in add-ons page won't work.
		$parsed_addons['all-in-one-seo-pack'] = $parsed_addons['aioseo'];

		// OptinMonster.
		$parsed_addons['optinmonster'] = array(
			'active'    => class_exists( 'OMAPI' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-om.png',
			'title'     => 'OptinMonster',
			'excerpt'   => __( 'Instantly get more subscribers, leads, and sales with the #1 conversion optimization toolkit. Create high converting popups, announcement bars, spin a wheel, and more with smart targeting and personalization.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'optinmonster/optin-monster-wp-api.php', $installed_plugins ),
			'basename'  => 'optinmonster/optin-monster-wp-api.php',
			'slug'      => 'optinmonster',
			'settings'  => admin_url( 'admin.php?page=optin-monster-dashboard' ),
		);
		// WP Mail Smtp.
		$parsed_addons['wp-mail-smtp'] = array(
			'active'    => function_exists( 'wp_mail_smtp' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-smtp.png',
			'title'     => 'WP Mail SMTP',
			'excerpt'   => __( 'Improve your WordPress email deliverability and make sure that your website emails reach user’s inbox with the #1 SMTP plugin for WordPress. Over 3 million websites use it to fix WordPress email issues.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'wp-mail-smtp/wp_mail_smtp.php', $installed_plugins ),
			'basename'  => 'wp-mail-smtp/wp_mail_smtp.php',
			'slug'      => 'wp-mail-smtp',
		);
		// SeedProd.
		$parsed_addons['coming-soon'] = array(
			'active'    => defined( 'SEEDPROD_VERSION' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-seedprod.png',
			'title'     => 'SeedProd',
			'excerpt'   => __( 'The fastest drag & drop landing page builder for WordPress. Create custom landing pages without writing code, connect them with your CRM, collect subscribers, and grow your audience. Trusted by 1 million sites.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'coming-soon/coming-soon.php', $installed_plugins ),
			'basename'  => 'coming-soon/coming-soon.php',
			'slug'      => 'coming-soon',
			'settings'  => admin_url( 'admin.php?page=seedprod_lite' ),
		);
		// RafflePress
		$parsed_addons['rafflepress'] = array(
			'active'    => defined('RAFFLEPRESS_VERSION'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-rafflepress.png',
			'title'     => 'RafflePress',
			'excerpt'   => __( 'Turn your website visitors into brand ambassadors! Easily grow your email list, website traffic, and social media followers with the most powerful giveaways & contests plugin for WordPress.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'rafflepress/rafflepress.php', $installed_plugins ),
			'basename'  => 'rafflepress/rafflepress.php',
			'slug'      => 'rafflepress',
			'settings'  => admin_url( 'admin.php?page=rafflepress_lite' ),
		);
		// TrustPulse
		$parsed_addons['trustpulse'] = array(
			'active'    => defined('TRUSTPULSE_PLUGIN_VERSION'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-trustpulse.png',
			'title'     => 'TrustPulse',
			'excerpt'   => __( 'Boost your sales and conversions by up to 15% with real-time social proof notifications. TrustPulse helps you show live user activity and purchases to help convince other users to purchase.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'trustpulse-api/trustpulse.php', $installed_plugins ),
			'basename'  => 'trustpulse-api/trustpulse.php',
			'slug'      => 'trustpulse-api',
		);

		$parsed_addons['trustpulse-api'] = $parsed_addons['trustpulse'];

		// SearchWP
		$parsed_addons['searchwp'] = array(
			'active'    => defined('SEARCHWP_LIVE_SEARCH_VERSION'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-searchwp.png',
			'title'     => 'SearchWP',
			'excerpt'   => __('The most advanced WordPress search plugin. Customize your WordPress search algorithm, reorder search results, track search metrics, and everything you need to leverage search to grow your business.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('searchwp-live-ajax-search/searchwp-live-ajax-search.php', $installed_plugins),
			'basename'  => 'searchwp-live-ajax-search/searchwp-live-ajax-search.php',
			'slug'      => 'searchwp-live-ajax-search',
			'settings'  => admin_url('admin.php?page=searchwp-live-search'),
		);

		// Smash Balloon (Instagram)
		$parsed_addons['instagram-feed'] = array(
			'active'    => defined( 'SBIVER' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-sb-instagram.png',
			'title'     => 'Smash Balloon Instagram Feeds',
			'excerpt'   => __( 'Easily display Instagram content on your WordPress site without writing any code. Comes with multiple templates, ability to show content from multiple accounts, hashtags, and more. Trusted by 1 million websites.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'instagram-feed/instagram-feed.php', $installed_plugins ),
			'basename'  => 'instagram-feed/instagram-feed.php',
			'slug'      => 'instagram-feed',
			'settings'  => admin_url( 'admin.php?page=sb-instagram-feed' ),
		);

		$parsed_addons['smash-balloon-instagram'] = $parsed_addons['instagram-feed'];

		// Smash Balloon (Facebook)
		$parsed_addons['custom-facebook-feed'] = array(
			'active'    => defined( 'CFFVER' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-sb-facebook.png',
			'title'     => 'Smash Balloon Facebook Feeds',
			'excerpt'   => __( 'Easily display Facebook content on your WordPress site without writing any code. Comes with multiple templates, ability to embed albums, group content, reviews, live videos, comments, and reactions.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'custom-facebook-feed/custom-facebook-feed.php', $installed_plugins ),
			'basename'  => 'custom-facebook-feed/custom-facebook-feed.php',
			'slug'      => 'custom-facebook-feed',
			'settings'  => admin_url( 'admin.php?page=cff-feed-builder' ),
		);

		$parsed_addons['smash-balloon-facebook'] = $parsed_addons['custom-facebook-feed'];

		// Smash Balloon (YouTube)
		$parsed_addons['smash-balloon-youtube'] = array(
			'active'    => defined('SBYVER'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-sb-youtube.png',
			'title'     => 'Smash Balloon YouTube Feeds',
			'excerpt'   => __('Easily display YouTube videos on your WordPress site without writing any code. Comes with multiple layouts, ability to embed live streams, video filtering, ability to combine multiple channel videos, and more.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('feeds-for-youtube/youtube-feed.php', $installed_plugins),
			'basename'  => 'feeds-for-youtube/youtube-feed.php',
			'slug'      => 'feeds-for-youtube',
			'settings'  => admin_url('admin.php?page=sby-feed-builder'),
		);

		// In Year in Review we need the exact slug for the key in order to make the AJAX activation work.
		$parsed_addons['feeds-for-youtube'] = $parsed_addons['smash-balloon-youtube'];

		// Smash Balloon (Twitter)
		$parsed_addons['smash-balloon-twitter'] = array(
			'active'    => defined('CTF_VERSION'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-sb-twitter.png',
			'title'     => 'Smash Balloon Twitter Feeds',
			'excerpt'   => __('Easily display Twitter content in WordPress without writing any code. Comes with multiple layouts, ability to combine multiple Twitter feeds, Twitter card support, tweet moderation, and more.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('custom-twitter-feeds/custom-twitter-feed.php', $installed_plugins),
			'basename'  => 'custom-twitter-feeds/custom-twitter-feed.php',
			'slug'      => 'custom-twitter-feeds',
			'settings'  => admin_url('admin.php?page=ctf-feed-builder'),
		);

		// We need the key of the addon to be exactly the slug. We should deprecate `smash-balloon-twitter` next version.
		$parsed_addons['custom-twitter-feeds'] = $parsed_addons['smash-balloon-twitter'];

		// PushEngage
		$parsed_addons['pushengage'] = array(
			'active'    => defined('PUSHENGAGE_VERSION'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-pushengage.png',
			'title'     => 'PushEngage',
			'excerpt'   => __('Connect with your visitors after they leave your website with the leading web push notification software. Over 10,000+ businesses worldwide use PushEngage to send 15 billion notifications each month.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists( 'pushengage/main.php', $installed_plugins ),
			'basename'  => 'pushengage/main.php',
			'slug'      => 'pushengage',
		);

		// Uncanny Automator
		$parsed_addons['uncanny-automator'] = array(
			'active'    => function_exists('automator_get_recipe_id'),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-uncanny-automator.png',
			'title'     => 'Uncanny Automator',
			'excerpt'   => __('Automate everything with the #1 no-code Automation tool for WordPress.', 'google-analytics-for-wordpress'),
			'installed' => array_key_exists('uncanny-automator/uncanny-automator.php', $installed_plugins),
			'basename'  => 'uncanny-automator/uncanny-automator.php',
			'slug'      => 'uncanny-automator',
			'setup_complete' => class_exists( 'Uncanny_Automator\Api_Server' ) ? !! \Uncanny_Automator\Api_Server::is_automator_connected() : false,
			'wizard_url'     => admin_url( 'edit.php?post_type=uo-recipe&page=uncanny-automator-setup-wizard' ),
			'recipe_url'     => admin_url( 'post-new.php?post_type=uo-recipe' ),
		);

		// Pretty Links
		$parsed_addons['pretty-link'] = array(
			'active'    => class_exists( 'PrliBaseController' ),
			'icon'      => '',
			'title'     => 'Pretty Links',
			'excerpt'   => __( 'Pretty Links helps you shrink, beautify, track, manage and share any URL on or off of your WordPress website. Create links that look how you want using your own domain name!', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'pretty-link/pretty-link.php', $installed_plugins ),
			'basename'  => 'pretty-link/pretty-link.php',
			'slug'      => 'pretty-link',
			'settings'  => admin_url( 'edit.php?post_type=pretty-link' ),
		);
		// SearchWP
		$parsed_addons['searchwp-live-ajax-search'] = array(
			'active'    => defined( 'SEARCHWP_LIVE_SEARCH_VERSION' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugin-searchwp.svg',
			'title'     => 'SearchWP',
			'excerpt'   => __( 'The most advanced WordPress search plugin. Customize your WordPress search algorithm, reorder search results, track search metrics, and everything you need to leverage search to grow your business.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'searchwp-live-ajax-search/searchwp-live-ajax-search.php', $installed_plugins ),
			'basename'  => 'searchwp-live-ajax-search/searchwp-live-ajax-search.php',
			'slug'      => 'searchwp-live-ajax-search',
			'settings'  => admin_url( 'admin.php?page=searchwp-live-search' ),
		);
		// Sugar Calendar Lite
		$parsed_addons['sugar-calendar-lite'] = array(
			'active'    => class_exists( 'Sugar_Calendar\\Requirements_Check' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugin-sugar-calendar.svg',
			'title'     => 'Sugar Calendar',
			'excerpt'   => __( 'A simple & powerful event calendar plugin for WordPress that comes with all the event management features including payments, scheduling, timezones, ticketing, recurring events, and more.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'sugar-calendar-lite/sugar-calendar-lite.php', $installed_plugins ),
			'basename'  => 'sugar-calendar-lite/sugar-calendar-lite.php',
			'slug'      => 'sugar-calendar-lite',
			'settings'  => admin_url( 'admin.php?page=sugar-calendar' ),
		);
		// Charitable
		$parsed_addons['charitable'] = array(
			'active'    => class_exists( 'Charitable' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-charitable.png',
			'title'     => 'WP Charitable',
			'excerpt'   => __( 'Top-rated WordPress donation and fundraising plugin. Over 10,000+ non-profit organizations and website owners use Charitable to create fundraising campaigns and raise more money online.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'charitable/charitable.php', $installed_plugins ),
			'basename'  => 'charitable/charitable.php',
			'slug'      => 'charitable',
			'settings'  => admin_url( 'admin.php?page=charitable&install=true' ),
		);
		// WPCode
		$parsed_addons['insert-headers-and-footers'] = array(
			'active'    => class_exists( 'WPCode' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugin-wpcode.svg',
			'title'     => 'WPCode',
			'excerpt'   => __( 'Future proof your WordPress customizations with the most popular code snippet management plugin for WordPress. Trusted by over 1,500,000+ websites for easily adding code to WordPress right from the admin area.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'insert-headers-and-footers/ihaf.php', $installed_plugins ),
			'basename'  => 'insert-headers-and-footers/ihaf.php',
			'slug'      => 'insert-headers-and-footers',
			'settings'  => admin_url( 'admin.php?page=wpcode' ),
		);
		// Duplicator
		$parsed_addons['duplicator'] = array(
			'active'    => class_exists( 'DuplicatorPhpVersionCheck' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-duplicator.png',
			'title'     => 'Duplicator',
			'excerpt'   => __( 'Leading WordPress backup & site migration plugin. Over 1,500,000+ smart website owners use Duplicator to make reliable and secure WordPress backups to protect their websites. It also makes website migration really easy.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'duplicator/duplicator.php', $installed_plugins ),
			'basename'  => 'duplicator/duplicator.php',
			'slug'      => 'duplicator',
			'settings'  => admin_url( 'admin.php?page=duplicator' ),
		);
		// WP Simple Pay
		$parsed_addons['stripe'] = array(
			'active'    => defined( 'SIMPLE_PAY_MAIN_FILE' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugin-simple-pay.svg',
			'title'     => 'WP Simple Pay',
			'excerpt'   => __( 'Start accepting one-time and recurring payments on your WordPress site without setting up a shopping cart. No code required.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'stripe/stripe-checkout.php', $installed_plugins ),
			'basename'  => 'stripe/stripe-checkout.php',
			'slug'      => 'stripe',
			'settings'  => admin_url( 'edit.php?post_type=simple-pay&page=simpay_settings&tab=general' ),
		);
		if ( function_exists( 'WC' ) ) {
			// Advanced Coupons
			$parsed_addons['advancedcoupons'] = array(
				'active'    => class_exists( 'ACFWF' ),
				'icon'      => '',
				'title'     => 'Advanced Coupons',
				'excerpt'   => __( 'Advanced Coupons for WooCommerce (Free Version) gives WooCommerce store owners extra coupon features so they can market their stores better.', 'google-analytics-for-wordpress' ),
				'installed' => array_key_exists( 'advanced-coupons-for-woocommerce-free/advanced-coupons-for-woocommerce-free.php', $installed_plugins ),
				'basename'  => 'advanced-coupons-for-woocommerce-free/advanced-coupons-for-woocommerce-free.php',
				'slug'      => 'advanced-coupons-for-woocommerce-free',
				'settings'  => admin_url( 'edit.php?post_type=shop_coupon&acfw' ),
			);
		}

		// UserFeedback.
		$parsed_addons['userfeedback-lite'] = array(
			'active'    => function_exists( 'userfeedback' ),
			'icon'      => plugin_dir_url( MONSTERINSIGHTS_PLUGIN_FILE ) . 'assets/images/plugins/plugin-userfeedback.png',
			'title'     => 'UserFeedback',
			'excerpt'   => __( 'See what your analytics software isn’t telling you with powerful UserFeedback surveys.', 'google-analytics-for-wordpress' ),
			'installed' => array_key_exists( 'userfeedback-lite/userfeedback.php', $installed_plugins ) || array_key_exists( 'userfeedback/userfeedback.php', $installed_plugins ),
			'basename'  => 'userfeedback-lite/userfeedback.php',
			'slug'      => 'userfeedback-lite',
			'settings'  => admin_url( 'admin.php?page=userfeedback_onboarding' ),
			'surveys'  => admin_url( 'admin.php?page=userfeedback_surveys' ),
			'setup_complete'  => (get_option('userfeedback_onboarding_complete', 0) == 1),
		);

		// Gravity Forms.
		$parsed_addons['gravity_forms'] = array(
			'active' => class_exists( 'GFCommon' ),
		);
		// Formidable Forms.
		$parsed_addons['formidable_forms'] = array(
			'active' => class_exists( 'FrmHooksController' ),
		);
		// Manual UA Addon.
		if ( ! isset( $parsed_addons['manual_ua'] ) ) {
			$parsed_addons['manual_ua'] = array(
				'active' => class_exists( 'MonsterInsights_Manual_UA' ),
			);
		}

		$parsed_addons = apply_filters('monsterinsights_parsed_addons', $parsed_addons);

		wp_send_json( $parsed_addons );
	}

	/**
	 * Wrapper around the monsterinsights_get_addon function.
	 * Kept for backwards compatibility.
	 *
	 * @param $installed_plugins
	 * @param $addons_type
	 * @param $addon
	 * @param $slug
	 * @deprecated Use monsterinsights_get_addon instead.
	 * @return mixed
	 */
	public function get_addon( $installed_plugins, $addons_type, $addon, $slug ) {
		return monsterinsights_get_addon($installed_plugins, $addons_type, $addon, $slug);
	}

	/**
	 * Use custom notices in the Vue app on the Settings screen.
	 */
	public function hide_old_notices() {

		global $wp_version;
		if ( version_compare( $wp_version, '4.6', '<' ) ) {
			// remove_all_actions triggers an infinite loop on older versions.
			return;
		}

		$screen = get_current_screen();
		// Bail if we're not on a MonsterInsights screen.
		if ( empty( $screen->id ) || strpos( $screen->id, 'monsterinsights' ) === false ) {
			return;
		}

		// Hide admin notices on the settings screen.
		if ( monsterinsights_is_settings_page() ) {
			remove_all_actions( 'admin_notices' );
		}

	}

	/**
	 * Update manual v4.
	 */
	public function update_manual_v4() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		$manual_v4_code = isset( $_POST['manual_v4_code'] ) ? sanitize_text_field( wp_unslash( $_POST['manual_v4_code'] ) ) : '';
		$manual_v4_code = monsterinsights_is_valid_v4_id( $manual_v4_code ); // Also sanitizes the string.

		if ( ! empty( $_REQUEST['isnetwork'] ) && sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) ) {
			define( 'WP_NETWORK_ADMIN', true );
		}
		$manual_v4_code_old = is_network_admin() ? MonsterInsights()->auth->get_network_manual_v4_id() : MonsterInsights()->auth->get_manual_v4_id();

		if ( $manual_v4_code && $manual_v4_code_old && $manual_v4_code_old === $manual_v4_code ) {
			// Same code we had before
			// Do nothing.
			wp_send_json_success();
		} else if ( $manual_v4_code && $manual_v4_code_old && $manual_v4_code_old !== $manual_v4_code ) {
			// Different UA code.
			if ( is_network_admin() ) {
				MonsterInsights()->auth->set_network_manual_v4_id( $manual_v4_code );
			} else {
				MonsterInsights()->auth->set_manual_v4_id( $manual_v4_code );
			}
		} else if ( $manual_v4_code && empty( $manual_v4_code_old ) ) {
			// Move to manual.
			if ( is_network_admin() ) {
				MonsterInsights()->auth->set_network_manual_v4_id( $manual_v4_code );
			} else {
				MonsterInsights()->auth->set_manual_v4_id( $manual_v4_code );
			}
		} else if ( empty( $manual_v4_code ) && $manual_v4_code_old ) {
			// Deleted manual.
			if ( is_network_admin() ) {
				MonsterInsights()->auth->delete_network_manual_v4_id();
			} else {
				MonsterInsights()->auth->delete_manual_v4_id();
			}
		} else if ( isset( $_POST['manual_v4_code'] ) && empty( $manual_v4_code ) ) {
			wp_send_json_error( array(
				'v4_error' => 1,
				// Translators: link tag starts with url, link tag ends.
				'error'    => sprintf(
					__( 'Oops! Please enter a valid Google Analytics 4 Measurement ID. %1$sLearn how to find your Measurement ID%2$s.', 'google-analytics-for-wordpress' ),
					'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'invalid-manual-gav4-code', 'https://www.monsterinsights.com/docs/how-to-set-up-dual-tracking/' ) . '">',
					'</a>'
				),
			) );
		}

		wp_send_json_success();
	}

	public function update_measurement_protocol_secret() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) ) {
			define( 'WP_NETWORK_ADMIN', true );
		}

		$value = empty( $_REQUEST['value'] ) ? '' : sanitize_text_field( wp_unslash( $_REQUEST['value'] ) );

		$auth = MonsterInsights()->auth;

		if ( is_network_admin() ) {
			$auth->set_network_measurement_protocol_secret( $value );
		} else {
			$auth->set_measurement_protocol_secret( $value );
		}

		// Send API request to Relay
		// TODO: Remove when token automation API is ready
		$api = new MonsterInsights_API_Request( 'auth/mp-token/', 'POST' );
		$api->set_additional_data( array(
			'mp_token' => $value,
		) );

		// Even if there's an error from Relay, we can still return a successful json
		// payload because we can try again with Relay token push in the future
		$data   = array();
		$result = $api->request();
		if ( is_wp_error( $result ) ) {
			// Just need to output the error in the response for debugging purpose
			$data['error'] = array(
				'message' => $result->get_error_message(),
				'code'    => $result->get_error_code(),
			);
		}

		wp_send_json_success( $data );
	}


	/**
	 * Import exported JSON file.
	 */
	public function handle_settings_import() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( ! isset( $_FILES['import_file'] ) ) {
			return;
		}

		$import_file = $_FILES['import_file']; // phpcs:ignore

		$extension = explode( '.', sanitize_text_field( wp_unslash( $import_file['name'] ) ) ); // phpcs:ignore
		$extension = end( $extension );

		if ( 'json' !== $extension ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Please upload a valid .json file', 'google-analytics-for-wordpress' ),
			) );
		}

		$file = file_get_contents( $import_file['tmp_name'] );

		if ( empty( $file ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Please select a valid file to upload.', 'google-analytics-for-wordpress' ),
			) );
		}

		// Retrieve the settings from the file and convert the json object to an array.
		$new_settings = json_decode( wp_json_encode( json_decode( $file ) ), true );
		$settings     = monsterinsights_get_options();
		$exclude      = array(
			'analytics_profile',
			'analytics_profile_code',
			'analytics_profile_name',
			'oauth_version',
			'cron_last_run',
			'monsterinsights_oauth_status',
		);

		$this->import_site_notes( $new_settings['site_notes'] );
		unset( $new_settings['site_notes'] );

		foreach ( $exclude as $e ) {
			if ( ! empty( $new_settings[ $e ] ) ) {
				unset( $new_settings[ $e ] );
			}
		}

		foreach ( $exclude as $e ) {
			if ( ! empty( $settings[ $e ] ) ) {
				$new_settings = $settings[ $e ];
			}
		}

		global $monsterinsights_settings;
		$monsterinsights_settings = $new_settings;

		update_option( monsterinsights_get_option_name(), $new_settings );

		wp_send_json_success( $new_settings );

	}

	/**
	 * Generic Ajax handler for grabbing report data in JSON.
	 */
	public function get_report_data() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			// Translators: link tag starts with url, link tag ends.
			$message = sprintf(
				esc_html__( 'Oops! You don not have permissions to view MonsterInsights reporting. Please check with your site administrator that your role is included in the MonsterInsights permissions settings. %1$sClick here for more information%2$s.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-view-reports', 'https://www.monsterinsights.com/docs/how-to-allow-user-roles-to-access-the-monsterinsights-reports-and-settings/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && $_REQUEST['isnetwork'] ) {
			define( 'WP_NETWORK_ADMIN', true );
		}
		$settings_page    = admin_url( 'admin.php?page=monsterinsights_settings' );
		$reactivation_url = monsterinsights_get_url( 'admin-notices', 'expired-license', "https://www.monsterinsights.com/my-account/" );
		$learn_more_link  = esc_url( 'https://www.monsterinsights.com/docs/faq/#licensedplugin' );

		// Only for Pro users, require a license key to be entered first so we can link to things.
		if ( monsterinsights_is_pro_version() ) {
			if ( ! MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->is_network_licensed() ) {
				// Translators: Support link tag starts with url and Support link tag ends.
				$message = sprintf(
					esc_html__( 'Oops! You cannot view MonsterInsights reports because you are not licensed. Please try again in a few minutes. If the issue continues, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
					'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-view-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
					'</a>'
				);
				wp_send_json_error( array(
					'message' => $message,
					'footer'  => '<a href="' . $settings_page . '">' . __( 'Add your license', 'google-analytics-for-wordpress' ) . '</a>',
				) );
			} else if ( MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->site_license_has_error() ) {
				// Good to go: site licensed.
			} else if ( MonsterInsights()->license->is_network_licensed() && ! MonsterInsights()->license->network_license_has_error() ) {
				// Good to go: network licensed.
			} else {
				// Translators: Support link tag starts with url and Support link tag ends.
				$message = sprintf(
					esc_html__( 'Oops! We had a problem due to a license key error. Please try again in a few minutes. If the problem persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
					'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-view-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
					'</a>'
				);
				wp_send_json_error( array( 'message' => $message ) );
			}
		}

		// We do not have a current auth.
		$site_auth = MonsterInsights()->auth->get_viewname();
		$ms_auth   = is_multisite() && MonsterInsights()->auth->get_network_viewname();
		if ( ! $site_auth && ! $ms_auth ) {
			$url = admin_url( 'admin.php?page=monsterinsights-onboarding' );

			// Check for MS dashboard
			if ( is_network_admin() ) {
				$url = network_admin_url( 'admin.php?page=monsterinsights-onboarding' );
			}
			// Translators: Wizard link tag starts with url and Wizard link tag ends.
			$message = sprintf(
				esc_html__( 'You need to authenticate into MonsterInsights before viewing reports. Please run our %1$ssetup wizard%2$s.', 'google-analytics-for-wordpress' ),
				'<a href="' . esc_url( $url ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		$report_name = isset( $_POST['report'] ) ? sanitize_text_field( wp_unslash( $_POST['report'] ) ) : '';

		if ( empty( $report_name ) ) {
			// Translators: Support link tag starts with url and Support link tag ends.
			$message = sprintf(
				esc_html__( 'Oops! We ran into a problem displaying this report. Please %1$scontact our support%2$s team if this issue persists.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-display-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		$report = MonsterInsights()->reporting->get_report( $report_name );

		$isnetwork = ! empty( $_REQUEST['isnetwork'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) : '';
		$start     = ! empty( $_POST['start'] ) ? sanitize_text_field( wp_unslash( $_POST['start'] ) ) : $report->default_start_date();
		$end       = ! empty( $_POST['end'] ) ? sanitize_text_field( wp_unslash( $_POST['end'] ) ) : $report->default_end_date();

		$args = array(
			'start' => $start,
			'end'   => $end,
		);

		// User want to show compare report.
		if ( isset( $_POST['compare_report'] ) ) {
			$args['compare_start'] = ! empty( $_POST['compare_start'] ) ? sanitize_text_field( wp_unslash( $_POST['compare_start'] ) ) : $report->default_compare_start_date();
			$args['compare_end']   = ! empty( $_POST['compare_end'] ) ? sanitize_text_field( wp_unslash( $_POST['compare_end'] ) ) : $report->default_compare_end_date();
		}

		if ( $isnetwork ) {
			$args['network'] = true;
		}

		if ( monsterinsights_is_pro_version() && ! MonsterInsights()->license->license_can( $report->level ) ) {
			$data = array(
				'success' => false,
				'error'   => 'license_level',
			);
		} else {
			$data = apply_filters( 'monsterinsights_vue_reports_data', $report->get_data( $args ), $report_name, $report );
		}

		if ( ! empty( $data['success'] ) ) {
			if ( empty( $data['data'] ) ) {
				wp_send_json_success( new stdclass() );
			} else {
				wp_send_json_success( $data['data'] );
			}
		} else if ( isset( $data['success'] ) && false === $data['success'] && ! empty( $data['error'] ) ) {
			// Use a custom handler for invalid_grant errors.
			if ( strpos( $data['error'], 'invalid_grant' ) > 0 ) {
				wp_send_json_error(
					array(
						'message' => 'invalid_grant',
						'footer'  => '',
					)
				);
			}

			wp_send_json_error(
				array(
					'message' => $data['error'],
					'footer'  => isset( $data['data']['footer'] ) ? $data['data']['footer'] : '',
					'type'    => isset( $data['data']['type'] ) ? $data['data']['type'] : '',
				)
			);
		}

		// Translators: Support link tag starts with url and Support link tag ends.
		$message = sprintf(
			esc_html__( 'Oops! We encountered an error while generating your reports. Please wait a few minutes and try again. If the issue persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
			'<a href="' . monsterinsights_get_url( 'notice', 'error-generating-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
			'</a>'
		);
		wp_send_json_error( array( 'message' => $message ) );
	}

	/**
	 * Install plugins which are not addons.
	 */
	public function install_plugin() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! monsterinsights_can_install_plugins() ) {
			wp_send_json( array(
				'error' => esc_html__( 'Oops! You are not allowed to install plugins. Please contact your website administrator for further assistance.', 'google-analytics-for-wordpress' ),
			) );
		}

		$slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : false;

		if ( ! $slug ) {
			wp_send_json( array(
				'message' => esc_html__( 'Missing plugin name.', 'google-analytics-for-wordpress' ),
			) );
		}

		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$api = plugins_api( 'plugin_information', array(
			'slug'   => $slug,
			'fields' => array(
				'short_description' => false,
				'sections'          => false,
				'requires'          => false,
				'rating'            => false,
				'ratings'           => false,
				'downloaded'        => false,
				'last_updated'      => false,
				'added'             => false,
				'tags'              => false,
				'compatibility'     => false,
				'homepage'          => false,
				'donate_link'       => false,
			),
		) );

		if ( is_wp_error( $api ) ) {
			return $api->get_error_message();
		}

		$download_url = $api->download_link;

		$method = '';
		$url    = add_query_arg(
			array(
				'page' => 'monsterinsights-settings',
			),
			admin_url( 'admin.php' )
		);
		$url    = esc_url( $url );

		ob_start();
		if ( false === ( $creds = request_filesystem_credentials( $url, $method, false, false, null ) ) ) {
			$form = ob_get_clean();

			wp_send_json( array( 'form' => $form ) );
		}

		// If we are not authenticated, make it happen now.
		if ( ! WP_Filesystem( $creds ) ) {
			ob_start();
			request_filesystem_credentials( $url, $method, true, false, null );
			$form = ob_get_clean();

			wp_send_json( array( 'form' => $form ) );

		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		monsterinsights_require_upgrader();

		// Prevent language upgrade in ajax calls.
		remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );
		// Create the plugin upgrader with our custom skin.
		$installer = new MonsterInsights_Plugin_Upgrader( new MonsterInsights_Skin() );
		$installer->install( $download_url );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();
		wp_send_json_success();

		wp_die();
	}

	/**
	 * Store that the first run notice has been dismissed so it doesn't show up again.
	 */
	public function dismiss_first_time_notice() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		monsterinsights_update_option( 'monsterinsights_first_run_notice', true );

		wp_send_json_success();
	}

	/**
	 * Get the notice status by id.
	 */
	public function get_notice_status() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$notice_id = empty( $_POST['notice'] ) ? false : sanitize_text_field( wp_unslash( $_POST['notice'] ) );
		if ( ! $notice_id ) {
			wp_send_json_error();
		}
		$is_dismissed = MonsterInsights()->notices->is_dismissed( $notice_id );

		wp_send_json_success( array(
			'dismissed' => $is_dismissed,
		) );
	}

	/**
	 * Dismiss notices by id.
	 */
	public function dismiss_notice() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$notice_id = empty( $_POST['notice'] ) ? false : sanitize_text_field( wp_unslash( $_POST['notice'] ) );
		if ( ! $notice_id ) {
			wp_send_json_error();
		}
		MonsterInsights()->notices->dismiss( $notice_id );

		wp_send_json_success();
	}

	/**
	 * Retrieve posts/pages
	 *
	 * @access admin
	 * @since 3.0.0
	 */
	public function get_posts() {

		// Run a security check first.
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : 'any';

		$already_added = monsterinsights_get_option('popular_posts_inline_curated', []);
		$exclude = array();
		if( is_array( $already_added ) && !empty( $already_added ) ){
			foreach ( $already_added as $key => $value ) {
				$exclude[$value['id']] = $value['id'];
			}
		}

		$exclude = array_unique(array_values($exclude));

		$args = array(
			's'              => isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '',
			'post_type'      => $post_type,
			'posts_per_page' => isset( $_POST['numberposts'] ) ? sanitize_text_field( wp_unslash( $_POST['numberposts'] ) ) : 25,
			'orderby'        => 'post_title',
			'order'          => 'ASC',
			'post__not_in'   => $exclude,
		);

		$array = array();
		$posts = get_posts( $args );

		if ( in_array( $post_type, array( 'page', 'any' ), true ) ) {
			$homepage = get_option( 'page_on_front' );
			if ( ! $homepage ) {
				$array[] = array(
					'id'    => - 1,
					'title' => __( 'Homepage', 'google-analytics-for-wordpress' ),
				);
			}
		}

		if ( $posts ) {
			foreach ( $posts as $post ) {
				$array[] = array(
					'id'    => $post->ID,
					'title' => $post->post_title,
				);
			}
		}

		wp_send_json_success( $array );
	}

	/**
	 * Search for taxonomy terms.
	 *
	 * @access admin
	 * @since 3.0.0
	 */
	public function get_taxonomy_terms() {

		// Run a security check first.
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$keyword  = isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';
		$taxonomy = isset( $_POST['taxonomy'] ) ? sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) ) : 'category';

		$args = array(
			'taxonomy'   => array( $taxonomy ),
			'hide_empty' => false,
			'name__like' => $keyword,
		);

		$terms = get_terms( $args );
		$array = array();

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$array[] = array(
					'id'   => esc_attr( $term->term_id ),
					'text' => esc_attr( $term->name ),
				);
			}
		}

		wp_send_json_success( $array );
	}

	/**
	 * Get the post types in a name => Label array.
	 */
	public function get_post_types() {

		// Run a security check first.
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$post_types_args = array(
			'public' => true,
		);
		$post_types      = get_post_types( $post_types_args, 'objects' );

		$post_types_parsed = array();

		foreach ( $post_types as $post_type ) {
			// Exclude post types that don't support the content editor.
			// Exclude the WooCommerce product post type as that doesn't use the "the_content" filter and we can't auto-add popular posts to it.
			if ( ! post_type_supports( $post_type->name, 'editor' ) || 'product' === $post_type->name ) {
				continue;
			}
			$post_types_parsed[ $post_type->name ] = $post_type->labels->singular_name;
		}

		$post_types_parsed = apply_filters( 'monsterinsights_vue_post_types_editor', $post_types_parsed );

		wp_send_json( $post_types_parsed );

	}


	public function check_popular_posts_report() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_view_dashboard' ) ) {
			// Translators: Link tag starts with url and link tag ends.
			$message = sprintf(
				esc_html__( 'Oops! You don not have permissions to view or access Popular Posts. Please check with your site administrator that your role is included in the MonsterInsights permissions settings. %1$sClick here for more information%2$s.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-view-dashboard', 'https://www.monsterinsights.com/docs/how-to-allow-user-roles-to-access-the-monsterinsights-reports-and-settings/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		if ( ! empty( $_REQUEST['isnetwork'] ) && $_REQUEST['isnetwork'] ) {
			define( 'WP_NETWORK_ADMIN', true );
		}
		$settings_page = admin_url( 'admin.php?page=monsterinsights_settings' );

		// Only for Pro users, require a license key to be entered first so we can link to things.
		if ( monsterinsights_is_pro_version() ) {
			if ( ! MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->is_network_licensed() ) {
				$url = admin_url( 'admin.php?page=monsterinsights_settings#/' );

				// Check for MS dashboard
				if ( is_network_admin() ) {
					$url = network_admin_url( 'admin.php?page=monsterinsights_settings#/' );
				}
				// Translators: Setting page link tag starts with url and Setting page link tag ends.
				$message = sprintf(
					esc_html__( 'Oops! We could not find a valid license key for MonsterInsights. Please %1$senter a valid license key%2$s to view this report.', 'google-analytics-for-wordpress' ),
					'<a href="' . esc_url( $url ) . '">',
					'</a>'
				);
				wp_send_json_error( array(
					'message' => $message,
					'footer'  => '<a href="' . $settings_page . '">' . __( 'Add your license', 'google-analytics-for-wordpress' ) . '</a>',
				) );
			} else if ( MonsterInsights()->license->is_site_licensed() && ! MonsterInsights()->license->site_license_has_error() ) {
				// Good to go: site licensed.
			} else if ( MonsterInsights()->license->is_network_licensed() && ! MonsterInsights()->license->network_license_has_error() ) {
				// Good to go: network licensed.
			} else {
				// Translators: Account page link tag starts with url and Account page link tag ends.
				$message = sprintf(
					esc_html__( 'Oops! We could not find a valid license key. Please enter a valid license key to view this report. You can find your license by logging into your %1$sMonsterInsights account%2$s.', 'google-analytics-for-wordpress' ),
					'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'license-errors', 'https://www.monsterinsights.com/my-account/licenses/' ) . '">',
					'</a>'
				);
				wp_send_json_error( array( 'message' => $message ) );
			}
		}

		// We do not have a current auth.
		$site_auth = MonsterInsights()->auth->get_viewname();
		$ms_auth   = is_multisite() && MonsterInsights()->auth->get_network_viewname();
		if ( ! $site_auth && ! $ms_auth ) {
			$url = admin_url( 'admin.php?page=monsterinsights_settings#/' );

			// Check for MS dashboard
			if ( is_network_admin() ) {
				$url = network_admin_url( 'admin.php?page=monsterinsights_settings#/' );
			}
			// Translators: Wizard page link tag starts with url and Wizard page link tag ends.
			$message = sprintf(
				esc_html__( 'You need to authenticate into MonsterInsights before viewing reports. Please complete the setup by going through our %1$ssetup wizard%2$s.', 'google-analytics-for-wordpress' ),
				'<a href="' . esc_url( $url ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		$report_name = 'popularposts';

		if ( empty( $report_name ) ) {
			// Translators: Support link tag starts with url and Support link tag ends.
			$message = sprintf(
				esc_html__( 'Oops! We encountered an error while generating your reports. Please wait a few minutes and try again. If the issue persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
				'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-generate-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
				'</a>'
			);
			wp_send_json_error( array( 'message' => $message ) );
		}

		$report = MonsterInsights()->reporting->get_report( $report_name );

		$isnetwork = ! empty( $_REQUEST['isnetwork'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['isnetwork'] ) ) : '';
		$start     = ! empty( $_POST['start'] ) ? sanitize_text_field( wp_unslash( $_POST['start'] ) ) : $report->default_start_date();
		$end       = ! empty( $_POST['end'] ) ? sanitize_text_field( wp_unslash( $_POST['end'] ) ) : $report->default_end_date();

		$args = array(
			'start' => $start,
			'end'   => $end,
		);

		if ( $isnetwork ) {
			$args['network'] = true;
		}

		if ( monsterinsights_is_pro_version() && ! MonsterInsights()->license->license_can( $report->level ) ) {
			$data = array(
				'success' => false,
				'error'   => 'license_level',
			);
		} else {
			$data = apply_filters( 'monsterinsights_vue_reports_data', $report->get_data( $args ), $report_name, $report );
		}

		if ( ! empty( $data['success'] ) && ! empty( $data['data'] ) ) {
			wp_send_json_success( $data['data'] );
		} else if ( isset( $data['success'] ) && false === $data['success'] && ! empty( $data['error'] ) ) {
			// Use a custom handler for invalid_grant errors.
			if ( strpos( $data['error'], 'invalid_grant' ) > 0 ) {
				wp_send_json_error(
					array(
						'message' => 'invalid_grant',
						'footer'  => '',
					)
				);
			}

			wp_send_json_error(
				array(
					'message' => $data['error'],
					'footer'  => isset( $data['data']['footer'] ) ? $data['data']['footer'] : '',
				)
			);
		}

		// Translators: Support link tag starts with url and Support link tag ends.
		$message = sprintf(
			__( 'Oops! We encountered an error while generating your reports. Please wait a few minutes and try again. If the issue persists, please %1$scontact our support%2$s team.', 'google-analytics-for-wordpress' ),
			'<a target="_blank" href="' . monsterinsights_get_url( 'notice', 'cannot-generate-reports', 'https://www.monsterinsights.com/my-account/support/' ) . '">',
			'</a>'
		);
		wp_send_json_error( array( 'message' => $message ) );
	}

	/**
	 * Ajax handler for popular posts theme customization settings.
	 * Specific theme styles are stored separately so we can handle 20+ themes with their specific settings.
	 */
	public function update_popular_posts_theme_setting() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		if ( ! empty( $_POST['type'] ) && ! empty( $_POST['theme'] ) && ! empty( $_POST['object'] ) && ! empty( $_POST['key'] ) && ! empty( $_POST['value'] ) ) {
			$settings_key = 'monsterinsights_popular_posts_theme_settings';
			$type         = sanitize_text_field( wp_unslash( $_POST['type'] ) ); // Type of Popular Posts instance: inline/widget/products.
			$theme        = sanitize_text_field( wp_unslash( $_POST['theme'] ) );
			$object       = sanitize_text_field( wp_unslash( $_POST['object'] ) ); // Style object like title, label, background, etc.
			$key          = sanitize_text_field( wp_unslash( $_POST['key'] ) ); // Style key for the object like color, font size, etc.
			$value        = sanitize_text_field( wp_unslash( $_POST['value'] ) ); // Value of custom style like 12px or #fff.
			$settings     = get_option( $settings_key, array() );

			if ( ! isset( $settings[ $type ] ) ) {
				$settings[ $type ] = array();
			}
			if ( ! isset( $settings[ $type ][ $theme ] ) ) {
				$settings[ $type ][ $theme ] = array();
			}

			if ( ! isset( $settings[ $type ][ $theme ][ $object ] ) ) {
				$settings[ $type ][ $theme ][ $object ] = array();
			}

			$settings[ $type ][ $theme ][ $object ][ $key ] = $value;

			update_option( $settings_key, $settings );

			wp_send_json_success();
		}

		wp_send_json_error();

	}

	/**
	 * Import site notes from exported file.
	 */
	private function import_site_notes( $site_notes ) {
		$notes_db = new MonsterInsights_Site_Notes_DB_Base();

		// Import site-notes category.
		foreach ( $site_notes['categories'] as $category ) {
			$notes_db->create_category( array(
				'name'             => $category['name'],
				'background_color' => $category['color'],
			) );
		}

		foreach ( $site_notes['notes'] as $notes ) {
			$category = get_term_by( 'name', $notes['category_name'], 'monsterinsights_note_category' );

			$notes_db->create( array(
				'note'      => $notes['note_title'],
				'date'      => $notes['note_date'],
				'important' => $notes['important'],
				'category'  => intval( ( ! empty( $category ) && ! empty( $category->term_id ) ) ? $category->term_id : 0 ),
			) );
		}
	}
}
