import axios from 'axios';
import Vue from 'vue';
import { __, sprintf } from '@wordpress/i18n';

const fetchErrors = ( context ) => {
	return new Promise( ( resolve ) => {
		let formData = new FormData();
		let url = Vue.prototype.$addQueryArg( Vue.prototype.$mi.ajax, 'page', 'monsterinsights-onboarding' );
		formData.append( 'action', 'monsterinsights_onboarding_get_errors' );
		axios.post( url, formData ).then( ( response ) => {
			resolve( response.data );
		}).catch( function( error ) {
			context.dispatch( '$_app/block', false, { root: true });
			if ( error.response ) {
				const response = error.response;
				return Vue.prototype.$mi_error_toast({
					title: sprintf( __( 'Can\'t load errors. Error: %1$s, %2$s', process.env.VUE_APP_TEXTDOMAIN ), response.status, response.statusText ),
				});
			}
			Vue.prototype.$mi_error_toast({
				title: __( 'You appear to be offline.', process.env.VUE_APP_TEXTDOMAIN ),
			});
		});
	});
};

export default {
	fetchErrors,
};
