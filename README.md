# [MonsterInsights](https://www.monsterinsights.com) #
The best Google Analytics Integration for WordPress. Period.<br />
![Plugin Version](https://img.shields.io/wordpress/plugin/v/google-analytics-for-wordpress.svg?maxAge=2592000) 
![Total Downloads](https://img.shields.io/wordpress/plugin/dt/google-analytics-for-wordpress.svg?maxAge=2592000)
![WordPress Compatibility](https://img.shields.io/wordpress/v/google-analytics-for-wordpress.svg?maxAge=2592000)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.2-8892BF.svg?style=flat-square)](https://php.net/)
[![License](https://img.shields.io/badge/license-GPL--2.0%2B-red.svg)](https://github.com/awesomemotive/google-analytics-for-wordpress/blob/master/license.txt)
[![Build Status](https://scrutinizer-ci.com/g/awesomemotive/google-analytics-for-wordpress/badges/build.png?b=master)](https://scrutinizer-ci.com/g/awesomemotive/google-analytics-for-wordpress/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/awesomemotive/google-analytics-for-wordpress/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/awesomemotive/google-analytics-for-wordpress/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/awesomemotive/google-analytics-for-wordpress/?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/2944b6d77fa342f59764e79285da02bf)](https://www.codacy.com/app/chriscct7/google-analytics-for-wordpress?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=awesomemotive/google-analytics-for-wordpress&amp;utm_campaign=Badge_Grade)

## Contributions ##
Anyone is welcome to contribute to MonsterInsights. Please read the [guidelines for contributing](https://github.com/awesomemotive/google-analytics-for-wordpress/blob/master/CONTRIBUTING.md) to this repository.

There are various ways you can contribute:

1. Raise an [Issue](https://github.com/awesomemotive/google-analytics-for-wordpress/issues) on GitHub
2. Send us a Pull Request with your bug fixes and/or new features
3. Provide feedback and suggestions on [enhancements](https://github.com/awesomemotive/google-analytics-for-wordpress/issues?direction=desc&labels=Enhancement&page=1&sort=created&state=open)

## Bugs ##
If you find an issue, let us know [here](https://github.com/awesomemotive/google-analytics-for-wordpress/issues?state=open)!

## Support ##
This is a developer's portal for Google Analytics for WordPress by MonsterInsights and should not be used for support. 

For the lite version please use the wordpress.org [support forums](http://wordpress.org/support/plugin/google-analytics-for-wordpress).

For users of the pro version, please use the support form, located on the Support tab of the My Account page of our website (note: you must be logged in & have 
an active license to use this form).

Please report security issues to support@monsterinsights.com

## Backwards Compatibility Guidelines for Developers ##
Note all functionality on the admin side of the plugin, including any php/js/css functions, class names, files (or filenames), hooks or otherwise will not be garunteed backwards compatibility as a general rule. Our admin code is subject to change at any time without warning. As MonsterInsights is a frontend focused plugin, we're not too sure why you'd be building stuff with our backend code anyways. If you for some reason do need to use our backend code (anything located in admin or enqueued only in admin), please contact us with your usecase so we can adjust this policy and ensure your code will not break in the future.

For the frontend, we will not garuntee that the JS output will always be the same, nor can we, as we will adjust it over time to ensure continued Google Analytics compatibility and add features. However, we will garuntee the backwards compatibility of any hooks (filters or actions) found exclusively in the `lite/includes/frontend`, `pro/includes/frontend`, and `includes/frontend` directories to the best of our abilities. These hooks are documented as such. Any sort of future breakage will be announced well in advance of any changes, and we'll try to never break anyone's use of these hooks.

## Code Styling Documentation ##

MonsterInsights adheres to the WordPress core PHP standard with a couple deviations noted below. See https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/ for information on documenting using this standard.

### Deviations ###

- [Use `elseif`, not `else if`](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/#use-elseif-not-else-if)
	- In MonsterInsights, use `else if` as we do not permit use of the colon form of `if else`, rendering Core's reason for this rule null. Colon forms of `if else` are harder for text editors such as Sublime Text to parse the opening and closing of conditional logic, and thus are not permitted.
- Avoid regular expressions wherever possible
	- Regular expressions make it more difficult for new contributors to contribute. Thus whenever possible, regular expressions should be avoided.
- [Yoda Conditions](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/#yoda-conditions)
	- Code should never be written where a variable is being non-strictly compared to `true` or `false`. Use the appropriate way of writing the if statement. `if ( true == $var )` is equivolent to `if ( $var )` and the latter is much easier to read. However when strict comparison is required (`===`), one should use `if ( true === $var )`.

### Additions ###

- Required: Code should be formatted using tabs, set to size of 4 spaces
	- 4 spaces per tab is the universally accepted default tab size. Tabs are easier to read through in most editors. Additionally, they take up less file space than 4 spaces.
- Required: Filter and function names should use underscores, not dashes
	- For consistency, all filter and functions should use underscores not dashes or CamelCasing when seperating words. Additionally, all filter and (functions not in a class) names should be preceeded by `monsterinsights_`. The only exception to this is filters that need to be pro or lite only, in which case they should be proceeded by `monsterinsights_pro_` and `monsterinsights_lite_` respectively. Functions in a class should not be preceeded by `monsterinsights_`. 
- Required: Hooks tied to a function not within a class, will be tied to the function after the end of the function's declaration
	- Since MonsterInsights requires all functions have a function docbloc immediately preceeding the declaration of a function, to make it easier to locate hooks tied to a function, the `add_action` or `add_filter` call(s) to a function shall be placed immediately after the end of a function declaration. For functions in classes, this rule does not apply as it is impossible to do this.
- Recommended: Use pre-increments (`++i`) instead of post-increments (`i++`)
	- The former is more performant as it does not require a copy of the variable to be made. This rule is not strictly enforced, and where it detracts from the readability or simplicity of code, it should be ignored.
- Recommended: Where possible avoid stored data changes
	- This plugin has a lot of active installs. As such, whereever possible, we should try to avoid requiring an upgrade routine to convert stored data. Having a routine that adds data to make something more performant is fine as long as it either falls back to existing data, a smart default, or stops execution (in a controlled manner).

## Language Translations, Textdomains, and Internationalization ##

MonsterInsights is translation ready. Thanks to an extensive team of .org translators, much of the plugin is available translated into a wide variety of languages.

### What Textdomain to Use ##

In googleanalytics-premium.php and in the /pro/ folder only use `ga-premium`. Everywhere else use `google-analytics-for-wordpress`.

MonsterInsights Lite uses WordPress.org provided translation files. MonsterInsights Pro loads the Lite translation files for files shared with Lite, and also loads Pro-only translation files for the Pro-only files. These files are created automatically from Pro translations. The latest copy of these files are pulled down and deployed with each Pro release.

### How To Contribute Translations ###

Via the WordPress.org translation system located [here](https://www.wordpress.org/plugins/google-analytics-for-wordpress/translations/).

### I've found a non-translatable string that a user can see. What should I do? ###

Please open a [issue](https://github.com/awesomemotive/google-analytics-for-wordpress/issues) for it.

## Automation and External Libraries ##

A project goal of MonsterInsights, is to embrace automation whenever possible.

The MonsterInsights project taskrunner standard is Robo, and tasks for this project can be found in RoboFile.php (not available to public), and executed via:
robo {command}

The entire deployment process, the thing that makes a new MonsterInsights version and releases it, is completely automated (no human interaction required) via Robo.

The MonsterInsights project dependency management system is Composer. Please make sure you don't accidentily override our composer file in your PRs.

We also use Node/NPM to manage packages used by our plugin primarily for admin styling and functionality.

We generally will always use the latest stable version of any Composer or NPM dependency, pulled and packaged during our automated release process, when possible. Some reasons we might use an out of date package include (but are not limited to):

- Lack of PHP version support
- A bug in the current version of the dependency that affects our plugin's use of the dependency
- Lack of time to test the current version of the dependency before the release of our plugin
- A security issue, which may or may not be public
- A compatibility issue between a dependency and a different dependency
- A change in the dependency that affects MonsterInsights's ability to be conflict-free with other plugins
- and so forth

When possible, we will always override/prefix all CSS rules, JS functions, and PHP class and function names in the dependencies we include when possible. With ~2 million active installs, not doing so is not an option (too many badly coded plugins out there). This process is completely automated, and done on release, between the step when composer/npm brings down the latest dependency versions, and when the zip files are autogenerated.

When possible, we will also minify all JS/CSS files from dependencies into a single file that gets used, except when there's compatibility issues, or if there's a bug in the parser of the CSS/JS minifier we use.

## Warning About Package Managing MonsterInsights ##

We do not maintain, nor have any current plans, to allow our plugin to be installed via Composer, Packagist, or other similar systems.

We also do not recommend you via direct code or a management system (such as via a GitHub repo download Composer package), assume our plugin, on any branch, will be the current and/or stable branch of our plugin.

The only official and maintained source of our plugin is on WordPress.org for the Lite version, and from the My Account area (or via the automatic updates) for the Pro version.

## Development Checkout Procedure ##

`composer install`
`cd assets`
`npm update`

or if you're a company employee, use the Robo checkout command:

`robo devsetup`

## Constants ##

The following constants can be defined in a wp-config file to allow for sections of MonsterInsights to be turned on and off across an installation base (and also for unit testing)

### Both Pro & Lite ###

Plugin defined:
- `MONSTERINSIGHTS_VERSION`
	- The version of pro/lite installed
- `MONSTERINSIGHTS_PLUGIN_NAME`
	- The name of the plugin
- `MONSTERINSIGHTS_PLUGIN_SLUG`
	- The slug of this plugin
- `MONSTERINSIGHTS_PLUGIN_FILE`
	- The name of this file
- `MONSTERINSIGHTS_PLUGIN_DIR`
	- Path to the MI base folder
- `MONSTERINSIGHTS_PLUGIN_URL`
	- URL to the MI base folder

User defined:
- `MONSTERINSIGHTS_LICENSE_KEY`
	- MonsterInsights license key to use as the fallback (please use auth though not this, as you can do this on the network panel now on multisites)
- `MONSTERINSIGHTS_FORCE_ACTIVATION`
	- Override the WP version activation check. Use at your own risk.
- `MONSTERINSIGHTS_AIRPLANE_MODE`
	- For future use. Currently does nothing. Useful for local site testing.
- `MONSTERINSIGHTS_GA_UA`
	- Don't use oAuth or the wizard, but hardcode to use UA. Note, this will not allow backend reports to work. You can also use the filter `monsterinsights_get_ua`.
- `MONSTERINSIGHTS_MULTISITE_GA_UA`.
	- You can use this constant to force the same the same UA for all subsites of an MS install. Note, this will not allow backend reports to work.
- `MONSTERINSIGHTS_DEBUG_MODE`
	- Enables analytics.js and events tracking debug mode. Sets asset version to time(). In future, turns on logging to file for logging class. Available to turn on in backend via debug_mode setting.

### Lite Only ###
- `MONSTERINSIGHTS_LITE_VERSION`
	- The version of lite installed

### Pro Only ###
- `MONSTERINSIGHTS_PRO_VERSION`
	- The version of pro installed

### Legacy Constants ###

We declare these for code that relies on old constants. Please upgrade your code to use the new constants.

- `GA_YOAST_PREMIUM_VERSION`
	- Use `MONSTERINSIGHTS_PRO_VERSION` instead.
- `GAWP_VERSION`
	- Use `MONSTERINSIGHTS_VERSION` instead.
- `GAWP_FILE`
	- Use `MONSTERINSIGHTS_PLUGIN_FILE` instead.
- `GAWP_PATH`
	- Use `MONSTERINSIGHTS_PLUGIN_DIR` instead.
- `GAWP_URL`
	- Use `MONSTERINSIGHTS_PLUGIN_URL` instead.