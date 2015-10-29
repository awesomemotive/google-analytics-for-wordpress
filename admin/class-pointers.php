<?php
/**
 * @package WPSEO\Admin
 */

/**
 * This class handles the pointers used in the introduction tour.
 */
class Yoast_GA_Pointers {

	/**
	 * @var Yoast_GA_Pointers
	 */
	public static $instance;

	/**
	 * @var array Holds the buttons to be put out
	 */
	private $button_array = array();

	/**
	 * @var array Holds the options such as content and position.
	 */
	private $options_array = array();

	/**
	 * @var string Holds the current styling selector.
	 */
	private $selector;

	/**
	 * @var array Holds the admin pages we have pointers for and the callback that generates the pointers content
	 */
	private $admin_pages = array(
		'yst_ga_settings'   => 'settings_pointer',
		'yst_ga_dashboard'  => 'dashboard_pointer',
		'yst_ga_extensions' => 'extensions_pointer',
	);

	/**
	 * Class constructor.
	 */
	public function __construct() {
		if ( current_user_can( 'manage_options' ) && ! get_user_meta( get_current_user_id(), 'ga_ignore_tour' ) ) {

			Yoast_GA_Admin_Assets::load_tour_assets( $this->localize_script() );
		}
	}

	/**
	 * Determine whether to call prepare_page_pointer or prepare_tour_pointer and return all needed information in an array.
	 *
	 * @return array
	 */
	protected function localize_script() {
		$this->prepare_pointer();

		$button_array_defaults = array(
			'primary_button'    => array(
				'text'          => false,
				'function'      => '',
			),
			'previous_button'   => array(
				'text'          => false,
				'function'      => '',
			),
		);

		$this->button_array = wp_parse_args( $this->button_array, $button_array_defaults );

		return array(
			'selector'          => $this->selector,
			'ignore_url'        => $this->get_ignore_url(),
			'options'           => $this->options_array,
			'buttons'           => $this->button_array,
			'close_button_text' => __( 'Close', 'google-analytics-for-wordpress' ),
		);
	}

	/**
	 * Check if we need to run the tour or the page pointer.
	 */
	protected function prepare_pointer() {
		global $pagenow;

		$page = $this->get_current_page();

		if ( $pagenow === 'admin.php' && array_key_exists( $page, $this->admin_pages ) ) {
			$this->prepare_page_pointer( $page );
		}
		else {
			$this->prepare_tour_pointer();
		}
	}

