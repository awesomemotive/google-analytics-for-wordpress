<?php

class MonsterInsights_VerifiedBadge_Controller
{

	/**
	 * Current class instance.
	 *
	 * @since 8.9.0
	 *
	 * @var MonsterInsights_VerifiedBadge_Controller
	 */
	private static $instance;

	/**
	 * Name of the shortcode
	 *
	 * @since 8.9.0
	 *
	 * @var string
	 */
	private $shortcode_key = 'monsterinsights-badge';

	/**
	 * Get instance from current class.
	 *
	 * @since 8.9.0
	 *
	 * @return MonsterInsights_VerifiedBadge_Controller
	 */
	public static function get_instance()
	{
		if (!isset(self::$instance) && !(self::$instance instanceof MonsterInsights_VerifiedBadge_Controller)) {
			self::$instance = new MonsterInsights_VerifiedBadge_Controller();
		}

		return self::$instance;
	}

	/**
	 * Entry point for this class to start adding callbacks for WP hooks.
	 *
	 * @since 8.9.0
	 *
	 * @return void
	 */
	public function run()
	{
		$this->register_shortcode();
		$this->add_hooks();
	}

	/**
	 * Register the shortcode.
	 *
	 * @since 8.9.0
	 *
	 * @return void
	 */
	private function register_shortcode()
	{
		if (empty($this->shortcode_key)) {
			return;
		}

		add_shortcode($this->shortcode_key, array($this, 'render_badge'));
	}

	/**
	 * Render badge HTML based on the passed attributes.
	 *
	 * @param array $atts Attributes used to customize the badge.
	 *
	 * @since 8.9.0
	 *
	 * @return string
	 */
	public function render_badge($atts)
	{
		$atts = shortcode_atts(array(
			'appearance' => 'light',
			'position'   => 'center'
		), $atts, $this->shortcode_key);

		$img_src =  esc_url(plugins_url('assets/images/monsterinsights-badge-' . (in_array($atts['appearance'], array('light', 'dark'), true) ? $atts['appearance'] : 'light') . '.svg', MONSTERINSIGHTS_PLUGIN_FILE));

		return sprintf(
			'<div style="text-align: %1$s;"><a href="%2$s" target="_blank" rel="nofollow"><img style="display: inline-block" alt="%3$s" title="%3$s" src="%4$s"/></a></div>',
			(in_array($atts['position'], array('left', 'center', 'right'), true) ? $atts['position'] : 'center'),
			$this->get_link(),
			__('Verified by MonsterInsights', 'google-analytics-for-wordpress'),
			$img_src
		);
	}

	private function get_link()
	{
		return add_query_arg(
			array(
				'utm_source'   => 'verifiedBadge',
				'utm_medium'   => 'verifiedBadge',
				'utm_campaign' => 'verifiedbyMonsterInsights',
			),
			'https://www.monsterinsights.com/'
		);
	}

	/**
	 * Start adding callbacks for WP Hooks.
	 *
	 * @since 8.9.0
	 *
	 * @return void
	 */
	public function add_hooks()
	{
		add_action('wp_footer', array($this, 'show_automatic_footer_badge'), 1000);
		add_action('wp_loaded', array($this, 'save_default_settings'));
	}

	/**
	 * Save default settings
	 *
	 * @since 8.9.0
	 *
	 * @return void
	 */
	public function save_default_settings()
	{
		$appearance = monsterinsights_get_option('verified_appearance', false);
		$position = monsterinsights_get_option('verified_position', false);
		if (!$appearance) {
			monsterinsights_update_option('verified_appearance', 'light');
		}
		if (!$position) {
			monsterinsights_update_option('verified_position', 'center');
		}
	}

	/**
	 * Show badge in footer if Automatic mode is enabled.
	 *
	 * @since 8.9.0
	 *
	 * @return void
	 */
	public function show_automatic_footer_badge()
	{
		if (!monsterinsights_get_option('verified_automatic')) {
			return;
		}

		$atts = array(
			'appearance' => monsterinsights_get_option('verified_appearance'),
			'position'   => monsterinsights_get_option('verified_position'),
		);
		// output escaped in render_badge function
		echo $this->render_badge($atts); // phpcs:ignore
	}
}

MonsterInsights_VerifiedBadge_Controller::get_instance()->run();
