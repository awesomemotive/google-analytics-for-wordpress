<?php
/**
 * Class used to add the Popular Posts widget to the Appearance > Widget area.
 */

/**
 * Class MonsterInsights_Popular_Posts_Widget_Sidebar
 */
class MonsterInsights_Popular_Posts_Widget_Sidebar extends WP_Widget {
	/**
	 * Hold widget settings defaults, populated in constructor.
	 *
	 * @since 7.12.0
	 *
	 * @var array
	 */
	protected $defaults;
	/**
	 * Hold widget options that are theme specific.
	 *
	 * @since 7.12.0
	 *
	 * @var array
	 */
	protected $conditional_options;

	/**
	 * Constructor
	 *
	 * @since 7.12.0
	 */
	public function __construct() {

		// Widget defaults.
		$this->defaults = array(
			'title'            => '',
			'display_title'    => 'on',
			'post_count'       => 5,
			'theme'            => 'alpha',
			'title_color'      => '#393F4C',
			'title_size'       => 12,
			'label_color'      => '#EB5757',
			'label_text'       => 'Trending',
			'meta_color'       => '#99A1B3',
			'meta_size'        => '12',
			'meta_author'      => 'on',
			'meta_date'        => 'on',
			'meta_comments'    => 'on',
			'background_color' => '#F0F2F4',
			'border_color'     => '#D3D7DE',
			'columns'          => '1',
		);

		$this->conditional_options = array(
			'title_color'       => array( 'title', 'color' ),
			'title_size'        => array( 'title', 'size' ),
			'label_color'       => array( 'label', 'color' ),
			'label_text'        => array( 'label', 'text' ),
			'background_color'  => array( 'background', 'color' ),
			'background_border' => array( 'background', 'border' ),
			'meta_color'        => array( 'meta', 'color' ),
			'meta_size'         => array( 'meta', 'size' ),
			'meta_author'       => array( 'meta', 'author' ),
			'meta_date'         => array( 'meta', 'date' ),
			'meta_comments'     => array( 'meta', 'comments' ),
			'comments_color'    => array( 'comments', 'color' ),
		);

		// Widget Slug.
		$widget_slug = 'monsterinsights-popular-posts-widget';

		// Widget basics.
		$widget_ops = array(
			'classname'   => $widget_slug,
			'description' => esc_html_x( 'Display popular posts.', 'Widget', 'google-analytics-for-wordpress' ),
		);

		// Widget controls.
		$control_ops = array(
			'id_base' => $widget_slug,
		);

		$this->add_scripts();

		// Load widget.
		parent::__construct( $widget_slug, esc_html_x( 'Popular Posts - MonsterInsights', 'Widget', 'google-analytics-for-wordpress' ), $widget_ops, $control_ops );
	}

	/**
	 * Output the HTML for this widget.
	 *
	 * @param array $args An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 *
	 * @since 7.12.0
	 *
	 */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		if ( $instance['display_title'] && ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo wp_kses_post( $title );
			echo $args['after_title'];
		}

		$atts = array(
			'theme'        => $instance['theme'],
			'post_count'   => $instance['post_count'],
			'columns'      => 1, // Sidebar is not wide so we always use the 1 column layout.
			'widget_title' => false, // Override this in favor of sidebar-specific markup above.
		);

		foreach ( $this->conditional_options as $key => $default ) {
			if ( ! empty( $instance[ $key ] ) ) {
				$atts[ $key ] = $instance[ $key ];
			}
		}

		echo MonsterInsights_Popular_Posts_Widget()->shortcode_output( $atts );

