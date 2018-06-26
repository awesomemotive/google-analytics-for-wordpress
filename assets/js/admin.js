/** 
 * Developer's Notice:
 * 
 * Note: JS in this file (and this file itself) is not garunteed backwards compatibility. JS can be added, changed or removed at any time without notice.
 * For more information see the `Backwards Compatibility Guidelines for Developers` section of the README.md file.
 */
/**
 * Handles:
 * - Copy to Clipboard functionality
 * - Dismissable Notices
 *
 * @since 6.0.0
 */

(function() {
	var list, dbjsError,
		errors = [];

	window.onerror = function( errorMsg, url, lineNumber ) {
		if ( ! document.getElementById( 'monsterinsights-ublock-origin-error' ) )
			errors[ errors.length ] = [errorMsg, url, lineNumber];
		else
			dbjsError(errorMsg, url, lineNumber);
	};

	jQuery(document).ready( function(){
		for ( var err in errors )
			dbjsError( errors[err][0], errors[err][1], errors[err][2] );

	});

	dbjsError = function( errorMsg, url, lineNumber ) {

		var errorLine, place, button, tab;


		if ( !list )
			list = document.getElementById( 'monsterinsights-ublock-origin-error' );

		if (!list )
			return; // threw way too early... @todo cache these?

		errorLine = document.createElement( 'li' );
		errorLine.className = 'debug-bar-js-error';
		errorLine.textContent = errorMsg + ' on ';
		place = document.createElement( 'span' );
		place.textContent = url + ' line ' + lineNumber;
		errorLine.appendChild( place );
		list.appendChild( errorLine );

	};

})();

