<?php

/**
 * Fetching and formatting Info Blocks for Email Summaries class.
 *
 * @since 8.19.0
 */
class MonsterInsights_Summaries_InfoBlocks {

	/**
	 * Source of info blocks content.
	 *
	 * @since 8.19.0
	 */
	const SOURCE_URL = 'https://plugin-cdn.monsterinsights.com/summaries.json';

	/**
	 * Fetch info blocks info from remote.
	 *
	 * @return array
	 * @since 8.19.0
	 *
	 */
	public function fetch_data() {
		$info = array();

		$res = wp_remote_get( self::SOURCE_URL );

		if ( is_wp_error( $res ) ) {
			return $info;
		}

		$body = wp_remote_retrieve_body( $res );

		if ( empty( $body ) ) {
			return $info;
		}

		$body = json_decode( $body, true );

		return $this->verify_fetched( $body );
	}

	/**
	 * Verify fetched blocks data.
	 *
	 * @param array $fetched Fetched blocks data.
	 *
	 * @return array
	 * @since 8.19.0
	 *
	 */
	protected function verify_fetched( $fetched ) {
		$info = array();

		if ( ! is_array( $fetched ) ) {
			return $info;
		}

		if ( empty( $fetched['status'] ) || empty( $fetched['summaries'] ) || empty( $fetched['default'] ) ) {
			return $info;
		}

		$info['status']                               = $fetched['status'];
		$info['default']                              = array();
		$info['default'][ $fetched['default']['id'] ] = $fetched['default'];
		$info['summaries']                            = array();

		foreach ( $fetched['summaries'] as $item ) {

			if ( empty( $item['id'] ) ) {
				continue;
			}

			$id = absint( $item['id'] );

			if ( empty( $id ) ) {
				continue;
			}

			$info['summaries'][ $id ] = $item;
		}

		return $info;
	}

	/**
	 * Get info blocks relevant to customer's licence.
	 *
	 * @return array
	 * @since 8.19.0
	 *
	 */
	protected function get_by_license() {
		$data     = $this->fetch_data();
		$data     = isset( $data['summaries'] ) ? $data['summaries'] : '';
		$filtered = array();

		if ( empty( $data ) || ! is_array( $data ) ) {
			return $filtered;
		}

		$has_level    = monsterinsights_is_pro_version() ? MonsterInsights()->license->get_license_type() : false;
		$license_type = $has_level ? $has_level : 'lite';

		foreach ( $data as $key => $item ) {

			if ( ! isset( $item['type'] ) || ! is_array( $item['type'] ) ) {
				continue;
			}

			if ( ! in_array( $license_type, $item['type'], true ) ) {
				continue;
			}

			$filtered[ $key ] = $item;
		}

		return $filtered;
	}

	/**
	 * Filter info blocks by current date
	 *
	 * @return array
	 * @since 8.19.0
	 *
	 */
	protected function filter_by_current_date( $data ) {
		if ( empty( $data ) ) {
			return;
		}
		// Loop through the $data, check if the items have start & end date. If start & end date range available in current date then add that item to a new array
		$data_by_date = array();

		foreach ( $data as $key => $item ) {
			$start        = isset( $item['start'] ) ? $item['start'] : '';
			$end          = isset( $item['end'] ) ? $item['end'] : '';
			$current_time = time();

			if ( ! empty( $start ) && empty( $end ) && $current_time >= $start ) {
				$data_by_date[ $key ] = $item;
			}

			if ( empty( $start ) && ! empty( $end ) && $current_time <= $end ) {
				$data_by_date[ $key ] = $item;
			}

			if ( $current_time >= $start && $current_time <= $end ) {
				$data_by_date[ $key ] = $item;
			}

		}

		if ( empty( $data_by_date ) ) {
			return $data;
		}

		$blocks_sent = get_option( 'monsterinsights_email_summaries_infoblocks_sent' );

		if ( empty( $blocks_sent ) || ! is_array( $blocks_sent ) ) {
			return $data_by_date;
		}

		// find unused items from the new array and return
		$filtered_blocks = array_diff_key( $data_by_date, array_flip( $blocks_sent ) );
		if ( ! empty( $filtered_blocks ) ) {
			return $filtered_blocks;
		}

		return $data;
	}

	/**
	 * Get the first block with a valid id.
	 * Needed to ignore blocks with invalid/missing ids.
	 *
	 * @param array $data Blocks array.
	 *
	 * @return array
	 * @since 8.19.0
	 *
	 */
	protected function get_first_with_id( $data ) {

		if ( empty( $data ) || ! is_array( $data ) ) {
			return array();
		}

		foreach ( $data as $item ) {
			$item_id = absint( $item['id'] );
			if ( ! empty( $item_id ) ) {
				return $item;
			}
		}

		return array();
	}

	/**
	 * Get next info block that wasn't sent yet.
	 *
	 * @return array
	 * @since 8.19.0
	 *
	 */
	public function get_next() {

		$data  = $this->get_by_license();
		$block = array();

		// if there is no data related to license then send default info block
		if ( empty( $data ) || ! is_array( $data ) ) {
			$all_data = $this->fetch_data();

			if ( ! isset( $all_data['default'] ) || empty( $all_data['default'] ) ) {
				return $block;
			}

			$data = $all_data['default'];
		} else {
			$data = $this->filter_by_current_date( $data );
		}

		$blocks_sent = get_option( 'monsterinsights_email_summaries_infoblocks_sent' );

		if ( empty( $blocks_sent ) || ! is_array( $blocks_sent ) ) {
			$block = $this->get_first_with_id( $data );
		}

		if ( empty( $block ) ) {
			// check for new info block
			$unused_info_blocks = array_diff_key( $data, array_flip( $blocks_sent ) );

			if ( ! empty( $unused_info_blocks ) ) {
				$block = $this->get_first_with_id( $unused_info_blocks );
			} else {
				// if there is no new block then send the recent info block again
				$block = $this->get_first_with_id( $data );
			}

		}

		return $block;
	}

	/**
	 * Register a block as sent.
	 *
	 * @param array $info_block Info block.
	 *
	 * @since 8.19.0
	 *
	 */
	public function register_sent( $info_block ) {

		$block_id = isset( $info_block['id'] ) ? absint( $info_block['id'] ) : false;

		if ( empty( $block_id ) ) {
			return;
		}

		$option_name = 'monsterinsights_email_summaries_infoblocks_sent';
		$blocks      = get_option( $option_name );

		if ( empty( $blocks ) || ! is_array( $blocks ) ) {
			update_option( $option_name, array( $block_id ) );

			return;
		}

		if ( in_array( $block_id, $blocks, true ) ) {
			return;
		}

		$blocks[] = $block_id;

		update_option( $option_name, $blocks );
	}

}