		echo $args['after_widget'];

	}

	/**
	 * Deal with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 *
	 * @param array $new_instance An array of new settings as submitted by the admin.
	 * @param array $old_instance An array of the previous settings.
	 *
	 * @return array The validated and (if necessary) amended settings
	 * @since 7.12.0
	 *
	 */
	public function update( $new_instance, $old_instance ) {

		$new_instance['title']         = wp_strip_all_tags( $new_instance['title'] );
		$new_instance['theme']         = wp_strip_all_tags( $new_instance['theme'] );
		$new_instance['display_title'] = isset( $new_instance['display_title'] ) ? wp_strip_all_tags( $new_instance['display_title'] ) : '';
		$new_instance['post_count']    = absint( $new_instance['post_count'] );

		// Theme-dependant options.
		$themes = new MonsterInsights_Popular_Posts_Themes( 'widget', ! empty( $old_instance['theme'] ) ? $old_instance['theme'] : '' );
		$theme  = $themes->get_theme();

		foreach ( $this->conditional_options as $key => $obj ) {
			$new_instance = $this->maybe_remove_option( ! empty( $theme['styles'][ $obj[0] ][ $obj[1] ] ), $key, $new_instance );
		}

		return $new_instance;
	}

	/**
	 * Process dynamic and checkbox values so they are stored correctly and specific to the current theme.
	 *
	 * @param bool   $is_used A check if this property is used in the currently selected theme.
	 * @param string $key The key of the property we're checking.
	 * @param array  $instance The current widget instance, new instance.
	 *
	 * @return mixed
	 */
	public function maybe_remove_option( $is_used, $key, $instance ) {

		$checkboxes = array(
			'meta_author',
			'meta_date',
			'meta_comments',
		);

		if ( $is_used && ! isset( $instance[ $key ] ) && in_array( $key, $checkboxes ) ) {
			$instance[ $key ] = 'off';
		} elseif ( ! $is_used && isset( $instance[ $key ] ) ) {
			unset( $instance[ $key ] );
		} elseif ( $is_used && isset( $instance[ $key ] ) ) {
			$instance[ $key ] = wp_strip_all_tags( $instance[ $key ] );
		}

		return $instance;
	}

	/**
	 * Display the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @param array $instance An array of the current settings for this widget.
	 *
	 * @since 7.12.0
	 */
	public function form( $instance ) {

		// Merge with defaults but use theme settings from Vue as defaults.
		$theme_name = empty( $instance['theme'] ) ? $this->defaults['theme'] : $instance['theme'];
		$themes     = new MonsterInsights_Popular_Posts_Themes( 'widget', $theme_name );
		$theme      = $themes->get_theme();
		$this->prepare_defaults_from_theme( $theme );

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$title_font_sizes = apply_filters( 'monsterinsights_popular_posts_widget_title_sizes', range( 10, 35 ) );

		$this->text_input( 'title', _x( 'Widget Title:', 'Widget', 'google-analytics-for-wordpress' ), $instance );

		$categories = array(
			array(
				'name'  => 'News',
				'value' => '0',
			),
			array(
				'name'  => 'Technology',
				'value' => '1',
			),
		);
		?>
		<p>
			<input type="checkbox"
				   id="<?php echo esc_attr( $this->get_field_id( 'display_title' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'display_title' ) ); ?>"
				   value="on" <?php checked( $instance['display_title'], 'on' ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'display_title' ) ); ?>">
				<?php echo esc_html( _x( 'Display Widget Title', 'Widget', 'google-analytics-for-wordpress' ) ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_count' ) ); ?>">
				<?php echo esc_html( _x( 'Number of posts to display:', 'Widget', 'google-analytics-for-wordpress' ) ); ?>
			</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'post_count' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'post_count' ) ); ?>">
				<option value="5" <?php selected( $instance['post_count'], 5 ); ?>>5</option>
				<option value="10" <?php selected( $instance['post_count'], 10 ); ?>>10</option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'theme' ) ); ?>">
				<?php echo esc_html( _x( 'Theme:', 'Widget', 'google-analytics-for-wordpress' ) ); ?>
			</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'theme' ) ); ?>"
					class="widefat monsterinsights-save-on-change"
					name="<?php echo esc_attr( $this->get_field_name( 'theme' ) ); ?>">
				<?php foreach ( $themes->themes as $key => $details ) {
					if ( 'lite' !== $details['level'] ) {
						continue;
					}
					?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $instance['theme'], $key ); ?>>
						<?php echo esc_html( ucfirst( $key ) ); ?>
					</option>
				<?php } ?>
			</select>
		</p>
		<div class="monsterinsights-widget-theme-preview">
			<span class="monsterinsights-widget-theme-preview-label">
				<?php esc_html_e( 'Theme Preview', 'google-analytics-for-wordpress' ); ?>
			</span>
			<div class="monsterinsights-widget-theme-preview-icon monsterinsights-widget-theme-preview-icon-<?php echo esc_attr( $instance['theme'] ); ?>"></div>
		</div>
		<?php if ( ! empty( $theme['styles']['title']['color'] ) ) {
			$this->color_input( 'title_color', _x( 'Title Color:', 'Widget', 'google-analytics-for-wordpress' ), $instance );
			?>
			<?php
		}
		if ( ! empty( $theme['styles']['title']['size'] ) ) {
			$this->size_input( 'title_size', _x( 'Title Font Size:', 'Widget', 'google-analytics-for-wordpress' ), $instance, $title_font_sizes );
		}
		if ( ! empty( $theme['styles']['label']['color'] ) ) {
			$this->color_input( 'label_color', _x( 'Label Color:', 'Widget', 'google-analytics-for-wordpress' ), $instance );
		}
		if ( ! empty( $theme['styles']['label']['editable'] ) && ! empty( $theme['styles']['label']['text'] ) ) {
			$this->text_input( 'label_text', _x( 'Label Text:', 'Widget', 'google-analytics-for-wordpress' ), $instance );
		}
		if ( ! empty( $theme['styles']['background']['border'] ) ) {
			$this->color_input( 'background_border', _x( 'Border Color:', 'Widget', 'google-analytics-for-wordpress' ), $instance );
		}
		if ( ! empty( $theme['styles']['background']['color'] ) ) {
			$this->color_input( 'background_color', _x( 'Background Color:', 'Widget', 'google-analytics-for-wordpress' ), $instance );
		}
		if ( ! empty( $theme['styles']['comments']['color'] ) ) {
			$this->color_input( 'comments_color', _x( 'Comments Count Color:', 'Widget', 'google-analytics-for-wordpress' ), $instance );
		}
		?>
		<p>

			<label class="monsterinsights-label-block">
				<?php echo esc_html( _x( 'Only Show Posts from These Categories:', 'Widget', 'google-analytics-for-wordpress' ) ); ?>
				<span class="monsterinsights-pro-pill">PRO</span>
			</label>
			<div class="select300 select300-container select300-container--default select300-container--disabled select300-container--focus"
				  dir="ltr" data-select300-id="2" style="width: auto;">
				<div class="selection">
					<div class="select300-selection select300-selection--multiple">
						<ul class="select300-selection__rendered">
							<li class="select300-selection__choice" title="News" data-select300-id="5">
								<span class="select300-selection__choice__remove" role="presentation">×</span>News
							</li>
							<li class="select300-selection__choice" title="Technology" data-select300-id="6">
								<span class="select300-selection__choice__remove" role="presentation">×</span>Technology
							</li>
						</ul>
					</div>
				</div>
			</div>
		</p>
		<?php
	}

	/**
	 * Colorpicker input element.
	 *
	 * @param string $name Name of the input, for saving/loading.
	 * @param string $label Label of the element.
	 * @param array  $instance The current widget instance.
	 */
	public function color_input( $name, $label, $instance ) {
		?>
		<p>
			<label class="monsterinsights-label-block"
				   for="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>">
				<?php echo esc_html( $label ); ?>
			</label>
			<input type="text"
				   id="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( $name ) ); ?>"
				   value="<?php echo esc_attr( $instance[ $name ] ); ?>"
				   class="widefat monsterinsights-color-field"/>
		</p>
		<?php
	}

	/**
	 * Regular text input.
	 *
	 * @param string $name Name of the input, for saving/loading.
	 * @param string $label Label of the element.
	 * @param array  $instance The current widget instance.
	 */
	public function text_input( $name, $label, $instance ) {
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>">
				<?php echo esc_html( $label ); ?>
			</label>
			<input type="text"
				   id="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( $name ) ); ?>"
				   value="<?php echo esc_attr( $instance[ $name ] ); ?>" class="widefat"/>
		</p>
		<?php
	}

	/**
	 * Size input - used for font size inputs.
	 *
	 * @param string $name Name of the input, for saving/loading.
	 * @param string $label Label of the element.
	 * @param array  $instance The current widget instance.
	 * @param array  $range The options available to select.
	 */
	public function size_input( $name, $label, $instance, $range = array() ) {
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>">
				<?php echo esc_html( $label ); ?>
			</label>
			<select id="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( $name ) ); ?>" class="widefat">
				<?php foreach ( $range as $font_size ) { ?>
					<option value="<?php echo absint( $font_size ); ?>" <?php selected( $instance[ $name ], $font_size ); ?>><?php printf( esc_html_x( '%dpx', 'google-analytics-for-wordpress' ), $font_size ); ?></option>
				<?php } ?>
			</select>
		</p>
		<?php
	}

	/**
	 * Prepare theme specific options.
	 *
	 * @param array $theme The theme options.
	 */
	public function prepare_defaults_from_theme( $theme ) {
		foreach ( $this->conditional_options as $key => $obj ) {
			if ( ! empty( $theme['styles'][ $obj[0] ][ $obj[1] ] ) ) {
				$this->defaults[ $key ] = $theme['styles'][ $obj[0] ][ $obj[1] ];
			}
		}
	}

	/**
	 * Load specific widget scripts in the admin.
	 */
	public function add_scripts() {
		add_action( 'admin_enqueue_scripts', array( $this, 'load_widget_scripts' ) );
	}

	/**
	 * Load admin-specific widget scripts.
	 */
	public function load_widget_scripts() {

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;

		if ( ! isset( $screen->id ) || 'widgets' !== $screen->id ) {
			return;
		}


		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_style( 'monsterinsights-admin-widget-setting-styles', plugins_url( 'assets/css/admin-widget-settings' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(
			'wp-color-picker',
		), monsterinsights_get_asset_version() );

		wp_register_script( 'monsterinsights-admin-widget-settings', plugins_url( 'assets/js/admin-widget-settings' . $suffix . '.js', MONSTERINSIGHTS_PLUGIN_FILE ), array(
			'jquery',
			'wp-color-picker',
		), monsterinsights_get_asset_version(), true );
		wp_enqueue_script( 'monsterinsights-admin-widget-settings' );

		wp_localize_script( 'monsterinsights-admin-widget-settings', 'monsterinsights_pp', array(
			'nonce' => wp_create_nonce( 'mi-admin-nonce' ),
		) );
	}
}
