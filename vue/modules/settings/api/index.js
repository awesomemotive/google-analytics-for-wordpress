import axios from 'axios';
import Vue from 'vue';
import { __, sprintf } from '@wordpress/i18n';

const fetchSettings = ( context ) => {
	return new Promise( ( resolve ) => {
		let formData = new FormData();
		formData.append( 'action', 'monsterinsights_vue_get_settings' );
		formData.append( 'nonce', Vue.prototype.$mi.nonce );
		axios.post( Vue.prototype.$mi.ajax, formData ).then( ( response ) => {
			Vue.prototype.$swal.close();
			resolve( response.data );
		}).catch( function( error ) {
			context.dispatch( '$_app/block', false, { root: true });
			if ( error.response ) {
				const response = error.response;
				return Vue.prototype.$mi_error_toast({
					title: sprintf( __( 'Can\'t load settings. Error: %1$s, %2$s', process.env.VUE_APP_TEXTDOMAIN ), response.status, response.statusText ),
				});
			}
			Vue.prototype.$mi_error_toast({
				title: __( 'You appear to be offline.', process.env.VUE_APP_TEXTDOMAIN ),
			});
		});
	});
};

const saveSettings = ( context, setting ) => {
	return new Promise( ( resolve ) => {
		let formData = new FormData();
		formData.append( 'action', 'monsterinsights_vue_update_settings' );
		formData.append( 'nonce', Vue.prototype.$mi.nonce );
		formData.append( 'setting', setting.name );
		if ( false !== setting.value ) {
			// If it's an array, send it as JSON.
			if ( Array === setting.value.constructor ) {
				formData.append( 'value', JSON.stringify( setting.value ) );
			} else {
				formData.append( 'value', setting.value );
			}
		}
		axios.post( Vue.prototype.$mi.ajax, formData ).then( ( response ) => {
			resolve( response.data );
		}).catch( function( error ) {
			if ( error.response ) {
				const response = error.response;
				return Vue.prototype.$mi_error_toast({
					title: sprintf( __( 'Can\'t save settings. Error: %1$s, %2$s', process.env.VUE_APP_TEXTDOMAIN ), response.status, response.statusText ),
				});
			}
			Vue.prototype.$mi_error_toast({
				title: __( 'Network error encountered. Settings not saved.', process.env.VUE_APP_TEXTDOMAIN ),
			});
		});
	});
};

export default {
	fetchSettings,
	saveSettings,
};
