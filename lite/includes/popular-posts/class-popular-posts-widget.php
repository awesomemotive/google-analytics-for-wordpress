<?php
/**
 * Code specific to the widget Popular Posts widget type.
 */

/**
 * Class MonsterInsights_Popular_Posts_Widget
 */
class MonsterInsights_Popular_Posts_Widget extends MonsterInsights_Popular_Posts {

	/**
	 * The instance type. Used for loading specific settings.
	 *
	 * @var string
	 */
	protected $type = 'widget';

	/**
	 * Used to load the setting specific for this class.
	 *
	 * @var string
	 */
	protected $settings_key = 'popular_posts_widget';

	/**
	 * Used for registering the shortcode specific to this class.
	 *
	 * @var string
	 */
	protected $shortcode_key = 'monsterinsights_popular_posts_widget';

	/**
	 * Widget-specific hooks.
	 */
	public function hooks() {
		parent::hooks();

		add_action( 'wp', array( $this, 'maybe_auto_insert' ) );

		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		add_filter( 'widget_types_to_hide_from_legacy_widget_block', array(
			$this,
			'remove_widget_from_legacy_widgets'
		) );
	}


	/**
	 * Register Popular Posts widget.
	 */
	public function register_widget() {
		register_widget( 'MonsterInsights_Popular_Posts_Widget_Sidebar' );
	}

	/**
	 * Get the rendered HTML for output.
	 *
	 * @param array $atts These are attributes used to build the specific instance, they can be either shortcode
	 * attributes or Gutenberg block props.
	 *
	 * @return string
	 */
	public function get_rendered_html( $atts ) {

		$theme = $this->theme;
		if ( ! empty( $atts['theme'] ) ) {
			$theme = $atts['theme'];
		}

		$theme = $this->is_theme_available( $theme );

		if ( ! empty( $atts['post_count'] ) ) {
			$limit = intval( $atts['post_count'] );
		} else {
			$limit = $this->count;
		}

		$posts = $this->get_posts_to_display();

		if ( empty( $posts ) ) {
			return '';
		}

		if ( 'curated' === $this->sort && apply_filters( 'monsterinsights_popular_posts_widget_curated_shuffle', true ) ) {
			// Randomize the order.
			shuffle( $posts );
		}

		$theme_styles = $this->get_theme_props( $theme )->get_theme();

		$label_text = '';
		if ( isset( $theme_styles['styles']['label'] ) ) {
			$label_text = isset( $atts['label_text'] ) ? esc_html($atts['label_text']) : esc_html($theme_styles['styles']['label']['text']);
		}

		if ( isset( $atts['widget_title'] ) ) {
			$show_title = (bool) $atts['widget_title'];
			$title_text = empty( $atts['widget_title_text'] ) ? '' : $atts['widget_title_text'];
		} else {
			$show_title = $this->title;
			$title_text = $this->title_text;
		}

		$html = '<div class="' . esc_attr($this->get_wrapper_class( $atts )) . '">';
		if ( $show_title ) {
			$html .= '<h2 class="monsterinsights-widget-popular-posts-widget-title">' . esc_html( $title_text ) . '</h2>';
		}

		$html .= '<ul class="monsterinsights-widget-popular-posts-list">';

		$display_count = 0;
		foreach ( $posts as $post ) {
			$display_count ++;
			if ( $display_count > $limit ) {
				break;
			}
			$this->set_post_shown( $post['id'] );
			$html .= '<li ';
			$html .= ! empty( $this->get_element_style( $theme, 'background', $atts ) ) ? 'style="' . esc_attr( $this->get_element_style( $theme, 'background', $atts ) ) . '"' : '';
			$html .= '>';
			$html .= '<a href="' . esc_url($post['link']) . '">';
			if ( ! empty( $theme_styles['image'] ) && ! empty( $post['image'] ) ) {
				$html .= '<div class="monsterinsights-widget-popular-posts-image">';
				$html .= '<img src="' . esc_url($post['image']) . '" srcset=" ' . esc_attr($post['srcset']) . ' " alt="' . esc_attr( $post['title'] ) . '" />';
				$html .= '</div>';
			}
			$html .= '<div class="monsterinsights-widget-popular-posts-text">';
			if ( isset( $theme_styles['styles']['label'] ) ) {
				$html .= '<span class="monsterinsights-widget-popular-posts-label" ';
				$html .= ! empty( $this->get_element_style( $theme, 'label', $atts ) ) ? 'style="' . esc_attr( $this->get_element_style( $theme, 'label', $atts ) ) . '"' : '';
				$html .= '>' . esc_html( $label_text ) . '</span>';
			}
			$html .= '<span class="monsterinsights-widget-popular-posts-title" ';
			$html .= ! empty( $this->get_element_style( $theme, 'title', $atts ) ) ? 'style="' . esc_attr( $this->get_element_style( $theme, 'title', $atts ) ) . '"' : '';
			$html .= '>' . esc_html( $post['title'] ) . '</span>';
			$html .= '</div>'; // monsterinsights-widget-popular-posts-text.
			$html .= '</a>';
			$html .= '</li>';
		}

		$html .= '</ul></div><p></p>';// Main div.

		return $html;

	}

