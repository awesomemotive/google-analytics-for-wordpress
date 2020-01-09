import api from '../api';
import Vue from 'vue';
import { __, sprintf } from "@wordpress/i18n";

const getReportData = ( context, report ) => {
	return new Promise( ( resolve ) => {
		if ( ! Vue.prototype.$mi.authed ) {
			resolve( false );
			context.commit( 'ENABLE_BLUR' );
			context.commit( 'ENABLE_NOAUTH' );
			return false;
		}
		// Check if data is already loaded.
		if ( context.state[report] && context.state[report].reportcurrentrange ) {
			if ( context.state[report].reportcurrentrange.startDate === context.state.date.start && context.state[report].reportcurrentrange.endDate === context.state.date.end ) {
				resolve( false );
				context.commit( 'DISABLE_BLUR' );
				return false; // No need to load data.
			}
		}

		Vue.prototype.$mi_loading_toast();
		context.commit( 'ENABLE_BLUR' );
		api.fetchReportData( context, report, context.state.date.start, context.state.date.end ).then( function( response ) {
			if ( 'license_level' === response.data.message ) {
				maybeCloseSwal( context );
				resolve( false );
				return;
			}
			if ( response.success ) {
				maybeCloseSwal( context );
				context.commit( 'DISABLE_BLUR' );
				context.commit( 'UPDATE_REPORT_DATA', {
					report: report,
					data: response.data,
				} );
				resolve( true );
			} else {
				if ( 'invalid_grant' === response.data.message ) {
					maybeCloseSwal( context );
					resolve( false );
					context.commit( 'ENABLE_REAUTH' );
					return;
				}
				if ( response.data.footer && 'install_addon' === response.data.footer ) {
					addonInstalled( context, report ).then( function( installed ) {
						if ( context.rootState.$_widget ) {
							context.commit( 'DISABLE_BLUR' );
							context.commit( '$_widget/UPDATE_LOADED', true, { root: true } );
						}
						// We need to call this so the error is correctly set in the dashboard widget.
						let verb = installed ? 'activate' : 'install';
						Vue.prototype.$mi_error_toast( {
							title: false,
							html: sprintf( response.data.message, verb ),
							footer: '<a href="' + Vue.prototype.$mi.addons_url + '">' + __( 'Visit addons page', process.env.VUE_APP_TEXTDOMAIN ) + '</a>',
							report,
						} );
						// If we can install the addon on the fly, give that option right away.
						Vue.prototype.$swal( {
							type: 'error',
							customContainerClass: 'monsterinsights-swal',
							title: __( 'Report Unavailable', process.env.VUE_APP_TEXTDOMAIN ),
							html: sprintf( response.data.message, verb ),
							allowOutsideClick: true,
							allowEscapeKey: true,
							allowEnterKey: false,
							showCancelButton: true,
							confirmButtonText: sprintf( __( '%s Addon', process.env.VUE_APP_TEXTDOMAIN ), verb.charAt( 0 ).toUpperCase() + verb.slice( 1 ) ),
							cancelButtonText: __( 'Dismiss', process.env.VUE_APP_TEXTDOMAIN ),
						} ).then( function( result ) {
							if ( result.value ) {
								if ( installed ) {
									activateAddon( context, context.rootState.$_addons.addons[report] );
								} else {
									installAddon( context, report );
								}
							}
						});
					} );
				} else {
					resolve( false );
					Vue.prototype.$mi_error_toast( {
						title: false,
						html: response.data.message,
						footer: response.data.footer,
						report,
					} );
				}
			}
		} );
	} );
};

function addonInstalled( context, slug ) {
	// Load available Addons.
	return new Promise( function( resolve ) {
		context.dispatch( '$_addons/getAddons', '', { root: true } ).then( function() {
			// If installed, activate.
			if ( context.rootState.$_addons.addons[slug] && context.rootState.$_addons.addons[slug].installed ) {
				resolve( true );
			} else {
				resolve( false );
			}
		} ).catch( function() {
			resolve( false );
			errorActivatingAddon();
		});
	});
}

function installAddon( context, slug ) {
	Vue.prototype.$swal({
		type: 'info',
		customContainerClass: 'monsterinsights-swal',
		title: __( 'Installing Addon', process.env.VUE_APP_TEXTDOMAIN ),
		html: __( 'Please wait', process.env.VUE_APP_TEXTDOMAIN ),
		allowOutsideClick: false,
		allowEscapeKey: false,
		allowEnterKey: false,
		onOpen: function() {
			Vue.prototype.$swal.showLoading();
			// Attempt to install and then activate.
			context.dispatch( '$_addons/installAddon', context.rootState.$_addons.addons[slug], {
				root: true,
			} ).then( function() {
				activateAddon( context, context.rootState.$_addons.addons[slug] );
			} ).catch( function() {
				errorActivatingAddon();
			} );
		},
	});
}

function activateAddon( context, addon ) {
	Vue.prototype.$swal( {
		type: 'info',
		customContainerClass: 'monsterinsights-swal',
		title: __( 'Activating Addon', process.env.VUE_APP_TEXTDOMAIN ),
		html: __( 'Please wait', process.env.VUE_APP_TEXTDOMAIN ),
		allowOutsideClick: false,
		allowEscapeKey: false,
		allowEnterKey: false,
		onOpen: function() {
			Vue.prototype.$swal.showLoading();
		},
	});
	context.dispatch( '$_addons/activateAddon', addon, {
		root: true,
	} ).then( function() {
		Vue.prototype.$swal( {
			type: 'info',
			customContainerClass: 'monsterinsights-swal',
			title: __( 'Addon Activated', process.env.VUE_APP_TEXTDOMAIN ),
			html: __( 'Loading report data', process.env.VUE_APP_TEXTDOMAIN ),
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			onOpen: function() {
				Vue.prototype.$swal.showLoading();
				setTimeout( function() {
					window.location.reload();
				}, 1000 );
			},
		} );
	} ).catch( function( error ) {
		errorActivatingAddon( error );
	});
}

function errorActivatingAddon( error ) {
	let content = __( 'Please activate manually', process.env.VUE_APP_TEXTDOMAIN );
	if ( error.response ) {
		content = sprintf( __( 'Error: %s, %s', process.env.VUE_APP_TEXTDOMAIN ), error.response.status, error.response.statusText );
	}

	Vue.prototype.$swal( {
		type: 'error',
		customContainerClass: 'monsterinsights-swal',
		title: __( 'Error Activating Addon', process.env.VUE_APP_TEXTDOMAIN ),
		html: content,
		allowOutsideClick: false,
		allowEscapeKey: false,
		allowEnterKey: false,
		showCancelButton: true,
		confirmButtonText: __( 'View Addons', process.env.VUE_APP_TEXTDOMAIN ),
		cancelButtonText: __( 'Dismiss', process.env.VUE_APP_TEXTDOMAIN ),
	} ).then( function(result) {
		if ( result.value ) {
			window.location = Vue.prototype.$mi.addons_url;
			Vue.prototype.$swal( {
				type: 'info',
				customContainerClass: 'monsterinsights-swal',
				title: __( 'Redirecting', process.env.VUE_APP_TEXTDOMAIN ),
				html: __( 'Please wait', process.env.VUE_APP_TEXTDOMAIN ),
				allowOutsideClick: false,
				allowEscapeKey: false,
				allowEnterKey: false,
				onOpen: function() {
					Vue.prototype.$swal.showLoading();
				},
			} );
		}
	});
}

function maybeCloseSwal( context ) {
	if ( context.rootState.$_widget ) {
		return;
	}
	Vue.prototype.$swal.close();
}

export default {
	getReportData,
};
