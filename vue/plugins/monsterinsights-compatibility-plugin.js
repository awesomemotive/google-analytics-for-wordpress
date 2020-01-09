// This is a vuex plugin which handles compatibility notices.
import { __, sprintf } from '@wordpress/i18n';

const MonsterInsightsCompatibilityPlugin = store => {
	store.subscribe( ( mutation, state ) => {
		// When the app is mounted, check if we need to add any notices.
		if ( '$_app/INIT' === mutation.type ) {
			const versions = state['$_app'].mi.versions;
			if ( versions.php_version_below_54 ) {
				store.commit( '$_app/ADD_NOTICE', {
					title: __( 'Yikes! PHP Update Required', process.env.VUE_APP_TEXTDOMAIN ),
					content: sprintf( __( 'MonsterInsights has detected that your site is running an outdated, insecure version of PHP (%1$s), which could be putting your site at risk for being hacked. WordPress stopped supporting your PHP version in April, 2019. Updating to the recommended version (PHP %2$s) only takes a few minutes and will make your website significantly faster and more secure.', process.env.VUE_APP_TEXTDOMAIN ), versions.php_version, '7.3' ),
					type: 'error',
					button: {
						enabled: true,
						text: __( 'Learn more about updating PHP', process.env.VUE_APP_TEXTDOMAIN ),
						link: versions.php_update_link,
					},
					dismissable: true,
					id: 'php_update_54',
				});
			} else if ( versions.wp_version_below_46 ) {
				store.commit( '$_app/ADD_NOTICE', {
					title: __( 'Yikes! WordPress Update Required', process.env.VUE_APP_TEXTDOMAIN ),
					content: sprintf( __( 'MonsterInsights has detected that your site is running an outdated version of WordPress (%s). MonsterInsights will stop supporting WordPress versions lower than 4.6 in April, 2019.  Updating WordPress takes just a few minutes and will also solve many bugs that exist in your WordPress install.', process.env.VUE_APP_TEXTDOMAIN ), versions.php_version, versions.php_min_version ),
					type: 'error',
					button: {
						enabled: true,
						text: __( 'Learn more about updating WordPress', process.env.VUE_APP_TEXTDOMAIN ),
						link: versions.wp_update_link,
					},
					dismissable: true,
					id: 'wp_update_46',
				});
			} else if ( versions.php_version_below_56 ) {
				store.commit( '$_app/ADD_NOTICE', {
					title: __( 'Yikes! PHP Update Required', process.env.VUE_APP_TEXTDOMAIN ),
					content: sprintf( __( 'MonsterInsights has detected that your site is running an outdated, insecure version of PHP (%1$s), which could be putting your site at risk for being hacked. WordPress stopped supporting your PHP version in April, 2019. Updating to the recommended version (PHP %2$s) only takes a few minutes and will make your website significantly faster and more secure.', process.env.VUE_APP_TEXTDOMAIN ), versions.php_version, '7.3' ),
					type: 'error',
					button: {
						enabled: true,
						text: __( 'Learn more about updating PHP', process.env.VUE_APP_TEXTDOMAIN ),
						link: versions.php_update_link,
					},
					dismissable: true,
					id: 'php_update_56',
				});
			} else if ( versions.wp_version_below_49 ) {
				store.commit( '$_app/ADD_NOTICE', {
					title: __( 'Yikes! WordPress Update Required', process.env.VUE_APP_TEXTDOMAIN ),
					content: sprintf( __( 'MonsterInsights has detected that your site is running an outdated version of WordPress (%s). MonsterInsights will stop supporting WordPress versions lower than 4.9 in October, 2019.  Updating WordPress takes just a few minutes and will also solve many bugs that exist in your WordPress install.', process.env.VUE_APP_TEXTDOMAIN ), versions.php_version, versions.php_min_version ),
					type: 'error',
					button: {
						enabled: true,
						text: __( 'Learn more about updating WordPress', process.env.VUE_APP_TEXTDOMAIN ),
						link: versions.wp_update_link,
					},
					dismissable: true,
					id: 'wp_update_49',
				});
			}
		}
	});
};

export default MonsterInsightsCompatibilityPlugin;
