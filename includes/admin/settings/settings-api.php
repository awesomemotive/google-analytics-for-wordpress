<?php
/**
 * Settings API for the Tracking Tab
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Settings API
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Get the settings for a section
 *
 * @since 6.0.0
 * @return void
*/
function monsterinsights_get_section_settings( $section, $page = 'tracking' ) {
	$output = '';
	$settings = monsterinsights_get_registered_settings();
	if ( is_array( $settings ) && ! empty( $settings[$section] ) && is_array( $settings[$section] ) ) {
		foreach ( $settings[$section] as $setting ) {
			$args = wp_parse_args( $setting, array(
				'id'            => null,
				'desc'          => '',
				'name'          => '',
				'size'          => null,
				'options'       => '',
				'std'           => '',
				'min'           => null,
				'max'           => null,
				'step'          => null,
				'select2'        => null,
				'placeholder'   => null,
				'allow_blank'   => true,
				'readonly'      => false,
				'faux'          => false,
				'tooltip_title' => false,
				'tooltip_desc'  => false,
				'field_class'   => '',
				'multiple'      => false,
				'allowclear'    => true,
				'notice_type'   => 'info',
				'no_label'      => false,
			) );
			$output .= monsterinsights_render_field( $args );
		}
	}
	return $output;
}

/**
 * Saves Settings
 *
 * @since 6.0.0
 * @access public
 *
 * @return null Return early if not fixing the broken migration
 */
function monsterinsights_save_settings() {

	// Check if user pressed the 'Update' button and nonce is valid
	if ( ! isset( $_POST['monsterinsights-settings-submit'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['monsterinsights-settings-nonce'], 'monsterinsights-settings-nonce' ) ) {
		return;
	}

	if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
		return;
	}

	if ( empty( $_POST['monsterinsights_settings_tab'] ) || empty( $_POST['monsterinsights_settings_sub_tab'] ) || $_POST['monsterinsights_settings_tab'] !== 'tracking' ) {
		return;
	}

	// get subtab
	$settings = monsterinsights_get_registered_settings();
	$tab      = $_POST['monsterinsights_settings_sub_tab'];
	if ( empty( $settings ) || !is_array( $settings ) || empty( $settings[ $tab ] ) || ! is_array( $settings[ $tab ] ) ) {
		return;
	}

	// Okay we're good to sanitize, validate, and save this section's settings

	// We only care about this sections's settings
	$settings = $settings[ $tab ]; 

	// Run a general sanitization for the tab for special fields
	$input    = ! empty( $_POST['monsterinsights_settings'] ) ? $_POST['monsterinsights_settings'] : array();
	$input    = apply_filters( 'monsterinsights_settings_' . $tab . '_sanitize', $input );

	foreach( $settings as $id => $setting ) {

		// If the value wasn't passed in, set to false, which will delete the option
		$value          = isset( $input[ $id ] ) ? $input[ $id ] : false;
		$previous_value = monsterinsights_get_option( $id, false );

		// Sanitize/Validate
		if ( empty( $setting['type'] ) ) {
			continue;
		}

		// Some setting types are not actually settings, just keep moving along here
		$non_setting_types = monsterinsights_get_non_setting_types();
		$type              = $setting['type'];

		if ( in_array( $type, $non_setting_types ) ) {
			continue;
		}

		$args = wp_parse_args( $setting, array(
			'id'            => null,
			'desc'          => '',
			'name'          => '',
			'size'          => null,
			'options'       => '',
			'std'           => '',
			'min'           => null,
			'max'           => null,
			'step'          => null,
			'select2'        => null,
			'placeholder'   => null,
			'allow_blank'   => true,
			'readonly'      => false,
			'faux'          => false,
			'tooltip_title' => false,
			'tooltip_desc'  => false,
			'field_class'   => '',
			'multiple'      => false,
			'allowclear'    => true,
			'notice_type'   => 'info',
		) );

		// Sanitize settings
		$value = apply_filters( 'monsterinsights_settings_sanitize_' . $id  , $value, $id, $args, $previous_value );
		$value = apply_filters( 'monsterinsights_settings_sanitize_' . $type, $value, $id, $args, $previous_value );
		$value = apply_filters( 'monsterinsights_settings_sanitize'         , $value, $id, $args, $previous_value );

		// Save
		if ( ! has_action( 'monsterinsights_settings_save_' . $args['type'] ) ) {
			monsterinsights_update_option( $id, $value );
		} else {
			do_action( 'monsterinsights_settings_save_' . $args['type'], $value, $id, $args, $previous_value );
		}
	}
	add_action( 'monsterinsights_tracking_' . $tab . '_tab_notice', 'monsterinsights_updated_settings' );
}
add_action( 'current_screen', 'monsterinsights_save_settings' );

