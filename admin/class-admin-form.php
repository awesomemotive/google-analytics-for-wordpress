<?php

/**
 * This class is for the backend
 */
if ( ! class_exists( 'Yoast_GA_Admin_Form' ) ) {

	class Yoast_GA_Admin_Form {

		/**
		 * Show a question mark with help
		 *
		 * @param string $id
		 * @param string $description
		 *
		 * @return string
		 */
		public static function show_help( $id, $description ) {
			$help = '<img src="' . plugins_url( 'assets/img/question-mark.png', GAWP_FILE ) . '" class="alignleft yoast_help" id="' . esc_attr( $id . 'help' ) . '" alt="' . esc_attr( $description ) . '" />';

			return $help;
		}

	}

}