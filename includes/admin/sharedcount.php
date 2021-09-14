<?php

/**
 * Handles the SharedCount integration and count grabbing.
 *
 * Class MonsterInsights_SharedCount
 */
class MonsterInsights_SharedCount {

	/**
	 * The action used to schedule daily events.
	 *
	 * @var string
	 */
	public $cron_key = 'monsterinsights_sharedcount_daily_update';
	/**
	 * Index progress key.
	 *
	 * @var string
	 */
	public static $progress_key = 'monsterinsights_sharedcount_index_progress';
	/**
	 * Index progress.
	 *
	 * @var array
	 */
	public static $progress;
	/**
	 * The error message from the api call.
	 *
	 * @var string
	 */
	public $error;
	/**
	 * The API endpoint.
	 *
	 * @var string
	 */
	private $endpoint = 'https://api.sharedcount.com/v1.0/';
	/**
	 * The API key to use for the requests.
	 *
	 * @var string
	 */
	private $api_key;
	/**
	 * If the current query needs to run again.
	 *
	 * @var bool
	 */
	private $more_pages = false;

	/**
	 * MonsterInsights_SharedCount constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_monsterinsights_sharedcount_start_indexing', array( $this, 'ajax_start_indexing' ) );
		add_action( 'wp_ajax_monsterinsights_sharedcount_get_index_progress', array(
			$this,
			'ajax_get_index_progress'
		) );

		add_action( 'monsterinsights_sharedcount_get_more_posts', array( $this, 'get_more_counts' ) );

		add_action( 'monsterinsights_sharedcount_bulk_grab', array( $this, 'grab_and_store_bulk_by_id' ), 10, 2 );

		add_action( $this->cron_key, array( $this, 'daily_cron_update' ) );
	}

	/**
	 * AJAX handler from the Vue app that checks if the API key is set and handles
	 * an error message from the SharedCount API call. If the first call is successful it will schedule
	 * a daily cron to keep the counts fresh.
	 */
	public function ajax_start_indexing() {

		check_ajax_referer( 'mi-admin-nonce', 'nonce' );

		if ( $this->get_api_key() ) {
			if ( $this->start_posts_count() ) {
				$this->schedule_daily_update();
				wp_send_json_success( array(
					'max_pages' => $this->more_pages,
				) );
			} else {
				wp_send_json_error( array(
					'message' => $this->error,
				) );
			}
		}

		// No API key, let's send an error message.
		wp_send_json_error( array(
			'message' => esc_html__( 'The SharedCount API key is not set', 'google-analytics-for-wordpress' ),
		) );

	}

	/**
	 * Get the API key.
	 *
	 * @return string
	 */
	public function get_api_key() {

		if ( empty( $this->api_key ) ) {
			$this->api_key = monsterinsights_get_option( 'sharedcount_key' );
		}

		return $this->api_key;
	}

	/**
	 * Start a grabbing process that will schedule events to grab more pages if needed.
	 *
	 * @return bool
	 */
	public function start_posts_count() {

		return $this->get_more_counts( 1 );

	}

	/**
	 * Handler for the scheduled event to grab more data for sites with large number of posts.
	 * This is also used by the first call and uses the return value to determine if an error was encountered.
	 * The error gets set in the $error property and used for display.
	 *
	 * @param int $page The page number to grab counts for.
	 *
	 * @return bool
	 */
	public function get_more_counts( $page ) {

		$urls         = $this->get_post_urls( $page );
		$urls_as_keys = $this->urls_as_keys( $urls );

		if ( $this->use_bulk_api() ) {
			$bulk_request = $this->post_bulk_urls( $urls );

			if ( $bulk_request && ! empty( $bulk_request['bulk_id'] ) ) {
				$this->grab_and_store_bulk_by_id( $bulk_request['bulk_id'], $urls_as_keys );
			} else {
				return false;
			}
		} else {
			$store_counts = $this->grab_counts_one_by_one( $urls );
			if ( ! $store_counts ) {
				// Error encountered, return error.
				return false;
			}
		}

		$this->save_progress( $page, $this->more_pages );

		if ( $this->more_pages ) {
			$page ++;
			$this->schedule_next_page( $page );
		}

		return true;

	}

	/**
	 * Save the current indexing progress.
	 *
	 * @param int $page The current page.
	 * @param int $max_pages The total number of pages.
	 */
	public function save_progress( $page, $max_pages ) {
		update_option( self::$progress_key, array(
			'page'      => $page,
			'max_pages' => $max_pages,
		), false );
	}

