<?php

/**
 * Class MonsterInsights_Welcome
 */
class MonsterInsights_Welcome {

	/**
	 * MonsterInsights_Welcome constructor.
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'maybe_redirect' ), 9999 );

	}

	/**
	 * Check if we should do any redirect.
	 */
	public function maybe_redirect() {

		// Bail if no activation redirect.
		if ( ! get_transient( '_monsterinsights_activation_redirect' ) ) {
			return;
		}

		// Delete the redirect transient.
		delete_transient( '_monsterinsights_activation_redirect' );

		// Bail if activating from network, or bulk.
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) { // WPCS: CSRF ok, input var ok.
			return;
		}

		$upgrade = get_option( 'monsterinsights_version_upgraded_from', false );
		if ( apply_filters( 'monsterinsights_enable_onboarding_wizard', false === $upgrade ) ) {
			$redirect = admin_url( 'index.php?page=monsterinsights-onboarding' );
			wp_safe_redirect( $redirect );
			exit;
		}
	}
}

new MonsterInsights_Welcome();
