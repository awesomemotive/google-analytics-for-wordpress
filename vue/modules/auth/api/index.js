import axios from 'axios';
import Vue from 'vue';
import { __, sprintf } from '@wordpress/i18n';

const fetchAuth = function() {
	return new Promise( ( resolve ) => {
		let formData = new FormData();
		formData.append( 'action', 'monsterinsights_vue_get_profile' );
		formData.append( 'nonce', Vue.prototype.$mi.nonce );
		axios.post( Vue.prototype.$mi.ajax, formData ).then( ( response ) => {
			resolve( response.data );
		}).catch( function( error ) {
			if ( error.response ) {
				const response = error.response;
				return Vue.prototype.$mi_error_toast({
					title: sprintf( __( 'Can\'t load authentication details. Error: %1$s, %2$s', process.env.VUE_APP_TEXTDOMAIN ), response.status, response.statusText ),
				});
			}
			Vue.prototype.$mi_error_toast({
				title: __( 'You appear to be offline.', process.env.VUE_APP_TEXTDOMAIN ),
			});
		});
	});
};

const getAuthRedirect = ( network ) => {
	return new Promise( ( resolve ) => {
		let formData = new FormData();
		formData.append( 'action', 'monsterinsights_maybe_authenticate' );
		formData.append( 'nonce', Vue.prototype.$mi.nonce );
		if ( network ) {
			formData.append( 'isnetwork', true );
		}
		axios.post( Vue.prototype.$mi.ajax, formData ).then( ( response ) => {
			resolve( response.data );
		}).catch( function( error ) {
			if ( error.response ) {
				const response = error.response;
				return Vue.prototype.$mi_error_toast({
					title: sprintf( __( 'Can\'t authenticate. Error: %1$s, %2$s', process.env.VUE_APP_TEXTDOMAIN ), response.status, response.statusText ),
				});
			}
			Vue.prototype.$mi_error_toast({
				title: __( 'You appear to be offline.', process.env.VUE_APP_TEXTDOMAIN ),
			});
		});
	});
};

const getReAuthRedirect = ( network ) => {
	return new Promise( ( resolve ) => {
		let formData = new FormData();
		formData.append( 'action', 'monsterinsights_maybe_reauthenticate' );
		formData.append( 'nonce', Vue.prototype.$mi.nonce );
		if ( network ) {
			formData.append( 'isnetwork', true );
		}
		axios.post( Vue.prototype.$mi.ajax, formData ).then( ( response ) => {
			resolve( response.data );
		}).catch( function( error ) {
			if ( error.response ) {
				const response = error.response;
				return Vue.prototype.$mi_error_toast({
					title: sprintf( __( 'Can\'t reauthenticate. Error: %1$s, %2$s', process.env.VUE_APP_TEXTDOMAIN ), response.status, response.statusText ),
				});
			}
			Vue.prototype.$mi_error_toast({
				title: __( 'You appear to be offline.', process.env.VUE_APP_TEXTDOMAIN ),
			});
		});
	});
};

const verifyAuth = ( network ) => {
	return new Promise( ( resolve ) => {
		let formData = new FormData();
		formData.append( 'action', 'monsterinsights_maybe_verify' );
		formData.append( 'nonce', Vue.prototype.$mi.nonce );
		formData.append( 'isnetwork', network );
		axios.post( Vue.prototype.$mi.ajax, formData ).then( ( response ) => {
			resolve( response.data );
		}).catch( function( error ) {
			if ( error.response ) {
				const response = error.response;
				return Vue.prototype.$mi_error_toast({
					title: sprintf( __( 'Can\'t verify credentials. Error: %1$s, %2$s', process.env.VUE_APP_TEXTDOMAIN ), response.status, response.statusText ),
				});
			}
			Vue.prototype.$mi_error_toast({
				title: __( 'You appear to be offline.', process.env.VUE_APP_TEXTDOMAIN ),
			});
		});
	});
};

const deleteAuth = ( context, network, force ) => {
	return new Promise( ( resolve ) => {
		let formData = new FormData();
		formData.append( 'action', 'monsterinsights_maybe_delete' );
		formData.append( 'nonce', Vue.prototype.$mi.nonce );
		formData.append( 'isnetwork', network );
		if ( force ) {
			formData.append( 'forcedelete', true );
		}
		axios.post( Vue.prototype.$mi.ajax, formData ).then( ( response ) => {
			if ( response.data.success ) {
				context.commit( 'AUTH_DELETED', network );
			}
			resolve( response.data );
		}).catch( function( error ) {
			if ( error.response ) {
				const response = error.response;
				return Vue.prototype.$mi_error_toast({
					title: sprintf( __( 'Can\'t deauthenticate. Error: %1$s, %2$s', process.env.VUE_APP_TEXTDOMAIN ), response.status, response.statusText ),
				});
			}
			Vue.prototype.$mi_error_toast({
				title: __( 'You appear to be offline.', process.env.VUE_APP_TEXTDOMAIN ),
			});
		});
	});
};

const updateManualUa = ( context, ua, network ) => {
	return new Promise( ( resolve ) => {
		let formData = new FormData();
		formData.append( 'action', 'monsterinsights_update_manual_ua' );
		formData.append( 'manual_ua_code', ua );
		formData.append( 'nonce', Vue.prototype.$mi.nonce );
		if ( network ) {
			formData.append( 'isnetwork', network );
		}
		axios.post( Vue.prototype.$mi.ajax, formData ).then( ( response ) => {
			context.commit( 'MANUAL_UA_UPDATE', ua, network );
			resolve( response.data );
		}).catch( function( error ) {
			if ( error.response ) {
				const response = error.response;
				return Vue.prototype.$mi_error_toast({
					title: sprintf( __( 'Can\'t save settings. Error: %1$s, %2$s', process.env.VUE_APP_TEXTDOMAIN ), response.status, response.statusText ),
				});
			}
			Vue.prototype.$mi_error_toast({
				title: __( 'You appear to be offline. Settings not saved.', process.env.VUE_APP_TEXTDOMAIN ),
			});
		});
	});
};

export default {
	fetchAuth,
	getAuthRedirect,
	getReAuthRedirect,
	verifyAuth,
	deleteAuth,
	updateManualUa,
};
