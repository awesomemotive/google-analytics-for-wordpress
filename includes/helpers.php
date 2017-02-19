<?php
/**
 * Helper functions.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Helper
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hours between two timestamps.
 *
 * @access public
 * @since 6.0.0
 *
 * @param string $start Timestamp of start time (in seconds since Unix).
 * @param string $stop  Timestamp of stop time (in seconds since Unix). Optional. If not used, current_time (in UTC 0 / GMT ) is used.
 *
 * @return int Hours between the two timestamps, rounded.
 */
function monsterinsights_hours_between( $start, $stop = false ) {
	if ( $stop === false ) {
		$stop = time();
	}

	$diff = (int) abs( $stop -  $start );
	$hours = round( $diff / HOUR_IN_SECONDS );
	return $hours;
}

/**
 * Is This MonsterInsights Pro?
 *
 * We use this function monsterinsights_to determine if the install is a pro version or a lite version install of MonsterInsights.
 * If the install is a lite version we disable the install from admin functionality[1] for addons as WordPress.org requires us to,
 * we change the links for where to get support (wp.org forum for free; our site for pro), we use this determine what class to load as
 * the base class in addons (to avoid fatal errors) and we use this on the system info page to know what constants to display values for
 * as the lite and pro versions of our plugin have different constants (and names for those constants) you can declare and use.
 *
 * [1] Note: This is not "feature-locking" under GPL guidelines but rather something WordPress.org requires us to do to stay
 * in compliance with their rules. We wish we didn't have to do this, as in our oppinion this diminishes the user experience
 * of users installing our free and premium addons, and we'd love to turn this on for non-Pro installs, but we're not allowed to.
 * If WordPress.org ever changes their mind on this subject, we'd totally turn on that feature for Lite installs in a heartbeat.
 *
 * @todo  Are we allowed to turn on admin installing if the user has to manually declare a PHP constant (and thus would not be on
 * either by default or via any sort of user interface)? If so, we could add a constant for forcing Pro version so that users can see
 * for themselves that we're not feature locking anything inside the plugin + it would make it easier for our team to test stuff (both via
 * Travis-CI but also when installing addons to test with the Lite version). Also this would allow for a better user experience for users
 * who want that feature.
 * 
 * @since 6.0.0
 * @access public
 *
 * @return bool True if pro version.
 */
function monsterinsights_is_pro_version() {
	if ( class_exists( 'MonsterInsights' ) ) {
		return true;
	} else {
		return false;
	}
}


/**
 * Get the user roles of this WordPress blog
 *
 * @return array
 */
function monsterinsights_get_roles() {
	global $wp_roles;

	$all_roles = $wp_roles->roles;
	$roles     = array();

	/**
	 * Filter: 'editable_roles' - Allows filtering of the roles shown within the plugin (and elsewhere in WP as it's a WP filter)
	 *
	 * @api array $all_roles
	 */
	$editable_roles = apply_filters( 'editable_roles', $all_roles );

	foreach ( $editable_roles as $id => $name ) {
		$roles[ $id ] = translate_user_role( $name['name'] );
	}

	return $roles;
}

/** Need to escape in advance of passing in $text. */
function monsterinsights_get_message( $type = 'error', $text = '' ) {
	$div = '';
	if ( $type === 'error' || $type === 'alert' || $type === 'success' || $type === 'info' ) {
		$base = MonsterInsights();
		return $base->notices->display_inline_notice( 'monsterinsights_standard_notice', '', $text, $type, false, array( 'skip_message_escape' => true ) );
	} else {
		return '';
	}
}

