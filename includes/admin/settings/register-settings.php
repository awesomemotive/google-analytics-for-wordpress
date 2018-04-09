<?php
/**
 * Registering MonsterInsights Settings
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Settings API
 * @author  Chris Christoff
 */

/**
 * Callback for getting all of the settings tabs for MonsterInsights.
 *
 * @since 6.0.0
 * @return array $tabs
 */
function monsterinsights_get_settings_tabs() {
	// Levels: lite, basic, plus, pro
	// coming soon = true (don't count on this)
	$tabs = array(
		'engagement' => array( 
			'title' => esc_html__( 'Engagement', 'google-analytics-for-wordpress' ),
			'level' => 'lite'
		),
		'demographics' => array( 
			'title' => esc_html__( 'Demographics', 'google-analytics-for-wordpress' ),
			'level' => 'lite'
		),
		'links' => array( 
			'title' => esc_html__( 'Link Attribution', 'google-analytics-for-wordpress' ),
			'level' => 'lite'
		),
		'files' => array( 
			'title' => esc_html__( 'File Downloads', 'google-analytics-for-wordpress' ),
			'level' => 'lite'
		),  
		'affiliates' => array( 
			'title' => esc_html__( 'Affiliate Links', 'google-analytics-for-wordpress' ),
			'level' => 'lite'
		),  
		'social' => array( 
			'title' => esc_html__( 'Social', 'google-analytics-for-wordpress' ),
			'level' => 'basic',
			'comingsoon' => true
		),
		'ads' => array( 
			'title' => esc_html__( 'Ads', 'google-analytics-for-wordpress' ),
			'level' => 'plus',
		),
		'forms' => array( 
			'title' => esc_html__( 'Forms', 'google-analytics-for-wordpress' ),
			'level' => 'pro',
		),
		'ecommerce' => array( 
			'title' => esc_html__( 'eCommerce', 'google-analytics-for-wordpress' ),
			'level' => 'pro'
		),
		'media' => array( 
			'title' => esc_html__( 'Media', 'google-analytics-for-wordpress' ),
			'level' => 'plus',
			'comingsoon' => true
		),
		'memberships' => array( 
			'title' => esc_html__( 'Memberships', 'google-analytics-for-wordpress' ),
			'level' => 'plus',
			'comingsoon' => true
		),
		'dimensions' => array( 
			'title' => esc_html__( 'Custom Dimensions', 'google-analytics-for-wordpress' ),
			'level' => 'pro',
		),
		'performance' => array( 
			'title' => esc_html__( 'Performance', 'google-analytics-for-wordpress' ),
			'level' => 'plus'
		),
		'amp' => array( 
			'title' => esc_html__( 'Google AMP', 'google-analytics-for-wordpress' ),
			'level' => 'plus'
		),
		'goptimize' => array( 
			'title' => esc_html__( 'Google Optimize', 'google-analytics-for-wordpress' ),
			'level' => 'pro'
		),
		'fbia' => array( 
			'title' => esc_html__( 'FB Instant Articles', 'google-analytics-for-wordpress' ),
			'level' => 'plus'
		),
		'bounce' => array( 
			'title' => esc_html__( 'Bounce Reduction', 'google-analytics-for-wordpress' ),
			'level' => 'plus',
			'comingsoon' => true
		),
		'reporting' => array( 
			'title' => esc_html__( 'Additional Reporting', 'google-analytics-for-wordpress' ),
			'level' => 'plus',
			'comingsoon' => true
		),
		'notifications' => array( 
			'title' => esc_html__( 'Notifications', 'google-analytics-for-wordpress' ),
			'level' => 'basic',
			'comingsoon' => true
		),
		'compatibility' => array( 
			'title' => esc_html__( 'Compatibility', 'google-analytics-for-wordpress' ),
			'level' => 'lite'
		),
		'permissions' => array( 
			'title' => esc_html__( 'Permissions', 'google-analytics-for-wordpress' ),
			'level' => 'lite'
		),
	);
	/**
	 * Developers Warning: MonsterInsights does not guarantee backwards compatiblity
	 * of tabs yet. We might add/remove/reorder/edit tabs for the first few major
	 * versions after 7.0 to ensure we've got the right layout for the long term. 
	 * Changes may be done without advance warning or announcement. You've been warned. 
	 */
	return apply_filters( 'monsterinsights_settings_tabs', $tabs );
}

