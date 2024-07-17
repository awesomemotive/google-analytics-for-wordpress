<?php

/**
 * Emails.
 *
 * This class handles all (notification) emails sent by MonsterInsights.
 *
 * Heavily influenced by the AffiliateWP plugin by Pippin Williamson.
 * https://github.com/AffiliateWP/AffiliateWP/blob/master/includes/emails/class-affwp-emails.php
 *
 * @since 7.10.5
 */
class MonsterInsights_WP_Emails {

	/**
	 * Holds the from address.
	 *
	 * @since 7.10.5
	 *
	 * @var string
	 */
	private $from_address;

	/**
	 * Holds the from name.
	 *
	 * @since 7.10.5
	 *
	 * @var string
	 */
	private $from_name;

	/**
	 * Holds the reply-to address.
	 *
	 * @since 7.10.5
	 *
	 * @var string
	 */
	private $reply_to = false;

	/**
	 * Holds the carbon copy addresses.
	 *
	 * @since 7.10.5
	 *
	 * @var string
	 */
	private $cc = false;

	/**
	 * Holds the email content type.
	 *
	 * @since 7.10.5
	 *
	 * @var string
	 */
	private $content_type;

	/**
	 * Holds the email headers.
	 *
	 * @since 7.10.5
	 *
	 * @var string
	 */
	private $headers;

	/**
	 * Whether to send email in HTML.
	 *
	 * @since 7.10.5
	 *
	 * @var bool
	 */
	private $html = true;

	/**
	 * The email template to use.
	 *
	 * @since 7.10.5
	 *
	 * @var string
	 */
	private $template;

	/**
	 * Header/footer/body arguments.
	 *
	 * @since 7.10.5
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Get things going.
	 *
	 * @since 7.10.5
	 */
	public function __construct( $template ) {
		$this->template = $template;

		$this->set_initial_args();

		add_action( 'monsterinsights_email_send_before', array( $this, 'send_before' ) );
		add_action( 'monsterinsights_email_send_after', array( $this, 'send_after' ) );
	}

	/**
	 * Set a property.
	 *
	 * @param string $key Object property key.
	 * @param mixed $value Object property value.
	 *
	 * @since 7.10.5
	 *
	 */
	public function __set( $key, $value ) {
		$this->$key = $value;
	}

	/**
	 * Get the email from name.
	 *
	 * @return string The email from name
	 * @since 7.10.5
	 *
	 */
	public function get_from_name() {

		if ( ! empty( $this->from_name ) ) {
			$this->from_name = $this->process_tag( $this->from_name );
		} else {
			$this->from_name = get_bloginfo( 'name' );
		}

		return apply_filters( 'monsterinsights_email_from_name', monsterinsights_decode_string( $this->from_name ), $this );
	}

	/**
	 * Get the email from address.
	 *
	 * @return string The email from address.
	 * @since 7.10.5
	 *
	 */
	public function get_from_address() {

		if ( ! empty( $this->from_address ) ) {
			$this->from_address = $this->process_tag( $this->from_address );
		} else {
			$this->from_address = get_option( 'admin_email' );
		}

		return apply_filters( 'monsterinsights_email_from_address', $this->from_address, $this );
	}

	/**
	 * Get the email reply-to.
	 *
	 * @return string The email reply-to address.
	 * @since 7.10.5
	 *
	 */
	public function get_reply_to() {

		if ( ! empty( $this->reply_to ) ) {

			$this->reply_to = $this->process_tag( $this->reply_to );

			if ( ! is_email( $this->reply_to ) ) {
				$this->reply_to = false;
			}
		}

		return apply_filters( 'monsterinsights_email_reply_to', $this->reply_to, $this );
	}

	/**
	 * Get the email carbon copy addresses.
	 *
	 * @return string The email reply-to address.
	 * @since 7.10.5
	 *
	 */
	public function get_cc() {

		if ( ! empty( $this->cc ) ) {

			$this->cc = $this->process_tag( $this->cc );

			$addresses = array_map( 'trim', explode( ',', $this->cc ) );

			foreach ( $addresses as $key => $address ) {
				if ( ! is_email( $address ) ) {
					unset( $addresses[ $key ] );
				}
			}

			$this->cc = implode( ',', $addresses );
		}

		return apply_filters( 'monsterinsights_email_cc', $this->cc, $this );
	}