	/**
	 * Get the singleton instance of this class
	 *
	 * @return Yoast_GA_Pointers
	 */
	public static function get_instance() {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get the current page the user is on.
	 *
	 * @return string
	 */
	protected function get_current_page() {
		return filter_input( INPUT_GET, 'page' );
	}

	/**
	 * Shows a pointer on the proper pages
	 *
	 * @param string $page
	 */
	protected function prepare_page_pointer( $page ) {
		$method = $this->admin_pages[ $page ];

		$pointer = $this->$method();

		$this->selector = '#yoast_ga_title';

		$this->options_array = array(
			'content'      => $pointer['content'],
			'position'     => array(
				'edge'  => 'top',
				'align' => ( is_rtl() ) ? 'left' : 'right',
			),
			'pointerWidth' => 450,
		);

		if ( isset( $pointer['next_page'] ) ) {
			$this->add_button( 'primary_button', __( 'Next', 'google-analytics-for-wordpress' ), admin_url( 'admin.php?page=yst_ga_' . $pointer['next_page'] ) );

		}
		if ( isset( $pointer['prev_page'] ) ) {
			$this->add_button( 'previous_button', __( 'Previous', 'google-analytics-for-wordpress' ), admin_url( 'admin.php?page=yst_ga_' . $pointer['prev_page'] ) );
		}
	}

	/**
	 * Create the button to navigate the tour.
	 *
	 * @param string $key
	 * @param string $text
	 * @param string $location
	 *
	 * @return array
	 */
	protected function add_button( $key, $text, $location ) {
		return $this->button_array[ $key ] = array(
			'text' => $text,
			'location' => $location,
		);
	}

	/**
	 * Show a pointer that starts the tour for WordPress SEO
	 */
	protected function prepare_tour_pointer() {
		$content  = '<h3>' . __( 'Congratulations!', 'google-analytics-for-wordpress' ) . '</h3>'
		            . '<p>' . __( 'You\'ve just installed Google Analytics by Yoast! Click "Start tour" to view a quick introduction of this plugin\'s core functionality.', 'google-analytics-for-wordpress' ) . '</p>';

		$this->selector = 'li#toplevel_page_yst_ga_dashboard';

		$this->options_array  = array(
			'content'  => $content,
			'position' => array(
				'edge' => 'top',
				'align' => 'center',
			),
		);

		$this->add_button( 'primary_button', __( 'Start tour', 'google-analytics-for-wordpress' ), admin_url( 'admin.php?page=yst_ga_settings' ) );
	}

	/**
	 * Extending the current page URL with two params to be able to ignore the tour.
	 *
	 * @return mixed
	 */
	protected function get_ignore_url() {
		$ignore_tour_parameters = array(
			'ga_restart_tour' => false,
			'ga_ignore_tour'  => '1',
			'nonce'           => wp_create_nonce( 'ga-ignore-tour' ),
		);

		return esc_url( add_query_arg( $ignore_tour_parameters ) );
	}

	/**
	 * Returns the content of the settings pointer
	 *
	 * @return array
	 */
	private function settings_pointer() {
		global $current_user;
		return array(
			'content'   => '<h3>' . __( 'Settings', 'google-analytics-for-wordpress' ) . '</h3>'
			               . '<p><strong>' . __( 'Tab: General', 'google-analytics-for-wordpress' ) . '</strong></p>'
			               /* translators: %s is the product name 'Google Analytics by Yoast' */
			               . '<p>' . sprintf( __( 'These are the general settings for %s. Here you can authenticate and connect your Google Analytics profile, enable general tracking features and restart this tour.', 'google-analytics-for-wordpress' ), 'Google Analytics by Yoast' )  . '</p>'
			               . '<p><strong>' . __( 'Tab: Universal', 'google-analytics-for-wordpress' ) . '</strong></p>'
			               . '<p>' . __( 'Enable Universal tracking and tracking features related to Universal tracking.', 'google-analytics-for-wordpress' ) . '</p>'
			               . '<p><strong>' . __( 'Tab: Advanced', 'google-analytics-for-wordpress' ) . '</strong></p>'
			               . '<p>' . __( 'The section where you can find the advanced settings of this plugin. Here you can alter how you track certain things and add custom code if necessary. Only use this if you know what you’re doing.', 'google-analytics-for-wordpress' ) . '</p>'
			               . '<p><strong>' . __( 'Tab: Custom dimensions', 'google-analytics-for-wordpress' ) . '</strong></p>'
			               /* translators: %s links to `https://yoast.com/wordpress/plugins/google-analytics/` */
			               . '<p>' . sprintf( __( 'You can only use this functionality if you have %s. Custom dimensions allow for much more powerful and specific tracking.', 'google-analytics-for-wordpress' ), '<a href="https://yoast.com/wordpress/plugins/google-analytics/#utm_source=ga_settings&utm_medium=ga_tour&utm_campaign=tour">Google Analytics by Yoast Premium</a>' ) . '</p>'
			               . '<p><strong>' . __( 'Tab: Debug mode', 'google-analytics-for-wordpress' ) . '</strong></p>'
			               . '<p>' . __( 'Only use this if you know what you’re doing. Here you can check what could be hindering your tracking.', 'google-analytics-for-wordpress' ) . '</p>'
		       . '<p><strong style="font-size:150%;">' . __( 'Subscribe to our Newsletter', 'google-analytics-for-wordpress' ) . '</strong><br/>'
		       . __( 'If you would like us to keep you up-to-date regarding Google Analytics and other plugins by Yoast, subscribe to our newsletter:', 'google-analytics-for-wordpress' ) . '</p>'
		       . '<form target="_blank" action="http://yoast.us1.list-manage1.com/subscribe/post?u=ffa93edfe21752c921f860358&amp;id=972f1c9122" method="post" selector="newsletter-form" accept-charset="' . esc_attr( get_bloginfo( 'charset' ) ) . '">'
		       . '<p>'
		       . '<input style="margin: 5px; color:#666" name="EMAIL" value="' . esc_attr( $current_user->user_email ) . '" selector="newsletter-email" placeholder="' . __( 'Email', 'google-analytics-for-wordpress' ) . '"/>'
		       . '<input type="hidden" name="group" value="2"/>'
		       . '<button type="submit" class="button-primary">' . __( 'Subscribe', 'google-analytics-for-wordpress' ) . '</button>'
		       . '</p>'
		       . '</form>',
			'next_page' => 'dashboard',
		);
	}

	/**
	 * Returns the content of the General Settings page pointer
	 *
	 * @return array
	 */
	private function dashboard_pointer() {
		return array(
			'content'   => '<h3>' . __( 'Dashboard', 'google-analytics-for-wordpress' ) . '</h3>'
			               . '<p><strong>' . __( 'Tab: Overview', 'google-analytics-for-wordpress' ) . '</strong><br/>'
			               . __( 'View your website’s last month’s analytics, such as sessions and bounce rate.', 'google-analytics-for-wordpress' ) . '</p>'
			               . '<p><strong>' . __( 'Tab: Reports', 'google-analytics-for-wordpress' ) . '</strong><br/>'
			               . __( 'View specific reports of your site’s analytics, such as traffic sources, your site’s popular pages and countries where your visitors come from.', 'google-analytics-for-wordpress' ) . '</p>'
			               . '<p><strong>' . __( 'Tab: Custom dimension reports', 'google-analytics-for-wordpress' )
			               . '</strong><br/>' . __( 'View basic reports of your custom dimensions, such as traffic per author, per category, etc.', 'google-analytics-for-wordpress' ) . '</p>',
			'next_page' => 'extensions',
			'prev_page' => 'settings',
		);
	}

	/**
	 * Returns the content of the extensions and licenses page pointer
	 *
	 * @return array
	 */
	private function extensions_pointer() {
		return array(
			'content'   => '<h3>' . __( 'Extensions and Licenses', 'google-analytics-for-wordpress' ) . '</h3>'
			               . '<p><strong>' . __( 'Tab: Extensions', 'google-analytics-for-wordpress' ) . '</strong><br/>'
			               /* translators: %s links to `https://yoast.com/wordpress/plugins/`. */
			               . sprintf( __( 'See which extensions you have installed and which you haven’t installed yet. You can find extensions to our Google Analytics plugin %shere%s.', 'google-analytics-for-wordpress' ), '<a href="https://yoast.com/wordpress/plugins/#utm_source=ga_licenses&utm_medium=ga_tour&utm_campaign=tour">', '</a>' ) . '</p>'
			               . '<p><strong>' . __( 'Tab: Licenses', 'google-analytics-for-wordpress' ) . '</strong><br/>'
			               . __( 'Here you can activate, deactivate and renew your licenses.', 'google-analytics-for-wordpress' ) . '</p>'
			               . '<p><strong>' . __( 'Like this plugin?', 'google-analytics-for-wordpress' ) . '</strong><br/>'
			               /* translators: %s links to `https://wordpress.org/plugins/google-analytics-for-wordpress/` */
			               . sprintf( __( 'So, we&#8217;ve come to the end of the tour. If you like the plugin, please %srate it 5 stars on WordPress.org%s!', 'google-analytics-for-wordpress' ), '<a target="_blank" href="https://wordpress.org/plugins/google-analytics-for-wordpress/">', '</a>' ) . '</p>'
			               /* translators: %s links to `https://yoast.com` */
			               . '<p>' . sprintf( __( 'Thank you for using our plugin and good luck with your Analytics!<br/><br/>Best,<br/>Team Yoast - %sYoast.com%s', 'google-analytics-for-wordpress' ), '<a target="_blank" href="' . esc_url( 'https://yoast.com/#utm_source=wpseo_licenses&utm_medium=wpseo_tour&utm_campaign=tour' ) . '">', '</a>' ) . '</p>',
			'prev_page' => 'dashboard',
		);
	}

} /* End of class */
