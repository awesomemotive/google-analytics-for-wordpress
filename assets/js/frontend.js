/** 
 * Developer's Notice:
 * 
 * Note: JS in this file (and this file itself) is not garunteed backwards compatibility. JS can be added, changed or removed at any time without notice.
 * For more information see the `Backwards Compatibility Guidelines for Developers` section of the README.md file.
 */
/**
 * Handles:
 * - JS Events handling
 *
 * @since 6.0.12
 */
var MonsterInsights = function(){
	// MonsterInsights JS  events tracking works on all major browsers, including IE starting at IE 7, via polyfills for any major JS function used that
	// is not supported by at least  95% of the global and/or US browser marketshare. Currently, IE 7 & 8 which as of 2/14/17 have under 0.25% global marketshare, require
	// us to polyfill Array.prototype.lastIndexOf, and if they continue to drop, we might remove this polyfill at some point. In that case note that events tracking
	// for IE 7/8 will continue to work, with the exception of events tracking of downloads.
	var lastClicked = [];
	
	this.setLastClicked = function(valuesArray,fieldsArray,tracked){ 
		valuesArray = typeof valuesArray !== 'undefined' ? valuesArray : [];
		fieldsArray = typeof fieldsArray !== 'undefined' ? fieldsArray : [];
		tracked     = typeof tracked !== 'undefined' ? tracked : false;

		lastClicked.valuesArray = valuesArray;
		lastClicked.fieldsArray = fieldsArray;
	};

	this.getLastClicked = function () {
		return lastClicked;
	};

	function __gaTrackerIsDebug () {
		if ( monsterinsights_frontend.is_debug_mode === "true" || window.monsterinsights_debug_mode ) {
			 return true;
		} else {
			return false;
		}
	}

	function __gaTrackerSend ( valuesArray, fieldsArray ) {
		valuesArray = typeof valuesArray !== 'undefined' ? valuesArray : [];
		fieldsArray = typeof fieldsArray !== 'undefined' ? fieldsArray : {};

		__gaTracker( 'send', fieldsArray );

		lastClicked.valuesArray = valuesArray;
		lastClicked.fieldsArray = fieldsArray;
		lastClicked.tracked     = true;
		__gaTrackerLog( 'Tracked: ' + valuesArray.type );
		__gaTrackerLog( lastClicked );
	}

	function __gaTrackerNotSend ( valuesArray ) {
		valuesArray             = typeof valuesArray !== 'undefined' ? valuesArray : [];

		lastClicked.valuesArray = valuesArray;
		lastClicked.fieldsArray = [];
		lastClicked.tracked     = false;
		__gaTrackerLog( 'Not Tracked: ' + valuesArray.exit );
		__gaTrackerLog( lastClicked );
	}

	function __gaTrackerLog ( message ) {
		if ( __gaTrackerIsDebug() ) {
			console.dir( message );
		}
	}

	function __gaTrackerStringTrim( x ) {
		return x.replace(/^\s+|\s+$/gm,'');
	}

	function __gaTrackerGetDomain() {
	   var i=0,currentdomain=document.domain,p=currentdomain.split('.'),s='_gd'+(new Date()).getTime();
	   while(i<(p.length-1) && document.cookie.indexOf(s+'='+s)==-1){
		  currentdomain = p.slice(-1-(++i)).join('.');
		  document.cookie = s+"="+s+";domain="+currentdomain+";";
	   }
	   document.cookie = s+"=;expires=Thu, 01 Jan 1970 00:00:01 GMT;domain="+currentdomain+";";
	   return currentdomain;
	}

	function __gaTrackerGetExtension( extension ) {
		extension = extension.toString();
		extension = extension.substring( 0, (extension.indexOf( "#" ) == -1 ) ? extension.length : extension.indexOf( "#" ) ); /* Remove the anchor at the end, if there is one */
		extension = extension.substring( 0, (extension.indexOf( "?" ) == -1 ) ? extension.length : extension.indexOf( "?" ) ); /* Remove the query after the file name, if there is one */
		extension = extension.substring( extension.lastIndexOf( "/" ) + 1, extension.length ); /* Remove everything before the last slash in the path */
		if ( extension.length > 0 && extension.indexOf('.') !== -1 ) { // If there's a period left in the URL, then there's a extension. Else it is not a extension.
			extension = extension.substring( extension.indexOf( "." ) + 1 ); /* Remove everything but what's after the first period */
			return extension;
		} else {
			return "";
		}
	}

	function __gaTrackerLoaded() {
		return typeof(__gaTracker) !== 'undefined' && __gaTracker && __gaTracker.hasOwnProperty( "loaded" ) && __gaTracker.loaded == true; // jshint ignore:line
	}

	function __gaTrackerTrackedClick( event ) {
		return event.which == 1 || event.which == 2 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey;
	}

	function __gaTrackerGetDownloadExtensions() {
		var download_extensions = [];
		if ( typeof monsterinsights_frontend.download_extensions == 'string' ) {
			download_extensions = monsterinsights_frontend.download_extensions.split(",");
		}
		return download_extensions;
	}

	function __gaTrackerGetInboundPaths() {
		var inbound_paths = [];
		if ( typeof monsterinsights_frontend.inbound_paths == 'string' ) {
			inbound_paths = monsterinsights_frontend.inbound_paths.split(",");
		}
		return inbound_paths;
	}

	function __gaTrackerTrackedClickType( event ) {
		if ( event.which == 1 ) {
			return 'event.which=1';
		} else if ( event.which == 2 ) {
			return 'event.which=2';
		} else if (  event.metaKey ){
			return 'metaKey';
		} else if (  event.ctrlKey ) {
			return 'ctrlKey';
		} else if ( event.shiftKey ) {
			return 'shiftKey';
		} else if (  event.altKey ) {
			return 'altKey';
		} else {
			return '';
		}
	}

	function __gaTrackerLinkType( el ) {
		var download_extensions = __gaTrackerGetDownloadExtensions();
		var inbound_paths       = __gaTrackerGetInboundPaths();
		var type                = 'unknown';
		var link 				= el.href;
		var extension           = __gaTrackerGetExtension( el.href );
		var currentdomain       = __gaTrackerGetDomain();
		var hostname            = el.hostname;
		var protocol            = el.protocol;
		var pathname       		= el.pathname;
		link 					= link.toString();
		var index, len;

		if ( link.match( /^javascript\:/i ) ) {
			type = 'internal'; // if it's a JS link, it's internal
		} else if ( protocol && protocol.length > 0 && ( __gaTrackerStringTrim( protocol ) == 'tel' || __gaTrackerStringTrim( protocol ) == 'tel:' ) ) { /* If it's a telephone link */
			type = "tel"; 
		} else if ( protocol && protocol.length > 0 && ( __gaTrackerStringTrim( protocol ) == 'mailto' ||  __gaTrackerStringTrim( protocol ) == 'mailto:' ) ) { /* If it's a email */
			type = "mailto"; 
		} else if ( hostname && currentdomain && hostname.length > 0 && currentdomain.length > 0 && ! hostname.endsWith( currentdomain ) ) { /* If it's a outbound */
			type = "external"; 
		} else if ( pathname && inbound_paths.length > 0 && pathname.length > 0 ) { /* If it's an internal as outbound */
			for ( index = 0, len = inbound_paths.length; index < len; ++index ) {
				if ( inbound_paths[ index ].length > 0 && pathname.startsWith( inbound_paths[ index ] ) ) {
					type = "internal-as-outbound";
					break;
				}
			}
		/* Enable window.monsterinsights_experimental_mode at your own risk. We might eventually remove it. Also you may/can/will burn through GA quota for your property quickly. */
		} else if ( hostname && window.monsterinsights_experimental_mode && hostname.length > 0 && document.domain.length > 0 && hostname !== document.domain ) { /* If it's a cross-hostname link */
			type = "cross-hostname";
		} 

		if ( extension && type === 'unknown' && download_extensions.length > 0 && extension.length > 0 ) { /* If it's a download */
			for ( index = 0, len = download_extensions.length; index < len; ++index ) {
				if ( download_extensions[ index ].length > 0 && ( link.endsWith( download_extensions[ index ] ) || download_extensions[ index ]  == extension ) ) {
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

	function __gaTrackerLinkTarget( el, event ) {

		/* Is actual target set and not _(self|parent|top)? */
		var target = ( el.target && !el.target.match( /^_(self|parent|top)$/i ) ) ? el.target : false;

		/* Assume a target if Ctrl|shift|meta-click */
		if ( event.ctrlKey || event.shiftKey || event.metaKey || event.which == 2 ) {
			target = "_blank";
		}
		return target;
	}

	function __gaTrackerClickEvent( event ) {
		var el            = event.srcElement || event.target;
		var valuesArray   = [];
		var fieldsArray;

		// Start Values Array
		valuesArray.el         = el;
		valuesArray.ga_loaded  = __gaTrackerLoaded();
		valuesArray.click_type = __gaTrackerTrackedClickType( event );

		/* If GA is blocked or not loaded, or not main|middle|touch click then don't track */
		if ( ! __gaTrackerLoaded() || ! __gaTrackerTrackedClick( event ) ) {
			valuesArray.exit = 'loaded';
			__gaTrackerNotSend( valuesArray );
			return;
		}

		/* Loop up the DOM tree through parent elements if clicked element is not a link (eg: an image inside a link) */
		while ( el && (typeof el.tagName == 'undefined' || el.tagName.toLowerCase() != 'a' || ! el.href ) ) {
			el = el.parentNode;
		}

		/* if a link with valid href has been clicked */
		if ( el && el.href && ! el.hasAttribute('xlink:href') ) {
			var link                		= el.href;														/* What link are we tracking */
			var extension           		= __gaTrackerGetExtension( el.href );							/* What extension is this link */
			var download_extensions 		= __gaTrackerGetDownloadExtensions(); 							/* Let's get the extensions to track */
			var inbound_paths       		= __gaTrackerGetInboundPaths(); 								/* Let's get the internal paths to track */
			var home_url            		= monsterinsights_frontend.home_url; 							/* Let's get the url to compare for external/internal use */
			var track_download_as   		= monsterinsights_frontend.track_download_as; 					/* should downloads be tracked as events or pageviews */
			var internal_label      		= "outbound-link-" + monsterinsights_frontend.internal_label; 	/* What is the prefix for internal-as-external links */
			var currentdomain       		= __gaTrackerGetDomain();										/* What domain are we on? */
			var type                		= __gaTrackerLinkType( el ); 									/* What type of link is this? */
			var target 						= __gaTrackerLinkTarget( el, event );							/* Is a new tab/window being opened? */

			/* Element */
			valuesArray.el                  = el;					/* el is an a element so we can parse it */
			valuesArray.el_href             = el.href; 				/* "http://example.com:3000/pathname/?search=test#hash" */
			valuesArray.el_protocol         = el.protocol; 			/* "http:" */
			valuesArray.el_hostname         = el.hostname; 			/* "example.com" */
			valuesArray.el_port             = el.port; 				/* "3000" */
			valuesArray.el_pathname         = el.pathname; 			/* "/pathname/" */
			valuesArray.el_search           = el.search; 			/* "?search=test" */
			valuesArray.el_hash             = el.hash;				/* "#hash" */
			valuesArray.el_host             = el.host; 				/* "example.com:3000" */

			/* Settings */
			valuesArray.debug_mode          = __gaTrackerIsDebug(); /* "example.com:3000" */
			valuesArray.download_extensions = download_extensions;  /* Let's get the extensions to track */
			valuesArray.inbound_paths       = inbound_paths; 		/* Let's get the internal paths to track */
			valuesArray.home_url            = home_url;				/* Let's get the url to compare for external/internal use */
			valuesArray.track_download_as   = track_download_as; 	/* should downloads be tracked as events or pageviews */
			valuesArray.internal_label      = internal_label;		/* What is the prefix for internal-as-external links */

			/* Parsed/Logic */
			valuesArray.link                = link; 				/* What link are we tracking */
			valuesArray.extension           = extension; 			/* What extension is this link */
			valuesArray.type                = type; 				/* What type of link is this */
			valuesArray.target              = target;				/* Is a new tab/window being opened? */
			valuesArray.title 				= el.title || el.textContent || el.innerText; /* Try link title, then text content */

			/* Let's track everything but internals (that aren't internal-as-externals) and javascript */
			if ( type !== 'internal' && type !== 'javascript' ) {

				var __gaTrackerHitBackRun = false; /* Tracker has not yet run */

				/* HitCallback to open link in same window after tracker */
				var __gaTrackerHitBack = function() {
					/* Run the hitback only once */
					if ( __gaTrackerHitBackRun ){
						return;
					}
					__gaTrackerHitBackRun = true;
					window.location.href = link;
				};

				var __gaTrackerNoRedirectExternal = function() {
					valuesArray.exit = 'external';
					__gaTrackerNotSend( valuesArray );
				};

				var __gaTrackerNoRedirectInboundAsExternal = function() {
					valuesArray.exit = 'internal-as-outbound';
					__gaTrackerNotSend( valuesArray );
				};
				var __gaTrackerNoRedirectCrossHostname = function() {
					valuesArray.exit = 'cross-hostname';
					__gaTrackerNotSend( valuesArray );
				};

				if ( target || type == 'mailto' || type == 'tel' ) { /* If target opens a new window then just track */
					if ( type == 'download' ) {
						if ( track_download_as == 'pageview' ) {
							fieldsArray = { 
								hitType : 'pageview',
								page    : link,
							};

							__gaTrackerSend( valuesArray, fieldsArray );
						} else {
							fieldsArray = {
								hitType       : 'event',
								eventCategory : 'download',
								eventAction   : link,
								eventLabel    : valuesArray.title,
							};

							__gaTrackerSend( valuesArray, fieldsArray );
						}
					} else if ( type == 'tel' ) {
						fieldsArray = {
							hitType       : 'event',
							eventCategory : 'tel',
							eventAction   : link,
							eventLabel    : valuesArray.title.replace('tel:', ''),
						};

						__gaTrackerSend( valuesArray, fieldsArray );
					} else if ( type == 'mailto' ) {
						fieldsArray = {
							hitType       : 'event',
							eventCategory : 'mailto',
							eventAction   : link,
							eventLabel    : valuesArray.title.replace('mailto:', ''),
						};

						__gaTrackerSend( valuesArray, fieldsArray );
					} else if ( type == 'internal-as-outbound' ) {
						fieldsArray = {
							hitType       : 'event',
							eventCategory : internal_label,
							eventAction   : link,
							eventLabel    : valuesArray.title,
						};

						__gaTrackerSend( valuesArray, fieldsArray );
					} else if ( type == 'external' ) {
						fieldsArray = {
							hitType: 'event',
							eventCategory:'outbound-link',
							eventAction: link,
							eventLabel: valuesArray.title,
						};

						__gaTrackerSend( valuesArray, fieldsArray );
					} else if ( type == 'cross-hostname' ) {
						fieldsArray = {
							hitType: 'event',
							eventCategory:'cross-hostname',
							eventAction: link,
							eventLabel: valuesArray.title,
						};

						__gaTrackerSend( valuesArray, fieldsArray );
					} else {
						valuesArray.exit = 'type';
						__gaTrackerNotSend( valuesArray );
					}
				} else { 
					/* Prevent standard click, track then open */
					if ( type != 'cross-hostname' && type != 'external' && type != 'internal-as-outbound' ) {
						if (! event.defaultPrevented ) {
							if ( event.preventDefault ) {
								event.preventDefault();
							} else {
								event.returnValue = false;
							}
						}
					}
					
					if ( type == 'download' ) {
						if ( track_download_as == 'pageview' ) {
							fieldsArray = {
								hitType       : 'pageview',
								page 		  : link,
								hitCallback   : __gaTrackerHitBack,
							};

							__gaTrackerSend( valuesArray, fieldsArray );
						} else {
							fieldsArray = {
								hitType       : 'event',
								eventCategory : 'download',
								eventAction   : link,
								eventLabel    : valuesArray.title,
								hitCallback   : __gaTrackerHitBack,
							};

							__gaTrackerSend( valuesArray, fieldsArray );
						}
					} else if ( type == 'internal-as-outbound' ) {
						window.onbeforeunload = function(e) {
							if (! event.defaultPrevented ) {
								if ( event.preventDefault ) {
									event.preventDefault();
								} else {
									event.returnValue = false;
								}
							}

							fieldsArray = {
								hitType       : 'event',
								eventCategory : internal_label,
								eventAction   : link,
								eventLabel    : valuesArray.title,
								hitCallback   : __gaTrackerHitBack,
							};

							if ( navigator.sendBeacon ) {
								fieldsArray.transport = 'beacon';
							}

							__gaTrackerSend( valuesArray, fieldsArray );
							setTimeout( __gaTrackerHitBack, 1000 );
						};
					} else if ( type == 'external' ) {
						window.onbeforeunload = function(e) {
							if (! event.defaultPrevented ) {
								if ( event.preventDefault ) {
									event.preventDefault();
								} else {
									event.returnValue = false;
								}
							}
							
							fieldsArray = {
								hitType       : 'event',
								eventCategory : 'outbound-link',
								eventAction   : link,
								eventLabel    : valuesArray.title,
								hitCallback   : __gaTrackerHitBack,
							};

							if ( navigator.sendBeacon ) {
								fieldsArray.transport = 'beacon';
							}

							__gaTrackerSend( valuesArray, fieldsArray );
							setTimeout( __gaTrackerHitBack, 1000 );
						};						
					} else if ( type == 'cross-hostname' ) {
						window.onbeforeunload = function(e) {
							if (! event.defaultPrevented ) {
								if ( event.preventDefault ) {
									event.preventDefault();
								} else {
									event.returnValue = false;
								}
							}
							
							fieldsArray = {
								hitType       : 'event',
								eventCategory : 'cross-hostname',
								eventAction   : link,
								eventLabel    : valuesArray.title,
								hitCallback   : __gaTrackerHitBack,
							};

							if ( navigator.sendBeacon ) {
								fieldsArray.transport = 'beacon';
							}

							__gaTrackerSend( valuesArray, fieldsArray );
							setTimeout( __gaTrackerHitBack, 1000 );
						};						
					} else {
						valuesArray.exit = 'type';
						__gaTrackerNotSend( valuesArray );
					}

					if ( type != 'external' && type != 'cross-hostname' && type != 'internal-as-outbound' ) {
						/* Run hitCallback again if GA takes longer than 1 second */
						setTimeout( __gaTrackerHitBack, 1000 );
					} else {
						if ( type == 'external' ) {
							setTimeout( __gaTrackerNoRedirectExternal, 1100 );
						} else if ( type == 'cross-hostname' ) {
							setTimeout( __gaTrackerNoRedirectCrossHostname, 1100 );
						} else {
							setTimeout( __gaTrackerNoRedirectInboundAsExternal, 1100 );
						}
					}
				}
			} else {
				valuesArray.exit = 'internal';
				__gaTrackerNotSend( valuesArray );
			}
		} else {
			valuesArray.exit = 'notlink';
			__gaTrackerNotSend( valuesArray );
		}
	}
	var prevHash = window.location.hash;
	function __gaTrackerHashChangeEvent() {
		// Todo: Ready this section for JS unit testing
		if ( monsterinsights_frontend.hash_tracking === "true" && prevHash != window.location.hash ) {
			prevHash = window.location.hash;
			__gaTracker('set', 'page', location.pathname + location.search + location.hash );
			__gaTracker('send', 'pageview' );
			__gaTrackerLog( "Hash change to: " + location.pathname + location.search + location.hash );
		} else {
			__gaTrackerLog( "Hash change to (untracked): " + location.pathname + location.search + location.hash );
		}
	}

	/* Attach the event to all clicks in the document after page has loaded */
	var __gaTrackerWindow    = window;
	if ( __gaTrackerWindow.addEventListener ) {
		__gaTrackerWindow.addEventListener( 
			"load", 
			function() { 
				document.body.addEventListener(
					"click", 
					__gaTrackerClickEvent,
					 false
				);
			}, 
			false
		);
		window.addEventListener("hashchange", __gaTrackerHashChangeEvent, false	);
	} else { 
		if ( __gaTrackerWindow.attachEvent ) {
			__gaTrackerWindow.attachEvent(
				"onload", 
				function() {
					document.body.attachEvent( "onclick", __gaTrackerClickEvent);
				}
			);
			window.attachEvent( "onhashchange", __gaTrackerHashChangeEvent);
		}
	}

	if (typeof String.prototype.endsWith !== 'function') {
		String.prototype.endsWith = function(suffix) {
			return this.indexOf(suffix, this.length - suffix.length) !== -1;
		};
	}
	if (typeof String.prototype.startsWith !== 'function') {
		String.prototype.startsWith = function(prefix) {
			return this.indexOf(prefix) === 0;
		};
	}

	if ( typeof Array.prototype.lastIndexOf !== 'function' ) {
	  Array.prototype.lastIndexOf = function(searchElement /*, fromIndex*/) {
		'use strict';

		if (this === void 0 || this === null) {
		  throw new TypeError();
		}

		var n, k,
		  t = Object(this),
		  len = t.length >>> 0; // jshint ignore:line
		if (len === 0) {
		  return -1;
		}

		n = len - 1;
		if (arguments.length > 1) {
		  n = Number(arguments[1]);
		  if (n != n) {
			n = 0;
		  }
		  else if (n != 0 && n != (1 / 0) && n != -(1 / 0)) { // jshint ignore:line
			n = (n > 0 || -1) * Math.floor(Math.abs(n));
		  }
		}

		for (k = n >= 0 ? Math.min(n, len - 1) : len - Math.abs(n); k >= 0; k--) {
		  if (k in t && t[k] === searchElement) {
			return k;
		  }
		}
		return -1;
	  };
	}
};
var MonsterInsightsObject = new MonsterInsights();