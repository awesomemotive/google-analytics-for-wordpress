<?php
/**
 * This is the abstract class to be used as a base for Site Insights block templates.
 *
 * @package MonsterInsights
 */

/**
 * Class MonsterInsights_SiteInsights_Metric_Template
 */
abstract class MonsterInsights_SiteInsights_Metric_Template {

	/**
	 * The metric that we want to display.
	 *
	 * @var string
	 */
	protected $metric;

	/**
	 * The type that we want to display.
	 *
	 * @varstring
	 */
	protected $type;

	/**
	 * Block attributes received at init.
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * GA data received at init.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * A method that should prepare chart options or scorecards data for the `output` method.
	 *
	 * @return array
	 */
	abstract protected function get_options();

	/**
	 * The method that returns the template for the given metric and block type.
	 *
	 * @return mixed
	 */
	abstract public function output();

	public function __construct( $attributes, $data ){
		$this->attributes = $attributes;
		$this->data = $data;
	}

	/**
	 * Returns the JSON version of `get_options`.
	 *
	 * @return false|string
	 */
	public function get_json_data() {
		$data = $this->get_options();

		if (empty($data)){
			return false;
		}

		return json_encode($data);
	}

	/**
	 * If color value is in preset format, convert it to a CSS var. Else return same value
	 * For example:
	 * "var:preset|color|pale-pink" -> "var(--wp--preset--color--pale-pink)"
	 * "#98b66e" -> "#98b66e"
	 *
	 * @param string $color_value value to be processed.
	 *
	 * @return (string)
	 */
	public static function get_color_value( $color_value ) {
		if ( is_string( $color_value ) && false !== strpos( $color_value, 'var:preset|color|' ) ) {
			$color_value = str_replace( 'var:preset|color|', '', $color_value );
			return sprintf( 'var(--wp--preset--color--%s)', $color_value );
		}

		return $color_value;
	}
}