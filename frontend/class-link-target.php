<?php
/**
 * @package GoogleAnalytics\Frontend
 */

/**
 * Class Yoast_GA_Link_Target
 */
class Yoast_GA_Link_Target {

	/**
	 * The category of the link
	 *
	 * @var string category of the link
	 */
	public $category;

	/**
	 * Get the domain and host. False if empty
	 *
	 * @var array|bool
	 */
	public $domain;

	/**
	 * Get the extension of the url
	 *
	 * @var string
	 */
	public $extension;

	/**
	 * Host of the URL
	 *
	 * @var string
	 */
	public $host;

	/**
	 * The link attributes of the URL
	 *
	 * @var string
	 */
	public $link_attributes;

	/**
	 * The text of the link
	 *
	 * @var string
	 */
	public $link_text;

	/**
	 * The full url without the protocol
	 *
	 * @var string
	 */
	public $original_url;

	/**
	 * The protocol of the url
	 *
	 * @var string
	 */
	public $protocol;

	/**
	 * The type of the URL - for example: internal as outbound, outbound, internal
	 *
	 * @var string
	 */
	public $type;

	/**
	 * The full hyperlink
	 *
	 * @var string
	 */
	public $hyperlink;

	/**
	 * Storage for the currently set options
	 * @var array
	 */
	private $options;

	/**
	 * Constructor of the class
	 *
	 * @param string $category
	 * @param array  $matches
	 * @param array  $options
	 */
	public function __construct( $category, array $matches, array $options ) {
		$this->options         = $options;
		$this->category        = $category;
		$this->original_url    = $matches[5];
		$this->domain          = $this->yoast_ga_get_domain();
		$this->extension       = substr( strrchr( $this->original_url, '.' ), 1 );
		$this->host            = $this->domain['host'];
		$this->link_attributes = trim( $matches[1] . ' ' . $matches[6] );
		$this->link_text       = $matches[7];
		$this->hyperlink       = $matches[0];

		$this->protocol = $matches[4];
		$this->type     = $this->get_target_type();


	}

	/**
	 * Parse the domain
	 *
	 * @return array|bool
	 */
	public function yoast_ga_get_domain() {
		$hostPattern     = '/^(https?:\/\/)?([^\/]+)/i';
		$domainPatternUS = '/[^\.\/]+\.[^\.\/]+$/';
		$domainPatternUK = '/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/';

		$matching = preg_match( $hostPattern, $this->original_url, $matches );
		if ( $matching ) {
			$host = $matches[2];
			if ( preg_match( '/.*\..*\..*\..*$/', $host ) ) {
				preg_match( $domainPatternUK, $host, $matches );
			}
			else {
				preg_match( $domainPatternUS, $host, $matches );
			}

			if ( isset( $matches[0] ) ) {
				return array( 'domain' => $matches[0], 'host' => $host );
			}
		}

		return false;
	}

	/**
	 * Getting the type for current target
	 *
	 * @return null|string
	 */
	protected function get_target_type() {
		$download_extensions = explode( ',', str_replace( '.', '', $this->options['extensions_of_files'] ) );
		$download_extensions = array_map( 'trim', $download_extensions );

		$full_url = $this->protocol . '://' . $this->domain['domain'];

		if ( ( $this->protocol == 'mailto' ) ) {
			$type = 'email';
		}
		elseif ( in_array( $this->extension, $download_extensions ) ) {
			$type = 'download';
		}
		elseif ( in_array( $this->protocol, array( 'http', 'https') ) && $full_url !== rtrim( home_url(), '\/' ) ) {
			$type = 'outbound';
		}
		else {
			$type = $this->parse_internal_link_type();
		}

		return $type;
	}

	/**
	 * Parse the type for outbound links
	 *
	 * @return string
	 */
	protected function parse_internal_link_type() {
		$out_links = explode( ',', $this->options['track_internal_as_outbound'] );
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
