<?php
/**
 * Code specific to the inline Popular Posts widget type.
 */

/**
 * Class MonsterInsights_Popular_Posts_Inline
 */
class MonsterInsights_Popular_Posts_Inline extends MonsterInsights_Popular_Posts {

	/**
	 * Used to load the setting specific for this class.
	 *
	 * @var string
	 */
	protected $settings_key = 'popular_posts_inline';

	/**
	 * Used for registering the shortcode specific to this class.
	 *
	 * @var string
	 */
	protected $shortcode_key = 'monsterinsights_popular_posts_inline';

	/**
	 * The instance type. Used for loading specific settings.
	 *
	 * @var string
	 */
	protected $type = 'inline';

	/**
	 * Inline-specific hooks.
	 */
	public function hooks() {
		parent::hooks();

		add_action( 'wp', array( $this, 'maybe_auto_insert' ) );
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

		$posts = $this->get_posts_to_display();

		if ( empty( $posts ) ) {
			return '';
		}

		if ( 'curated' === $this->sort ) {
			// Randomize the order.
			shuffle( $posts );
		}

		$theme_styles = $this->get_theme_props( $theme )->get_theme();
		$limit        = ! empty( $theme_styles['posts'] ) ? $theme_styles['posts'] : 1;

		$label_text = '';
		if ( isset( $theme_styles['styles']['label'] ) ) {
			$label_text = isset( $atts['labelText'] ) ? $atts['labelText'] : $theme_styles['styles']['label']['text'];
		}

		// Wrap in a P tag to keep the same spacing.
		$html = '<div class="' . $this->get_wrapper_class( $atts ) . '" ' . $this->get_element_style( $theme, 'background', $atts ) . '>';

		if ( ! empty( $theme_styles['image'] ) && ! empty( $posts[0]['image'] ) ) {
			$html .= '<div class="monsterinsights-inline-popular-posts-image">';
			$html .= '<img src="' . $posts[0]['image'] . '" srcset=" ' . $posts[0]['srcset'] . ' " />';
			$html .= '</div>';
		}
		$html .= '<div class="monsterinsights-inline-popular-posts-text">';
		if ( ! empty( $theme_styles['styles']['icon'] ) ) {
			$html .= '<span class="monsterinsights-inline-popular-posts-icon" style=""><svg width="14" height="19" viewBox="0 0 14 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.875 0.899463C7.875 1.59183 8.0816 2.24711 8.49479 2.8653C8.93229 3.48349 9.44271 4.06458 10.026 4.60859C10.6337 5.15259 11.2292 5.73369 11.8125 6.35188C12.4201 6.97007 12.9306 7.76135 13.3438 8.72572C13.7812 9.66537 14 10.7163 14 11.8785C14 13.832 13.3073 15.5011 11.9219 16.8858C10.5608 18.2953 8.92014 19 7 19C5.07986 19 3.42708 18.2953 2.04167 16.8858C0.680556 15.5011 0 13.832 0 11.8785C0 9.94973 0.668403 8.28062 2.00521 6.87116C2.27257 6.57443 2.58854 6.50024 2.95312 6.64861C3.31771 6.79697 3.5 7.08134 3.5 7.50171V10.6545C3.5 11.3221 3.71875 11.8908 4.15625 12.3607C4.61806 12.8305 5.16493 13.0654 5.79688 13.0654C6.45312 13.0654 7.01215 12.8428 7.47396 12.3978C7.93576 11.9279 8.16667 11.3592 8.16667 10.6916C8.16667 10.2712 8.04514 9.86318 7.80208 9.46754C7.58333 9.0719 7.31597 8.71336 7 8.3919C6.68403 8.07044 6.34375 7.73662 5.97917 7.39043C5.63889 7.04425 5.34722 6.66097 5.10417 6.2406C4.88542 5.82024 4.73958 5.35041 4.66667 4.83114C4.59375 4.31186 4.67882 3.68131 4.92188 2.93948C5.18924 2.17293 5.63889 1.33219 6.27083 0.417277C6.51389 0.0463641 6.84201 -0.0772735 7.25521 0.0463641C7.6684 0.170002 7.875 0.454368 7.875 0.899463Z" fill="#EB5757"></path></svg></span>';
		}
		if ( ! empty( $theme_styles['styles']['label'] ) ) {
			$html .= '<span class="monsterinsights-inline-popular-posts-label" ' . $this->get_element_style( $theme, 'label', $atts ) . '>' . $label_text . '</span>';
		}
		if ( ! empty( $theme_styles['styles']['border'] ) ) {
			$html .= '<span class="monsterinsights-inline-popular-posts-border" ' . $this->get_element_style( $theme, 'border', $atts, 'color' ) . '></span>';
		}
		if ( ! empty( $theme_styles['styles']['border']['color2'] ) ) {
			$html .= '<span class="monsterinsights-inline-popular-posts-border-2" ' . $this->get_element_style( $theme, 'border', $atts, 'color2' ) . '></span>';
		}

		$display_count = 0;
		foreach ( $posts as $post ) {
			$display_count ++;
			if ( $display_count > $limit ) {
				break;
			}
			$this->set_post_shown( $post['id'] );
			$html .= '<div class="monsterinsights-inline-popular-posts-post">';
			$html .= '<a class="monsterinsights-inline-popular-posts-title" ' . $this->get_element_style( $theme, 'title', $atts ) . ' href="' . $post['link'] . '">' . $post['title'] . '</a>';
			$html .= '</div>';
		}

		$html .= '</div>';// Text div.
		$html .= '</div><p></p>';// Main div.

		return $html;

	}

