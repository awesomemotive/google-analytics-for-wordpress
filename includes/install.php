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
	 * @todo  I'd like to add preflight checks here.
	 * @todo  I'd like to add a recovery system here.
	 * 
	 * @return void
	 */
	public function init() {

		// Get a copy of the current MI settings.
		$this->new_settings = get_option( monsterinsights_get_option_name() );


		$version = get_option( 'monsterinsights_current_version', false );
		$yoast   = get_option( 'yst_ga', false );
		$cachec  = false; // have we forced an object cache to be cleared already (so we don't clear it unnecessarily)

		// if new install and have not used Yoast previously
		if ( ! $version && ! $yoast ) {

			$this->new_install();
			// This is the version used for MI upgrade routines.
			update_option( 'monsterinsights_db_version', '6.2.0' );
			
		} else if ( ! $version && $yoast ) { // if new install and has used Yoast previously

			$this->upgrade_from_yoast();
			// This is the version used for MI upgrade routines.
			update_option( 'monsterinsights_db_version', '6.2.0' );

			if ( ! $cachec ) {
				wp_cache_flush();
				$cachec = true;
			}
			
		} else { // if existing install
			if ( version_compare( $version, '6.0.2', '<' ) ) {
				$this->v602_upgrades();
			}
			
			if ( version_compare( $version, '6.0.11', '<' ) ) {
				$this->v6011_upgrades();

				if ( ! $cachec ) {
					wp_cache_flush();
					$cachec = true;
				}
			}
			if ( version_compare( $version, '6.2.0', '<' ) ) {
				$this->v620_upgrades();
			}

			update_option( 'monsterinsights_db_version', '6.2.0' );
			
			// @todo: doc as nonpublic
			
			update_option( 'monsterinsights_version_upgraded_from', $version );
			do_action( 'monsterinsights_after_existing_upgrade_routine', $version );
		}

		// This hook is used primarily by the Pro version to run some Pro
		// specific install stuff. Please do not use this hook. It is not 
		// considered a public hook by MI's dev team and can/will be removed, 
		// relocated, and/or altered without warning at any time. You've been warned.
		// As this hook is not for public use, we've intentionally not docbloc'd this
		// hook to avoid developers seeing it future public dev docs.
		do_action( 'monsterinsights_after_install_routine', $version );

		// This is the version of the MI settings themselves
		update_option( 'monsterinsights_settings_version', '6.0.0' );

		// This is the version of MI installed
		update_option( 'monsterinsights_current_version', MONSTERINSIGHTS_VERSION );

		// This is where we save MI settings
		update_option( monsterinsights_get_option_name(), $this->new_settings );

		// This is where we redirect to the MI welcome page
		//set_transient( '_monsterinsights_activation_redirect', true, 30 ); @todo: Investigate

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
		 * mi_settings_version: Used to store the version 
		 * 						of the MI settings. We use this
		 * 						so we can do upgrade routines where
		 * 						we'd have to do different actions based
		 * 						on the version the settings were installed
		 * 						in. For example: if we made a mistake with 
		 * 						the value we saved as the default for 
		 * 						a select setting, we can detect the version
		 * 						containing this mistake and correct it.
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
		 * 						or more.
		 *
		 * mi_db_version: 		This is different from mi_current_version.
		 * 						Unlike the former, this is used to determine
		 * 						if a site needs to run a *user* initiated
		 * 						upgrade routine (see MI_Upgrade class). This
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
		 * yst_ga:			    Yoast's old settings option. We no longer use this, though
		 * 						for backwards compatibility reasons we store the updated settings
		 * 						in this just for a little while longer. These settings are migrated
		 * 						to the new settings option when you upgrade to MonsterInsights
		 * 						6.0 or higher automatically.
		 *
		 * yst_* & yoast_*:		These are options from when the plugin was developed by
		 * 						Yoast, and also of the few point releases we did after
		 * 						the acquisition. Note, while we currently do backcompat
		 * 						on some of these options so other plugins will continue working
		 * 						please realize there will be a point in the near future that we 
		 * 						will no longer support them. Please do not use them anymore.
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

		$data = array(
			'installed_version' => MONSTERINSIGHTS_VERSION,
			'installed_date'    => time(),
			'installed_pro'     => monsterinsights_is_pro_version(),
		);

		update_option( 'monsterinsights_over_time', $data );

		// Add cron job
		if ( ! wp_next_scheduled( 'monsterinsights_daily_cron' ) ) {
			// Set the next event of fetching data
			wp_schedule_event( strtotime( date( 'Y-m-d', strtotime( 'tomorrow' ) ) . ' 00:05:00 ' ), 'daily', 'monsterinsights_daily_cron' );
		}

		// Let addons + MI Pro/Lite hook in here. @todo: doc as nonpublic
		do_action( 'monsterinsights_after_new_install_routine', MONSTERINSIGHTS_VERSION );
	}

	/**
	 * Upgrade from Yoast.
	 *
	 * This function does the upgrade routine from Yoast to this plugin version.
	 * Includes all of Yoast's previous routines.
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @return void
	 */
	public function upgrade_from_yoast() {
		// Do Yoast's Old Routines
		$options = get_option( 'yst_ga', array() );
		if ( ! empty( $options['ga_general'] ) ) {
			$options = $options['ga_general'];
		}

		$tracking_code = null;
		if ( ! empty( $options['analytics_profile'] ) && ! empty( $options['analytics_profile_code'] ) ) {
			$tracking_code = $options['analytics_profile_code'];
		} else if ( ! empty( $options['analytics_profile'] ) && empty( $options['analytics_profile_code'] ) ) {
			// Analytics profile is still holding the UA code
			$tracking_code = $options['analytics_profile'];
		}

		if ( ! empty( $options['manual_ua_code_field'] ) && ! empty( $options['manual_ua_code'] ) ) {
			$tracking_code = $options['manual_ua_code_field'];
		}

		if ( ! isset( $options['version'] ) && is_null( $tracking_code ) ) {
			$old_options = get_option( 'Yoast_Google_Analytics' );
			if ( isset( $old_options ) && is_array( $old_options ) ) {
				if ( isset( $old_options['uastring'] ) && '' !== trim( $old_options['uastring'] ) ) {
					// Save UA as manual UA, instead of saving all the old GA crap
					$options['manual_ua_code']       = 1;
					$options['manual_ua_code_field'] = $old_options['uastring'];
				}
				// Other settings
				$options['allow_anchor']               = $old_options['allowanchor'];
				$options['add_allow_linker']           = $old_options['allowlinker'];
				$options['anonymous_data']             = $old_options['anonymizeip'];
				$options['track_outbound']             = $old_options['trackoutbound'];
				$options['track_internal_as_outbound'] = $old_options['internallink'];
				$options['track_internal_as_label']    = $old_options['internallinklabel'];
				$options['extensions_of_files']        = $old_options['dlextensions'];
			}
			delete_option( 'Yoast_Google_Analytics' );
		}
		// 5.0.0 to 5.0.1 fix of ignore users array
		if ( ! isset( $options['version'] ) || version_compare( $options['version'], '5.0.1', '<' ) ) {
			if ( isset( $options['ignore_users'] ) && ! is_array( $options['ignore_users'] ) ) {
				$options['ignore_users'] = (array) $options['ignore_users'];
			}
		}
		// 5.1.2+ Remove firebug_lite from options, if set
		if ( ! isset ( $options['version'] ) || version_compare( $options['version'], '5.1.2', '<' ) ) {
			if ( isset( $options['firebug_lite'] ) ) {
				unset( $options['firebug_lite'] );
			}
		}
		// 5.2.8+ Add disabled dashboards option
		if ( ! isset ( $options['dashboards_disabled'] ) || version_compare( $options['version'], '5.2.8', '>' ) ) {
			$options['dashboards_disabled'] = 0;
		}
		// Check is API option already exists - if not add it
		$yst_ga_api = get_option( 'yst_ga_api' );
		if ( $yst_ga_api === false ) {
			add_option( 'yst_ga_api', array(), '', 'no' );
		}
		// Fallback to make sure every default option has a value
		$defaults = $this->get_yoast_default_values();
		if ( is_array( $defaults ) ) {
			foreach ( $defaults[ 'ga_general' ] as $key => $value ) {
				if ( ! isset( $options[ $key ] ) ) {
					$options[ $key ] = $value;
				}
			}
		}

		// Set to the current version now that we've done all needed upgrades
		$options['version'] = '5.5.3'; // Last Yoast codebase version
		$saved_options = get_option( 'yst_ga' );
		$saved_options[ 'ga_general' ] = $options;
		update_option( 'yst_ga', $saved_options );


		// Do license key switchover 
			$key     = '';
			$found   = false;
			$network = false;
			// Try network active Premium
			$is_key = get_site_option( 'google-analytics-by-yoast-premium_license', array() );
			if ( $is_key && ! empty( $is_key ) && is_array( $is_key ) && ! empty( $is_key['key'] ) && is_multisite() ){
				$key    = $is_key['key'];
				$found = true;
				$network = true;
			}

			// Try single site Premium
			if ( ! $found ) {
				$is_key = get_option( 'google-analytics-by-yoast-premium_license', array() );
				if ( $is_key && ! empty( $is_key ) && is_array( $is_key ) && ! empty( $is_key['key'] ) ){
					$key    = $is_key['key'];
					$found = true;
				}				
			}

			// Try network active Premium
			if ( ! $found ) {
				$is_key = get_site_option( 'monsterinsights-pro_license', array() );
				if ( $is_key && ! empty( $is_key ) && is_array( $is_key ) && ! empty( $is_key['key'] ) && is_multisite() ){
					$key    = $is_key['key'];
					$found = true;
					$network = true;
				}				
			}
			
			// Try single site Premium
			if ( ! $found ) {
				$is_key = get_option( 'monsterinsights-pro_license', array() );
				if ( $is_key && ! empty( $is_key ) && is_array( $is_key ) && ! empty( $is_key['key'] ) ){
					$key    = $is_key['key'];
					$found = true;
				}				
			}
			
			// Try network active ecommmerce
			if ( ! $found ) {
				$is_key = get_site_option( 'ecommerce-addon_license', array() );
				if ( $is_key && ! empty( $is_key ) && is_array( $is_key ) && ! empty( $is_key['key'] ) && is_multisite() ){
					$key    = $is_key['key'];
					$found = true;
					$network = true;
				}
			}
			// Try single site ecommerce
			if ( ! $found ) {
				$is_key = get_option( 'ecommerce-addon_license', array() );
				if ( $is_key && ! empty( $is_key ) && is_array( $is_key ) && ! empty( $is_key['key'] ) ){
					$key    = $is_key['key'];
					$found = true;
				}				
			}

			// set as new key for monsterinsights
			if ( $found && ! empty( $key ) ) {
				// In pro, install custom dimensions + ads. In lite, just save the key
				do_action( 'monsterinsights_upgrade_from_yoast', $key, $network );
			}

		// Next up: Settings Migration
		
			$options = get_option( 'yst_ga', array() );
			if ( ! empty( $options['ga_general'] ) ) {
				$options = $options['ga_general'];
			}


			// Let's remove the defaults
			if ( isset( $options['ga_general'] ) ) {
				unset( $options['ga_general'] );
			}

			// Let's remove unused options
			if ( isset( $options['yoast_ga_nonce'] ) ) {
				unset( $options['yoast_ga_nonce'] );
			}			
			if ( isset( $options['ga-form-settings'] ) ) {
				unset( $options['ga-form-settings'] );
			}
			if ( isset( $options['string_error_custom_dimensions'] ) ) {
				unset( $options['string_error_custom_dimensions'] );
			}
			if ( isset( $options['custom_metrics'] ) ) {
				unset( $options['custom_metrics'] );
			}	
			if ( isset( $options['track_full_url'] ) ) {
				unset( $options['track_full_url'] );
			}	
			if ( isset( $options['version'] ) ) {
				unset( $options['version'] );
			}

			// Migrate universal to tracking_mode
			if ( isset( $options['enable_universal'] ) ) {
				unset( $options['enable_universal'] );
				$options['tracking_mode'] = 'analytics';
			} else {
				$options['tracking_mode'] = 'ga';
			}

			// Migrate events tracking
			if ( isset( $options['track_outbound'] ) ) {
				unset( $options['track_outbound'] );
				$options['events_mode'] = 'php';
			} else {
				$options['events_mode'] = 'none';
			}

			// Migrate anonymous_data to allow tracking
			if ( isset( $options['anonymous_data'] ) ) {
				unset( $options['anonymous_data'] );
				$options['allow_tracking'] = 1;
			} else {
				$options['allow_tracking'] = 0;
			}

		
		// Migrate GA profile data if there
			// first let's try to salvage the current profile
			$access_token  = get_option( 'yoast-ga-access_token', array() );
			$refresh_token = get_option( 'yoast-ga-refresh_token', array() );
			$profiles      = get_option( 'yst_ga_api', array() );

			$profile_name  = ! empty( $options['analytics_profile'] ) ? $options['analytics_profile'] : ''; 

				if ( empty( $refresh_token ) && ! empty( $access_token['refresh_token'] ) ) {
					$refresh_token = $access_token['refresh_token'];
				}
	
				// We need a name and a profile
				if ( ! empty( $refresh_token ) && ! empty( $options['analytics_profile'] ) && ! empty( $profiles['ga_api_response_accounts'] ) ) {
					// See if we have an access token
					if ( ! empty( $access_token['access_token'] ) ) {
						if ( monsterinsights_is_pro_version() ) {
							update_option( 'monsterinsights_pro_access_token', $access_token['access_token'] );
						} else {
							update_option( 'monsterinsights_lite_access_token', $access_token['access_token'] );
						}
					}

					// We need a refresh token
					if ( monsterinsights_is_pro_version() ) {
						update_option( 'monsterinsights_pro_refresh_token', $refresh_token );
					} else {
						update_option( 'monsterinsights_lite_refresh_token', $refresh_token );
					}

					// If we can find the profile in the response save the name
					if ( ! empty( $profiles['ga_api_response_accounts'] ) && is_array( $profiles['ga_api_response_accounts'] ) ) {
						foreach ( $profiles['ga_api_response_accounts'] as $account ) {
							foreach ( $account['items'] as $profile ) {
								foreach ( $profile['items'] as $subprofile ) {
									if ( isset( $subprofile['id'] ) && $subprofile['id'] == $profile_name ) {
										$options['analytics_profile_name'] = $subprofile['name'];
										if ( empty( $options['analytics_profile_code'] ) ) {
											$options['analytics_profile_code'] = $subprofile['ua_code'];
										}
										break 3;
									}
								}
							}
						}
					}
					$options['cron_last_run'] = strtotime("-25 hours");
				} else {
					// if UA in manual code field, remove analytics profile fields if set
					if ( ! empty( $options['manual_ua_code_field' ] ) ) {
						if ( isset( $options['analytics_profile_code'] ) ) {
							unset( $options['analytics_profile_code'] );
						}
						if ( isset( $options['analytics_profile'] ) ) {
							unset( $options['analytics_profile'] );
						}
						$options['manual_ua_code'] = $options['manual_ua_code_field'];
						delete_option( 'yoast-ga-access_token' );
						delete_option( 'yoast-ga-refresh_token' );
						delete_option( 'yst_ga_api' );
					} else if ( ! empty( $options['analytics_profile_code' ] ) ) {
					// if UA in profile fields, remove others and use that
						$options['manual_ua_code'] = $options['analytics_profile_code'];
						if ( isset( $options['analytics_profile_code'] ) ) {
							unset( $options['analytics_profile_code'] );
						}
						if ( isset( $options['analytics_profile'] ) ) {
							unset( $options['analytics_profile'] );
						}
						delete_option( 'yoast-ga-access_token' );
						delete_option( 'yoast-ga-refresh_token' );
						delete_option( 'yst_ga_api' );
					}  else {
					// if UA in profile profiles, remove others and use that
						if ( ! empty( $options['analytics_profile' ] ) && ! empty( $profiles['ga_api_response_accounts'] ) && is_array( $profiles['ga_api_response_accounts'] ) ) {
							foreach ( $profiles as $account ) {
								foreach ( $account['items'] as $profile ) {
									foreach ( $profile['items'] as $subprofile ) {
										if ( isset( $subprofile['id'] ) && $subprofile['id'] == $options['analytics_profile' ] ) {
											$options['manual_ua_code'] = $subprofile['ua_code'];
											break 3;
										}
									}
								}
							}
						}
						delete_option( 'yoast-ga-access_token' );
						delete_option( 'yoast-ga-refresh_token' );
						delete_option( 'yst_ga_api' );
					}
				}
			
			if ( isset( $options['manual_ua_code_field'] ) ) {
				unset( $options['manual_ua_code_field'] );
			}

		// oAuth Stir Data Tank
			// Will happen automatically as cron_last_run set to 25 hours ago

		// Add oAuth version 
				$options['oauth_version'] = '1.0.0';

		$data = array(
			'installed_version' => MONSTERINSIGHTS_VERSION,
			'installed_date'    => time(),
			'installed_pro'     => monsterinsights_is_pro_version(),
		);

		update_option( 'monsterinsights_over_time', $data );
		
		// Add the cron job
		if ( ! wp_next_scheduled( 'monsterinsights_daily_cron' ) ) {
			// Set the next event of fetching data
			wp_schedule_event( strtotime( date( 'Y-m-d', strtotime( 'tomorrow' ) ) . ' 00:05:00 ' ), 'daily', 'monsterinsights_daily_cron' );
		}
		
		// Finish up
			// Save the new settings
			$this->new_settings = $options;
	}

	public function get_yoast_default_values() {
		$options = array(
			'ga_general' => array(
				'analytics_profile'          => null,
				'analytics_profile_code'     => null,
				'manual_ua_code'             => 0,
				'manual_ua_code_field'       => null,
				'track_internal_as_outbound' => null,
				'track_internal_as_label'    => null,
				'track_outbound'             => 0,
				'anonymous_data'             => 0,
				'enable_universal'           => 1,
				'demographics'               => 0,
				'ignore_users'               => array( 'administrator', 'editor' ),
				'dashboards_disabled'        => 0,
				'anonymize_ips'              => 0,
				'track_download_as'          => 'event',
				'extensions_of_files'        => 'doc,exe,js,pdf,ppt,tgz,zip,xls',
				'track_full_url'             => 'domain',
				'subdomain_tracking'         => null,
				'tag_links_in_rss'           => 0,
				'allow_anchor'               => 0,
				'add_allow_linker'           => 0,
				'enhanced_link_attribution'  => 0,
				'custom_code'                => null,
				'debug_mode'                 => 0,
			)
		);
		$options = apply_filters( 'yst_ga_default-ga-values', $options, 'ga_general' );
		return $options;
	}

	public function get_monsterinsights_default_values() {
		return array(
			'analytics_profile'          => '',
			'analytics_profile_code'     => '',
			'manual_ua_code'             => '',
			'track_internal_as_outbound' => 0,
			'track_internal_as_label'    => '',
			'track_outbound'             => 1,
			'allow_tracking'             => 0,
			'tracking_mode'              => 'analytics',
			'events_mode'                => 'js',
			'demographics'               => 1,
			'ignore_users'               => array( 'administrator', 'editor' ),
			'dashboards_disabled'        => 0,
			'anonymize_ips'              => 0,
			'track_download_as'          => 'event',
			'extensions_of_files'        => 'doc,exe,js,pdf,ppt,tgz,zip,xls',
			'subdomain_tracking'         => '',
			'tag_links_in_rss'           => 0,
			'allow_anchor'               => 0,
			'add_allow_linker'           => 0,
			'enhanced_link_attribution'  => 1,
			'custom_code'                => '',
			'debug_mode'                 => 0,
			'anonymous_data'             => 0,
			'save_settings'              => array(),
			'view_reports'               => array(),
		);
	}

	/**
	 * MonsterInsights Version 6.0.2 upgrades.
	 *
	 * This detects if a manual auth code is in the Yoast settings, and not
	 * in the MI settings, and that oAuth hasn't been performed (caused by the
	 * manual ua code not being transferred during the 6.0 upgrade routine)
	 * and automatically fixes it.
	 *
	 * @since 6.0.2
	 * @access public
	 * 
	 * @return void
	 */
	public function v602_upgrades() {
		$options = get_option( 'yst_ga', array() );
		if ( ( empty( $this->new_settings[ 'manual_ua_code'] ) || $this->new_settings[ 'manual_ua_code']  === '1' )  &&  
			 empty( $this->new_settings[ 'analytics_profile_code'] ) &&
			 ! empty( $options ) &&
			 is_array( $options ) &&
			 ! empty( $options['ga_general']['manual_ua_code_field'] )
		) {
			$this->new_settings['manual_ua_code'] = $options['ga_general']['manual_ua_code_field'];
		}
	}

	/**
	 * MonsterInsights Version 6.0.11 upgrades.
	 *
	 * This upgrade routine finds and removes the old crons if they exist.
	 *
	 * @since 6.0.11
	 * @access public
	 * 
	 * @return void
	 */
	public function v6011_upgrades() {
		// If old tracking checkin exists, remove it
		if ( wp_next_scheduled( 'monsterinsights_send_tracking_checkin' ) ) {
			wp_clear_scheduled_hook( 'monsterinsights_send_tracking_checkin' );
		}

		// Remove Weekly cron
		if ( wp_next_scheduled( 'monsterinsights_weekly_cron' ) ) {
			wp_clear_scheduled_hook( 'monsterinsights_weekly_cron' );
		}

		// Remove Yoast cron
		if ( wp_next_scheduled( 'yst_ga_aggregate_data' ) ) {
			wp_clear_scheduled_hook( 'yst_ga_aggregate_data' );
		}
	}

	/**
	 * MonsterInsights Version 6.2.0 upgrades.
	 *
	 * Turns off debug mode if its on.
	 *
	 * @since 6.2.0
	 * @access public
	 * 
	 * @return void
	 */
	public function v620_upgrades() {
		// Turns off debug mode if its on.
		if ( empty( $this->new_settings['debug_mode' ] ) ) {
			$this->new_settings['debug_mode' ] = 0;
		}
	}

	/**
	 * MonsterInsights Version 6.3 upgrades.
	 *
	 * This function does the
	 * upgrade routine from MonsterInsights 6.2->6.3.
	 *
	 * @since 6.3.0
	 * @access public
	 * 
	 * @return void
	 */
	public function v630_upgrades() {
		// Not in use yet.
		
		/**
		 * Running List of Things To Do In 6.1.0's Upgrade Routine
		 *
		 * 1. Drop Yoast yst_ga options if the upgraded from option === 6.0 or higher
		 * 2. Remove yst_ga support from helper options
		 * 3. Remove track_full_url
		 */
	}
}
