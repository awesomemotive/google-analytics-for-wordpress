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
	}

	jQuery(document).ready( function(){
		for ( err in errors )
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
	$("#screen-meta-links").prependTo("#monsterinsights-header-temp");
	$("#screen-meta").prependTo("#monsterinsights-header-temp");

	// Tooltips
	$('.monsterinsights-help-tip').tooltip({
		content: function() {
			return $(this).prop('title');
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

	/**
	* Copy to Clipboard
	*/
	if ( typeof Clipboard !== 'undefined' ) {
		var monsterinsights_clipboard = new Clipboard( '.monsterinsights-copy-to-clipboard' );
		$( document ).on( 'click', '.monsterinsights-copy-to-clipboard', function( e ) {
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

	/**
	* Dismissable Notices
	* - Sends an AJAX request to mark the notice as dismissed
	*/
	$( 'div.monsterinsights-notice' ).on( 'click', '.notice-dismiss', function( e ) {
		e.preventDefault();
		$( this ).closest( 'div.monsterinsights-notice' ).fadeOut();

		// If this is a dismissible notice, it means we need to send an AJAX request
		if ( $( this ).hasClass( 'is-dismissible' ) ) {
			$.post(
				monsterinsights_admin.ajax,
				{
					action: 'monsterinsights_ajax_dismiss_notice',
					nonce:  monsterinsights_admin.dismiss_notice_nonce,
					notice: $( this ).parent().data( 'notice' )
				},
				function( response ) {
				},
				'json'
			);
		}

	} );

	function modelMatcher(params, data) {
			data.parentText = data.parentText || "";

			// Always return the object if there is nothing to compare
			if ($.trim(params.term) === '') {
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
		$('.monsterinsights-select300').select300();

		var fields_changed = false;
		$(document).on('change', '#monsterinsights-settings :input', function(){
			fields_changed = true;
		});
		
		$(document).on('click', 'a:not(.monsterinsights-settings-click-excluded)', function( e ){ 

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
		
		$('#monsterinsights-google-authenticate-submit').on( "click", function( e ) {
			e.preventDefault();
			$('<div id="monsterinsights_google_auth_view" class="monsterinsights-hideme"></div>').prependTo('body');
			$('<div id="monsterinsights_google_auth_block_view" class="monsterinsights-hideme"></div>').prependTo('body');
			var data = {
				'action': 'monsterinsights_google_view',
				'view': 'prestart',
				'reauth': false
			};
			jQuery.post(ajaxurl, data, function(response) {
				$('#monsterinsights_google_auth_view').html(response);
				$('#monsterinsights_google_auth_view').removeClass('monsterinsights-hideme');
				$('#monsterinsights_google_auth_block_view').removeClass('monsterinsights-hideme');
				$('#wpadminbar').addClass('monsterinsights-hideme');
				document.body.style.overflowY = "hidden";
				document.body.style.overflowX = "hidden";
				window.scrollTo( 0, 0 );
				$('#adminmenumain').addClass('monsterinsights_opacity_60');
			}).fail( function(xhr, textStatus, errorThrown) {
				var message = $(xhr.responseText).text();
				message = message.substring(0, message.indexOf("Call Stack"));
				console.log( message );
			});
		});
		$('#monsterinsights-google-reauthenticate-submit').on( "click", function( e ) {
			e.preventDefault();
			$('<div id="monsterinsights_google_auth_view" class="monsterinsights_google_auth_reauth monsterinsights-hideme"></div>').prependTo('body');
			$('<div id="monsterinsights_google_auth_block_view" class="monsterinsights-hideme"></div>').prependTo('body');
			var data = {
				'action': 'monsterinsights_google_view',
				'view': 'prestart',
				'reauth': true
			};

			jQuery.post(ajaxurl, data, function(response) {
				$('#monsterinsights_google_auth_view').html(response);
				$('#monsterinsights_google_auth_view').removeClass('monsterinsights-hideme');
				$('#monsterinsights_google_auth_block_view').removeClass('monsterinsights-hideme');
				$('#wpadminbar').addClass('monsterinsights-hideme');
				document.body.style.overflowY = "hidden";
				document.body.style.overflowX = "hidden";
				window.scrollTo( 0, 0 );
				$('#adminmenumain').addClass('monsterinsights_opacity_60');
			}).fail( function(xhr, textStatus, errorThrown) {
				var message = $(xhr.responseText).text();
				message = message.substring(0, message.indexOf("Call Stack"));
				console.log( message );
			});
		});
		$( document ).on( "click", '#monsterinsights_google_auth_box_next', function( e ) {
			e.preventDefault();
			var stepdata = '';
			if ( document.getElementById('monsterinsights_step_data') != null ) {
				stepdata = document.getElementById('monsterinsights_step_data').value;
			}

			var data = {
				'action': 'monsterinsights_google_view',
				'view': document.getElementById('monsterinsightsview').value,
				'reauth': document.getElementById('monsterinsightsreauth').value,
				'stepdata': stepdata,
			};
			
			$('#monsterinsights_google_auth_box_footer').html( '<div class="monsterinsights-google-loading">' + monsterinsights_admin.loadingtext + '</div>' );
			$('.monsterinsights_google_auth_box_cancel').hide();

			jQuery.post(ajaxurl, data, function(response) {

				$('#monsterinsights_google_auth_view').html(response);
				$('#monsterinsights_google_auth_view').removeClass('monsterinsights-hideme');
				$('#monsterinsights_google_auth_block_view').removeClass('monsterinsights-hideme');

				var view = document.getElementById('monsterinsightsview').value;
				if ( view == 'selectprofile' ) {
					$('.monsterinsights_select_ga_profile').select300({matcher: modelMatcher});
					monsterinsights_closepopupwindow();
				}
			}).fail( function(xhr, textStatus, errorThrown) {
				var message = $(xhr.responseText).text();
				message = message.substring(0, message.indexOf("Call Stack"));
				console.log( message );
			});
		});
		$( document ).on( "click", '.monsterinsights_google_auth_box_done', function( e ) {
			e.preventDefault();
			location.reload();
		});
		$( document ).on( "click", '.monsterinsights_google_auth_box_cancel_error', function( e ) {
			e.preventDefault();
			location.reload();
		});
		$( document ).on( "click", '.monsterinsights_google_auth_box_cancel', function( e ) {
			e.preventDefault();
			var stepdata = '';
			if ( document.getElementById('monsterinsights_step_data') != null ) {
				stepdata = document.getElementById('monsterinsights_step_data').value;
			}
			var data = {
				'action': 'monsterinsights_google_cancel',
				'view': document.getElementById('monsterinsightsview').value,
				'reauth': document.getElementById('monsterinsightsreauth').value,
				'stepdata': stepdata,
			};
			jQuery.post(ajaxurl, data, function(response) {
				document.body.style.overflowY = "visible";
				document.body.style.overflowX = "visible";
				$('#monsterinsights_google_auth_view').html('');
				$('#monsterinsights_google_auth_view').addClass('monsterinsights-hideme');
				$('#monsterinsights_google_auth_block_view').addClass('monsterinsights-hideme');
				$('#adminmenumain').removeClass('monsterinsights_opacity_60');
				$('#wpadminbar').removeClass('monsterinsights-hideme');
			}).fail( function(xhr, textStatus, errorThrown) {
				var message = $(xhr.responseText).text();
				message = message.substring(0, message.indexOf("Call Stack"));
				console.log( message );
			});
		}); 

		// Tools JS
		$('#monsterinsights-url-builder input').keyup(monsterinsights_update_campaign_url);
		$('#monsterinsights-url-builder input').click(monsterinsights_update_campaign_url);

		function monsterinsights_update_campaign_url() {
				var domain   = $('#monsterinsights-url-builer-domain').val().trim();
				var source   = $('#monsterinsights-url-builer-source').val().trim();
				var medium   = $('#monsterinsights-url-builer-medium').val().trim();
				var term     = $('#monsterinsights-url-builer-term').val().trim();
				var content  = $('#monsterinsights-url-builer-content').val().trim();
				var name     = $('#monsterinsights-url-builer-name').val().trim();
				var fragment = $('#monsterinsights-url-builer-fragment').is(':checked');
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
					var html = html + '&utm_medium=' + encodeURIComponent(medium);
				}                
				if (name) {
					var html = html + '&utm_campaign=' + encodeURIComponent(name);
				}
				if (term) {
					var html = html + '&utm_term=' + encodeURIComponent(term);
				}
				if (content) {
					var html = html + '&utm_content=' + encodeURIComponent(content);
				}


				if ( domain && source ) {
					$('#monsterinsights-url-builer-url').html(html.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;"));
				} else {
					$('#monsterinsights-url-builer-url').html('');
				}         
		}

		$( document ).on( 'click', '#monsterinsights-shorten-url', function( e ) {
			e.preventDefault();
			$("#monsterinsights-shorten-url").text( monsterinsights_admin.working );
			var url = decodeURIComponent( $('#monsterinsights-url-builer-url').val() );
			var data = {
				'action': 'monsterinsights_get_shortlink',
				'url'   : url,
				'nonce':  monsterinsights_admin.admin_nonce,

			};
			jQuery.post(ajaxurl, data, function(response) {
				$('#monsterinsights-url-builer-url').html(response.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;"));
				$("#monsterinsights-shorten-url").text( monsterinsights_admin.shortened );
				window.setTimeout(function() {
					$("#monsterinsights-shorten-url").text( monsterinsights_admin.shorten );
				}, 2000);
			}).fail( function(xhr, textStatus, errorThrown) {
				$("#monsterinsights-shorten-url").text( monsterinsights_admin.failed );
				window.setTimeout(function() {
					$("#monsterinsights-shorten-url").text( monsterinsights_admin.shorten );
				}, 2000);
			});
		} );

		// Addons JS
			// Addon background color
				if ( $( "#monsterinsights-addons" ).length !== 0 ) {
					$( "#wpbody").css("background-color", "#f1f1f1");
					$( "body").css("background-color", "#f1f1f1");
					$( "#wpfooter").css("background-color", "#f1f1f1");
					$( "#wpbody-content").css("padding-bottom", "0px");
				}

			// Addons Search
			var addon_search_timeout;
			$( 'form#add-on-search input#add-on-searchbox' ).on( 'keyup', function() {

				// Clear timeout
				clearTimeout( addon_search_timeout );

				// Get the search input, heading, results and cancel elements
				var search          = $( this ),
					search_terms    = $( search ).val().toLowerCase(),
					search_heading  = $( search ).data( 'heading' ),
					search_results  = $( search ).data( 'results' ),
					search_cancel   = $( search ).data( 'cancel' );

				// Show the Spinner
				$( 'form#add-on-search .spinner' ).css( 'visibility', 'visible' );

				// If the search terms is less than 3 characters, show all Addons
				if ( search_terms.length < 3 ) {
					$( 'div.monsterinsights-addon' ).fadeIn( 'fast', function() {
						// Hide the Spinner
						$( 'form#add-on-search .spinner' ).css( 'visibility', 'hidden' );
					} );
					return;
				}

				// Iterate through the Addons, showing or hiding them depending on whether they 
				// match the given search terms.
				$( 'div.monsterinsights-addon' ).each( function() {
					if ( $( 'h3.monsterinsights-addon-title', $( this ) ).text().toLowerCase().search( search_terms ) >= 0 ) {
						// This Addon's title does match the search terms
						// Show
						$( this ).fadeIn();
					} else {
						// This Addon's title does not match the search terms
						// Hide
						$( this ).fadeOut();
					}
				} );

				// Hide the Spinner
				$( 'form#add-on-search .spinner' ).css( 'visibility', 'hidden' );

			} );

		// Addons Sorting
			var monsterinsights_addons_licensed_sorting = new List( 'monsterinsights-addons-licensed', {
				valueNames: [ 'monsterinsights-addon-title' ]
			} );
			var monsterinsights_addons_unlicensed_sorting = new List( 'monsterinsights-addons-unlicensed', {
				valueNames: [ 'monsterinsights-addon-title' ]
			} );
			$( 'select#monsterinsights-filter-select' ).on( 'change', function() {
				if ( typeof monsterinsights_addons_licensed_sorting.sort !== 'undefined' ) {
					monsterinsights_addons_licensed_sorting.sort( 'monsterinsights-addon-title', {
						order: $( this ).val(),
					} );
				}
				if ( typeof monsterinsights_addons_unlicensed_sorting.sort !== 'undefined' ) {
					monsterinsights_addons_unlicensed_sorting.sort( 'monsterinsights-addon-title', {
						order: $( this ).val(),
					} );
				}
			} );

		// Re-enable install button if user clicks on it, needs creds but tries to install another addon instead.
			$('#monsterinsights-addons').on('click.refreshInstallAddon', '.monsterinsights-addon-action-button', function(e) {
				var el      = $(this);
				var buttons = $('#monsterinsights-addons').find('.monsterinsights-addon-action-button');
				$.each(buttons, function(i, element) {
					if ( el == element ) {
						return true;
					}

					monsterinsightsAddonRefresh(element);
				});
			});

		// Activate Addon
			$('#monsterinsights-addons').on('click.activateAddon', '.monsterinsights-activate-addon', function(e) {
				e.preventDefault();
				var $this = $(this);

				// Remove any leftover error messages, output an icon and get the plugin basename that needs to be activated.
				$('.monsterinsights-addon-error').remove();
				$(this).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.activating);
				$(this).next().css({'display' : 'inline-block', 'margin-top' : '0px'});
				var button  = $(this);
				var plugin  = $(this).attr('rel');
				var el      = $(this).parent().parent();
				var message = $(this).parent().parent().find('.addon-status');

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
							$(el).slideDown('normal', function() {
								$(this).after('<div class="monsterinsights-addon-error"><strong>' + response.error + '</strong></div>');
								$this.next().hide();
								$('.monsterinsights-addon-error').delay(3000).slideUp();
							});
							return;
						}

						// The Ajax request was successful, so let's update the output.
						if ( monsterinsights_admin.isnetwork ) {
							$(button).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.networkdeactivate).removeClass('monsterinsights-activate-addon').addClass('monsterinsights-deactivate-addon');
						} else {
							$(button).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.deactivate).removeClass('monsterinsights-activate-addon').addClass('monsterinsights-deactivate-addon');
						}

						$(message).text(monsterinsights_admin.active);
						// Trick here to wrap a span around he last word of the status
						var heading = $(message), word_array, last_word, first_part;

						word_array = heading.html().split(/\s+/); // split on spaces
						last_word = word_array.pop();             // pop the last word
						first_part = word_array.join(' ');        // rejoin the first words together

						heading.html([first_part, ' <span>', last_word, '</span>'].join(''));
						// Proceed with CSS changes
						$(el).removeClass('monsterinsights-addon-inactive').addClass('monsterinsights-addon-active');
						$this.next().hide();
					},
					error: function(xhr, textStatus ,e) {
						$this.next().hide();
						return;
					}
				}
				$.ajax(opts);
			});

		// Deactivate Addon
			$('#monsterinsights-addons').on('click.deactivateAddon', '.monsterinsights-deactivate-addon', function(e) {
				e.preventDefault();
				var $this = $(this);

				// Remove any leftover error messages, output an icon and get the plugin basename that needs to be activated.
				$('.monsterinsights-addon-error').remove();
				$(this).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.deactivating);
				$(this).next().css({'display' : 'inline-block', 'margin-top' : '0px'});
				var button  = $(this);
				var plugin  = $(this).attr('rel');
				var el      = $(this).parent().parent();
				var message = $(this).parent().parent().find('.addon-status');

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
							$(el).slideDown('normal', function() {
								$(this).after('<div class="monsterinsights-addon-error"><strong>' + response.error + '</strong></div>');
								$this.next().hide();
								$('.monsterinsights-addon-error').delay(3000).slideUp();
							});
							return;
						}

						// The Ajax request was successful, so let's update the output.
						if ( monsterinsights_admin.isnetwork ) {
							$(button).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.networkactivate).removeClass('monsterinsights-deactivate-addon').addClass('monsterinsights-activate-addon');
						} else {
							$(button).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.activate).removeClass('monsterinsights-deactivate-addon').addClass('monsterinsights-activate-addon');
						}

						$(message).text(monsterinsights_admin.inactive);

						// Trick here to wrap a span around he last word of the status
						var heading = $(message), word_array, last_word, first_part;

						word_array = heading.html().split(/\s+/); // split on spaces
						last_word = word_array.pop();             // pop the last word
						first_part = word_array.join(' ');        // rejoin the first words together

						heading.html([first_part, ' <span>', last_word, '</span>'].join(''));
						// Proceed with CSS changes
						$(el).removeClass('monsterinsights-addon-active').addClass('monsterinsights-addon-inactive');
						$this.next().hide();
					},
					error: function(xhr, textStatus ,e) {
						$this.next().hide();
						return;
					}
				}
				$.ajax(opts);
			});

		// Install Addon
			$('#monsterinsights-addons').on('click.installAddon', '.monsterinsights-install-addon', function(e) {
				e.preventDefault();
				var $this = $(this);

				// Remove any leftover error messages, output an icon and get the plugin basename that needs to be activated.
				$('.monsterinsights-addon-error').remove();
				$(this).html('<i class="monsterinsights-cloud-download"></i> ' + monsterinsights_admin.installing);
				$(this).next().css({'display' : 'inline-block', 'margin-top' : '0px'});
				var button  = $(this);
				var plugin  = $(this).attr('rel');
				var el      = $(this).parent().parent();
				var message = $(this).parent().parent().find('.addon-status');

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
							$(el).slideDown('normal', function() {
								$(button).parent().parent().after('<div class="monsterinsights-addon-error"><div class="xinterior"><p><strong>' + response.error + '</strong></p></div></div>');
								$(button).html('<i class="monsterinsights-cloud-download"></i> ' + monsterinsights_admin.install);
								$this.next().hide();
								$('.monsterinsights-addon-error').delay(4000).slideUp();
							});
							return;
						}

						// If we need more credentials, output the form sent back to us.
						if ( response.form ) {
							// Display the form to gather the users credentials.
							$(el).slideDown('normal', function() {
								$(this).after('<div class="monsterinsights-addon-error">' + response.form + '</div>');
								$this.next().hide();
							});

							// Add a disabled attribute the install button if the creds are needed.
							$(button).attr('disabled', true);

							$('#monsterinsights-addons').on('click.installCredsAddon', '#upgrade', function(e) {
								// Prevent the default action, let the user know we are attempting to install again and go with it.
								e.preventDefault();
								$this.next().hide();
								$(this).html('<i class="monsterinsights-cloud-download"></i> ' + monsterinsights_admin.installing);
								$(this).next().css({'display' : 'inline-block', 'margin-top' : '0px'});

								// Now let's make another Ajax request once the user has submitted their credentials.
								var hostname  = $(this).parent().parent().find('#hostname').val();
								var username  = $(this).parent().parent().find('#username').val();
								var password  = $(this).parent().parent().find('#password').val();
								var proceed   = $(this);
								var connect   = $(this).parent().parent().parent().parent();
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
											$(el).slideDown('normal', function() {
												$(button).parent().parent().after('<div class="monsterinsights-addon-error"><strong>' + response.error + '</strong></div>');
												$(button).html('<i class="monsterinsights-cloud-download"></i> ' + monsterinsights_admin.install);
												$this.next().hide();
												$('.monsterinsights-addon-error').delay(4000).slideUp();
											});
											return;
										}

										if ( response.form ) {
											$this.next().hide();
											$('.monsterinsights-inline-error').remove();
											$(proceed).val(monsterinsights_admin.proceed);
											$(proceed).after('<span class="monsterinsights-inline-error">' + monsterinsights_admin.connect_error + '</span>');
											return;
										}

										// The Ajax request was successful, so let's update the output.
										$(connect).remove();
										$(button).show();

										if ( monsterinsights_admin.isnetwork ) {
											$(button).text(monsterinsights_admin.networkactivate).removeClass('monsterinsights-install-addon').addClass('monsterinsights-activate-addon');
										} else {
											$(button).text(monsterinsights_admin.activate).removeClass('monsterinsights-install-addon').addClass('monsterinsights-activate-addon');
										}

										$(button).attr('rel', response.plugin);
										$(button).removeAttr('disabled');
										$(message).text(monsterinsights_admin.inactive);
										
										// Trick here to wrap a span around he last word of the status
										var heading = $(message), word_array, last_word, first_part;

										word_array = heading.html().split(/\s+/); // split on spaces
										last_word = word_array.pop();             // pop the last word
										first_part = word_array.join(' ');        // rejoin the first words together

										heading.html([first_part, ' <span>', last_word, '</span>'].join(''));
										// Proceed with CSS changes
										$(el).removeClass('monsterinsights-addon-not-installed').addClass('monsterinsights-addon-inactive');
										$this.next().hide();
									},
									error: function(xhr, textStatus ,e) {
										$this.next().hide();
										return;
									}
								}
								$.ajax(cred_opts);
							});

							// No need to move further if we need to enter our creds.
							return;
						}

						// The Ajax request was successful, so let's update the output.
						if ( monsterinsights_admin.isnetwork ) {
							$(button).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.networkactivate).removeClass('monsterinsights-install-addon').addClass('monsterinsights-activate-addon');
						} else {
							$(button).html('<i class="monsterinsights-toggle-on"></i> ' + monsterinsights_admin.activate).removeClass('monsterinsights-install-addon').addClass('monsterinsights-activate-addon');
						}
						$(button).attr('rel', response.plugin);
						$(message).text(monsterinsights_admin.inactive);

						// Trick here to wrap a span around he last word of the status
						var heading = $(message), word_array, last_word, first_part;

						word_array = heading.html().split(/\s+/); // split on spaces
						last_word = word_array.pop();             // pop the last word
						first_part = word_array.join(' ');        // rejoin the first words together

						heading.html([first_part, ' <span>', last_word, '</span>'].join(''));
						// Proceed with CSS changes
						$(el).removeClass('monsterinsights-addon-not-installed').addClass('monsterinsights-addon-inactive');
						$this.next().hide();
					},
					error: function(xhr, textStatus ,e) {
						$this.next().hide();
						return;
					}
				}
				$.ajax(opts);
			});

		// Function to clear any disabled buttons and extra text if the user needs to add creds but instead tries to install a different addon.
			function monsterinsightsAddonRefresh(element) {
				if ( $(element).attr('disabled') ) {
					$(element).removeAttr('disabled');
				}

				if ( $(element).parent().parent().hasClass('monsterinsights-addon-not-installed') ) {
					$(element).text( monsterinsights_admin.install );
				}
			}
	/**
	 * Handles tabbed interfaces within MonsterInsights:
	 * - Settings Page
	 * - Reports Page
	 * - Tools Page
	 */
	/* @todo: remove this comment, convert other comments to multiline (reduction safe), and namespace all variables (reduction safe) */
		$( function() {
			MonsterInsightsTriggerTabs( true );
		});

		$( window ).on( "hashchange", function( e ) {
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
					var firstchildclick = $( sub_tabs_nav );

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

				$( tab_nav ).find( '.monsterinsights-active' ).removeClass( 'monsterinsights-active' );
				$( tabs_section ).find( '.monsterinsights-active' ).removeClass( 'monsterinsights-active' );
				$( sub_tabs_nav ).find( '.monsterinsights-active' ).removeClass( 'monsterinsights-active' );
				$( sub_tabs_section ).find( '.monsterinsights-active' ).removeClass( 'monsterinsights-active' );

				$( tab_nav ).find( 'a[href="' + current_tab + '"]' ).addClass( 'monsterinsights-active' );
				$( tabs_section ).find( current_tab ).addClass( 'monsterinsights-active' );  

				// Check to make sure the subtab given in the url exists, and then open it.
				if ( $( sub_tabs_nav ).find( 'a[href="' + current_sub_tab + '"]' ).length == 1 ) {
					$( sub_tabs_nav ).find( 'a[href="' + current_sub_tab + '"]' ).addClass( 'monsterinsights-active' );
					$( sub_tabs_section ).find( current_sub_tab_div ).addClass( 'monsterinsights-active' ); 
				} else { 
				   // If the subtab given in the URL doesn't exist, let's see if the page has subtabs, and if so select the first one. 
					var firstchildclick = $( sub_tabs_nav );
					if ( "0" in firstchildclick && "firstElementChild" in firstchildclick[0] && "hash" in firstchildclick[0].firstElementChild ) {
						$( sub_tabs_nav ).find( 'a[href="#' + (firstchildclick[0].firstElementChild.hash).split( '?' )[1] + '"]' ).addClass( 'monsterinsights-active' );
						$( sub_tabs_section ).find( '#' + (firstchildclick[0].firstElementChild.hash).split( '?' )[1] ).addClass( 'monsterinsights-active' );
					}
				}

				if ( $('.monsterinsights-main-nav-tabs .monsterinsights-main-nav-tab:not(".monsterinsights-active") .monsterinsights-tab-settings-notices .monsterinsights-notice' ).length > 0 ) {
					$('.monsterinsights-main-nav-tabs .monsterinsights-main-nav-tab:not(".monsterinsights-active") .monsterinsights-tab-settings-notices .monsterinsights-notice' ).remove();
				}

				if ( $('.monsterinsights-sub-nav-tabs .monsterinsights-sub-nav-tab:not("' + current_sub_tab_div + '") .monsterinsights-subtab-settings-notices .monsterinsights-notice' ).length > 0 ) {
					 $('.monsterinsights-sub-nav-tabs .monsterinsights-sub-nav-tab:not("' + current_sub_tab_div + '") .monsterinsights-subtab-settings-notices .monsterinsights-notice' ).remove();
				}

				if ( current_tab !== '#monsterinsights-main-tab-tracking' ) {
					if ( $('.monsterinsights-sub-nav-tabs .monsterinsights-sub-nav-tab .monsterinsights-subtab-settings-notices .monsterinsights-notice' ).length > 0 ) {
						 $('.monsterinsights-sub-nav-tabs .monsterinsights-sub-nav-tab  .monsterinsights-subtab-settings-notices .monsterinsights-notice' ).remove();
					} 
				}
				 // Is the window taller than the #adminmenuwrap?
				  if ($(window).height() > $("#adminmenuwrap").height()) {
					 // ...if so, make the #adminmenuwrap fixed
					 $('#adminmenuwrap').css('position', 'fixed'); 
					
				  } else {
					 //...otherwise, leave it relative        
					 $('#adminmenuwrap').css('position', 'relative'); 

				  }
			}   
		}
});

function monsterinsights_popupwindow(url, w, h) {
	'use strict';
	monsterinsights_closepopupwindow();
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/8);
	monsterinsights_authwindow = window.open(url, 'monsterinsights_ga_auth_window', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}

function monsterinsights_closepopupwindow() {
	if( monsterinsights_authwindow ){
		monsterinsights_authwindow.close();
	}
}

function monsterinsights_show_manual( ){
	document.getElementById("monsterinsights-google-ua-box").className = "";
}

var uorigindetected = 'no';
var monsterinsights_authwindow;