/* global YoastGAPointerL10n */
/* global yoastGAPointers */
/* jshint browser: true */
/* jshint -W097 */
'use strict';

/**
 * Prototype to load the pointers for the tour.
 *
 * @constructor
 */
var YoastGAPointers = function() {
	// Don't show the tour on screens with an effective width smaller than 1024px or an effective height smaller than 768px.
	if ( jQuery( window ).width() < 1024 || jQuery( window ).availWidth < 1024 ) {
		return;
	}

	this.preparePointerOptions();

	this.init();
};

/**
 * Prepares the options needed to setup the pointers.
 */
YoastGAPointers.prototype.preparePointerOptions = function() {
	this.pointerOptions = YoastGAPointerL10n.options;

	this.pointerOptions.buttons = function ( event, t ) {
		var button = jQuery('<a href="' + YoastGAPointerL10n.ignore_url + '" id="pointer-close" style="margin:0 5px;" class="button-secondary">' + YoastGAPointerL10n.close_button_text + '</a>');
		button.bind( 'click.pointer', function () {
			t.element.pointer( 'close' );
		} );
		return button;
	};

	this.pointerOptions.close = function() {};
};

/**
 * Inserts the primary button in a specific pointer.
 *
 * @param {jQuery.Element} The pointer we insert the button in.
 */
YoastGAPointers.prototype.insertPrimaryButton = function( pointer ) {
	if ( YoastGAPointerL10n.buttons.primary_button.text ) {
		pointer.find( '#pointer-close' ).after( '<a id="pointer-primary" class="button-primary">' + YoastGAPointerL10n.buttons.primary_button.text + '</a>' );
		pointer.find('#pointer-primary').click( function() {
			window.location = YoastGAPointerL10n.buttons.primary_button.location;
		});
	}
};

/**
 * Inserts the previous button in a specific pointer.
 *
 *	@param {jQuery.Element} The pointer we insert the button in.
 */
YoastGAPointers.prototype.insertPreviousButton = function( pointer ) {
	if ( YoastGAPointerL10n.buttons.previous_button.text ) {
		pointer.find( '#pointer-primary').after( '<a id="pointer-ternary" style="float: left;" class="button-secondary">' + YoastGAPointerL10n.buttons.previous_button.text + '</a>' );
		pointer.find( '#pointer-ternary' ).click( function() {
			window.location = YoastGAPointerL10n.buttons.previous_button.location;
		} );
	}
};

/**
 * Setup the pointer and insert the navigation.
 */
YoastGAPointers.prototype.setup = function() {
	jQuery( YoastGAPointerL10n.selector ).pointer( yoastGAPointers.pointerOptions ).pointer( 'open' );

	var lastOpenedPointer = jQuery( '.wp-pointer').slice( -1 );

	yoastGAPointers.insertPrimaryButton( lastOpenedPointer );
	yoastGAPointers.insertPreviousButton( lastOpenedPointer );
};

/**
 * Bind the setup callback to wp pointers or the document.
 */
YoastGAPointers.prototype.init = function() {
	if ( this.pointerOptions.position && this.pointerOptions.position.defer_loading ) {
		jQuery( window ).bind( 'load.wp-pointers', this.setup );
	}
	else {
		jQuery( document ).ready( this.setup );
	}
};

window.yoastGAPointers = new YoastGAPointers();