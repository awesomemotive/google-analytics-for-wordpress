jQuery( document ).ready( function( $ ) {
	var is_active = jQuery('#adminmenu a[href$="admin.php?page=monsterinsights_tracking"]').hasClass('current');
	jQuery('#adminmenu a[href$="admin.php?page=monsterinsights_tracking"]').parent().remove();

	if ( is_active ) {
		jQuery('#adminmenu a[href$="admin.php?page=monsterinsights_settings"]').addClass('current');
		jQuery('#adminmenu a[href$="admin.php?page=monsterinsights_settings"]').parent().addClass('current');
	}
	
	/**
	* Dismissable Notices
	* - Sends an AJAX request to mark the notice as dismissed
	*/
	$( 'div.monsterinsights-notice' ).on( 'click', 'button.notice-dismiss', function( e ) {
		e.preventDefault();
		$( this ).closest( 'div.monsterinsights-notice' ).fadeOut();

		// If this is a dismissible notice, it means we need to send an AJAX request
		if ( $( this ).parent().hasClass( 'is-dismissible' ) ) {
			$.post(
				monsterinsights_admin_common.ajax,
				{
					action: 'monsterinsights_ajax_dismiss_notice',
					nonce:  monsterinsights_admin_common.dismiss_notice_nonce,
					notice: $( this ).parent().data( 'notice' )
				},
				function( response ) {},
				'json'
			);
		}

	} );
});