<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { 
	exit;
}

function monsterinsights_registered_settings_filter( $settings ) {
	$tracking_mode   = monsterinsights_get_option( 'tracking_mode', 'analytics' );
	$events_tracking = monsterinsights_get_option( 'events_mode', 'js' );

	// both
	if ( ! empty( $settings['engagement']['events_mode'] ) ) {
		if ( $tracking_mode === 'ga' && ! monsterinsights_is_debug_mode() ) {
			// if we're not using Universal Analytics, we can't do JS events tracking
			unset( $settings['engagement']['events_mode']['options']['js'] );
		} else {
			if ( $tracking_mode !== 'ga' && $events_tracking !== 'php' && ! monsterinsights_is_debug_mode() ) {
			   // if we're not using PHP events tracking, turn it off
				unset( $settings['engagement']['events_mode']['options']['php'] );
			}
		}
	}
	if ( ! empty( $settings['demographics']['demographics'] ) && $tracking_mode === 'ga' && ! monsterinsights_is_debug_mode() ) {
		// Events relies on universal tracking
		$url = esc_url( wp_nonce_url( add_query_arg( array( 'monsterinsights-action' => 'switch_to_analyticsjs', 'return' => 'demographics' ) ), 'monsterinsights-switch-to-analyticsjs-nonce' ) );
		$settings['demographics']['demographics']['type'] = 'notice';
		$settings['demographics']['demographics']['desc'] = sprintf( esc_html__( 'Demographics and Interests tracking is only available on Universal Tracking (analytics.js). You\'re currently using deprecated ga.js tracking. We recommend switching to analytics.js, as it is significantly more accurate than ga.js, and allows for additional functionality (like the more accurate Javascript based events tracking we offer). Further Google Analytics has deprecated support for ga.js, and it may stop working at any time when Google decides to disable it from their server. To switch to using the newer Universal Analytics (analytics.js) %1$sclick here%2$s.', 'google-analytics-for-wordpress' ), '<a href="' . $url .'">', '</a>' );
	}
	if ( ! empty( $settings['links']['enhanced_link_attribution'] ) && $tracking_mode === 'ga' && ! monsterinsights_is_debug_mode() ) {
		// This relies on universal tracking
		$url = esc_url( wp_nonce_url( add_query_arg( array( 'monsterinsights-action' => 'switch_to_analyticsjs', 'return' => 'demographics' ) ), 'monsterinsights-switch-to-analyticsjs-nonce' ) );
		$settings['links']['enhanced_link_attribution']['type'] = 'notice';
		$settings['links']['enhanced_link_attribution']['desc'] = sprintf( esc_html__( 'Enhanced Link Attribution tracking is only available on Universal Tracking (analytics.js). You\'re currently using deprecated ga.js tracking. We recommend switching to analytics.js, as it is significantly more accurate than ga.js, and allows for additional functionality (like the more accurate Javascript based events tracking we offer). Further Google Analytics has deprecated support for ga.js, and it may stop working at any time when Google decides to disable it from their server. To switch to using the newer Universal Analytics (analytics.js) %1$sclick here%2$s.', 'google-analytics-for-wordpress' ), '<a href="' . $url .'">', '</a>' );
	}


	if ( ! empty( $settings['compatibility']['subdomain_tracking'] ) && $tracking_mode === 'ga' && ! monsterinsights_is_debug_mode() ) {
		$settings['links']['enhanced_link_attribution']['name'] = __( 'Subdomain tracking:', 'google-analytics-for-wordpress' );
		$settings['links']['enhanced_link_attribution']['desc'] = sprintf( esc_html__( 'This allows you to set the domain that\'s set by %1$s for tracking subdomains. If empty, this will not be set. Can be used to set localhost for ga.js tracking.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiDomainDirectory#_gat.GA_Tracker_._setDomainName" target="_blank" rel="noopener noreferrer" referrer="no-referrer"><code>_setDomainName</code></a>' );
	}


	// Addons:
		// Social
			$settings['social']['social_notice'] = array( 
				'id' => 'social_notice',
				'name' => __( 'Want to setup Social Tracking?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => sprintf( esc_html__( 'To setup social tracking, please %1$supgrade your MonsterInsights account%2$s to unlock this feature.', 'google-analytics-for-wordpress' ), '<a href="' . esc_attr( monsterinsights_get_upgrade_link() ) . '">', '</a>' ) , '<br /><a href="' . monsterinsights_get_upgrade_link() . '" class="monsterinsights-upsell-box-button button button-primary" target="_blank" rel="noopener noreferrer" referrer="no-referrer" >' . esc_html__( 'Learn more about MonsterInsights Pro', 'google-analytics-for-wordpress' ) . '</a>'
			);

		// Ads
			$settings['ads']['ads_notice'] = array( 
				'id' => 'ads_notice',
				'name' => __( 'Want to setup Ad Tracking?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => sprintf( esc_html__( 'To setup ad tracking, please %1$supgrade your MonsterInsights account%2$s to unlock this feature.', 'google-analytics-for-wordpress' ), '<a href="' . esc_attr( monsterinsights_get_upgrade_link() ) . '">', '</a>' ) , '<br /><a href="' . monsterinsights_get_upgrade_link() . '" class="monsterinsights-upsell-box-button button button-primary" target="_blank" rel="noopener noreferrer" referrer="no-referrer" >' . esc_html__( 'Learn more about MonsterInsights Pro', 'google-analytics-for-wordpress' ) . '</a>'
			);

		// Forms
			$settings['forms']['forms_notice'] = array( 
				'id' => 'forms_notice',
				'name' => __( 'Want to setup Forms Tracking?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => sprintf( esc_html__( 'To setup form tracking, please %1$supgrade your MonsterInsights account%2$s to unlock this feature.', 'google-analytics-for-wordpress' ), '<a href="' . esc_attr( monsterinsights_get_upgrade_link() ) . '">', '</a>' ) , '<br /><a href="' . monsterinsights_get_upgrade_link() . '" class="monsterinsights-upsell-box-button button button-primary" target="_blank" rel="noopener noreferrer" referrer="no-referrer" >' . esc_html__( 'Learn more about MonsterInsights Pro', 'google-analytics-for-wordpress' ) . '</a>'
			);

		// Media
			$settings['media']['media_notice'] = array( 
				'id' => 'media_notice',
				'name' => __( 'Want to setup Media Tracking?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => sprintf( esc_html__( 'To setup media tracking, please %1$supgrade your MonsterInsights account%2$s to unlock this feature.', 'google-analytics-for-wordpress' ), '<a href="' . esc_attr( monsterinsights_get_upgrade_link() ) . '">', '</a>' ) , '<br /><a href="' . monsterinsights_get_upgrade_link() . '" class="monsterinsights-upsell-box-button button button-primary" target="_blank" rel="noopener noreferrer" referrer="no-referrer" >' . esc_html__( 'Learn more about MonsterInsights Pro', 'google-analytics-for-wordpress' ) . '</a>'
			);

		// Membership
			$settings['membership']['membership_notice'] = array( 
				'id' => 'membership_notice',
				'name' => __( 'Want to setup Membership Tracking?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => sprintf( esc_html__( 'To setup membership tracking, please %1$supgrade your MonsterInsights account%2$s to unlock this feature.', 'google-analytics-for-wordpress' ), '<a href="' . esc_attr( monsterinsights_get_upgrade_link() ) . '">', '</a>' ) , '<br /><a href="' . monsterinsights_get_upgrade_link() . '" class="monsterinsights-upsell-box-button button button-primary" target="_blank" rel="noopener noreferrer" referrer="no-referrer" >' . esc_html__( 'Learn more about MonsterInsights Pro', 'google-analytics-for-wordpress' ) . '</a>'
			);

		// Dimensions
			$settings['dimensions']['dimensions_notice'] = array( 
				'id' => 'dimensions_notice',
				'name' => __( 'Want to setup Custom Dimensions?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => sprintf( esc_html__( 'To setup custom dimensions, please %1$supgrade your MonsterInsights account%2$s to unlock this feature.', 'google-analytics-for-wordpress' ), '<a href="' . esc_attr( monsterinsights_get_upgrade_link() ) . '">', '</a>' ) , '<br /><a href="' . monsterinsights_get_upgrade_link() . '" class="monsterinsights-upsell-box-button button button-primary" target="_blank" rel="noopener noreferrer" referrer="no-referrer" >' . esc_html__( 'Learn more about MonsterInsights Pro', 'google-analytics-for-wordpress' ) . '</a>'
			);

			// Performance
			$settings['performance']['performance_notice'] = array( 
				'id' => 'performance_notice',
				'name' => __( 'Want to setup Performance Tracking?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => sprintf( esc_html__( 'To setup performance tracking, please %1$supgrade your MonsterInsights account%2$s to unlock this feature.', 'google-analytics-for-wordpress' ), '<a href="' . esc_attr( monsterinsights_get_upgrade_link() ) . '">', '</a>' ) , '<br /><a href="' . monsterinsights_get_upgrade_link() . '" class="monsterinsights-upsell-box-button button button-primary" target="_blank" rel="noopener noreferrer" referrer="no-referrer" >' . esc_html__( 'Learn more about MonsterInsights Pro', 'google-analytics-for-wordpress' ) . '</a>'
			);

			// Reporting
			$settings['reporting']['reporting_notice'] = array( 
				'id' => 'reporting_notice',
				'name' => __( 'Want to setup Custom Reporting?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => sprintf( esc_html__( 'To setup custom reporting, please %1$supgrade your MonsterInsights account%2$s to unlock this feature.', 'google-analytics-for-wordpress' ), '<a href="' . esc_attr( monsterinsights_get_upgrade_link() ) . '">', '</a>' ) , '<br /><a href="' . monsterinsights_get_upgrade_link() . '" class="monsterinsights-upsell-box-button button button-primary" target="_blank" rel="noopener noreferrer" referrer="no-referrer" >' . esc_html__( 'Learn more about MonsterInsights Pro', 'google-analytics-for-wordpress' ) . '</a>'
			);

			// Reporting
			$settings['notifications']['notifications_notice'] = array( 
				'id' => 'notifications_notice',
				'name' => __( 'Want to setup Custom Notifications?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => sprintf( esc_html__( 'To setup custom notifications, please %1$supgrade your MonsterInsights account%2$s to unlock this feature.', 'google-analytics-for-wordpress' ), '<a href="' . esc_attr( monsterinsights_get_upgrade_link() ) . '">', '</a>' ) , '<br /><a href="' . monsterinsights_get_upgrade_link() . '" class="monsterinsights-upsell-box-button button button-primary" target="_blank" rel="noopener noreferrer" referrer="no-referrer" >' . esc_html__( 'Learn more about MonsterInsights Pro', 'google-analytics-for-wordpress' ) . '</a>'
			);

			// Reporting
			$settings['ecommerce']['ecommerce_notice'] = array( 
				'id' => 'ecommerce_notice',
				'name' => __( 'Want to setup eCommerce Tracking?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => sprintf( esc_html__( 'To setup ecommerce tracking, please %1$supgrade your MonsterInsights account%2$s to unlock this feature.', 'google-analytics-for-wordpress' ), '<a href="' . esc_attr( monsterinsights_get_upgrade_link() ) . '">', '</a>' ) , '<br /><a href="' . monsterinsights_get_upgrade_link() . '" class="monsterinsights-upsell-box-button button button-primary" target="_blank" rel="noopener noreferrer" referrer="no-referrer" >' . esc_html__( 'Learn more about MonsterInsights Pro', 'google-analytics-for-wordpress' ) . '</a>'
			);
	return $settings;
}
add_filter( 'monsterinsights_registered_settings', 'monsterinsights_registered_settings_filter' );