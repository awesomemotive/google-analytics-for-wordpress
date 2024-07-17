<?php
/**
 * Capabilities class.
 *
 * @access public
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Capabilities
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Map MonsterInsights Capabilities.
 *
 * Using meta caps, we're creating virtual capabilities that are
 * for backwards compatibility reasons given to users with manage_options, and to
 * users who have at least of the roles selected in the options on the permissions
 * tab of the MonsterInsights settings.
 *
 * @access public
 *
 * @param array $caps Array of capabilities the user has.
 * @param string $cap The current cap being filtered.
 * @param int $user_id User to check permissions for.
 * @param array $args Extra parameters. Unused.
 *
 * @return array Array of caps needed to have this meta cap. If returned array is empty, user has the capability.
 * @since 6.0.0
 *
 */
function monsterinsights_add_capabilities( $caps, $cap, $user_id, $args ) {

	switch ( $cap ) {
		case 'monsterinsights_view_dashboard' :
			$roles = monsterinsights_get_option( 'view_reports', array() );

			$user_can_via_settings = false;
			if ( ! empty( $roles ) && is_array( $roles ) ) {
				foreach ( $roles as $role ) {
					if ( is_string( $role ) ) {
						if ( user_can( $user_id, $role ) ) {
							$user_can_via_settings = true;
							break;
						}
					}
				}
			} else if ( ! empty( $roles ) && is_string( $roles ) ) {
				if ( user_can( $user_id, $roles ) ) {
					$user_can_via_settings = true;
				}
			}

			if ( user_can( $user_id, 'manage_options' ) || $user_can_via_settings ) {
				$caps = array();
			}

			break;
		case 'monsterinsights_save_settings' :
			$roles = monsterinsights_get_option( 'save_settings', array() );

			$user_can_via_settings = false;
			if ( ! empty( $roles ) && is_array( $roles ) ) {
				foreach ( $roles as $role ) {
					if ( is_string( $role ) ) {
						if ( user_can( $user_id, $role ) ) {
							$user_can_via_settings = true;
							break;
						}
					}
				}
			} else if ( ! empty( $roles ) && is_string( $roles ) ) {
				if ( user_can( $user_id, $roles ) ) {
					$user_can_via_settings = true;
				}
			}

			if ( user_can( $user_id, 'manage_options' ) || $user_can_via_settings ) {
				$caps = array();
			}

			break;
	}

	return $caps;
}

add_filter( 'map_meta_cap', 'monsterinsights_add_capabilities', 10, 4 );
