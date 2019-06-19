<?php
/**
 * Add MonsterInsights tests to the WP Site Health area.
 */

/**
 * Class MonsterInsights_WP_Site_Health_Lite
 */
class MonsterInsights_WP_Site_Health_Lite {

	/**
	 * Is the site licensed?
	 *
	 * @var bool
	 */
	private $is_licensed;
	/**
	 * Is the site autherd?
	 *
	 * @var bool
	 */
	private $is_authed;
	/**
	 * Which eCommerce type, if any.
	 *
	 * @var bool|string
	 */
	private $ecommerce;

	/**
	 * MonsterInsights_WP_Site_Health_Lite constructor.
	 */
	public function __construct() {

		add_filter( 'site_status_tests', array( $this, 'add_tests' ) );

		add_action( 'wp_ajax_health-check-monsterinsights-test_connection', array( $this, 'test_check_connection' ) );

	}

	/**
	 * Add MonsterInsights WP Site Health tests.
	 *
	 * @param array $tests The current filters array.
	 *
	 * @return array
	 */
	public function add_tests( $tests ) {

		if ( $this->is_licensed() ) {
			$tests['direct']['monsterinsights_license'] = array(
				'label' => __( 'MonsterInsights License', 'google-analytics-for-wordpress' ),
				'test'  => array( $this, 'test_check_license' ),
			);
		}

		$tests['direct']['monsterinsights_auth'] = array(
			'label' => __( 'MonsterInsights Authentication', 'google-analytics-for-wordpress' ),
			'test'  => array( $this, 'test_check_authentication' ),
		);

		$tests['direct']['monsterinsights_automatic_updates'] = array(
			'label' => __( 'MonsterInsights Automatic Updates', 'google-analytics-for-wordpress' ),
			'test'  => array( $this, 'test_check_autoupdates' ),
		);

		if ( $this->is_ecommerce() ) {
			$tests['direct']['monsterinsights_ecommerce'] = array(
				'label' => __( 'MonsterInsights eCommerce', 'google-analytics-for-wordpress' ),
				'test'  => array( $this, 'test_check_ecommerce' ),
			);
		}

		if ( $this->uses_amp() ) {
			$tests['direct']['monsterinsights_amp'] = array(
				'label' => __( 'MonsterInsights AMP', 'google-analytics-for-wordpress' ),
				'test'  => array( $this, 'test_check_amp' ),
			);
		}

		if ( $this->uses_fbia() ) {
			$tests['direct']['monsterinsights_fbia'] = array(
				'label' => __( 'MonsterInsights FBIA', 'google-analytics-for-wordpress' ),
				'test'  => array( $this, 'test_check_fbia' ),
			);
		}

		$tests['async']['monsterinsights_connection'] = array(
			'label' => __( 'MonsterInsights Connection', 'google-analytics-for-wordpress' ),
			'test'  => 'monsterinsights_test_connection',
		);

		return $tests;
	}

	/**
	 * Checke if the website is licensed.
	 *
	 * @return bool
	 */
	public function is_licensed() {

		if ( ! isset( $this->is_licensed ) ) {
			$this->is_licensed = is_network_admin() ? MonsterInsights()->license->is_network_licensed() : MonsterInsights()->license->is_site_licensed();
		}

		return $this->is_licensed;

	}

	/**
	 * Check if any of the supported eCommerce integrations are available.
	 *
	 * @return bool
	 */
	public function is_ecommerce() {

		if ( isset( $this->ecommerce ) ) {
			return $this->ecommerce;
		}

		$this->ecommerce = false;

		if ( class_exists( 'WooCommerce' ) ) {
			$this->ecommerce = 'WooCommerce';
		} else if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			$this->ecommerce = 'Easy Digital Downloads';
		} else if ( defined( 'MEPR_VERSION' ) && version_compare( MEPR_VERSION, '1.3.43', '>' ) ) {
			$this->ecommerce = 'MemberPress';
		}

