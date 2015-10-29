<?php
/**
 * @package GoogleAnalytics\Frontend
 */

/**
 * The basic frontend tracking class for the GA plugin, extendable for the children
 */
abstract class Yoast_GA_Tracking {

	/**
	 * Storage for the currently set options
	 * @var mixed|void
	 */
	public $options;

	/**
	 * Get the regular expression used in Ga.js and universal tracking to detect links
	 *
	 * @return string
	 */
	protected function get_link_regex() {
		return '/'
		       . '<a'               // matches the characters <a literally
		       . '([^>]*)'          // 1. match a single character not present in the list (Between 0 and * times)
		       . '\s'               // match any white space character
		       . 'href='            // matches the characters href= literally
		       . '([\'\"])'         // 2. match a single or a double quote
		       . '('                // 3. matches the link protocol (between 0 and 1 times)
		       .   '([a-zA-Z]+)'    // 4. matches the link protocol name
		       .   ':'              // matches the character : literally
		       .   '(?:\/\/)??'      // matches the characters // literally (between 0 and 1 times)
		       .  ')??'
		       .  '(.*)'            // 5. matches any character (except newline) (Between 0 and * times)
		       .  '\2'              // matches the same quote (2nd capturing group) as was used to open the href value
		       .  '([^>]*)'         // 6. match a single character not present in the list below
		       . '>'                // matches the characters > literally
		       . '(.*)'             // 7. matches any character (except newline) (Between 0 and * times)
		       . '<\/a>'            // matches the characters </a> literally
		       . '/isU';            // case insensitive, single line, ungreedy
	}

	/**
	 * @var boolean $do_tracking Should the tracking code be added
	 */
	protected $do_tracking = null;

	/**
	 * Function to output the GA Tracking code in the wp_head()
	 *
	 * @param bool $return_array
	 *
	 * @return mixed
	 */
	abstract public function tracking( $return_array = false );

	/**
	 * Output tracking link
	 *
	 * @param string               $category
	 * @param Yoast_GA_Link_Target $link_target
	 *
	 * @return string
	 */
	abstract protected function output_parse_link( $category, Yoast_GA_Link_Target $link_target );

	/**
	 * Class constructor
	 */
	public function __construct() {
		$options_class = $this->get_options_class();
		$this->options = $options_class->options;

		add_action( 'wp_head', array( $this, 'tracking' ), 8 );

		if ( $this->options['track_outbound'] == 1 ) {
			$this->track_outbound_filters();
		}
	}

	/**
	 * Delegates `get_tracking_code` to the options class
	 *
	 * @return null
	 */
	public function get_tracking_code() {
		return $this->get_options_class()->get_tracking_code();
	}

	/**
	 * Get 1 or 0 if we need to do enhanced link attribution
	 *
	 * @return mixed
	 */
	public function get_enhanced_link_attribution() {
		return $this->options['enhanced_link_attribution'];
	}

	/**
	 * Parse article link
	 *
	 * @param array $matches
	 *
	 * @return string
	 */
	public function parse_article_link( $matches ) {
		return $this->parse_link( 'outbound-article', $matches );
	}

	/**
	 * Parse comment link
	 *
	 * @param array $matches
	 *
	 * @return string
	 */
	public function parse_comment_link( $matches ) {
		return $this->parse_link( 'outbound-comment', $matches );
	}

	/**
	 * Parse widget link
	 *
	 * @param array $matches
	 *
	 * @return string
	 */
	public function parse_widget_link( $matches ) {
		return $this->parse_link( 'outbound-widget', $matches );
	}

	/**
	 * Parse menu link
	 *
	 * @param array $matches
	 *
	 * @return string
	 */
	public function parse_nav_menu( $matches ) {
		return $this->parse_link( 'outbound-menu', $matches );
	}