function monsterinsights_get_country_list() {
	$countries = array(
		''   => '',
		'US' => __( 'United States', 'google-analytics-for-wordpress' ),
		'CA' => __( 'Canada', 'google-analytics-for-wordpress' ),
		'GB' => __( 'United Kingdom', 'google-analytics-for-wordpress' ),
		'AF' => __( 'Afghanistan', 'google-analytics-for-wordpress' ),
		'AX' => __( '&#197;land Islands', 'google-analytics-for-wordpress' ),
		'AL' => __( 'Albania', 'google-analytics-for-wordpress' ),
		'DZ' => __( 'Algeria', 'google-analytics-for-wordpress' ),
		'AS' => __( 'American Samoa', 'google-analytics-for-wordpress' ),
		'AD' => __( 'Andorra', 'google-analytics-for-wordpress' ),
		'AO' => __( 'Angola', 'google-analytics-for-wordpress' ),
		'AI' => __( 'Anguilla', 'google-analytics-for-wordpress' ),
		'AQ' => __( 'Antarctica', 'google-analytics-for-wordpress' ),
		'AG' => __( 'Antigua and Barbuda', 'google-analytics-for-wordpress' ),
		'AR' => __( 'Argentina', 'google-analytics-for-wordpress' ),
		'AM' => __( 'Armenia', 'google-analytics-for-wordpress' ),
		'AW' => __( 'Aruba', 'google-analytics-for-wordpress' ),
		'AU' => __( 'Australia', 'google-analytics-for-wordpress' ),
		'AT' => __( 'Austria', 'google-analytics-for-wordpress' ),
		'AZ' => __( 'Azerbaijan', 'google-analytics-for-wordpress' ),
		'BS' => __( 'Bahamas', 'google-analytics-for-wordpress' ),
		'BH' => __( 'Bahrain', 'google-analytics-for-wordpress' ),
		'BD' => __( 'Bangladesh', 'google-analytics-for-wordpress' ),
		'BB' => __( 'Barbados', 'google-analytics-for-wordpress' ),
		'BY' => __( 'Belarus', 'google-analytics-for-wordpress' ),
		'BE' => __( 'Belgium', 'google-analytics-for-wordpress' ),
		'BZ' => __( 'Belize', 'google-analytics-for-wordpress' ),
		'BJ' => __( 'Benin', 'google-analytics-for-wordpress' ),
		'BM' => __( 'Bermuda', 'google-analytics-for-wordpress' ),
		'BT' => __( 'Bhutan', 'google-analytics-for-wordpress' ),
		'BO' => __( 'Bolivia', 'google-analytics-for-wordpress' ),
		'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'google-analytics-for-wordpress' ),
		'BA' => __( 'Bosnia and Herzegovina', 'google-analytics-for-wordpress' ),
		'BW' => __( 'Botswana', 'google-analytics-for-wordpress' ),
		'BV' => __( 'Bouvet Island', 'google-analytics-for-wordpress' ),
		'BR' => __( 'Brazil', 'google-analytics-for-wordpress' ),
		'IO' => __( 'British Indian Ocean Territory', 'google-analytics-for-wordpress' ),
		'BN' => __( 'Brunei Darrussalam', 'google-analytics-for-wordpress' ),
		'BG' => __( 'Bulgaria', 'google-analytics-for-wordpress' ),
		'BF' => __( 'Burkina Faso', 'google-analytics-for-wordpress' ),
		'BI' => __( 'Burundi', 'google-analytics-for-wordpress' ),
		'KH' => __( 'Cambodia', 'google-analytics-for-wordpress' ),
		'CM' => __( 'Cameroon', 'google-analytics-for-wordpress' ),
		'CV' => __( 'Cape Verde', 'google-analytics-for-wordpress' ),
		'KY' => __( 'Cayman Islands', 'google-analytics-for-wordpress' ),
		'CF' => __( 'Central African Republic', 'google-analytics-for-wordpress' ),
		'TD' => __( 'Chad', 'google-analytics-for-wordpress' ),
		'CL' => __( 'Chile', 'google-analytics-for-wordpress' ),
		'CN' => __( 'China', 'google-analytics-for-wordpress' ),
		'CX' => __( 'Christmas Island', 'google-analytics-for-wordpress' ),
		'CC' => __( 'Cocos Islands', 'google-analytics-for-wordpress' ),
		'CO' => __( 'Colombia', 'google-analytics-for-wordpress' ),
		'KM' => __( 'Comoros', 'google-analytics-for-wordpress' ),
		'CD' => __( 'Congo, Democratic People\'s Republic', 'google-analytics-for-wordpress' ),
		'CG' => __( 'Congo, Republic of', 'google-analytics-for-wordpress' ),
		'CK' => __( 'Cook Islands', 'google-analytics-for-wordpress' ),
		'CR' => __( 'Costa Rica', 'google-analytics-for-wordpress' ),
		'CI' => __( 'Cote d\'Ivoire', 'google-analytics-for-wordpress' ),
		'HR' => __( 'Croatia/Hrvatska', 'google-analytics-for-wordpress' ),
		'CU' => __( 'Cuba', 'google-analytics-for-wordpress' ),
		'CW' => __( 'Cura&Ccedil;ao', 'google-analytics-for-wordpress' ),
		'CY' => __( 'Cyprus', 'google-analytics-for-wordpress' ),
		'CZ' => __( 'Czech Republic', 'google-analytics-for-wordpress' ),
		'DK' => __( 'Denmark', 'google-analytics-for-wordpress' ),
		'DJ' => __( 'Djibouti', 'google-analytics-for-wordpress' ),
		'DM' => __( 'Dominica', 'google-analytics-for-wordpress' ),
		'DO' => __( 'Dominican Republic', 'google-analytics-for-wordpress' ),
		'TP' => __( 'East Timor', 'google-analytics-for-wordpress' ),
		'EC' => __( 'Ecuador', 'google-analytics-for-wordpress' ),
		'EG' => __( 'Egypt', 'google-analytics-for-wordpress' ),
		'GQ' => __( 'Equatorial Guinea', 'google-analytics-for-wordpress' ),
		'SV' => __( 'El Salvador', 'google-analytics-for-wordpress' ),
		'ER' => __( 'Eritrea', 'google-analytics-for-wordpress' ),
		'EE' => __( 'Estonia', 'google-analytics-for-wordpress' ),
		'ET' => __( 'Ethiopia', 'google-analytics-for-wordpress' ),
		'FK' => __( 'Falkland Islands', 'google-analytics-for-wordpress' ),
		'FO' => __( 'Faroe Islands', 'google-analytics-for-wordpress' ),
		'FJ' => __( 'Fiji', 'google-analytics-for-wordpress' ),
		'FI' => __( 'Finland', 'google-analytics-for-wordpress' ),
		'FR' => __( 'France', 'google-analytics-for-wordpress' ),
		'GF' => __( 'French Guiana', 'google-analytics-for-wordpress' ),
		'PF' => __( 'French Polynesia', 'google-analytics-for-wordpress' ),
		'TF' => __( 'French Southern Territories', 'google-analytics-for-wordpress' ),
		'GA' => __( 'Gabon', 'google-analytics-for-wordpress' ),
		'GM' => __( 'Gambia', 'google-analytics-for-wordpress' ),
		'GE' => __( 'Georgia', 'google-analytics-for-wordpress' ),
		'DE' => __( 'Germany', 'google-analytics-for-wordpress' ),
		'GR' => __( 'Greece', 'google-analytics-for-wordpress' ),
		'GH' => __( 'Ghana', 'google-analytics-for-wordpress' ),
		'GI' => __( 'Gibraltar', 'google-analytics-for-wordpress' ),
		'GL' => __( 'Greenland', 'google-analytics-for-wordpress' ),
		'GD' => __( 'Grenada', 'google-analytics-for-wordpress' ),
		'GP' => __( 'Guadeloupe', 'google-analytics-for-wordpress' ),
		'GU' => __( 'Guam', 'google-analytics-for-wordpress' ),
		'GT' => __( 'Guatemala', 'google-analytics-for-wordpress' ),
		'GG' => __( 'Guernsey', 'google-analytics-for-wordpress' ),
		'GN' => __( 'Guinea', 'google-analytics-for-wordpress' ),
		'GW' => __( 'Guinea-Bissau', 'google-analytics-for-wordpress' ),
		'GY' => __( 'Guyana', 'google-analytics-for-wordpress' ),
		'HT' => __( 'Haiti', 'google-analytics-for-wordpress' ),
		'HM' => __( 'Heard and McDonald Islands', 'google-analytics-for-wordpress' ),
		'VA' => __( 'Holy See (City Vatican State)', 'google-analytics-for-wordpress' ),
		'HN' => __( 'Honduras', 'google-analytics-for-wordpress' ),
		'HK' => __( 'Hong Kong', 'google-analytics-for-wordpress' ),
		'HU' => __( 'Hungary', 'google-analytics-for-wordpress' ),
		'IS' => __( 'Iceland', 'google-analytics-for-wordpress' ),
		'IN' => __( 'India', 'google-analytics-for-wordpress' ),
		'ID' => __( 'Indonesia', 'google-analytics-for-wordpress' ),
		'IR' => __( 'Iran', 'google-analytics-for-wordpress' ),
		'IQ' => __( 'Iraq', 'google-analytics-for-wordpress' ),
		'IE' => __( 'Ireland', 'google-analytics-for-wordpress' ),
		'IM' => __( 'Isle of Man', 'google-analytics-for-wordpress' ),
		'IL' => __( 'Israel', 'google-analytics-for-wordpress' ),
		'IT' => __( 'Italy', 'google-analytics-for-wordpress' ),
		'JM' => __( 'Jamaica', 'google-analytics-for-wordpress' ),
		'JP' => __( 'Japan', 'google-analytics-for-wordpress' ),
		'JE' => __( 'Jersey', 'google-analytics-for-wordpress' ),
		'JO' => __( 'Jordan', 'google-analytics-for-wordpress' ),
		'KZ' => __( 'Kazakhstan', 'google-analytics-for-wordpress' ),
		'KE' => __( 'Kenya', 'google-analytics-for-wordpress' ),
		'KI' => __( 'Kiribati', 'google-analytics-for-wordpress' ),
		'KW' => __( 'Kuwait', 'google-analytics-for-wordpress' ),
		'KG' => __( 'Kyrgyzstan', 'google-analytics-for-wordpress' ),
		'LA' => __( 'Lao People\'s Democratic Republic', 'google-analytics-for-wordpress' ),
		'LV' => __( 'Latvia', 'google-analytics-for-wordpress' ),
		'LB' => __( 'Lebanon', 'google-analytics-for-wordpress' ),
		'LS' => __( 'Lesotho', 'google-analytics-for-wordpress' ),
		'LR' => __( 'Liberia', 'google-analytics-for-wordpress' ),
		'LY' => __( 'Libyan Arab Jamahiriya', 'google-analytics-for-wordpress' ),
		'LI' => __( 'Liechtenstein', 'google-analytics-for-wordpress' ),
		'LT' => __( 'Lithuania', 'google-analytics-for-wordpress' ),
		'LU' => __( 'Luxembourg', 'google-analytics-for-wordpress' ),
		'MO' => __( 'Macau', 'google-analytics-for-wordpress' ),
		'MK' => __( 'Macedonia', 'google-analytics-for-wordpress' ),
		'MG' => __( 'Madagascar', 'google-analytics-for-wordpress' ),
		'MW' => __( 'Malawi', 'google-analytics-for-wordpress' ),
		'MY' => __( 'Malaysia', 'google-analytics-for-wordpress' ),
		'MV' => __( 'Maldives', 'google-analytics-for-wordpress' ),
		'ML' => __( 'Mali', 'google-analytics-for-wordpress' ),
		'MT' => __( 'Malta', 'google-analytics-for-wordpress' ),
		'MH' => __( 'Marshall Islands', 'google-analytics-for-wordpress' ),
		'MQ' => __( 'Martinique', 'google-analytics-for-wordpress' ),
		'MR' => __( 'Mauritania', 'google-analytics-for-wordpress' ),
		'MU' => __( 'Mauritius', 'google-analytics-for-wordpress' ),
		'YT' => __( 'Mayotte', 'google-analytics-for-wordpress' ),
		'MX' => __( 'Mexico', 'google-analytics-for-wordpress' ),
		'FM' => __( 'Micronesia', 'google-analytics-for-wordpress' ),
		'MD' => __( 'Moldova, Republic of', 'google-analytics-for-wordpress' ),
		'MC' => __( 'Monaco', 'google-analytics-for-wordpress' ),
		'MN' => __( 'Mongolia', 'google-analytics-for-wordpress' ),
		'ME' => __( 'Montenegro', 'google-analytics-for-wordpress' ),
		'MS' => __( 'Montserrat', 'google-analytics-for-wordpress' ),
		'MA' => __( 'Morocco', 'google-analytics-for-wordpress' ),
		'MZ' => __( 'Mozambique', 'google-analytics-for-wordpress' ),
		'MM' => __( 'Myanmar', 'google-analytics-for-wordpress' ),
		'NA' => __( 'Namibia', 'google-analytics-for-wordpress' ),
		'NR' => __( 'Nauru', 'google-analytics-for-wordpress' ),
		'NP' => __( 'Nepal', 'google-analytics-for-wordpress' ),
		'NL' => __( 'Netherlands', 'google-analytics-for-wordpress' ),
		'AN' => __( 'Netherlands Antilles', 'google-analytics-for-wordpress' ),
		'NC' => __( 'New Caledonia', 'google-analytics-for-wordpress' ),
		'NZ' => __( 'New Zealand', 'google-analytics-for-wordpress' ),
		'NI' => __( 'Nicaragua', 'google-analytics-for-wordpress' ),
		'NE' => __( 'Niger', 'google-analytics-for-wordpress' ),
		'NG' => __( 'Nigeria', 'google-analytics-for-wordpress' ),
		'NU' => __( 'Niue', 'google-analytics-for-wordpress' ),
		'NF' => __( 'Norfolk Island', 'google-analytics-for-wordpress' ),
		'KP' => __( 'North Korea', 'google-analytics-for-wordpress' ),
		'MP' => __( 'Northern Mariana Islands', 'google-analytics-for-wordpress' ),
		'NO' => __( 'Norway', 'google-analytics-for-wordpress' ),
		'OM' => __( 'Oman', 'google-analytics-for-wordpress' ),
		'PK' => __( 'Pakistan', 'google-analytics-for-wordpress' ),
		'PW' => __( 'Palau', 'google-analytics-for-wordpress' ),
		'PS' => __( 'Palestinian Territories', 'google-analytics-for-wordpress' ),
		'PA' => __( 'Panama', 'google-analytics-for-wordpress' ),
		'PG' => __( 'Papua New Guinea', 'google-analytics-for-wordpress' ),
		'PY' => __( 'Paraguay', 'google-analytics-for-wordpress' ),
		'PE' => __( 'Peru', 'google-analytics-for-wordpress' ),
		'PH' => __( 'Philippines', 'google-analytics-for-wordpress' ),
		'PN' => __( 'Pitcairn Island', 'google-analytics-for-wordpress' ),
		'PL' => __( 'Poland', 'google-analytics-for-wordpress' ),
		'PT' => __( 'Portugal', 'google-analytics-for-wordpress' ),
		'PR' => __( 'Puerto Rico', 'google-analytics-for-wordpress' ),
		'QA' => __( 'Qatar', 'google-analytics-for-wordpress' ),
		'XK' => __( 'Republic of Kosovo', 'google-analytics-for-wordpress' ),
		'RE' => __( 'Reunion Island', 'google-analytics-for-wordpress' ),
		'RO' => __( 'Romania', 'google-analytics-for-wordpress' ),
		'RU' => __( 'Russian Federation', 'google-analytics-for-wordpress' ),
		'RW' => __( 'Rwanda', 'google-analytics-for-wordpress' ),
		'BL' => __( 'Saint Barth&eacute;lemy', 'google-analytics-for-wordpress' ),
		'SH' => __( 'Saint Helena', 'google-analytics-for-wordpress' ),
		'KN' => __( 'Saint Kitts and Nevis', 'google-analytics-for-wordpress' ),
		'LC' => __( 'Saint Lucia', 'google-analytics-for-wordpress' ),
		'MF' => __( 'Saint Martin (French)', 'google-analytics-for-wordpress' ),
		'SX' => __( 'Saint Martin (Dutch)', 'google-analytics-for-wordpress' ),
		'PM' => __( 'Saint Pierre and Miquelon', 'google-analytics-for-wordpress' ),
		'VC' => __( 'Saint Vincent and the Grenadines', 'google-analytics-for-wordpress' ),
		'SM' => __( 'San Marino', 'google-analytics-for-wordpress' ),
		'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'google-analytics-for-wordpress' ),
		'SA' => __( 'Saudi Arabia', 'google-analytics-for-wordpress' ),
		'SN' => __( 'Senegal', 'google-analytics-for-wordpress' ),
		'RS' => __( 'Serbia', 'google-analytics-for-wordpress' ),
		'SC' => __( 'Seychelles', 'google-analytics-for-wordpress' ),
		'SL' => __( 'Sierra Leone', 'google-analytics-for-wordpress' ),
		'SG' => __( 'Singapore', 'google-analytics-for-wordpress' ),
		'SK' => __( 'Slovak Republic', 'google-analytics-for-wordpress' ),
		'SI' => __( 'Slovenia', 'google-analytics-for-wordpress' ),
		'SB' => __( 'Solomon Islands', 'google-analytics-for-wordpress' ),
		'SO' => __( 'Somalia', 'google-analytics-for-wordpress' ),
		'ZA' => __( 'South Africa', 'google-analytics-for-wordpress' ),
		'GS' => __( 'South Georgia', 'google-analytics-for-wordpress' ),
		'KR' => __( 'South Korea', 'google-analytics-for-wordpress' ),
		'SS' => __( 'South Sudan', 'google-analytics-for-wordpress' ),
		'ES' => __( 'Spain', 'google-analytics-for-wordpress' ),
		'LK' => __( 'Sri Lanka', 'google-analytics-for-wordpress' ),
		'SD' => __( 'Sudan', 'google-analytics-for-wordpress' ),
		'SR' => __( 'Suriname', 'google-analytics-for-wordpress' ),
		'SJ' => __( 'Svalbard and Jan Mayen Islands', 'google-analytics-for-wordpress' ),
		'SZ' => __( 'Swaziland', 'google-analytics-for-wordpress' ),
		'SE' => __( 'Sweden', 'google-analytics-for-wordpress' ),
		'CH' => __( 'Switzerland', 'google-analytics-for-wordpress' ),
		'SY' => __( 'Syrian Arab Republic', 'google-analytics-for-wordpress' ),
		'TW' => __( 'Taiwan', 'google-analytics-for-wordpress' ),
		'TJ' => __( 'Tajikistan', 'google-analytics-for-wordpress' ),
		'TZ' => __( 'Tanzania', 'google-analytics-for-wordpress' ),
		'TH' => __( 'Thailand', 'google-analytics-for-wordpress' ),
		'TL' => __( 'Timor-Leste', 'google-analytics-for-wordpress' ),
		'TG' => __( 'Togo', 'google-analytics-for-wordpress' ),
		'TK' => __( 'Tokelau', 'google-analytics-for-wordpress' ),
		'TO' => __( 'Tonga', 'google-analytics-for-wordpress' ),
		'TT' => __( 'Trinidad and Tobago', 'google-analytics-for-wordpress' ),
		'TN' => __( 'Tunisia', 'google-analytics-for-wordpress' ),
		'TR' => __( 'Turkey', 'google-analytics-for-wordpress' ),
		'TM' => __( 'Turkmenistan', 'google-analytics-for-wordpress' ),
		'TC' => __( 'Turks and Caicos Islands', 'google-analytics-for-wordpress' ),
		'TV' => __( 'Tuvalu', 'google-analytics-for-wordpress' ),
		'UG' => __( 'Uganda', 'google-analytics-for-wordpress' ),
		'UA' => __( 'Ukraine', 'google-analytics-for-wordpress' ),
		'AE' => __( 'United Arab Emirates', 'google-analytics-for-wordpress' ),
		'UY' => __( 'Uruguay', 'google-analytics-for-wordpress' ),
		'UM' => __( 'US Minor Outlying Islands', 'google-analytics-for-wordpress' ),
		'UZ' => __( 'Uzbekistan', 'google-analytics-for-wordpress' ),
		'VU' => __( 'Vanuatu', 'google-analytics-for-wordpress' ),
		'VE' => __( 'Venezuela', 'google-analytics-for-wordpress' ),
		'VN' => __( 'Vietnam', 'google-analytics-for-wordpress' ),
		'VG' => __( 'Virgin Islands (British)', 'google-analytics-for-wordpress' ),
		'VI' => __( 'Virgin Islands (USA)', 'google-analytics-for-wordpress' ),
		'WF' => __( 'Wallis and Futuna Islands', 'google-analytics-for-wordpress' ),
		'EH' => __( 'Western Sahara', 'google-analytics-for-wordpress' ),
		'WS' => __( 'Western Samoa', 'google-analytics-for-wordpress' ),
		'YE' => __( 'Yemen', 'google-analytics-for-wordpress' ),
		'ZM' => __( 'Zambia', 'google-analytics-for-wordpress' ),
		'ZW' => __( 'Zimbabwe', 'google-analytics-for-wordpress' ),
	);
	return $countries;
}

function monsterinsights_is_wp_seo_active( ) {
	$wp_seo_active = false; // @todo: improve this check. This is from old Yoast code.
	
	// Makes sure is_plugin_active is available when called from front end
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ) ) {
		$wp_seo_active = true;
	}
	return $wp_seo_active;
}

function monsterinsights_get_asset_version() {
	if ( monsterinsights_is_debug_mode() ) {
		return time();
	} else {
		return MONSTERINSIGHTS_VERSION;
	}
}

function monsterinsights_is_debug_mode() {
	$debug_mode = false;
	if ( defined( 'MONSTERINSIGHTS_DEBUG_MODE' ) && MONSTERINSIGHTS_DEBUG_MODE ) {
		$debug_mode = true;
	}
	
	if ( monsterinsights_get_option( 'debug_mode', false ) ) {
		$debug_mode = true;
	}
	
	return apply_filters( 'monsterinsights_is_debug_mode', $debug_mode );
}

function monsterinsights_is_network_active() {
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}
	 
	if ( is_multisite() && is_plugin_active_for_network( plugin_basename( MONSTERINSIGHTS_PLUGIN_FILE ) ) ) {
	   return true;
	} else {
		return false;
	}
}