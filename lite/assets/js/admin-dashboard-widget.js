function MiWidget() {

	var self = this,
		$ = window.jQuery,
		$widget_element = $( document.getElementById( 'monsterinsights_reports_widget' ) ),
		$widget_title = $widget_element.find( '.hndle' ),
		$widget_controls = $widget_element.find( '.mi-dw-controls' ),
		$normal_sortables = $( document.getElementById( 'normal-sortables' ) ),
		$welcome_panel = $( document.getElementById( 'welcome-panel' ) ),
		$lite_content = $widget_element.find( '.mi-dw-lite-content' );

	this.init = function () {
		// Stop loading early if MI is not authenticated.
		if ( ! this.is_authed() ) {
			return false;
		}
		this.add_widget_toggle();
		this.add_events();
		this.tooltips();
	};


	this.add_widget_toggle = function () {
		$widget_controls.appendTo( $widget_title );
		$widget_element.addClass( 'mi-loaded' );
	};

	this.add_events = function () {
		$widget_controls.on( 'click', 'label,button', function ( e ) {
			e.stopPropagation();
			self.shake_content();
		} );
	};

	this.shake_content = function ( el ) {
		$lite_content.addClass( 'mi-animation-shake' );
		setTimeout( function () {
			$lite_content.removeClass( 'mi-animation-shake' );
		}, 1000 );
	};

	this.is_authed = function () {
		return ! (
			$widget_element.find( '.mi-dw-not-authed' ).length > 0
		);
	};

	this.tooltips = function () {
		$( '.mi-dw-styled-toggle' ).tooltip( {
			tooltipClass: 'mi-dw-ui-tooltip',
			position: {my: 'center bottom-12', at: 'center top', collision: 'flipfit'},
		} );
	};

	this.init();
}

new MiWidget();
