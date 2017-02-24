<?php
/**
 * Events JS class.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage  Events
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MonsterInsights_Events_JS {

	/**
	 * Holds the base class object.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var object $base Base class object.
	 */
	public $base;
	
	/**
	 * Holds the name of the events type.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $name Name of the events type.
	 */
	public $name = 'js';

	/**
	 * Version of the events class.
	 *
	 * @since 6.0.0
	 * @access public
	 *
	 * @var string $version Version of the events class.
	 */
	public $version = '1.0.0';

	/**
	 * Primary class constructor.
	 *
	 * @since 6.0.0
	 * @access public
	 */
	public function __construct() {
		$this->base     = MonsterInsights();
		$tracking = monsterinsights_get_option( 'tracking_mode', false );
		$events = monsterinsights_get_option( 'events_mode', false );
		if ( $events === 'js' && $tracking === 'analytics' ) {
			add_action( 'wp_head', array( $this, 'output_javascript' ), 9 ); 
			add_action( 'login_head', array( $this, 'output_javascript' ), 9 );
		}
	}

	/**
	 * Outputs the Javascript for JS tracking on the page.
	 *
	 * @since 6.0.0
	 * @access public
	 * 
	 * @return string
	 */
	public function output_javascript() {
		// What should we track downloads as?
		$track_download_as = monsterinsights_get_option( 'track_download_as', 'pageview' );
		$track_download_as = ( $track_download_as === 'pageview' || $track_download_as === 'event' ) ? $track_download_as : 'pageview';

		// What label should be used for internal links?
		$internal_label = monsterinsights_get_option( 'track_internal_as_label', 'int' );
		if ( ! empty( $internal_label ) && is_string( $internal_label ) ) {
			$internal_label = trim( $internal_label, ',' );
			$internal_label = trim( $internal_label );
		}

		// If the label is empty, set a default value
		if ( empty( $internal_label ) ) {
			$internal_label = 'int';
		}

		$internal_label = esc_js( $internal_label );

		// Get download extensions to track
		$inbound_paths = monsterinsights_get_option( 'track_internal_as_outbound', '' );
		$inbound_paths = explode( ',', str_replace( '.', '', $inbound_paths ) );

		$i = 0;
		foreach( $inbound_paths as $path ){
			$inbound_paths[ $i ] = '"'. esc_js( trim( $path ) ) . '"';
			$i++;
		}

		$inbound_paths = "[" . implode( ",", $inbound_paths ) . "]";
		if ( $inbound_paths === '[""]' ) {
			$inbound_paths = "[]";
		}

		// Get download extensions to track
		$download_extensions = monsterinsights_get_option( 'extensions_of_files', '' );
		$download_extensions = explode( ',', str_replace( '.', '', $download_extensions ) );

		$i = 0;
		foreach( $download_extensions as $extension ){
			$download_extensions[ $i ] = '"'. esc_js( trim( $extension ) ) . '"';
			$i++;
		}

		$download_extensions = "[" . implode( ",", $download_extensions ) . "]";
		if ( $download_extensions === '[""]' ) {
			$download_extensions = "[]";
		}

		$track_download_as = monsterinsights_get_option( 'track_download_as', '' );
		$track_download_as = $track_download_as === 'pageview' ? 'pageview' : 'event';

		$is_debug_mode     =  monsterinsights_is_debug_mode();
		if ( current_user_can( 'manage_options' ) && $is_debug_mode ) {
			$is_debug_mode = 'true';
		} else {
			$is_debug_mode = 'false';
		}

		if ( $is_debug_mode === 'true' ) {
		ob_start();
		?>
<!-- MonsterInsights JS Event Tracking -->
<script type="text/javascript" data-cfasync="false">
(function(){
	// MonsterInsights JS  events tracking works on all major browsers, including IE starting at IE 7, via polyfills for any major JS fucntion used that
	// is not supported by at least  95% of the global and/or US browser marketshare. Currently, IE 7 & 8 which as of 2/14/17 have under 0.25% global marketshare, require
	// us to polyfill Array.prototype.lastIndexOf, and if they continue to drop, we might remove this polyfill at some point. In that case note that events tracking
	// for IE 7/8 will continue to work, with the exception of events tracking of downloads.
	function __gaTrackerClickEventPHP() {
		var phpvalues = { 
			'is_debug_mode' 	  : <?php echo $is_debug_mode; ?>,
			'download_extensions' : <?php echo $download_extensions; ?>, /* Let's get the extensions to track */
			'inbound_paths'       : <?php echo $inbound_paths; ?>, /* Let's get the internal paths to track */
			'home_url'            : "<?php echo home_url(); ?>", /* Let's get the url to compare for external/internal use */
			'track_download_as'   : "<?php echo $track_download_as; ?>", /* should downloads be tracked as events or pageviews */
			'internal_label'      : "outbound-link-<?php echo $internal_label; ?>", /* What is the prefix for internal-as-external links */
		};
		return phpvalues;
	}

	function __gaTrackerClickEvent( event ) {
		var phpvalues     = __gaTrackerClickEventPHP();
		var is_debug_mode =  phpvalues.is_debug_mode || window.monsterinsights_debug_mode; /* Console log instead of running? */
		var el = event.srcElement || event.target;
		if ( is_debug_mode ) {
			console.log( "__gaTracker.hasOwnProperty(loaded)" );
			console.log( __gaTracker.hasOwnProperty( "loaded" ) );
			console.log( "__gaTracker.loaded" );
			console.log( __gaTracker.loaded );
			console.log( "Event.which: " + event.which );
			console.log( "El: ");
			console.log( el );
			console.log( "Will track: " + ! __gaTracker.hasOwnProperty( "loaded" ) || __gaTracker.loaded != true || ( event.which != 1 && event.which != 2 && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey  ) );
		}

		/* If GA is blocked or not loaded, or not main|middle|touch click then don't track */
		if ( ! __gaTracker.hasOwnProperty( "loaded" ) || __gaTracker.loaded != true || ( event.which != 1 && event.which != 2 && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey  ) ) {
			return;
		}

		/* Loop up the DOM tree through parent elements if clicked element is not a link (eg: an image inside a link) */
		while ( el && (typeof el.tagName == 'undefined' || el.tagName.toLowerCase() != 'a' || !el.href ) ) {
			el = el.parentNode;
		}

		/* if a link with valid href has been clicked */
		if ( el && el.href ) {
			/* el is an a element so we can parse it */
			/* el.href;      => "http://example.com:3000/pathname/?search=test#hash" */
			/* el.protocol;  => "http:" */
			/* el.hostname;  => "example.com" */
			/* el.port;      => "3000" */
			/* el.pathname;  => "/pathname/" */
			/* el.search;    => "?search=test" */
			/* el.hash;      => "#hash"
			/* el.host;      => "example.com:3000 */

			var link 				= el.href;
			var extension  			= el.href;
			var type 				= 'internal'; /* By default, we assume all links are internal ones, which we don't track by default */
			var download_extensions = phpvalues.download_extensions; /* Let's get the extensions to track */
			var inbound_paths       = phpvalues.inbound_paths; /* Let's get the internal paths to track */
			var home_url            = phpvalues.home_url; /* Let's get the url to compare for external/internal use */
			var track_download_as   = phpvalues.track_download_as; /* should downloads be tracked as events or pageviews */
			var internal_label      = "outbound-link-" + phpvalues.internal_label; /* What is the prefix for internal-as-external links */

			/* Remove the anchor at the end, if there is one */
			extension = extension.substring( 0, (extension.indexOf( "#" ) == -1 ) ? extension.length : extension.indexOf( "#" ) );

			/* Remove the query after the file name, if there is one */
			extension = extension.substring( 0, (extension.indexOf( "?" ) == -1 ) ? extension.length : extension.indexOf( "?" ) );

			/* Remove everything before the last slash in the path */
			extension = extension.substring( extension.lastIndexOf( "/" ) + 1, extension.length );

			/* Remove everything but what's after the first period */
			extension = extension.substring( extension.indexOf( "." ) + 1 );

			var currentdomain = (function(){
			   var i=0,currentdomain=document.domain,p=currentdomain.split('.'),s='_gd'+(new Date()).getTime();
			   while(i<(p.length-1) && document.cookie.indexOf(s+'='+s)==-1){
				  currentdomain = p.slice(-1-(++i)).join('.');
				  document.cookie = s+"="+s+";domain="+currentdomain+";";
			   }
			   document.cookie = s+"=;expires=Thu, 01 Jan 1970 00:00:01 GMT;domain="+currentdomain+";";
			   return currentdomain;
			})();

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

			if ( typeof Array.prototype.includes !== 'function') {
			  Object.defineProperty(Array.prototype, 'includes', {
				value: function(searchElement, fromIndex) {

				  // 1. Let O be ? ToObject(this value).
				  if (this == null) {
					throw new TypeError('"this" is null or not defined');
				  }

				  var o = Object(this);

				  // 2. Let len be ? ToLength(? Get(O, "length")).
				  var len = o.length >>> 0;

				  // 3. If len is 0, return false.
				  if (len === 0) {
					return false;
				  }

				  // 4. Let n be ? ToInteger(fromIndex).
				  //    (If fromIndex is undefined, this step produces the value 0.)
				  var n = fromIndex | 0;

				  // 5. If n ≥ 0, then
				  //  a. Let k be n.
				  // 6. Else n < 0,
				  //  a. Let k be len + n.
				  //  b. If k < 0, let k be 0.
				  var k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);

				  // 7. Repeat, while k < len
				  while (k < len) {
					// a. Let elementK be the result of ? Get(O, ! ToString(k)).
					// b. If SameValueZero(searchElement, elementK) is true, return true.
					// c. Increase k by 1.
					// NOTE: === provides the correct "SameValueZero" comparison needed here.
					if (o[k] === searchElement) {
					  return true;
					}
					k++;
				  }

				  // 8. Return false
				  return false;
				}
			  });
			}

			if ( typeof Array.prototype.lastIndexOf !== 'function' ) {
			  Array.prototype.lastIndexOf = function(searchElement /*, fromIndex*/) {
				'use strict';

				if (this === void 0 || this === null) {
				  throw new TypeError();
				}

				var n, k,
				  t = Object(this),
				  len = t.length >>> 0;
				if (len === 0) {
				  return -1;
				}

				n = len - 1;
				if (arguments.length > 1) {
				  n = Number(arguments[1]);
				  if (n != n) {
					n = 0;
				  }
				  else if (n != 0 && n != (1 / 0) && n != -(1 / 0)) {
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

			function monsterinsightsStringTrim(x) {
				return x.replace(/^\s+|\s+$/gm,'');
			}

			if ( is_debug_mode ) {
				console.log( "Link: " + link);
				console.log( "Extension: " + extension );
				console.log( "Protocol: " + el.protocol );
				console.log( "External: " + (el.hostname.length > 0 && currentdomain.length > 0 && ! el.hostname.endsWith( currentdomain )) );
				console.log( "Current domain: " + currentdomain );
				console.log( "Link domain: " + el.hostname );
			}

			/* Let's get the type of click event this is */
			if ( monsterinsightsStringTrim( el.protocol ) == 'mailto' ||  monsterinsightsStringTrim( el.protocol ) == 'mailto:' ) { /* If it's an email */
				type = "mailto"; 
			} else if ( download_extensions.length > 0 && extension.length > 0 && download_extensions.includes(extension) ) { /* If it's a download */
				type = "download"; 
			} else if ( el.hostname.length > 0 && currentdomain.length > 0 && ! el.hostname.endsWith( currentdomain ) ) { /* If it's a outbound */
				type = "external"; 
			} else {
				var index, len;
				var pathname = el.pathname;
				for ( index = 0, len = inbound_paths.length; index < len; ++index ) {
					if ( pathname.startsWith( inbound_paths[ index ] ) ) {
						type = "internal-as-outbound";
						break;
					}
				}
			}

			if ( is_debug_mode ) {
				console.log( "Type: " + type );
			}

			/* Let's track everything but internals (that aren't internal-as-externals) */
			if ( type !== 'internal' && ! link.match( /^javascript\:/i ) ) {

				/* Is actual target set and not _(self|parent|top)? */
				var target = ( el.target && !el.target.match( /^_(self|parent|top)$/i ) ) ? el.target : false;

				/* Assume a target if Ctrl|shift|meta-click */
				if ( event.ctrlKey || event.shiftKey || event.metaKey || event.which == 2 ) {
					target = "_blank";
				}

				if ( is_debug_mode ) {
					console.log( "Control Key: " + event.ctrlKey );
					console.log( "Shift Key: " + event.shiftKey );
					console.log( "Meta Key: " + event.metaKey );
					console.log( "Which Key: " + event.which );
					console.log( "Target: " + target );
				}

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
				
				if ( target ) { /* If target opens a new window then just track */
					if ( type == 'download' ) {
						if ( track_download_as == 'pageview' ) {
							if ( ! is_debug_mode ) {
								__gaTracker( 'send', 'pageview', link );
							} else {
								console.log( "Target | Download | Send | Pageview | " + link );
							}
						} else {
							if ( ! is_debug_mode ) {
								__gaTracker( 'send', 'event', 'download', link );
							} else {
								console.log( "Target | Download | Send | Event | " + link );
							}
						}
					} else if ( type == 'mailto' ) {
						if ( ! is_debug_mode ) {
							__gaTracker( 'send', 'event', 'mailto', link );
						} else {
							console.log( "Target | Mailto | Send | Event | Mailto | " + link );
						}

					} else if ( type == 'internal-as-outbound' ) {
						if ( ! is_debug_mode ) {
							__gaTracker( 'send', 'event', internal_label, link, el.title );
						} else {
							console.log( "Target | Internal-As-Outbound | Send | event | " + internal_label + " | " + link + " | " + el.title );
						}

					} else if ( type == 'external' ) {
						if ( ! is_debug_mode ) {
							__gaTracker( 'send', 'event', 'outbound-link', link, el.title );
						} else {
							console.log( "Target | External | Send | 'outbound-link' | " + link + " | " + el.title );
						}

					} else {
						if ( is_debug_mode ) {
							console.log(  "Target | " + type + " | " + link + " is not a tracked click." );
						}
					}

					if ( is_debug_mode ) {
						return false;
					}
				} else { /* Prevent standard click, track then open */
						if (!event.defaultPrevented) {
							event.preventDefault ? event.preventDefault() : event.returnValue = !1;
						}

					if ( type == 'download' ) {
						if ( track_download_as == 'pageview' ) {
							if ( ! is_debug_mode ) {
								__gaTracker( 'send', 'pageview', link, { "hitCallback": __gaTrackerHitBack } );
							} else {
								console.log( "Not Target | Download | Send | Pageview | " + link );
							}
						} else {
							if ( ! is_debug_mode ) {
								__gaTracker( 'send', 'event', 'download',{ "hitCallback": __gaTrackerHitBack } );
							} else {
								console.log( "Not Target | Download | Send | Event | " + link );
							}
						}

					} else if ( type == 'mailto' ) {
						if ( ! is_debug_mode ) {
							__gaTracker( 'send', 'event', 'mailto', link, { "hitCallback": __gaTrackerHitBack } );
						} else {
							console.log( "Not Target | Mailto | Send | Event | Mailto | " + link );
						}

					} else if ( type == 'internal-as-outbound' ) {
						window.onbeforeunload = function(e) {
							if ( ! is_debug_mode ) {
								if ( ! navigator.sendBeacon ) {
									__gaTracker( 'send', 'event', internal_label, link, el.title, { "hitCallback": __gaTrackerHitBack } );
								} else {
									__gaTracker( 'send', 'event', internal_label, link, el.title, { transport: 'beacon' } );
								}
							} else {
								console.log( "Not Target | Internal-As-Outbound | Send | event | " + internal_label + " | " + link + " | " + el.title );
							}
						};
					} else if ( type == 'external' ) {
						window.onbeforeunload = function(e) {
							if ( ! is_debug_mode ) {
								if ( ! navigator.sendBeacon ) {
									__gaTracker( 'send', 'event', 'outbound-link', link, el.title, { "hitCallback": __gaTrackerHitBack } )
								} else {
									__gaTracker( 'send', 'event', 'outbound-link', link, el.title, { transport: 'beacon' } )
								}
							} else {
								console.log( "Not Target | External | Send | 'outbound-link' | " + link + " | " + el.title );
							}
						};
					} else {
						if ( is_debug_mode ) {
							console.log(  "Not Target | " + type + " | " + link + " is not a tracked click." );
						}
					}
					
					if ( is_debug_mode ) {
						return false;
					}

					/* Run hitCallback again if GA takes longer than 1 second */
					setTimeout( __gaTrackerHitBack, 1000 );
				}
			}
		}
	}

	var __gaTrackerWindow = window;
	var __gaTrackerEventType = "click";
	/* Attach the event to all clicks in the document after page has loaded */
	__gaTrackerWindow.addEventListener ? __gaTrackerWindow.addEventListener( "load", function() {document.body.addEventListener(__gaTrackerEventType, __gaTrackerClickEvent, false)}, false)
									   : __gaTrackerWindow.attachEvent && __gaTrackerWindow.attachEvent("onload", function() {document.body.attachEvent( "on" + __gaTrackerEventType, __gaTrackerClickEvent)});
})();
</script>
<!-- End MonsterInsights JS Event Tracking -->
<?php
		$output = ob_get_contents();
		ob_end_clean();
		echo $output;
		} else {		
			ob_start();
			?>
<!-- MonsterInsights JS Event Tracking -->
<script type="text/javascript" data-cfasync="false">
(function(){
function __gaTrackerClickEventPHP() {var phpvalues = { 'is_debug_mode': <?php echo $is_debug_mode; ?>,'download_extensions': <?php echo $download_extensions; ?>,'inbound_paths': <?php echo $inbound_paths; ?>,'home_url': "<?php echo home_url(); ?>",'track_download_as': "<?php echo $track_download_as; ?>",'internal_label': "<?php echo $internal_label; ?>"};return phpvalues;}
function __gaTrackerClickEvent(e){function t(e){return e.replace(/^\s+|\s+$/gm,"")}var n=__gaTrackerClickEventPHP(),o=n.is_debug_mode||window.monsterinsights_debug_mode,a=e.srcElement||e.target;if(o&&(console.log("__gaTracker.hasOwnProperty(loaded)"),console.log(__gaTracker.hasOwnProperty("loaded")),console.log("__gaTracker.loaded"),console.log(__gaTracker.loaded),console.log("Event.which: "+e.which),console.log("El: "),console.log(a),console.log("Will track: "+!__gaTracker.hasOwnProperty("loaded")||1!=__gaTracker.loaded||1!=e.which&&2!=e.which&&!e.metaKey&&!e.ctrlKey&&!e.shiftKey&&!e.altKey)),__gaTracker.hasOwnProperty("loaded")&&1==__gaTracker.loaded&&(1==e.which||2==e.which||e.metaKey||e.ctrlKey||e.shiftKey||e.altKey)){for(;a&&("undefined"==typeof a.tagName||"a"!=a.tagName.toLowerCase()||!a.href);)a=a.parentNode;if(a&&a.href){var r=a.href,l=a.href,i="internal",c=n.download_extensions,d=n.inbound_paths,s=(n.home_url,n.track_download_as),g="outbound-link-"+n.internal_label;l=l.substring(0,-1==l.indexOf("#")?l.length:l.indexOf("#")),l=l.substring(0,-1==l.indexOf("?")?l.length:l.indexOf("?")),l=l.substring(l.lastIndexOf("/")+1,l.length),l=l.substring(l.indexOf(".")+1);var h=function(){for(var e=0,t=document.domain,n=t.split("."),o="_gd"+(new Date).getTime();e<n.length-1&&-1==document.cookie.indexOf(o+"="+o);)t=n.slice(-1-++e).join("."),document.cookie=o+"="+o+";domain="+t+";";return document.cookie=o+"=;expires=Thu, 01 Jan 1970 00:00:01 GMT;domain="+t+";",t}();if("function"!=typeof String.prototype.endsWith&&(String.prototype.endsWith=function(e){return-1!==this.indexOf(e,this.length-e.length)}),"function"!=typeof String.prototype.startsWith&&(String.prototype.startsWith=function(e){return 0===this.indexOf(e)}),"function"!=typeof Array.prototype.includes&&Object.defineProperty(Array.prototype,"includes",{value:function(e,t){if(null==this)throw new TypeError('"this" is null or not defined');var n=Object(this),o=n.length>>>0;if(0===o)return!1;for(var a=0|t,r=Math.max(a>=0?a:o-Math.abs(a),0);o>r;){if(n[r]===e)return!0;r++}return!1}}),"function"!=typeof Array.prototype.lastIndexOf&&(Array.prototype.lastIndexOf=function(e){"use strict";if(void 0===this||null===this)throw new TypeError;var t,n,o=Object(this),a=o.length>>>0;if(0===a)return-1;for(t=a-1,arguments.length>1&&(t=Number(arguments[1]),t!=t?t=0:0!=t&&t!=1/0&&t!=-(1/0)&&(t=(t>0||-1)*Math.floor(Math.abs(t)))),n=t>=0?Math.min(t,a-1):a-Math.abs(t);n>=0;n--)if(n in o&&o[n]===e)return n;return-1}),o&&(console.log("Link: "+r),console.log("Extension: "+l),console.log("Protocol: "+a.protocol),console.log("External: "+(a.hostname.length>0&&h.length>0&&!a.hostname.endsWith(h))),console.log("Current domain: "+h),console.log("Link domain: "+a.hostname)),"mailto"==t(a.protocol)||"mailto:"==t(a.protocol))i="mailto";else if(c.length>0&&l.length>0&&c.includes(l))i="download";else if(a.hostname.length>0&&h.length>0&&!a.hostname.endsWith(h))i="external";else{var u,_,f=a.pathname;for(u=0,_=d.length;_>u;++u)if(f.startsWith(d[u])){i="internal-as-outbound";break}}if(o&&console.log("Type: "+i),"internal"!==i&&!r.match(/^javascript\:/i)){var k=a.target&&!a.target.match(/^_(self|parent|top)$/i)?a.target:!1;(e.ctrlKey||e.shiftKey||e.metaKey||2==e.which)&&(k="_blank"),o&&(console.log("Control Key: "+e.ctrlKey),console.log("Shift Key: "+e.shiftKey),console.log("Meta Key: "+e.metaKey),console.log("Which Key: "+e.which),console.log("Target: "+k));var v=!1,T=function(){v||(v=!0,window.location.href=r)};if(k){if("download"==i?"pageview"==s?o?console.log("Target | Download | Send | Pageview | "+r):__gaTracker("send","pageview",r):o?console.log("Target | Download | Send | Event | "+r):__gaTracker("send","event","download",r):"mailto"==i?o?console.log("Target | Mailto | Send | Event | Mailto | "+r):__gaTracker("send","event","mailto",r):"internal-as-outbound"==i?o?console.log("Target | Internal-As-Outbound | Send | event | "+g+" | "+r+" | "+a.title):__gaTracker("send","event",g,r,a.title):"external"==i?o?console.log("Target | External | Send | 'outbound-link' | "+r+" | "+a.title):__gaTracker("send","event","outbound-link",r,a.title):o&&console.log("Target | "+i+" | "+r+" is not a tracked click."),o)return!1}else{if(e.defaultPrevented||(e.preventDefault?e.preventDefault():e.returnValue=!1),"download"==i?"pageview"==s?o?console.log("Not Target | Download | Send | Pageview | "+r):__gaTracker("send","pageview",r,{hitCallback:T}):o?console.log("Not Target | Download | Send | Event | "+r):__gaTracker("send","event","download",{hitCallback:T}):"mailto"==i?o?console.log("Not Target | Mailto | Send | Event | Mailto | "+r):__gaTracker("send","event","mailto",r,{hitCallback:T}):"internal-as-outbound"==i?window.onbeforeunload=function(){o?console.log("Not Target | Internal-As-Outbound | Send | event | "+g+" | "+r+" | "+a.title):navigator.sendBeacon?__gaTracker("send","event",g,r,a.title,{transport:"beacon"}):__gaTracker("send","event",g,r,a.title,{hitCallback:T})}:"external"==i?window.onbeforeunload=function(){o?console.log("Not Target | External | Send | 'outbound-link' | "+r+" | "+a.title):navigator.sendBeacon?__gaTracker("send","event","outbound-link",r,a.title,{transport:"beacon"}):__gaTracker("send","event","outbound-link",r,a.title,{hitCallback:T})}:o&&console.log("Not Target | "+i+" | "+r+" is not a tracked click."),o)return!1;setTimeout(T,1e3)}}}}}var __gaTrackerWindow=window,__gaTrackerEventType="click";__gaTrackerWindow.addEventListener?__gaTrackerWindow.addEventListener("load",function(){document.body.addEventListener(__gaTrackerEventType,__gaTrackerClickEvent,!1)},!1):__gaTrackerWindow.attachEvent&&__gaTrackerWindow.attachEvent("onload",function(){document.body.attachEvent("on"+__gaTrackerEventType,__gaTrackerClickEvent)});
})();
</script>
<!-- End MonsterInsights JS Event Tracking -->
<?php
			$output = ob_get_contents();
			ob_end_clean();
			echo $output;
		}
	}
}