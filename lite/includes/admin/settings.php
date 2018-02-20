<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { 
	exit;
}

function monsterinsights_registered_settings_filter( $settings ) {
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
				'name' => __( 'Want to use Google Optimize to retarget your website visitors and perform A/B split tests with ease?', 'google-analytics-for-wordpress'),
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
