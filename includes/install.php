<?php
/**
 * MonsterInsights Installation and Automatic Upgrades.
 *
 * This file handles setting up new
 * MonsterInsights installs as well as performing
 * behind the scene upgrades between
 * MonsterInsights versions.
 *
 * @package MonsterInsights
 * @subpackage Install/Upgrade
 * @since 6.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MonsterInsights Install.
 *
 * This class handles a new MI install
 * as well as automatic (non-user initiated)
 * upgrade routines.
 *
 * @since 6.0.0
 * @access public
 */
class MonsterInsights_Install {

	/**
	 * MI Settings.
	 *
	 * @since 6.0.0
	 * @access public
	 * @var array $new_settings When the init() function starts, initially
	 *      					contains the original settings. At the end
	 *      				 	of init() contains the settings to save.
	 */
	public $new_settings = array();

	/**
	 * Install/Upgrade routine.
	 *
	 * This function is what is called to actually install MI data on new installs and to do
	 * behind the scenes upgrades on MI upgrades. If this function contains a bug, the results
	 * can be catastrophic. This function gets the highest priority in all of MI for unit tests.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function init() {

		// Get a copy of the current MI settings.
		$this->new_settings = get_option( monsterinsights_get_option_name() );


		$version = get_option( 'monsterinsights_current_version', false );
		$cachec  = false; // have we forced an object cache to be cleared already (so we don't clear it unnecessarily)

		// if new install or Yoast Era instal
		if ( ! $version ) {
			// See if from Yoast
			$yoast   = get_option( 'yst_ga', false );

			// In case from Yoast, start from scratch
			delete_option( 'yoast-ga-access_token' );
			delete_option( 'yoast-ga-refresh_token' );
			delete_option( 'yst_ga' );
			delete_option( 'yst_ga_api' );

			$this->new_install();

			// set db version (Do not increment! See below large comment)
			update_option( 'monsterinsights_db_version', '7.4.0' );

			// Remove Yoast hook if present
			if ( wp_next_scheduled( 'yst_ga_aggregate_data' ) ) {
				wp_clear_scheduled_hook( 'yst_ga_aggregate_data' );
			}

			// Clear cache since coming from Yoast
			if ( ! $cachec && ! empty( $yoast ) ) {
				wp_cache_flush();
				$cachec = true;
			}
		} else { // if existing install
			if ( version_compare( $version, '6.0.11', '<' ) ) {
				if ( ! $cachec ) {
					wp_cache_flush();
					$cachec = true;
				}
			}

			if ( version_compare( $version, '7.0.0', '<' ) ) {
				$this->v700_upgrades();
			}

			if ( version_compare( $version, '7.4.0', '<' ) ) {
				$this->v740_upgrades();
				// Do not increment! See below large comment
				update_option( 'monsterinsights_db_version', '7.4.0' );
			}

			if ( version_compare( $version, '7.5.0', '<' ) ) {
				$this->v750_upgrades();
			}

			// Do not use. See monsterinsights_after_install_routine comment below.
			do_action( 'monsterinsights_after_existing_upgrade_routine', $version );
			$version = get_option( 'monsterinsights_current_version', $version );
			update_option( 'monsterinsights_version_upgraded_from', $version );
		}

		// This hook is used primarily by the Pro version to run some Pro
		// specific install stuff. Please do not use this hook. It is not
		// considered a public hook by MI's dev team and can/will be removed,
		// relocated, and/or altered without warning at any time. You've been warned.
		// As this hook is not for public use, we've intentionally not docbloc'd this
		// hook to avoid developers seeing it future public dev docs.
		do_action( 'monsterinsights_after_install_routine', $version );

		// This is the version of MI installed
		update_option( 'monsterinsights_current_version', MONSTERINSIGHTS_VERSION );

		// This is where we save MI settings
		update_option( monsterinsights_get_option_name(), $this->new_settings );

		// There's no code for this function below this. Just an explanation
		// of the MI core options.

		/**
		 * Explanation of MonsterInsights core options
		 *
		 * By now your head is probably spinning trying to figure
		 * out what all of these version options are for. Note, I've abbreviated
		 * "monsterinsights" to "mi" in the options names to make this table easier
		 * to read.
		 *
		 * Here's a basic rundown:
		 *
		 * mi_current_version:  This starts with the actual version MI was
		 * 						installed on. We use this version to
		 * 						determine whether or not a site needs
		 * 						to run one of the behind the scenes
		 * 						MI upgrade routines. This version is updated
		 * 						every time a minor or major background upgrade
		 * 						routine is run. Generally lags behind the
		 * 						MONSTERINSIGHTS_VERSION constant by at most a couple minor
		 * 						versions. Never lags behind by 1 major version
		 * 						or more generally.
		 *
		 * mi_db_version: 		This is different from mi_current_version.
		 * 						Unlike the former, this is used to determine
		 * 						if a site needs to run a *user* initiated
		 * 						upgrade routine (incremented in MI_Upgrade class). This
		 * 						value is only update when a user initiated
		 * 						upgrade routine is done. Because we do very
		 * 						few user initiated upgrades compared to
		 * 						automatic ones, this version can lag behind by
		 * 						2 or even 3 major versions. Generally contains
		 * 						the current major version.
		 *
		 * mi_settings:		    Returned by monsterinsights_get_option_name(), this
		 * 						is actually "monsterinsights_settings" for both pro
		 * 						and lite version. However we use a helper function to
		 * 						retrieve the option name in case we ever decide down the
		 * 						road to maintain seperate options for the Lite and Pro versions.
		 * 					 	If you need to access MI's settings directly, (as opposed to our
		 * 					 	monsterinsights_get_option helper which uses the option name helper
		 * 					 	automatically), you should use this function to get the
		 * 					 	name of the option to retrieve.
		 *
		 * Therefore you should never increment mi_db_version in this file and always increment mi_current_version.
		 */
	}


	/**
	 * New MonsterInsights Install routine.
	 *
	 * This function installs all of the default
	 * things on new MI installs. Flight 5476 with
	 * non-stop service to a whole world of
	 * possibilities is now boarding.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function new_install() {

		// Add default settings values
		$this->new_settings = $this->get_monsterinsights_default_values();

		$this->maybe_import_thirstyaffiliates_options();

		$data = array(
			'installed_version' => MONSTERINSIGHTS_VERSION,
			'installed_date'    => time(),
			'installed_pro'     => monsterinsights_is_pro_version(),
		);

		update_option( 'monsterinsights_over_time', $data );

		// Let addons + MI Pro/Lite hook in here. @todo: doc as nonpublic
		do_action( 'monsterinsights_after_new_install_routine', MONSTERINSIGHTS_VERSION );
	}

	public function get_monsterinsights_default_values() {
		return array(
			'enable_affiliate_links'    => true,
			'affiliate_links'           => array(
				array(
					'path'  => '/go/',
					'label' => 'affiliate',
				),
				array(
					'path'  => '/recommend/',
					'label' => 'affiliate',
				)
			),
			'demographics'              => 1,
			'ignore_users'              => array( 'administrator' ),
			'dashboards_disabled'       => 0,
			'anonymize_ips'             => 0,
			'extensions_of_files'       => 'doc,exe,js,pdf,ppt,tgz,zip,xls',
			'subdomain_tracking'        => '',
			'link_attribution'          => true,
			'tag_links_in_rss'          => true,
			'allow_anchor'              => 0,
			'add_allow_linker'          => 0,
			'custom_code'               => '',
			'save_settings'             => array( 'administrator' ),
			'view_reports'              => array( 'administrator', 'editor' ),
			'events_mode'               => 'js',
			'tracking_mode'             => 'analytics',
		);
	}

	/**
	 * Check if ThirstyAffiliates plugin is installed and use the link prefix value in the affiliate settings.
	 *
	 * @return void
	 */
	public function maybe_import_thirstyaffiliates_options() {

		// Check if ThirstyAffiliates is installed.
		if ( ! function_exists( 'ThirstyAffiliates' ) ) {
			return;
		}

		$link_prefix = get_option( 'ta_link_prefix', 'recommends' );

		if ( $link_prefix === 'custom' ) {
			$link_prefix = get_option( 'ta_link_prefix_custom', 'recommends' );
		}

		if ( ! empty( $link_prefix ) ) {

			// Check if prefix exists.
			$prefix_set = false;
			foreach ( $this->new_settings['affiliate_links'] as $affiliate_link ) {
				if ( $link_prefix === trim( $affiliate_link['path'], '/' ) ) {
					$prefix_set = true;
					break;
				}
			}

			if ( ! $prefix_set ) {
				$this->new_settings['affiliate_links'][] = array(
					'path'  => '/' . $link_prefix . '/',
					'label' => 'affiliate',
				);
			}
		}
	}

	/**
	 * MonsterInsights Version 7.0 upgrades.
	 *
	 * This function does the
	 * upgrade routine from MonsterInsights 6.2->7.0.
	 *
	 * @since 7.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function v700_upgrades() {
		// 1. Default all event tracking and tracking to GA + JS respectively
			// 3a Set tracking_mode to use analytics.js
			$this->new_settings['tracking_mode' ] = 'analytics';


			// 3b Set events mode to use JS if the events mode is not set explicitly to none
			if ( empty( $this->new_settings['events_mode' ] ) || $this->new_settings['events_mode' ] !== 'none' ) {
				$this->new_settings['events_mode' ] = 'js';
			}

		// 2. Migrate manual UA codes
			// 2a Manual UA has the lowest priority
			if ( ! empty( $this->new_settings['manual_ua_code' ] ) ) {
				// Set as manual UA code
				is_network_admin() ? update_site_option( 'monsterinsights_network_profile', array( 'manual' => $this->new_settings['manual_ua_code' ] ) ) : update_option( 'monsterinsights_site_profile', array( 'manual' => $this->new_settings['manual_ua_code' ] ) );
			}

			// 2b Then try the oAuth UA code
			if ( ! empty( $this->new_settings['analytics_profile_code' ] ) ) {
				// Set as manual UA code
				is_network_admin() ? update_site_option( 'monsterinsights_network_profile', array( 'manual' => $this->new_settings['analytics_profile_code' ] ) ) : update_option( 'monsterinsights_site_profile', array( 'manual' => $this->new_settings['analytics_profile_code' ] ) );
			}

		// 3. Migrate License keys
		if ( is_multisite() ) {
			$ms_license = get_site_option( 'monsterinsights_license', '' );
			if ( $ms_license ) {
				update_site_option( 'monsterinsights_network_license_updates', get_site_option( 'monsterinsights_license_updates', '' ) );
				update_site_option( 'monsterinsights_network_license', $ms_license );
			}
		}
	}

	/**
	 * Upgrade routine for the new settings panel, onboarding wizard, and the internal-as-outbound v2 settings system.
	 */
	public function v740_upgrades() {

		// 1. Settings Conversions:
			// Convert affiliate field to repeater format
			if ( ! empty( $this->new_settings['track_internal_as_outbound'] ) ) {
				$affiliate_old_paths = $this->new_settings['track_internal_as_outbound'];
				$affiliate_old_label = isset( $this->new_settings['track_internal_as_label'] ) ? $this->new_settings['track_internal_as_label'] : '';

				$new_paths = explode( ',', $affiliate_old_paths );

				$this->new_settings['affiliate_links'] = array();
				if ( ! empty( $new_paths ) ) {
					$this->new_settings['enable_affiliate_links'] = true;
					foreach ( $new_paths as $new_path ) {
						$this->new_settings['affiliate_links'][] = array(
							'path'  => $new_path,
							'label' => $affiliate_old_label,
						);
					}
				}

				$settings = array(
					'track_internal_as_outbound',
					'track_internal_as_label',
				);
				foreach ( $settings as $setting ) {
					if ( ! empty( $this->new_settings[ $setting ] ) ) {
						unset( $this->new_settings[ $setting ] );
					}
				}
			}

			// Update option to disable just reports or also the dashboard widget.
			if ( isset( $this->new_settings['dashboards_disabled'] ) && $this->new_settings['dashboards_disabled'] ) {
				$this->new_settings['dashboards_disabled'] = 'disabled';
			}

			$this->new_settings['tracking_mode'] = 'analytics';
			$this->new_settings['events_mode']   = 'js';

			// If opted in during allow_tracking era, move that over
			if ( ! empty( $this->new_settings['allow_tracking'] ) ) {
				$this->new_settings['anonymous_data'] = 1;
			}

		// 2. Remove Yoast stuff
			delete_option( 'yoast-ga-access_token' );
			delete_option( 'yoast-ga-refresh_token' );
			delete_option( 'yst_ga' );
			delete_option( 'yst_ga_api' );


		// 3. Remove fake settings from other plugins using our key for some reason and old settings of ours
			$settings = array(
				'debug_mode',
				'track_download_as',
				'analytics_profile',
				'analytics_profile_code',
				'analytics_profile_name',
				'manual_ua_code',
				'track_outbound',
				'track_download_as',
				'enhanced_link_attribution',
				'oauth_version',
				'monsterinsights_oauth_status',
				'firebug_lite',
				'google_auth_code',
				'allow_tracking',
			);

			foreach ( $settings as $setting ) {
				if ( ! empty( $this->new_settings[ $setting ] ) ) {
					unset( $this->new_settings[ $setting ] );
				}
			}

			$settings = array(
				'_repeated',
				'ajax',
				'asmselect0',
				'bawac_force_nonce',
				'icl_post_language',
				'saved_values',
				'mlcf_email',
				'mlcf_name',
				'cron_failed',
				'undefined',
				'cf_email',
				'cf_message',
				'cf_name',
				'cf_number',
				'cf_phone',
				'cf_subject',
				'content',
				'credentials',
				'cron_failed',
				'cron_last_run',
				'global-css',
				'grids',
				'page',
				'punch-fonts',
				'return_tab',
				'skins',
				'navigation-skins',
				'title',
				'type',
				'wpcf_email',
				'wpcf_your_name',
			);

			foreach ( $settings as $setting ) {
				if ( ! empty( $this->new_settings[ $setting ] ) ) {
					unset( $this->new_settings[ $setting ] );
				}
			}

		// 4. Remove old crons
			if ( wp_next_scheduled( 'monsterinsights_daily_cron' ) ) {
				wp_clear_scheduled_hook( 'monsterinsights_daily_cron' );
			}
			if ( wp_next_scheduled( 'monsterinsights_send_tracking_data' ) ) {
				wp_clear_scheduled_hook( 'monsterinsights_send_tracking_data' );
			}

			if ( wp_next_scheduled( 'monsterinsights_send_tracking_checkin' ) ) {
				wp_clear_scheduled_hook( 'monsterinsights_send_tracking_checkin' );
			}

			if ( wp_next_scheduled( 'monsterinsights_weekly_cron' ) ) {
				wp_clear_scheduled_hook( 'monsterinsights_weekly_cron' );
			}

			if ( wp_next_scheduled( 'yst_ga_aggregate_data' ) ) {
				wp_clear_scheduled_hook( 'yst_ga_aggregate_data' );
			}

			delete_option( 'monsterinsights_tracking_last_send' );
			delete_option( 'mi_tracking_last_send' );

		// 5. Remove old option
			delete_option( 'monsterinsights_settings_version' );
	}


	/**
	 * Upgrade routine
	 */
	public function v750_upgrades() {
		// 1. One time re-prompt for anonymous data (due to migration bug now fixed)
		// if ( ! monsterinsights_is_pro_version() ) {
		// 	if ( empty( $this->new_settings[ 'anonymous_data' ] ) ) {
		// 		update_option( 'monsterinsights_tracking_notice', 0 );
		// 	}
		// }
		//
		// 2. Clear old settings ( 	'tracking_mode','events_mode',)


		// 3. Attempt to extract the cross-domain settings from the Custom Code area and use in the new option.
		$custom_code = isset( $this->new_settings['custom_code'] ) ? $this->new_settings['custom_code'] : '';
		if ( ! empty( $custom_code ) ) {
			$pattern = '/(?:\'linker:autoLink\', )(?:\[)(.*)(?:\])/m';
			preg_match_all( $pattern, $custom_code, $matches, PREG_SET_ORDER, 0 );
			if ( ! empty( $matches ) && isset( $matches[0] ) && isset( $matches[0][1] ) ) {
				$cross_domains = array();
				$domains       = explode( ',', $matches[0][1] );
				foreach ( $domains as $key => $domain ) {
					$domain          = trim( $domain );
					$cross_domains[] = array(
						'domain' => trim( $domain, '\'\"' ),
					);
				}
				$this->new_settings['add_allow_linker'] = true;
				$this->new_settings['cross_domains']    = $cross_domains;

				$notices = get_option( 'monsterinsights_notices' );
				if ( ! is_array( $notices ) ) {
					$notices = array();
				}
				$notices['monsterinsights_cross_domains_extracted'] = false;
				update_option( 'monsterinsights_notices', $notices );
			}
		}
	}
}