function monsterinsights_is_settings_tab( $tab = '' ){
	$tabs = monsterinsights_get_settings_tabs();
	if ( empty( $tab ) || empty( $tabs ) || ! is_string( $tab ) || ! is_array( $tabs ) ) {
		return false;
	}

	return !empty( $tabs[$tab]);
}

/**
 * Flattens the set of registered settings and their type so we can easily sanitize all the settings
 * in a much cleaner set of logic in monsterinsights_settings_sanitize
 *
 * @since  6.0.0
 * @return array Key is the setting ID, value is the type of setting it is registered as
 */
function monsterinsights_get_registered_settings_types( $section = '' ) {
	$settings      = monsterinsights_get_registered_settings();
	$setting_types = array();
	if ( ! empty( $section ) ) {
		if ( ! empty( $settings[$section] ) ) {
			foreach ( $settings[$section] as $setting ) {
				if ( is_array( $setting ) && array_key_exists( 'type', $setting ) ) {
					$setting_types[ $setting['id'] ] = $setting['type'];
				}
			}
		}
	} else {
		foreach ( $settings as $tab ) {
			foreach ( $tab as $setting ) {
				if ( is_array( $setting ) && array_key_exists( 'type', $setting ) ) {
					$setting_types[ $setting['id'] ] = $setting['type'];
				}
			}
		}
	}
	return $setting_types;
}

/**
 * Sanitize rich editor fields
 *
 * @since 6.0.0
 * @param array $input The field value
 * @return string $input Sanitizied value
 */
function monsterinsights_sanitize_rich_editor_field( $input ) {
	$tags = array(
		'p' => array(
			'class' => array(),
			'id'    => array(),
		),
		'span' => array(
			'class' => array(),
			'id'    => array(),
		),
		'a' => array(
			'href' => array(),
			'title' => array(),
			'class' => array(),
			'title' => array(),
			'id'    => array(),
		),
		'strong' => array(),
		'em' => array(),
		'br' => array(),
		'img' => array(
			'src'   => array(),
			'title' => array(),
			'alt'   => array(),
			'id'    => array(),
		),
		'div' => array(
			'class' => array(),
			'id'    => array(),
		),
		'ul' => array(
			'class' => array(),
			'id'    => array(),
		),
		'li' => array(
			'class' => array(),
			'id'    => array(),
		)
	);

	//$allowed_tags = apply_filters( 'monsterinsights_allowed_html_tags', $tags );

	return trim( wp_kses( $input, $allowed_tags ) );
}
add_filter( 'monsterinsights_settings_sanitize_rich_editor', 'monsterinsights_sanitize_rich_editor_field' );

if ( ! function_exists( 'sanitize_textarea_field' ) ) {
	function sanitize_textarea_field( $str ) {
		$filtered = _sanitize_text_fields( $str, true );
		return apply_filters( 'sanitize_textarea_field', $filtered, $str );
	}
}

if ( ! function_exists( 'sanitize_textarea_field' ) ) {
	function _sanitize_text_fields( $str, $keep_newlines = false ) {
		$filtered = wp_check_invalid_utf8( $str );
	 
		if ( strpos($filtered, '<') !== false ) {
			$filtered = wp_pre_kses_less_than( $filtered );
			// This will strip extra whitespace for us.
			$filtered = wp_strip_all_tags( $filtered, false );
	 
			// Use html entities in a special case to make sure no later
			// newline stripping stage could lead to a functional tag
			$filtered = str_replace("<\n", "&lt;\n", $filtered);
		}
	 
		if ( ! $keep_newlines ) {
			$filtered = preg_replace( '/[\r\n\t ]+/', ' ', $filtered );
		}
		$filtered = trim( $filtered );
	 
		$found = false;
		while ( preg_match('/%[a-f0-9]{2}/i', $filtered, $match) ) {
			$filtered = str_replace($match[0], '', $filtered);
			$found = true;
		}
	 
		if ( $found ) {
			// Strip out the whitespace that may now exist after removing the octets.
			$filtered = trim( preg_replace('/ +/', ' ', $filtered) );
		}
	 
		return $filtered;
	}
}

