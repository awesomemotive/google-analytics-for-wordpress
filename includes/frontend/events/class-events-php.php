<?php
/**
 * Events PHP class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage  Events
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MonsterInsights_Events_PHP {

	/**
	 * Holds the base class object.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var object $base Base class object.
	 */
	public $base;
	
	/**
	 * Holds the name of the events type.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $name Name of the events type.
	 */
	public $name = 'php';

	/**
	 * Version of the events class.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $version Version of the events class.
	 */
	public $version = '1.0.0';

	/**
	 * Holds the name of the tracking type.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $name Name of the tracking type.
	 */
	public $tracking = 'ga';

	/**
	 * Primary class constructor.
	 *
	 * @since 6.0.0
	 * @access public
	 */
	public function __construct() {
		$this->base     = MonsterInsights();
		$this->tracking = monsterinsights_get_option( 'tracking_mode', false );
		$events = monsterinsights_get_option( 'events_mode', false );

		if ( $events === 'php' && ( $this->tracking === 'ga' || $this->tracking === 'analytics' ) ) {
			require_once plugin_dir_path( $this->base->file ) . 'includes/frontend/events/class-link.php';
			add_filter( 'the_content', array( $this, 'the_content' ), 99 );
			add_filter( 'the_excerpt', array( $this, 'the_content' ), 99 );
			add_filter( 'widget_text', array( $this, 'widget_content' ), 99 );
			add_filter( 'wp_list_bookmarks', array( $this, 'widget_content' ), 99 );
			add_filter( 'comment_text', array( $this, 'comment_text' ), 99 );
			add_filter( 'wp_nav_menu', array( $this, 'nav_menu' ), 99 );
		}
	}

	/**
	 * Get the regular expression used in ga.js and analytics.js PHP tracking to detect links
	 *
	 * @since 6.0.0
	 * @access protected
	 *
	 * @todo If we don't remove this soon, it'd be far superior to use a real DOM parser.
	 * 
	 * @return string
	 */
	protected function get_link_regex() {
		return '/'
			   . '<a'               // matches the characters <a literally
			   . '\s'				// Match any sort of whitespace
			   . '([^>]*)'          // 1. match a single character not present in the list (Between 0 and * times)
			   . '\s'               // match any white space character
			   . 'href='            // matches the characters href= literally
			   . '([\'\"])'         // 2. match a single or a double quote
			   . '('                // 3. matches the link protocol (between 0 and 1 times)
			   .   '([a-zA-Z]+)'    // 4. matches the link protocol name
			   .   ':'              // matches the character : literally
			   .   '(?:\/\/)??'     // matches the characters
			   .  ')??'             // literally (between 0 and 1 times)
			   .  '(.*)'            // 5. matches any character (except newline) (Between 0 and * times)
			   .  '\2'              // matches the same quote (2nd capturing group) as was used to open the href value
			   .  '([^>]*)'         // 6. match a single character not present in the list below
			   . '>'                // matches the characters > literally
			   . '(.*)'             // 7. matches any character (except newline) (Between 0 and * times)
			   . '<\/a>'            // matches the characters </a> literally
			   . '/isU';            // case insensitive, single line, ungreedy
	}


	/**
	 * Parse the_content or the_excerpt for links
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @param string $text Text to parse
	 *
	 * @return string The resulting content.
	 */
	public function the_content( $text ) {
		if ( ! is_feed() ) {
			$text = preg_replace_callback( $this->get_link_regex(), array( $this, 'parse_article_link' ), $text );
		}
		return $text;
	}

	/**
	 * Parse article link
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @param array $matches The matches for links within the content
	 *
	 * @return string The parsed link string.
	 */
	public function parse_article_link( $matches ) {
		return $this->parse_link( 'outbound-article', $matches );
	}

	/**
	 * Parse the widget content for links
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @param string $text The text to parse.
	 *
	 * @return string The resulting content.
	 */
	public function widget_content( $text ) {
		$text = preg_replace_callback( $this->get_link_regex(), array( $this, 'parse_widget_link' ), $text );
		return $text;
	}

	/**
	 * Parse widget link
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @param array $matches The matches for links within the content
	 *
	 * @return string The parsed link string.
	 */
	public function parse_widget_link( $matches ) {
		return $this->parse_link( 'outbound-widget', $matches );
	}

	/**
	 * Parse the nav menu content for links
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @param string $text The text to parse.
	 *
	 * @return string The resulting content.
	 */
	public function nav_menu( $text ) {
		$text = preg_replace_callback( $this->get_link_regex(), array( $this, 'parse_nav_menu_link' ), $text );
		return $text;
	}

	/**
	 * Parse nav menu link
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @param array $matches The matches for links within the content
	 *
	 * @return string The parsed link string.
	 */
	public function parse_nav_menu_link( $matches ) {
		return $this->parse_link( 'outbound-menu', $matches );
	}

	/**
	 * Parse comment text for links
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @param string $text The text to parse.
	 *
	 * @return string The resulting content.
	 */
	public function comment_text( $text ) {
		if ( ! is_feed() ) {
			$text = preg_replace_callback( $this->get_link_regex(), array( $this, 'parse_comment_link' ), $text );
		}

		return $text;
	}

	/**
	 * Parse comment link
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @param array $matches The matches for links within the content
	 *
	 * @return string The parsed link string.
	 */
	public function parse_comment_link( $matches ) {
		return $this->parse_link( 'outbound-comment', $matches );
	}


	/**
	 * Merge the existing onclick with a new one and append it
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @param string $link_attribute The new onclick value to append.
	 * @param string $onclick The existing onclick value.
	 *
	 * @return string The resulting link attribute for onclick.
	 */
	public function output_add_onclick( $link_attribute, $onclick ) {
		if ( preg_match( '/onclick=[\'\"](.*?;)[\'\"]/i', $link_attribute, $matches ) > 0 && is_string( $onclick ) ) {
			$onclick_with_double = str_replace( "'", '"', $onclick );
			$js_snippet_single = 'onclick=\'' . $matches[1] . ' ' . $onclick_with_double . '\'';
			$js_snippet_double = 'onclick="' . $matches[1] . ' ' . $onclick . '"';

			$link_attribute = str_replace( 'onclick="' . $matches[1] . '"', $js_snippet_double, $link_attribute );
			$link_attribute = str_replace( "onclick='" . $matches[1] . "'", $js_snippet_single, $link_attribute );

			return $link_attribute;
		} else if ( preg_match( '/onclick=[\'\"](.*?)[\'\"]/i', $link_attribute, $matches ) > 0 && is_string( $onclick ) ) { // if not closed, see #33
			$onclick_with_double = str_replace( "'", '"', $onclick );
			if ( strlen( trim ( $matches[1] ) ) > 0 ) {
				$js_snippet_single = 'onclick=\'' . $matches[1] . '; ' . $onclick_with_double . '\'';
				$js_snippet_double = 'onclick="' . $matches[1] . '; ' . $onclick . '"';
			} else {
				$js_snippet_single = 'onclick=\'' . $matches[1] . ' ' . $onclick_with_double . '\'';
				$js_snippet_double = 'onclick="' . $matches[1] . ' ' . $onclick . '"';				
			}

			$link_attribute = str_replace( 'onclick="' . $matches[1] . '"', $js_snippet_double, $link_attribute );
			$link_attribute = str_replace( "onclick='" . $matches[1] . "'", $js_snippet_single, $link_attribute );

			return $link_attribute;
		} else {
			if ( ! empty( $onclick ) && is_string( $onclick ) ) {
				return 'onclick="' . $onclick . '" ' . $link_attribute;
			} else {
				return $link_attribute;
			}
		}
	}

	/**
	 * Generate the full URL.
	 * 
	 * Takes an existing link that's missing it's protocol
	 * and pre-pends the protocol to it.
	 * 
	 * @since 6.0.0
	 * @access public
	 * 
	 * @param MonsterInsights_Link $link The protocol-less link.
	 *
	 * @return string The resulting link (with pre-pended protocol).
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
	 * Get the output tracking link
	 *
	 * @since 6.0.0
	 * @access protected
	 * 
	 * @param string $category The category of the link (ex: outbound-widget).
	 * @param array  $matches The matches found for links within the content.
	 *
	 * @return string The resulting link.
	 */
	protected function parse_link( $category, $matches ) {
		return $this->output_parse_link( $category, new MonsterInsights_Link( $this->base, $category, $matches ) );
	}

	/**
	 * Trims the track_internal_as_label option to prevent commas and whitespaces.
	 *
	 * @since 6.0.0
	 * @access protected
	 * 
	 * @return string The internal label to use.
	 */
	protected function sanitize_internal_label() {
		$label = monsterinsights_get_option( 'track_internal_as_label', '' );
		if ( ! empty( $label ) && is_string( $label ) ) {
			$label = trim( $label, ',' );
			$label = trim( $label );
		}

		// If the label is empty, set a default value
		if ( empty( $label ) ) {
			$label = 'int';
		}

		return $label;
	}

	/**
	 * Create the event tracking link.
	 *
	 * @since 6.0.0
	 * @access protected
	 * 
	 * @param string               $category The category of the label (ex: outbound-widget ).
	 * @param MonsterInsights_Link $link_target The link object we're working on.
	 *
	 * @return string The resultant new <a> tag to use.
	 */
	protected function output_parse_link( $category, $link_target ) {
		$object_name = '__gaTracker'; // $this->tracking === 'analytics'
		if ( $this->tracking === 'ga' ) {
			$object_name = '_gaq.push';
		}

		// bail early for links that we can't handle
		if ( $link_target->type === 'internal' ) {
			return $link_target->hyperlink;
		}

		$onclick  = null;
		$full_url = $this->make_full_url( $link_target );
		switch ( $link_target->type ) {
			case 'download':
				if ( $this->tracking === 'ga' ){
					if ( monsterinsights_get_option('track_download_as', '' ) === 'pageview' ) {
						$onclick = $object_name . "(['_trackPageview','download/" . esc_js( $full_url ) . "']);";
					} else {
						$onclick = $object_name . "(['_trackEvent','download','" . esc_js( $full_url ) . "']);";
					}
				} else {
					if ( monsterinsights_get_option('track_download_as', '' ) === 'pageview' ) {
						$onclick = $object_name . "('send', 'pageview', '" . esc_js( $full_url ) . "');";
					} else {
						$onclick = $object_name . "('send', 'event', 'download', '" . esc_js( $full_url ) . "');";
					}
				}
				break;
			case 'email':
				if ( $this->tracking === 'ga' ){
					$onclick = $object_name . "(['_trackEvent','mailto','" . esc_js( $link_target->original_url ) . "']);";
				} else {
					$onclick = $object_name . "('send', 'event', 'mailto', '" . esc_js( $link_target->original_url ) . "');";
				}
				break;
			case 'internal-as-outbound':
				if ( $this->tracking === 'ga' ){ 
					$category = $this->sanitize_internal_label();
					$onclick = $object_name . "(['_trackEvent', '" . esc_js( $link_target->category ) . '-' . esc_js( $category ) . "', '" . esc_js( $full_url ) . "', '" . esc_js( strip_tags( $link_target->link_text ) ) . "']);";
				} else {
					$category = $this->sanitize_internal_label();
					$onclick = $object_name . "('send', '" . esc_js( monsterinsights_get_option('track_download_as', '' ) ) . "', '" . esc_js( $link_target->category ) . '-' . esc_js( $category ) . "', '" . esc_js( $full_url ) . "', '" . esc_js( strip_tags( $link_target->link_text ) ) . "');";
				}
				break;
			case 'outbound':
				if ( $this->tracking === 'ga' ){  
					 $onclick = $object_name . "(['_trackEvent', '" . esc_js( $link_target->category ) . "', '" . esc_js( $full_url ) . "', '" . esc_js( strip_tags( $link_target->link_text ) ) . "']);";
				} else {
					$onclick = $object_name . "('send', 'event', '" . esc_js( $link_target->category ) . "', '" . esc_js( $full_url ) . "', '" . esc_js( strip_tags( $link_target->link_text ) ) . "');";
				}
				break;
		}

		$link_target->link_attributes = $this->output_add_onclick( $link_target->link_attributes, $onclick );

		if ( ! empty( $link_target->link_attributes ) ) {
			return '<a href="' . $full_url . '" ' . trim( $link_target->link_attributes ) . '>' . $link_target->link_text . '</a>';
		}

		return '<a href="' . $full_url . '">' . $link_target->link_text . '</a>';
	}
}