<?php
/**
 * @package WPSEO\Admin
 */

/**
 * This class handles the pointers used in the introduction tour.
 */
class Yoast_GA_Pointers {

	/**
	 * @var object Instance of this class
	 */
	public static $instance;

	/**
	 * @var array Holds the buttons to be put out
	 */
	private $button_array;

	/**
	 * @var array Holds the admin pages we have pointers for and the callback that generates the pointers content
	 */
	private $admin_pages = array(
		'dashboard'  => 'dashboard_pointer',
		'settings'   => 'settings_pointer',
		'extensions' => 'extensions_pointer',
	);

	/**
	 * Class constructor.
	 */
	public function __construct() {
		if ( ! get_user_meta( get_current_user_id(), 'ga_ignore_tour' ) ) {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script( 'wp-pointer' );
			add_action( 'admin_print_footer_scripts', array( $this, 'intro_tour' ) );
		}
	}

	/**
	 * Get the singleton instance of this class
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load the introduction tour
	 */
	public function intro_tour() {
		global $pagenow;

		$page = preg_replace( '/^(yst_ga_)/', '', filter_input( INPUT_GET, 'page' ) );

		if ( 'admin.php' === $pagenow && array_key_exists( $page, $this->admin_pages ) ) {
			$this->do_page_pointer( $page );
		}
		else {
			$this->start_tour_pointer();
		}
	}

	/**
	 * Prints the pointer script
	 *
	 * @param string $selector The CSS selector the pointer is attached to.
	 * @param array  $options  The options for the pointer.
	 */
	public function print_scripts( $selector, $options ) {
		// Button1 is the close button, which always exists.
		$button_array_defaults = array(
			'button2' => array(
				'text'     => false,
				'function' => '',
			),
			'button3' => array(
				'text'     => false,
				'function' => '',
			),
		);
		$this->button_array    = wp_parse_args( $this->button_array, $button_array_defaults );

		if ( function_exists( 'wp_json_encode' ) ) {
			$json_options = wp_json_encode( $options );
		}
		else {
			// @codingStandardsIgnoreStart
			$json_options = json_encode( $options );
			// @codingStandardsIgnoreEnd
		}

		?>
		<script type="text/javascript">
			//<![CDATA[
			(function ($) {
				// Don't show the tour on screens with an effective width smaller than 1024px or an effective height smaller than 768px.
				if (jQuery(window).width() < 1024 || jQuery(window).availWidth < 1024) {
					return;
				}

				var wpseo_pointer_options = <?php echo $json_options; ?>, setup;

				wpseo_pointer_options = $.extend(wpseo_pointer_options, {
					buttons: function (event, t) {
						var button = jQuery('<a href="<?php echo $this->get_ignore_url(); ?>" id="pointer-close" style="margin:0 5px;" class="button-secondary">' + '<?php _e( 'Close', 'google-analytics-for-wordpress' ) ?>' + '</a>');
						button.bind('click.pointer', function () {
							t.element.pointer('close');
						});
						return button;
					},
					close  : function () {
					}
				});

				setup = function () {
					$('<?php echo $selector; ?>').pointer(wpseo_pointer_options).pointer('open');
					<?php
					$this->button2();
					$this->button3();
					?>
				};

				if (wpseo_pointer_options.position && wpseo_pointer_options.position.defer_loading)
					$(window).bind('load.wp-pointers', setup);
				else
					$(document).ready(setup);
			})(jQuery);
			//]]>
		</script>
	<?php
	}

	/**
	 * Render button 2, if needed
	 */
	private function button2() {
		if ( $this->button_array['button2']['text'] ) {
			?>
			jQuery('#pointer-close').after('<a id="pointer-primary" class="button-primary">' +
				'<?php echo $this->button_array['button2']['text']; ?>' + '</a>');
			jQuery('#pointer-primary').click(function () {
			<?php echo $this->button_array['button2']['function']; ?>
			});
		<?php
		}
	}

