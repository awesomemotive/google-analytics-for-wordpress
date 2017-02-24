# [MonsterInsights](https://www.monsterinsights.com) #
The best Google Analytics Integration for WordPress. Period.<br />
![Plugin Version](https://img.shields.io/wordpress/plugin/v/google-analytics-for-wordpress.svg?maxAge=2592000) 
![Total Downloads](https://img.shields.io/wordpress/plugin/dt/google-analytics-for-wordpress.svg?maxAge=2592000)
![WordPress Compatibility](https://img.shields.io/wordpress/v/google-analytics-for-wordpress.svg?maxAge=2592000)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.2-8892BF.svg?style=flat-square)](https://php.net/)
[![Build Status](https://img.shields.io/travis/awesomemotive/google-analytics-for-wordpress/master.svg?maxAge=2592000)](https://travis-ci.org/awesomemotive/google-analytics-for-wordpress) 
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/awesomemotive/google-analytics-for-wordpress.svg)](https://scrutinizer-ci.com/gawesomemotive/google-analytics-for-wordpress/?branch=master) 
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/awesomemotive/google-analytics-for-wordpress.svg?maxAge=2592000)](https://scrutinizer-ci.com/g/awesomemotive/google-analytics-for-wordpress/?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d7e64d1841794c249ea74f1e0e81a0e2)](https://www.codacy.com/app/chriscct7/google-analytics-for-wordpress?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=awesomemotive/google-analytics-for-wordpress&amp;utm_campaign=Badge_Grade)
[![License](https://img.shields.io/badge/license-GPL--2.0%2B-red.svg)](https://github.com/awesomemotive/google-analytics-for-wordpress/blob/master/license.txt)

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

- Required: Functions should be unit testable, documented and unit-tested
	- When submitting new functions, ensure the functions are easily testable by PHPUnit where applicable. If altering existing functions, adjust and add/remove unit tests and documentation covering the function where applicable. If you're adding/editing a function in a class, make sure to add `@access {private|public|protected}`
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

## Documentation ##

MonsterInsights has adopted the WordPress core PHP documentation standard. See https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/ for information on documenting using this standard.

MonsterInsights is compatible with both PHPDocumentor2 and PHPDox.

Soon, you'll also be able to build phpDocumenter and PHPDox documentation for MonsterInsights by running `robo documentation`.

In the meantime you can run `php phpDocumentor.phar run` and `php phpdox.phar` respectively. 

Be aware in order to make the graphs in PHPDocumentor2 you must have Graphviz installed.

## Automation ##

A project goal of MonsterInsights, is to embrace automation whenever possible.

The MonsterInsights project taskrunner standard is Robo, and tasks for this project can be found in RoboFile.php (not available to public), and executed via:
robo {command}

Some goals of the project moving forward is to allow for documentation of MonsterInsights, as well as releases of MonsterInsights with as minimal effort required.

The MonsterInsights project dependency management system is Composer. Please make sure you don't accidentily override our composer file in your PRs.

## PHP Unit Testing ##

A goal of this project is to make all functionality unit tested. 

The project goal is to become code covered. Over time, we will release our internal unit tests to the public repo.

This project uses Travis-CI Free & Pro for continous integration on push and pull requests. 

Code coverage analysis, as well as general project code quality is available from Codacy.

Perhaps you'd like to run our unit tests locally? 

Awesome, we offer 3 options for doing that:

- PHPCI
- JoliCI (note: there's a bug that prevents this from working in Windows envs atm caused by a TLS verified call in dependency docker-php caused by a bug in openssl (which isn't changeable on WAMP). Ref: https://github.com/stage1/docker-php/issues/140 )
- Standard PHPUnit

JoliCI additionally allows you to parse and run the Travis-CI suite locally using Docker

## Development Checkout Procedure ##

`php composer.phar install`

or soon, the recommended way will be 

`php robo.phar setup`

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
	- MonsterInsights license key to use
- `MONSTERINSIGHTS_FORCE_ACTIVATION`
	- Override the WP version activation check. Use at your own risk.
- `MONSTERINSIGHTS_AIRPLANE_MODE`
	- For future use. Currently does nothing. Useful for local site testing.
- `MONSTERINSIGHTS_GA_UA`
	- Don't use oAuth or the wizard, but hardcode to use UA. Note, this will not allow backend reports to work. You can also use the filter `monsterinsights_ga_ua`.
- `MONSTERINSIGHTS_MULTISITE_GA_UA`. See UA priority rules below.
	- You can use this constant to force the same the same UA for all subsites of an MS install. Note, this will not allow backend reports to work. See UA priority rules below.
- `MONSTERINSIGHTS_DEBUG_MODE`
	- Enables analytics.js and events tracking debug mode. Sets asset version to time(). In future, turns on logging to file for logging class. Available to turn on in backend via debug_mode setting.
- `MONSTERINSIGHTS_SHAREASALE_ID`
	- If you want to bundle MonsterInsights with a theme, you can use this constant to ensure you get affiliate credit for any conversions.

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


## Google Analytics UA Priority Rules
These rules dictate which Google Analytics UA code is used. 
The order of priority is as follows (top of list has most priority):

- `monsterinsights_get_ua` filter
- MonsterInsights per site settings for manual UA or oAuth retrieved UA
	- Note, these settings are hidden when MONSTERINSIGHTS_GA_UA is defined
- `MONSTERINSIGHTS_GA_UA` constant
- `MONSTERINSIGHTS_MS_GA_UA` constant (Multisite use only)


As a result of this order of priority, on MS installs you can use the `MONSTERINSIGHTS_MS_GA_UA` constant to 
set the default UA for all of the subsites of an MS install, and then override that on a subsite basis using either
the UI in the settings panel, the `MONSTERINSIGHTS_GA_UA` constant or the `monsterinsights_get_ua` filter. 

Let's say you run a really large MS install, like for a university, and all of your sites are {sitename}.mysite.com or 
mysite.com/{example}/. In this case, you can quickly deploy MI for your entire network by network activating MonsterInsights
and using the `MONSTERINSIGHTS_MS_GA_UA` constant. This is a new feature, available starting with MonsterInsights 6.0.0.

Also, since you can now filter each option value retrieved (via the filter in the Option's class's get_option() function ),
you can even set the values used for each setting. If you want, you can disable access to site users by setting the menu capability for MonsterInsights
to a custom or non-existant capability, and as a result run MonsterInsights without any user even needing to see the WordPress backend for
MonsterInsights.