/**
 * Sanitize textarea fields
 *
 * @since 6.0.0
 * @todo  docbloc
 */
function monsterinsights_sanitize_textarea_field( $value, $id, $setting, $previous_value ) {
	return sanitize_textarea_field( $value );
}
add_filter( 'monsterinsights_settings_sanitize_textarea', 'monsterinsights_sanitize_textarea_field', 10, 4 );

/**
 * Sanitize checkbox fields
 *
 * @since 6.0.0
 * @todo  docbloc
 */
function monsterinsights_sanitize_checkbox_field( $value, $id, $setting, $previous_value ) {
	return (bool) $value;
}
add_filter( 'monsterinsights_settings_sanitize_checkbox', 'monsterinsights_sanitize_checkbox_field', 10, 4 );

/**
 * Sanitize multicheck fields
 *
 * @since 6.0.0
 * @todo  docbloc
 */
function monsterinsights_sanitize_multicheck_field( $value, $id, $setting, $previous_value ) {
	$save_value = array();
	if ( ! empty( $value ) && is_array( $value ) ) {
		foreach( $setting['options'] as $key => $option ){
			if ( in_array( $key, $value ) ) {
				$save_value[] = $key;
			}
		}
	}
	return $save_value;
}
add_filter( 'monsterinsights_settings_sanitize_multicheck', 'monsterinsights_sanitize_multicheck_field', 10, 4 );

/**
 * Sanitize select fields
 *
 * @since 6.0.0
 * @todo  docbloc
 */
function monsterinsights_sanitize_select_field( $value, $id, $setting, $previous_value ) {
	if ( ! empty( $setting['multiple'] ) && $setting['multiple'] ) {
		$save_value = array();
	} else {
		$save_value = '';
	}
	if ( ! empty( $value ) && is_array( $value ) ) {
		if ( $setting['multiple'] ) {
			foreach ( $value as $vid => $vname ) {
				foreach( $setting['options'] as $key => $option ){
					if ( $key === $vname ) {
						$save_value[] = $key;
						break;
					}
				}
			}
		} else {
			foreach( $setting['options'] as $key => $option ){
				if ( is_array( $value ) && in_array( $key, $value ) ) {
					$save_value = $key;
					break;
				} else if ( is_string( $value ) && $key === $value ){
					$save_value = $key;
					break;
				}
			}
		}
	}
	return $save_value;
}
add_filter( 'monsterinsights_settings_sanitize_select', 'monsterinsights_sanitize_select_field', 10, 4 );


/**
 * Sanitize radio fields
 *
 * @since 6.0.0
 * @todo  docbloc
 */
function monsterinsights_sanitize_radio_field( $value, $id, $setting, $previous_value ) {
	$save_value = '';
	if ( ! empty( $value ) ) {
		foreach( $setting['options'] as $key => $option ){
			if ( $key === $value ) {
				$save_value = $key;
			}
		}
	}
	return $save_value;
}
add_filter( 'monsterinsights_settings_sanitize_radio', 'monsterinsights_sanitize_radio_field', 10, 4 );

/**
 * Sanitize text fields
 *
 * @since 6.0.0
 * @todo  docbloc
 */
function monsterinsights_sanitize_text_field( $value, $id, $setting, $previous_value ) {
	return sanitize_text_field( $value );
}
add_filter( 'monsterinsights_settings_sanitize_text', 'monsterinsights_sanitize_text_field', 10, 4 );

/**
 * Sanitize password fields
 *
 * @since 6.0.0
 * @todo  docbloc
 */
function monsterinsights_sanitize_password_field( $value, $id, $setting, $previous_value ) {
	return sanitize_text_field( $value );
}
add_filter( 'monsterinsights_settings_sanitize_password', 'monsterinsights_sanitize_password_field', 10, 4 );

/**
 * Sanitize number fields
 *
 * @since 6.0.0
 * @todo  docbloc
 */
function monsterinsights_sanitize_number_field( $value, $id, $setting, $previous_value ) {
	if ( is_int( (int) $value ) ) {
		return $int;
	} else if ( is_int( $previous_value ) ) {
		return $previous_value;
	} else {
		return 0;
	}
}
add_filter( 'monsterinsights_settings_sanitize_number', 'monsterinsights_sanitize_number_field', 10, 4 );