	/**
	 * Render button 3, if needed. This is the previous button in most cases
	 */
	private function button3() {
		if ( $this->button_array['button3']['text'] ) {
			?>
			jQuery('#pointer-primary').after('<a id="pointer-ternary" style="float: left;" class="button-secondary">' +
				'<?php echo $this->button_array['button3']['text']; ?>' + '</a>');
			jQuery('#pointer-ternary').click(function () {
			<?php echo $this->button_array['button3']['function']; ?>
			});
		<?php
		}
	}

	/**
	 * Show a pointer that starts the tour for WordPress SEO
	 */
	private function start_tour_pointer() {
		$selector = 'li#toplevel_page_yst_ga_dashboard';
		$content  = '<h3>' . __( 'Congratulations!', 'google-analytics-for-wordpress' ) . '</h3>'
		            . '<p>' . __( 'You\'ve just installed Google Analytics by Yoast! Click "Start Tour" to view a quick introduction of this plugin\'s core functionality.', 'google-analytics-for-wordpress' ) . '</p>';
		$opt_arr  = array(
			'content'  => $content,
			'position' => array( 'edge' => 'top', 'align' => 'center' ),
		);

		$this->button_array['button2']['text']     = __( 'Start Tour', 'google-analytics-for-wordpress' );
		$this->button_array['button2']['function'] = sprintf( 'document.location="%s";', admin_url( 'admin.php?page=yst_ga_dashboard' ) );

		$this->print_scripts( $selector, $opt_arr );
	}

	/**
	 * Shows a pointer on the proper pages
	 *
	 * @param string $page
	 */
	private function do_page_pointer( $page ) {
		$selector = '#yoast_ga_title';

		$pointer = call_user_func( array( $this, $this->admin_pages[ $page ] ) );

		$opt_arr = array(
			'content'      => $pointer['content'],
			'position'     => array(
				'edge'  => 'top',
				'align' => ( is_rtl() ) ? 'left' : 'right',
			),
			'pointerWidth' => 450,
		);
		if ( isset( $pointer['next_page'] ) ) {
			$this->button_array['button2'] = array(
				'text'     => __( 'Next', 'google-analytics-for-wordpress' ),
				'function' => 'window.location="' . admin_url( 'admin.php?page=yst_ga_' . $pointer['next_page'] ) . '";',
			);
		}
		if ( isset( $pointer['prev_page'] ) ) {
			$this->button_array['button3'] = array(
				'text'     => __( 'Previous', 'google-analytics-for-wordpress' ),
				'function' => 'window.location="' . admin_url( 'admin.php?page=yst_ga_' . $pointer['prev_page'] ) . '";',
			);
		}
		$this->print_scripts( $selector, $opt_arr );
	}

	/**
	 * Returns the content of the General Settings page pointer
	 *
	 * @return array
	 */
	private function dashboard_pointer() {
		global $current_user;

		return array(
			'content'   => '<h3>' . __( 'Dashboard', 'google-analytics-for-wordpress' ) . '</h3>'
			               . '<p><strong>' . __( 'Tab: Overview', 'google-analytics-for-wordpress' ) . '</strong><br/>' . __( 'Info overview', 'google-analytics-for-wordpress' ) . '</p>'
			               . '<p><strong>' . __( 'Tab: Reports', 'google-analytics-for-wordpress' ) . '</strong><br/>' . __( 'Info reports', 'google-analytics-for-wordpress' ) . '</p>'
			               . '<p><strong>' . __( 'Tab: Custom dimension reports', 'google-analytics-for-wordpress' ) . '</strong><br/>' . __( 'Info custom dimensions reports', 'google-analytics-for-wordpress' ) . '</p>'
			               . '<p><strong style="font-size:150%;">' . __( 'Subscribe to our Newsletter', 'google-analytics-for-wordpress' ) . '</strong><br/>'
			               . __( 'If you would like us to keep you up-to-date regarding Google Analytics and other plugins by Yoast, subscribe to our newsletter:', 'google-analytics-for-wordpress' ) . '</p>'
			               . '<form target="_blank" action="http://yoast.us1.list-manage1.com/subscribe/post?u=ffa93edfe21752c921f860358&amp;id=972f1c9122" method="post" selector="newsletter-form" accept-charset="' . esc_attr( get_bloginfo( 'charset' ) ) . '">'
			               . '<p>'
			               . '<input style="margin: 5px; color:#666" name="EMAIL" value="' . esc_attr( $current_user->user_email ) . '" selector="newsletter-email" placeholder="' . __( 'Email', 'google-analytics-for-wordpress' ) . '"/>'
			               . '<input type="hidden" name="group" value="2"/>'
			               . '<button type="submit" class="button-primary">' . __( 'Subscribe', 'google-analytics-for-wordpress' ) . '</button>'
			               . '</p>'
			               . '</form>',
			'next_page' => 'settings',
		);
	}

