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

function monsterinsights_is_page_reload() {
	// Can't be a refresh without having a referrer
	if ( ! isset( $_SERVER['HTTP_REFERER'] ) ) {
		return false;
	}

	// IF the referrer is identical to the current page request, then it's a refresh
	return ( parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_PATH ) === parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
}


function monsterinsights_track_user( $user_id = -1 ) {
	if ( $user_id === -1 ) {
		$user = wp_get_current_user();
	} else {
		$user = new WP_User( $user_id );
	}

	$track_user  = true;
	$roles     = monsterinsights_get_option( 'ignore_users', array() );

	if ( ! empty( $roles ) && is_array( $roles ) ) {
		foreach ( $roles as $role ) {
			if ( is_string( $role ) ) {
				if ( user_can( $user, $role ) ) {
					$track_user = false;
					break;
				}
			}
		}
	}

	$track_super_admin = apply_filters( 'monsterinsights_track_super_admins', false );
	if ( $user_id === -1 && $track_super_admin === false && is_multisite() && is_super_admin() ) {
		$track_user = false;
	}

	// or if tracking code is not entered
	$tracking_ids = monsterinsights_get_tracking_ids();
	if ( empty( $tracking_ids ) ) {
		$track_user = false;
	}

	return apply_filters( 'monsterinsights_track_user', $track_user, $user );
}

function monsterinsights_get_client_id( $payment_id = false ) {
	if ( is_object( $payment_id ) ) {
		$payment_id = $payment_id->ID;
	}
	$user_cid    = monsterinsights_get_uuid();
	$saved_cid   = ! empty( $payment_id ) ? get_post_meta( $payment_id, '_yoast_gau_uuid', true ) : false;

	if ( ! empty( $payment_id ) && ! empty( $saved_cid ) ) {
		return $saved_cid;
	} else if ( ! empty( $user_cid ) ) {
		return $user_cid;
	} else {
		return monsterinsights_generate_uuid();
	}
}

/**
 * Returns the Google Analytics clientId to store for later use
 *
 * @since 6.0.0
 *
 * @link  https://developers.google.com/analytics/devguides/collection/analyticsjs/domains#getClientId
 *
 * @return bool|string False if cookie isn't set, GA UUID otherwise
 */
function monsterinsights_get_uuid() {
	if ( empty( $_COOKIE['_ga'] ) ) {
		return false;
	}

	/**
	 * Example cookie formats:
	 *
	 * GA1.2.XXXXXXX.YYYYY
	 * _ga=1.2.XXXXXXX.YYYYYY -- We want the XXXXXXX.YYYYYY part
	 *
	 * for AMP pages the format is sometimes GA1.3.amp-XXXXXXXXXXXXX-XXXXXXXX
	 * if the first page visited is AMP, the cookie may be in the format amp-XXXXXXXXXXXXX-XXXXXXXX
	 *
	 */

	$ga_cookie    = $_COOKIE['_ga'];
	$cookie_parts = explode( '.', $ga_cookie );
	if ( is_array( $cookie_parts ) && ! empty( $cookie_parts[2] ) ) {
		$cookie_parts = array_slice( $cookie_parts, 2 );
		$uuid         = implode( '.', $cookie_parts );
		if ( is_string( $uuid ) ) {
			return $uuid;
		} else {
			return false;
		}
	} elseif ( 0 === strpos( $ga_cookie, 'amp-' ) ) {
		return $ga_cookie;
	} else {
		return false;
	}
}


/**
 * Generate UUID v4 function - needed to generate a CID when one isn't available
 *
 * @link http://www.stumiller.me/implementing-google-analytics-measurement-protocol-in-php-and-wordpress/
 *
 * @since 6.1.8
 * @return string
 */
function monsterinsights_generate_uuid() {

	return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

		// 32 bits for "time_low"
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

		// 16 bits for "time_mid"
		mt_rand( 0, 0xffff ),

		// 16 bits for "time_hi_and_version",
		// four most significant bits holds version number 4
		mt_rand( 0, 0x0fff ) | 0x4000,

		// 16 bits, 8 bits for "clk_seq_hi_res",
		// 8 bits for "clk_seq_low",
		// two most significant bits holds zero and one for variant DCE1.1
		mt_rand( 0, 0x3fff ) | 0x8000,

		// 48 bits for "node"
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
	);
}

/**
 * Returns the Google Analytics clientId to store for later use
 *
 * @since 6.0.0
 *
 * @return GA UUID or error code.
 */
function monsterinsights_get_cookie( $debug = false ) {
	if ( empty( $_COOKIE['_ga'] ) ) {
		return ( $debug ) ? 'FCE' : false;
	}

	$ga_cookie    = $_COOKIE['_ga'];
	$cookie_parts = explode( '.', $ga_cookie );
	if ( is_array( $cookie_parts ) && ! empty( $cookie_parts[2] ) ) {
		$cookie_parts = array_slice( $cookie_parts, 2 );
		$uuid         = implode( '.', $cookie_parts );
		if ( is_string( $uuid ) ) {
			return $ga_cookie;
		} else {
			return ( $debug ) ? 'FA' : false;
		}
	} elseif ( 0 === strpos( $ga_cookie, 'amp-' ) ) {
		return $ga_cookie;
	} else {
		return ( $debug ) ? 'FAE' : false;
	}
}


