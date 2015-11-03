<?php
/**
 * @package GoogleAnalytics\Admin
 */

/**
 * This class is for the backend, extendable for all child classes
 */
class Yoast_GA_Admin_Menu {

	/**
	 * @var object $target_object The property used for storing target object (class admin)
	 */
	private $target_object;

	/**
	 * @var boolean $dashboard_disabled The dashboards disabled bool
	 */
	private $dashboards_disabled;

	/**
	 * The parent slug for the submenu items based on if the dashboards are disabled or not.
	 *
	 * @var string
	 */
	private $parent_slug;

	/**
	 * Setting the target_object and adding actions
	 *
	 * @param object $target_object
	 */
	public function __construct( $target_object ) {

		$this->target_object = $target_object;

		add_action( 'admin_menu', array( $this, 'create_admin_menu' ), 10 );

		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		if ( is_plugin_active_for_network( GAWP_PATH ) ) {
			add_action( 'network_admin_menu', array( $this, 'create_admin_menu' ), 5 );
		}

		$this->dashboards_disabled = Yoast_GA_Settings::get_instance()->dashboards_disabled();
		$this->parent_slug         = ( ( $this->dashboards_disabled ) ? 'yst_ga_settings' : 'yst_ga_dashboard' );
	}

	/**
	 * Create the admin menu
	 */
	public function create_admin_menu() {
		/**
		 * Filter: 'wpga_menu_on_top' - Allows filtering of menu location of the GA plugin, if false is returned, it moves to bottom.
		 *
		 * @api book unsigned
		 */

		// Base 64 encoded SVG image.
		$icon_svg = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbDpzcGFjZT0icHJlc2VydmUiIGZpbGw9Im5vbmUiIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48Zz48Zz48Zz48Zz48cGF0aCBzdHlsZT0iZmlsbDojMDAwIiBkPSJNMjAzLjYsMzk1YzYuOC0xNy40LDYuOC0zNi42LDAtNTRsLTc5LjQtMjA0aDcwLjlsNDcuNywxNDkuNGw3NC44LTIwNy42SDExNi40Yy00MS44LDAtNzYsMzQuMi03Niw3NlYzNTdjMCw0MS44LDM0LjIsNzYsNzYsNzZIMTczQzE4OSw0MjQuMSwxOTcuNiw0MTAuMywyMDMuNiwzOTV6Ii8+PC9nPjxnPjxwYXRoIHN0eWxlPSJmaWxsOiMwMDAiIGQ9Ik00NzEuNiwxNTQuOGMwLTQxLjgtMzQuMi03Ni03Ni03NmgtM0wyODUuNywzNjVjLTkuNiwyNi43LTE5LjQsNDkuMy0zMC4zLDY4aDIxNi4yVjE1NC44eiIvPjwvZz48L2c+PHBhdGggc3R5bGU9ImZpbGw6IzAwMCIgc3Ryb2tlLXdpZHRoPSIyLjk3NCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBkPSJNMzM4LDEuM2wtOTMuMywyNTkuMWwtNDIuMS0xMzEuOWgtODkuMWw4My44LDIxNS4yYzYsMTUuNSw2LDMyLjUsMCw0OGMtNy40LDE5LTE5LDM3LjMtNTMsNDEuOWwtNy4yLDF2NzZoOC4zYzgxLjcsMCwxMTguOS01Ny4yLDE0OS42LTE0Mi45TDQzMS42LDEuM0gzMzh6IE0yNzkuNCwzNjJjLTMyLjksOTItNjcuNiwxMjguNy0xMjUuNywxMzEuOHYtNDVjMzcuNS03LjUsNTEuMy0zMSw1OS4xLTUxLjFjNy41LTE5LjMsNy41LTQwLjcsMC02MGwtNzUtMTkyLjdoNTIuOGw1My4zLDE2Ni44bDEwNS45LTI5NGg1OC4xTDI3OS40LDM2MnoiLz48L2c+PC9nPjwvc3ZnPg==';

		$menu_name = is_network_admin() ? 'extensions' : 'dashboard';

		if ( $this->dashboards_disabled ) {
			$menu_name = 'settings';
		}

		// Add main page
		add_menu_page(
			__( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . __( 'General settings', 'google-analytics-for-wordpress' ), __( 'Analytics', 'google-analytics-for-wordpress' ), 'manage_options', 'yst_ga_' . $menu_name,
			array(
				$this->target_object,
				'load_page',
			),
			$icon_svg,
			$this->get_menu_position()
		);

		$this->add_submenu_pages();
	}

	/**
	 * Get the menu position of the Analytics item
	 *
	 * @return string
	 */
	private function get_menu_position() {
		$on_top = apply_filters( 'wpga_menu_on_top', true );

		if ( $on_top ) {
			$position = $this->get_menu_position_value( 'top' );

		}
		else {
			$position = $this->get_menu_position_value( 'bottom' );
		}

		// If the dashboards are disabled, force the menu item to stay at the bottom of the menu
		if ( $this->dashboards_disabled ) {
			$position = $this->get_menu_position_value( 'bottom' );
		}

		return $position;
	}

	/**
	 * Get the top or bottom menu location number
	 *
	 * @param string $location
	 *
	 * @return string
	 */
	private function get_menu_position_value( $location ) {
		if ( $location == 'top' ) {
			return '2.00013467543';
		}

		return '100.00013467543';
	}

	/**
	 * Prepares an array that can be used to add a submenu page to the Google Analytics for Wordpress menu
	 *
	 * @param string $submenu_name
	 * @param string $submenu_slug
	 * @param string $font_color
	 *
	 * @return array
	 */
	private function prepare_submenu_page( $submenu_name, $submenu_slug, $font_color = '' ) {
		return array(
			'parent_slug'      => $this->parent_slug,
			'page_title'       => __( 'Yoast Google Analytics:', 'google-analytics-for-wordpress' ) . ' ' . $submenu_name,
			'menu_title'       => $this->parse_menu_title( $submenu_name, $font_color ),
			'capability'       => 'manage_options',
			'menu_slug'        => 'yst_ga_' . $submenu_slug,
			'submenu_function' => array( $this->target_object, 'load_page' ),
		);
	}

	/**
	 * Parsing the menutitle
	 *
	 * @param string $menu_title
	 * @param string $font_color
	 *
	 * @return string
	 */
	private function parse_menu_title( $menu_title, $font_color ) {
		if ( ! empty( $font_color ) ) {
			$menu_title = '<span style="color:' . $font_color . '">' . $menu_title . '</span>';
		}

		return $menu_title;
	}

	/**
	 * Adds a submenu page to the Google Analytics for WordPress menu
	 *
	 * @param array $submenu_page
	 */
	private function add_submenu_page( $submenu_page ) {
		$page         = add_submenu_page( $submenu_page['parent_slug'], $submenu_page['page_title'], $submenu_page['menu_title'], $submenu_page['capability'], $submenu_page['menu_slug'], $submenu_page['submenu_function'] );
		$is_dashboard = ( 'yst_ga_dashboard' === $submenu_page['menu_slug'] );
		$this->add_assets( $page, $is_dashboard );
	}

	/**
	 * Adding stylesheets and based on $is_not_dashboard maybe some more styles and scripts.
	 *
	 * @param string  $page
	 * @param boolean $is_dashboard
	 */
	private function add_assets( $page, $is_dashboard ) {
		add_action( 'admin_print_styles-' . $page, array( 'Yoast_GA_Admin_Assets', 'enqueue_styles' ) );
		add_action( 'admin_print_styles-' . $page, array( 'Yoast_GA_Admin_Assets', 'enqueue_settings_styles' ) );
		add_action( 'admin_print_scripts-' . $page, array( 'Yoast_GA_Admin_Assets', 'enqueue_scripts' ) );
		if ( ! $is_dashboard && filter_input( INPUT_GET, 'page' ) === 'yst_ga_dashboard' ) {
			Yoast_GA_Admin_Assets::enqueue_dashboard_assets();
		}
	}

	/**
	 * Prepares and adds submenu pages to the Google Analytics for Wordpress menu:
	 * - Dashboard
	 * - Settings
	 * - Extensions
	 *
	 * @return void
	 */
	private function add_submenu_pages() {
		foreach ( $this->get_submenu_types() as $submenu ) {
			if ( isset( $submenu['color'] ) ) {
				$submenu_page = $this->prepare_submenu_page( $submenu['label'], $submenu['slug'], $submenu['color'] );
			}
			else {
				$submenu_page = $this->prepare_submenu_page( $submenu['label'], $submenu['slug'] );
			}
			$this->add_submenu_page( $submenu_page );
		}
	}

	/**
	 * Determine which submenu types should be added as a submenu page.
	 *
	 * Dashboard can be disables by user
	 *
	 * Dashboard and settings are disables in network admin
	 *
	 * @return array
	 */
	private function get_submenu_types() {
		/**
		 * Array structure:
		 *
		 * array(
		 *   $submenu_name => array(
		 *        'color' => $font_color,
		 *        'label' => __( 'text-label', 'google-analytics-for-wordpress' ),
		 * 		  'slug'  => $menu_slug,
		 *        ),
		 *   ..,
		 * )
		 *
		 * $font_color can be left empty.
		 *
		 */
		$submenu_types = array();

		if ( ! is_network_admin() ) {

			if ( ! $this->dashboards_disabled ) {
				$submenu_types['dashboard'] = array(
					'label' => __( 'Dashboard', 'google-analytics-for-wordpress' ),
					'slug'  => 'dashboard',
				);
			}

			$submenu_types['settings'] = array(
				'label' => __( 'Settings', 'google-analytics-for-wordpress' ),
				'slug'  => 'settings',
			);
		}

		$submenu_types['extensions'] = array(
			'color' => '#f18500',
			'label' => __( 'Extensions', 'google-analytics-for-wordpress' ),
			'slug'  => 'extensions',
		);

		return $submenu_types;
	}
}