/**
 * Sanitize unfiltered textarea fields
 *
 * @since 6.0.0
 * @todo  docbloc
 */
function monsterinsights_sanitize_unfiltered_textarea_field( $value, $id, $setting, $previous_value ) {
	if ( current_user_can( 'unfiltered_html' ) || current_user_can( 'monsterinsights_unfiltered_html' ) ) {
		return $value;
	} else {
		return $previous_value;
	}
}
add_filter( 'monsterinsights_settings_unfiltered_textarea_number', 'monsterinsights_sanitize_unfiltered_textarea_field', 10, 4 );

/**
 * Sanitizes a string key for MonsterInsights Settings
 *
 * Keys are used as internal identifiers. Alphanumeric characters, dashes, underscores, stops, colons and slashes are allowed
 *
 * @since  6.0.0
 * @param  string $key String key
 * @return string Sanitized key
 */
function monsterinsights_sanitize_key( $key ) {
	$raw_key = $key;
	$key = preg_replace( '/[^a-zA-Z0-9_\-\.\:\/]/', '', $key );
	/**
	 * Filter a sanitized key string.
	 *
	 * @since 6.0.0
	 * @param string $key     Sanitized key.
	 * @param string $raw_key The key prior to sanitization.
	 */
	return apply_filters( 'monsterinsights_sanitize_key', $key, $raw_key );
}

/**
 * Sanitize HTML Class Names
 *
 * @since 6.0.0
 * @param  string|array $class HTML Class Name(s)
 * @return string $class
 */
function monsterinsights_sanitize_html_class( $class = '' ) {

	if ( is_string( $class ) ) {
		$class = sanitize_html_class( $class );
	} else if ( is_array( $class ) ) {
		$class = array_values( array_map( 'sanitize_html_class', $class ) );
		$class = implode( ' ', array_unique( $class ) );
	}

	return $class;

}

/**
 * Retrieve a list of all published pages
 *
 * On large sites this can be expensive, so only load if on the settings page or $force is set to true
 *
 * @since 6.0.0
 * @param bool $force Force the pages to be loaded even if not on settings
 * @return array $pages_options An array of the pages
 */
function monsterinsights_get_pages( $force = false ) {
	$pages_options = array( '' => '' ); // Blank option
	if( ( ! isset( $_GET['page'] ) || 'monsterinsights_settings' != $_GET['page'] ) && ! $force ) {
		return $pages_options;
	}

	$pages = get_pages();
	if ( $pages ) {
		foreach ( $pages as $page ) {
			$pages_options[ $page->ID ] = $page->post_title;
		}
	}

	return $pages_options;
}

/**
 * Checkbox Callback
 *
 * Renders checkboxes.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 *
 * @return void
 */
function monsterinsights_checkbox_callback( $args ) {
	$monsterinsights_option = monsterinsights_get_option( $args['id'] );

	if ( isset( $args['faux'] ) && true === $args['faux'] ) {
		$name = '';
	} else {
		$name = 'name="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . ']"';
	}

	$class = monsterinsights_sanitize_html_class( $args['field_class'] );

	$checked  = ! empty( $monsterinsights_option ) ? checked( 1, $monsterinsights_option, false ) : '';

	$disabled = '';

	if ( isset( $args['faux'] ) && true === $args['faux'] ) {
		// Disable class
		$disabled = 'disabled="disabled"';
		
		// Checked
		$checked  = isset( $args['std'] ) && true === $args['std'] ? checked( 1, 1, false ) : '';
	}

	$html     = '<input type="checkbox" id="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . ']"' . $name . ' value="1" ' . $checked . ' class="' . $class . '" ' . $disabled . ' />';
	$html    .= '<p class="description">'  . wp_kses_post( $args['desc'] ) . '</p>';

	return apply_filters( 'monsterinsights_after_setting_output', $html, $args );
}

/**
 * Multicheck Callback
 *
 * Renders multiple checkboxes.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 *
 * @return void
 */