/**
 * Retrieve the array of plugin settings
 *
 * @since 6.0.0
 * @return array
*/
function monsterinsights_get_registered_settings() {

	/**
	 * 'Whitelisted' MonsterInsights settings, filters are provided for each settings
	 * section to allow extensions and other plugins to add their own settings
	 */
	
	/**
	 * Developers Warning: MonsterInsights does not guarantee backwards compatiblity
	 * of settings or tabs. We may add/remove settings and/or tabs, or re-arrange the 
	 * settings panel as we need to. We provide these filters here right now for internal use only.
	 * As such the filters below may be removed or altered at any time, without advance
	 * warning or announcement. You've been warned. 
	 *
	 * We will eventually promise backwards compatibility on these filters below but we want to ensure
	 * for the first few major releases after 7.0 we do that the current settings arrangement won't stifle
	 * our development progress. Once we're confident we've made the correct decision on settings layout, 
	 * we'll remove this notice, and only at that point should you rely on the filters below.
	 */
	$monsterinsights_settings = array(
		/** Engagement Settings */
		'engagement' => apply_filters( 'monsterinsights_settings_engagement',
			array(
				'ignore_users' => array(
					'id'          => 'ignore_users',
					'name'        => __( 'Ignore these user roles from tracking:', 'google-analytics-for-wordpress' ),
					'desc'        => __( 'Users that have at least one of these roles will not be tracked into Google Analytics.', 'google-analytics-for-wordpress' ),
					'type'        => 'select',
					'options'     => monsterinsights_get_roles(),
					'select2'     => true,
					'multiple'    => true,
					'allowclear'  => true,
				),
				'events_mode' => array(
					'id'          => 'events_mode',
					'name'        => __( 'Enable MonsterInsights events tracking:', 'google-analytics-for-wordpress' ),
					'desc'        => __( 'This turns on our Javascript based tracking system which among other things tracks clicks on outbound, affiliate, mail, telephone, hashed and download links.', 'google-analytics-for-wordpress' ),
					'type' 		  => 'radio',
					'std'  		  => 'js',
					'options'     => array(
						'js'   => __( 'Yes (Recommended)', 'google-analytics-for-wordpress' ),
						'none' => __( 'No', 'google-analytics-for-wordpress' ),
					),
				),
			)
		),
		/** Demographics Settings */
		'demographics' => apply_filters('monsterinsights_settings_demographics',
			array(
				'demographics' => array(
					'id'          => 'demographics',
					'name'        => __( 'Enable Demographics and Interests Reports for Remarketing and Advertising', 'google-analytics-for-wordpress' ),
					'desc'        => sprintf( esc_html__( 'Check this setting to add the Demographics and Remarketing features to your Google Analytics tracking code. Make sure to enable Demographics and Remarketing in your Google Analytics account. We have a guide for how to do that in our %1$sknowledge base%2$s. For more information about Remarketing, we refer you to %3$sGoogle\'s documentation%2$s. Note that usage of this function is affected by privacy and cookie laws around the world. Be sure to follow the laws that affect your target audience.', 'google-analytics-for-wordpress' ), '<a href="https://www.monsterinsights.com/docs/enable-demographics-and-interests-report-in-google-analytics/#utm_medium=kb-link&amp;utm_source=gawp-config&amp;utm_campaign=wpgaplugin" target="_blank" rel="noopener noreferrer" referrer="no-referrer">',
									'</a>','<a href="https://support.google.com/analytics/answer/2444872?hl=' . get_locale() . '" target="_blank" rel="noopener noreferrer" referrer="no-referrer">'
					),
					'type' 		  => 'checkbox',
				),
				'anonymize_ips' => array(
					'id'          => 'anonymize_ips',
					'name'        => __( 'Anonymize IP addresses?', 'google-analytics-for-wordpress' ),
					'desc'        => sprintf( esc_html__( 'This adds %1$s, telling Google Analytics to anonymize the information sent by the tracker objects by removing the last octet of the IP address prior to its storage.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApi_gat?csw=1#_gat._anonymizeIp" target="_blank" rel="noopener noreferrer" referrer="no-referrer"><code>_anonymizeIp</code></a>' ),
					'type' 		  => 'checkbox',
				),
			)
		),
		/** Enhanced Link Attribution Settings */
		'links' => apply_filters('monsterinsights_settings_links',
			array(
				'enhanced_link_attribution' => array(
					'id'          => 'enhanced_link_attribution',
					'name'        => __( 'Enable enhanced link attribution?', 'google-analytics-for-wordpress' ),
					'desc'        => sprintf( esc_html__( 'Add %1$sEnhanced Link Attribution%2$s to your tracking code.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-link-attribution" target="_blank" rel="noopener noreferrer" referrer="no-referrer">', ' </a>' ),
					'type' 		  => 'checkbox',
				),
				'hash_tracking' => array(
					'id'          => 'hash_tracking',
					'name'        => __( 'Turn on anchor tracking', 'google-analytics-for-wordpress' ),
					'desc'        => esc_html__( 'Many WordPress "1-page" style themes rely on anchor tags for navigation to show virtual pages. The problem is that to Google Analytics, these are all just a single page, and it makes it hard to get meaningful statistics about pages viewed. This feature allows proper tracking in those themes.', 'google-analytics-for-wordpress' ),
					'type' 		  => 'checkbox',
				),
				'allow_anchor' => array(
					'id'          => 'allow_anchor',
					'name'        => __( 'Turn on allowAnchor', 'google-analytics-for-wordpress' ),
					'desc'        => sprintf( esc_html__( 'This adds a %1$s call to your tracking code, and makes RSS link tagging use a %2$s as well.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiCampaignTracking?csw=1#_gat.GA_Tracker_._setAllowAnchor" target="_blank" rel="noopener noreferrer" referrer="no-referrer"><code>_setAllowAnchor</code></a>', '<code>#</code>' ),
					'type' 		  => 'checkbox',
				),
				'add_allow_linker' => array(
					'id'          => 'add_allow_linker',
					'name'        => __( 'Turn on allowLinker', 'google-analytics-for-wordpress' ),
					'desc'        => sprintf( esc_html__( 'This adds a %1$s call to your tracking code, allowing you to use %2$s and related functions.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiDomainDirectory?csw=1#_gat.GA_Tracker_._setAllowLinker" target="_blank" rel="noopener noreferrer" referrer="no-referrer"><code>_setAllowLinker</code></a>', ' <code>_link</code>' ),
					'type' 		  => 'checkbox',
				),
				'tag_links_in_rss' => array(
					'id'          => 'tag_links_in_rss',
					'name'        => __( 'Turn on tag links in RSS', 'google-analytics-for-wordpress' ),
					'desc'        => sprintf( esc_html__( 'Do not use this feature if you use FeedBurner, as FeedBurner can do this automatically and better than this plugin can. Check %1$sthis help page%2$s for info on how to enable this feature in FeedBurner.', 'google-analytics-for-wordpress' ), '<a href="https://support.google.com/feedburner/answer/165769?hl=en&amp;ref_topic=13075" target="_blank" rel="noopener noreferrer" referrer="no-referrer">', '</a>' ),
					'type' 		  => 'checkbox',
				),
			)
		),
		/** File Download Settings */
		'files' => apply_filters('monsterinsights_settings_files',
			array(
				'track_download_as' => array(
					'id'          => 'track_download_as',
					'name'        => __( 'Track downloads as an:', 'google-analytics-for-wordpress' ),
					'desc'        => __( 'Tracking as pageviews is not recommended, as this would skew your statistics, but it does make it possible to track downloads as goals. This feature requires that event tracking be turned on.', 'google-analytics-for-wordpress' ),
					'type' 		  => 'radio',
					'std'  		  => 'event',
					'options'     => array(
						'event'     => __( 'Event (recommended)', 'google-analytics-for-wordpress' ),
						'pageview'  => __( 'Pageview', 'google-analytics-for-wordpress' )
					),
				),
				'extensions_of_files' => array( 		/* @todo: Select2 extensions of files */
					'id'          => 'extensions_of_files',
					'name'        => __( 'Extensions of files to track as downloads:', 'google-analytics-for-wordpress' ),
					'desc'        => __( 'If you have enabled event tracking, MonsterInsights will send an event/pageview to GA if a link to a local file has one of the above extensions.', 'google-analytics-for-wordpress' ),
					'type' 		  => 'text',
				),
			)
		),
		/** Affiliate Tracking Settings */
		'affiliates' => apply_filters('monsterinsights_settings_affiliates',
			array(
				'track_internal_as_outbound' => array(
					'id'          => 'track_internal_as_outbound',
					'name'        => __( 'Set path for internal links to track as outbound links', 'google-analytics-for-wordpress' ),
					'desc'        => sprintf( esc_html__( 'If you want to track all internal links that begin with %1$s, enter %1$s in the box above. If you have multiple prefixes you can separate them with comma\'s: %2$s', 'google-analytics-for-wordpress' ), '<code>/out/</code>', '<code>/out/,/recommends/</code>' ),
					'type' 		  => 'text',
				),
				'track_internal_as_label' => array(
					'id'          => 'track_internal_as_label',
					'name'        => __( 'Label for those links:', 'google-analytics-for-wordpress' ),
					'desc'        => sprintf( esc_html__( 'The label to use for these links, this will be added to where the click came from, so if the label is %s, the label for a click from the content of an article becomes "outbound-link-aff".', 'google-analytics-for-wordpress' ), '"aff"' ),
					'type' 		  => 'text',
				),
			)
		),
		/** Social Tracking Settings */
		'social' => apply_filters('monsterinsights_settings_social',
			array()
		),
		/** Ad Tracking Settings */
		'ads' => apply_filters('monsterinsights_settings_ads',
			array()
		),
		/** Forms Tracking Settings */
		'forms' => apply_filters('monsterinsights_settings_forms',
			array()
		),
		/** eCommerce Tracking Settings */
		'ecommerce' => apply_filters('monsterinsights_settings_ecommerce',
			array()
		),
		/** Media Tracking Settings */
		'media' => apply_filters('monsterinsights_settings_media',
			array()
		),
		/** Members Tracking Settings */
		'memberships' => apply_filters('monsterinsights_settings_memberships',
			array()
		),
		/** Dimensions Tracking Settings */
		'dimensions' => apply_filters('monsterinsights_settings_dimensions',
			array()
		),
		/** Performance Tracking Settings */
		'performance' => apply_filters('monsterinsights_settings_performance',
			array()
		),
		/** AMP Tracking Settings */
		'amp' => apply_filters('monsterinsights_settings_amp',
			array()
		),
		/** Google Optimize Tracking Settings */
		'goptimize' => apply_filters('monsterinsights_settings_goptimize',
			array()
		),
		/** Facebook Instant Articles Tracking Settings */
		'fbia' => apply_filters('monsterinsights_settings_fbia',
			array()
		),
		/** Bounce Reduction Settings */
		'bounce' => apply_filters('monsterinsights_settings_bounce',
			array()
		),
		/** Reporting Tracking Settings */
		'reporting' => apply_filters('monsterinsights_settings_reporting',
			array()
		),
		/** Notifications Tracking Settings */
		'notifications' => apply_filters('monsterinsights_settings_notifications',
			array()
		),
		/** Compatibility Tracking Settings */
		'compatibility' => apply_filters('monsterinsights_settings_compatibility',
			array(
				'subdomain_tracking' => array(
					'id'          => 'subdomain_tracking',
					'name'        => __( 'Domain to track as:', 'google-analytics-for-wordpress' ),
					'desc'        => sprintf( esc_html__( 'This allows you to %1$sset the domain%2$s that\'s used for tracking. Only is used if set to a value, else defaults to automatic determination. It is very rare that you would need to use this setting.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/tracking-snippet-reference" target="_blank" rel="noopener noreferrer" referrer="no-referrer">' ,'</a>' ),
					'type' 		  => 'text',
				),
				'custom_code' => array(
					'id'          => 'custom_code',
					'name'        => __( 'Custom code', 'google-analytics-for-wordpress' ),
					'desc'        => sprintf( esc_html__( 'Not for the average user: this allows you to add a line of code, to be added before the %1$s call.', 'google-analytics-for-wordpress' ), '<a href="https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiBasicConfiguration#_gat.GA_Tracker_._trackPageview" target="_blank" rel="noopener noreferrer" referrer="no-referrer"><code>_trackPageview</code></a>' ),
					'type' 		  => 'unfiltered_textarea',
				),
				'debug_mode' => array(
					'id'          => 'debug_mode',
					'name'        => __( 'Enable Debug Mode', 'google-analytics-for-wordpress' ),
					'desc'        => __( 'Turns on debugging in JS events tracking, logging of eCommerce requests and enables debug settings.', 'google-analytics-for-wordpress'),
					'type' 		  => 'checkbox',
				),
			)
		),
		/** Permissions Tracking Settings */
		'permissions' => apply_filters('monsterinsights_settings_permissions',
			array(
				'view_reports' => array(
					'id'          => 'view_reports',
					'name'        => __( 'Let these user roles see reports:', 'google-analytics-for-wordpress' ),
					'desc'        => sprintf( esc_html( 'Users that have at least one of these roles will be able to view the reports, along with any user with the %s capability.', 'google-analytics-for-wordpress' ), '<code>manage_options</code>'),
					'type'        => 'select',
					'options'     => monsterinsights_get_roles(),
					'select2'     => true,
					'multiple'    => true,
					'allowclear'  => true
				),
				'save_settings' => array(
					'id'          => 'save_settings',
					'name'        => __( 'Let these user roles save settings:', 'google-analytics-for-wordpress' ),
					'desc'        => sprintf( esc_html__( 'Users that have at least one of these roles will be able to view and save the settings panel, along with any user with the %s capability.', 'google-analytics-for-wordpress'), '<code>manage_options</code>' ),
					'type'        => 'select',
					'options'     => monsterinsights_get_roles(),
					'select2'     => true,
					'multiple'    => true,
					'allowclear'  => true
				),
				'automatic_updates' => array(
					'id'          => 'automatic_updates',
					'name'        => __( 'Automatic Updates', 'google-analytics-for-wordpress' ),
					'type' 		  => 'radio',
					'std'  		  => 'none',
					'options'     => array(
						'all'     => __( 'Yes (Recommended) - Get the latest features, bugfixes, and security updates as they are released.', 'google-analytics-for-wordpress' ),
						'minor'   => __( 'Minor Only - Only get bugfixes and security updates, but not major features.', 'google-analytics-for-wordpress' ),
						'none'    => __( 'None - Manually update everything.', 'google-analytics-for-wordpress' ),
					),
				),
				'anonymous_data' => array(
					'id'          => 'anonymous_data',
					'name'        => __( 'Allow Usage Tracking', 'google-analytics-for-wordpress' ),
					'desc'        => __( 'By allowing us to track usage data we can better help you, because we know with which WordPress configurations, themes and plugins we should test.', 'google-analytics-for-wordpress' ),
					'type' 		  => 'checkbox',
				),
			)
		),
	);
    if ( monsterinsights_is_pro_version() ) {
       unset( $monsterinsights_settings['permissions']['anonymous_data'] );
    }
	return apply_filters( 'monsterinsights_registered_settings', $monsterinsights_settings );
}
