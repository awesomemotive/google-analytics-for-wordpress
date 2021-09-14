<?php
/**
 * Used to handle ajax requests specific to popular posts.
 *
 * @package MonsterInsights
 */

/**
 * Class MonsterInsights_Popular_Posts_Ajax
 */
class MonsterInsights_Popular_Posts_Ajax {

	/**
	 * MonsterInsights_Popular_Posts_Ajax constructor.
	 */
	public function __construct() {

		add_action( 'rest_api_init', array( $this, 'register_api_endpoints' ) );

		add_action( 'wp_ajax_monsterinsights_popular_posts_empty_cache', array( $this, 'empty_cache' ) );

		add_action( 'wp_ajax_monsterinsights_popular_posts_get_widget_output', array( $this, 'get_ajax_output' ) );
		add_action( 'wp_ajax_nopriv_monsterinsights_popular_posts_get_widget_output', array(
			$this,
			'get_ajax_output'
		) );

		add_action( 'wp_ajax_monsterinsights_get_popular_posts_themes', array( $this, 'ajax_get_themes' ) );
	}

	/**
	 * Register the wp-json API endpoints for the Gutenberg blocks.
	 */
	public function register_api_endpoints() {

		register_rest_route( 'monsterinsights/v1', '/popular-posts/themes/(?P<type>[a-zA-Z0-9-]+)', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_gutenberg_themes' ),
			'permission_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
			'args'                => array(
				'type' => array(),
			),
		) );

		register_rest_route( 'monsterinsights/v1', '/terms/(?P<slug>[a-zA-Z0-9-_]+)', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_taxonomy_terms' ),
			'permission_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
			'args'                => array(
				'slug' => array(),
			),
		) );

		register_rest_route( 'monsterinsights/v1', '/taxonomy/(?P<slug>[a-zA-Z0-9-_]+)', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_taxonomy' ),
			'permission_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
			'args'                => array(
				'slug' => array(),
			),
		) );
	}

	/**
	 * Get the themes for Gutenberg (use the specific nonce).
	 */
	public function get_gutenberg_themes( $data ) {

		$type = ! empty( $data['type'] ) ? $data['type'] : 'inline';

		return $this->get_themes_by_type( $type );

	}

	/**
	 * Get the themes for a specific type using ajax.
	 */
	public function ajax_get_themes() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : 'inline';

		wp_send_json_success( $this->get_themes_by_type( $type, false ) );

	}

	/**
	 * Helper to get themes by type.
	 *
	 * @param string $type The widget type: inline/widget/products.
	 * @param bool   $styled Whether to style the selected theme or not.
	 *
	 * @return array
	 */
	public function get_themes_by_type( $type, $styled = true ) {
		$theme = '';

		if ( $styled ) {
			if ( 'inline' === $type ) {
				$theme = MonsterInsights_Popular_Posts_Inline()->theme;
			}

			if ( 'widget' === $type ) {
				$theme = MonsterInsights_Popular_Posts_Widget()->theme;
			}
		}
		$themes       = $this->get_themes( $type, $theme );
		$themes_array = $themes->themes;

		if ( isset( $themes_array[ $theme ] ) && $styled ) {
			$themes_array[ $theme ] = $themes->get_theme();
		}

		$response = array(
			'themes'   => $themes_array,
			'selected' => $theme,
		);

		return $response;
	}

	/**
	 * Get themes by type.
	 *
	 * @param string $type The widget type: inline/widget/products.
	 * @param string $theme The selected theme.
	 *
	 * @return MonsterInsights_Popular_Posts_Themes
	 */
	public function get_themes( $type, $theme ) {

		$themes_object = new MonsterInsights_Popular_Posts_Themes( $type, $theme );

		return $themes_object;
	}

	/**
	 * Get a specific theme details.
	 *
	 * @param string $type The widget type: inline/widget/products.
	 * @param string $theme The selected theme.
	 *
	 * @return array|mixed
	 */
	public function get_theme_details( $type, $theme ) {

		$themes = new MonsterInsights_Popular_Posts_Themes( $type, $theme );

		return $themes->get_theme();

	}

	/**
	 * Handler for loading taxonomy terms using a custom endpoint.
	 *
	 * @param array $data Data passed from the request.
	 *
	 * @return array
	 */
	public function get_taxonomy_terms( $data ) {

		$slug = ! empty( $data['slug'] ) ? $data['slug'] : 'category';

		$terms = get_terms( array(
			'taxonomy' => $slug,
		) );

		$return = array();

		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$return[] = array(
					'id'     => $term->term_id,
					'name'   => $term->name,
					'parent' => $term->parent,
				);
			}
		}

		return $return;

	}

	/**
	 * Get details for a taxonomy so we can use it in the Gutenberg block.
	 *
	 * @param array $data Data passed from the request.
	 *
	 * @return false|WP_Taxonomy
	 */
	public function get_taxonomy( $data ) {

		$slug = ! empty( $data['slug'] ) ? $data['slug'] : 'category';

		return get_taxonomy( $slug );

	}

	/**
	 * Ajax handler to empty the Popular Posts cache for all instances.
	 */
	public function empty_cache() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
			return;
		}

		$types = array(
			'inline',
			'widget',
			'products',
		);

		foreach ( $types as $type ) {
			delete_option( 'monsterinsights_popular_posts_cache_' . $type );
		}

		wp_send_json_success();

	}

	/**
	 * Ajax handler to get the output for Popular Posts widgets from the JSON data on the frontend.
	 */
	public function get_ajax_output() {

		if ( empty( $_POST['data'] ) || ! is_array( $_POST['data'] ) ) {
			return;
		}

		$html         = array();
		$widgets_args = $_POST['data'];

		foreach ( $widgets_args as $args ) {
			$args = json_decode( sanitize_text_field( wp_unslash( $args ) ), true );
			if ( ! empty( $args['type'] ) ) {
				$type            = ucfirst( $args['type'] );
				$widget_function = function_exists( 'MonsterInsights_Popular_Posts_' . $type ) ? call_user_func( 'MonsterInsights_Popular_Posts_' . $type ) : false;
				if ( $widget_function ) {
					$html[] = $widget_function->get_rendered_html( $args );
				}
			}
		}

		wp_send_json( $html );
	}

}

new MonsterInsights_Popular_Posts_Ajax();
