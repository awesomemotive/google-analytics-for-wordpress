<?php
/**
 * @package GoogleAnalytics\AdminSettingsFields
 */

/**
 * Class Yoast_GA_Admin_Settings_Fields
 */
class Yoast_GA_Admin_Settings_Fields {

	/**
	 * @var array
	 */
	private static $options = array();

	/**
	 * Render a text field
	 *
	 * @param array $args Add arguments for the text field
	 */
	public static function yst_ga_text_field( $args ) {
		self::set_options();
		self::before_input( $args );

		echo '<input type="text" name="yst_ga[ga_general][' . $args['key'] . ']" id="' . $args['label_for'] . '" value="' . esc_attr( self::$options[ $args['key'] ] ) . '" class="ga-form-text">';
	}

	/**
	 * Render a text field
	 *
	 * @param array $args Add arguments for the text field
	 */
	public static function yst_ga_textarea_field( $args ) {
		self::set_options();
		self::before_input( $args );

		$value = self::$options[ $args['key'] ];
		if ( $args['key'] !== 'custom_code' ) {
			$value = esc_attr( $value );
		}

		echo '<textarea name="yst_ga[ga_general][' . $args['key'] . ']" id="' . $args['label_for'] . '" rows="5" cols="60">' . $value . '</textarea>';
	}

	/**
	 * Render a text field
	 *
	 * @param array $args Add arguments for the text field
	 */
	public static function yst_ga_checkbox_field( $args ) {
		self::set_options();
		self::before_input( $args );

		echo '<input type="checkbox" name="yst_ga[ga_general][' . $args['key'] . ']" id="' . $args['label_for'] . '" value="1" ' . checked( self::$options[ $args['key'] ], 1, false ) . '>';
	}

	/**
	 * Render a select field
	 *
	 * @param array $args Add arguments for the text field
	 */
	public static function yst_ga_select_field( $args ) {
		self::set_options();

		$options       = null;
		$class         = null;
		$name_addition = null;

		if ( isset( $args['class'] ) ) {
			$class = ' class="' . $args['class'] . '"';
		}

		if ( ! isset( $args['attributes'] ) ) {
			$args['attributes'] = null;
		}

		if ( $args['key'] === 'ignore_users' ) {
			$name_addition = '[]';
		}

		foreach ( $args['options'] as $option ) {
			if ( is_array( self::$options[ $args['key'] ] ) ) {
				if ( in_array( $option['id'], self::$options[ $args['key'] ] ) ) {
					$options .= '<option value="' . esc_attr( $option['id'] ) . '" selected="selected">' . esc_attr( $option['name'] ) . '</option>';
					continue;
				}

				$options .= '<option value="' . esc_attr( $option['id'] ) . '">' . esc_attr( $option['name'] ) . '</option>';
				continue;
			}

			$options .= '<option value="' . esc_attr( $option['id'] ) . '" ' . selected( $option['id'], self::$options[ $args['key'] ], false ) . '>' . esc_attr( $option['name'] ) . '</option>';
		}

		echo self::show_help( 'id-' . $args['key'], $args['help'] ) . '<select id="' . $args['label_for'] . '" name="yst_ga[ga_general][' . $args['key'] . ']' . $name_addition . '"' . $class . $args['attributes'] . '>' . $options . '</select>';
	}

	/**
	 * Render a select field
	 *
	 * @param array $args Add arguments for the text field
	 */
	public static function yst_ga_select_profile_field( $args ) {
		self::set_options();

		$options    = null;
		$class      = null;

		if ( isset( $args['class'] ) ) {
			$class = ' class="' . $args['class'] . '"';
		}

		if ( ! isset( $args['attributes'] ) ) {
			$args['attributes'] = null;
		}

		foreach ( $args['options'] as $option ) {
			foreach ( $option['items'] as $optgroup ) {
				$options .= '<optgroup label="' . esc_attr( $optgroup['name'] ) . '">';

				foreach ( $optgroup['items'] as $item ) {
					$options .= '<option value="' . esc_attr( $item['id'] ) . '" ' . selected( $item['id'], self::$options[ $args['key'] ], false ) . '>' . esc_attr( $item['name'] ) . '</option>';
				}

				$options .= '</optgroup>';
			}
		}

		echo self::show_help( 'id-' . $args['key'], $args['help'] ) . '<select id="' . $args['label_for'] . '" name="yst_ga[ga_general][' . $args['key'] . ']"' . $class . $args['attributes'] . ' data-placeholder="' . __( 'Select a profile', 'google-analytics-for-wordpress' ) . '" ><option></option>' . $options . '</select>';
	}


	/**
	 * Cache the options in this class, so check if they're set
	 */
	private static function set_options() {
		if ( self::$options === array() ) {
			$options = get_option( 'yst_ga' );

			if ( ! isset( $options['ga_general'] ) ) {
				self::$options = Yoast_GA_Options::instance()->default_ga_values();
			}
			else {
				self::$options = $options['ga_general'];
			}

			unset( self::$options['ga_general'] );
		}
	}

	/**
	 * Show a question mark with help
	 *
	 * @param string $id
	 * @param string $description
	 *
	 * @return string
	 */
	private static function show_help( $id, $description ) {
		if ( is_null( $description ) ) {
			return '';
		}

		$help = '<img src="' . plugins_url( 'assets/img/question-mark.png', GAWP_FILE ) . '" class="alignleft yoast_help" id="' . esc_attr( $id . 'help' ) . '" alt="' . esc_attr( $description ) . '" />';

		return $help;
	}

	/**
	 * Render the help button and set the option value
	 *
	 * @param array $args Arguments for the input
	 */
	private static function before_input( $args ) {
		if ( ! isset( self::$options[ $args['key'] ] ) ) {
			self::$options[ $args['key'] ] = '';
		}

		if ( isset( $args['help'] ) ) {
			echo self::show_help( $args['key'], $args['help'] );
		}
	}

}