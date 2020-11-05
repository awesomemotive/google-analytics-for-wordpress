(
	function ( $ ) {

		// Add Color Picker to all inputs that have 'color-field' class.
		$( function () {
			init_color_picker();
			init_multiselect();
			$( document ).on( 'widget-updated widget-added', function (e) {
				init_color_picker();
				init_multiselect();
			} );
			$( document ).on( 'change', '.monsterinsights-save-on-change', function () {
				save_and_refresh_form( $( this ).closest( '.widget' ) );
			} );
		} );

		function init_color_picker() {
			var timeout;
			$( '#widgets-right .monsterinsights-color-field' ).wpColorPicker( {
				change: function ( event, ui ) {
					if ( timeout ) {
						clearTimeout( timeout );
					}
					timeout = setTimeout( function () {
						$( event.target ).trigger( 'change' );
					}, 300 );
				},
			} );
		}

		function save_and_refresh_form( widget ) {
			if ( wpWidgets && 'undefined' !== typeof wpWidgets.save ) {
				wpWidgets.save( widget, 0, 0 );
			}
		}

		function init_multiselect() {
			$('#widgets-right .monsterinsights-multiselect').select2({
				ajax: {
					type: 'POST',
					url: ajaxurl,
					delay: 250,
					width: 'resolve',
					data: function (params) {
						var taxonomy = $(this).data('taxonomy');
						return {
							taxonomy: taxonomy,
							keyword: params.term,
							action: 'monsterinsights_get_terms',
							nonce: monsterinsights_pp.nonce,
						};
					},
					processResults: function (data) {
						return {
							results: data.data
						};
					},
					dataType: 'json'
				}
			});

		}

	}
)( jQuery );
