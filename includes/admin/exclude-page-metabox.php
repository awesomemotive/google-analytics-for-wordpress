<?php

/**
 * Metabox class.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'MonsterInsights_MetaBox_ExcludePage' ) ) {
	class MonsterInsights_MetaBox_ExcludePage {

		public function __construct() {
			add_action( 'init', [ $this, 'register_meta' ] );

			if ( ! is_admin() ) {
				return;
			}

			add_action( 'load-post.php', [ $this, 'meta_box_init' ] );
			add_action( 'load-post-new.php', [ $this, 'meta_box_init' ] );
		}

		private function is_gutenberg_editor() {
			if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
				return true;
			}

			$current_screen = get_current_screen();
			if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
				return true;
			}

			return false;
		}

		private function posttype_supports_gutenberg() {
			return post_type_supports( monsterinsights_get_current_post_type(), 'custom-fields' );
		}

		private function get_current_post_type() {
			global $post;

			if ( $post && $post->post_type ) {
				return $post->post_type;
			}

			global $typenow;

			if ( $typenow ) {
				return $typenow;
			}

			global $current_screen;

			if ( $current_screen && $current_screen->post_type ) {
				return $current_screen->post_type;
			}

			if ( isset( $_REQUEST['post_type'] ) ) {
				return sanitize_key( $_REQUEST['post_type'] );
			}

			return null;
		}

		public function meta_box_init() {
			$post_type = $this->get_current_post_type();
			if ( ! is_post_type_viewable( $post_type ) ) {
				return;
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'load_metabox_styles' ) );
			if ( $this->is_gutenberg_editor() && $this->posttype_supports_gutenberg() ) {
				return;
			}
			add_action( 'add_meta_boxes', [ $this, 'create_meta_box' ] );
		}

		public function register_meta() {
			if ( ! function_exists( 'register_post_meta' ) ) {
				return;
			}

			register_post_meta(
				'',
				'_monsterinsights_skip_tracking',
				[
					'auth_callback' => '__return_true',
					'default'       => false,
					'show_in_rest'  => true,
					'single'        => true,
					'type'          => 'boolean',
				]
			);
		}

		public function create_meta_box() {
			add_meta_box(
				'monsterinsights-metabox',
				'MonsterInsights',
				[ $this, 'print_metabox_html' ],
				null,
				'side',
				'high'
			);
		}

		public function print_metabox_html( $post ) {
			$skipped = (bool) get_post_meta( $post->ID, '_monsterinsights_skip_tracking', true );
			wp_nonce_field( 'monsterinsights_metabox', 'monsterinsights_metabox_nonce' );
			?>
			<div class="monsterinsights-metabox" id="monsterinsights-metabox-skip-tracking">
				<div class="monsterinsights-metabox-input-checkbox">
					<label class="">
						<input type="checkbox" name="_monsterinsights_skip_tracking"
							   value="1" <?php checked( $skipped ); ?> <?php disabled( ! monsterinsights_is_pro_version() ); ?>>
						<span
							class="monsterinsights-metabox-input-checkbox-label"><?php _e( 'Exclude page from Google Analytics Tracking', 'google-analytics-for-wordpress' ); ?></span>
					</label>
				</div>
				<div class="monsterinsights-metabox-helper">
					<?php _e( 'Toggle to prevent Google Analytics from tracking this page.', 'google-analytics-for-wordpress' ); ?>
				</div>
			</div>

			<?php do_action( 'monsterinsights_after_exclude_metabox', $skipped, $post ); ?>

			<?php if ( ! monsterinsights_is_pro_version() ) { ?>
				<div class="monsterinsights-metabox-pro-badge">
                        <span>
                            <svg width="15" height="14" viewBox="0 0 15 14" fill="none"
								 xmlns="http://www.w3.org/2000/svg">
                            <path
								d="M6.57617 1.08203L4.92578 4.45898L1.19336 4.99219C0.533203 5.09375 0.279297 5.90625 0.761719 6.38867L3.42773 9.00391L2.79297 12.6855C2.69141 13.3457 3.40234 13.8535 3.98633 13.5488L7.3125 11.7969L10.6133 13.5488C11.1973 13.8535 11.9082 13.3457 11.8066 12.6855L11.1719 9.00391L13.8379 6.38867C14.3203 5.90625 14.0664 5.09375 13.4062 4.99219L9.69922 4.45898L8.02344 1.08203C7.74414 0.498047 6.88086 0.472656 6.57617 1.08203Z"
								fill="#31862D"/>
                            </svg>
                            <?php _e( 'This is a PRO feature.', 'google-analytics-for-wordpress' ); ?>
                        </span>
					<div class="monsterinsights-metabox-pro-badge-upgrade">
						<a href="<?php echo monsterinsights_get_upgrade_link( 'exclude-page-tracking', 'lite-metabox', "https://www.monsterinsights.com/lite/" ); // phpcs:ignore ?>"
						   target="_blank" rel="noopener">
							<?php _e( 'Upgrade', 'google-analytics-for-wordpress' ); ?>
						</a>
					</div>
				</div>
			<?php } ?>

			<?php
		}

		public function load_metabox_styles() {
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_register_style( 'monsterinsights-admin-metabox-style', plugins_url( 'assets/css/admin-metabox' . $suffix . '.css', MONSTERINSIGHTS_PLUGIN_FILE ), array(), monsterinsights_get_asset_version() );
			wp_enqueue_style( 'monsterinsights-admin-metabox-style' );

			if ( monsterinsights_is_pro_version() ) {
				return;
			}

			wp_register_script( 'monsterinsights-admin-metabox-script', plugins_url( 'assets/js/admin-metabox' . $suffix . '.js', MONSTERINSIGHTS_PLUGIN_FILE ), array( 'jquery' ), monsterinsights_get_asset_version() );
			wp_enqueue_script( 'monsterinsights-admin-metabox-script' );
		}
	}

	new MonsterInsights_MetaBox_ExcludePage();
}
