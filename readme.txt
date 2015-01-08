=== Google Analytics by Yoast ===
Contributors: joostdevalk,PvW_NL
Donate link: https://yoast.com/donate/
Tags: analytics, google analytics, statistics, tracking, stats, google, yoast
Requires at least: 3.8
Tested up to: 4.1
Stable tag: 5.2.8

Track your WordPress site easily with the latest tracking codes and lots added data for search result pages and error pages.

== Description ==

The Google Analytics by Yoast plugin for WordPress allows you to track your blog easily and always stays up to date with the newest features in Google Analytics.

> <strong>Development on GitHub</strong><br>
> The development of Google Analytics by Yoast [takes place on GitHub](https://github.com/Yoast/google-analytics-for-wordpress). Bugs and pull requests are welcomed there. For support, you have two options: either [buy the premium version of Google Analytics by Yoast on Yoast.com](https://yoast.com/wordpress/plugins/google-analytics/), this will give you access to our support team, or refer to the forums.

Full list of features:

* Simple installation through integration with Google Analytics API: authenticate, select the site you want to track and you're done.
* This plugin uses the universal or the asynchronous Google Analytics tracking code, the fastest and most reliable tracking code Google Analytics offers.
* Option to enable demographics and interest reports.
* Outbound link & downloads tracking.
	* Configurable options to track outbound links either as pageviews or as events.
	* Option to track just downloads as pageviews or events in Google Analytics.
	* Option to track internal links with a particular format as outbound links, very useful for affiliate links that start with /out/, for instance.
* Possibility to ignore any user level and up, so all editors and higher for instance.
* Option to anonymize IP's for use in countries with stricter privacy regulations.
* Tracking of your search result pages and 404 pages.
* Full [debug mode](http://yoast.com/google-analytics-debug-mode/), including Firebug lite and ga_debug.js for debugging Google Analytics issues.

> <strong>Coming soon: dashboards!</strong><br>
> We're working hard on the next iteration of the plugin which will contain a Dashboard within your WordPress admin with the most important stats from Google Analytics.

Other interesting stuff:

* Check out the other [WordPress Plugins](https://yoast.com/wordpress/plugins/) by the same team.
* Want to increase traffic to your WordPress blog? Check out the [WordPress SEO](https://yoast.com/articles/wordpress-seo/) Guide!
* Check out the authors [WordPress Hosting](https://yoast.com/articles/wordpress-hosting/) experience. Good hosting is hard to come by, but it doesn't have to be expensive, Joost tells you why!

== Installation ==

This section describes how to install the plugin and get it working.

1. Delete any existing `gapp` or `google-analytics-for-wordpress` folder from the `/wp-content/plugins/` directory
1. Upload `google-analytics-for-wordpress` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the options panel under the 'Settings' menu and add your Analytics account number and set the settings you want.

== Changelog ==

= 5.2.8 =

Release Date: January 8th, 2015

* Bugfixes:
	* Makes sure reauthentication notice is only shown on the GA dashboard page.
	* Fixes a couple of situations in which the GA reauthentication notice was shown erroneously.

= 5.2.7 =

Release Date: December 18th, 2014

* Bugfixes:
	* Increase timeout limit for request to Google API to prevent quickly session time-outs.
	* Setting SSL verifier to FALSE, to prevent checking SSL-chain.
	* Checking for cURL version 7.19, it this version is used the plugin switches to http streams.

* Enhancements:
	* Using webproperties instead of account names in the select box for choosing the Google Analytics profile.

= 5.2.6 =

Release Date: December 16th, 2014

* Hotfix:
	* Fixing API library to prevent fatal error.

* Bugfixes:
	* Fixes the way of getting data from the Google API. cURL was used, but is changed into core wp_remote functionality to prevent errors when cURL isn't enabled.

= 5.2.5 =

Release Date: December 16th, 2014

* Enhancements:
	* When deactivating the plugin the options with API-details will be cleared.
	* Show notice when the plugin isn't able to fetch data from Google for more than 48 hours.

* Bugfixes:
	* Fixes the way of getting data from the Google API. cURL was used, but is changed into core wp_remote functionality to prevent errors when cURL isn't enabled.
	* Using autoloader for Google OAuth libraries instead of require them immediately to prevent conflicts with already loaded files.

= 5.2.4 =

Release Date: December 15th, 2014

* Enhancements:
	* Moved from OAuth1 to OAuth2 for Google Analytics API requests.
	* Open authentication and reauthentication for Google in a new window.
	* Force reloading of CSS and JS on version change of plugin.
	* Refactoring fetching profiles from Google Analytics

= 5.2.3 =

Release Date: December 11th, 2014

* Enhancements:
	* improved visual look and data representation for dashboard graphs, props [Andrey Savchenko](https://github.com/Rarst/)
	* when manual UA-code is selected, the profile selection gets disabled to clarify that the user is choosing an alternative to regular profile selection.

* Bugfixes:
	* Fixes a bug where GA profile was fetched with every admin request, resulting in a pile of unnecessary API requests.
	* Fixes a problem where GA started throttling API requests done by our plugin because there were too many. We now fetch the data only once a day from GA. For realtime data, users should go to Google Analytics.

= 5.2.2 =

Release Date: December 10th, 2014

* Bugfixes:
	* Fixes a bug where it was no longer possible to uncheck checkboxes in the settings.
	* Fixes a bug where jQuery Chosen component was not rendered correctly on a hidden tab.

= 5.2.1 =

Release Date: December 9th, 2014

* Enhancements:
	* Replaced jQuery Chosen library with latest version to enable optgroup search.
	* Shows a warning when configuring a manual UA-code that this will not work together with the dashboards functionality.

* Bugfixes:
	* Fixes a 'headers already sent' warning.
	* Fixes a bug where nothing was shown on the dashboards for setups with a manual UA-code. It now shows you a message that you need to authenticate with Google Analytics if you wish to make use of the dashboards feature.

= 5.2 =

Release Date: December 9th, 2014

* Features:
	* Added a brand new GA dashboard:
		* Added graphs showing the sessions and bounce rates.
		* Added reporting tables showing top pages, traffic sources and countries.

= 5.1.4 =

Release Date: December 2nd, 2014

* Enhancements:
	* Added GA property name to 'Analytics profile' selection in settings. Thanks [stephenyeargin](https://github.com/stephenyeargin) for reporting.
	* Removed firebug lite as it's rather obsolete with todays development consoles.

* i18n:
    * Several string fixes for easier translation.
    * Added en_GB and he_IL.

* Bugfixes:
	* Fixes a bug where site admins for sites on a multisite setup would receive a notice when visiting another site on the same setup which they didn't administrate, props [nerrad](https://github.com/nerrad).
	* Fixes a bug where saving the admin settings would cause non-string form fields to be ignored or rejected.

= 5.1.3 =

Release Date: November 26th, 2014

* Security fix: fixed a very minor cross site scripting issue with the manual UA code in de admin settings. The manual UA code is now sanitized before it is saved.

= 5.1.2 =

Release Date: November 19th, 2014

* Features:
	* Added a new box promoting our translation site for non en_US users.

* Bugfixes:
	* Fixes a bug where links on the extension page where not pointing to the correct url.

= 5.1.1 =

Release Date: November 12th, 2014

* Bugfixes:
	* Fixes a conflict with Fancybox.
	* Fixes a bug where links without the href attribute would cause the tracking code to be added wrongly.
	* Fixes a multisite bug in Premium which was caused by the absence of a network admin menu.
	* Fixes an issue with the version number.
	* Fixes a bug where translations might be loaded several times.

* Enhancements:
	* Shows more relevant banners.
	* Adds an image to the premium extension on the extensions page.
	* Always show the custom dimension tab.
	* Added translations for Finnish, Dutch and Russian.

= 5.1 =

Release Date: October 30th, 2014

* Bugfixes:
	* Fixes a possible JavaScript conflict within the tracking code.
	* Makes sure translations are loaded correctly.

* Enhancements:
	* Improved UX for help texts in form.
	* Added "Google Analytics by Yoast Premium" to the extensions page.
	* Makes sure the user returns to the original settings tab after saving.
	* Added a filter `wp_nav_menu` to enable tracking outbound links from the menu.
	* Added a filter `wp_list_bookmarks` to enable tracking for blogroll widgets.

= 5.0.7 =
Release Date: October 14th, 2014

* Bugfixes:
	* Fixes a bug where 404 tracking would not work when using ga.js tracking.

* Enhancements:
	* Refactored several bits of code.

= 5.0.6 =
Release Date: September 17th, 2014

* Bugfixes:
	* Fixed several notices.
	* Improved support for premium extensions to this plugin.
	* Fixed bug where on multi-site or subdirectory installs, settings wouldn't save.
	* Fix the tracking of downloads in ga.js tracking.
	* Fixed a bug where custom code would be output after the send pageview instead of before.
	* Fixed an empty delimiter warning.

* Enhancements:
	* Improved admin icon.
	* Added a filter `wpga_menu_on_top` which, when returned false, moves the analytics menu down.
	* Added filters `yoast-ga-push-array-ga-js` and `yoast-ga-push-array-universal` to filter the push arrays.

= 5.0.5 =
Release Date: September 4th, 2014

* Bugfixes:
	* Fixes error in link parsing that would cause all sorts of display errors.

= 5.0.4 =
Release Date: September 4th, 2014

* Bugfixes:
	* Fix small error in GA setup error link.
	* Fix bug that would remove attributes from links.
	* Added Try/Catch around connect to Google Analytics to prevent uncaught exceptions.
	* Move require of function file to prevent error with already defined functions.
	* Fix bug that sometimes prevented saving user roles to be ignored.
	* Fix several notices.
* Enhancements:
	* Add links to Settings page and Knowledge Base on plugins page.
	* No longer store result from Google API in the main option, saves memory on frontend.

= 5.0.3 =
Skipped due to error during release.

= 5.0.2 =
Release Date: September 4th, 2014

* Bugfixes:
	* Fixed bug with outbound link tracking not properly escaping attributes.
	* Fixed bug that broke tracking with ga.js.

= 5.0.1 =
Release Date: September 4th, 2014

* Bugfixes:
	* Fixed string where array expected warning.

= 5.0.0 =
Release Date: September 4th, 2014

Complete rewrite of the Google Analytics plugin.

* Enhancements:
	* Universal tracking added
	* Better link tracking
	* New Universal demographics feature
	* New menu items in the WordPress admin menu

= 4.3.5 =

* Enhancement:
	* Update banners in admin.

= 4.3.4 =

* Bugfix: 
	* Fixed error in a database query as reported by [mikeotgaar](http://wordpress.org/support/topic/wordpress-database-error-table-1) and applied some best practices for the database queries - props [Jrf](http://profiles.wordpress.org/jrf).
	* Fixed error in a database query.
	* Made check for customcode option more robust - props [Rarst](https://github.com/Rarst).

* i18n
	* Updated gawp.pot file
	* Added de_DE, el_GR, es_ES, fi, fr_FR, hu_HU & nl_NL

= 4.3.3 =

* Fix a possible fatal error in tracking.

= 4.3.2 =

* Bugfix: Google Analytics crappy API output is different when you have a single GA account versus multiple. Annoying, but fixed now.

= 4.3.1 =

* Removes a left over JS alert.

= 4.3 =

* Major refactor of plugin code, to only load necessary code on front and backend.
* Made entire plugin i18n ready.
* Fixed Google Authentication process (thanks to [Jan Willem Eshuis](http://www.janwillemeshuis.nl/)).

= 4.2.8 =

* Fix a small bug in tracking that could potentially slow down admin.

= 4.2.7 =

* Fix to prevent far too agressive oAuth implementation from breaking other plugins.

= 4.2.6 =

* Fix to prevent far too agressive oAuth implementation from breaking other plugins.

= 4.2.5 =

* Fixed a couple notices.
* Added tracking to better understand configurations to test the plugin with.

= 4.2.4 =

* Fixed bug introduced with 4.2.3 that wouldn't allow saving settings.
* Now only flushing enabled W3TC caches.

= 4.2.3 =

* Removed Dashboard widget.
* Improvements to comment form tracking.

= 4.2.2 =

* Fix for OAuth issues, caused by other plugins that don't check for the existence of a class. Namespaced the whole thing to prevent it.

= 4.2.1 =

* Minor bugfix.

= 4.2 =

* Google Authentication now happens using OAuth. The requests have become signed as an extra security measure and tokens have become more stable, as opposed to the prior tokens used with AuthSub.
* Added support for cross-domain tracking.
* Fixed various small bugs.

= 4.1.3 =

* Security fix: badly crafted comments could lead to insertion of "weird" links into comments. They'd have to pass your moderation, but still... Immediate update advised. Props to David Whitehouse and James Slater for finding it.

= 4.1.2 =

* Fixed bug with custom SE tracking introduced in 4.1.1.

= 4.1.1 =

* Made plugin admin work with jQuery 1.6 and jQuery 1.4.
* Added contextual help.
* Improved cache flushing when using W3TC.
* Fixed various minor other notices.
* First stab at getting ready for full i18n compatibility.

= 4.1 =

* Added:
	* Google Site Speed tracking, turned it on by default.

* Fixed:
	* Custom code now properly removes slashes.

= 4.0.12 =

* Fixed:
	* Tons of notices in backend and front end when no settings were saved yet.
	* Set proper defaults for all variables.
	* Notice for unset categories array on custom post types.
	* Notice for unset variable.
	* Error when user is not logged in in certain corner cases.
	* Bug where $options was used but never loaded for blogroll links.

= 4.0.11 =

* Bugs fixed:
	* You can now disable comment form tracking properly.
	* Removed charset property from script tags to allow validation with HTML5 doctype.

= 4.0.10 =

* Known issues:
	* Authentication with Google gives errors in quite a few cases. Please use the manual option to add your UA code until we find a way to reliably fix it.

* Added functionality:
	* Option to set `_setAllowHash` to false, for proper subdomain tracking and some other uses.
	* Option to add a custom string of code to the tracking, before the push string is sent out.

* Documentation fixes:
	* Fixed link for `_setDomainName()`.
	* Fixed some grammatical errors (keep emailing me about those, please!)
	* Removed second comment in source output.
	* Fixed version number output in source.

= 4.0.9 =

* Code enhancements:
	* Updated Shopp integration to also work with the upcoming Shopp 1.1 and higher.
	* Switched from [split](http://php.net/split) to [explode](http://php.net/explode), as split has been deprecated in PHP 5.3+.
* New features:
	* A new debug mode has been added, using the new [ga_debug.js](http://analytics.blogspot.com/2010/08/new-tools-to-debug-your-tracking-code.html). Along with this you can now enable Firebug Lite, so you can easily see the output from the debug script in each browser. Admins only, of course.
	* A list of modules has been added to the right sidebar, to allow easy navigation within the settings page.

= 4.0.8 =
* Reverted double quote change from 4.0.7 because it was causing bigger issues.

= 4.0.7 =
* Bugs fixed in this release:
	* Changed access level from "edit_users" to "manage_options" so super-admins in an multi site environment would be able to access.
	* Not a real bug but a fix nonetheless: UA ID is now trimmed on output, so spaces accidently entered in the settings screen won't prevent tracking.
	* Changed double quotes in link tracking output to single quotes to resolve incompatibilities with several plugins.

= 4.0.6 =
* Bugs fixed in this release:
	* Sanitizing relative URL's could go wrong on some blogs installed in subdirectories.
	* Comment form tracking only worked for posts, not for pages, and would sometimes cause other issues. Patch by [Milan Dinić](http://blog.milandinic.com/).
	* Settings page: now correctly hiding internal links to track as outbound block when outbound link tracking is disabled.
* Code sanitization:
	* Hardcoded the [scope for custom variables](http://code.google.com/apis/analytics/docs/gaJS/gaJSApiBasicConfiguration.html#_gat.GA_Tracker_._setCustomVar) to prevent that from possibly going wrong.
	* Improved method of determining whether current user should be tracked or not.
	* Added plugin version number in script branding comment, and moved branding comment to within CDATA section to assist in debugging, even when people use W3TC or another form of code minification.
* Documentation fixes:
	* Updated custom variable order in settings panel to reflect order of tracking. You can now determine their index key by counting down, first checked box is index 1, second 2, etc.
	* Ignored users dropdown now correctly reflects that ignoring subcribers and up means ignoring ALL logged in users.

= 4.0.5 =
* New features in this release:
	* Added a simple check to see if the UA code, when entered manually, matches a basic pattern of UA-1234567-12.
	* Added integration with [W3 Total Cache](http://wordpress.org/extend/plugins/w3-total-cache/) and [WP Super Cache](http://wordpress.org/extend/plugins/wp-super-cache/). The page cache is now automatically cleared after updating settings. Caching is recommended for all WordPress users, as faster page loads improve tracking reliability and W3 Total Cache is our recommended caching plugin.
	* Added the option to enter a manual location for ga.js, allowing you to host it locally should you wish to.
* Bugs fixed:
	* Fixed implementation of _anonymizeIp, it now correctly anonymizes IP's by setting [_gat._anonymizeIp](http://code.google.com/apis/analytics/docs/gaJS/gaJSApi_gat.html#_gat._anonymizeIp).
	* Increased request timeout time for Google Analytics authentication from 10 to 20 seconds, for slow hosts (if this fixes it for you, your hosting is probably not really good, consider another WordPress host).
* Documentation fixes:
	* Added a note about profiles with the same UA code to the Analytics Profile selection.
	* The profile selection dropdown now shows the UA code after the profile name too.
	* Updated the [screenshots](http://wordpress.org/extend/plugins/google-analytics-for-wordpress/screenshots/) and the [FAQ](http://wordpress.org/extend/plugins/google-analytics-for-wordpress/faq/) for this plugin.

= 4.0.4 =
* Fix for stupid boolean mistake in 4.0.3.

= 4.0.3 =
* New features in this release:
	* Added versioning to the options array, to allow for easy addition of options.
	* Added an option to enable comment form tracking (as this loads jQuery), defaults to on.
* Bugs fixed:
	* If you upgraded from before 4.0 to 4.0.2 you might have an empty value for ignore_userlevel in some edge cases, this is now fixed.
	* Custom search engines were loaded after trackPageview, this was wrong as shown [by these docs](http://code.google.com/intl/sr/apis/analytics/docs/tracking/asyncMigrationExamples.html#SearchEngines), now fixed.

= 4.0.2 =
* Old settings from versions below 4.0 are now properly sanitized and inherited (slaps forehead about simplicity of fix).
* New features in this release:
	* Link sanitization added: relative links will be rewritten to absolute, so /out/ becomes http://example.com/out/ and is tracked properly.
	* Added a feature to track and label internal links as outbound clicks, for instance /out/ links.
	* Added tracking for mailto: links.
	* Added a filter for text-widgets, all links in those widgets are now tagged too.
	* Added support for [_anonymizeIp](http://code.google.com/apis/analytics/docs/gaJS/gaJSApi_gat.html#_gat._anonymizeIp).
* Bugs fixed in this release:
	* Made sure all content filters don't run when the current user is ignored because of his user level.

= 4.0.1 =
* Fix for when you have only 1 site in a specific Analytics profile.

= 4.0 =
* NOTE WHEN UPGRADING: you'll have to reconfigure the plugin so it can fully support all the new features!
* Complete rewrite of the codebase
* Switched to the new asynchronous event tracking model
* Switched link tracking to an event tracking model, because of this change removed 5 settings from the settings panel that were no longer needed
* Implemented custom variable tracking to track:
	* On the session level: whether the user is logged in or not.
	* On the page level: the current posts's author, category, tags, year of publication and post type.
* Added Google Analytics API integration, so you can easily select a site to track.
* E-Commerce integration, tracking transactions, support for WP E-Commerce and Shopp.
* Much much more: check out [the release post](http://yoast.com/google-analytics-wordpress-v4/).

= 3.2.3 =
* Added 0 result search tracking inspired by [Justin Cutroni's post](http://www.epikone.com/blog/2009/09/08/tracking-ero-result-searches-in-google-analytics/).

= 3.2.2 =
* Fix to the hashtag redirect so it actually works in all cases.

= 3.2.1 =
* Slight change to RSS URL tagging, now setting campaign to post name, and behaving better when not using rewritten URL's.
* Two patches by [Lee Willis](http://www.leewillis.co.uk):
	* Made some changes so the entire plugin works fine with .co.uk, .co.za etc domains.
	* Made sure internal blogroll links aren't tagged as external clicks.

= 3.2 =
* Added option to add tracking to add tracking to login / register pages, so you can track new signups (under Advanced settings).
* Added beta option to track Google image search as a search engine, needs more testing to make sure it works.
* Fixed a bug in the extra search engine tracking implementation.
* Removed redundant "More Info" section from readme.txt.

= 3.1.1 =
* Stupid typo that caused warnings.

= 3.1 =
* Added 404 tracking as described [here](http://www.google.com/support/googleanalytics/bin/answer.py?hl=en&answer=86927).
* Optimized the tracking script, if extra search engine tracking is disabled it'll be a lot smaller now.
* Various code optimizations to prevent PHP notices and removal of redundant code.

= 3.0.1 =
* Removed no longer needed code to add config page that caused PHP warnings.

= 3.0 =
* Major backend overhaul, using new Yoast backend class.
* Added ability to automatically redirect non hashtagged campaign URLs to hashtagged campaign URL's when setAllowAnchor is set to true (if you don't get it, forget about it, you might need it but don't need to worry)

= 2.9.5 =
* Fixed a bug with the included RSS, which came up when multiple Yoast plugins were installed.

= 2.9.4 =
* Changed to the new Changelog design.
* Removed pre 2.6 compatibility code, plugin now requires WP 2.6 or higher.
* Small changes to the admin screen.

= 2.9.3 =
* Added a new option for RSS link tagging, which allows you to tag your RSS feed links with RSS campaign variables. When you've set campaign variables to use # instead of ?, this will adhere to that setting too. Thanks to [Timan Rebel](http://rebelic.nl/) for the idea and code.

= 2.9.2: =
* Added a check to see whether the wp_footer() call is in footer.php.
* Added a message to the source when tracking code is left out because user is logged in as admin.
* Added option to segment logged in users.
* Added try - catch to script lines like in new Google Analytics scripts.
* Fixed bug in warning when no UA code is entered.
* Prevent link tracking when admin is logged in and admin tracking is disabled.
* Now prevents parsing of non http and https link.

= 2.9 =
* Re arranged admin panel to have "standard" and "advanced" settings.
* Added domain tracking.
* Added fix for double onclick parameter, as suggested [here](http://wordpress.org/support/topic/241757).

= 2.8 =
* Added the option to add setAllowAnchor to the tracking code, allowing you to track campaigns with # instead of ?.

= 2.7 =
* Added option to select either header of footer position.
* Added new AdSense integration options.
* Removed now unneeded adsense tracking script.

= 2.6.6=
* Fixed settings link.

= 2.6.5 =
* added Ozh admin menu icon and settings link.

= 2.6.4 =
* Fixes for 2.7.

= 2.6.3 =
* Fixed bug that didn't allow saving of outbound clicks from comments string.

= 2.6 =
* Fixed incompatibility with WP 2.6.

= 2.5.4 =
* Fixed an issue with pluginpath being used globally.
* Changed links to [new domain](http://yoast.com/).

= 2.2 =
* Switched to the new tracking code.

= 2.1 =
* Made sure tracking was disabled on preview pages.

= 2.0 =
* Added AdSense tracking.

= 1.5 =
* Added option to enable admin tracking, off by default.

== Frequently Asked Questions ==

For all frequently asked questions, and their answers, check the [Yoast Knowledge base](http://kb.yoast.com/category/43-google-analytics-for-wordpress).

== Screenshots ==

1. Screenshot of the general settings panel for this plugin.
2. Screenshot of the universal settings panel.
3. Screenshot of the advanced settings panel.
4. Screenshot of the account selection drop down.
