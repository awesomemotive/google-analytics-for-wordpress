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
				'no_label' => true,
				'name' => __( 'Want to increase your traffic, conversion, & engagement?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can add Social tracking to see who's clicking on your social share links, so you can track and maximize your social sharing exposure.", 'google-analytics-for-wordpress' )
			);

		// Ads
			$settings['ads']['ads_notice'] = array( 
				'id' => 'ads_notice',
				'no_label' => true,
				'name' => __( 'Want to increase your traffic, conversion, & engagement?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can add Ads tracking to see who's clicking on your Google Ads, so you can increase your revenue.", 'google-analytics-for-wordpress' )
			);

		// Forms
			$settings['forms']['forms_notice'] = array( 
				'id' => 'forms_notice',
				'no_label' => true,
				'name' => __( 'Want to increase your traffic, conversion, & engagement?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can add Forms tracking to see who's seeing and submitting your forms, so you can increase your conversion rate.", 'google-analytics-for-wordpress' )
			);

		// Media
			$settings['media']['media_notice'] = array( 
				'id' => 'media_notice',
				'no_label' => true,
				'name' => __( 'Want to increase your traffic, conversion, & engagement?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can add Media tracking to see who's interacting with the media on your site, so you know what your users are most interested in on your site. You can use this to tailor future content to meet your audience's interest to promote repeat visitors and expand your average user's time spent visiting your website on each visit.", 'google-analytics-for-wordpress' )
			);

		// Membership
			$settings['membership']['membership_notice'] = array( 
				'id' => 'membership_notice',
				'no_label' => true,
				'name' => __( 'Want to increase your traffic, conversion, & engagement?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can add Membership tracking.", 'google-analytics-for-wordpress' )
			);

			// Dimensions
			$settings['dimensions']['dimensions_notice'] = array( 
				'id' => 'dimensions_notice',
				'no_label' => true,
				'name' => __( 'Want to increase your traffic, conversion, & engagement?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can add Custom Dimensions and track who's the most popular author on your site, which post types get the most traffic, and more. Why not check it out?", 'google-analytics-for-wordpress' )
			);

			// Performance
			$settings['performance']['performance_notice'] = array( 
				'id' => 'performance_notice',
				'no_label' => true,
				'name' => __( 'Want to increase your traffic, conversion, & engagement?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can adjust the sample rate so you don't exceed Google Analytics' processing limit. You can also use it to enable Google Optimize for A/B testing and personalization.", 'google-analytics-for-wordpress' )
			);

			// Reporting
			$settings['reporting']['reporting_notice'] = array( 
				'id' => 'reporting_notice',
				'no_label' => true,
				'name' => __( 'Want to increase your traffic, conversion, & engagement?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can enable enhanced reporting.", 'google-analytics-for-wordpress' )
			);

			// Google AMP
			$settings['amp']['amp_notice'] = array( 
				'id' => 'amp_notice',
				'no_label' => true,
				'name' => __( 'Want to use track users visiting your AMP pages?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can enable AMP page tracking.", 'google-analytics-for-wordpress' )
			);

			// Google Optimize
			$settings['goptimize']['goptimize_notice'] = array( 
				'id' => 'goptimize_notice',
				'no_label' => true,
				'name' => __( 'Want to use Google Optimize to retarget your website vistors and perform A/B split tests with ease?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can enable Google Optimize.", 'google-analytics-for-wordpress' )
			);

			// Facebook Instant Articles
			$settings['fbia']['fbia_notice'] = array( 
				'id' => 'fbia_notice',
				'no_label' => true,
				'name' => __( 'Want to expand your website audience beyond your website with Facebook Instant Articles?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can track your Facebook Instant Article visitors with MonsterInsights.", 'google-analytics-for-wordpress' )
			);

			// Bounce Reduction
			$settings['bounce']['bounce_notice'] = array( 
				'id' => 'bounce_notice',
				'no_label' => true,
				'name' => __( 'Want to adjust your website bounce rate?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can adjust your Google Analytics bounce rate with MonsterInsights.", 'google-analytics-for-wordpress' )
			);

			// Notifications
			$settings['notifications']['notifications_notice'] = array( 
				'id' => 'notifications_notice',
				'no_label' => true,
				'name' => __( 'Want to increase your traffic, conversion, & engagement?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can enable notifications.", 'google-analytics-for-wordpress' )
			);

			// eCommerce
			$settings['ecommerce']['ecommerce_notice'] = array( 
				'id' => 'ecommerce_notice',
				'no_label' => true,
				'name' => __( 'Want to increase your traffic, conversion, & engagement?', 'google-analytics-for-wordpress'),
				'type' => 'upgrade_notice',
				'desc' => esc_html__( "By upgrading to MonsterInsights Pro, you can add Ecommerce tracking to see who's buying your product, what's the most popular item on your store, the average order value, and tons more.", 'google-analytics-for-wordpress' )
			);

	return $settings;
}
add_filter( 'monsterinsights_registered_settings', 'monsterinsights_registered_settings_filter' );