function monsterinsights_generate_ga_client_id() {
	return rand(100000000,999999999) . '.' . time();
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

/**
 * Get the user roles which can manage options. Used to prevent these roles from getting unselected in the settings.
 *
 * @return array
 */
function monsterinsights_get_manage_options_roles() {
	global $wp_roles;

	$all_roles = $wp_roles->roles;
	$roles     = array();

	/**
	 * Filter: 'editable_roles' - Allows filtering of the roles shown within the plugin (and elsewhere in WP as it's a WP filter)
	 *
	 * @api array $all_roles
	 */
	$editable_roles = apply_filters( 'editable_roles', $all_roles );

	foreach ( $editable_roles as $id => $role ) {
		if ( isset( $role['capabilities']['manage_options'] ) && $role['capabilities']['manage_options'] ) {
			$roles[ $id ] = translate_user_role( $role['name'] );
		}
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

function monsterinsights_is_dev_url( $url = '' ) {
	$is_local_url = false;
	// Trim it up
	$url = strtolower( trim( $url ) );
	// Need to get the host...so let's add the scheme so we can use parse_url
	if ( false === strpos( $url, 'http://' ) && false === strpos( $url, 'https://' ) ) {
		$url = 'http://' . $url;
	}
	$url_parts = parse_url( $url );
	$host      = ! empty( $url_parts['host'] ) ? $url_parts['host'] : false;
	if ( ! empty( $url ) && ! empty( $host ) ) {
		if ( false !== ip2long( $host ) ) {
			if ( ! filter_var( $host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
				$is_local_url = true;
			}
		} else if ( 'localhost' === $host ) {
			$is_local_url = true;
		}

		$tlds_to_check = array( '.local', ':8888', ':8080', ':8081', '.invalid', '.example', '.test' );
		foreach ( $tlds_to_check as $tld ) {
			if ( false !== strpos( $host, $tld ) ) {
				$is_local_url = true;
				break;
			}

		}
		if ( substr_count( $host, '.' ) > 1 ) {
			$subdomains_to_check =  array( 'dev.', '*.staging.', 'beta.', 'test.' );
			foreach ( $subdomains_to_check as $subdomain ) {
				$subdomain = str_replace( '.', '(.)', $subdomain );
				$subdomain = str_replace( array( '*', '(.)' ), '(.*)', $subdomain );
				if ( preg_match( '/^(' . $subdomain . ')/', $host ) ) {
					$is_local_url = true;
					break;
				}
			}
		}
	}
	return $is_local_url;
}

// Set cookie to expire in 2 years
function monsterinsights_get_cookie_expiration_date( $time ) {
	return date('D, j F Y H:i:s', time() + $time );
}

function monsterinsights_string_ends_with( $string, $ending ) {
	$strlen = strlen($string);
	$endinglen = strlen($ending);
	if ( $endinglen > $strlen ) {
		return false;
	}
	return substr_compare( $string, $ending, $strlen - $endinglen, $endinglen) === 0;
}

function monsterinsights_string_starts_with( $string, $start ) {
	if ( ! is_string( $string ) || ! is_string( $start ) ) {
		return false;
	}

	return substr( $string, 0, strlen( $start ) ) === $start;
}

function monsterinsights_get_country_list( $translated = false ) {
	if ( $translated ) {
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
			'CZ' => __( 'Czechia', 'google-analytics-for-wordpress' ),
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
			'MK' => __( 'Macedonia (FYROM)', 'google-analytics-for-wordpress' ),
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
			'ZZ' => __( 'Unknown Country', 'google-analytics-for-wordpress' ),
		);
	} else {
		$countries = array(
			''   => '',
			'US' => 'United States',
			'CA' => 'Canada',
			'GB' => 'United Kingdom',
			'AF' => 'Afghanistan',
			'AX' => '&#197;land Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua and Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BQ' => 'Bonaire, Saint Eustatius and Saba',
			'BA' => 'Bosnia and Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darrussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CD' => 'Congo, Democratic People\'s Republic',
			'CG' => 'Congo, Republic of',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote d\'Ivoire',
			'HR' => 'Croatia/Hrvatska',
			'CU' => 'Cuba',
			'CW' => 'Cura&Ccedil;ao',
			'CY' => 'Cyprus',
			'CZ' => 'Czechia',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'TP' => 'East Timor',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'GQ' => 'Equatorial Guinea',
			'SV' => 'El Salvador',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GR' => 'Greece',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard and McDonald Islands',
			'VA' => 'Holy See (City Vatican State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Lao People\'s Democratic Republic',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macau',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia',
			'MD' => 'Moldova, Republic of',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar (Burma)',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'AN' => 'Netherlands Antilles',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'KP' => 'North Korea',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territories',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn Island',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'XK' => 'Republic of Kosovo',
			'RE' => 'Reunion Island',
			'RO' => 'Romania',
			'RU' => 'Russia',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barth&eacute;lemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts and Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin (French)',
			'SX' => 'Saint Martin (Dutch)',
			'PM' => 'Saint Pierre and Miquelon',
			'VC' => 'Saint Vincent and the Grenadines',
			'SM' => 'San Marino',
			'ST' => 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovak Republic',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia',
			'KR' => 'South Korea',
			'SS' => 'South Sudan',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard and Jan Mayen Islands',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad and Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks and Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'UY' => 'Uruguay',
			'UM' => 'US Minor Outlying Islands',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Vietnam',
			'VG' => 'Virgin Islands (British)',
			'VI' => 'Virgin Islands (USA)',
			'WF' => 'Wallis and Futuna Islands',
			'EH' => 'Western Sahara',
			'WS' => 'Western Samoa',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
			'ZZ' => 'Unknown Country',
		);
	}
	return $countries;
}

function monsterinsights_get_api_url(){
	return apply_filters( 'monsterinsights_get_api_url', 'api.monsterinsights.com/v2/' );
}

function monsterinsights_get_licensing_url(){
	return apply_filters( 'monsterinsights_get_licensing_url', 'https://www.monsterinsights.com' );
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
	if ( monsterinsights_is_debug_mode() || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) {
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

if ( ! function_exists ( 'remove_class_filter' ) ) {
	/**
	 * Remove Class Filter Without Access to Class Object
	 *
	 * In order to use the core WordPress remove_filter() on a filter added with the callback
	 * to a class, you either have to have access to that class object, or it has to be a call
	 * to a static method.  This method allows you to remove filters with a callback to a class
	 * you don't have access to.
	 *
	 * Works with WordPress 1.2 - 4.7+
	 *
	 * @param string $tag         Filter to remove
	 * @param string $class_name  Class name for the filter's callback
	 * @param string $method_name Method name for the filter's callback
	 * @param int    $priority    Priority of the filter (default 10)
	 *
	 * @return bool Whether the function is removed.
	 */
	function remove_class_filter( $tag, $class_name = '', $method_name = '', $priority = 10 ) {
		global $wp_filter;
		// Check that filter actually exists first
		if ( ! isset( $wp_filter[ $tag ] ) ) return FALSE;
		/**
		 * If filter config is an object, means we're using WordPress 4.7+ and the config is no longer
		 * a simple array, rather it is an object that implements the ArrayAccess interface.
		 *
		 * To be backwards compatible, we set $callbacks equal to the correct array as a reference (so $wp_filter is updated)
		 *
		 * @see https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/
		 */
		if ( is_object( $wp_filter[ $tag ] ) && isset( $wp_filter[ $tag ]->callbacks ) ) {
			$callbacks = &$wp_filter[ $tag ]->callbacks;
		} else {
			$callbacks = &$wp_filter[ $tag ];
		}
		// Exit if there aren't any callbacks for specified priority
		if ( ! isset( $callbacks[ $priority ] ) || empty( $callbacks[ $priority ] ) ) return FALSE;
		// Loop through each filter for the specified priority, looking for our class & method
		foreach( (array) $callbacks[ $priority ] as $filter_id => $filter ) {
			// Filter should always be an array - array( $this, 'method' ), if not goto next
			if ( ! isset( $filter[ 'function' ] ) || ! is_array( $filter[ 'function' ] ) ) continue;
			// If first value in array is not an object, it can't be a class
			if ( ! is_object( $filter[ 'function' ][ 0 ] ) ) continue;
			// Method doesn't match the one we're looking for, goto next
			if ( $filter[ 'function' ][ 1 ] !== $method_name ) continue;
			// Method matched, now let's check the Class
			if ( get_class( $filter[ 'function' ][ 0 ] ) === $class_name ) {
				// Now let's remove it from the array
				unset( $callbacks[ $priority ][ $filter_id ] );
				// and if it was the only filter in that priority, unset that priority
				if ( empty( $callbacks[ $priority ] ) ) unset( $callbacks[ $priority ] );
				// and if the only filter for that tag, set the tag to an empty array
				if ( empty( $callbacks ) ) $callbacks = array();
				// If using WordPress older than 4.7
				if ( ! is_object( $wp_filter[ $tag ] ) ) {
					// Remove this filter from merged_filters, which specifies if filters have been sorted
					unset( $GLOBALS[ 'merged_filters' ][ $tag ] );
				}
				return TRUE;
			}
		}
		return FALSE;
	}
} // End function exists

if ( ! function_exists ( 'remove_class_action' ) ) {
	/**
	 * Remove Class Action Without Access to Class Object
	 *
	 * In order to use the core WordPress remove_action() on an action added with the callback
	 * to a class, you either have to have access to that class object, or it has to be a call
	 * to a static method.  This method allows you to remove actions with a callback to a class
	 * you don't have access to.
	 *
	 * Works with WordPress 1.2 - 4.7+
	 *
	 * @param string $tag         Action to remove
	 * @param string $class_name  Class name for the action's callback
	 * @param string $method_name Method name for the action's callback
	 * @param int    $priority    Priority of the action (default 10)
	 *
	 * @return bool               Whether the function is removed.
	 */
	function remove_class_action( $tag, $class_name = '', $method_name = '', $priority = 10 ) {
		remove_class_filter( $tag, $class_name, $method_name, $priority );
	}
} // End function exists

/**
 * Format a big number, instead of 1000000 you get 1.0M, works with billions also.
 *
 * @param int $number
 * @param int $precision
 *
 * @return string
 */
function monsterinsights_round_number( $number, $precision = 2 ) {

	if ( $number < 1000000 ) {
		// Anything less than a million
		$number = number_format_i18n( $number );
	} else if ( $number < 1000000000 ) {
		// Anything less than a billion
		$number = number_format_i18n( $number / 1000000, $precision ) . 'M';
	} else {
		// At least a billion
		$number = number_format_i18n( $number / 1000000000, $precision ) . 'B';
	}

	return $number;
}

if ( ! function_exists( 'wp_get_jed_locale_data' ) ) {
	/**
	 * Returns Jed-formatted localization data. Added for backwards-compatibility.
	 *
	 * @param  string $domain Translation domain.
	 *
	 * @return array
	 */
	function wp_get_jed_locale_data( $domain ) {
		$translations = get_translations_for_domain( $domain );

		$locale = array(
			'' => array(
				'domain' => $domain,
				'lang'   => is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale(),
			),
		);

		if ( ! empty( $translations->headers['Plural-Forms'] ) ) {
			$locale['']['plural_forms'] = $translations->headers['Plural-Forms'];
		}

		foreach ( $translations->entries as $msgid => $entry ) {
			$locale[ $msgid ] = $entry->translations;
		}

		return $locale;
	}
}

function monsterinsights_get_inline_menu_icon() {
	$scheme          = get_user_option( 'admin_color', get_current_user_id() );
	$use_dark_scheme = $scheme === 'light';
	if ( $use_dark_scheme ) {
		return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAFQUlEQVRYha2Yb2hXZRTHP+c3nc6pm07NF0KWWUtSo0wqzBdiZRItTKMaEZXSi0zRNAsqTBKKSFOa0B8Jigqz2lSwLMtqRURgRuCCLLNmselyZups2+/04pzbnt3de3eTDlzufc5znvN8n+ec55zzXFFV8pKITANOqmpTP3JTgIKq7sutPCJVzfUABeAb4DSwMENuKdABNObV3Wv8fwB0C6DAUX8/67sQ9Q8ANsVk5v5vgIDKWHsvcAgYCWzzCbc6kFJgh/PqgVHAb8DnWTpzA3LzHARmeXuqT/Zo0L/eeZuAV/x7fbRrwJPOu9Dbc4EDgJwNoMmurAt4Bljt7cmBjACvOl+BzTEdVzj/EWAj0O3tC84G0AIf3BRMeDz0GZcbBvzqKy+L9Q30A6AxXTdmARqQcPAAyv29CBjjO1RU1SKAiIwGFgLX+MrbgBnAh5ECVe0UkUMO6nHgFLA70J1McacD5gHbfTXzg77qwBeOBysPn830PnnVwXety7wL1AAV/ZoM+MIHdQCfAdfF+s8H/koBEz0rU9xgLtAInHG5j/KYrNWf8ap6OmFD7w+2/Cugwd/NmOkqgbIUS+wEdorIEOAwFqv6UBKgihQwANNc0b2quh1ARIZi/nUqZUycOrDDcCSps5AAaJBPkkStwNVAs4i8JiLHgBPASRFpFZEGEZktIpIBqBIoIWWH4nZegtl3fIofjAKeoyemfAe8hZnu64D/NjAsRcdEl1mcx6lvc+HLU6L3O97/JXBlgszF9KSVvXhswkxUC6wLdKzIA2iWC1+fMNlK72sASlMjrQHf4LIvAw8B7fScwmNAZ7DDs7MARSmjNsYf7oqak0wBjAXuBlb5Lo9wE0Yg6rHAOdjlR2KB9Qc384o0QOe4giUx/u3OX5oA5gEsCoexqBnYAxTTfMXHlvuOF4F5SYBKHPGaGH+jTzQxxefSnnVpYAIdg9x0PwEDkwSOAHUx3hafoDzGP5AB5gQ56h/XU+NjauJxCCxRjo7xOvw9ImKISBUwIWF8RLtVtT2jP6SdWBKe1QuQiCwDLsKcNKSoqJ8e8BJTREAHc4JBVTuBn4Gx/wISkflYndyNOXdI2/29OOAd7mfSIXkBOZUDxTACt2A78SLQnmDnBszOiwLeraT70Ld5/Mf1jPMxqyLGWqxcnYoFMqVvBTgOK9y7gOVAifMfdF4SqJk5Aa3FLFMNduxagQbvvJOUfIb51/f0lKSrsROyHCtlIyDtrrMJqOoHzAysRvrA28wmSBfAtd7uk6u8vwwr/JOqxm4sl01wvZ3AfhJyo+taAPyJhYi/gekCPIXdNitV9YyIXIIFqptVdVsf13MSkVJgJlZF4rvSqKq/BzJzgNexcPEp8LFPXAHcAFzqoKcAddjR5z2Cay/m4Arcl9cp+zFJFfA0dslMOwB1wD1AewGrTw4Ei2/zVcSP/lmRqrap6irs8gAwid7xDOAuzNwlgmXxF1T14ahXRPZjtU1k3+g5Tk8pkUUFzCwVWC003N/DgGVYIXheIF/EfmQcFczDW4DnsVtBCxbUtmIOPAAzY6MPLgMG+/dlDrIADHWlYL4QpZuZWLjYgp3SOb7QMbFFFLF6LDNB7sGcri7FP7qwWmcX9t8oSWaDA6zCqomXUuZ6U1UpYDXxH5jfgKWET/y7zXfolIgkJeJMEpES/xwMXKWq3aq6CLu9PAH8Eog/Fn2UYnlkDWa2c719E3Y/f8NX0AL8GHuianAXtuXx/lZ6brR9/npgcWgHcEfEkyg6ZqyyBrt1ptE+X9SkDJl6VX0/cyKnfwBb6gwNaZ8ExgAAAABJRU5ErkJggg';
	} else {
		return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH4AoEBjcfBsDvpwAABQBJREFUWMO1mGmollUQgJ9z79Vc01LLH0GLWRqlUhYV5o+LbRIVbVQSUSn9qJTKsqDCoqCINKUbtBEUFbbeDGyz1SIiaCHIINu18KZ1bbkuV+/Tj+arw8v7fvdVcuDjvGdmzsycM3Nm5nywE6BOVSfW4JukTmF3gtqifqJuVmc34ZunblFX7W6DzvYf2BDjPWpLRm9T7y/wzPw/DRhZmH+sfq/urb4YCp8JQwaqLwXuBXW0+pP6XjOZO+ueb9X2mE8OZTdl9MWBu199NL4XN05NvT1wh8R8prpGTbti0BEhbLt6t7ow5kdkPEl9zP/gkYKMowN/o7pU3RHzg3fFoHNj8epM4aY8ZoJvuPpj7HxwgTYgLoAFWac1091WgR8a4xxgH2Ah0JdS6gtlY4DZwAnADmAjMA14vSEgpdSrfg9sBm4BeoCVmex6gayepS6P3ZyT0SZksbDJcnikcPMmZN+zgud59Qx1RB2D3o9FW9R31ZMK9IPUP20O11XInqmuUrcG3xt1XNYVvwNSSptL+K/IjvxDoDPGteG6kcDgMkUppRXACnUIsA7YUNegERXGAEwNQZellJbHzodFfPXUjIwtwHDglzJiS4lBe4SSMugCjgfWqo+rvwF/AH+pXWqnOqOfXDMSaK06oaKf54Z/D6igj1bvzXLK5+rTYchHGf5ZdXiFjPHBc2Udg84P5qMqsvdzQf9APbaEZ2JWVj5u5KbIV7PURZmM+XUMag/mk0to1wWtUx3YT9lZErwPq9er3dkt/E3tzU54Rp2SMauA3zMErS1zhTpWvURdEKe8V7jQrOBOUwcF/97qbPWrcPP8KoP2DQFzC/gLAj+vZM1Vak8hF61V31L7msWKOjROvE89q4yhNSy+rYBfGorGV8RcFSyqESZ7hOu+UQeUMfyidhRwy0LB0AJ+TRNj/qjb/0QpUT2jpYS+ERhTkswA9sqEjALGNdGzMqXUXTNZrogi3F5sJ64GDgXGFhasjvGYDDe4HyXf1i3qKaVe4DtgbF6ZzwHuiZq0b2HN8hjzAF3Xj9IhO9mGDQX68gy8PpqoB9XuEj93hp/nZLjzmsTQZzvR9uwXaxY0EHdEuzo5EpklHeB+0bhvV69RWwN/beDKYHpNg+6I2z2hce261M4gXlRVz9RD1S+zlnRh3JBropVtQHfIXB3B38yYadEjvdZAzMjLhXpizI+tEDA4Gv+yrnFH1LJxIbdX/aKsNma9+++RIrapxyT1TmAeMDKltFU9HPgcODOl9GKTnQ0EpgMHBaobWJVS+jnjOQV4ItLFO8CbwDZgBHAqMAXoBSYBHcBm1JfzZ28EuOrl/9ODc5R6Vzwyq6BDvVTtbgHGA2sKiXFbydXfJUgpbUwpLQAateqwQj4DuDjSTWuKru+BlNIN2a6+ACYCv0dH2PhtCtfYjx0t4ZYR0a7uGeNw4GpgLnBgxt8HfAJsSOpWYD1wH7AqvocAz0Q2bgNGB62RoQfF95FhZAswLIQSZaBRbqYDPwHLogqcEhvdp7CJPqC9vwL5VtyUjor42B69zqvqXxU8S+IFOyq6iYcqdD3VONqngV8jbhol4e0sntqAnuIzumZAt8bnIOC4lNKOlNKceL3cCvyQsd/87/WNRuk29T51/5ifHu/zJ2MH69WvCz+zE+oroXdlL9pUkYdeUi/89xLU6VWAZn88fQoMjNtTBS+klF6pc6p/A2ye4OCYzm1lAAAAAElFTkSuQmCC';
	}
}


function monsterinsights_get_shareasale_id() {
	// Check if there's a constant.
	$shareasale_id = '';
	if ( defined( 'MONSTERINSIGHTS_SHAREASALE_ID' ) ) {
		$shareasale_id = MONSTERINSIGHTS_SHAREASALE_ID;
	}

	// If there's no constant, check if there's an option.
	if ( empty( $shareasale_id ) ) {
		$shareasale_id = get_option( 'monsterinsights_shareasale_id', '' );
	}

	// Whether we have an ID or not, filter the ID.
	$shareasale_id = apply_filters( 'monsterinsights_shareasale_id', $shareasale_id );

	// Ensure it's a number
	$shareasale_id = absint( $shareasale_id );

	return $shareasale_id;
}

// Passed in with mandatory default redirect and shareasaleid from monsterinsights_get_upgrade_link
function monsterinsights_get_shareasale_url( $shareasale_id, $shareasale_redirect ) {
	// Check if there's a constant.
	$custom = false;
	if ( defined( 'MONSTERINSIGHTS_SHAREASALE_REDIRECT_URL' ) ) {
		$shareasale_redirect = MONSTERINSIGHTS_SHAREASALE_REDIRECT_URL;
		$custom              = true;
	}

	// If there's no constant, check if there's an option.
	if ( empty( $custom ) ) {
		$shareasale_redirect = get_option( 'monsterinsights_shareasale_redirect_url', '' );
		$custom              = true;
	}

	// Whether we have an ID or not, filter the ID.
	$shareasale_redirect = apply_filters( 'monsterinsights_shareasale_redirect_url', $shareasale_redirect, $custom );
	$shareasale_url      = sprintf( 'https://www.shareasale.com/r.cfm?B=971799&U=%s&M=69975&urllink=%s', $shareasale_id, $shareasale_redirect );
	$shareasale_url      = apply_filters( 'monsterinsights_shareasale_redirect_entire_url', $shareasale_url, $shareasale_id, $shareasale_redirect );
	return $shareasale_url;
}

/**
 * Get a clean page title for archives.
 */
function monsterinsights_get_page_title() {

	$title = __( 'Archives' );

	if ( is_category() ) {
		/* translators: Category archive title. %s: Category name */
		$title = sprintf( __( 'Category: %s' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		/* translators: Tag archive title. %s: Tag name */
		$title = sprintf( __( 'Tag: %s' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		/* translators: Author archive title. %s: Author name */
		$title = sprintf( __( 'Author: %s' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		/* translators: Yearly archive title. %s: Year */
		$title = sprintf( __( 'Year: %s' ), get_the_date( _x( 'Y', 'yearly archives date format' ) ) );
	} elseif ( is_month() ) {
		/* translators: Monthly archive title. %s: Month name and year */
		$title = sprintf( __( 'Month: %s' ), get_the_date( _x( 'F Y', 'monthly archives date format' ) ) );
	} elseif ( is_day() ) {
		/* translators: Daily archive title. %s: Date */
		$title = sprintf( __( 'Day: %s' ), get_the_date( _x( 'F j, Y', 'daily archives date format' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title' );
		}
	} elseif ( is_post_type_archive() ) {
		/* translators: Post type archive title. %s: Post type name */
		$title = sprintf( __( 'Archives: %s' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s' ), $tax->labels->singular_name, single_term_title( '', false ) );
	}

	return $title;

}

/**
 * Count the number of occurrences of UA tags inserted by third-party plugins.
 *
 * @param string $body
 * @param string $type
 *
 * @return int
 */
function monsterinsights_count_third_party_ua_codes( $body, $type = 'ua' ) {
	$count = 0;

	// If the ads addon is installed another UA is added to the page.
	if ( class_exists( 'MonsterInsights_Ads' ) ) {
		$count++;
	}

	// Count all potential google site verification tags
	if ( $type === 'ua' ) {
		$pattern = '/content="UA-[0-9-]+"/';

		if ( preg_match_all( $pattern, $body, $matches ) ) {
			$count += count( $matches[0] );
		}
	}

	// Advanced Ads plugin (https://wpadvancedads.com)
	// When `Ad blocker counter` setting is populated with an UA ID
	if ( class_exists( 'Advanced_Ads' ) ) {
		$options = Advanced_Ads::get_instance()->options();

		$pattern = '/UA-[0-9-]+/';
		if ( $type === 'ua' && isset( $options['ga-UID'] ) && preg_match( $pattern, $options['ga-UID'] ) ) {
			++ $count;
		}
	}

	// WP Popups plugin (https://wppopups.com/)
	// When `Google UA Code` setting is populated with an UA Id
	if ( function_exists( 'wppopups_setting' ) ) {
		$code = wppopups_setting( 'ua-code' );

		$pattern = '/UA-[0-9-]+/';
		if ( $type === 'ua' && ! empty( $code ) && preg_match( $pattern, $code ) ) {
			++ $count;
		}
	}

	return $count;
}

/**
 * Detect tracking code error depending on the type of tracking code
 *
 * @param string $body
 * @param string $type
 *
 * @return array
 */
function monsterinsights_detect_tracking_code_error( $body, $type = 'ua' ) {
	$errors = array();

	$current_code = $type === 'ua'
		? monsterinsights_get_ua_to_output()
		: monsterinsights_get_v4_id_to_output();

	// Translators: The placeholders are for making the "We noticed you're using a caching plugin" text bold.
	$cache_error = sprintf( esc_html__( '%1$sWe noticed you\'re using a caching plugin or caching from your hosting provider.%2$s Be sure to clear the cache to ensure the tracking appears on all pages and posts. %3$s(See this guide on how to clear cache)%4$s.', 'google-analytics-for-wordpress' ), '<b>', '</b>', ' <a href="https://www.wpbeginner.com/beginners-guide/how-to-clear-your-cache-in-wordpress/" target="_blank">', '</a>' );

	// Check if the current UA code is actually present.
	if ( $current_code && false === strpos( $body, $current_code ) ) {
		// We have the tracking code but using another UA, so it's cached.
		$errors[] = $cache_error;
		return $errors;
	}

	if ( empty( $current_code ) ) {
		return $errors;
	}

	if ( $type === 'v4' && false === strpos( $body, '__gtagTracker' ) ) {
		$errors[] = $cache_error;
		return $errors;
	}

	$limit = 3;

	// TODO: Need to re-evaluate this regularly when third party plugins start supporting v4
	$limit += monsterinsights_count_third_party_ua_codes( $body, $type );

	// Count all the codes from the page.
	$total_count = substr_count( $body, $current_code );

	// Count the `send_to` instances which are valid
	$pattern = '/send_to[\'"]*?:\s*[\'"]' . $current_code . '/m';
	if ( preg_match_all( $pattern, $body, $matches ) ) {
		$total_count -= count( $matches[0] );
	}

	// Main property always has a ?id=(UA|G)-XXXXXXXX script
	$connected_type = MonsterInsights()->auth->get_connected_type();
	if ( $type === $connected_type && strpos( $body, 'googletagmanager.com/gtag/js?id=' . $current_code ) !== false ) {
		// In that case, we can safely deduct one from the total count
		-- $total_count;
	}

	if ( $total_count > $limit ) {
		// Translators: The placeholders are for making the "We have detected multiple tracking codes" text bold & adding a link to support.
		$message           = esc_html__( '%1$sWe have detected multiple tracking codes%2$s! You should remove non-MonsterInsights ones. If you need help finding them please %3$sread this article%4$s.', 'google-analytics-for-wordpress' );
		$url               = monsterinsights_get_url( 'site-health', 'comingsoon', 'https://www.monsterinsights.com/docs/how-to-find-duplicate-google-analytics-tracking-codes-in-wordpress/' );
		$multiple_ua_error = sprintf(
			$message,
			'<b>',
			'</b>',
			'<a href="' . $url . '" target="_blank">',
			'</a>'
		);

		$errors[] = $multiple_ua_error;
	}

	return $errors;
}

/**
 * Make a request to the front page and check if the tracking code is present. Moved here from onboarding wizard
 * to be used in the site health check.
 *
 * @return array
 */
function monsterinsights_is_code_installed_frontend() {
	// Grab the front page html.
	$request = wp_remote_request( home_url(), array(
		'sslverify' => false,
	) );
	$errors  = array();

	$accepted_http_codes = array(
		200,
		503
	);

	$response_code = wp_remote_retrieve_response_code( $request );

	if ( in_array( $response_code, $accepted_http_codes, true ) ) {
		$body            = wp_remote_retrieve_body( $request );

		$errors = array_merge(
			monsterinsights_detect_tracking_code_error( $body ),
			monsterinsights_detect_tracking_code_error( $body, 'v4' )
		);
	}

	return $errors;
}

/**
 * Returns a HEX color to highlight menu items based on the admin color scheme.
 */
function monsterinsights_menu_highlight_color() {

	$color_scheme = get_user_option( 'admin_color' );
	$color        = '#1da867';
	if ( 'light' === $color_scheme || 'blue' === $color_scheme ) {
		$color = '#5f3ea7';
	}

	return $color;
}

/**
 * Track Pretty Links redirects with MonsterInsights.
 *
 * @param string $url The url to which users get redirected.
 */
function monsterinsights_custom_track_pretty_links_redirect( $url ) {
	if ( ! function_exists( 'monsterinsights_mp_track_event_call' ) && ! function_exists( 'monsterinsights_mp_collect_v4') ) {
		return;
	}
	// Try to determine if click originated on the same site.
	$referer = ! empty( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
	if ( ! empty( $referer ) ) {
		$current_site_url    = get_bloginfo( 'url' );
		$current_site_parsed = wp_parse_url( $current_site_url );
		$parsed_referer      = wp_parse_url( $referer );
		if ( ! empty( $parsed_referer['host'] ) && ! empty( $current_site_parsed['host'] ) && $current_site_parsed['host'] === $parsed_referer['host'] ) {
			// Don't track clicks originating from same site as those are tracked with JS.
			return;
		}
	}
	// Check if this is an affiliate link and use the appropriate category.
	$ec            = 'outbound-link';
	$inbound_paths = monsterinsights_get_option( 'affiliate_links', array() );
	$path          = empty( $_SERVER['REQUEST_URI'] ) ? '' : $_SERVER['REQUEST_URI'];
	if ( ! empty( $inbound_paths ) && is_array( $inbound_paths ) && ! empty( $path ) ) {
		$found = false;
		foreach ( $inbound_paths as $inbound_path ) {
			if ( empty( $inbound_path['path'] ) ) {
				continue;
			}
			if ( 0 === strpos( $path, trim( $inbound_path['path'] ) ) ) {
				$label = ! empty( $inbound_path['label'] ) ? trim( $inbound_path['label'] ) : 'aff';
				$ec   .= '-' . $label;
				$found = true;
				break;
			}
		}
		if ( ! $found ) {
			return;
		}
	} else {
		// no paths setup in MonsterInsights settings
		return;
	}

	if ( monsterinsights_get_ua_to_output() ) {
		$track_args = array(
			't'  => 'event',
			'ec' => $ec,
			'ea' => $url,
			'el' => 'external-redirect',
		);
		monsterinsights_mp_track_event_call( $track_args );
	}

	if ( monsterinsights_get_v4_id_to_output() ) {
		$url_components = parse_url( $url );
		$args = array(
			'events' => array(
				array(
					'link_text' => 'external-redirect',
					'link_url' => $url,
					'link_domain' => $url_components['host'],
					'outbound' => true,
				)
			)
		);

		if ( ! empty( $label ) ) {
			$args['events'][0]['affiliate_label'] = $label;
			$args['events'][0]['is_affiliate_link'] = true;
		}

		monsterinsights_mp_collect_v4( $args );
	}
}
add_action( 'prli_before_redirect', 'monsterinsights_custom_track_pretty_links_redirect' );

/**
 * Get post type in admin side
 *
 */
function monsterinsights_get_current_post_type() {
	global $post, $typenow, $current_screen;

	if ( $post && $post->post_type ) {
		return $post->post_type;
	} elseif ( $typenow ) {
		return $typenow;
	} elseif ( $current_screen && $current_screen->post_type ) {
		return $current_screen->post_type;
	} elseif ( isset( $_REQUEST['post_type'] ) ) {
		return sanitize_key( $_REQUEST['post_type'] );
	}

	return null;
}

/** Decode special characters, both alpha- (<) and numeric-based (').
 *
 * @since 7.10.5
 *
 * @param string $string Raw string to decode.
 *
 * @return string
 */
function monsterinsights_decode_string( $string ) {

	if ( ! is_string( $string ) ) {
		return $string;
	}

	return wp_kses_decode_entities( html_entity_decode( $string, ENT_QUOTES ) );
}

add_filter( 'monsterinsights_email_message', 'monsterinsights_decode_string' );

/**
 * Sanitize a string, that can be a multiline.
 * If WP core `sanitize_textarea_field()` exists (after 4.7.0) - use it.
 * Otherwise - split onto separate lines, sanitize each one, merge again.
 *
 * @since 7.10.5
 *
 * @param string $string
 *
 * @return string If empty var is passed, or not a string - return unmodified. Otherwise - sanitize.
 */
function monsterinsights_sanitize_textarea_field( $string ) {

	if ( empty( $string ) || ! is_string( $string ) ) {
		return $string;
	}

	if ( function_exists( 'sanitize_textarea_field' ) ) {
		$string = sanitize_textarea_field( $string );
	} else {
		$string = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $string ) ) );
	}

	return $string;
}

/**
 * Trim a sentence
 *
 * @since 7.10.5
 *
 * @param string $string
 * @param int $count
 *
 * @return trimed sentence
 */
function monsterinsights_trim_text( $text, $count ){
	$text 	= str_replace("  ", " ", $text);
	$string = explode(" ", $text);
	$trimed = "";

	for ( $wordCounter = 0; $wordCounter <= $count; $wordCounter++ ) {
		$trimed .= isset( $string[$wordCounter] ) ? $string[$wordCounter] : '';

		if ( $wordCounter < $count ){
			$trimed .= " ";
		} else {
			$trimed .= "...";
		}
	}

	$trimed = trim($trimed);

	return $trimed;
}

/**
 * Add newly generated builder URL to PrettyLinks &
 * Clear localStorage key(MonsterInsightsURL) after saving PrettyLink
 */
function monsterinsights_tools_copy_url_to_prettylinks() {
	global $pagenow;

	$post_type                 = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
	$monsterinsights_reference = isset( $_GET['monsterinsights_reference'] ) ? $_GET['monsterinsights_reference'] : '';

	if ( 'post-new.php' === $pagenow && 'pretty-link' === $post_type && 'url_builder' === $monsterinsights_reference ) { ?>
        <script>
            let targetTitleField = document.querySelector("input[name='post_title']");
            let targetUrlField = document.querySelector("textarea[name='prli_url']");
            let MonsterInsightsUrl = JSON.parse(localStorage.getItem('MonsterInsightsURL'));
            if ( 'undefined' !== typeof targetUrlField && 'undefined' !== typeof MonsterInsightsUrl ) {
                let url = MonsterInsightsUrl.value;
                let postTitle = '';
                let pathArray = url.split('?');
                if ( pathArray.length <= 1 ) {
                    pathArray = url.split('#');
                }
                let urlParams = new URLSearchParams(pathArray[1]);
                if (urlParams.has('utm_campaign')) {
                    let campaign_name = urlParams.get('utm_campaign');
                    postTitle += campaign_name;
                }
                if (urlParams.has('utm_medium')) {
                    let campaign_medium = urlParams.get('utm_medium');
                    postTitle += ` ${campaign_medium}`;
                }
                if (urlParams.has('utm_source')) {
                    let campaign_source = urlParams.get('utm_source');
                    postTitle += ` on ${campaign_source}`;
                }
                if (urlParams.has('utm_term')) {
                    let campaign_term = urlParams.get('utm_term');
                    postTitle += ` for ${campaign_term}`;
                }
                if (urlParams.has('utm_content')) {
                    let campaign_content = urlParams.get('utm_content');
                    postTitle += ` - ${campaign_content}`;
                }
                if ( 'undefined' !== typeof targetTitleField && postTitle ) {
                    targetTitleField.value = postTitle;
                }
                if( url ) {
                    targetUrlField.value = url;
                }
            }
            let form = document.getElementById('post');
            form.addEventListener('submit', function(){
                localStorage.removeItem('MonsterInsightsURL');
            });
        </script>
	<?php }
}
add_action( 'admin_footer', 'monsterinsights_tools_copy_url_to_prettylinks' );

/**
 * When click on 'Create New Pretty Link" button(on tools/prettylinks-flow page) after installing & activating prettylinks plugin
 * it redirects to PrettyLinks welcome scree page instead of prettylinks add new page.
 * This function will skip that welcome screen
 */
function monsterinsights_skip_prettylinks_welcome_screen() {
	global $pagenow;

	$post_type                 = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
	$monsterinsights_reference = isset( $_GET['monsterinsights_reference'] ) ? $_GET['monsterinsights_reference'] : '';

	if ( 'post-new.php' === $pagenow && 'pretty-link' === $post_type && 'url_builder' === $monsterinsights_reference ) {
		$onboard  = get_option( 'prli_onboard' );

		if ( $onboard == 'welcome' || $onboard == 'update' ) {
			update_option( 'monsterinsights_backup_prli_onboard_value', $onboard );
			delete_option( 'prli_onboard' );
		}
	}
}
add_action( 'wp_loaded', 'monsterinsights_skip_prettylinks_welcome_screen', 9 );

/**
 * Restore the `prli_onboard` value after creating a prettylinks with monsterinsights prettylinks flow
 * users will see the prettylinks welcome screen after fresh installation & creating prettylinks with monsterinsights prettylinks flow
 */
function monsterinsights_restore_prettylinks_onboard_value() {
	global $pagenow;

	$post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';

	if ( 'edit.php' === $pagenow && 'pretty-link' === $post_type ) {
		$onboard   = get_option( 'monsterinsights_backup_prli_onboard_value' );

		if ( class_exists( 'PrliBaseController' ) && ( $onboard == 'welcome' || $onboard == 'update' ) ) {
			update_option( 'prli_onboard', $onboard );
			delete_option( 'monsterinsights_backup_prli_onboard_value' );
		}
	}
}
add_action( 'wp_loaded', 'monsterinsights_restore_prettylinks_onboard_value', 15 );

/**
 * Check WP version and include the compatible upgrader skin.
 *
 * @param bool $custom_upgrader If true it will include our custom upgrader, otherwise it will use the default WP one.
 */
function monsterinsights_require_upgrader( $custom_upgrader = true ) {

	global $wp_version;

	$base = MonsterInsights();

	if ( ! $custom_upgrader ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	}

	// WP 5.3 changes the upgrader skin.
	if ( version_compare( $wp_version, '5.3', '<' ) ) {
		if ( $custom_upgrader ) {
			require_once plugin_dir_path( $base->file ) . 'includes/admin/licensing/plugin-upgrader.php';
		}
		require_once plugin_dir_path( $base->file ) . '/includes/admin/licensing/skin-legacy.php';
	} else {
		if ( $custom_upgrader ) {
			require_once plugin_dir_path( $base->file ) . 'includes/admin/licensing/plugin-upgrader.php';
		}
		require_once plugin_dir_path( $base->file ) . '/includes/admin/licensing/skin.php';
	}

}

/**
 * Load headline analyzer if wp version is higher than/equal to 5.4
 *
 * @return boolean
 * @since 7.12.3
 *
 */
function monsterinsights_load_gutenberg_app() {
	global $wp_version;

	if ( version_compare( $wp_version, '5.4', '<' ) ) {
		return false;
	}

	return true;
}

/**
 * Helper function for frontend script attributes
 *
 * @return string
 * @since 7.12.3
 *
 *
 */
function monsterinsights_get_frontend_analytics_script_atts() {
	$attr_string = '';

	$attributes = apply_filters( 'monsterinsights_tracking_analytics_script_attributes', array(
		'type'         => "text/javascript",
		'data-cfasync' => 'false',
		'data-wpfc-render' => 'false'
	) );

	if ( ! empty( $attributes ) ) {
		foreach ( $attributes as $attr_name => $attr_value ) {
			if ( ! empty( $attr_name ) ) {
				$attr_string .= ' ' . sanitize_key( $attr_name ) . '="' . esc_attr( $attr_value ) . '"';
			} else {
				$attr_string .= ' ' . esc_attr( $attr_value );
			}
		}
	}

	return $attr_string;
}

/**
 * Get native english speaking countries
 *
 * @return array
 *
 * @since 7.12.3
 */
function monsterinsights_get_english_speaking_countries() {
	return array(
		'AG' => __( 'Antigua and Barbuda', 'google-analytics-for-wordpress' ),
		'AU' => __( 'Australia', 'google-analytics-for-wordpress' ),
		'BB' => __( 'Barbados', 'google-analytics-for-wordpress' ),
		'BZ' => __( 'Belize', 'google-analytics-for-wordpress' ),
		'BW' => __( 'Botswana', 'google-analytics-for-wordpress' ),
		'BI' => __( 'Burundi', 'google-analytics-for-wordpress' ),
		'CM' => __( 'Cameroon', 'google-analytics-for-wordpress' ),
		'CA' => __( 'Canada', 'google-analytics-for-wordpress' ),
		'DM' => __( 'Dominica', 'google-analytics-for-wordpress' ),
		'FJ' => __( 'Fiji', 'google-analytics-for-wordpress' ),
		'GD' => __( 'Grenada', 'google-analytics-for-wordpress' ),
		'GY' => __( 'Guyana', 'google-analytics-for-wordpress' ),
		'GM' => __( 'Gambia', 'google-analytics-for-wordpress' ),
		'GH' => __( 'Ghana', 'google-analytics-for-wordpress' ),
		'IE' => __( 'Ireland', 'google-analytics-for-wordpress' ),
		'IN' => __( 'India', 'google-analytics-for-wordpress' ),
		'JM' => __( 'Jamaica', 'google-analytics-for-wordpress' ),
		'KE' => __( 'Kenya', 'google-analytics-for-wordpress' ),
		'KI' => __( 'Kiribati', 'google-analytics-for-wordpress' ),
		'LS' => __( 'Lesotho', 'google-analytics-for-wordpress' ),
		'LR' => __( 'Liberia', 'google-analytics-for-wordpress' ),
		'MW' => __( 'Malawi', 'google-analytics-for-wordpress' ),
		'MT' => __( 'Malta', 'google-analytics-for-wordpress' ),
		'MH' => __( 'Marshall Islands', 'google-analytics-for-wordpress' ),
		'MU' => __( 'Mauritius', 'google-analytics-for-wordpress' ),
		'FM' => __( 'Micronesia', 'google-analytics-for-wordpress' ),
		'NZ' => __( 'New Zealand', 'google-analytics-for-wordpress' ),
		'NA' => __( 'Namibia', 'google-analytics-for-wordpress' ),
		'NR' => __( 'Nauru', 'google-analytics-for-wordpress' ),
		'NG' => __( 'Nigeria', 'google-analytics-for-wordpress' ),
		'PK' => __( 'Pakistan', 'google-analytics-for-wordpress' ),
		'PW' => __( 'Palau', 'google-analytics-for-wordpress' ),
		'PG' => __( 'Papua New Guinea', 'google-analytics-for-wordpress' ),
		'PH' => __( 'Philippines', 'google-analytics-for-wordpress' ),
		'RW' => __( 'Rwanda', 'google-analytics-for-wordpress' ),
		'SG' => __( 'Singapore', 'google-analytics-for-wordpress' ),
		'KN' => __( 'St Kitts and Nevis', 'google-analytics-for-wordpress' ),
		'LC' => __( 'St Lucia', 'google-analytics-for-wordpress' ),
		'VC' => __( 'St Vincent and the Grenadines', 'google-analytics-for-wordpress' ),
		'SZ' => __( 'Swaziland', 'google-analytics-for-wordpress' ),
		'WS' => __( 'Samoa', 'google-analytics-for-wordpress' ),
		'SC' => __( 'Seychelles', 'google-analytics-for-wordpress' ),
		'SL' => __( 'Sierra Leone', 'google-analytics-for-wordpress' ),
		'SB' => __( 'Solomon Islands', 'google-analytics-for-wordpress' ),
		'ZA' => __( 'South Africa', 'google-analytics-for-wordpress' ),
		'SS' => __( 'South Sudan', 'google-analytics-for-wordpress' ),
		'SD' => __( 'Sudan', 'google-analytics-for-wordpress' ),
		'TT' => __( 'Trinidad and Tobago', 'google-analytics-for-wordpress' ),
		'BS' => __( 'The Bahamas', 'google-analytics-for-wordpress' ),
		'TZ' => __( 'Tanzania', 'google-analytics-for-wordpress' ),
		'TO' => __( 'Tonga', 'google-analytics-for-wordpress' ),
		'TV' => __( 'Tuvalu', 'google-analytics-for-wordpress' ),
		'GB' => __( 'United Kingdom', 'google-analytics-for-wordpress' ),
		'US' => __( 'United States of America', 'google-analytics-for-wordpress' ),
		'UG' => __( 'Uganda', 'google-analytics-for-wordpress' ),
		'VU' => __( 'Vanuatu', 'google-analytics-for-wordpress' ),
		'ZM' => __( 'Zambia', 'google-analytics-for-wordpress' ),
		'ZW' => __( 'Zimbabwe', 'google-analytics-for-wordpress' ),
	);
}

/**
 * Helper function to check if the current user can install a plugin.
 *
 * @return bool
 */
function monsterinsights_can_install_plugins() {

	if ( ! current_user_can( 'install_plugins' ) ) {
		return false;
	}

	// Determine whether file modifications are allowed.
	if ( function_exists( 'wp_is_file_mod_allowed' ) && ! wp_is_file_mod_allowed( 'monsterinsights_can_install' ) ) {
		return false;
	}

	return true;
}

/**
 * Check if current date is between given dates. Date format: Y-m-d.
 *
 * @since 7.13.2
 *
 * @param string $start_date Start Date. Eg: 2021-01-01.
 * @param string $end_date   End Date. Eg: 2021-01-14.
 *
 * @return bool
 */
function monsterinsights_date_is_between( $start_date, $end_date ) {

	$current_date = current_time( 'Y-m-d' );

	$start_date = date( 'Y-m-d', strtotime( $start_date ) );
	$end_date   = date( 'Y-m-d', strtotime( $end_date ) );

	if ( ( $current_date >= $start_date ) && ( $current_date <= $end_date ) ) {
		return true;
	}

	return false;
}

/**
 * Check is All-In-One-Seo plugin is active or not.
 *
 * @since 7.17.0
 *
 * @return bool
 */
function monsterinsights_is_aioseo_active() {

	if ( function_exists( 'aioseo' ) ) {
		return true;
	}

	return false;
}

/**
 * Return AIOSEO Dashboard URL if plugin is active.
 *
 * @since 7.17.0
 *
 * @return string
 */
function monsterinsights_aioseo_dashboard_url() {
	$url = '';

	if ( function_exists( 'aioseo' ) ) {
		$url = is_multisite() ? network_admin_url( 'admin.php?page=aioseo' ) : admin_url( 'admin.php?page=aioseo' );
	}

	return $url;
}

/**
 * Check if AIOSEO Pro version is installed or not.
 *
 * @since 7.17.10
 *
 * @return bool
 */
function monsterinsights_is_installed_aioseo_pro() {
	$installed_plugins = get_plugins();

	if ( array_key_exists( 'all-in-one-seo-pack-pro/all_in_one_seo_pack.php', $installed_plugins ) ) {
		return true;
	}

	return false;
}
