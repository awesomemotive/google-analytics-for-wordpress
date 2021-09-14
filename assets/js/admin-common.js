jQuery( document ).ready( function( $ ) {
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

var submenu_item = document.querySelector( '.monsterinsights-upgrade-submenu' );
if ( null !== submenu_item ) {
	var li = submenu_item.parentNode.parentNode;
	if ( li ) {
		li.classList.add( 'monsterinsights-submenu-highlight' );
	}
}