	/**
	 * Parse the_content or the_excerpt for links
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function the_content( $text ) {
		if ( ! $this->do_tracking() ) {
			return $text;
		}

		if ( ! is_feed() ) {
			$text = preg_replace_callback( $this->get_link_regex(), array( $this, 'parse_article_link' ), $text );
		}

		return $text;
	}

	/**
	 * Parse the widget content for links
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function widget_content( $text ) {
		if ( ! $this->do_tracking() ) {
			return $text;
		}
		$text = preg_replace_callback( $this->get_link_regex(), array( $this, 'parse_widget_link' ), $text );

		return $text;
	}

	/**
	 * Parse the nav menu for links
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function nav_menu( $text ) {
		if ( ! $this->do_tracking() ) {
			return $text;
		}

		if ( ! is_feed() ) {
			$text = preg_replace_callback( $this->get_link_regex(), array( $this, 'parse_nav_menu' ), $text );
		}

		return $text;
	}

	/**
	 * Parse comment text for links
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function comment_text( $text ) {
		if ( ! $this->do_tracking() ) {
			return $text;
		}

		if ( ! is_feed() ) {
			$text = preg_replace_callback( $this->get_link_regex(), array( $this, 'parse_comment_link' ), $text );
		}

		return $text;
	}

	/**
	 * Merge the existing onclick with a new one and append it
	 *
	 * @param string $link_attribute
	 * @param string $onclick
	 *
	 * @return string
	 */
	public function output_add_onclick( $link_attribute, $onclick ) {
		if ( preg_match( '/onclick=[\'\"](.*?;)[\'\"]/i', $link_attribute, $matches ) > 0 ) {
			$js_snippet_single = 'onclick=\'' . $matches[1] . ' ' . $onclick . '\'';
			$js_snippet_double = 'onclick="' . $matches[1] . ' ' . $onclick . '"';

			$link_attribute = str_replace( 'onclick="' . $matches[1] . '"', $js_snippet_double, $link_attribute );
			$link_attribute = str_replace( "onclick='" . $matches[1] . "'", $js_snippet_single, $link_attribute );

			return $link_attribute;
		}
		else {
			if ( ! is_null( $onclick ) ) {
				return 'onclick="' . $onclick . '" ' . $link_attribute;
			}
			else {
				return $link_attribute;
			}
		}
	}

	/**
	 * Generate the full URL
	 *
	 * @param Yoast_GA_Link_Target $link
	 *
	 * @return string
	 */
	public function make_full_url( $link ) {
		$protocol = '';
		switch ( $link->type ) {
			case 'download':
			case 'internal':
			case 'internal-as-outbound':
			case 'outbound':
				$protocol = ! empty( $link->protocol ) ? $link->protocol . '://' : '';
				break;
			case 'email':
				$protocol = 'mailto:';
				break;
		}

		return $protocol . $link->original_url;
	}

	/**
	 * Check if we need to show an actual tracking code
	 *
	 * @return bool
	 */
	public function do_tracking() {
		if ( $this->do_tracking === null ) {
			global $current_user;

			get_currentuserinfo();

			$this->do_tracking = true;

			if ( 0 != $current_user->ID && isset( $this->options['ignore_users'] ) ) {
				if ( ! empty( $current_user->roles ) && in_array( $current_user->roles[0], $this->options['ignore_users'] ) ) {
					$this->do_tracking = false;
				}
			}

			/**
			 * Filter: 'yst_ga_track_super_admin' - Allows filtering if the Super admin should be tracked in a multi-site setup
			 *
			 * @api array $all_roles
			 */
			$track_super_admin = apply_filters( 'yst_ga_track_super_admin', true );
			if ( $track_super_admin === false && is_super_admin() ) {
				$this->do_tracking = false;
			}
		}

		return $this->do_tracking;
	}

	/**
	 * Setting the filters for tracking outbound links
	 *
	 */
	protected function track_outbound_filters() {
		add_filter( 'the_content', array( $this, 'the_content' ), 99 );
		add_filter( 'widget_text', array( $this, 'widget_content' ), 99 );
		add_filter( 'wp_list_bookmarks', array( $this, 'widget_content' ), 99 );
		add_filter( 'wp_nav_menu', array( $this, 'widget_content' ), 99 );
		add_filter( 'the_excerpt', array( $this, 'the_content' ), 99 );
		add_filter( 'comment_text', array( $this, 'comment_text' ), 99 );
	}

