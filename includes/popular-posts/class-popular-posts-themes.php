<?php
/**
 * Popular posts theme-specific functionality.
 *
 * @package MonsterInsights
 */

/**
 * Class MonsterInsights_Popular_Posts_Themes
 */
class MonsterInsights_Popular_Posts_Themes {

	/**
	 * The type of widget to load themes for (inline, widget, products).
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Holds the array of themes specific to the current type loaded.
	 *
	 * @var array
	 */
	public $themes = array();

	/**
	 * Holds the data for the currently selected theme (if indicated in the constructor).
	 *
	 * @var array
	 */
	public $theme = array();

	/**
	 * The current theme instance with styles from settings applied.
	 *
	 * @var array
	 */
	public $styled_theme;

	/**
	 * The theme options key used to store specific theme styles.
	 *
	 * @var string
	 */
	private $themes_styles_key = 'monsterinsights_popular_posts_theme_settings';

	/**
	 * Variable for the theme settings.
	 *
	 * @var array
	 */
	private $themes_styles;

	/**
	 * MonsterInsights_Popular_Posts_Themes constructor.
	 *
	 * @param string $type The type of widget: inline/widget/products.
	 * @param string $theme The current theme to load details for.
	 */
	public function __construct( $type = 'inline', $theme = '' ) {

		$this->type = $type;
		if ( method_exists( $this, 'get_themes_' . $type ) ) {
			$this->themes = call_user_func( array( $this, 'get_themes_' . $type ) );
			if ( ! empty( $theme ) ) {
				$this->theme = isset( $this->themes[ $theme ] ) ? $this->themes[ $theme ] : array();

				return $this->theme;
			} else {
				return $this->themes;
			}
		}

		return false;

	}

	/**
	 * Get the current theme details with the option to load properties already styled.
	 *
	 * @return array|mixed
	 */
	public function get_theme() {

		return $this->theme;

	}

	/**
	 * Get the currently loaded themes for the widget type.
	 *
	 * @return array|mixed
	 */
	public function get_themes() {
		return $this->themes;
	}

	public function get_theme_stored_styles() {
		if ( ! isset( $this->themes_styles ) ) {
			$this->themes_styles = get_option( $this->themes_styles_key, array() );
		}

		return $this->themes_styles;
	}

	/**
	 * Go through the themes and apply styles from the stored settings.
	 *
	 * @var string $type The instance type: inline/widget/products.
	 * @var array $themes The themes to process/apply styles for.
	 */
	public function process_themes_styles( $type, $themes ) {

		$settings = $this->get_theme_stored_styles();

		if ( ! empty( $settings[ $type ] ) ) {
			foreach ( $themes as $theme_key => $theme_values ) {
				if ( ! empty( $settings[ $type ][ $theme_key ] ) ) {
					foreach ( $themes[ $theme_key ]['styles'] as $object => $props ) {
						if ( ! empty( $settings[ $type ][ $theme_key ][ $object ] ) ) {
							foreach ( $props as $style_key => $style_value ) {
								if ( ! empty( $settings[ $type ][ $theme_key ][ $object ][ $style_key ] ) ) {
									$themes[ $theme_key ]['styles'][ $object ][ $style_key ] = $settings[ $type ][ $theme_key ][ $object ][ $style_key ];
								}
							}
						}
					}
				}
			}
		}

		return $themes;

	}

