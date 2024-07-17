<?php
/**
 * This file contains class that will be extended by other
 * providers metabox classes.
 *
 * @since 8.7.0
 *
 * @package MonsterInsights
 * @subpackage MonsterInsights_User_Journey
 */

abstract class MonsterInsights_User_Journey_Lite_Metabox {

	/**
	 * URL to assets folder.
	 *
	 * @since 8.7.0
	 *
	 * @var string
	 */
	public $assets_url = MONSTERINSIGHTS_PLUGIN_URL . 'lite/includes/admin/user-journey/assets/';

	/**
	 * Get Currently loaded provider name.
	 *
	 * @return string
	 * @since 8.7.0
	 *
	 */
	abstract protected function get_provider();

	/**
	 * Metabox Title.
	 *
	 * @return void
	 * @since 8.7.0
	 *
	 */
	protected function metabox_title() {
		return '';
	}

	/**
	 * Contains HTML to display inside the metabox
	 *
	 * @return void
	 * @since 8.7.0
	 *
	 */
	public function metabox_html() {
		$image        = $this->assets_url . 'img/Frame.png';
		$utm_provider = $this->get_provider() . '-user-journey';
		$learn_more   = monsterinsights_get_upgrade_link( $utm_provider, 'lite-user-journey', 'https://monsterinsights.com' );
		$upgrade_link = monsterinsights_get_upgrade_link( $utm_provider, 'lite-user-journey', 'https://monsterinsights.com/lite' );
		?>
		<!-- User Journey metabox -->
		<?php $this->metabox_title(); ?>
		<div id="monsterinsights-user-journey-lite-metabox-container">
			<div class="monsterinsights-lite-uj-backdrop-pic"
				 style="background-image: url( '<?php echo esc_url( $this->assets_url ); ?>img/user-journey-backdrop.png' )"></div>
			<div id="monsterinsights-lite-entry-user-journey" class="postbox">
				<div class="monsterinsights-lite-uj-container desktop">
					<div class="monsterinsights-lite-uj-modal-head">
						<h3><?php esc_html_e( 'Unlock User Journey', 'monsterinsights' ); ?></h3>
					</div>
					<div class="monsterinsights-lite-uj-modal-content">
						<div class="monsterinsights-lite-modal-left">
							<h4><?php esc_html_e( 'With MonsterInsights Pro, See Each Step Your Visitor Took Before Purchasing From Your Website.', 'monsterinsights' ); ?></h4>
							<p>
								<?php
								// Translators: strong tag to make text bold, link to website to learn more
								echo sprintf(
									esc_html__('%1$sPlus%2$s, upgrading to pro will unlock %3$sall%4$s of advanced reports, tracking, and integrations. %5$sLearn more about Pro%6$s', 'monsterinsights'),
									'<strong>',
									'</strong>',
									'<strong>',
									'</strong>',
									'<a target="_blank" href="' . esc_url($learn_more) . '" title="'.  esc_attr__('Upgrade', 'monsterinsights') .'">',
									'</a>'
								); ?>
							</p>
							<a target="_blank" href="<?php echo esc_url( $upgrade_link ); ?>" title=""
							   class="monsterinsights-uj-button monsterinsights-button">
								<?php esc_html_e( 'Upgrade and Unlock', 'monsterinsights' ); ?>
								<svg width="13" height="15" viewBox="0 0 13 15" fill="none"
									 xmlns="http://www.w3.org/2000/svg">
									<path
										d="M11.3125 7.25H4.53125V4.43359C4.53125 3.36719 5.37891 2.46484 6.47266 2.4375C7.56641 2.4375 8.46875 3.33984 8.46875 4.40625V4.84375C8.46875 5.22656 8.74219 5.5 9.125 5.5H10C10.3555 5.5 10.6562 5.22656 10.6562 4.84375V4.40625C10.6562 2.10938 8.76953 0.25 6.47266 0.25C4.17578 0.277344 2.34375 2.16406 2.34375 4.46094V7.25H1.6875C0.949219 7.25 0.375 7.85156 0.375 8.5625V12.9375C0.375 13.6758 0.949219 14.25 1.6875 14.25H11.3125C12.0234 14.25 12.625 13.6758 12.625 12.9375V8.5625C12.625 7.85156 12.0234 7.25 11.3125 7.25Z"
										fill="white"/>
								</svg>
							</a>
						</div>
						<div class="monsterinsights-lite-modal-right">
							<img src="<?php echo esc_url( $image ); ?>" alt="Frame"/>
						</div>
					</div>
				</div>
				<div class="monsterinsights-lite-uj-container mobile">
					<div class="monsterinsights-lite-uj-modal-content">
						<div class="monsterinsights-lite-modal-left">
							<h4><?php esc_html_e( 'User Journey', 'monsterinsights' ); ?></h4>
							<p><?php esc_html_e( 'See each step your visitor took before purchasing from your site', 'monsterinsights' ); ?></p>
						</div>
					</div>
				</div>
			</div>
			<div class="monsterinsights-lite-uj-upgrade">
				<p>
					<svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M5.395 10.039C4.745 10.039 4.134 9.91767 3.562 9.675C2.99 9.42367 2.48733 9.08133 2.054 8.648C1.62067 8.206 1.27833 7.699 1.027 7.127C0.784333 6.555 0.663 5.93967 0.663 5.281C0.663 4.61367 0.784333 3.994 1.027 3.422C1.27833 2.84133 1.62067 2.33867 2.054 1.914C2.48733 1.48067 2.99 1.147 3.562 0.912999C4.134 0.670333 4.745 0.548999 5.395 0.548999C6.06233 0.548999 6.682 0.674666 7.254 0.926C7.83467 1.16867 8.34167 1.511 8.775 1.953C9.20833 2.38633 9.54633 2.889 9.789 3.461C10.0317 4.02433 10.153 4.631 10.153 5.281C10.153 5.93967 10.0273 6.555 9.776 7.127C9.53333 7.699 9.19533 8.206 8.762 8.648C8.32867 9.08133 7.82167 9.42367 7.241 9.675C6.669 9.91767 6.05367 10.039 5.395 10.039ZM2.821 8.765L5.395 6.893L7.995 8.765L7.007 5.827L9.399 4.215H6.461L5.395 1.043L4.355 4.215H1.417L3.809 5.827L2.821 8.765Z"
							fill="#31862D"/>
					</svg>
					<?php esc_html_e( 'This is a PRO feature.', 'monsterinsights' ); ?>
					<a target="_blank" href="<?php echo esc_url( $upgrade_link ); ?>"
					   title=""><?php esc_html_e( 'Upgrade', 'monsterinsights' ); ?></a>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Check if an array is a valid array and not empty.
	 * This will also check if a key exists inside an array
	 * if the param is set to true.
	 *
	 * @param array $array Array to check.
	 * @param string $key Array key to check.
	 * @param boolean $check_key Wether to check the key or not.
	 *
	 * @return boolean
	 * @since 8.7.0
	 *
	 */
	public static function is_valid_array( $array, $key, $check_key = false ) {
		if ( is_array( $array ) ) {
			if ( ! empty( $array ) ) {
				if ( $check_key ) {
					if ( array_key_exists( $key, $array ) ) {
						return true;
					} else {
						return false;
					}
				}

				return true;
			} else {
				return false;
			}
		}

		return false;
	}
}
