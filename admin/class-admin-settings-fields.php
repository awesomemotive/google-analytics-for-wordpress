<?php

class Yoast_GA_Admin_Settings_Fields {

	public static $options = array();

	/**
	 * Render a text field
	 *
	 * @param $args
	 */
	public static function yst_ga_text_field( $args ) {
		self::set_options();

		if ( ! isset( self::$options[$args['key']] ) ) {
			self::$options[$args['key']] = '';
		}

		if ( isset( $args['help'] ) ) {
			echo self::show_help( $args['key'], $args['help'] );
		}

		echo '<input type="text" name="yst_ga[ga_general][' . $args['key'] . ']" value="' . self::$options[$args['key']] . '" class="ga-form-text">';
	}

	/**
	 * Render a text field
	 *
	 * @param $args
	 */
	public static function yst_ga_textarea_field( $args ) {
		self::set_options();

		if ( ! isset( self::$options[$args['key']] ) ) {
			self::$options[$args['key']] = '';
		}

		if ( isset( $args['help'] ) ) {
			echo self::show_help( $args['key'], $args['help'] );
		}

		echo '<textarea name="yst_ga[ga_general][' . $args['key'] . ']" rows="5" cols="60">' . self::$options[$args['key']] . '</textarea>';
	}

	/**
	 * Render a text field
	 *
	 * @param $args
	 */
	public static function yst_ga_checkbox_field( $args ) {
		self::set_options();

		if ( ! isset( self::$options[$args['key']] ) ) {
			self::$options[$args['key']] = '';
		}

		if ( isset( $args['help'] ) ) {
			echo self::show_help( $args['key'], $args['help'] );
		}

		echo '<input type="checkbox" name="yst_ga[ga_general][' . $args['key'] . ']" value="1" ' . checked( self::$options[$args['key']], 1, false ) . '>';
	}

	/**
	 * Render a select field
	 *
	 * @param $args
	 */
	public static function yst_ga_select_field( $args ) {
		self::set_options();

		$options    = null;
		$class      = null;
		$attributes = null;

		if ( isset( $args['class'] ) ) {
			$class = ' class="' . $args['class'] . '"';
		}

		if ( isset( $args['attributes'] ) ) {
			$attributes = $args['attributes'];
		}

		foreach ( $args['options'] as $option ) {
			if ( is_array( self::$options[$args['key']] ) ) {
				if ( in_array( $option['id'], self::$options[$args['key']] ) ) {
					$options .= '<option value="' . $option['id'] . '" selected="selected">' . $option['name'] . '</option>';
					continue;
				}

				$options .= '<option value="' . $option['id'] . '">' . $option['name'] . '</option>';
				continue;
			}

			$options .= '<option value="' . $option['id'] . '" ' . selected( $option['id'], self::$options[$args['key']], false ) . '>' . $option['name'] . '</option>';
		}

		echo self::show_help( 'id-' . $args['key'], $args['help'] ) . '<select name="yst_ga[ga_general][' . $args['key'] . ']"' . $class . $attributes . '>' . $options . '</select>';
	}


	/**
	 * Cache the options in this class, so check if they're set
	 */
	private static function set_options() {
		if ( self::$options == array() ) {
			$options       = get_option( 'yst_ga' );
			self::$options = $options['ga_general'];
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
			return;
		}

		$help = '<img src="' . plugins_url( 'assets/img/question-mark.png', GAWP_FILE ) . '" class="alignleft yoast_help" id="' . esc_attr( $id . 'help' ) . '" alt="' . esc_attr( $description ) . '" />';

		return $help;
	}

}