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

        // JavaScript

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
                    '**/*.php'
                ]
            }
        },

        makepot: {
            theme: {
                options: {
                    domainPath: '/lib/languages',
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

    grunt.registerTask('build:i18n', [
        'addtextdomain',
        'makepot'
    ]);

    grunt.registerTask('default', [
        'build'
    ]);

    grunt.registerTask('fucking', [
        'grunt-jsbeautifier'
    ]);

};