/* global require */
module.exports = function(grunt) {
	'use strict';

	require('load-grunt-tasks')(grunt, {
		pattern: ['grunt-*', 'assemble-less']
	});

	require('time-grunt')(grunt);

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// Watch source files
		watch: {
			gruntfile: {
				files: ['Gruntfile.js'],
				tasks: ['jshint:grunt', 'jsvalidate', 'jscs']
			},
			php: {
				files: ['**/*.php', '*/*.php', '!node_modules/**'],
				tasks: ['phplint', 'phpcs']
			},
			js: {
				files: ['assets/js/*.js'],
				tasks: ['jshint', 'jsvalidate', 'jscs', 'uglify']
			},
			css: {
				files: ['assets/css/*css'],
				tasks: ['build:css']
			}
		},        // JavaScript

		// Lint JS code practices
		jshint: {
			grunt: {
				options: {
					jshintrc: '.gruntjshintrc'
				},
				src: ['Gruntfile.js']
			}
		},

		// Lint JS for code standards
		jscs: {
			options: {
				config: '.jscsrc'
			},
			all: {
				files: {
					src: [
						'Gruntfile.js',
						'.gruntjshintrc',
						'.jshintrc',
						'package.json'
					]
				}
			}
		},

		// Lint JSON files for syntax errors
		jsonlint: {
			all: {
				src: [
					'.gruntjshintrc',
					'.jshintrc',
					'package.json'
				]
			}
		},

		// Lint .js files for syntax errors
		jsvalidate: {
			all: {
				options: {
					verbose: true
				},
				files: {
					src: [
						'Gruntfile.js'
					]
				}
			}
		},

		uglify: {
			'google-analytics-for-wordpess': {
				options: {
					preserveComments: 'some',
					report: 'gzip'
				},
				files: [
					{
						expand: true,
						cwd: 'assets/js',
						src: ['*.js', '!*.min.js'],
						dest: 'assets/js',
						ext: '.min.js',
						extDot: 'first',
						isFile: true
					}
				]
			}
		},

		// CSS
		autoprefixer: {
			options: {
				browsers: [
					'last 1 versions',
					'Explorer >= 8'
				]
			},
			all: {
				src: [
					'assets/css/*.css', '!assets/css/*.min.css'
				],
				options: {
					// diff: 'tmp/autoprefixer.patch'
				}
			}
		},

		csscomb: {
			css: {
				expand: true,
				src: ['assets/css/*.css', '!assets/css/*.min.css']
			}
		},

		cssbeautifier: {
			files: ['assets/css/*.css', '!assets/css/*.min.css'],
			options: {
				indent: '\t',
				openbrace: 'end-of-line',
				autosemicolon: true
			}
		},

		cssmin: {
			minify: {
				expand: true,
				cwd: 'assets/css/',
				src: ['*.css', '!*.min.css'],
				dest: 'assets/css/',
				ext: '.min.css'
			}
		},

		// I18n
		addtextdomain: {
			options: {
				textdomain: 'google-analytics-for-wordpress'
			},
			php: {
				files: {
					src: [
						'**/*.php'
					]
				}
			}
		},

		// Lint .php files for syntax errors
		phplint: {
			all: ['**/*.php', '!node_modules/**']
		},

		// Lint .php files for code standards
		phpcs: {
			all: {
				//adjust these to the folder you do and don't want to be treated
				dir: ['**/*.php', '!admin/license-manager/**', '!node_modules/**']
			},
			options: {
				standard: 'codesniffer.xml',
				reportFile: 'phpcs.txt',
				ignoreExitCode: true
			}
		},

// Optimize images to save bytes
		imagemin: {
			images: {
				files: [{
					expand: true,
					// this would require the addition of a assets folder from which the images are
					// processed and put inside the images folder
					cwd: 'assets/img/',
					src: ['*.*'],
					dest: 'assets/img/'
				}]
			}
		},

		checktextdomain: {
			options: {
				text_domain: 'google-analytics-for-wordpress',
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d',
					'esc_attr__:1,2d',
					'esc_html__:1,2d',
					'esc_attr_e:1,2d',
					'esc_html_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'esc_html_x:1,2c,3d'
				]
			},
			files: {
				expand: true,
				src: [
					'**/*.php', '!node_modules/**', '!admin/license-manager/**'
				]
			}
		},

		makepot: {
			theme: {
				options: {
					domainPath: '/languages',
					processPot: function(pot) {
						pot.headers['report-msgid-bugs-to'] = 'http://wordpress.org/support/plugin/google-analytics-for-wordpress\n';
						pot.headers['plural-forms'] = 'nplurals=2; plural=n != 1;';
						pot.headers['last-translator'] = 'Remkus de Vries <translations@yoast.com>\n';
						pot.headers['language-team'] = 'Yoast Translate <translations@yoast.com>\n';
						pot.headers['x-generator'] = 'grunt-wp-i18n 0.4.4';
						pot.headers['x-poedit-basepath'] = '.';
						pot.headers['x-poedit-language'] = 'English';
						pot.headers['x-poedit-country'] = 'UNITED STATES';
						pot.headers['x-poedit-sourcecharset'] = 'utf-8';
						pot.headers['x-poedit-keywordslist'] = '__;_e;_x:1,2c;_ex:1,2c;_n:1,2; _nx:1,2,4c;_n_noop:1,2;_nx_noop:1,2,3c;esc_attr__; esc_html__;esc_attr_e; esc_html_e;esc_attr_x:1,2c; esc_html_x:1,2c;';
						pot.headers['x-poedit-bookmarks'] = '';
						pot.headers['x-poedit-searchpath-0'] = '.';
						pot.headers['x-textdomain-support'] = 'yes';
						return pot;
					},
					type: 'wp-plugin'
				}
			}
		}

	});

	grunt.registerTask('check', [
		'jshint',
		'jsonlint',
		'jsvalidate',
		'checktextdomain'
	]);

	grunt.registerTask('build', [
		'build:css',
		'build:js',
		'build:i18n',
		'imagemin'
	]);

	grunt.registerTask('build:css', [
		'autoprefixer',
		'csscomb',
		'cssbeautifier',
		'cssmin'
	]);

	grunt.registerTask('build:js', [
		'uglify'
	]);

	grunt.registerTask('build:i18n', [
		'addtextdomain',
		'makepot'
	]);

	grunt.registerTask('default', [
		'check'
	]);
};
