<?php

if ( ! class_exists( 'MonsterInsights_Compatibility_Check' ) ) {
	/**
	 * Check PHP and WP compatibility
	 *
	 * @since 8.0.0
	 */
	class MonsterInsights_Compatibility_Check {
		/**
		 * Holds singleton instance
		 *
		 * @since 8.0.0
		 * @var MonsterInsights_Compatibility_Check
		 */
		private static $instance;

		/**
		 * Return Singleton instance
		 *
		 * @return MonsterInsights_Compatibility_Check
		 * @since 8.0.0
		 */
		public static function get_instance() {
			if ( empty( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * @since 8.0.0
		 * @var array {
		 *     PHP Version requirement and recommendation
		 *
		 * @type string $required Halt and deactivate plugin if PHP is under this version
		 * @type string $warning Display undismissable warning if PHP is under this version
		 * @type string $recommended Display undismissable warning if PHP is under this version
		 * }
		 */
		private $compatible_php_version = array(
			'required'    => '7.2',
			'warning'     => '7.0',
			'recommended' => false,
		);

		/**
		 * @since 8.0.0
		 * @var array {
		 *     WP Version requirement and recommendation
		 *
		 * @type string $required Halt and deactivate plugin if WP is under this version
		 * @type string $warning Display undismissable warning if WP is under this version
		 * @type string $recommended Display undismissable warning if WP is under this version
		 * }
		 */
		private $compatible_wp_version = array(
			'required'    => '5.6',
			'warning'     => '6.0',
			'recommended' => false,
		);

		/**
		 * Private constructor
		 *
		 * @since 8.0.0
		 */
		private function __construct() {
			add_filter( 'monsterinsights_compatible_php_version', array(
				$this,
				'filter_compatible_php_version'
			), 10, 1 );
			add_filter( 'monsterinsights_compatible_wp_version', array(
				$this,
				'filter_compatible_wp_version'
			), 10, 1 );
		}

		/**
		 * Return the strictest php compatibility versions
		 *
		 * @param array $version {
		 *     PHP Version requirement and recommendation
		 *
		 * @type string $required Halt and deactivate plugin if PHP is under this version
		 * @type string $warning Display undismissable warning if PHP is under this version
		 * @type string $recommended Display undismissable warning if PHP is under this version
		 * }
		 *
		 * @return array {
		 *     PHP Version requirement and recommendation
		 *
		 * @type string $required Halt and deactivate plugin if PHP is under this version
		 * @type string $warning Display undismissable warning if PHP is under this version
		 * @type string $recommended Display undismissable warning if PHP is under this version
		 * }
		 * @since 8.0.0
		 */
		public function filter_compatible_php_version( $version ) {
			if ( ! $version || version_compare( $version['required'], $this->compatible_php_version['required'], '<' ) ) {
				return $this->compatible_php_version;
			}

			return $version;
		}

		/**
		 * Return the strictest WP compatibility versions
		 *
		 * @param array $version {
		 *     WP Version requirement and recommendation
		 *
		 * @type string $required Halt and deactivate plugin if WP is under this version
		 * @type string $warning Display undismissable warning if WP is under this version
		 * @type string $recommended Display undismissable warning if WP is under this version
		 * }
		 *
		 * @return array {
		 *     WP Version requirement and recommendation
		 *
		 * @type string $required Halt and deactivate plugin if WP is under this version
		 * @type string $warning Display undismissable warning if WP is under this version
		 * @type string $recommended Display undismissable warning if WP is under this version
		 * }
		 * @since 8.0.0
		 */
		public function filter_compatible_wp_version( $version ) {
			if ( ! $version || version_compare( $version['required'], $this->compatible_wp_version['required'], '<' ) ) {
				return $this->compatible_wp_version;
			}

			return $version;
		}

		/**
		 * Return required, warning and recommended PHP versions
		 *
		 * @return array {
		 *     PHP Version requirement and recommendation
		 *
		 * @type string $required Halt and deactivate plugin if PHP is under this version
		 * @type string $warning Display undismissable warning if PHP is under this version
		 * @type string $recommended Display undismissable warning if PHP is under this version
		 * }
		 * @since 8.0.0
		 */
		public function get_compatible_php_version() {
			return apply_filters( 'monsterinsights_compatible_php_version', $this->compatible_php_version );
		}

		/**
		 * Check to see if PHP version meets the minimum required version
		 *
		 * @return bool
		 * @since 8.0.0
		 */
		public function is_php_compatible() {
			$compatible_php_version = $this->get_compatible_php_version();

			return empty( $compatible_php_version['required'] ) || version_compare( phpversion(), $compatible_php_version['required'], '>=' );
		}

		/**
		 * Return required, warning and recommended WP versions
		 *
		 * @return array {
		 *     WP Version requirement and recommendation
		 *
		 * @type string $required Halt and deactivate plugin if WP is under this version
		 * @type string $warning Display undismissable warning if WP is under this version
		 * @type string $recommended Display undismissable warning if WP is under this version
		 * }
		 * @since 8.0.0
		 */
		public function get_compatible_wp_version() {
			return apply_filters( 'monsterinsights_compatible_wp_version', $this->compatible_wp_version );
		}

		/**
		 * Check to see if WP version meets the minimum required version
		 *
		 * @return bool
		 * @since 8.0.0
		 */
		public function is_wp_compatible() {
			global $wp_version;
			$compatible_wp_version = $this->get_compatible_wp_version();

			return empty( $compatible_wp_version['required'] ) || version_compare( $wp_version, $compatible_wp_version['required'], '>=' );
		}

		/**
		 * Check to see if the main plugin or any other add-ons have displayed the required version notice
		 *
		 * @return bool
		 * @since 8.0.0
		 */
		private function is_notice_already_active() {
			return defined( 'MONSTERINSIGHTS_VERSION_NOTICE_ACTIVE' ) && MONSTERINSIGHTS_VERSION_NOTICE_ACTIVE;
		}

		/**
		 * Set global constant so that main plugin or other add-ons are aware that the version notice
		 * has been set for display already
		 *
		 * @return void
		 * @since 8.0.0
		 */
		private function set_notice_active() {
			if ( ! defined( 'MONSTERINSIGHTS_VERSION_NOTICE_ACTIVE' ) ) {
				define( 'MONSTERINSIGHTS_VERSION_NOTICE_ACTIVE', true );
			}
		}

		/**
		 * Display version notice in admin area if:
		 * 1. Minimum PHP and WP versions are not met
		 * 2. The notice has been displayed elsewhere (in case there are multiple add-ons)
		 *
		 * @return void
		 * @since 8.0.0
		 */
		public function maybe_display_notice() {
			if ( defined( 'MONSTERINSIGHTS_FORCE_ACTIVATION' ) && MONSTERINSIGHTS_FORCE_ACTIVATION ) {
				return;
			}

			if ( $this->is_notice_already_active() ) {
				return;
			}

			if ( ! $this->is_php_compatible() ) {
				add_action( 'admin_notices', array( $this, 'display_php_notice' ) );
			}

			if ( ! $this->is_wp_compatible() ) {
				add_action( 'admin_notices', array( $this, 'display_wp_notice' ) );
			}
		}

		/**
		 * Deactivate plugin if minimum PHP and WP requirements are not met.
		 *
		 * @param $plugin
		 *
		 * @return void
		 * @since 8.0.0
		 */
		public function maybe_deactivate_plugin( $plugin ) {
			if ( defined( 'MONSTERINSIGHTS_FORCE_ACTIVATION' ) && MONSTERINSIGHTS_FORCE_ACTIVATION ) {
				return;
			}

			$url                    = admin_url( 'plugins.php' );
			$compatible_php_version = $this->get_compatible_php_version();
			$compatible_wp_version  = $this->get_compatible_wp_version();

			if ( ! empty( $compatible_php_version['required'] ) && ! $this->is_php_compatible() ) {
				deactivate_plugins( $plugin );
				// Translators: HTML strong tag starts, PHP version number, HTML string tag end, plugins page Link tag starts with url and plugins page link tag ends.
				wp_die(
					sprintf( esc_html__( 'Sorry, but your current version of PHP does not meet the minimum required version %1$s%2$s%3$s or newer to run MonsterInsights properly. For information on how to upgrade your PHP version, contact your web host. %4$sClick here to return to the Dashboard%5$s.', 'google-analytics-for-wordpress' ),
						'<strong>',
						$compatible_php_version['required'], // phpcs:ignore
						'</strong>',
						'<a target="_blank" href="' . esc_url($url) . '">',
						'</a>'
					)
				);
			}

			if ( ! empty( $compatible_wp_version['required'] ) && ! $this->is_wp_compatible() ) {
				deactivate_plugins( plugin_basename( __FILE__ ) );
				// Translators: HTML strong tag starts, WP version number, HTML string tag end, plugins page Link tag starts with url and plugins page link tag ends.
				wp_die(
					sprintf(
						esc_html__( 'Sorry, but your WordPress version is not %1$s%2$s%3$s or newer. Please update your WordPress version and then activate MonsterInsights. For help on how to update your WordPress %4$sclick here%5$s.', 'google-analytics-for-wordpress' ),
						'<strong>',
						$compatible_wp_version['required'], // phpcs:ignore
						'</strong>',
						'<a target="_blank" href="' . esc_url($url) . '">',
						'</a>'
					)
				);
			}
		}

		/**
		 * Output a nag notice if the user has an out of date PHP version installed
		 *
		 * @return void
		 * @since 8.0.0
		 */
		public function display_php_notice() {
			$url = admin_url( 'plugins.php' );
			// Check for MS dashboard
			if ( is_network_admin() ) {
				$url = network_admin_url( 'plugins.php' );
			}

			$compatible_php_version = $this->get_compatible_php_version();
			if ( empty( $compatible_php_version['required'] ) ) {
				return;
			}

			$this->set_notice_active();
			?>
			<div class="error">
				<p>
					<?php
					// Translators: HTML strong tag starts, PHP version number, HTML string tag end, plugins page Link tag starts with url and plugins page link tag ends.
					echo sprintf(
						esc_html__( 'Sorry, but your current version of PHP does not meet the minimum required version %1$s%2$s%3$s or newer to run MonsterInsights properly. For information on how to upgrade your PHP version, contact your web host. %4$sClick here to return to the Dashboard%5$s.', 'google-analytics-for-wordpress' ),
						'<strong>',
						$compatible_php_version['required'], // phpcs:ignore
						'</strong>',
						'<a target="_blank" href="' . $url . '">', // phpcs:ignore
						'</a>' );
					?>
				</p>
			</div>
			<?php
		}

		/**
		 * Output a nag notice if the user has an out of date WP version installed
		 *
		 * @return void
		 * @since 8.0.0
		 */
		public function display_wp_notice() {
			$url = monsterinsights_get_url( 'notice', 'wrong-wp-version', 'https://www.wpbeginner.com/beginners-guide/ultimate-guide-to-upgrade-wordpress-for-beginners-infograph/' );

			$compatible_wp_version = $this->get_compatible_wp_version();
			if ( empty( $compatible_wp_version['required'] ) ) {
				return;
			}

			$this->set_notice_active();
			?>
			<div class="error">
				<p>
					<?php
					// Translators: Make version number bold and add a link to return to the plugins page.
					echo sprintf(
						esc_html__( 'Sorry, but your WordPress version is not %1$s%2$s%3$s or newer. Please update your WordPress version and then activate MonsterInsights. For help on how to update your WordPress %4$sclick here%5$s.', 'google-analytics-for-wordpress' ),
						'<strong>',
						$compatible_wp_version['required'], // phpcs:ignore
						'</strong>',
						'<a target="_blank" href="' . $url . '">', // phpcs:ignore
						'</a>'
					);
					?>
				</p>
			</div>
			<?php
		}
	}
}