	/**
	 * Add widget-specific styles based on theme settings.
	 */
	public function build_inline_styles() {

		$themes = $this->get_themes_styles_for_output();
		$styles = '';

		foreach ( $themes as $theme_key => $theme_styles ) {

			if ( ! empty( $theme_styles['background'] ) ) {
				$styles .= '.monsterinsights-popular-posts-styled.monsterinsights-widget-popular-posts.monsterinsights-widget-popular-posts-' . esc_attr($theme_key) . ' .monsterinsights-widget-popular-posts-list li {';

				if ( ! empty( $theme_styles['background']['color'] ) ) {
					$styles .= 'background-color:' . esc_attr($theme_styles['background']['color']) . ';';
				}
				if ( ! empty( $theme_styles['background']['border'] ) ) {
					$styles .= 'border-color:' . esc_attr($theme_styles['background']['border']) . ';';
				}

				$styles .= '}';
			}

			if ( ! empty( $theme_styles['label'] ) ) {
				$styles .= '.monsterinsights-popular-posts-styled.monsterinsights-widget-popular-posts.monsterinsights-widget-popular-posts-' . esc_attr($theme_key) . ' .monsterinsights-widget-popular-posts-label {';

				if ( ! empty( $theme_styles['label']['color'] ) ) {
					$styles .= 'color:' . esc_attr($theme_styles['label']['color']) . ';';
				}

				if ( ! empty( $theme_styles['label']['background'] ) ) {
					$styles .= 'background-color:' . esc_attr($theme_styles['label']['background']) . ';';
				}

				$styles .= '}';
			}

			if ( ! empty( $theme_styles['title'] ) ) {
				$styles .= '.monsterinsights-popular-posts-styled.monsterinsights-widget-popular-posts.monsterinsights-widget-popular-posts-' . esc_attr($theme_key) . ' .monsterinsights-widget-popular-posts-list li .monsterinsights-widget-popular-posts-title {';

				if ( ! empty( $theme_styles['title']['color'] ) ) {
					$styles .= 'color:' . esc_attr($theme_styles['title']['color']) . ';';
				}
				if ( ! empty( $theme_styles['title']['size'] ) ) {
					$styles .= 'font-size:' . esc_attr($theme_styles['title']['size']) . 'px;';
				}

				$styles .= '}';
			}

			if ( ! empty( $theme_styles['border'] ) ) {
				$styles .= '.monsterinsights-popular-posts-styled.monsterinsights-widget-popular-posts-' . esc_attr($theme_key) . ' .monsterinsights-inline-popular-posts-border {';

				if ( ! empty( $theme_styles['border']['color'] ) ) {
					$styles .= 'border-color:' . esc_attr($theme_styles['border']['color']) . ';';
				}

				$styles .= '}';
			}
		}

		return $styles;
	}

	/**
	 * Check if we should attempt to automatically insert the inline widget.
	 */
	public function maybe_auto_insert() {

		$post_types = $this->post_types;
		if ( ! empty( $post_types ) && is_singular( $post_types ) && $this->automatic ) {
			add_filter( 'the_content', array( $this, 'add_inline_posts_to_content' ) );
		}

	}

	/**
	 * Insert the widget in the content.
	 *
	 * @param string $content The post content.
	 *
	 * @return string
	 */
	public function add_inline_posts_to_content( $content ) {

		if ( $this->is_post_excluded() ) {
			return $content;
		}

		$content .= $this->shortcode_output( array() );

		return $content;
	}

	/**
	 * Check if the selected theme is available with the current license to avoid showing a theme not available.
	 * Returns the default 'alpha' theme if not available.
	 *
	 * @param string $theme Theme slug for which we are checking.
	 *
	 * @return string
	 */
	public function is_theme_available( $theme ) {

		$theme_props = $this->get_theme_props( $theme )->get_theme();

		if ( ! empty( $theme_props['level'] ) && 'lite' === $theme_props['level'] ) {
			return $theme;
		}

		return 'alpha';

	}

	/**
	 * Remove this widget from legacy widgets not to have duplications.
	 *
	 * @param string[] $widgets An array of excluded widget-type IDs.
	 *
	 * @return mixed
	 */
	public function remove_widget_from_legacy_widgets( $widgets ) {
		// $widgets[] = 'monsterinsights-popular-posts-widget';

		return $widgets;
	}

}

/**
 * Get the current class in a function.
 *
 * @return MonsterInsights_Popular_Posts_Widget Instance of the current class.
 */
function MonsterInsights_Popular_Posts_Widget() {
	return MonsterInsights_Popular_Posts_Widget::get_instance();
}

MonsterInsights_Popular_Posts_Widget();
