<?php
/**
 * Full setup checklist functionality here.
 *
 * @package monsterinsights
 */

/**
 * Class MonsterInsights_Setup_Checklist
 */
class MonsterInsights_Setup_Checklist {

	/**
	 * MonsterInsights_Setup_Checklist constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_monsterinsights_vue_get_setup_checklist', array( $this, 'ajax_get_setup_checklist' ) );
		add_action( 'wp_ajax_monsterinsights_vue_setup_checklist_click_track', array(
			$this,
			'ajax_button_click_track'
		) );
	}

	/**
	 * Setup checklist admin ajax.
	 *
	 * @return void
	 */
	public function ajax_get_setup_checklist() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		$checklist = $this->get_checklist_data();

		wp_send_json( array(
			'checklist'          => $checklist,
			'expanded_step'      => $this->get_expanded_checklist_step( $checklist ),
			'ecommerce_provider' => $this->get_ecommerce_provider(),
			'milestone_left'     => $this->get_milestone_left(),
		) );
	}

	/**
	 * Default checklist value.
	 *
	 * @return array
	 */
	private function default_checklist() {
		return array(
			'step_1_install_monsterinsights' => true,
			'step_1_connect_monsterinsights' => array(
				'launch_setup_wizard' => false,
				'select_a_property'   => false,
				'ga_receiving_data'   => false,
			),
			'step_2_ecommerce_tracking'      => false,
			'step_2_google_search_console'   => false,
			'step_2_form_conversion'         => false,
			'step_2_visit_overview_report'   => false,
			'step_3_create_site_note'        => false,
			'step_4_install_userfeedback'    => false,
			'step_4_performance_addon'       => false,
			'step_4_custom_dimensions'       => false,
			'step_5_check_out_growth_tools'  => false,
			'step_5_embed_popular_posts'     => false,
			'step_5_install_aioseo'          => false,
			'step_5_install_optinmonster'    => false,
			'settings'                       => array( 'dismiss' => false ),
		);
	}

	/**
	 * Get ecommerce provider name.
	 *
	 * @return string
	 */
	private function get_ecommerce_provider() {
		if ( class_exists( 'WooCommerce' ) ) {
			return 'WooCommerce';
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			return 'Easy Digital Downloads';
		}

		if ( defined( 'MEPR_VERSION' ) ) {
			return 'MemberPress';
		}

		if ( function_exists( 'LLMS' ) ) {
			return 'LifterLMS';
		}

		if ( function_exists( 'Give' ) ) {
			return 'GiveWP';
		}

		return '';
	}

	/**
	 * Save user has clicked any button.
	 */
	public function ajax_button_click_track() {
		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! isset( $_POST['button_key'] ) ) {
			wp_send_json_error();
		}

		$button_key = sanitize_text_field( wp_unslash( $_POST['button_key'] ) );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		$default_checklist = $this->default_checklist();

		$checklist = get_option( 'monsterinsights_setup_checklist', array() );

		if ( ! $checklist ) {
			$checklist = $default_checklist;
		}

		switch ( $button_key ) {
			case 'settings_dismiss':
				$checklist['settings']['dismiss'] = true;
				break;

			default:
				$checklist[ $button_key ] = true;
				break;
		}

		update_option( 'monsterinsights_setup_checklist', $checklist );

		wp_send_json_success();
	}

	/**
	 * Setup checklist menu count.
	 *
	 * @return string
	 */
	public function get_menu_count() {
		$milestone_left = $this->get_milestone_left();

		if ( $milestone_left > 0 ) {
			return '<span class="monsterinsights-menu-notification-indicator update-plugins monsterinsights-setup-checklist-menu-indicator">' . $milestone_left . '</span>';
		}

		return '';
	}

	/**
	 * Get how many milestone left.
	 *
	 * @return int
	 */
	private function get_milestone_left() {
		$checklist = $this->get_checklist_data();

		$left = 0;

		foreach ( $checklist as $key => $checklist_item ) {
			if ( 'settings' === $key ) {
				continue;
			}

			if ( is_array( $checklist_item ) ) {
				foreach ( $checklist_item as $sub_item ) {
					if ( ! $sub_item ) {
						$left ++;
					}
				}

				continue;
			}

			if ( ! $checklist_item ) {
				$left ++;
			}
		}

		return $left;
	}

	/**
	 * Get checklist array.
	 *
	 * @return array
	 */
	private function get_checklist_data() {

		$default_checklist = $this->default_checklist();

		$checklist = get_option( 'monsterinsights_setup_checklist', array() );

		if ( ! is_array( $checklist ) ) {
			$checklist = array();
		}

		$checklist = array_merge( $default_checklist, $checklist );

		if ( monsterinsights_get_v4_id_to_output() ) {
			$checklist['step_1_connect_monsterinsights']['launch_setup_wizard'] = true;
			$checklist['step_1_connect_monsterinsights']['select_a_property']   = true;
			$checklist['step_1_connect_monsterinsights']['ga_receiving_data']   = true;
		}

		if ( class_exists( 'MonsterInsights_Forms' ) ) {
			$checklist['step_2_form_conversion'] = true;
		}

		$notes_count = wp_count_posts( 'monsterinsights_note' );

		if ( $notes_count && isset( $notes_count->publish ) ) {
			$checklist['step_3_create_site_note'] = ! ! $notes_count->publish;
		}

		if ( defined( 'USERFEEDBACK_VERSION' ) ) {
			$checklist['step_4_install_userfeedback'] = true;
		}

		if ( class_exists( 'MonsterInsights_Performance' ) ) {
			$checklist['step_4_performance_addon'] = true;
		}

		if ( class_exists( 'MonsterInsights_Dimensions' ) ) {
			$checklist['step_4_custom_dimensions'] = true;
		}

		if ( function_exists( 'aioseo' ) ) {
			$checklist['step_5_install_aioseo'] = true;
		}

		if ( class_exists( 'OMAPI' ) ) {
			$checklist['step_5_install_optinmonster'] = true;
		}

		return $checklist;
	}

	/**
	 * Check Setup checklist is dismissed.
	 *
	 * @return bool
	 */
	public function is_dismissed() {
		// Get plugin installed information.
		$over_time = get_option( 'monsterinsights_over_time', array() );

		if ( ! isset( $over_time['installed_date'] ) ) {
			return false;
		}

		// Timestamp 1692662400 is for 22 Aug 2023.
		if ( $over_time['installed_date'] < 1692662400 ) {
			// If plugin has installed before 22 Aug 2023, then we don't need to show setup checklist.
			return true;
		}

		$checklist = get_option( 'monsterinsights_setup_checklist', array() );

		if ( ! $checklist ) {
			return false;
		}

		// User dismissed by clicking on the button.
		if ( isset( $checklist['settings'] ) && isset( $checklist['settings']['dismiss'] ) ) {
			return (bool) $checklist['settings']['dismiss'];
		}

		return false;
	}

	/**
	 * Find out which step we should expand.
	 *
	 * @param array $checklist Data of full checklist.
	 *
	 * @return string
	 */
	private function get_expanded_checklist_step( $checklist ) {
		foreach ( $checklist as $key => $checklist_item ) {
			if ( 'settings' === $key ) {
				continue;
			}

			$done = true;

			if ( is_array( $checklist_item ) ) {
				foreach ( $checklist_item as $sub_item ) {
					if ( ! $sub_item ) {
						$done = $sub_item;
						break;
					}
				}
			} else {
				$done = $checklist_item;
			}

			if ( ! $done ) {
				return substr( $key, 0, 6 );
			}
		}

		return 'step_6';
	}

}
