<?php
/**
 * Store the time when the float bar was hidden so it won't show again for 14 days.
 */
function monsterinsights_mark_floatbar_hidden() {
	check_ajax_referer( 'mi-admin-nonce', 'nonce' );
	update_option( 'monsterinsights_float_bar_hidden', time() );
	wp_send_json_success();
}

add_action( 'wp_ajax_monsterinsights_hide_floatbar', 'monsterinsights_mark_floatbar_hidden' );

/**
 * Store the time when the float bar was hidden so it won't show again for 14 days.
 */
function monsterinsights_get_floatbar() {
	check_ajax_referer( 'mi-admin-nonce', 'nonce' );

	$show_floatbar = get_option( 'monsterinsights_float_bar_hidden', 0 );
	if ( time() - $show_floatbar > 14 * DAY_IN_SECONDS ) {
		$show_floatbar = true;
	} else {
		$show_floatbar = false;
	}

	wp_send_json( array(
		'show' => $show_floatbar,
	) );

}

add_action( 'wp_ajax_monsterinsights_get_floatbar', 'monsterinsights_get_floatbar' );

/**
 * Admin menu tooltip.
 */
function monsterinsights_get_admin_menu_tooltip() {

	$show_tooltip = get_option( 'monsterinsights_admin_menu_tooltip', 0 );
	$activated    = get_option( 'monsterinsights_over_time', array() );
	$ua_code      = monsterinsights_get_ua();

	if ( monsterinsights_is_reports_page() || monsterinsights_is_settings_page() ) {
		// Don't show on MI pages.
		return;
	}

	if ( ! current_user_can( 'monsterinsights_save_settings' ) ) {
		return;
	}

	if ( $show_tooltip && $show_tooltip + 30 * DAY_IN_SECONDS > time() ) {
		// Dismissed less than 30 days ago.
		return;
	}

	if ( empty( $activated['installed_date'] ) || ( $activated['installed_date'] + 30 * DAY_IN_SECONDS > time() ) || empty( $ua_code ) ) {
		return;
	}
	// More than 30 days since it was installed & is tracking.
	$url = monsterinsights_get_upgrade_link( 'menu-tooltip', 'upgrade' );
	?>
	<div id="monterinsights-admin-menu-tooltip" class="monterinsights-admin-menu-tooltip-hide">
		<div class="monsterinsights-admin-menu-tooltip-header">
			<span class="monsterinsights-admin-menu-tooltip-icon"><span
						class="dashicons dashicons-megaphone"></span></span>
			<?php esc_html_e( 'Get Better Insights. Grow FASTER!', 'google-analytics-for-wordpress' ); ?>
			<a href="#" class="monsterinsights-admin-menu-tooltip-close"><span
						class="dashicons dashicons-dismiss"></span></a>
		</div>
		<div class="monsterinsights-admin-menu-tooltip-content">
			<strong><?php esc_html_e( 'Grow Your Business with MonsterInsights Pro', 'google-analytics-for-wordpress' ); ?></strong>
			<p><?php esc_html_e( 'It\'s easy to double your traffic and sales when you know exactly how people find and use your website.', 'google-analytics-for-wordpress' ); ?></p>
			<p><?php esc_html_e( 'MonsterInsights Pro shows you the stats that matter, so you can boost your business growth!', 'google-analytics-for-wordpress' ); ?></p>
			<p>
				<?php
				// Translators: makes text bold.
				printf( esc_html__( '%1$sBonus:%2$s You also get 50%% off discount for being a loyal MonsterInsights Lite user.', 'google-analytics-for-wordpress' ), '<strong>', '</strong>' );
				?>
			</p>
			<p>
				<a href="<?php echo esc_url( $url ); ?>"
				   class="button button-primary"><?php esc_html_e( 'Upgrade to MonsterInsights Pro', 'google-analytics-for-wordpress' ); ?></a>
			</p>
		</div>
	</div>
	<style type="text/css">
		#monterinsights-admin-menu-tooltip {
			position: absolute;
			left: 100%;
			top: 100%;
			background: #fff;
			margin-left: 16px;
			width: 350px;
			box-shadow: 0px 4px 7px 0px #ccc;
		}

		#monterinsights-admin-menu-tooltip:before {
			content: '';
			width: 0;
			height: 0;
			border-style: solid;
			border-width: 12px 12px 12px 0;
			border-color: transparent #fff transparent transparent;
			position: absolute;
			right: 100%;
			top: 130px;
			z-index: 10;
		}

		#monterinsights-admin-menu-tooltip:after {
			content: '';
			width: 0;
			height: 0;
			border-style: solid;
			border-width: 13px 13px 13px 0;
			border-color: transparent #ccc transparent transparent;
			position: absolute;
			right: 100%;
			margin-left: -1px;
			top: 129px;
			z-index: 5;
		}

		#monterinsights-admin-menu-tooltip.monsterinsights-tooltip-arrow-top:before {
			top: 254px;
		}

		#monterinsights-admin-menu-tooltip.monsterinsights-tooltip-arrow-top:after {
			top: 253px;
		}

		.monsterinsights-admin-menu-tooltip-header {
			background: #03a0d2;
			padding: 5px 12px;
			font-size: 14px;
			font-weight: 700;
			font-family: Arial, Helvetica, "Trebuchet MS", sans-serif;
			color: #fff;
			line-height: 1.6;
		}

		.monsterinsights-admin-menu-tooltip-icon {
			background: #fff;
			border-radius: 50%;
			width: 28px;
			height: 25px;
			display: inline-block;
			color: #03a0d2;
			text-align: center;
			padding: 3px 0 0;
			margin-right: 6px;
		}

		.monterinsights-admin-menu-tooltip-hide {
			display: none;
		}

		.monsterinsights-admin-menu-tooltip-content {
			padding: 20px 15px 7px;
		}

		.monsterinsights-admin-menu-tooltip-content strong {
			font-size: 14px;
		}

		.monsterinsights-admin-menu-tooltip-content p strong {
			font-size: 13px;
		}

		.monsterinsights-admin-menu-tooltip-close {
			color: #fff;
			text-decoration: none;
			position: absolute;
			right: 10px;
			top: 12px;
			display: block;
		}

		.monsterinsights-admin-menu-tooltip-close:hover {
			color: #fff;
			text-decoration: none;
		}

		.monsterinsights-admin-menu-tooltip-close .dashicons {
			font-size: 14px;
		}

		@media ( max-width: 782px ) {
			#monterinsights-admin-menu-tooltip {
				display: none;
			}
		}
	</style>
	<script type="text/javascript">
		if ( 'undefined' !== typeof jQuery ) {
			jQuery( function ( $ ) {
				var $tooltip = $( document.getElementById( 'monterinsights-admin-menu-tooltip' ) );
				var $menuwrapper = $( document.getElementById( 'adminmenuwrap' ) );
				var $menuitem = $( document.getElementById( 'toplevel_page_monsterinsights_reports' ) );
				if ( 0 === $menuitem.length ) {
					$menuitem = $( document.getElementById( 'toplevel_page_monsterinsights_network' ) );
				}
				if ( 0 === $menuitem.length ) {
					$menuitem = $( document.getElementById( 'toplevel_page_monsterinsights_settings' ) );
				}
				if ( 0 === $menuitem.length ) {
					return;
				}

				if ( $menuitem.length ) {
					$menuwrapper.append( $tooltip );
					$tooltip.removeClass( 'monterinsights-admin-menu-tooltip-hide' );
				}

				function alignTooltip() {
					var sticky = $( 'body' ).hasClass( 'sticky-menu' );

					var menuitem_pos = $menuitem.position();
					var tooltip_top = menuitem_pos.top - 124;
					if ( sticky && $( window ).height() > $menuwrapper.height() + 150 ) {
						$tooltip.removeClass( 'monsterinsights-tooltip-arrow-top' );
					} else {
						tooltip_top = menuitem_pos.top - 250;
						$tooltip.addClass( 'monsterinsights-tooltip-arrow-top' );
					}
					// Don't let the tooltip go outside of the screen and make the close button not visible.
					if ( tooltip_top < 40 ) {
						tooltip_top = 40;
					}
					$tooltip.css( {
						top: tooltip_top + 'px'
					} );
				}

				var $document = $( document );
				var timeout = setTimeout( alignTooltip, 10 );
				$document.on( 'wp-pin-menu wp-window-resized.pin-menu postboxes-columnchange.pin-menu postbox-toggled.pin-menu wp-collapse-menu.pin-menu wp-scroll-start.pin-menu', function () {
					if ( timeout ) {
						clearTimeout( timeout );
					}
					timeout = setTimeout( alignTooltip, 10 );
				} );

				$( '.monsterinsights-admin-menu-tooltip-close' ).on( 'click', function ( e ) {
					e.preventDefault();
					hideTooltip();
				} );

				function hideTooltip() {
					$tooltip.addClass( 'monterinsights-admin-menu-tooltip-hide' );
					$.post( ajaxurl, {
						action: 'monsterinsights_hide_admin_menu_tooltip',
						nonce: '<?php echo esc_js( wp_create_nonce( 'mi-admin-nonce' ) ); ?>',
					} );
				}
			} );
		}
	</script>
	<?php
}

add_action( 'adminmenu', 'monsterinsights_get_admin_menu_tooltip' );

/**
 * Store the time when the float bar was hidden so it won't show again for 14 days.
 */
function monsterinsights_mark_admin_menu_tooltip_hidden() {
	check_ajax_referer( 'mi-admin-nonce', 'nonce' );
	update_option( 'monsterinsights_admin_menu_tooltip', time() );
	wp_send_json_success();
}

add_action( 'wp_ajax_monsterinsights_hide_admin_menu_tooltip', 'monsterinsights_mark_admin_menu_tooltip_hidden' );
