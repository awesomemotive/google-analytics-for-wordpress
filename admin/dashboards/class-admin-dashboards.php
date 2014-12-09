<?php

if ( ! class_exists( 'Yoast_GA_Dashboards' ) ) {

	class Yoast_GA_Dashboards {

		/**
		 * Store the data aggregator
		 *
		 * @package
		 */
		public $aggregator;

		/**
		 * Store the Data instance
		 *
		 * @package
		 */
		public $data;

		/**
		 * Store the active metrics
		 *
		 * @var
		 */
		public $active_metrics;

		/**
		 * Store the valid metrics which are available in the Google API, more can be added
		 *
		 * @var array
		 *
		 * @link https://ga-dev-tools.appspot.com/explorer/
		 */
		private $valid_metrics = array( 'sessions', 'bounces', 'users', 'newUsers', 'percentNewSessions', 'bounceRate', 'sessionDuration', 'avgSessionDuration', 'hits' );

		/**
		 * Store this instance
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Construct on the dashboards class for GA
		 */
		protected function __construct() {
			add_filter( 'ga_extend_dashboards', array( $this, 'extend_dashboards' ), 10, 1 );
		}

		/**
		 * Init the dashboards
		 *
		 * @param integer $ga_profile_id
		 */
		public function init_dashboards( $ga_profile_id ) {

			$dashboards = array(
				'sessions'      => array(
					'title' => __( 'Sessions', 'google-analytics-for-wordpress' ),
					'help'  => __( 'A session is a group of interactions that take place on your website within a given time frame. For example a single session can contain multiple screen or page views, events, social interactions, and ecommerce transactions. <a href="http://yoa.st/gasessions" target="_blank">[Learn more]</a>', 'google-analytics-for-wordpress' ),
					'type'  => 'graph',
					'tab'   => 'general',
				),
				'bounceRate'    => array(
					'title'        => __( 'Bounce rate', 'google-analytics-for-wordpress' ),
					'help'         => __( 'Bounce Rate is the percentage of single-page sessions (i.e. sessions in which the person left your site from the entrance page without interacting with the page). <a href="http://yoa.st/gabounce" target="_blank">[Learn more]</a>', 'google-analytics-for-wordpress' ),
					'data-percent' => true,
					'type'         => 'graph',
					'tab'          => 'general',
				),
				'source'        => array(
					'title'   => __( 'Traffic sources', 'google-analytics-for-wordpress' ),
					'help'    => __( 'Every referral to a web site has an origin, or (traffic) source. Possible sources include: “google” (the name of a search engine), “facebook.com” (the name of a referring site), “spring_newsletter” (the name of one of your newsletters), and “direct” (users that typed your URL directly into their browser, or who had bookmarked your site). <a href="http://yoa.st/gabnce" target="_blank">[Learn more]</a>', 'google-analytics-for-wordpress' ),
					'type'    => 'table',
					'columns' => array(
						__( 'Sessions', 'google-analytics-for-wordpress' )
					),
					'tab'     => 'dimensions',
				),
				'top_pageviews' => array(
					'title'   => __( 'Popular pages', 'google-analytics-for-wordpress' ),
					'help'    => __( 'Pages by url.', 'google-analytics-for-wordpress' ),
					'type'    => 'table',
					'columns' => array(
						__( 'Sessions', 'google-analytics-for-wordpress' )
					),
					'tab'     => 'dimensions',
				),
				'top_countries' => array(
					'title'   => __( 'Countries', 'google-analytics-for-wordpress' ),
					'help'    => __( 'The country or territory from which visits originated. <a href="http://yoa.st/gacountry" target="_blank">[Learn more]</a>', 'google-analytics-for-wordpress' ),
					'type'    => 'table',
					'columns' => array(
						__( 'Sessions', 'google-analytics-for-wordpress' )
					),
					'tab'     => 'dimensions',
				),
			);

			$this->extend_dashboards( $dashboards );

			// Register the active metrics
			$register = array_keys( $dashboards );

			$this->aggregator = new Yoast_GA_Dashboards_Collector( $ga_profile_id, $register, $this->valid_metrics );

			$this->register( $register );
		}

		/**
		 * Adding dashboards for front-end
		 *
		 * By hook as filter: $dashboards = apply_filters( 'ga_extend_dashboards', $dashboards);
		 *
		 * @param $dashboards
		 *
		 * @return mixed
		 */
		public function extend_dashboards( $dashboards ) {
			// Initialize the dashboard graphs
			Yoast_GA_Dashboards_Display::get_instance()->add_dashboards( $dashboards );

			return $dashboards;
		}

		/**
		 * Get the instance
		 *
		 * @return Yoast_GA_Dashboards
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Register the dashboard types
		 *
		 * @param $types array or singular string
		 *
		 * @return bool
		 */
		public function register( $types ) {
			if ( is_array( $types ) == false ) {
				$types = array( $types );
			}

			if ( is_array( $types ) && count( $types ) >= 1 ) {
				if ( $this->validate_dashboard_types( $types ) ) {
					$this->active_metrics = $types;

					return true;
				}
			}

			return false;
		}

		/**
		 * Adding translations to ga-admin-dashboard
		 */
		public function add_dashboard_js_translations() {
			// Now we can localize the script with our data.
			$translation_array = array(
				// For datatables
				'sort_ascending'      => __( ': activate to sort column ascending', 'google-analytics-for-wordpress' ),
				'sort_descending'     => __( ': activate to sort column descending', 'google-analytics-for-wordpress' ),
				'empty_table'         => __( 'No data available', 'google-analytics-for-wordpress' ),
				'info'                => _x( 'Showing _START_ to _END_ of _TOTAL_ rows', '_START_, _END_ and _TOTAL_ will be replaced by JS (See: http://datatables.net/reference/option/language.info)', 'google-analytics-for-wordpress' ),
				'info_empty'          => __( 'No rows to show', 'google-analytics-for-wordpress' ),
				'info_filtered'       => _x( '(filtered from _MAX_ total rows)', '_MAX_ will be replaced by JS (See: http://datatables.net/reference/option/language.infoFiltered)', 'google-analytics-for-wordpress' ),
				'length_menu'         => _x( 'Show _MENU_ rows', '_MAX_ will be replaced by JS', 'google-analytics-for-wordpress' ),
				'loading_records'     => __( 'Loading...', 'google-analytics-for-wordpress' ),
				'pagination_first'    => __( 'First', 'google-analytics-for-wordpress' ),
				'pagination_last'     => __( 'Last', 'google-analytics-for-wordpress' ),
				'pagination_next'     => __( 'Next', 'google-analytics-for-wordpress' ),
				'pagination_previous' => __( 'Previous', 'google-analytics-for-wordpress' ),
				'processing'          => __( 'Processing...', 'google-analytics-for-wordpress' ),
				'search_placeholder'  => __( 'Search', 'google-analytics-for-wordpress' ),
				'zero_records'        => __( 'No matching records found', 'google-analytics-for-wordpress' ),

				// For dimensions
				'dimensions'          => __( 'Reports', 'google-analytics-for-wordpress' ),
				'custom_dimensions'   => __( 'Custom dimension reports', 'google-analytics-for-wordpress' ),
			);

			wp_localize_script( 'ga-admin-dashboard', 'dashboard_translate', $translation_array );

		}

		/**
		 * Validate the registered types of dashboards
		 *
		 * @param $types
		 *
		 * @return bool
		 */
		private function validate_dashboard_types( $types ) {
			$valid = true;

			if ( is_array( $types ) ) {
				foreach ( $types as $check_type ) {
					if ( ! in_array( $check_type, $this->valid_metrics ) ) {
						$valid = false;
					}
				}
			}

			return $valid;
		}
	}

}