		return $this->ecommerce;
	}

	/**
	 * Is the site using AMP or has the AMP addon installed?
	 *
	 * @return bool
	 */
	public function uses_amp() {

		return class_exists( 'MonsterInsights_AMP' ) || defined( 'AMP__FILE__' );

	}

	/**
	 * Is the site using FB Instant Articles or has the FBIA addon installed?
	 *
	 * @return bool
	 */
	public function uses_fbia() {

		return class_exists( 'MonsterInsights_FB_Instant_Articles' ) || defined( 'IA_PLUGIN_VERSION' ) && version_compare( IA_PLUGIN_VERSION, '3.3.4', '>' );

	}

	/**
	 * Check if MonsterInsights is authenticated and display a specific message.
	 *
	 * @return array
	 */
	public function test_check_authentication() {
		$result = array(
			'label'       => __( 'Your website is authenticated with MonsterInsights', 'google-analytics-for-wordpress' ),
			'status'      => 'good',
			'badge'       => array(
				'label' => __( 'MonsterInsights', 'google-analytics-for-wordpress' ),
				'color' => 'blue',
			),
			'description' => __( 'MonsterInsights integrates your WordPress website with Google Analytics.', 'google-analytics-for-wordpress' ),
			'actions'     => sprintf(
				'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></p>',
				add_query_arg( 'page', 'monsterinsights_reports', admin_url( 'admin.php' ) ),
				__( 'View Reports', 'google-analytics-for-wordpress' )
			),
			'test'        => 'monsterinsights_auth',
		);

		$this->is_authed = MonsterInsights()->auth->is_authed() || MonsterInsights()->auth->is_network_authed();

		if ( ! $this->is_authed ) {
			if ( '' !== monsterinsights_get_ua() ) {
				// Using Manual UA.
				$result['status']      = 'recommended';
				$result['label']       = __( 'You are using Manual UA code output', 'google-analytics-for-wordpress' );
				$result['description'] = __( 'We highly recommend authenticating with MonsterInsights so that you can access our new reporting area and take advantage of new MonsterInsights features.', 'google-analytics-for-wordpress' );
				$result['actions']     = sprintf(
					'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></p>',
					add_query_arg( 'page', 'monsterinsights_settings', admin_url( 'admin.php' ) ),
					__( 'Authenticate now', 'google-analytics-for-wordpress' )
				);

			} else {
				// Not authed at all.
				$result['status']      = 'critical';
				$result['label']       = __( 'Please configure your Google Analytics settings', 'google-analytics-for-wordpress' );
				$result['description'] = __( 'Your traffic is not being tracked by MonsterInsights at the moment and you are losing data. Authenticate and get access to the reporting area and advanced tracking features.', 'google-analytics-for-wordpress' );
				$result['actions']     = sprintf(
					'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></p>',
					add_query_arg( 'page', 'monsterinsights_settings', admin_url( 'admin.php' ) ),
					__( 'Authenticate now', 'google-analytics-for-wordpress' )
				);
			}
		}

		return $result;
	}

	/**
	 * Check if the license is properly set up.
	 *
	 * @return array
	 */
	public function test_check_license() {

		$result = array(
			'status'      => 'critical',
			'badge'       => array(
				'label' => __( 'MonsterInsights', 'google-analytics-for-wordpress' ),
				'color' => 'blue',
			),
			'test'        => 'monsterinsights_license',
			'label'       => __( 'MonsterInsights Upgrade not applied', 'google-analytics-for-wordpress' ),
			'description' => __( 'A valid license has been added to MonsterInsights but you are still using the Lite version.', 'google-analytics-for-wordpress' ),
			'actions'     => sprintf(
				'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></p>',
				add_query_arg( 'page', 'monsterinsights_settings', admin_url( 'admin.php' ) ),
				__( 'Go to License Settings', 'google-analytics-for-wordpress' )
			),
		);

		return $result;
	}

	/**
	 * Tests that run to check if autoupdates are enabled.
	 *
	 * @return array
	 */
	public function test_check_autoupdates() {

		$result = array(
			'label'       => __( 'Your website is receiving automatic updates', 'google-analytics-for-wordpress' ),
			'status'      => 'good',
			'badge'       => array(
				'label' => __( 'MonsterInsights', 'google-analytics-for-wordpress' ),
				'color' => 'blue',
			),
			'description' => __( 'MonsterInsights automatic updates are enabled and you are getting the latest features, bugfixes, and security updates as they are released.', 'google-analytics-for-wordpress' ),
			'test'        => 'monsterinsights_automatic_updates',
		);

		$updates_option = monsterinsights_get_option( 'automatic_updates', false );

		if ( 'minor' === $updates_option ) {
			$result['label']       = __( 'Your website is receiving minor updates', 'google-analytics-for-wordpress' );
			$result['description'] = __( 'MonsterInsights minor updates are enabled and you are getting the latest bugfixes and security updates, but not major features.', 'google-analytics-for-wordpress' );
		}
		if ( 'none' === $updates_option ) {
			$result['status']      = 'recommended';
			$result['label']       = __( 'Automatic updates are disabled', 'google-analytics-for-wordpress' );
			$result['description'] = __( 'MonsterInsights automatic updates are disabled. We recommend enabling automatic updates so you can get access to the latest features, bugfixes, and security updates as they are released.', 'google-analytics-for-wordpress' );
			$result['actions']     = sprintf(
				'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></p>',
				add_query_arg( 'page', 'monsterinsights_settings#/advanced', admin_url( 'admin.php' ) ),
				__( 'Update Settings', 'google-analytics-for-wordpress' )
			);
		}

		return $result;

	}

	/**
	 * Tests that run to check if eCommerce is present.
	 *
	 * @return array
	 */
	public function test_check_ecommerce() {
		$result = array(
			'label'       => __( 'eCommerce data is not being tracked', 'google-analytics-for-wordpress' ),
			'status'      => 'recommended',
			'badge'       => array(
				'label' => __( 'MonsterInsights', 'google-analytics-for-wordpress' ),
				'color' => 'blue',
			),
			// Translators: The eCommerce store currently active.
			'description' => sprintf( __( 'You are using %s but the MonsterInsights eCommerce addon is not active, please Install & Activate it to start tracking eCommerce data.', 'google-analytics-for-wordpress' ), $this->ecommerce ),
			'test'        => 'monsterinsights_ecommerce',
			'actions'     => sprintf(
				'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></p>',
				add_query_arg( 'page', 'monsterinsights_settings#/addons', admin_url( 'admin.php' ) ),
				__( 'View Addons', 'google-analytics-for-wordpress' )
			),
		);

		return $result;
	}

	/**
	 * Tests for the AMP cases.
	 *
	 * @return array
	 */
	public function test_check_amp() {

		$result = array(
			'label'       => __( 'AMP pages are not being tracked', 'google-analytics-for-wordpress' ),
			'status'      => 'recommended',
			'badge'       => array(
				'label' => __( 'MonsterInsights', 'google-analytics-for-wordpress' ),
				'color' => 'blue',
			),
			'description' => __( 'Your website has Google AMP-enabled pages set up but they are not tracked by Google Analytics at the moment. You need to Install & Activate the MonsterInsights AMP Addon.', 'google-analytics-for-wordpress' ),
			'test'        => 'monsterinsights_amp',
			'actions'     => sprintf(
				'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></p>',
				add_query_arg( 'page', 'monsterinsights_settings#/addons', admin_url( 'admin.php' ) ),
				__( 'View Addons', 'google-analytics-for-wordpress' )
			),
		);

		return $result;

	}

	/**
	 * Tests for the FBIA cases.
	 *
	 * @return array
	 */
	public function test_check_fbia() {

		$result = array(
			'label'       => __( 'Facebook Instant Articles pages are not being tracked', 'google-analytics-for-wordpress' ),
			'status'      => 'recommended',
			'badge'       => array(
				'label' => __( 'MonsterInsights', 'google-analytics-for-wordpress' ),
				'color' => 'blue',
			),
			'description' => __( 'Your website has Facebook Instant Articles pages set up but they are not tracked by Google Analytics at the moment. You need to Install & Activate the MonsterInsights Facebook Instant Articles Addon.', 'google-analytics-for-wordpress' ),
			'test'        => 'monsterinsights_fbia',
			'actions'     => sprintf(
				'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></p>',
				add_query_arg( 'page', 'monsterinsights_settings#/addons', admin_url( 'admin.php' ) ),
				__( 'View Addons', 'google-analytics-for-wordpress' )
			),
		);

		return $result;

	}

	/**
	 * Checks if there are errors communicating with Monsterinsights.com.
	 */
	public function test_check_connection() {

		$result = array(
			'label'       => __( 'Can connect to MonsterInsights.com correctly', 'google-analytics-for-wordpress' ),
			'status'      => 'good',
			'badge'       => array(
				'label' => __( 'MonsterInsights', 'google-analytics-for-wordpress' ),
				'color' => 'blue',
			),
			'description' => __( 'The MonsterInsights API is reachable and no connection issues have been detected.', 'google-analytics-for-wordpress' ),
			'test'        => 'monsterinsights_connection',
		);

		$url      = 'https://api.monsterinsights.com/v2/test/';
		$params   = array(
			'sslverify'  => false,
			'timeout'    => 2,
			'user-agent' => 'MonsterInsights/' . MONSTERINSIGHTS_VERSION,
			'body'       => '',
		);
		$response = wp_remote_get( $url, $params );

		if ( is_wp_error( $response ) || $response['response']['code'] < 200 || $response['response']['code'] > 300 ) {
			$result['status']      = 'critical';
			$result['label']       = __( 'The MonsterInsights server is not reachable.', 'google-analytics-for-wordpress' );
			$result['description'] = __( 'Your server is blocking external requests to monsterinsights.com, please check your firewall settings or contact your host for more details.', 'google-analytics-for-wordpress' );

			if ( is_wp_error( $response ) ) {
				// Translators: The error message received.
				$result['description'] .= ' ' . sprintf( __( 'Error message: %s', 'google-analytics-for-wordpress' ), $response->get_error_message() );
			}
		}

		wp_send_json_success( $result );
	}
}

new MonsterInsights_WP_Site_Health_Lite();