	/**
	 * Specific inline styles based on theme settings.
	 *
	 * @return string
	 */
	public function build_inline_styles() {

		$themes = $this->get_themes_styles_for_output();
		$styles = '';

		foreach ( $themes as $theme_key => $theme_styles ) {
			if ( ! empty( $theme_styles['background'] ) ) {
				$styles .= '.monsterinsights-inline-popular-posts.monsterinsights-popular-posts-styled.monsterinsights-inline-popular-posts-' . $theme_key . ' {';

				if ( ! empty( $theme_styles['background']['color'] ) ) {
					$styles .= 'background-color:' . $theme_styles['background']['color'] . ';';
				}
				if ( ! empty( $theme_styles['background']['border'] ) ) {
					$styles .= 'border-color:' . $theme_styles['background']['border'] . ';';
				}

				$styles .= '}';
			}

			if ( ! empty( $theme_styles['label'] ) ) {
				$styles .= '.monsterinsights-inline-popular-posts.monsterinsights-popular-posts-styled.monsterinsights-inline-popular-posts-' . $theme_key . ' .monsterinsights-inline-popular-posts-label {';

				if ( ! empty( $theme_styles['label']['color'] ) ) {
					$styles .= 'color:' . $theme_styles['label']['color'] . ';';
				}

				if ( ! empty( $theme_styles['label']['background'] ) ) {
					$styles .= 'background-color:' . $theme_styles['label']['background'] . ';';
				}

				$styles .= '}';
			}

			if ( ! empty( $theme_styles['title'] ) ) {
				$styles .= '.monsterinsights-inline-popular-posts.monsterinsights-popular-posts-styled.monsterinsights-inline-popular-posts-' . $theme_key . ' .monsterinsights-inline-popular-posts-title {';

				if ( ! empty( $theme_styles['title']['color'] ) ) {
					$styles .= 'color:' . $theme_styles['title']['color'] . ';';
				}
				if ( ! empty( $theme_styles['title']['size'] ) ) {
					$styles .= 'font-size:' . $theme_styles['title']['size'] . 'px;';
				}

				$styles .= '}';
			}

			if ( ! empty( $theme_styles['border'] ) ) {
				$styles .= '.monsterinsights-inline-popular-posts.monsterinsights-popular-posts-styled.monsterinsights-inline-popular-posts-' . $theme_key . ' .monsterinsights-inline-popular-posts-border {';

				if ( ! empty( $theme_styles['border']['color'] ) ) {
					$styles .= 'border-color:' . $theme_styles['border']['color'] . ';';
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
		if ( ! empty( $post_types ) && is_singular( $post_types ) && 'automatic' === $this->placement ) {
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
	public function add_inline_posts_to_content(
		$content
	) {

		if ( $this->is_post_excluded() ) {
			return $content;
		}

		$words_count = str_word_count( $content );
		$after_count = intval( $this->after_count );

		// Insert only if there are more words then the insert after value.
		if ( $words_count > $after_count ) {

			$words = explode( ' ', $content );
			$count = 0;

			foreach ( $words as $index => $word ) {
				$count ++;
				if ( $count > $after_count ) {
					$p_index = strpos( $word, '</p>' );
					// Make sure the paragraph tag is not wrapped in another element like a blockquote.
					if ( false !== $p_index && false === strpos( $word, '</p></' ) ) {
						$words[ $index ] = substr_replace( $word, $this->shortcode_output( array() ), $p_index + 4, 0 );
						$this->posts     = array();
						break;
					}
				}
			}

			$content = implode( ' ', $words );

		}

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

}

/**
 * Get the current class in a function.
 *
 * @return MonsterInsights_Popular_Posts_Inline Instance of the current class.
 */
function MonsterInsights_Popular_Posts_Inline() {
	return MonsterInsights_Popular_Posts_Inline::get_instance();
}

MonsterInsights_Popular_Posts_Inline();
