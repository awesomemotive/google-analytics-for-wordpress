<?php
/**
 * Gutenberg Blocks registration class.
 *
 * @since 7.13.9
 *
 * @package MonsterInsights
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gutenberg Blocks registration class.
 *
 * @since 7.13.0
 */
class MonsterInsights_Blocks {

	/**
	 * Holds the class object.
	 *
	 * @since 7.13.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 7.13.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 7.13.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 7.13.0
	 */
	public function __construct() {

		if ( function_exists( 'register_block_type' ) ) {

			// Set our object.
			$this->set();
			$this->register_blocks();
		}

	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 7.13.0
	 */
	public function set() {
		self::$instance = $this;
	}

	/**
	 * Register MonsterInsights Gutenberg blocks on the backend.
	 *
	 * @since 7.13.0
	 */
	public function register_blocks() {
		register_block_type(
			'monsterinsights/popular-posts-inline',
			array(
				'attributes'      => array(
					'slug'        => array(
						'type' => 'string',
					),
					'followrules' => array(
						'type' => 'boolean',
					),
				),
				'render_callback' => array( $this, 'popular_posts_inline_output' ),
			)
		);
		register_block_type(
			'monsterinsights/popular-posts-widget',
			array(
				'attributes'      => array(
					'slug'        => array(
						'type' => 'string',
					),
					'followrules' => array(
						'type' => 'boolean',
					),
				),
				'render_callback' => array( $this, 'popular_posts_widget_output' ),
			)
		);
	}

	/**
	 * Get form HTML to display in a MonsterInsights Gutenberg block.
	 *
	 * @param array $atts Attributes passed by MonsterInsights Gutenberg block.
	 *
	 * @return string
	 * @since 7.13.0
	 *
	 */
	public function popular_posts_inline_output( $atts ) {

		$output = MonsterInsights_Popular_Posts_Inline()->shortcode_output( $atts );

		return $output;
	}

	/**
	 * Get form HTML to display in a MonsterInsights Gutenberg block.
	 *
	 * @param array $atts Attributes passed by MonsterInsights Gutenberg block.
	 *
	 * @return string
	 * @since 7.13.0
	 */
	public function popular_posts_widget_output( $atts ) {

		$atts   = $this->add_default_values( $atts );
		$output = MonsterInsights_Popular_Posts_Widget()->shortcode_output( $atts );

		return $output;
	}

	/**
	 * This ensures that what is displayed as default in the Gutenberg block is reflected in the output.
	 *
	 * @param array $atts The attributes from Gutenberg.
	 *
	 * @return array
	 */
	private function add_default_values( $atts ) {

		$default_values = array(
			'columns'      => 1,
			'widget_title' => false,
		);

		return wp_parse_args( $atts, $default_values );

	}
}

new MonsterInsights_Blocks();