function monsterinsights_multicheck_callback( $args ) {
	$monsterinsights_option = monsterinsights_get_option( $args['id'] );

	$class = monsterinsights_sanitize_html_class( $args['field_class'] );

	$html = '';
	if ( ! empty( $args['options'] ) ) {
		foreach( $args['options'] as $key => $option ):
			if( isset( $monsterinsights_option[ $key ] ) ) { $enabled = $option; } else { $enabled = NULL; }
			$html .= '<input name="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . '][' . monsterinsights_sanitize_key( $key ) . ']" id="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . '][' . monsterinsights_sanitize_key( $key ) . ']" class="' . $class . '" type="checkbox" value="' . esc_attr( $option ) . '" ' . checked($option, $enabled, false) . '/>&nbsp;';
			$html .= '<label for="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . '][' . monsterinsights_sanitize_key( $key ) . ']">' . wp_kses_post( $option ) . '</label><br/>';
		endforeach;
		$html .= '<p class="description">' . $args['desc'] . '</p>';
	}

	return apply_filters( 'monsterinsights_after_setting_output', $html, $args );
}


/**
 * Radio Callback
 *
 * Renders radio boxes.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 *
 * @return void
 */
function monsterinsights_radio_callback( $args ) {
	$monsterinsights_options = monsterinsights_get_option( $args['id'] );

	$html = '';

	$class = monsterinsights_sanitize_html_class( $args['field_class'] );

	foreach ( $args['options'] as $key => $option ) :
		$checked = false;

		if ( $monsterinsights_options && $monsterinsights_options == $key ) {
			$checked = true;
		} else if( isset( $args['std'] ) && $args['std'] == $key && ! $monsterinsights_options ) {
			$checked = true;
		}

		$html .= '<label for="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . '][' . monsterinsights_sanitize_key( $key ) . ']">';
		$html .= '<input name="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . ']" id="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . '][' . monsterinsights_sanitize_key( $key ) . ']" class="' . $class . '" type="radio" value="' . monsterinsights_sanitize_key( $key ) . '" ' . checked(true, $checked, false) . '/>&nbsp;';
		$html .= esc_html( $option ) . '</label>';
	endforeach;

	$html .= '<p class="description">' . apply_filters( 'monsterinsights_after_setting_output', wp_kses_post( $args['desc'] ), $args ) . '</p>';

	return $html;
}

/**
 * Text Callback
 *
 * Renders text fields.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 *
 * @return void
 */
function monsterinsights_text_callback( $args ) {
	$monsterinsights_option = monsterinsights_get_option( $args['id'] );

	if ( $monsterinsights_option ) {
		$value = $monsterinsights_option;
	} elseif( ! empty( $args['allow_blank'] ) && empty( $monsterinsights_option ) ) {
		$value = '';
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	if ( isset( $args['faux'] ) && true === $args['faux'] ) {
		$args['readonly'] = true;
		$value = isset( $args['std'] ) ? $args['std'] : '';
		$name  = '';
	} else {
		$name = 'name="monsterinsights_settings[' . esc_attr( $args['id'] ) . ']"';
	}

	$class = monsterinsights_sanitize_html_class( $args['field_class'] );

	$disabled = ! empty( $args['disabled'] ) ? ' disabled="disabled"' : '';
	$readonly = $args['readonly'] === true ? ' readonly="readonly"' : '';
	$size     = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html     = '<input type="text" class="' . $class . ' ' . sanitize_html_class( $size ) . '-text" id="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . ']" ' . $name . ' value="' . esc_attr( stripslashes( $value ) ) . '"' . $readonly . $disabled . ' placeholder="' . esc_attr( $args['placeholder'] ) . '"/>';
	$html    .= '<p class="description"> '  . wp_kses_post( $args['desc'] ) . '</p>';

	return apply_filters( 'monsterinsights_after_setting_output', $html, $args );
}

/**
 * Number Callback
 *
 * Renders number fields.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 *
 * @return void
 */
