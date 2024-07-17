<?php

/**
 * Ask for some love.
 *
 * @package    MonsterInsights
 * @author     MonsterInsights
 * @since      7.0.7
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2018, MonsterInsights LLC
 * TODO:
 *  Go through this file and remove UA references/usages
 *  Check if this class is actually working
 */
class MonsterInsights_Review {
	/**
	 * Primary class constructor.
	 *
	 * @since 7.0.7
	 */
	public function __construct() {
		// Admin notice requesting review.
		add_action( 'admin_notices', array( $this, 'review_request' ) );
		add_action( 'wp_ajax_monsterinsights_review_dismiss', array( $this, 'review_dismiss' ) );
	}

	/**
	 * Add admin notices as needed for reviews.
	 *
	 * @since 7.0.7
	 */
	public function review_request() {
		// Only consider showing the review request to admin users.
		if ( ! is_super_admin() ) {
			return;
		}

		// If the user has opted out of product announcement notifications, don't
		// display the review request.
		if ( monsterinsights_get_option( 'hide_am_notices', false ) || monsterinsights_get_option( 'network_hide_am_notices', false ) ) {
			return;
		}
		// Verify that we can do a check for reviews.
		$review = get_option( 'monsterinsights_review' );
		$time   = time();
		$load   = false;

		if ( ! $review ) {
			$review = array(
				'time'      => $time,
				'dismissed' => false,
			);
			update_option( 'monsterinsights_review', $review );
		} else {
			// Check if it has been dismissed or not.
			if ( ( isset( $review['dismissed'] ) && ! $review['dismissed'] ) && ( isset( $review['time'] ) && ( ( $review['time'] + DAY_IN_SECONDS ) <= $time ) ) ) {
				$load = true;
			}
		}

		// If we cannot load, return early.
		if ( ! $load ) {
			return;
		}

		$this->review();
	}

	/**
	 * Maybe show review request.
	 *
	 * @since 7.0.7
	 */
	public function review() {
		// Fetch when plugin was initially installed.
		$activated = get_option( 'monsterinsights_over_time', array() );
		$v4_code   = monsterinsights_get_v4_id();

		if ( ! empty( $activated['connected_date'] ) ) {
			// Only continue if plugin has been tracking for at least 14 days.
			$days = 14;
			if ( monsterinsights_get_option( 'gadwp_migrated', 0 ) > 0 ) {
				$days = 21;
			}
			if ( ( $activated['connected_date'] + ( DAY_IN_SECONDS * $days ) ) > time() ) {
				return;
			}
		} else {
			if ( empty( $activated ) ) {
				$data = array(
					'installed_version' => MONSTERINSIGHTS_VERSION,
					'installed_date'    => time(),
					'installed_pro'     => monsterinsights_is_pro_version(),
				);
			} else {
				$data = $activated;
			}
			// If already has a UA code mark as connected now.
			if ( ! empty( $v4_code ) ) {
				$data['connected_date'] = time();
			}

			update_option( 'monsterinsights_over_time', $data, false );

			return;
		}

		// Only proceed with displaying if the user is tracking.
		if ( empty( $v4_code ) ) {
			return;
		}

		$feedback_url = add_query_arg( array(
			'wpf192157_24' => untrailingslashit( home_url() ),
			'wpf192157_26' => monsterinsights_get_license_key(),
			'wpf192157_27' => monsterinsights_is_pro_version() ? 'pro' : 'lite',
			'wpf192157_28' => MONSTERINSIGHTS_VERSION,
		), 'https://www.monsterinsights.com/plugin-feedback/' );
		$feedback_url = monsterinsights_get_url( 'review-notice', 'feedback', $feedback_url );
		?>
		<div class="notice notice-info is-dismissible monsterinsights-review-notice">
			<div class="monsterinsights-review-step">
				<p>
					<?php
					echo wp_kses(
						sprintf(
							__( 'Hey - we noticed you\'ve been using %1$s for a while - that\'s great! Could you do us a BIG favor and give it a 5-star review on WordPress to help us spread the word and boost our motivation?', 'google-analytics-for-wordpress' ),
							'<strong>MonsterInsights</strong>'
						),
						array( 'strong' => array() )
					);
					?>
				</p>
				<p>
					<a
						href="https://wordpress.org/support/plugin/google-analytics-for-wordpress/reviews/?filter=5#new-post"
					   	class="monsterinsights-dismiss-review-notice monsterinsights-review-out"
						target="_blank"
					   	rel="noopener noreferrer"
					>
						<?php esc_html_e( 'Ok, you deserve it', 'google-analytics-for-wordpress' ); ?>
					</a>
					<br>
					<a
						href="#"
						class="monsterinsights-dismiss-review-notice monsterinsights-review-later"
					   	rel="noopener noreferrer"
					>
						<?php esc_html_e( 'Nope, maybe later', 'google-analytics-for-wordpress' ); ?>
					</a>
					<br>
					<a
						href="#"
						class="monsterinsights-dismiss-review-notice"
					   	rel="noopener noreferrer"
					>
						<?php esc_html_e( 'I already did', 'google-analytics-for-wordpress' ); ?>
					</a>
				</p>
			</div>
		</div>
		<script type="text/javascript">
            jQuery(document).ready(function ($) {
                $(document).on('click', '.monsterinsights-dismiss-review-notice', function (event) {
                    if (!$(this).hasClass('monsterinsights-review-out')) {
                        event.preventDefault();
                    }
                    $.post(ajaxurl, {
                        action: 'monsterinsights_review_dismiss',
                        review_later: $(this).hasClass('monsterinsights-review-later')
                    });
                    $('.monsterinsights-review-notice').remove();
                });
            });
		</script>
		<?php
	}

	/**
	 * Dismiss the review admin notice
	 *
	 * @since 7.0.7
	 */
	public function review_dismiss() {
		$review              = get_option( 'monsterinsights_review', array() );
		$review['time']      = time();
		$review['dismissed'] = true;
		update_option( 'monsterinsights_review', $review );

		if ( is_super_admin() && is_multisite() ) {
			$site_list = get_sites();
			foreach ( (array) $site_list as $site ) {
				switch_to_blog( $site->blog_id );

				update_option( 'monsterinsights_review', $review );

				restore_current_blog();
			}
		}

		die;
	}
}

new MonsterInsights_Review();
