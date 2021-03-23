<?php
/**
 * This is the base class for the Popular Posts output functionality.
 * Each actual Popular Posts option extends this class (inline, widget, products).
 *
 * @package MonsterInsights
 */

/**
 * Class MonsterInsights_Popular_Posts
 */
class MonsterInsights_Popular_Posts {

	/**
	 * The key prefix used to store the settings for the magic __get method.
	 *
	 * @var string
	 */
	protected $settings_key;

	/**
	 * Name of the shortcode
	 *
	 * @var string
	 */
	protected $shortcode_key;

	/**
	 * The popular posts object type, by default inline, widget or products.
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * An array of posts used in the query process.
	 *
	 * @var array
	 */
	public $posts = array();

	/**
	 * An array of posts already displayed. Used to avoid duplicate posts on the same page.
	 *
	 * @var array
	 */
	public $shown_posts = array();

	/**
	 * The inline styles string with theme specifics from the Vue settings.
	 * Each instance should append to this variable so we print styles for all the instances in the same place.
	 *
	 * @var string
	 */
	public static $inline_styles = '';

	/**
	 * Stores the option to use ajax to display the popular posts widgets on the frontend.
	 *
	 * @var string
	 */
	public $ajaxify;

	/**
	 * Stores the cache instance, specific to the plugin version.
	 *
	 * @var MonsterInsights_Popular_Posts_Cache
	 */
	public $cache;

	/**
	 * Holds the class object.
	 *
	 * @since 7.13.0
	 * @access public
	 * @var array
	 */
	public static $instances = array();

	/**
	 * @var MonsterInsights_Popular_Posts_Themes
	 */
	protected $theme_props;

	/**
	 * Indicator that inline styles have been printed to avoid duplicates.
	 *
	 * @var bool
	 */
	private static $styles_printed = false;

	/**
	 * Number of posts to query from the db. Not all queried posts are used for display in the same widget.
	 *
	 * @var int
	 */
	public $posts_count = 15;

	/**
	 * MonsterInsights_Popular_Posts constructor.
	 */
	public function __construct() {

		$this->hooks();
		$this->register_shortcode();

		$this->ajaxify = monsterinsights_get_option( 'popular_posts_ajaxify', false );
	}

	/**
	 * Magic get for different types of popular posts.
	 *
	 * @param $name
	 *
	 * @return string|array|mixed
	 */
	public function __get( $name ) {
		return monsterinsights_get_option( $this->settings_key . '_' . $name );
	}