	/**
	 * Get the email content type.
	 *
	 * @return string The email content type.
	 * @since 7.10.5
	 *
	 */
	public function get_content_type() {

		if ( ! $this->content_type && $this->html ) {
			$this->content_type = apply_filters( 'monsterinsights_email_default_content_type', 'text/html', $this );
		} elseif ( ! $this->html ) {
			$this->content_type = 'text/plain';
		}

		return apply_filters( 'monsterinsights_email_content_type', $this->content_type, $this );
	}

	/**
	 * Get the email headers.
	 *
	 * @return string The email headers.
	 * @since 7.10.5
	 *
	 */
	public function get_headers() {

		if ( ! $this->headers ) {
			$this->headers = "From: {$this->get_from_name()} <{$this->get_from_address()}>\r\n";
			if ( $this->get_reply_to() ) {
				$this->headers .= "Reply-To: {$this->get_reply_to()}\r\n";
			}
			if ( $this->get_cc() ) {
				$this->headers .= "Cc: {$this->get_cc()}\r\n";
			}
			$this->headers .= "Content-Type: {$this->get_content_type()}; charset=utf-8\r\n";
		}

		return apply_filters( 'monsterinsights_email_headers', $this->headers, $this );
	}

	/**
	 * Set initial arguments to use in a template.
	 *
	 * @since 7.10.5
	 */
	public function set_initial_args() {
		$header_args = array(
			'title' => esc_html__( 'MonsterInsights', 'google-analytics-for-wordpress' ),
		);

		$args = array(
			'header' => array(),
			'body'   => array(),
			'footer' => array(),
		);

		$from_address = $this->get_from_address();
		if ( ! empty( $from_address ) ) {
			$args['footer']['from_address'] = $from_address;
		}

		$args = apply_filters( 'monsterinsights_emails_templates_set_initial_args', $args, $this );

		$this->set_args( $args );
	}

	/**
	 * Set header/footer/body/style arguments to use in a template.
	 *
	 * @param array $args Arguments to set.
	 * @param bool $merge Merge the arguments with existing once or replace.
	 *
	 * @return MI_WP_Emails
	 * @since 7.10.5
	 *
	 */
	public function set_args( $args, $merge = true ) {

		$args = apply_filters( 'monsterinsights_emails_templates_set_args', $args, $this );

		if ( empty( $args ) || ! is_array( $args ) ) {
			return $this;
		}

		foreach ( $args as $type => $value ) {

			if ( ! is_array( $value ) ) {
				continue;
			}

			if ( ! isset( $this->args[ $type ] ) || ! is_array( $this->args[ $type ] ) ) {
				$this->args[ $type ] = array();
			}

			$this->args[ $type ] = $merge ? array_merge( $this->args[ $type ], $value ) : $value;
		}

		return $this;
	}

	/**
	 * Get header/footer/body arguments
	 *
	 * @param string $type Header/footer/body.
	 *
	 * @return array
	 * @since 7.10.5
	 *
	 */
	public function get_args( $type ) {
		if ( ! empty( $type ) ) {
			return isset( $this->args[ $type ] ) ? apply_filters( 'monsterinsights_emails_templates_get_args_' . $type, $this->args[ $type ], $this ) : array();
		}

		return apply_filters( 'monsterinsights_emails_templates_get_args', $this->args, $this );
	}

	/**
	 * Build the email.
	 *
	 * @param string $message The email message.
	 *
	 * @return string
	 * @since 7.10.5
	 *
	 */
	public function build_email( $message = null ) {
		// process plain text email
		if ( false === $this->html ) {
			$body    = $this->get_template_part( 'body', $this->get_template(), true );
			$body    = wp_strip_all_tags( $body );
			$message = str_replace( '{email}', $message, $body );

			return apply_filters( 'monsterinsights_email_message', $message, $this );
		}

		// process html email template
		$email_parts           = array();
		$email_parts['header'] = $this->get_template_part( 'header', $this->get_template(), true );

		// Hooks into the email header.
		do_action( 'monsterinsights_email_header', $email_parts['header'] );

		$email_parts['body'] = $this->get_template_part( 'body', $this->get_template(), true );

		// Hooks into the email body.
		do_action( 'monsterinsights_email_body', $email_parts['body'] );

		$email_parts['footer'] = $this->get_template_part( 'footer', $this->get_template(), true );

		// Hooks into the email footer.
		do_action( 'monsterinsights_email_footer', $email_parts['footer'] );

		$body    = implode( $email_parts );
		$message = $this->process_tag( $message, false );
		$message = $message ? nl2br( $message ) : '';
		$message = str_replace( '{email}', $message, $body );

		//$message = make_clickable( $message );

		return apply_filters( 'monsterinsights_email_message', $message, $this );
	}

