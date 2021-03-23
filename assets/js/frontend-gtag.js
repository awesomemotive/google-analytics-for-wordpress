/**
 * Developer's Notice:
 *
 * Note: JS in this file (and this file itself) is not guaranteed backwards compatibility. JS can be added, changed or removed at any time without notice.
 * For more information see the `Backwards Compatibility Guidelines for Developers` section of the README.md file.
 */
/**
 * Handles:
 * - JS Events handling
 *
 * @since 7.15.0
 */
var MonsterInsights = function () {
	/* MonsterInsights JS  events tracking works on all major browsers, including IE starting at IE 7, via polyfills for any major JS function used that
	   is not supported by at least  95% of the global and/or US browser marketshare. Currently, IE 7 & 8 which as of 2/14/17 have under 0.25% global marketshare, require
	   us to polyfill Array.prototype.lastIndexOf, and if they continue to drop, we might remove this polyfill at some point. In that case note that events tracking
	   for IE 7/8 will continue to work, with the exception of events tracking of downloads. */
	var lastClicked = [];
	var internalAsOutboundCategory = '';
	var beforeUnloadChanged = false;

	this.setLastClicked = function ( valuesArray, fieldsArray, tracked ) {
		valuesArray = typeof valuesArray !== 'undefined' ? valuesArray : [];
		fieldsArray = typeof fieldsArray !== 'undefined' ? fieldsArray : [];
		tracked = typeof tracked !== 'undefined' ? tracked : false;

		lastClicked.valuesArray = valuesArray;
		lastClicked.fieldsArray = fieldsArray;
	};

	this.getLastClicked = function () {
		return lastClicked;
	};

	this.setInternalAsOutboundCategory = function ( category ) {
		internalAsOutboundCategory = category;
	};

	this.getInternalAsOutboundCategory = function () {
		return internalAsOutboundCategory;
	};

	this.sendEvent = function ( type, action, fieldsArray ) {
		__gtagTrackerSend( type, action, fieldsArray, [] );
	};

	function __gtagTrackerIsDebug() {
		if ( window.monsterinsights_debug_mode ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This attempts to be compatible with the gtag function.
	 *
	 * @see https://developers.google.com/analytics/devguides/collection/gtagjs
	 * @param type string Type of request, event, timing, config.
	 * @param action string Event action or UA for config.
	 * @param fieldsArray object The configuration object.
	 * @param valuesArray object The values for the log.
	 * @private
	 */
	function __gtagTrackerSend( type, action, fieldsArray, valuesArray ) {
		type = typeof type !== 'undefined' ? type : 'event';
		action = typeof action !== 'undefined' ? action : '';
		valuesArray = typeof valuesArray !== 'undefined' ? valuesArray : [];
		fieldsArray = typeof fieldsArray !== 'undefined' ? fieldsArray : {};

		__gtagTracker( type, action, fieldsArray );

		lastClicked.valuesArray = valuesArray;
		lastClicked.fieldsArray = fieldsArray;
		lastClicked.fieldsArray.event_action = action;
		lastClicked.tracked = true;
		__gtagTrackerLog( 'Tracked: ' + valuesArray.type );
		__gtagTrackerLog( lastClicked );
	}

	function __gtagTrackerNotSend( valuesArray ) {
		valuesArray = typeof valuesArray !== 'undefined' ? valuesArray : [];

		lastClicked.valuesArray = valuesArray;
		lastClicked.fieldsArray = [];
		lastClicked.tracked = false;
		__gtagTrackerLog( 'Not Tracked: ' + valuesArray.exit );
		__gtagTrackerLog( lastClicked );
	}

	function __gtagTrackerLog( message ) {
		if ( __gtagTrackerIsDebug() ) {
			console.dir( message );
		}
	}

	function __gtagTrackerStringTrim( x ) {
		return x.replace( /^\s+|\s+$/gm, '' );
	}

	function __gtagTrackerGetDomain() {
		var i = 0, currentdomain = document.domain, p = currentdomain.split( '.' ), s = '_gd' + (
			new Date()
		).getTime();
		while ( i < ( p.length - 1 ) && document.cookie.indexOf( s + '=' + s ) == - 1 ) {
			currentdomain = p.slice( - 1 - (
				++ i
			) ).join( '.' );
			document.cookie = s + "=" + s + ";domain=" + currentdomain + ";";
		}
		document.cookie = s + "=;expires=Thu, 01 Jan 1970 00:00:01 GMT;domain=" + currentdomain + ";";
		return currentdomain;
	}

	function __gtagTrackerGetExtension( extension ) {
		extension = extension.toString();
		extension = extension.substring( 0, (
			extension.indexOf( "#" ) == - 1
		) ? extension.length : extension.indexOf( "#" ) ); /* Remove the anchor at the end, if there is one */
		extension = extension.substring( 0, (
			extension.indexOf( "?" ) == - 1
		) ? extension.length : extension.indexOf( "?" ) ); /* Remove the query after the file name, if there is one */
		extension = extension.substring( extension.lastIndexOf( "/" ) + 1, extension.length ); /* Remove everything before the last slash in the path */
		if ( extension.length > 0 && extension.indexOf( '.' ) !== - 1 ) { /* If there's a period left in the URL, then there's a extension. Else it is not a extension. */
			extension = extension.substring( extension.indexOf( "." ) + 1 ); /* Remove everything but what's after the first period */
			return extension;
		} else {
			return "";
		}
	}

	function __gtagTrackerTrackedClick( event ) {
		return event.which == 1 || event.which == 2 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey;
	}

	function __gtagTrackerGetDownloadExtensions() {
		var download_extensions = [];
		if ( typeof monsterinsights_frontend.download_extensions == 'string' ) {
			download_extensions = monsterinsights_frontend.download_extensions.split( "," );
		}
		return download_extensions;
	}

	function __gtagTrackerGetInboundPaths() {
		var inbound_paths = [];
		if ( typeof monsterinsights_frontend.inbound_paths == 'string' ) {
			inbound_paths = JSON.parse( monsterinsights_frontend.inbound_paths );
		}

		return inbound_paths;
	}

	function __gtagTrackerTrackedClickType( event ) {
		if ( event.which == 1 ) {
			return 'event.which=1';
		} else if ( event.which == 2 ) {
			return 'event.which=2';
		} else if ( event.metaKey ) {
			return 'metaKey';
		} else if ( event.ctrlKey ) {
			return 'ctrlKey';
		} else if ( event.shiftKey ) {
			return 'shiftKey';
		} else if ( event.altKey ) {
			return 'altKey';
		} else {
			return '';
		}
	}

	function __gtagTrackerLinkType( el ) {
		var download_extensions = __gtagTrackerGetDownloadExtensions();
		var inbound_paths = __gtagTrackerGetInboundPaths();
		var type = 'unknown';
		var link = el.href;
		var extension = __gtagTrackerGetExtension( el.href );
		var currentdomain = __gtagTrackerGetDomain();
		var hostname = el.hostname;
		var protocol = el.protocol;
		var pathname = el.pathname;
		link = link.toString();
		var index, len;
		var category = el.getAttribute( "data-vars-ga-category" );

		if ( category ) {
			return category;
		}

		if ( link.match( /^javascript\:/i ) ) {
			type = 'internal'; /* if it's a JS link, it's internal */
		} else if ( protocol && protocol.length > 0 && (
			__gtagTrackerStringTrim( protocol ) == 'tel' || __gtagTrackerStringTrim( protocol ) == 'tel:'
		) ) { /* If it's a telephone link */
			type = "tel";
		} else if ( protocol && protocol.length > 0 && (
			__gtagTrackerStringTrim( protocol ) == 'mailto' || __gtagTrackerStringTrim( protocol ) == 'mailto:'
		) ) { /* If it's a email */
			type = "mailto";
		} else if ( hostname && currentdomain && hostname.length > 0 && currentdomain.length > 0 && !hostname.endsWith( '.' + currentdomain ) && hostname !== currentdomain ) { /* If it's a outbound */
			type = "external";
		} else if ( pathname && JSON.stringify( inbound_paths ) != "{}" && pathname.length > 0 ) { /* If it's an internal as outbound */
			var inbound_paths_length = inbound_paths.length;
			for ( var inbound_paths_index = 0; inbound_paths_index < inbound_paths_length; inbound_paths_index ++ ) {
				if ( inbound_paths[inbound_paths_index].path && inbound_paths[inbound_paths_index].label && inbound_paths[inbound_paths_index].path.length > 0 && inbound_paths[inbound_paths_index].label.length > 0 && pathname.startsWith( inbound_paths[inbound_paths_index].path ) ) {
					type = "internal-as-outbound";
					internalAsOutboundCategory = "outbound-link-" + inbound_paths[inbound_paths_index].label;
					break;
				}
			}
			/* Enable window.monsterinsights_experimental_mode at your own risk. We might eventually remove it. Also you may/can/will burn through GA quota for your property quickly. */
		} else if ( hostname && window.monsterinsights_experimental_mode && hostname.length > 0 && document.domain.length > 0 && hostname !== document.domain ) { /* If it's a cross-hostname link */
			type = "cross-hostname";
		}

		if ( extension && (
			type === 'unknown' || 'external' === type
		) && download_extensions.length > 0 && extension.length > 0 ) { /* If it's a download */
			for ( index = 0, len = download_extensions.length; index < len; ++ index ) {
				if ( download_extensions[index].length > 0 && (
					link.endsWith( download_extensions[index] ) || download_extensions[index] == extension
				) ) {
					type = "download";
					break;
				}
			}
		}

		if ( type === 'unknown' ) {
			type = 'internal';
		}
		return type;
	}

	function __gtagTrackerLinkTarget( el, event ) {

		/* Is actual target set and not _(self|parent|top)? */
		var target = (
			el.target && !el.target.match( /^_(self|parent|top)$/i )
		) ? el.target : false;

		/* Assume a target if Ctrl|shift|meta-click */
		if ( event.ctrlKey || event.shiftKey || event.metaKey || event.which == 2 ) {
			target = "_blank";
		}
		return target;
	}

	function __gtagTrackerGetTitle( el ) {
		if ( el.getAttribute( "data-vars-ga-label" ) && el.getAttribute( "data-vars-ga-label" ).replace( /\n/ig, '' ) ) {
			return el.getAttribute( "data-vars-ga-label" ).replace( /\n/ig, '' );
		} else if ( el.title && el.title.replace( /\n/ig, '' ) ) {
			return el.title.replace( /\n/ig, '' );
		} else if ( el.innerText && el.innerText.replace( /\n/ig, '' ) ) {
			return el.innerText.replace( /\n/ig, '' );
		} else if ( el.getAttribute( 'aria-label' ) && el.getAttribute( 'aria-label' ).replace( /\n/ig, '' ) ) {
			return el.getAttribute( 'aria-label' ).replace( /\n/ig, '' );
		} else if ( el.alt && el.alt.replace( /\n/ig, '' ) ) {
			return el.alt.replace( /\n/ig, '' );
		} else if ( el.textContent && el.textContent.replace( /\n/ig, '' ) ) {
			return el.textContent.replace( /\n/ig, '' );
		} else {
			return undefined;
		}
	}

	function __gtagTrackerGetInnerTitle( el ) {
		var children = el.children;
		var count = 0;
		var child;
		var value;
		for ( var i = 0; i < children.length; i ++ ) {
			child = children[i];
			value = __gtagTrackerGetTitle( child );
			if ( value ) {
				return value;
			}
			/* max search 100 elements to ensure performance */
			if ( count == 99 ) {
				return undefined;
			}
			count ++;
		}
		return undefined;
	}

	function __gtagTrackerClickEvent( event ) {
		var el = event.srcElement || event.target;
		var valuesArray = [];
		var fieldsArray;

		/* Start Values Array */
		valuesArray.el = el;
		valuesArray.click_type = __gtagTrackerTrackedClickType( event );

		/* If GA is blocked or not loaded, or not main|middle|touch click then don't track */
		if ( 'undefined' === typeof __gtagTracker || ! __gtagTrackerTrackedClick( event ) ) {
			valuesArray.exit = 'loaded';
			__gtagTrackerNotSend( valuesArray );
			return;
		}

		/* Loop up the DOM tree through parent elements if clicked element is not a link (eg: an image inside a link) */
		while ( el && (
			typeof el.tagName == 'undefined' || el.tagName.toLowerCase() != 'a' || !el.href
		) ) {
			el = el.parentNode;
		}

		/* if a link with valid href has been clicked */
		if ( el && el.href && !el.hasAttribute( 'xlink:href' ) ) {
			var link = el.href;														/* What link are we tracking */
			var extension = __gtagTrackerGetExtension( el.href );							/* What extension is this link */
			var download_extensions = __gtagTrackerGetDownloadExtensions(); 							/* Let's get the extensions to track */
			var inbound_paths = __gtagTrackerGetInboundPaths(); 								/* Let's get the internal paths to track */
			var home_url = monsterinsights_frontend.home_url; 							/* Let's get the url to compare for external/internal use */
			var currentdomain = __gtagTrackerGetDomain();										/* What domain are we on? */
			var type = __gtagTrackerLinkType( el ); 									/* What type of link is this? */
			var target = __gtagTrackerLinkTarget( el, event );							/* Is a new tab/window being opened? */
			var action = el.getAttribute( "data-vars-ga-action" );
			var label = el.getAttribute( "data-vars-ga-label" );

			/* Element */
			valuesArray.el = el;					/* el is an a element so we can parse it */
			valuesArray.el_href = el.href; 				/* "http://example.com:3000/pathname/?search=test#hash" */
			valuesArray.el_protocol = el.protocol; 			/* "http:" */
			valuesArray.el_hostname = el.hostname; 			/* "example.com" */
			valuesArray.el_port = el.port; 				/* "3000" */
			valuesArray.el_pathname = el.pathname; 			/* "/pathname/" */
			valuesArray.el_search = el.search; 			/* "?search=test" */
			valuesArray.el_hash = el.hash;				/* "#hash" */
			valuesArray.el_host = el.host; 				/* "example.com:3000" */

			/* Settings */
			valuesArray.debug_mode = __gtagTrackerIsDebug(); /* "example.com:3000" */
			valuesArray.download_extensions = download_extensions;  /* Let's get the extensions to track */
			valuesArray.inbound_paths = inbound_paths; 		/* Let's get the internal paths to track */
			valuesArray.home_url = home_url;				/* Let's get the url to compare for external/internal use */

			/* Parsed/Logic */
			valuesArray.link = link; 				/* What link are we tracking */
			valuesArray.extension = extension; 			/* What extension is this link */
			valuesArray.type = type; 				/* What type of link is this */
			valuesArray.target = target;				/* Is a new tab/window being opened? */
			valuesArray.title = __gtagTrackerGetTitle( el ); /* Try link title, then text content */

			/* only find innerTitle if we need one */
			if ( ! valuesArray.label && !valuesArray.title ) {
				valuesArray.title = __gtagTrackerGetInnerTitle( el );
			}

			/* Let's track everything but internals (that aren't internal-as-externals) and javascript */
			if ( type !== 'internal' && type !== 'javascript' ) {

				var __gtagTrackerHitBackRun = false; /* Tracker has not yet run */

				/* HitCallback to open link in same window after tracker */
				var __gtagTrackerHitBack = function () {
					/* Run the hitback only once */
					if ( __gtagTrackerHitBackRun ) {
						return;
					}
					maybePreventBeforeUnload();
					__gtagTrackerHitBackRun = true;
					window.location.href = link;
				};

				var __gtagTrackerNoRedirectExternal = function () {
					valuesArray.exit = 'external';
					__gtagTrackerNotSend( valuesArray );
				};

				var __gtagTrackerNoRedirectInboundAsExternal = function () {
					valuesArray.exit = 'internal-as-outbound';
					__gtagTrackerNotSend( valuesArray );
				};
				var __gtagTrackerNoRedirectCrossHostname = function () {
					valuesArray.exit = 'cross-hostname';
					__gtagTrackerNotSend( valuesArray );
				};

				if ( target || type == 'mailto' || type == 'tel' ) { /* If target opens a new window then just track */
					if ( type == 'download' ) {
						fieldsArray = {
							event_category: 'download',
							event_label: label || valuesArray.title,
						};
					} else if ( type == 'tel' ) {
						fieldsArray = {
							event_category: 'tel',
							event_label: label || valuesArray.title.replace( 'tel:', '' ),
						};
					} else if ( type == 'mailto' ) {
						console.log( label || valuesArray.title.replace( 'mailto:', '' ) );
						fieldsArray = {
							event_category: 'mailto',
							event_label: label || valuesArray.title.replace( 'mailto:', '' ),
						};
					} else if ( type == 'internal-as-outbound' ) {
						fieldsArray = {
							event_category: internalAsOutboundCategory,
							event_label: label || valuesArray.title,
						};
					} else if ( type == 'external' ) {
						fieldsArray = {
							event_category: 'outbound-link',
							event_label: label || valuesArray.title,
						};
					} else if ( type == 'cross-hostname' ) {
						fieldsArray = {
							event_category: 'cross-hostname',
							event_label: label || valuesArray.title,
						};
					}

					if ( fieldsArray ) {
						__gtagTrackerSend( 'event', action || link, fieldsArray, valuesArray );
					} else {
						if ( type && type != 'internal' ) {
							fieldsArray = {
								event_category: type,
								event_label: label || valuesArray.title,
							};

							__gtagTrackerSend( 'event', action || link, fieldsArray, valuesArray );
						} else {
							valuesArray.exit = 'type';
							__gtagTrackerNotSend( valuesArray );
						}
					}
				} else {
					/* Prevent standard click, track then open */
					if ( type != 'cross-hostname' && type != 'external' && type != 'internal-as-outbound' ) {
						if ( !event.defaultPrevented ) {
							if ( event.preventDefault ) {
								event.preventDefault();
							} else {
								event.returnValue = false;
							}
						}
					}

					if ( type == 'download' ) {
						fieldsArray = {
							event_category: 'download',
							event_label: label || valuesArray.title,
							event_callback: __gtagTrackerHitBack,
						};

						__gtagTrackerSend( 'event', action || link, fieldsArray, valuesArray );
					} else if ( type == 'internal-as-outbound' ) {
						beforeUnloadChanged = true;
						window.onbeforeunload = function ( e ) {
							if ( !event.defaultPrevented ) {
								if ( event.preventDefault ) {
									event.preventDefault();
								} else {
									event.returnValue = false;
								}
							}

							fieldsArray = {
								event_category: internalAsOutboundCategory,
								event_label: label || valuesArray.title,
								event_callback: __gtagTrackerHitBack,
							};

							if ( navigator.sendBeacon ) {
								fieldsArray.transport = 'beacon';
							}

							__gtagTrackerSend( 'event', action || link, fieldsArray, valuesArray );
							setTimeout( __gtagTrackerHitBack, 1000 );
						};
					} else if ( type == 'external' ) {
						beforeUnloadChanged = true;
						window.onbeforeunload = function ( e ) {
							if ( !event.defaultPrevented ) {
								if ( event.preventDefault ) {
									event.preventDefault();
								} else {
									event.returnValue = false;
								}
							}

							fieldsArray = {
								event_category: 'outbound-link',
								event_label: label || valuesArray.title,
								event_callback: __gtagTrackerHitBack,
							};

							if ( navigator.sendBeacon ) {
								fieldsArray.transport = 'beacon';
							}

							__gtagTrackerSend( 'event', action || link, fieldsArray, valuesArray );
							setTimeout( __gtagTrackerHitBack, 1000 );
						};
					} else if ( type == 'cross-hostname' ) {
						beforeUnloadChanged = true;
						window.onbeforeunload = function ( e ) {
							if ( !event.defaultPrevented ) {
								if ( event.preventDefault ) {
									event.preventDefault();
								} else {
									event.returnValue = false;
								}
							}

							fieldsArray = {
								event_category: 'cross-hostname',
								event_label: label || valuesArray.title,
								event_callback: __gtagTrackerHitBack,
							};

							if ( navigator.sendBeacon ) {
								fieldsArray.transport = 'beacon';
							}

							__gtagTrackerSend( 'event', action || link, fieldsArray, valuesArray );
							setTimeout( __gtagTrackerHitBack, 1000 );
						};
					} else {
						if ( type && type !== 'internal' ) {
							fieldsArray = {
								event_category: type,
								event_label: label || valuesArray.title,
								event_callback: __gtagTrackerHitBack,
							};

							__gtagTrackerSend( 'event', action || link, fieldsArray, valuesArray );
						} else {
							valuesArray.exit = 'type';
							__gtagTrackerNotSend( valuesArray );
						}
					}

					if ( type != 'external' && type != 'cross-hostname' && type != 'internal-as-outbound' ) {
						/* Run event_callback again if GA takes longer than 1 second */
						setTimeout( __gtagTrackerHitBack, 1000 );
					} else {
						if ( type == 'external' ) {
							setTimeout( __gtagTrackerNoRedirectExternal, 1100 );
						} else if ( type == 'cross-hostname' ) {
							setTimeout( __gtagTrackerNoRedirectCrossHostname, 1100 );
						} else {
							setTimeout( __gtagTrackerNoRedirectInboundAsExternal, 1100 );
						}
					}

					// Clear out the beforeunload event if it was set to avoid sending false events.
					setTimeout( maybePreventBeforeUnload, 100 );
				}
			} else {
				maybePreventBeforeUnload();
				valuesArray.exit = 'internal';
				__gtagTrackerNotSend( valuesArray );
			}
		} else {
			valuesArray.exit = 'notlink';
			__gtagTrackerNotSend( valuesArray );
		}
	}

	var prevHash = window.location.hash;

	function __gtagTrackerHashChangeEvent() {
		/* Todo: Ready this section for JS unit testing */
		if ( monsterinsights_frontend.hash_tracking === "true" && prevHash != window.location.hash && monsterinsights_frontend.ua ) {
			prevHash = window.location.hash;
			__gtagTracker( 'config', monsterinsights_frontend.ua, {
				page_path: location.pathname + location.search + location.hash,
			} )
			__gtagTrackerLog( "Hash change to: " + location.pathname + location.search + location.hash );
		} else {
			__gtagTrackerLog( "Hash change to (untracked): " + location.pathname + location.search + location.hash );
		}
	}

	function maybePreventBeforeUnload() {
		if ( beforeUnloadChanged ) {
			window.onbeforeunload = null;
		}
	}

	/* Attach the event to all clicks in the document after page has loaded */
	var __gtagTrackerWindow = window;
	if ( __gtagTrackerWindow.addEventListener ) {
		__gtagTrackerWindow.addEventListener(
			"load",
			function () {
				document.body.addEventListener(
					"click",
					__gtagTrackerClickEvent,
					false
				);
			},
			false
		);
		window.addEventListener( "hashchange", __gtagTrackerHashChangeEvent, false );
	} else {
		if ( __gtagTrackerWindow.attachEvent ) {
			__gtagTrackerWindow.attachEvent(
				"onload",
				function () {
					document.body.attachEvent( "onclick", __gtagTrackerClickEvent );
				}
			);
			window.attachEvent( "onhashchange", __gtagTrackerHashChangeEvent );
		}
	}

	if ( typeof String.prototype.endsWith !== 'function' ) {
		String.prototype.endsWith = function ( suffix ) {
			return this.indexOf( suffix, this.length - suffix.length ) !== - 1;
		};
	}
	if ( typeof String.prototype.startsWith !== 'function' ) {
		String.prototype.startsWith = function ( prefix ) {
			return this.indexOf( prefix ) === 0;
		};
	}

	if ( typeof Array.prototype.lastIndexOf !== 'function' ) {
		Array.prototype.lastIndexOf = function ( searchElement /*, fromIndex*/ ) {
			'use strict';

			if ( this === void 0 || this === null ) {
				throw new TypeError();
			}

			var n, k,
				t = Object( this ),
				len = t.length >>> 0; /* jshint ignore:line */
			if ( len === 0 ) {
				return - 1;
			}

			n = len - 1;
			if ( arguments.length > 1 ) {
				n = Number( arguments[1] );
				if ( n != n ) {
					n = 0;
				} else if ( n != 0 && n != (
					1 / 0
				) && n != - (
					1 / 0
				) ) { /* jshint ignore:line */
					n = (
						    n > 0 || - 1
					    ) * Math.floor( Math.abs( n ) );
				}
			}

			for ( k = n >= 0 ? Math.min( n, len - 1 ) : len - Math.abs( n ); k >= 0; k -- ) {
				if ( k in t && t[k] === searchElement ) {
					return k;
				}
			}
			return - 1;
		};
	}
};
var MonsterInsightsObject = new MonsterInsights();