	/**
	 * Reset the progress option. Used for when the cron is disabled.
	 */
	public function reset_progress() {
		delete_option( self::$progress_key );
	}

	/**
	 * Use WP_Query to get a list of URLs to query SharedCount for share data.
	 *
	 * @param int $page The page number.
	 *
	 * @return array
	 */
	public function get_post_urls( $page = 1 ) {

		$posts_args  = array(
			'posts_per_page'   => 100, // Don't try to load more than 500 posts at once.
			'fields'           => 'ids', // Load just the ids.
			'paged'            => $page,
			'suppress_filters' => true, // Avoid loading additional functionality from other plugins/theme.
		);
		$posts_query = new WP_Query( $posts_args );
		$urls        = array();

		if ( $posts_query->have_posts() ) {
			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();

				$urls[ get_the_ID() ] = get_permalink( get_the_ID() );
			}
		}

		if ( $posts_query->max_num_pages > $page ) {
			$this->more_pages = $posts_query->max_num_pages;
		} else {
			$this->more_pages = false;
		}

		wp_reset_postdata();

		return $urls;
	}

	/**
	 * Use URLs as array keys to make it easier to match with the post id.
	 *
	 * @param array $urls The urls with post ids as keys.
	 *
	 * @return array
	 */
	public function urls_as_keys( $urls ) {

		$urls_as_keys = array();
		foreach ( $urls as $id => $url ) {
			$urls_as_keys[ $url ] = $id;
		}

		return $urls_as_keys;

	}

	/**
	 * Helper method for using the bulk API. Disabled by default as the free api doesn't have access to it.
	 * This can be used by large sites to use less requests to the SharedCount API and grab data more efficiently
	 * if they have a paid license.
	 *
	 * @return mixed|void
	 */
	public function use_bulk_api() {
		// Bulk API is not available for free sharedcount accounts so let's set this to off by default.
		return apply_filters( 'monsterinsights_sharedcount_use_bulk_api', false );
	}

	/**
	 * Use the bulk API method to post data to the SharedCount API.
	 *
	 * @param array $urls An array with the URLs to be sent in the bulk request.
	 *
	 * @return bool|mixed
	 */
	public function post_bulk_urls( $urls ) {

		$body = implode( "\n", $urls );

		$request_url = add_query_arg(
			array(
				'apikey' => $this->get_api_key(),
			),
			$this->get_api_url( 'bulk' )
		);

		$request = wp_remote_post( $request_url, array(
			'body' => $body,
		) );

		$response        = wp_remote_retrieve_body( $request );
		$parsed_response = json_decode( $response, true );
		if ( 200 === wp_remote_retrieve_response_code( $request ) ) {
			return $parsed_response;
		} else {
			$this->handle_api_error( $parsed_response );

			return false;
		}
	}

	/**
	 * Get the API url.
	 *
	 * @param string $path The API path to use e.g. "bulk".
	 *
	 * @return string
	 */
	public function get_api_url( $path = '' ) {
		// Allow users to override the SharedCount URL if they have a custom URL.
		return apply_filters( 'monsterinsights_sharedcount_api_url', $this->endpoint . $path );
	}

	/**
	 * Generic handler for error responses from the SharedCount API.
	 * This uses the $error property to pass the error back for being displayed.
	 *
	 * @param array $parsed_response The response object from a SharedCount API call converted to an Array.
	 */
	public function handle_api_error( $parsed_response ) {
		if ( isset( $parsed_response['Error'] ) && isset( $parsed_response['Type'] ) && 'invalid_api_key' === $parsed_response['Type'] ) {
			$error = esc_html__( 'The SharedCount API key is invalid', 'google-analytics-for-wordpress' );
		} elseif ( ! empty( $parsed_response['quota_exceeded'] ) ) {
			$error = $parsed_response['quota_exceeded'];
		} else {
			$error = isset( $parsed_response['Error'] ) ? $parsed_response['Error'] : esc_html__( 'There was an error grabbing data from SharedCount, please check the API Key', 'google-analytics-for-wordpress' );
		}
		$this->error = $error;
	}

	/**
	 * Attempt to grab bulk data from the API by bulk id, if the bulk request is not completed
	 * schedule an event to try again in a minute.
	 *
	 * @param string $bulk_id The bulk id from the SharedCount bulk post request.
	 * @param array  $urls_as_keys An array of URLs where the keys are the URLs and the values are the post ids.
	 */
	public function grab_and_store_bulk_by_id( $bulk_id, $urls_as_keys ) {
		$bulk_data = $this->get_bulk_data( $bulk_id );
		// If the processing for the current bulk id is not completed schedule a single event to try again.
		if ( $bulk_data['_meta']['completed'] ) {
			$this->store_bulk_data( $bulk_data, $urls_as_keys );
		} else {
			$this->schedule_bulk_grabbing( $bulk_id, $urls_as_keys );
		}
	}

	/**
	 * Grab data from the SharedCount API using their Bulk API.
	 *
	 * @param string $bulk_id The bulk id from a POST request to the bulk API.
	 *
	 * @return bool|mixed
	 * @see MonsterInsights_SharedCount::post_bulk_urls()
	 *
	 */
	public function get_bulk_data( $bulk_id ) {

		$request_url = add_query_arg(
			array(
				'bulk_id' => $bulk_id,
				'apikey'  => $this->get_api_key(),
			),
			$this->get_api_url()
		);

		$request = wp_remote_get( $request_url );

		if ( 200 === wp_remote_retrieve_response_code( $request ) ) {
			$response        = wp_remote_retrieve_body( $request );
			$parsed_response = json_decode( $response, true );

			return $parsed_response;
		} else {
			return false;
		}
	}

	/**
	 * Iterate through the bulk data returned and store it in the post meta.
	 *
	 * @param array $bulk_data The bulk data response from the SharedCount API.
	 * @param array $urls_as_keys An array of URLs where the keys are the URLs and the values are the post ids.
	 */
	public function store_bulk_data( $bulk_data, $urls_as_keys ) {
		if ( ! empty( $bulk_data['data'] ) && is_array( $bulk_data['data'] ) ) {
			foreach ( $bulk_data['data'] as $url => $values ) {
				$post_id = array_key_exists( $url, $urls_as_keys ) ? $urls_as_keys[ $url ] : false;

				if ( $post_id ) {
					$this->store_post_counts( $post_id, $values );
				}
			}
		}
	}

	/**
	 * Save the post counts response to the post meta.
	 * The total value is saved separately for querying.
	 *
	 * @param int   $post_id The post id to save to.
	 * @param array $values The array of values received from the SharedCount API.
	 *
	 * @see MonsterInsights_SharedCount::get_counts_by_url()
	 */
	public function store_post_counts( $post_id, $values ) {
		$total_count = $this->combine_counts( $values );
		update_post_meta( $post_id, '_monsterinsights_sharedcount_total', $total_count );
		update_post_meta( $post_id, '_monsterinsights_sharedcount_values', $values );
	}

	/**
	 * Process a SharedCounts response and compile all counts into one number.
	 *
	 * @param array $response Array from decoding the API JSON response.
	 *
	 * @return int
	 */
	public function combine_counts( $response ) {

		$total = 0;
		if ( ! isset( $response['Error'] ) ) {
			foreach ( $response as $count ) {
				if ( is_int( $count ) ) {
					$total += $count;
				} elseif ( is_array( $count ) && isset( $count['share_count'] ) ) {
					$total += $count['share_count'];
				}
			}
		}

		return $total;
	}

	/**
	 * If the bulk request is not completed we need to schedule it to try again later.
	 *
	 * @param string $bulk_id The bulk id from the SharedCount bulk post request.
	 * @param array  $urls_as_keys An array of URLs where the keys are the URLs and the values are the post ids.
	 *
	 * @see MonsterInsights_SharedCount::post_bulk_urls()
	 * @see MonsterInsights_SharedCount::grab_and_store_bulk_by_id()
	 */
	public function schedule_bulk_grabbing( $bulk_id, $urls_as_keys ) {

		wp_schedule_single_event( time() + 60, 'monsterinsights_sharedcount_bulk_grab', array(
			'bulk_id' => $bulk_id,
			'urls'    => $urls_as_keys,
		) );

	}

	/**
	 * The SharedCount Bulk API is not available for free users so we need
	 * to use multiple calls to the API to grab data.
	 *
	 * @param array $urls An array of urls with the post ids as keys.
	 *
	 * @return bool
	 * @see MonsterInsights_SharedCount::get_post_urls()
	 *
	 */
	private function grab_counts_one_by_one( $urls ) {

		foreach ( $urls as $id => $url ) {
			$counts = $this->get_counts_by_url( $url );

			if ( $counts ) {
				$this->store_post_counts( $id, $counts );
			} else {
				// Return false to display error message from API request.
				return false;
			}
		}

		return true;

	}

	/**
	 * Request the SharedCount data from the API by URL.
	 *
	 * @param string $url The URL to request data for.
	 *
	 * @return bool|mixed
	 */
	public function get_counts_by_url( $url ) {

		$url         = apply_filters( 'monsterinsights_sharedcount_url_pre_grab', $url );
		$request_url = add_query_arg(
			array(
				'url'    => $url,
				'apikey' => $this->get_api_key(),
			),
			$this->get_api_url()
		);

		$request         = wp_remote_get( $request_url );
		$response        = wp_remote_retrieve_body( $request );
		$parsed_response = json_decode( $response, true );
		if ( 200 === wp_remote_retrieve_response_code( $request ) ) {
			return $parsed_response;
		} else {
			$this->handle_api_error( $parsed_response );

			return false;
		}

	}

	/**
	 * Schedule a single event for the next page in the WP Query to be grabbed.
	 *
	 * @param int $page The page number.
	 */
	public function schedule_next_page( $page ) {

		wp_schedule_single_event( time() + 60, 'monsterinsights_sharedcount_get_more_posts', array( 'page' => $page ) );

	}

	/**
	 * This schedules the daily event with the first one in 24hrs from the current time.
	 */
	public function schedule_daily_update() {

		if ( ! wp_next_scheduled( $this->cron_key ) ) {
			wp_schedule_event( time() + DAY_IN_SECONDS, 'daily', $this->cron_key );
		}

	}

	/**
	 * Cron handler that checks if the sorting method is still set to SharedCount.
	 * If the sorting method changed, it will disable the daily cron.
	 */
	public function daily_cron_update() {
		$sort_option = monsterinsights_get_option( 'popular_posts_inline_sort', 'comments' );

		if ( 'sharedcount' === $sort_option ) {
			$this->start_posts_count();
		} else {
			$this->disable_counts_updates();
		}
	}

	/**
	 * Disable cron and reset progress.
	 */
	public function disable_counts_updates() {
		// If we are no longer using this option disable the cron.
		wp_clear_scheduled_hook( $this->cron_key );
		$this->reset_progress();
	}

	/**
	 * Get the post counts based on a post id.
	 * Not used currently.
	 *
	 * @param int $post_id The id of the post.
	 *
	 * @return bool|mixed
	 */
	public function get_post_counts( $post_id ) {
		$post_url = get_permalink( $post_id );

		return $this->combine_counts( $this->get_counts_by_url( $post_url ) );
	}

	/**
	 * Get the indexing progress as percent.
	 *
	 * @return int
	 */
	public static function get_index_progress_percent() {

		$progress = self::get_index_progress();

		if ( ! empty( $progress ) && ! empty( $progress['page'] ) && ! empty( $progress['max_pages'] ) ) {
			$progress = 100 / $progress['max_pages'] * $progress['page'];
			$progress = floor( $progress );

			return $progress;
		} elseif ( isset( $progress['max_pages'] ) && false === $progress['max_pages'] ) {
			return 100;
		}

		return 0;

	}

	/**
	 * Get the current progress.
	 *
	 * @return array
	 */
	public static function get_index_progress() {

		if ( empty( self::$progress ) ) {
			self::$progress = get_option( self::$progress_key, array() );
		}

		return self::$progress;

	}

	/**
	 * Get the index progress with ajax.
	 */
	public function ajax_get_index_progress() {
		wp_send_json( array(
			'progress' => self::get_index_progress_percent(),
		) );
	}

	/**
	 * Get the top popular posts by SharedCount shares.
	 *
	 * @param int $count The number of posts to get.
	 *
	 * @return array
	 */
	public static function query_popular_posts( $count = 5 ) {

		$popular_posts_args  = array(
			'posts_per_page' => $count,
			'meta_value'     => 'monsterinsights_sharedcount_total',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
		);
		$popular_posts_query = new WP_Query( $popular_posts_args );
		$popular_posts       = array();

		if ( $popular_posts_query->have_posts() ) {
			while ( $popular_posts_query->have_posts() ) {
				$popular_posts_query->the_post();
				$popular_posts[ get_the_ID() ] = array(
					'post_title' => get_the_title(),
					'permalink'  => get_permalink(),
					'thumbnail'  => get_the_post_thumbnail_url( get_the_ID(), 'medium' ),
				);
			}
		}

		wp_reset_postdata();

		return $popular_posts;

	}

}

new MonsterInsights_SharedCount();