	/**
	 * Send the email.
	 *
	 * @param string $to The To address.
	 * @param string $subject The subject line of the email.
	 * @param string $message The body of the email.
	 * @param array $attachments Attachments to the email.
	 *
	 * @return bool
	 * @since 7.10.5
	 *
	 */
	public function send( $to, $subject, $message = null, $attachments = array() ) {

		if ( ! did_action( 'init' ) && ! did_action( 'admin_init' ) ) {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'You cannot send emails with MI_WP_Emails() until init/admin_init has been reached.', 'google-analytics-for-wordpress' ), null );

			return false;
		}

		// Don't send anything if emails have been disabled.
		if ( $this->is_email_disabled() ) {
			return false;
		}

		// Don't send if email address is invalid.
		if ( is_string( $to ) && ! is_email( $to ) ) {
			return false;
		}

		// Hooks before email is sent.
		do_action( 'monsterinsights_email_send_before', $this );

		/*
		 * Allow to filter data on per-email basis,
		 * useful for localizations based on recipient email address, form settings,
		 * or for specific notifications - whatever available in MI_WP_Emails class.
		 */
		$data = apply_filters(
			'monsterinsights_emails_send_email_data',
			array(
				'to'          => $to,
				'subject'     => $subject,
				'message'     => $message,
				'headers'     => $this->get_headers(),
				'attachments' => $attachments,
			),
			$this
		);

		// Let's do this.
		$sent = wp_mail(
			$data['to'],
			monsterinsights_decode_string( $this->process_tag( $data['subject'] ) ),
			$this->build_email( $data['message'] ),
			$data['headers'],
			$data['attachments']
		);

		// Hooks after the email is sent.
		do_action( 'monsterinsights_email_send_after', $this );

		return $sent;
	}

	/**
	 * Add filters/actions before the email is sent.
	 *
	 * @since 7.10.5
	 */
	public function send_before() {

		add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );
	}

	/**
	 * Remove filters/actions after the email is sent.
	 *
	 * @since 7.10.5
	 */
	public function send_after() {

		remove_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
		remove_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
		remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );
	}

	/**
	 * Process a smart tag.
	 *
	 * @param string $string String that may contain tags.
	 * @param bool $sanitize Toggle to maybe sanitize.
	 * @param bool $linebreaks Toggle to process linebreaks.
	 *
	 * @return string
	 * @since 7.10.5
	 *
	 */
	public function process_tag( $string = '', $sanitize = true, $linebreaks = false ) {

		$tag = apply_filters( 'monsterinsights_process_smart_tags', $string );

		$tag = monsterinsights_decode_string( $tag );

		if ( $sanitize ) {
			if ( $linebreaks ) {
				$tag = monsterinsights_sanitize_textarea_field( $tag );
			} else {
				$tag = sanitize_text_field( $tag );
			}
		}

		return $tag;
	}

	/**
	 * Email kill switch if needed.
	 *
	 * @return bool
	 * @since 7.10.5
	 *
	 */
	public function is_email_disabled() {
		return (bool) apply_filters( 'monsterinsights_disable_all_emails', false, $this );
	}

	/**
	 * Get the enabled email template.
	 *
	 * @return string When filtering return 'default' to switch to text/plain email.
	 * @since 7.10.5
	 *
	 */
	public function get_template() {

		if ( ! empty( $this->template ) ) {
			$this->template = $this->process_tag( $this->template );
		} else {
			$this->template = 'default';
		}

		return apply_filters( 'monsterinsights_email_template', $this->template );
	}

	/**
	 * Retrieves a template content.
	 *
	 * @param string $slug Template file slug.
	 * @param string $name Optional. Default null.
	 * @param bool $load Maybe load.
	 *
	 * @return string
	 * @since 7.10.5
	 *
	 */
	public function get_template_part( $slug, $name = null, $load = true ) {

		if ( false === $this->html ) {
			$name .= '-plain';
		}

		if ( isset( $name ) ) {
			$template = $slug . '-' . $name;

			$html = $this->get_html(
				$template,
				$this->get_args( $slug ),
				true
			);

			return apply_filters( 'monsterinsights_emails_templates_get_content_part', $html, $template, $this );
		}

	}

	/**
	 * Like $this->include_html, but returns the HTML instead of including.
	 *
	 * @param string $template_name Template name.
	 * @param array $args Arguments.
	 * @param bool $extract Extract arguments.
	 *
	 * @return string
	 * @since 7.10.5
	 *
	 */
	public static function get_html( $template_name, $args = array(), $extract = false ) {
		ob_start();
		self::include_html( $template_name, $args, $extract );

		return ob_get_clean();
	}

	/**
	 * Include a template.
	 * Uses 'require' if $args are passed or 'load_template' if not.
	 *
	 * @param string $template_name Template name.
	 * @param array $args Arguments.
	 * @param bool $extract Extract arguments.
	 *
	 * @throws \RuntimeException If extract() tries to modify the scope.
	 * @since 7.10.5
	 *
	 */
	public static function include_html( $template_name, $args = array(), $extract = false ) {

		$template_name .= '.php';

		// Allow 3rd party plugins to filter template file from their plugin.
		$located = apply_filters( 'monsterinsights_helpers_templates_include_html_located', self::locate_template( $template_name ), $template_name, $args, $extract );
		$args    = apply_filters( 'monsterinsights_helpers_templates_include_html_args', $args, $template_name, $extract );

		if ( empty( $located ) || ! is_readable( $located ) ) {
			return;
		}

		// Load template WP way if no arguments were passed.
		if ( empty( $args ) ) {
			load_template( $located, false );

			return;
		}

		$extract = apply_filters( 'monsterinsights_helpers_templates_include_html_extract_args', $extract, $template_name, $args );

		if ( $extract && is_array( $args ) ) {

			$created_vars_count = extract( $args, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract

			// Protecting existing scope from modification.
			if ( count( $args ) !== $created_vars_count ) {
				throw new RuntimeException( 'Extraction failed: variable names are clashing with the existing ones.' );
			}
		}

		require $located;
	}

	/**
	 * Locate a template and return the path for inclusion.
	 *
	 * @param string $template_name Template name.
	 *
	 * @return string
	 * @since 7.10.5
	 *
	 */
	public static function locate_template( $template_name ) {

		// Trim off any slashes from the template name.
		$template_name = ltrim( $template_name, '/' );

		if ( empty( $template_name ) ) {
			return apply_filters( 'monsterinsights_helpers_templates_locate', '', $template_name );
		}

		$located = '';

		// Try locating this template file by looping through the template paths.
		foreach ( self::get_theme_template_paths() as $template_path ) {
			if ( file_exists( $template_path . $template_name ) ) {
				$located = $template_path . $template_name;
				break;
			}
		}

		return apply_filters( 'monsterinsights_helpers_templates_locate', $located, $template_name );
	}

	/**
	 * Return a list of paths to check for template locations
	 *
	 * @return array
	 * @since 7.10.5
	 *
	 */
	public static function get_theme_template_paths() {

		$template_dir = 'monsterinsights-email';

		$file_paths = array(
			1   => trailingslashit( get_stylesheet_directory() ) . $template_dir,
			10  => trailingslashit( get_template_directory() ) . $template_dir,
			100 => trailingslashit( MONSTERINSIGHTS_PLUGIN_DIR ) . 'includes/emails/templates',
		);

		$file_paths = apply_filters( 'monsterinsights_email_template_paths', $file_paths );

		// Sort the file paths based on priority.
		ksort( $file_paths, SORT_NUMERIC );

		return array_map( 'trailingslashit', $file_paths );
	}
}