	/**
	 * Get the themes for the inline widget type.
	 *
	 * @return array
	 */
	public function get_themes_inline() {

		$themes = array(
			'alpha'    => array(
				'label'  => 'Alpha',
				'styles' => array(
					'title'      => array(
						'color' => '#393F4C',
						'size'  => 18,
						'text'  => '15 Proven Ways to Repurpose Content on Your WordPress Site',
					),
					'label'      => array(
						'color' => '#EB5757',
						'text'  => 'Trending',
					),
					'background' => array(
						'color' => '#F0F2F4',
					),
				),
				'level'  => 'lite',
			),
			'beta'     => array(
				'label'  => 'Beta',
				'styles' => array(
					'title'      => array(
						'color' => '#393F4C',
						'size'  => 18,
						'text'  => 'How to Use Google Trends to Boost Traffic and Sales (9 Simple Ways)',
					),
					'label'      => array(
						'color' => '#EB5757',
						'text'  => 'Trending',
					),
					'background' => array(
						'border' => '#F0F2F4',
					),
					'image'      => 'theme-preview-beta.png',
				),
				'image'  => true,
				'level'  => 'lite',
			),
			'charlie'  => array(
				'label'  => 'Charlie',
				'styles' => array(
					'title'  => array(
						'color' => '#393f4c',
						'size'  => 16,
					),
					'label'  => array(
						'color' => '#393f4c',
						'text'  => 'Popular Stories Right now',
					),
					'border' => array(
						'color' => '#D3D7DE',
					),
				),
				'list'   => array(
					'15 Proven Ways to Repurpose Content on Your WordPress Site',
					'How to Use Google Trends to Boost Traffic and Sales (9 Simple Ways)',
					'How to Set Up Online Ordering for Your Restaurant Website',
				),
				'posts'  => 3,
				'level'  => 'lite',
			),
			'delta'    => array(
				'label'  => 'Delta',
				'styles' => array(
					'icon'       => array(
						'color' => '#EB5757',
					),
					'title'      => array(
						'color' => '#393f4c',
						'size'  => 16,
						'text'  => 'How to Use Google Trends to Boost Traffic and Sales (9 Simple Ways)',
					),
					'label'      => array(
						'color' => '#EB5757',
						'text'  => 'Trending',
					),
					'background' => array(
						'border' => '#F0F2F4',
					),
				),
				'level'  => 'plus',
			),
			'echo'     => array(
				'label'  => 'Echo',
				'styles' => array(
					'title'      => array(
						'color' => '#393f4c',
						'size'  => 18,
						'text'  => '15 Proven Ways to Repurpose Content on Your WordPress Site',
					),
					'label'      => array(
						'color' => '#EB5757',
						'text'  => 'Trending:',
					),
					'background' => array(
						'color' => '#F0F2F4',
					),
				),
				'level'  => 'plus',
			),
			'foxtrot'  => array(
				'label'  => 'Foxtrot',
				'styles' => array(
					'title' => array(
						'color' => '#393f4c',
						'size'  => 18,
						'text'  => 'How to Build an Email List in WordPress – Email Marketing 101',
					),
					'label' => array(
						'color' => '#EB5757',
						'text'  => 'Trending',
					),
					'image' => 'theme-preview-image.jpg',
				),
				'image'  => true,
				'level'  => 'plus',
			),
			'golf'     => array(
				'label'  => 'Golf',
				'styles' => array(
					'title'  => array(
						'color' => '#393f4c',
						'size'  => 16,
						'text'  => '15 Proven Ways to Repurpose Content on Your WordPress Site',
					),
					'label'  => array(
						'color' => '#EB5757',
						'text'  => 'Popular now',
					),
					'border' => array(
						'color'  => '#EB5757',
						'color2' => '#E2E4E9',
					),
				),
				'level'  => 'plus',
			),
			'hotel'    => array(
				'label'  => 'Hotel',
				'styles' => array(
					'title' => array(
						'color' => '#393f4c',
						'size'  => 18,
						'text'  => 'How to Use Google Trends to Boost Traffic and Sales (9 Simple Ways)',
					),
					'icon'  => array(
						'color' => '#EB5757',
					),
				),
				'level'  => 'plus',
			),
			'india'    => array(
				'label'  => 'India',
				'styles' => array(
					'title'      => array(
						'color' => '#393f4c',
						'size'  => 14,
						'text'  => 'How to Use Google Trends to Boost Traffic and Sales (9 Simple Ways)',
					),
					'label'      => array(
						'color' => '#EB5757',
						'text'  => 'Trending:',
					),
					'border'     => array(
						'color' => '#EB5757',
					),
					'background' => array(
						'color' => '#f0f2f4',
					),
				),
				'level'  => 'plus',
			),
			'juliett'  => array(
				'label'  => 'Juliett',
				'styles' => array(
					'title'  => array(
						'color' => '#393f4c',
						'size'  => 18,
						'text'  => 'How to Build an Email List in WordPress – Email Marketing 101',
					),
					'label'  => array(
						'color'      => '#393f4c',
						'background' => '#e2e4e9',
						'text'       => 'Trending',
					),
					'border' => array(
						'color' => '#e2e4e9',
					),
				),
				'image'  => true,
				'level'  => 'plus',
			),
			'kilo'     => array(
				'label'  => 'Kilo',
				'styles' => array(
					'title'  => array(
						'color' => '#393f4c',
						'size'  => 18,
						'text'  => 'How to Use Google Trends to Boost Traffic and Sales (9 Simple Ways)',
					),
					'label'  => array(
						'color' => '#EB5757',
						'text'  => 'Popular now',
					),
					'border' => array(
						'color'  => '#e2e4e9',
						'color2' => '#e2e4e9',
					),
				),
				'level'  => 'plus',
			),
			'lima'     => array(
				'label'  => 'Lima',
				'styles' => array(
					'title'      => array(
						'color' => '#393f4c',
						'size'  => 18,
						'text'  => '15 Proven Ways to Repurpose Content on Your WordPress Site',
					),
					'label'      => array(
						'color' => '#EB5757',
						'text'  => 'Trending',
					),
					'background' => array(
						'color' => '#f0f2f4',
					),
					'image'      => 'theme-preview-image-2.jpg',
				),
				'image'  => true,
				'level'  => 'plus',
			),
			'mike'     => array(
				'label'  => 'Mike',
				'styles' => array(
					'title'      => array(
						'color' => '#393f4c',
						'size'  => 18,
						'text'  => 'How to Build an Email List in WordPress – Email Marketing 101',
					),
					'label'      => array(
						'color'      => '#fff',
						'background' => '#f2994a',
						'text'       => 'Trending',
					),
					'background' => array(
						'color' => '#f0f2f4',
					),
					'image'      => 'theme-preview-image.jpg',
				),
				'image'  => true,
				'level'  => 'plus',
			),
			'november' => array(
				'label'  => 'November',
				'styles' => array(
					'title'      => array(
						'color' => '#393f4c',
						'size'  => 16,
						'text'  => 'How to Use Google Trends to Boost Traffic and Sales (9 Simple Ways)',
					),
					'label'      => array(
						'color' => '#eb5757',
						'text'  => 'Trending',
					),
					'background' => array(
						'border' => '#f0f2f4',
					),
					'icon'       => array(
						'background' => '#eb5757',
						'color'      => '#fff',
					),
				),
				'level'  => 'plus',
			),
		);

		return $this->process_themes_styles( 'inline', $themes );

	}