function monsterinsights_number_callback( $args ) {
	$monsterinsights_option = monsterinsights_get_option( $args['id'] );

	if ( $monsterinsights_option ) {
		$value = $monsterinsights_option;
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	if ( isset( $args['faux'] ) && true === $args['faux'] ) {
		$args['readonly'] = true;
		$value = isset( $args['std'] ) ? $args['std'] : '';
		$name  = '';
	} else {
		$name = 'name="monsterinsights_settings[' . esc_attr( $args['id'] ) . ']"';
	}

	$class = monsterinsights_sanitize_html_class( $args['field_class'] );

	$max  = isset( $args['max'] )  ? $args['max']   : 999999;
	$min  = isset( $args['min'] )  ? $args['min']   : 0;
	$step = isset( $args['step'] ) ? $args['step'] : 1;

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="number" step="' . esc_attr( $step ) . '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . $class . ' ' . sanitize_html_class( $size ) . '-text" id="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . ']" ' . $name . ' value="' . esc_attr( stripslashes( $value ) ) . '"/>';
	$html .= '<p class="description"> '  . wp_kses_post( $args['desc'] ) . '</p>';

	return apply_filters( 'monsterinsights_after_setting_output', $html, $args );
}

/**
 * Textarea Callback
 *
 * Renders textarea fields.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 *
 * @return void
 */
function monsterinsights_textarea_callback( $args ) {
	$monsterinsights_option = monsterinsights_get_option( $args['id'] );

	if ( $monsterinsights_option ) {
		$value = $monsterinsights_option;
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	$class = monsterinsights_sanitize_html_class( $args['field_class'] );

	$html = '<textarea class="' . $class . ' large-text" cols="50" rows="5" id="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . ']" name="monsterinsights_settings[' . esc_attr( $args['id'] ) . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
	$html .= '<p class="description"> '  . wp_kses_post( $args['desc'] ) . '</p>';

	return apply_filters( 'monsterinsights_after_setting_output', $html, $args );
}


/**
 * Unfiltered Textarea Callback
 *
 * Renders unfiltered textarea fields.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 *
 * @return void
 */
function monsterinsights_unfiltered_textarea_callback( $args ) {
	
	if ( current_user_can( 'unfiltered_html' ) || current_user_can( 'monsterinsights_unfiltered_html' ) ) {
		$monsterinsights_option = monsterinsights_get_option( $args['id'] );

		if ( $monsterinsights_option ) {
			$value = $monsterinsights_option;
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$class = monsterinsights_sanitize_html_class( $args['field_class'] );

		$html = '<textarea class="' . $class . ' large-text" cols="50" rows="5" id="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . ']" name="monsterinsights_settings[' . esc_attr( $args['id'] ) . ']">' . stripslashes( $value ) . '</textarea>';
		$html .= '<p class="description"> '  . wp_kses_post( $args['desc'] ) . '</p>';
	} else {
		$html .= sprintf( esc_html__( 'You must have the %s capability to view/edit this setting', 'google-analytics-for-wordpress' ), '"unfiltered_html"' );
	}

	return apply_filters( 'monsterinsights_after_setting_output', $html, $args );
}

/**
 * Password Callback
 *
 * Renders password fields.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 *
 * @return void
 */
function monsterinsights_password_callback( $args ) {
	$monsterinsights_options = monsterinsights_get_option( $args['id'] );

	if ( $monsterinsights_options ) {
		$value = $monsterinsights_options;
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	$class = monsterinsights_sanitize_html_class( $args['field_class'] );

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="password" class="' . $class . ' ' . sanitize_html_class( $size ) . '-text" id="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . ']" name="monsterinsights_settings[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<p class="description"> ' . wp_kses_post( $args['desc'] ) . '</p>';

	return apply_filters( 'monsterinsights_after_setting_output', $html, $args );
}

/**
 * Select Callback
 *
 * Renders select fields.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 *
 * @return void
 */
function monsterinsights_select_callback($args) {
	$monsterinsights_option = monsterinsights_get_option( $args['id'] );

	if ( $monsterinsights_option ) {
		$value = $monsterinsights_option;
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	if ( isset( $args['placeholder'] ) ) {
		$placeholder = $args['placeholder'];
	} else {
		$placeholder = '';
	}

	$class = monsterinsights_sanitize_html_class( $args['field_class'] );

	if ( isset( $args['select2'] ) ) {
		$class .= ' monsterinsights-select300';
	}

	$allowclear   = isset( $args['allowclear'] ) ? (bool) $args['allowclear'] : false;
	$multiple     = isset( $args['multiple'] )   ? (bool) $args['multiple'] : false;
	$multiple     = $multiple ? 'multiple="multiple"' : '';
	$multiple_arg = $multiple ? '[]' : '';

	$html = '<select id="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . ']" name="monsterinsights_settings[' . esc_attr( $args['id'] ) . ']' . $multiple_arg .'" class="' . $class . '" data-placeholder="' . esc_html( $placeholder ) . '" data-allow-clear="' . $allowclear . '" ' . $multiple . ' />';

	foreach ( $args['options'] as $option => $name ) {
		$selected = ! empty( $value ) && is_array( $value ) ? in_array( $option, $value ) :  $value === $option;
		$selected = selected( true, $selected, false );
		$html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( $name ) . '</option>';
	}

	$html .= '</select>';
	$html .= '<p class="description"> ' . wp_kses_post( $args['desc'] ) . '</p>';

	return apply_filters( 'monsterinsights_after_setting_output', $html, $args );
}

/**
 * Rich Editor Callback
 *
 * Renders rich editor fields.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 */
function monsterinsights_rich_editor_callback( $args ) {
	$monsterinsights_option = monsterinsights_get_option( $args['id'] );

	if ( $monsterinsights_option ) {
		$value = $monsterinsights_option;
	} else {
		if( ! empty( $args['allow_blank'] ) && empty( $monsterinsights_option ) ) {
			$value = '';
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}
	}

	$rows = isset( $args['size'] ) ? $args['size'] : 20;

	$class = monsterinsights_sanitize_html_class( $args['field_class'] );

	ob_start();
	wp_editor( stripslashes( $value ), 'monsterinsights_settings_' . esc_attr( $args['id'] ), array( 'textarea_name' => 'monsterinsights_settings[' . esc_attr( $args['id'] ) . ']', 'textarea_rows' => absint( $rows ), 'editor_class' => $class ) );
	$html = ob_get_clean();

	$html .= '<br/><p class="description"> ' . wp_kses_post( $args['desc'] ) . '</p>';

	return apply_filters( 'monsterinsights_after_setting_output', $html, $args );
}

/**
 * Descriptive text callback.
 *
 * Renders descriptive text onto the settings field.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 * @return void
 */
function monsterinsights_descriptive_text_callback( $args ) {
	$html = wp_kses_post( $args['desc'] );
	return apply_filters( 'monsterinsights_after_setting_output', $html, $args );
}

/**
 * Notice Callback
 *
 * Renders notice fields.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 *
 * @return void
 */
function monsterinsights_notice_callback( $args ) {
	$html = monsterinsights_get_message( $args['notice_type'], $args['desc'] );
	return apply_filters( 'monsterinsights_after_setting_output', $html, $args );
}

/**
 * Upgrade Notice Callback
 *
 * Renders upgrade notice fields.
 *
 * @since 6.1.7
 * @param array $args Arguments passed by the setting
 *
 * @return void
 */
function monsterinsights_upgrade_notice_callback( $args ) {
	$html =   '<div class="monsterinsights-upsell-box"><h2>' . esc_html( $args['name' ] ) . '</h2>'
			. '<p class="monsterinsights-upsell-lite-text">' . $args['desc'] . '</p>'
			. '<p class="monsterinsights-upsell-button-par"><a href="https://www.monsterinsights.com/lite/" class="monsterinsights-upsell-box-button button button-primary">' . __( 'Click here to Upgrade', 'google-analytics-for-wordpress' ) . '</a></p>'
			. '</div>';
	return apply_filters( 'monsterinsights_after_setting_output', $html, $args ); 
}

/**
 * Hook Callback
 *
 * Adds a do_action() hook in place of the field
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 * @return void
 */
function monsterinsights_hook_callback( $args ) {
	do_action( 'monsterinsights_' . $args['id'], $args );
	return '';
}

/**
 * Missing Callback
 *
 * If a function is missing for settings callbacks alert the user.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 * @return void
 */
function monsterinsights_missing_callback($args) {
	return sprintf(
		__( 'The callback function used for the %s setting is missing.', 'google-analytics-for-wordpress' ),
		'<strong>' . $args['id'] . '</strong>'
	);
}

/**
 * Render Submit Button
 *
 * If there's a saveable field on the page, show save button.
 *
 * @since 6.0.0
 * @param array $args Arguments passed by the setting
 * @return void
 */
function monsterinsights_render_submit_field( $section, $page = 'tracking' ) {
	$html = '';
	$settings = monsterinsights_get_registered_settings();
	if ( is_array( $settings ) && ! empty( $settings[$section] ) && is_array( $settings[$section] ) ) {
		$non_setting_types = monsterinsights_get_non_setting_types();
		$submit_button     = false;
		foreach ( $settings[$section] as $setting ) {
			if ( ! empty( $non_setting_types ) && ! empty( $setting['type'] ) && ! in_array( $setting['type'], $non_setting_types ) ) {
				if ( empty( $setting['faux'] ) ) {
					$submit_button = true;
					break;
				}
			}
		}
		if ( $submit_button ) {
			$html .= '<input type="hidden" name="monsterinsights_settings_tab" value="' . esc_attr( $page ). '"/>';
			$html .= '<input type="hidden" name="monsterinsights_settings_sub_tab" value="' .  esc_attr( $section ) . '"/>';
			$html .= wp_nonce_field( 'monsterinsights-settings-nonce', 'monsterinsights-settings-nonce', true, false );
			$html .= get_submit_button( esc_html__( 'Save Changes', 'google-analytics-for-wordpress' ), 'primary', 'monsterinsights-settings-submit', false );
		}
		$html      = apply_filters( 'monsterinsights_html_after_submit_field', $html, $page, $section );
	}
	return $html;
}

/** 
 * @todo  docbloc
 */
function monsterinsights_render_field( $args ) {
	$output = '';
	$output .='<tr id="monsterinsights-input-' . monsterinsights_sanitize_key( $args['id'] ) .'">';
		if ( ! empty( $args['name'] ) && empty( $args['no_label'] ) ) {
			$output .= '<th scope="row">';
				$output .='<label for="monsterinsights_settings[' . monsterinsights_sanitize_key( $args['id'] ) . ']">' . esc_html( $args["name"] ) . '</label>';
			$output .= '</th>';
		}
		$output .= '<td>';
			$render  = ! empty( $args['type'] ) && function_exists( 'monsterinsights_' . $args['type'] . '_callback' ) ? 'monsterinsights_' . $args['type'] . '_callback' : 'monsterinsights_missing_callback';
			$output .= call_user_func( $render, $args );
		$output .= '</td>';
	$output .= '</tr>';
	return $output;
}

/** 
 * @todo  docbloc
 */
function monsterinsights_add_setting_tooltip( $html, $args ) { // @todo: enqueue tooltips

	if ( ! empty( $args['tooltip_title'] ) && ! empty( $args['tooltip_desc'] ) ) {
		$tooltip = '<span alt="f223" class="monsterinsights-help-tip dashicons dashicons-editor-help" title="<strong>' . $args['tooltip_title'] . '</strong>: ' . $args['tooltip_desc'] . '"></span>';
		$html .= $tooltip;
	}

	return $html;
}
add_filter( 'monsterinsights_after_setting_output', 'monsterinsights_add_setting_tooltip', 10, 2 );

/** 
 * @todo  docbloc
 */
function monsterinsights_get_settings_notices( $delete_on_retrieve = true ) {
	$notices = get_transient( 'monsterinsights_settings_notices' );
	if ( $delete_on_retrieve ) {
		delete_transient( 'monsterinsights_settings_notices' );
	}
	return $notices;
}

/** 
 * @todo  docbloc
 */
function monsterinsights_add_settings_notice( $name, $type = 'success', $message = '' ) {
	$notices = get_transient( 'monsterinsights_settings_notices' );
	if ( empty( $notices ) ) {
		$notices          = array();
		$notices[ $name ] = array( "type" => $type, "message" => $message );
	} else {
		$notices[ $name ] = array( "type" => $type, "message" => $message );
	}
	set_transient( 'monsterinsights_settings_notices', $notices );
}

/** 
 * @todo  docbloc
 */
function monsterinsights_remove_settings_notice( $name ) {
	$notices = get_transient( 'monsterinsights_settings_notices' );
	$found   = false;
	if ( ! empty( $notices ) ) {
		if ( isset( $notices[ $name] ) ) {
			unset( $notices[ $name] );
			set_transient( 'monsterinsights_settings_notices', $notices );
			$found = true;
		} else {
			set_transient( 'monsterinsights_settings_notices', $notices );
		}
	}
	return $found;
}

/** 
 * @todo  docbloc
 */
function monsterinsights_get_non_setting_types(){
	return apply_filters( 'monsterinsights_non_setting_types', array(  'descriptive_text', 'hook', 'upgrade_notice', 'install_notice', 'notice' ) );
}