	/**
	 * Get the output tracking link
	 *
	 * @param string $category
	 * @param array  $matches
	 *
	 * @return string
	 */
	protected function parse_link( $category, array $matches ) {
		return $this->output_parse_link( $category, new Yoast_GA_Link_Target( $category, $matches, $this->options ) );
	}

	/**
	 * Get the options class
	 *
	 * @return Yoast_GA_Options
	 */
	protected function get_options_class() {
		return Yoast_GA_Options::instance();
	}

	/**
	 * Trims the track_internal_as_label option to prevent commas and whitespaces
	 *
	 * @return string
	 */
	protected function sanitize_internal_label() {
		if ( ! is_null( $this->options['track_internal_as_label'] ) && ! empty( $this->options['track_internal_as_label'] ) ) {
			$label = $this->options['track_internal_as_label'];
			$label = trim( $label, ',' );
			$label = trim( $label );
		}

		// Be sure label isn't empty, if so, set value with in
		if ( empty( $label ) ) {
			$label = 'int';
		}

		return $label;
	}

	/**
	 * When a usergroup is disabled, show a message in the source to notify the user they are in a disabled user group.
	 */
	protected function disabled_usergroup() {
		/* translators %1$s is the product name 'Google Analytics by Yoast'. %2$s displays the plugin version the website uses and a link to the plugin on Yoast.com */
		echo '<!-- ' . sprintf( __( 'This site uses the %1$s plugin version %2$s', 'google-analytics-for-wordpress' ), 'Google Analytics by Yoast', GAWP_VERSION . ' - https://yoast.com/wordpress/plugins/google-analytics/' ) . ' -->';

		if ( current_user_can( 'manage_options' ) ) {
			echo '<!-- ' . __( '@Webmaster, normally you will find the Google Analytics tracking code here, but you are in the disabled user groups. To change this, navigate to Analytics -> Settings (Ignore usergroups)', 'google-analytics-for-wordpress' ) . ' -->';
		}
		else {
			echo '<!-- ' . __( 'Normally you will find the Google Analytics tracking code here, but the webmaster disabled your user group.', 'google-analytics-for-wordpress' ) . ' -->';
		}

		// Do not make this translatable, as this is the product name.
		echo '<!-- / Google Analytics by Yoast -->';
	}

	/**
	 * When the debug mode is enabled, display a message in the source.
	 *
	 * @return bool
	 */
	protected function debug_mode() {
		if ( $this->options['debug_mode'] === 1 ) {
			/* translators %1$s is the product name 'Google Analytics by Yoast'. %2$s displays the plugin version the website uses and a link to the plugin on Yoast.com */
			echo '<!-- ' . sprintf( __( 'This site uses the %1$s plugin version %2$s', 'google-analytics-for-wordpress' ), 'Google Analytics by Yoast', GAWP_VERSION . ' - https://yoast.com/wordpress/plugins/google-analytics/' ) . ' -->';

			if ( current_user_can( 'manage_options' ) ) {
				echo '<!-- ' . __( '@Webmaster, normally you will find the Google Analytics tracking code here, but the Debug Mode is enabled. To change this, navigate to Analytics -> Settings -> (Tab) Debug Mode and disable Debug Mode to enable tracking of your site.', 'google-analytics-for-wordpress' ) . ' -->';
			}
			else {
				echo '<!-- ' . __( 'Normally you will find the Google Analytics tracking code here, but the webmaster has enabled the Debug Mode.', 'google-analytics-for-wordpress' ) . ' -->';
			}

			// Do not make this translatable, as this is the product name.
			echo '<!-- / Google Analytics by Yoast -->';

			return true;
		}
		return false;
	}

}