	/**
	 * Get the themese for the widget instance.
	 *
	 * @return array
	 */
	public function get_themes_widget() {

		$themes = array(
			'alpha'   => array(
				'label'  => 'Alpha',
				'styles' => array(
					'title'      => array(
						'color' => '#393F4C',
						'size'  => 16,
					),
					'background' => array(
						'color' => '#F0F2F4',
					),

				),
				'list'   => array(
					'items' => array(
						'How to Set Up WordPress User Activity Tracking in 3 Easy Steps',
						'How to Share Your Google Analytics Reports with Others (5 Easy Ways)',
						'12 Best Social Media Analytics Tools for Marketers (Tried & Tested)',
						'9 Proven Ways to Get Google to Index Your Website Right Away',
					),
				),
				'level'  => 'lite',
			),
			'beta'    => array(
				'label'  => 'Beta',
				'styles' => array(
					'title'      => array(
						'color' => '#393F4C',
						'size'  => 16,
					),
					'background' => array(
						'border' => '#1EC185',
					),
				),
				'list'   => array(
					'items' => array(
						'9 Proven Ways to Get Google to Index Your Website Right Away',
						'How to Share Your Google Analytics Reports with Others (5 Easy Ways)',
						'12 Best Social Media Analytics Tools for Marketers (Tried & Tested)',
						'Uncover How Much Traffic Does a Website Get (5 Effortless Ways)',
					),
				),
				'level'  => 'lite',
			),
			'charlie' => array(
				'label'  => 'Charlie',
				'styles' => array(
					'title'      => array(
						'color' => '#393f4c',
						'size'  => 16,
					),
					'background' => array(
						'color'  => '#F0F2F4',
						'border' => '#338EEF',
					),
				),
				'list'   => array(
					'items' => array(
						'Uncover How Much Traffic Does a Website Get (5 Effortless Ways)',
						'How to Share Your Google Analytics Reports with Others (5 Easy Ways)',
						'12 Best Social Media Analytics Tools for Marketers (Tried & Tested)',
						'9 Proven Ways to Get Google to Index Your Website Right Away',
					),
				),
				'level'  => 'lite',
			),
			'delta'   => array(
				'label'  => 'Delta',
				'styles' => array(
					'title'      => array(
						'color' => '#393f4c',
						'size'  => 18,
					),
					'background' => array(
						'border' => '#D3D7DE',
					),
					'meta'       => array(
						'color'     => '#99A1B3',
						'author'    => 'on',
						'date'      => 'on',
						'separator' => '&#9679;',
					),
				),
				'list'   => array(
					'items'  => array(
						'9 Proven Ways to Get Google to Index Your Website Right Away',
						'How to Share Your Google Analytics Reports with Others (5 Easy Ways)',
						'12 Best Social Media Analytics Tools for Marketers (Tried & Tested)',
						'Uncover How Much Traffic Does a Website Get (5 Effortless Ways)',
					),
					'images' => array(
						'theme-widget-1.jpg',
						'theme-widget-2.jpg',
						'theme-widget-3.jpg',
						'theme-widget-4.jpg',
					),
				),
				'image'  => true,
				'level'  => 'pro',
			),
			'echo'    => array(
				'label'  => 'Echo',
				'styles' => array(
					'title'    => array(
						'color' => '#393f4c',
						'size'  => 16,
					),
					'meta'     => array(
						'color'     => '#99A1B3',
						'size'      => 12,
						'author'    => 'on',
						'date'      => 'on',
						'comments'  => 'on',
						'separator' => 'on',
					),
					'comments' => array(
						'color' => '#393F4C',
					),
				),
				'list'   => array(
					'items'  => array(
						'9 Proven Ways to Get Google to Index Your Website Right Away',
						'How to Share Your Google Analytics Reports with Others (5 Easy Ways)',
						'12 Best Social Media Analytics Tools for Marketers (Tried & Tested)',
						'Uncover How Much Traffic Does a Website Get (5 Effortless Ways)',
					),
					'images' => array(
						'theme-widget-5.jpg',
						'theme-widget-5.jpg',
						'theme-widget-5.jpg',
						'theme-widget-5.jpg',
					),
				),
				'image'  => true,
				'level'  => 'pro',
			),
			'foxtrot' => array(
				'label'  => 'Foxtrot',
				'styles' => array(
					'title'    => array(
						'color' => '#393f4c',
						'size'  => 16,
					),
					'meta'     => array(
						'color'     => '#99A1B3',
						'size'      => 12,
						'author'    => 'on',
						'date'      => 'on',
						'comments'  => 'on',
						'separator' => '|',
					),
					'comments' => array(
						'color' => '#393F4C',
					),
				),
				'list'   => array(
					'items' => array(
						'9 Proven Ways to Get Google to Index Your Website Right Away',
						'12 Best Social Media Analytics Tools for Marketers (Tried & Tested) ',
						'Uncover How Much Traffic Does a Website Get (5 Effortless Ways)',
						'How to Share Your Google Analytics Reports with Others (5 Easy Ways)',
					),
				),
				'level'  => 'pro',
			),
			'golf'    => array(
				'label'  => 'Golf',
				'styles' => array(
					'title'    => array(
						'color' => '#393f4c',
						'size'  => 16,
					),
					'label'    => array(
						'color'      => '#fff',
						'background' => '#EB5757',
						'text'       => 'Trending:',
						'editable'   => true,
					),
					'meta'     => array(
						'color'     => '#99A1B3',
						'size'      => 12,
						'author'    => 'on',
						'date'      => 'on',
						'comments'  => 'on',
						'separator' => '|',
					),
					'comments' => array(
						'color' => '#393F4C',
					),
				),
				'list'   => array(
					'items' => array(
						'9 Proven Ways to Get Google to Index Your Website Right Away',
						'12 Best Social Media Analytics Tools for Marketers (Tried & Tested) ',
						'Uncover How Much Traffic Does a Website Get (5 Effortless Ways)',
						'How to Share Your Google Analytics Reports with Others (5 Easy Ways)',
					),
				),
				'level'  => 'pro',
			),
			'hotel'   => array(
				'label'  => 'Hotel',
				'styles' => array(
					'title' => array(
						'color' => '#fff',
						'size'  => 16,
					),
					'meta'  => array(
						'color'  => '#fff',
						'size'   => 12,
						'author' => 'on',
						'date'   => 'on',
					),
				),
				'list'   => array(
					'items'  => array(
						'How to Allow WordPress to Upload All File Types (The Easy Way)',
						'14 Handy Google Search Operators for SEO (A Complete List)',
						'How to Write Irresistible Meta Descriptions for SEO & More Clicks?',
						'Uncover How Much Traffic Does a Website Get (5 Effortless Ways)',
					),
					'images' => array(
						'theme-widget-5.jpg',
						'theme-widget-6.jpg',
						'theme-widget-7.jpg',
						'theme-widget-8.jpg',
					),
				),
				'image'  => true,
				'level'  => 'pro',
			),
		);

		return $this->process_themes_styles( 'widget', $themes );

	}