	/**
	 * Returns the content of the settings pointer
	 *
	 * @return array
	 */
	private function settings_pointer() {
		return array(
			'content'   => '<h3>' . __( 'Settings' ) . '</h3>',

			'next_page' => 'extensions',
		);
	}

	/**
	 * Returns the content of the extensions and licenses page pointer
	 *
	 * @return array
	 */
	private function extensions_pointer() {
		return array(
			'content'   => '<h3>' . __( 'Extensions and Licenses', 'wordpress-seo' ) . '</h3>'
			               . '<p><strong>' . __( 'Extensions', 'wordpress-seo' ) . '</strong><br/>' . sprintf( __( 'The powerful functions of WordPress SEO can be extended with %1$sYoast premium plugins%2$s. These premium plugins require the installation of WordPress SEO or WordPress SEO Premium and add specific functionality. You can read all about the Yoast Premium Plugins %1$shere%2$s.', 'wordpress-seo' ), '<a target="_blank" href="' . esc_url( 'https://yoast.com/wordpress/plugins/#utm_source=wpseo_licenses&utm_medium=wpseo_tour&utm_campaign=tour' ) . '">', '</a>' ) . '</p>'
			               . '<p><strong>' . __( 'Licenses', 'wordpress-seo' ) . '</strong><br/>' . __( 'Once you&#8217;ve purchased WordPress SEO Premium or any other premium Yoast plugin, you&#8217;ll have to enter a license key. You can do so on the Licenses-tab. Once you&#8217;ve activated your premium plugin, you can use all its powerful features.', 'wordpress-seo' ) . '</p>'
			               . '<p><strong>' . __( 'Like this plugin?', 'wordpress-seo' ) . '</strong><br/>' . sprintf( __( 'So, we&#8217;ve come to the end of the tour. If you like the plugin, please %srate it 5 stars on WordPress.org%s!', 'wordpress-seo' ), '<a target="_blank" href="https://wordpress.org/plugins/wordpress-seo/">', '</a>' ) . '</p>'
			               . '<p>' . sprintf( __( 'Thank you for using our plugin and good luck with your SEO!<br/><br/>Best,<br/>Team Yoast - %1$sYoast.com%2$s', 'wordpress-seo' ), '<a target="_blank" href="' . esc_url( 'https://yoast.com/#utm_source=wpseo_licenses&utm_medium=wpseo_tour&utm_campaign=tour' ) . '">', '</a>' ) . '</p>',
			'prev_page' => 'settings',
		);
	}

	/**
	 * Extending the current page URL with two params to be able to ignore the tour.
	 *
	 * @return mixed
	 */
	private function get_ignore_url() {
		$arr_params = array(
			'ga_restart_tour' => false,
			'ga_ignore_tour'  => '1',
			'nonce'           => wp_create_nonce( 'ga-ignore-tour' ),
		);

		return esc_url( add_query_arg( $arr_params ) );
	}
} /* End of class */
