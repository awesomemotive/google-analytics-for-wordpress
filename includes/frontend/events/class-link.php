<?php
/**
 * Link class.
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

class MonsterInsights_Link {
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
	 * The category of the link.
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @var string $category Category of the link.
	 */
	public $category;

	/**
	 * Get the domain and host. False if empty.
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @var array|bool $domain Domain of link.
	 */
	public $domain;

	/**
	 * Extension of the url.
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @var string $extension File extension in given url.
	 */
	public $extension;

	/**
	 * Host of the URL.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $host Host of given url.
	 */
	public $host;

	/**
	 * The link attributes of the URL.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $link_attributes Link attributes of given hyperlink.
	 */
	public $link_attributes;

	/**
	 * The text of the link.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $link_text Text of given hyperlink.
	 */
	public $link_text;

	/**
	 * The full url without the protocol.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $original_url The full url without the protocol.
	 */
	public $original_url;

	/**
	 * The protocol of the url.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $protocol The protocol of the link.
	 */
	public $protocol;

	/**
	 * The type of the URL - for example: internal as outbound, outbound, internal.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $type The type of the link.
	 */
	public $type;

	/**
	 * The full hyperlink.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $hyperlink The hyperlink.
	 */
	public $hyperlink;

	/**
	 * Constructor of the class.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @param MonsterInsights $base The base plugin object.
	 * @param string $category The category of the url (ex: outbound-link).
	 * @param array  $matches Matches found for the hyperlink.
	 */
	public function __construct( $base, $category, $matches ) {
		$this->base 		   = $base;
		$this->category        = $category;
		$this->original_url    = $matches[5];
		$this->domain          = $this->get_domain();
		$this->extension       = substr( strrchr( $this->original_url, '.' ), 1 );
		$this->host            = $this->domain['host'];
		$this->link_attributes = trim( $matches[1] . ' ' . $matches[6] );
		$this->link_text       = $matches[7];
		$this->hyperlink       = $matches[0];
		$this->protocol 	   = $matches[4];
		$this->type     	   = $this->get_target_type();
	}
	/**
	 * Parse the domain.
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @return array|bool The domain/host of the link.
	 */
	public function get_domain() {
		$hostPattern     = '/^(https?:\/\/)?([^\/]+)/i';
		$domainPatternUS = '/[^\.\/]+\.[^\.\/]+$/';
		$domainPatternUK = '/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/';
		$matching = preg_match( $hostPattern, $this->original_url, $matches );
		if ( $matching ) {
			$host = $matches[2];
			if ( preg_match( '/.*\..*\..*\..*$/', $host ) ) {
				preg_match( $domainPatternUK, $host, $matches );
			} else {
				preg_match( $domainPatternUS, $host, $matches );
			}
			
			if ( isset( $matches[0] ) ) {
				return array( 'domain' => $matches[0], 'host' => $host );
			}
		}
		return false;
	}

	/**
	 * Getting the type for current target.
	 *
	 * @since 6.0.0
	 * @access protected
	 * 
	 * @return string The type of link.
	 */
	protected function get_target_type() {
		$download_extensions = explode( ',', str_replace( '.', '', monsterinsights_get_option( 'extensions_of_files', '' ) ) );
		$download_extensions = array_map( 'trim', $download_extensions );
		$full_url = $this->protocol . '://' . $this->domain['domain'];
		if ( $this->protocol == 'mailto' ) {
			$type = 'email';
		} else if ( in_array( $this->extension, $download_extensions ) ) {
			$type = 'download';
		} else if ( in_array( $this->protocol, array( 'http', 'https') ) && $full_url !== rtrim( home_url(), '\/' ) ) {
			$type = 'outbound';
		} else {
			$type = $this->parse_internal_link_type();
		}
		return $type;
	}
	
	/**
	 * Parse the type for outbound links.
	 *
	 * @since 6.0.0
	 * @access protected
	 *
	 * @return string The type of link.
	 */
	protected function parse_internal_link_type() {
		$out_links = explode( ',', monsterinsights_get_option( 'track_internal_as_outbound', '' ) );
		$out_links = array_unique( array_map( 'trim', $out_links ) );
		if ( ! empty( $this->original_url ) && count( $out_links ) >= 1 ) {
			foreach ( $out_links as $out ) {
				if ( ! empty( $out ) && strpos( $this->original_url, $out ) !== false ) {
					return 'internal-as-outbound';
				}
			}
		}
		return 'internal';
	}
}