	/**
	 * Get the themes for the products widget.
	 *
	 * @return array
	 */
	public function get_themes_products() {
		$themes = array(
			'alpha'   => array(
				'label'  => 'Alpha',
				'styles' => array(
					'title'      => array(
						'color' => '#393F4C',
						'size'  => 16,
					),
					'background' => array(
						'border' => '#d3d7de',
					),
					'price'      => array(
						'color' => '#393F4C',
						'size'  => 12,
					),
					'rating'     => array(
						'color' => '#EB5757',
					),
					'meta'       => array(
						'price'  => 'on',
						'rating' => 'on',
						'image'  => 'on',
					),
				),
				'list'   => array(
					'items'  => array(
						'WPBeginner 10-Year Anniversary Gray T-Shirt',
						'WPForms Small White Logo T-Shirt',
						'OptinMonster White Text Color Mascot T-Shirt',
						'WPForms Make Things Simple Gray T-Shirt',
					),
					'images' => array(
						'theme-products-1.jpg',
						'theme-products-2.jpg',
						'theme-products-3.jpg',
						'theme-products-4.jpg',
					),
					'prices' => array(
						'$59.99',
						'$28.00',
						'$65.00',
						'$59.50',
					),
				),
			),
			'beta'    => array(
				'label'  => 'Beta',
				'styles' => array(
					'title'      => array(
						'color' => '#393F4C',
						'size'  => 16,
					),
					'background' => array(
						'color' => '#F0F2F4',
					),
					'price'      => array(
						'color' => '#4C5566',
						'size'  => 12,
					),
					'rating'     => array(
						'color' => '#F2D74A',
					),
					'meta'       => array(
						'price'  => 'on',
						'rating' => 'on',
						'image'  => 'on',
					),
				),
				'list'   => array(
					'items'  => array(
						'Admin WPBeginner Black T-Shirt',
						'Black WP Beginner logo T-Shirt',
						'Technically Awesome Groovy White T-Shirt',
						'Technically Awesome Code Comment Black T-Shirt',
					),
					'images' => array(
						'theme-products-5.jpg',
						'theme-products-7.jpg',
						'theme-products-6.jpg',
						'theme-products-8.jpg',
					),
					'prices' => array(
						'$29.50',
						'$28.00',
						'$65.00',
						'$59.50',
					),
				),
			),
			'charlie' => array(
				'label'  => 'Charlie',
				'styles' => array(
					'title'  => array(
						'color' => '#fff',
						'size'  => 16,
					),
					'rating' => array(
						'color' => '#F2D74A',
					),
					'price'  => array(
						'color' => '#fff',
						'size'  => 12,
					),
					'meta'   => array(
						'price'  => 'on',
						'rating' => 'on',
					),
				),
				'list'   => array(
					'items'  => array(
						'Admin WPBeginner Black T-Shirt',
						'Black WP Beginner logo T-Shirt',
						'Technically Awesome Groovy White T-Shirt',
						'Technically Awesome Code Comment Black T-Shirt',
					),
					'images' => array(
						'theme-products-5.jpg',
						'theme-products-7.jpg',
						'theme-products-6.jpg',
						'theme-products-8.jpg',
					),
					'prices' => array(
						'$29.50',
						'$28.00',
						'$65.00',
						'$59.50',
					),
				),
				'image'  => true,
			),
			'delta'   => array(
				'label'  => 'Delta',
				'styles' => array(
					'title'  => array(
						'color' => '#393f4c',
						'size'  => 14,
					),
					'rating' => array(
						'color' => '#F2D74A',
					),
					'price'  => array(
						'color' => '#4C5566',
						'size'  => 12,
					),
					'meta'   => array(
						'price'  => 'on',
						'rating' => 'on',
						'image'  => 'on',
					),
				),
				'list'   => array(
					'items'  => array(
						'Admin WPBeginner Black T-Shirt',
						'Black WP Beginner logo T-Shirt',
						'Technically Awesome Groovy White T-Shirt',
						'Technically Awesome Code Comment Black T-Shirt',
					),
					'images' => array(
						'theme-products-5.jpg',
						'theme-products-7.jpg',
						'theme-products-6.jpg',
						'theme-products-8.jpg',
					),
					'prices' => array(
						'$29.50',
						'$28.00',
						'$65.00',
						'$59.50',
					),
				),
			),
		);

		return $this->process_themes_styles( 'products', $themes );
	}

}