	/**
	 * Add hooks needed for the output.
	 */
	public function hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_styles' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_load_ajaxify_script' ) );

		$this->add_inline_styles();
	}

	/**
	 * Add inline styles for each widget type to a single variable for printing.
	 */
	protected function add_inline_styles() {
		if ( 'no_styles' !== $this->styling ) {
			self::$inline_styles .= $this->build_inline_styles();
		}
	}

	/**
	 * Should return object-specific inline styles.
	 *
	 * @return string
	 */
	public function build_inline_styles() {
		return '';
	}

	/**
	 * Register the shortcode for the specific class.
	 */
	public function register_shortcode() {

		if ( ! empty( $this->shortcode_key ) ) {
			add_shortcode( $this->shortcode_key, array( $this, 'render_shortcode' ) );
		}

	}

	/**
	 * Load the frontend styles if they are enabled.
	 */
	public function load_frontend_styles() {

		// Only load our styles if enabled.
		if ( apply_filters( 'monsterinsights_popular_posts_styles_output', 'no_styles' === $this->styling, $this ) ) {
			return;
		}
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Load Popular Posts styles.
		wp_register_style( 'monsterinsights-popular-posts-style', plugins_url( 'assets/css/frontend' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );

		$this->add_theme_specific_styles();

	}

	/**
	 * If the Ajaxify option is enabled, print needed scripts.
	 */
	public function maybe_load_ajaxify_script() {
		if ( ! $this->ajaxify ) {
			return;
		}
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_register_script( 'monsterinsights-popular-posts-js', plugins_url( 'assets/js/popular-posts' . $suffix . '.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version(), true );

		wp_enqueue_script( 'monsterinsights-popular-posts-js' );

		wp_localize_script( 'monsterinsights-popular-posts-js', 'monsterinsights_pp', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'post_id' => get_the_ID(),
		) );

	}

	/**
	 * Add inline styles based on customizations from the vue panel.
	 */
	public function add_theme_specific_styles() {

		if ( ! self::$styles_printed ) {
			wp_add_inline_style( 'monsterinsights-popular-posts-style', $this->get_inline_styles() );
			self::$styles_printed = true;
		}

	}

	/**
	 * We have a single static variable for inline styles shared by all instances so we print just once.
	 *
	 * @return string
	 */
	public function get_inline_styles() {
		return self::$inline_styles;
	}

	/**
	 * Rendering the shortcode.
	 *
	 * @return string
	 */
	public function render_shortcode( $args ) {

		return apply_filters( 'monsterinsights_popular_posts_shortcode_output', $this->shortcode_output( $args ), $args, $this );

	}

	/**
	 * Output of shortcode based on settings.
	 *
	 * @param array $args Arguments from shortcode/block.
	 *
	 * @return string
	 */
	public function shortcode_output( $args ) {
		// Load frontend.css file when shortcode is available
		wp_enqueue_style( 'monsterinsights-popular-posts-style' );

		if ( $this->ajaxify ) {
			return $this->get_ajax_json_data( $args );
		} else {
			return $this->get_rendered_html( $args );
		}
	}

	/**
	 * Print inline JSON data that with settings that get processed using an AJAX call. Acts similar to printing out
	 * a shortcode with its settings but actually loading the output for that after the page was loaded, with AJAX.
	 *
	 * @param array $args Arguments from shortcode/block.
	 *
	 * @return string
	 */
	public function get_ajax_json_data( $args ) {

		$args['type'] = $this->type;

		$data = '<div><script type="application/json" class="monsterinsights-popular-posts-widget-json">';
		$data .= wp_json_encode( $args );
		$data .= '</script></div>';

		return $data;
	}

	/**
	 * This is replaced with actual HTML output in child classes.
	 *
	 * @param array $args Arguments used to build specific html.
	 *
	 * @return string
	 */
	public function get_rendered_html( $args ) {
		return '';
	}

	/**
	 * Get the cache instance for the set type.
	 *
	 * @return MonsterInsights_Popular_Posts_Cache
	 */
	public function get_cache() {
		if ( ! isset( $this->cache ) ) {
			$this->cache = new MonsterInsights_Popular_Posts_Cache( $this->type );
		}

		return $this->cache;
	}

	/**
	 * Use the query args to grab posts from the database.
	 */
	public function get_posts() {

		$posts_args = $this->get_query_args();

		$posts = $this->get_cache()->get_cached_posts( $posts_args );

		if ( empty( $posts ) ) {

			if ( isset( $posts_args['post__in'] ) && empty( $posts_args['post__in'] ) ) {
				$this->posts = array();

				return $this->posts;
			}
			$posts = get_posts( $posts_args );

			$posts = $this->process_posts( $posts );

			$this->get_cache()->save_posts_to_cache( $posts_args, $posts );
		}

		return apply_filters( 'monsterinsights_popular_posts_posts', $posts );

	}

	/**
	 * Go through posts from a WP Query and prepare them for output.
	 *
	 * @param array $posts Array of posts from WP Query or similar, also supports array of ids.
	 *
	 * @return array
	 */
	private function process_posts( $posts ) {
		$processed_posts = array();
		foreach ( $posts as $post ) {
			if ( is_int( $post ) ) {
				$post = get_post( $post );
			}
			$post_thumbnail    = get_post_thumbnail_id( $post->ID );
			$post_image        = '';
			$post_image_srcset = '';
			if ( ! empty( $post_thumbnail ) ) {
				$post_image = wp_get_attachment_image_src( $post_thumbnail, 'small' );
				if ( is_array( $post_image ) && ! empty( $post_image[0] ) ) {
					$post_image = $post_image[0];
				}
				$post_image_srcset = wp_get_attachment_image_srcset( $post_thumbnail, 'small' );
			}

			$author_data = get_userdata( $post->post_author );

			$processed_posts[] = array(
				'id'          => $post->ID,
				'title'       => get_the_title( $post->ID ),
				'link'        => get_permalink( $post->ID ),
				'image'       => $post_image,
				'srcset'      => $post_image_srcset,
				'image_id'    => $post_thumbnail,
				'author'      => $post->post_author,
				'author_name' => $author_data->display_name,
				'date'        => get_the_date( '', $post->ID ),
				'comments'    => get_comments_number( $post->ID ),
			);
		}

		return $processed_posts;
	}

	/**
	 * Get the query args for grabbing the posts. This should probably get overwritten in child classes.
	 *
	 * @return mixed|void
	 */
	private function get_query_args() {

		$args = array(
			'numberposts'         => $this->posts_count,
			'ignore_sticky_posts' => true,
		);
		$args = wp_parse_args( $this->query_args(), $args );

		return apply_filters( 'monsterinsights_popular_posts_query_args', $args );
	}

	/**
	 * Set the query args specific to this instance.
	 *
	 * @return array
	 */
	protected function query_args() {

		if ( 'comments' === $this->sort ) {
			return $this->get_query_args_comments();
		} elseif ( 'sharedcount' === $this->sort ) {
			return $this->get_query_args_sharedcount();
		} elseif ( 'curated' === $this->sort ) {
			return $this->get_query_args_curated();
		}

	}


	/**
	 * Get the query args for ordering by comments.
	 *
	 * @return array
	 */
	protected function get_query_args_comments() {

		$query_args = array(
			'orderby' => 'comment_count',
			'order'   => 'DESC',
		);

		return $query_args;
	}

	/**
	 * Get the query args for ordering by sharedcount.
	 *
	 * @return array
	 */
	protected function get_query_args_sharedcount() {

		$query_args = array(
			'orderby'  => 'meta_value_num',
			'order'    => 'DESC',
			'meta_key' => '_monsterinsights_sharedcount_total',
		);

		return $query_args;
	}


	/**
	 * Build the query args for the curated option from the settings in the panel.
	 *
	 * @return array
	 */
	protected function get_query_args_curated() {

		$posts   = $this->curated;
		$post_in = array();

		if ( ! empty( $posts ) && is_array( $posts ) ) {
			foreach ( $posts as $post ) {
				if ( ! empty( $post['id'] ) ) {
					$post_in[] = intval( $post['id'] );
				}
			}
		}

		$query_args = array(
			'post__in' => $post_in,
		);

		return $query_args;
	}

	/**
	 * Load theme props for the specific instance.
	 *
	 * @param string $theme Theme key.
	 *
	 * @return MonsterInsights_Popular_Posts_Themes
	 */
	public function get_theme_props( $theme = '' ) {

		if ( empty( $theme ) ) {
			$theme = $this->theme;
		}
		$theme_props = new MonsterInsights_Popular_Posts_Themes( $this->type, $theme );

		return $theme_props;
	}

	/**
	 * Marks a post as already displayed, by id.
	 *
	 * @param $id
	 */
	public function set_post_shown( $id ) {
		if ( ! in_array( $id, $this->shown_posts, true ) ) {
			$this->shown_posts[] = $id;
		}
	}

	/**
	 * Returns an array of posts that were already displayed on the current page.
	 *
	 * @return array
	 */
	public function get_shown_posts() {

		return $this->shown_posts;

	}

	/**
	 * Generic helper function to build style attributes for elements based on shortcode/block parameters.
	 *
	 * @param string $theme The theme for which  we're building the style.
	 * @param string $object Object we're styling like title, label, background, etc.
	 * @param array  $atts Attributes passed from shortcode/block.
	 * @param string $key The key of the style we're going to output.
	 *
	 * @return string
	 */
	public function get_element_style( $theme, $object, $atts, $key = '' ) {

		if ( 'no_styles' === $this->styling ) {
			// If no styles is selected don't output any styles.
			return '';
		}

		if ( empty( $theme ) ) {
			$theme = $this->theme;
		}

		// Find theme-specific available options and check if our attributes have those set.
		$theme_styles = $this->get_theme_props( $theme )->get_theme();
		$style_output = '';
		$style_css    = '';

		if ( ! empty( $theme_styles['styles'] ) ) {
			foreach ( $theme_styles['styles'] as $element => $options ) {
				if ( $object !== $element ) {
					continue;
				}
				foreach ( $options as $style_key => $value ) {
					$atts_key = $element . '_' . $style_key;

					if ( ! empty( $key ) && $key !== $style_key ) {
						// Allow output for just a specific key.
						continue;
					}

					if ( ! empty( $atts[ $atts_key ] ) ) {
						if ( is_bool( $atts[ $atts_key ] ) || 'on' === $atts[ $atts_key ] ) {
							continue;
						}
						if ( 'size' === $style_key ) {
							$style_key         = 'font-size';
							$atts[ $atts_key ] .= 'px';
						}
						if ( 'background' === $style_key || 'background' === $element && 'color' === $style_key ) {
							$style_key = 'background-color';
						}
						if ( 'border' === $element || 'border' === $style_key ) {
							$style_key = 'border-color';
						}
						$style_css .= $style_key . ':' . $atts[ $atts_key ] . ';';
					}
				}
			}
		}

		if ( ! empty( $style_css ) ) {
			$style_output = 'style="' . $style_css . '"';
		}

		return $style_output;

	}

	/**
	 * Get the current instance based on the called class.
	 *
	 * @return mixed
	 */
	public static function get_instance() {

		if ( ! function_exists( 'get_called_class' ) ) {
			return false;
		}

		$class = get_called_class();

		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new $class;
		}

		return self::$instances[ $class ];

	}

	/**
	 * Check if the post is excluded from loading the widget.
	 *
	 * @param null|WP_Post $post The post to check if it's excluded.
	 *
	 * @return bool
	 */
	public function is_post_excluded( $post = null ) {
		if ( is_null( $post ) ) {
			$post = get_post( get_the_ID() );
		}
		$excluded = false;

		$posts_to_exclude = $this->exclude_posts;
		if ( ! empty( $posts_to_exclude ) ) {
			$post_ids = array();
			foreach ( $posts_to_exclude as $exclude_post ) {
				if ( ! empty( $exclude_post['id'] ) ) {
					$post_ids[] = intval( $exclude_post['id'] );
				}
			}

			if ( in_array( $post->ID, $post_ids, true ) ) {
				$excluded = true;
			}
		}

		return $excluded;
	}

	/**
	 * Build a wrapper class based on theme, instance and some settings.
	 *
	 * @param array $atts Attributes of the shortcode/instance to process for output.
	 *
	 * @return string
	 */
	public function get_wrapper_class( $atts ) {
		$theme = $this->theme;
		if ( ! empty( $atts['theme'] ) ) {
			$theme = $atts['theme'];
		}
		$columns = ! empty( $atts['columns'] ) ? $atts['columns'] : $this->theme_columns;
		$classes = array(
			'monsterinsights-' . $this->type . '-popular-posts',
			'monsterinsights-' . $this->type . '-popular-posts-' . $theme,
			'no_styles' !== $this->styling ? 'monsterinsights-popular-posts-styled' : '',
		);

		if ( $columns ) {
			$classes[] = 'monsterinsights-' . $this->type . '-popular-posts-columns-' . $columns;
		}

		if ( isset( $atts['className'] ) ) {
			$classes[] = $atts['className'];
		}

		$classname = implode( ' ', $classes );

		return $classname;
	}

	/**
	 * Check if the id is of the currently displayed post. Compatible with the Ajaxify functionality.
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function is_current_post( $id ) {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$current_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;

			return $id === $current_id;
		}

		// Only run this check for singular pages.
		if ( ! is_singular() ) {
			return false;
		}

		return get_the_ID() === absint( $id );

	}

	/**
	 * Helper function that checks if a post should be displayed on the current page.
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	public function should_display_post( $id ) {
		$shown = $this->get_shown_posts();
		if ( in_array( $id, $shown, true ) ) {
			return false;
		}
		if ( $this->is_current_post( $id ) ) {
			return false;
		}

		return true;
	}

	/**
	 * This function grabs the posts from the cache or a fresh query and runs them through a check if they should be
	 * displayed on the current page to avoid duplicates.
	 *
	 * @return array
	 */
	public function get_posts_to_display() {
		$posts = $this->get_posts();

		$returned_posts = array();

		foreach ( $posts as $post ) {
			if ( $this->should_display_post( $post['id'] ) ) {
				$returned_posts[] = $post;
			}
		}

		if ( apply_filters( 'monsterinsights_popular_posts_show_duplicates', true ) && count( $posts ) > 0 && count( $this->shown_posts ) > 0 && count( $returned_posts ) === 0 ) {
			$this->shown_posts = array(); // Reset shown posts.
			return $this->get_posts_to_display(); // Run the function to grab the same posts again.
		}

		return $returned_posts;
	}

	/**
	 * Check if the current instance has any posts available to display.
	 *
	 * @param array $posts Posts array to check if still available for display.
	 *
	 * @return bool
	 */
	public function has_posts_to_show( $posts ) {

		foreach ( $posts as $post ) {
			if ( $this->should_display_post( $post['id'] ) ) {
				return true;
			}
		}

		return false;

	}

	/**
	 * Only inline styles that were customized for the specific instance.
	 *
	 * @return array
	 */
	public function get_themes_styles_for_output() {

		$stored_styles = $this->get_theme_props()->get_theme_stored_styles();
		$themes        = ! empty( $stored_styles[ $this->type ] ) && is_array( $stored_styles[ $this->type ] ) ? $stored_styles[ $this->type ] : array();

		return $themes;

	}
}