jQuery( document ).ready( function( $ ) {
	// Disable function
	jQuery.fn.extend({
		disable: function(state) {
			return this.each(function() {
				this.disabled = state;
			});
		}
	});

	jQuery("#screen-meta-links").prependTo("#monsterinsights-header-temp");
	jQuery("#screen-meta").prependTo("#monsterinsights-header-temp");

	// Tooltips
	jQuery('.monsterinsights-help-tip').tooltip({
		content: function() {
			return jQuery(this).prop('title');
		},
		tooltipClass: 'monsterinsights-ui-tooltip',
		position: {
			my: 'center top',
			at: 'center bottom+10',
			collision: 'flipfit',
		},
		hide: {
			duration: 200,
		},
		show: {
			duration: 200,
		},
	});

	// Reports Tooltips
	jQuery("body").tooltip({
		selector: '.monsterinsights-reports-uright-tooltip',
		items: "[data-tooltip-title], [data-tooltip-description]",
		content: function() {
			return '<div class="monsterinsights-reports-tooltip-title">' + jQuery(this).data("tooltip-title") + '</div>' +
				   '<div class="monsterinsights-reports-tooltip-content">' + jQuery(this).data("tooltip-description") + '</div>';
		},
		tooltipClass: 'monsterinsights-reports-ui-tooltip',
		position: { my: "right-10 top", at: "left top", collision: "flipfit" },
		hide: {duration: 200},
		show: {duration: 200},
	});

	/**
	* Copy to Clipboard
	*/
	if ( typeof Clipboard !== 'undefined' ) {
		var monsterinsights_clipboard = new Clipboard( '.monsterinsights-copy-to-clipboard' );
		jQuery( document ).on( 'click', '.monsterinsights-copy-to-clipboard', function( e ) {
			e.preventDefault();
		} );

		function fallbackMessage(action){
			var actionMsg='';var actionKey=(action==='cut'?'X':'C');
			if (/iPhone|iPad/i.test(navigator.userAgent ) ) {
				actionMsg='No support :(';
			} else if (/Mac/i.test(navigator.userAgent ) ) {
				actionMsg='Press ⌘-'+ actionKey+' to '+ action;
			} else { 
				actionMsg='Press Ctrl-'+ actionKey+' to '+ action; 
			}
			return actionMsg;
		}
		monsterinsights_clipboard.on('success',function(e){
			e.trigger.textContent = monsterinsights_admin.copied;
			window.setTimeout(function() {
				e.trigger.textContent = monsterinsights_admin.copytoclip;
			}, 2000);
		});
		monsterinsights_clipboard.on('error',function(e){
			e.trigger.textContent = fallbackMessage(e.action);
		});
	}

	function modelMatcher(params, data) {
			data.parentText = data.parentText || "";

			// Always return the object if there is nothing to compare
			if (jQuery.trim(params.term) === '') {
				return data;
			}

			// Do a recursive check for options with children
			if (data.children && data.children.length > 0) {
				// Clone the data object if there are children
				// This is required as we modify the object to remove any non-matches
				var match = $.extend(true, {}, data);

				// Check each child of the option
				for (var c = data.children.length - 1; c >= 0; c--) {
					var child = data.children[c];
					child.parentText += data.parentText + " " + data.text;

					var matches = modelMatcher(params, child);

					// If there wasn't a match, remove the object in the array
					if (matches == null) {
						match.children.splice(c, 1);
					}
				}

				// If any children matched, return the new object
				if (match.children.length > 0) {
					return match;
				}

				// If there were no matching children, check just the plain object
				return modelMatcher(params, match);
			}

			// If the typed-in term matches the text of this term, or the text from any
			// parent term, then it's a match.
			var original = (data.parentText + ' ' + data.text).toUpperCase();
			var term = params.term.toUpperCase();

			// Check if the text contains the term
			if (original.indexOf(term) > -1) {
				return data;
			}

			// If it doesn't contain the term, don't return anything
			return null;
		}


	// Setup Select2
		jQuery('.monsterinsights-select300').select300();

		var fields_changed = false;
		jQuery(document).on('change', '#monsterinsights-settings :input', function(){
			fields_changed = true;
		});
		
		jQuery(document).on('click', 'a:not(.monsterinsights-settings-click-excluded)', function( e ){ 

			if ( fields_changed ) { 
				var answer = confirm( monsterinsights_admin.settings_changed_confirm );
				if ( answer ){
				   fields_changed = false;
				   return true;
				} else {
					e.preventDefault();
					return false;
				}
			} 
		});


	// Auth Actions
		// Auth and Reauth
			jQuery('#monsterinsights-google-authenticate-submit').on( "click", function( e ) {
				e.preventDefault();
				swal({
				  type: 'info',
				  title: monsterinsights_admin.redirect_loading_title_text,
				  text: monsterinsights_admin.redirect_loading_text_text,
				  allowOutsideClick: false,
				  allowEscapeKey: false,
				  allowEnterKey: false,
				  onOpen: function () {
					swal.showLoading();
				  }
				}).catch(swal.noop);
				var data = { 
					'action': 'monsterinsights_maybe_authenticate', 
					'nonce':  monsterinsights_admin.admin_nonce,
					'isnetwork': monsterinsights_admin.isnetwork
				};
				jQuery.post(ajaxurl, data, function( response ) {
					if ( response.success ) {
						window.location = response.data.redirect;
					} else {
						swal({
							type: 'error',
							  title: monsterinsights_admin.redirect_loading_error_title,
							  text: response.data.message,
							  confirmButtonText: monsterinsights_admin.ok_text,
						  }).catch(swal.noop);
					}
				}).fail( function(xhr, textStatus, errorThrown) {
					var message = jQuery(xhr.responseText).text();
					message = message.substring(0, message.indexOf("Call Stack"));
					swal({
						type: 'error',
						  title: monsterinsights_admin.redirect_loading_error_title,
						  text: message,
						  confirmButtonText: monsterinsights_admin.ok_text,
					  }).catch(swal.noop);
				});
			});

		// Reauth
			jQuery('#monsterinsights-google-reauthenticate-submit').on( "click", function( e ) {
				e.preventDefault();
				swal({
				  type: 'info',
				  title: monsterinsights_admin.redirect_loading_title_text,
				  text: monsterinsights_admin.redirect_loading_text_text,
				  allowOutsideClick: false,
				  allowEscapeKey: false,
				  allowEnterKey: false,
				  onOpen: function () {
					swal.showLoading();
				  }
				}).catch(swal.noop);
				var data = { 
					'action': 'monsterinsights_maybe_reauthenticate',
					'nonce':  monsterinsights_admin.admin_nonce,
					'isnetwork': monsterinsights_admin.isnetwork
				};
				jQuery.post(ajaxurl, data, function( response ) {
					if ( response.success ) {
						window.location = response.data.redirect;
					} else {
						swal({
							type: 'error',
							  title: monsterinsights_admin.redirect_loading_error_title,
							  text: response.data.message,
							  confirmButtonText: monsterinsights_admin.ok_text,
						  }).catch(swal.noop);
					}
				}).fail( function(xhr, textStatus, errorThrown) {
					var message = jQuery(xhr.responseText).text();
					message = message.substring(0, message.indexOf("Call Stack"));
					swal({
						type: 'error',
						  title: monsterinsights_admin.redirect_loading_error_title,
						  text: message,
						  confirmButtonText: monsterinsights_admin.ok_text,
					  }).catch(swal.noop);
				});
			});

		// Verify
			jQuery('#monsterinsights-google-verify-submit').on( "click", function( e ) {
				e.preventDefault();
				swal({
				  type: 'info',
				  title: monsterinsights_admin.verify_loading_title_text,
				  text: monsterinsights_admin.verify_loading_text_text,
				  allowOutsideClick: false,
				  allowEscapeKey: false,
				  allowEnterKey: false,
				  onOpen: function () {
					swal.showLoading();
				  }
				}).catch(swal.noop);
				var data = { 
					'action': 'monsterinsights_maybe_verify',
					'nonce':  monsterinsights_admin.admin_nonce,
					'isnetwork': monsterinsights_admin.isnetwork
				};
				jQuery.post(ajaxurl, data, function( response ) {
				if ( response.success ) {
					swal({
						type: 'success',
						  title: monsterinsights_admin.verify_success_title_text,
						  text: monsterinsights_admin.verify_success_text_text,
						  confirmButtonText: monsterinsights_admin.ok_text,
					  }).catch(swal.noop);
				} else {
					swal({
						type: 'error',
						  title: monsterinsights_admin.verify_loading_error_title,
						  text: response.data.message,
						  confirmButtonText: monsterinsights_admin.ok_text,
					  }).catch(swal.noop);
				}
				}).fail( function(xhr, textStatus, errorThrown) {
					var message = jQuery(xhr.responseText).text();
					message = message.substring(0, message.indexOf("Call Stack"));
					swal({
						type: 'error',
						  title: monsterinsights_admin.verify_loading_error_title,
						  text: message,
						  confirmButtonText: monsterinsights_admin.ok_text,
					  }).catch(swal.noop);
				});
			});

		// Delete
			jQuery(document).on('click','#monsterinsights-google-deauthenticate-submit', function( e ){
				e.preventDefault();
				monsterinsights_delete_auth( $(this), false );
			});

		// Force Delete
			jQuery(document).on('click','#monsterinsights-google-force-deauthenticate-submit', function( e ){
				e.preventDefault();
				monsterinsights_delete_auth( $(this), true );
			});

			function monsterinsights_delete_auth( buttonObject, force ) {
				swal({
				  type: 'info',
				  title: monsterinsights_admin.deauth_loading_title_text,
				  text: monsterinsights_admin.deauth_loading_text_text,
				  allowOutsideClick: false,
				  allowEscapeKey: false,
				  allowEnterKey: false,
				  onOpen: function () {
					swal.showLoading();
				  }
				}).catch(swal.noop);
				var data = { 
					'action': 'monsterinsights_maybe_delete', 
					'nonce':  monsterinsights_admin.admin_nonce,
					'isnetwork': monsterinsights_admin.isnetwork,
					'forcedelete' : force.toString(),
				};
				jQuery.post(ajaxurl, data, function( response ) {
				if ( response.success ) {
					swal({
						type: 'success',
						  title: monsterinsights_admin.deauth_success_title_text,
						  text: monsterinsights_admin.deauth_success_text_text,
						  confirmButtonText: monsterinsights_admin.ok_text,
						  allowOutsideClick: false,
						  allowEscapeKey: false,
						  allowEnterKey: false,
					  }).then(function () {
						location.reload();
					  }).catch(swal.noop);
				} else {
					if ( ! force ) {
						// Replace name, ID, value
						buttonObject.attr('name', 'monsterinsights-google-force-deauthenticate-submit' );
						buttonObject.attr('id', 'monsterinsights-google-force-deauthenticate-submit' );
						buttonObject.attr('value', monsterinsights_admin.force_deauth_button_text );
					}
					swal({
						type: 'error',
						  title: monsterinsights_admin.deauth_loading_error_title,
						  text: response.data.message,
						  confirmButtonText: monsterinsights_admin.ok_text,
					  }).catch(swal.noop);
				}
				}).fail( function(xhr, textStatus, errorThrown) {
					var message = jQuery(xhr.responseText).text();
					message = message.substring(0, message.indexOf("Call Stack"));
					swal({
						type: 'error',
						title: monsterinsights_admin.deauth_loading_error_title,
						text: message,
						confirmButtonText: monsterinsights_admin.ok_text,
					}).catch(swal.noop);
				});
			};

		// Tools JS
		jQuery('#monsterinsights-url-builder input').keyup(monsterinsights_update_campaign_url);
		jQuery('#monsterinsights-url-builder input').click(monsterinsights_update_campaign_url);

		function monsterinsights_update_campaign_url() {
				var domain   = jQuery('#monsterinsights-url-builer-domain').val().trim();
				var source   = jQuery('#monsterinsights-url-builer-source').val().trim();
				var medium   = jQuery('#monsterinsights-url-builer-medium').val().trim();
				var term     = jQuery('#monsterinsights-url-builer-term').val().trim();
				var content  = jQuery('#monsterinsights-url-builer-content').val().trim();
				var name     = jQuery('#monsterinsights-url-builer-name').val().trim();
				var fragment = jQuery('#monsterinsights-url-builer-fragment').is(':checked');
				var file     = domain.substring(domain.lastIndexOf("/") + 1);

				if ( fragment && file.length > 0 && file.indexOf('#') > -1 ) {
					// If we're going to use hash, but there's already a hash, use &
					fragment = '&';
				} else if ( ! fragment && file.length > 0 && file.indexOf('?') > -1 ) {
					// If we're going to use ?, but there's already one of those, use &
					fragment = '&';
				} else {
					// The attachment we want to use doesn't exist yet, use requested (? or #)
					fragment = fragment ? '#' : '?';
				}

				var html     = domain + fragment + 'utm_source=' + encodeURIComponent(source);

				if (medium) {
					html = html + '&utm_medium=' + encodeURIComponent(medium);
				}                
				if (name) {
					html = html + '&utm_campaign=' + encodeURIComponent(name);
				}
				if (term) {
					html = html + '&utm_term=' + encodeURIComponent(term);
				}
				if (content) {
					html = html + '&utm_content=' + encodeURIComponent(content);
				}


				if ( domain && source ) {
					jQuery('#monsterinsights-url-builer-url').html(html.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;"));
				} else {
					jQuery('#monsterinsights-url-builer-url').html('');
				}         
		}

		// Addons JS
			// Addon background color
				if ( jQuery( "#monsterinsights-addons" ).length !== 0 ) {
					jQuery( "#wpbody").css("background-color", "#f1f1f1");
					jQuery( "body").css("background-color", "#f1f1f1");
					jQuery( "#wpfooter").css("background-color", "#f1f1f1");
					jQuery( "#wpbody-content").css("padding-bottom", "0px");
				}

			// Addons Search
			var addon_search_timeout;
			jQuery( 'form#add-on-search input#add-on-searchbox' ).on( 'keyup', function() {

				// Clear timeout
				clearTimeout( addon_search_timeout );

				// Get the search input, heading, results and cancel elements
				var search          = jQuery( this ),
					search_terms    = jQuery( search ).val().toLowerCase(),
					search_heading  = jQuery( search ).data( 'heading' ),
					search_results  = jQuery( search ).data( 'results' ),
					search_cancel   = jQuery( search ).data( 'cancel' );

				// Show the Spinner
				jQuery( 'form#add-on-search .spinner' ).css( 'visibility', 'visible' );

				// If the search terms is less than 3 characters, show all Addons
				if ( search_terms.length < 3 ) {
					jQuery( 'div.monsterinsights-addon' ).fadeIn( 'fast', function() {
						// Hide the Spinner
						jQuery( 'form#add-on-search .spinner' ).css( 'visibility', 'hidden' );
					} );
					return;
				}

				// Iterate through the Addons, showing or hiding them depending on whether they 
				// match the given search terms.
				jQuery( 'div.monsterinsights-addon' ).each( function() {
					if ( jQuery( 'h3.monsterinsights-addon-title', jQuery( this ) ).text().toLowerCase().search( search_terms ) >= 0 ) {
						// This Addon's title does match the search terms
						// Show
						jQuery( this ).fadeIn();
					} else {
						// This Addon's title does not match the search terms
						// Hide
						jQuery( this ).fadeOut();
					}
				} );

				// Hide the Spinner
				jQuery( 'form#add-on-search .spinner' ).css( 'visibility', 'hidden' );

			} );

		// Addons Sorting
			var monsterinsights_addons_licensed_sorting = new List( 'monsterinsights-addons-licensed', {
				valueNames: [ 'monsterinsights-addon-title' ]
			} );
			var monsterinsights_addons_unlicensed_sorting = new List( 'monsterinsights-addons-unlicensed', {
				valueNames: [ 'monsterinsights-addon-title' ]
			} );
			jQuery( 'select#monsterinsights-filter-select' ).on( 'change', function() {
				if ( typeof monsterinsights_addons_licensed_sorting.sort !== 'undefined' ) {
					monsterinsights_addons_licensed_sorting.sort( 'monsterinsights-addon-title', {
						order: jQuery( this ).val(),
					} );
				}
				if ( typeof monsterinsights_addons_unlicensed_sorting.sort !== 'undefined' ) {
					monsterinsights_addons_unlicensed_sorting.sort( 'monsterinsights-addon-title', {
						order: jQuery( this ).val(),
					} );
				}
			} );

		// Re-enable install button if user clicks on it, needs creds but tries to install another addon instead.
			jQuery('#monsterinsights-addons').on('click.refreshInstallAddon', '.monsterinsights-addon-action-button', function(e) {
				var el      = jQuery(this);
				var buttons = jQuery('#monsterinsights-addons').find('.monsterinsights-addon-action-button');
				$.each(buttons, function(i, element) {
					if ( el == element ) {
						return true;
					}

					monsterinsightsAddonRefresh(element);
				});
			});

		// Activate Addon
			jQuery('#monsterinsights-addons').on('click.activateAddon', '.monsterinsights-activate-addon', function(e) {
				e.preventDefault();
				var $this = jQuery(this);

				// Remove any leftover error messages, output an icon and get the plugin basename that needs to be activated.
				jQuery('.monsterinsights-addon-error').remove();
				jQuery(this).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.activating);
				jQuery(this).next().css({'display' : 'inline-block', 'margin-top' : '0px'});
				var button  = jQuery(this);
				var plugin  = jQuery(this).attr('rel');
				var el      = jQuery(this).parent().parent();
				var message = jQuery(this).parent().parent().find('.addon-status');

				// Process the Ajax to perform the activation.
				var opts = {
					url:      ajaxurl,
					type:     'post',
					async:    true,
					cache:    false,
					dataType: 'json',
					data: {
						action: 'monsterinsights_activate_addon',
						nonce:  monsterinsights_admin.activate_nonce,
						plugin: plugin,
						isnetwork: monsterinsights_admin.isnetwork
					},
					success: function(response) {
						// If there is a WP Error instance, output it here and quit the script.
						if ( response && true !== response ) {
							jQuery(el).slideDown('normal', function() {
								jQuery(this).after('<div class="monsterinsights-addon-error"><strong>' + response.error + '</strong></div>');
								$this.next().hide();
								jQuery('.monsterinsights-addon-error').delay(3000).slideUp();
							});
							return;
						}

						// The Ajax request was successful, so let's update the output.
						if ( monsterinsights_admin.isnetwork ) {
							jQuery(button).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.networkdeactivate).removeClass('monsterinsights-activate-addon').addClass('monsterinsights-deactivate-addon');
						} else {
							jQuery(button).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.deactivate).removeClass('monsterinsights-activate-addon').addClass('monsterinsights-deactivate-addon');
						}

						jQuery(message).text(monsterinsights_admin.active);
						// Trick here to wrap a span around he last word of the status
						var heading = jQuery(message), word_array, last_word, first_part;

						word_array = heading.html().split(/\s+/); // split on spaces
						last_word = word_array.pop();             // pop the last word
						first_part = word_array.join(' ');        // rejoin the first words together

						heading.html([first_part, ' <span>', last_word, '</span>'].join(''));
						// Proceed with CSS changes
						jQuery(el).removeClass('monsterinsights-addon-inactive').addClass('monsterinsights-addon-active');
						$this.next().hide();
					},
					error: function(xhr, textStatus ,e) {
						$this.next().hide();
						return;
					}
				};
				$.ajax(opts);
			});

		// Deactivate Addon
			jQuery('#monsterinsights-addons').on('click.deactivateAddon', '.monsterinsights-deactivate-addon', function(e) {
				e.preventDefault();
				var $this = jQuery(this);

				// Remove any leftover error messages, output an icon and get the plugin basename that needs to be activated.
				jQuery('.monsterinsights-addon-error').remove();
				jQuery(this).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.deactivating);
				jQuery(this).next().css({'display' : 'inline-block', 'margin-top' : '0px'});
				var button  = jQuery(this);
				var plugin  = jQuery(this).attr('rel');
				var el      = jQuery(this).parent().parent();
				var message = jQuery(this).parent().parent().find('.addon-status');

				// Process the Ajax to perform the activation.
				var opts = {
					url:      ajaxurl,
					type:     'post',
					async:    true,
					cache:    false,
					dataType: 'json',
					data: {
						action: 'monsterinsights_deactivate_addon',
						nonce:  monsterinsights_admin.deactivate_nonce,
						plugin: plugin,
						isnetwork: monsterinsights_admin.isnetwork
					},
					success: function(response) {
						// If there is a WP Error instance, output it here and quit the script.
						if ( response && true !== response ) {
							jQuery(el).slideDown('normal', function() {
								jQuery(this).after('<div class="monsterinsights-addon-error"><strong>' + response.error + '</strong></div>');
								$this.next().hide();
								jQuery('.monsterinsights-addon-error').delay(3000).slideUp();
							});
							return;
						}

						// The Ajax request was successful, so let's update the output.
						if ( monsterinsights_admin.isnetwork ) {
							jQuery(button).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.networkactivate).removeClass('monsterinsights-deactivate-addon').addClass('monsterinsights-activate-addon');
						} else {
							jQuery(button).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.activate).removeClass('monsterinsights-deactivate-addon').addClass('monsterinsights-activate-addon');
						}

						jQuery(message).text(monsterinsights_admin.inactive);

						// Trick here to wrap a span around he last word of the status
						var heading = jQuery(message), word_array, last_word, first_part;

						word_array = heading.html().split(/\s+/); // split on spaces
						last_word = word_array.pop();             // pop the last word
						first_part = word_array.join(' ');        // rejoin the first words together

						heading.html([first_part, ' <span>', last_word, '</span>'].join(''));
						// Proceed with CSS changes
						jQuery(el).removeClass('monsterinsights-addon-active').addClass('monsterinsights-addon-inactive');
						$this.next().hide();
					},
					error: function(xhr, textStatus ,e) {
						$this.next().hide();
						return;
					}
				};
				$.ajax(opts);
			});

		// Install Addon
			jQuery('#monsterinsights-addons').on('click.installAddon', '.monsterinsights-install-addon', function(e) {
				e.preventDefault();
				var $this = jQuery(this);

				// Remove any leftover error messages, output an icon and get the plugin basename that needs to be activated.
				jQuery('.monsterinsights-addon-error').remove();
				jQuery(this).html('<i class="monsterinsights-cloud-download"></i> ' + monsterinsights_admin.installing);
				jQuery(this).next().css({'display' : 'inline-block', 'margin-top' : '0px'});
				var button  = jQuery(this);
				var plugin  = jQuery(this).attr('rel');
				var el      = jQuery(this).parent().parent();
				var message = jQuery(this).parent().parent().find('.addon-status');

				// Process the Ajax to perform the activation.
				var opts = {
					url:      ajaxurl,
					type:     'post',
					async:    true,
					cache:    false,
					dataType: 'json',
					data: {
						action: 'monsterinsights_install_addon',
						nonce:  monsterinsights_admin.install_nonce,
						plugin: plugin
					},
					success: function(response) {
						// If there is a WP Error instance, output it here and quit the script.
						if ( response.error ) {
							jQuery(el).slideDown('normal', function() {
								jQuery(button).parent().parent().after('<div class="monsterinsights-addon-error"><div class="xinterior"><p><strong>' + response.error + '</strong></p></div></div>');
								jQuery(button).html('<i class="monsterinsights-cloud-download"></i> ' + monsterinsights_admin.install);
								$this.next().hide();
								jQuery('.monsterinsights-addon-error').delay(4000).slideUp();
							});
							return;
						}

						// If we need more credentials, output the form sent back to us.
						if ( response.form ) {
							// Display the form to gather the users credentials.
							jQuery(el).slideDown('normal', function() {
								jQuery(this).after('<div class="monsterinsights-addon-error">' + response.form + '</div>');
								$this.next().hide();
							});

							// Add a disabled attribute the install button if the creds are needed.
							jQuery(button).attr('disabled', true);

							jQuery('#monsterinsights-addons').on('click.installCredsAddon', '#upgrade', function(e) {
								// Prevent the default action, let the user know we are attempting to install again and go with it.
								e.preventDefault();
								$this.next().hide();
								jQuery(this).html('<i class="monsterinsights-cloud-download"></i> ' + monsterinsights_admin.installing);
								jQuery(this).next().css({'display' : 'inline-block', 'margin-top' : '0px'});

								// Now let's make another Ajax request once the user has submitted their credentials.
								var hostname  = jQuery(this).parent().parent().find('#hostname').val();
								var username  = jQuery(this).parent().parent().find('#username').val();
								var password  = jQuery(this).parent().parent().find('#password').val();
								var proceed   = jQuery(this);
								var connect   = jQuery(this).parent().parent().parent().parent();
								var cred_opts = {
									url:      ajaxurl,
									type:     'post',
									async:    true,
									cache:    false,
									dataType: 'json',
									data: {
										action:   'monsterinsights_install_addon',
										nonce:    monsterinsights_admin.install_nonce,
										plugin:   plugin,
										hostname: hostname,
										username: username,
										password: password
									},
									success: function(response) {
										// If there is a WP Error instance, output it here and quit the script.
										if ( response.error ) {
											jQuery(el).slideDown('normal', function() {
												jQuery(button).parent().parent().after('<div class="monsterinsights-addon-error"><strong>' + response.error + '</strong></div>');
												jQuery(button).html('<i class="monsterinsights-cloud-download"></i> ' + monsterinsights_admin.install);
												$this.next().hide();
												jQuery('.monsterinsights-addon-error').delay(4000).slideUp();
											});
											return;
										}

										if ( response.form ) {
											$this.next().hide();
											jQuery('.monsterinsights-inline-error').remove();
											jQuery(proceed).val(monsterinsights_admin.proceed);
											jQuery(proceed).after('<span class="monsterinsights-inline-error">' + monsterinsights_admin.connect_error + '</span>');
											return;
										}

										// The Ajax request was successful, so let's update the output.
										jQuery(connect).remove();
										jQuery(button).show();

										if ( monsterinsights_admin.isnetwork ) {
											jQuery(button).text(monsterinsights_admin.networkactivate).removeClass('monsterinsights-install-addon').addClass('monsterinsights-activate-addon');
										} else {
											jQuery(button).text(monsterinsights_admin.activate).removeClass('monsterinsights-install-addon').addClass('monsterinsights-activate-addon');
										}

										jQuery(button).attr('rel', response.plugin);
										jQuery(button).removeAttr('disabled');
										jQuery(message).text(monsterinsights_admin.inactive);
										
										// Trick here to wrap a span around he last word of the status
										var heading = jQuery(message), word_array, last_word, first_part;

										word_array = heading.html().split(/\s+/); // split on spaces
										last_word = word_array.pop();             // pop the last word
										first_part = word_array.join(' ');        // rejoin the first words together

										heading.html([first_part, ' <span>', last_word, '</span>'].join(''));
										// Proceed with CSS changes
										jQuery(el).removeClass('monsterinsights-addon-not-installed').addClass('monsterinsights-addon-inactive');
										$this.next().hide();
									},
									error: function(xhr, textStatus ,e) {
										$this.next().hide();
										return;
									}
								};
								$.ajax(cred_opts);
							});

							// No need to move further if we need to enter our creds.
							return;
						}

						// The Ajax request was successful, so let's update the output.
						if ( monsterinsights_admin.isnetwork ) {
							jQuery(button).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.networkactivate).removeClass('monsterinsights-install-addon').addClass('monsterinsights-activate-addon');
						} else {
							jQuery(button).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.activate).removeClass('monsterinsights-install-addon').addClass('monsterinsights-activate-addon');
						}
						jQuery(button).attr('rel', response.plugin);
						jQuery(message).text(monsterinsights_admin.inactive);

						// Trick here to wrap a span around he last word of the status
						var heading = jQuery(message), word_array, last_word, first_part;

						word_array = heading.html().split(/\s+/); // split on spaces
						last_word = word_array.pop();             // pop the last word
						first_part = word_array.join(' ');        // rejoin the first words together

						heading.html([first_part, ' <span>', last_word, '</span>'].join(''));
						// Proceed with CSS changes
						jQuery(el).removeClass('monsterinsights-addon-not-installed').addClass('monsterinsights-addon-inactive');
						$this.next().hide();
					},
					error: function(xhr, textStatus ,e) {
						$this.next().hide();
						return;
					}
				};
				$.ajax(opts);
			});

		// Function to clear any disabled buttons and extra text if the user needs to add creds but instead tries to install a different addon.
			function monsterinsightsAddonRefresh(element) {
				if ( jQuery(element).attr('disabled') ) {
					jQuery(element).removeAttr('disabled');
				}

				if ( jQuery(element).parent().parent().hasClass('monsterinsights-addon-not-installed') ) {
					jQuery(element).text( monsterinsights_admin.install );
				}
			}

			jQuery(document).ready(function($) {
				monsterinsights_equalheight2column();
			});


	jQuery(document).on('click', ".monsterinsights-reports-show-selector-group > .btn", function( e ){
		e.preventDefault();
		var id = jQuery(this).attr("data-tid");
		jQuery(this).addClass("active").disable(true).siblings().removeClass("active").disable(false);
		if ( jQuery(this).hasClass("ten") ) {
			jQuery("#" + id + " .monsterinsights-reports-pages-list > .monsterinsights-listing-table-row").slice(10,50).hide();
			jQuery("#" + id + " .monsterinsights-reports-pages-list > .monsterinsights-listing-table-row").slice(0,10).show();
		} else if ( jQuery(this).hasClass("twentyfive") ) {
			jQuery("#" + id + " .monsterinsights-reports-pages-list > .monsterinsights-listing-table-row").slice(25,50).hide();
			jQuery("#" + id + " .monsterinsights-reports-pages-list > .monsterinsights-listing-table-row").slice(0,25).show();
		} else if ( jQuery(this).hasClass("fifty") ) {
			jQuery("#" + id + " .monsterinsights-reports-pages-list > .monsterinsights-listing-table-row").slice(0,50).show();
		}
	});

	/**
	 * Handles tabbed interfaces within MonsterInsights:
	 * - Settings Page
	 * - Reports Page
	 * - Tools Page
	 */
	/* @todo: remove this comment, convert other comments to multiline (reduction safe), and namespace all variables (reduction safe) */
		// Reports graph tabs
		jQuery(document).on('click', '.monsterinsights-tabbed-nav > .monsterinsights-tabbed-nav-tab-title', function( e ){
			e.preventDefault();
			var tabname = jQuery(this).attr("data-tab");
			jQuery(this).addClass("active").siblings().removeClass("active");
			jQuery('.monsterinsights-tabbed-nav-panel').hide();
			jQuery('.monsterinsights-tabbed-nav-panel.' + tabname ).show();
		});

		jQuery( function() {
			MonsterInsightsTriggerTabs( true );
		});

		jQuery( window ).on( "hashchange", function( e ) {
			e.preventDefault();
			MonsterInsightsTriggerTabs( false );
		});

		function MonsterInsightsTriggerTabs( init ) {
			var window_hash         = window.location.hash;    
			var current_tab         = '';
			var tab_nav             = '.monsterinsights-main-nav-container';
			var tabs_section        = '.monsterinsights-main-nav-tabs';

			var current_sub_tab     = '';
			var sub_tabs_nav        = '.monsterinsights-sub-nav-container';
			var sub_tabs_section    = '.monsterinsights-sub-nav-tabs';
			var current_sub_tab_div = '';

			// If there's no hash, then we're on the default, which the page will auto load first tab + subtab as active
			if ( window_hash.indexOf( '#' ) > -1 ) {
				if ( window_hash.indexOf( '?' ) < 1 ) {
					 // No ?, but there is a #
					current_tab         = window_hash;
					var firstchildclick = jQuery( sub_tabs_nav );

					// If there's no subtab defined, let's see if the page has subtabs, and if so select the first one.
					if ( "0" in firstchildclick && "firstElementChild" in firstchildclick[0] && "hash" in firstchildclick[0].firstElementChild ) {
						current_sub_tab     = firstchildclick[0].firstElementChild.hash;
						current_sub_tab_div = '#' + ( firstchildclick[0].firstElementChild.hash ).split( '?' )[1];
					}
				} else {
					// ? and a #
					var tab_split       = window_hash.split( '?' );
					current_tab         = tab_split[0];
					current_sub_tab     = window_hash;
					current_sub_tab_div = '#' + tab_split[1];
				}
				
				// @todo: if the tab doesn't exist, we should fallback to finding the first tab and opening that
				// If we fallback, we should clear the sub_tab so we ensure we land on the first subtab of the new
				// tab, if that pages has subtabs.

				jQuery( tab_nav ).find( '.monsterinsights-active' ).removeClass( 'monsterinsights-active' );
				jQuery( tabs_section ).find( '.monsterinsights-active' ).removeClass( 'monsterinsights-active' );
				jQuery( sub_tabs_nav ).find( '.monsterinsights-active' ).removeClass( 'monsterinsights-active' );
				jQuery( sub_tabs_section ).find( '.monsterinsights-active' ).removeClass( 'monsterinsights-active' );

				jQuery( tab_nav ).find( 'a[href="' + current_tab + '"]' ).addClass( 'monsterinsights-active' );
				jQuery( tabs_section ).find( current_tab ).addClass( 'monsterinsights-active' );  

				// Check to make sure the subtab given in the url exists, and then open it.
				if ( jQuery( sub_tabs_nav ).find( 'a[href="' + current_sub_tab + '"]' ).length == 1 ) {
					jQuery( sub_tabs_nav ).find( 'a[href="' + current_sub_tab + '"]' ).addClass( 'monsterinsights-active' );
					jQuery( sub_tabs_section ).find( current_sub_tab_div ).addClass( 'monsterinsights-active' ); 
				} else { 
				   // If the subtab given in the URL doesn't exist, let's see if the page has subtabs, and if so select the first one. 
					var firstchildclick = jQuery( sub_tabs_nav );
					if ( "0" in firstchildclick && "firstElementChild" in firstchildclick[0] && "hash" in firstchildclick[0].firstElementChild ) {
						jQuery( sub_tabs_nav ).find( 'a[href="#' + (firstchildclick[0].firstElementChild.hash).split( '?' )[1] + '"]' ).addClass( 'monsterinsights-active' );
						jQuery( sub_tabs_section ).find( '#' + (firstchildclick[0].firstElementChild.hash).split( '?' )[1] ).addClass( 'monsterinsights-active' );
					}
				}

				if ( jQuery('.monsterinsights-main-nav-tabs .monsterinsights-main-nav-tab:not(".monsterinsights-active") .monsterinsights-tab-settings-notices .monsterinsights-notice' ).length > 0 ) {
					jQuery('.monsterinsights-main-nav-tabs .monsterinsights-main-nav-tab:not(".monsterinsights-active") .monsterinsights-tab-settings-notices .monsterinsights-notice' ).remove();
				}

				if ( jQuery('.monsterinsights-sub-nav-tabs .monsterinsights-sub-nav-tab:not("' + current_sub_tab_div + '") .monsterinsights-subtab-settings-notices .monsterinsights-notice' ).length > 0 ) {
					 jQuery('.monsterinsights-sub-nav-tabs .monsterinsights-sub-nav-tab:not("' + current_sub_tab_div + '") .monsterinsights-subtab-settings-notices .monsterinsights-notice' ).remove();
				}

				if ( current_tab !== '#monsterinsights-main-tab-tracking' ) {
					if ( jQuery('.monsterinsights-sub-nav-tabs .monsterinsights-sub-nav-tab .monsterinsights-subtab-settings-notices .monsterinsights-notice' ).length > 0 ) {
						 jQuery('.monsterinsights-sub-nav-tabs .monsterinsights-sub-nav-tab  .monsterinsights-subtab-settings-notices .monsterinsights-notice' ).remove();
					} 
				}
				 // Is the window taller than the #adminmenuwrap?
				  if (jQuery(window).height() > jQuery("#adminmenuwrap").height()) {
					 // ...if so, make the #adminmenuwrap fixed
					 jQuery('#adminmenuwrap').css('position', 'fixed'); 
					
				  } else {
					 //...otherwise, leave it relative        
					 jQuery('#adminmenuwrap').css('position', 'relative'); 

				  }
			} else if ( init ) {
				// If we have a default open, else open one
				if ( jQuery(tab_nav + " .monsterinsights-active").length > 0 ){  
					return;
				}
				jQuery(tab_nav).find('a:first').addClass( 'monsterinsights-active' );
				jQuery( tabs_section ).find('div:first').addClass( 'monsterinsights-active' );
				jQuery(sub_tabs_nav).find('a:first').addClass( 'monsterinsights-active' );
				jQuery( sub_tabs_section ).find('div:first').addClass( 'monsterinsights-active' );
			}
		}
});

function monsterinsights_equalheight2column(){
	jQuery('.monsterinsights-reports-2-column-container').each(function(i, elem) {
		jQuery(elem)
			.find('.monsterinsights-reports-data-table-tbody')   // Only children of this row
			.matchHeight({byRow: false}); // Row detection gets confused so disable it
		jQuery(elem)
			.find('.monsterinsights-reports-2-column-panel')   // Only children of this row
			.matchHeight({byRow: true}); // Row detection gets confused so disable it
	});
}

function monsterinsights_show_manual( ){
	document.getElementById("monsterinsights-google-ua-box").className = "";
}

var uorigindetected = 'no';

// Reports:
// Thanks ChartJS for making us have to do this nonsense.

// A huge Thanks ChartJS for making us have to do this nonsense. Why ChartJS can't just fix non-initalization on hidden elements (or at least
// give a generic action to re-fire initialization for in-view charts generically is beyond me)
jQuery(document).ready(function($) {
	var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

	jQuery.fn.attrchange = function(callback) {
		if (MutationObserver) {
			var options = {
				subtree: false,
				attributes: true,
				attributeName: "class",
			};

			var observer = new MutationObserver(function(mutations) {
				mutations.forEach(function(e) {
					callback.call(e.target, e.attributeName);
				});
			});

			return this.each(function() {
				observer.observe(this, options);
			});
		}
	};
	
	jQuery('#monsterinsights-reports-page-main-nav .monsterinsights-main-nav-item.monsterinsights-nav-item').attrchange(function(attrName) {
		if ( attrName != 'class' ){
			return;
		}
	
		// Blur report shown
		jQuery( "#monsterinsights-reports-pages" ).addClass( "monsterinsights-mega-blur" );

		// Which report?
		var reportname = jQuery("#monsterinsights-reports-pages").find( "div.monsterinsights-main-nav-tab.monsterinsights-active" ).attr("id").replace("monsterinsights-main-tab-", "" );
		var reportid   = jQuery("#monsterinsights-reports-pages").find( "div.monsterinsights-main-nav-tab.monsterinsights-active" ).attr("id");
		var start      = moment( moment().subtract(30, 'days') ).tz(monsterinsights_admin.timezone).format('YYYY-MM-DD');
		var end        = moment( moment().subtract( 1, 'days' ) ).tz(monsterinsights_admin.timezone).format('YYYY-MM-DD');

		swal({
		  type: 'info',
		  title: monsterinsights_admin.refresh_report_title,
		  text: monsterinsights_admin.refresh_report_text,
		  allowOutsideClick: false,
		  allowEscapeKey: false,
		  allowEnterKey: false,
		  onOpen: function () {
			swal.showLoading();

			var data = { 
				'action'   : 'monsterinsights_refresh_reports', 
				'security' :  monsterinsights_admin.admin_nonce,
				'isnetwork':  monsterinsights_admin.isnetwork,
				'start'    :  start,
				'end'      :  end,
				'report'   :  reportname,
			};
			
			jQuery.post(ajaxurl, data, function( response ) {

				if ( response.success && response.data.html ) {
					// Insert new data here
					jQuery("#monsterinsights-main-tab-" + reportname + " > .monsterinsights-reports-wrap").html( response.data.html );

					// Resize divs
					monsterinsights_equalheight2column();
					swal.close();
				} else {
					var swal_settings = {
						type: 'error',
						title: monsterinsights_admin.refresh_report_failure_title,
						html: response.data.message,
					};
					if ( response.data.data.footer ) {
						swal_settings.footer = response.data.data.footer;
					}
					swal(swal_settings).catch(swal.noop);
				}
			}).then(function (result) {
				// Unblur reports
				jQuery( "#monsterinsights-reports-pages" ).removeClass( "monsterinsights-mega-blur" );
			}).fail( function(xhr, textStatus, errorThrown) {
				var message = jQuery(xhr.responseText).text();
				message = message.substring(0, message.indexOf("Call Stack"));
				swal({
					type: 'error',
					  title: monsterinsights_admin.refresh_report_failure_title,
					  text: message,
				  }).catch(swal.noop);
				// Unblur reports
				jQuery( "#monsterinsights-reports-pages" ).removeClass( "monsterinsights-mega-blur" );
			});
		  }
		});
	});
});