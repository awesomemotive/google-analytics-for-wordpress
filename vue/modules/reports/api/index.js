import axios from 'axios';
import Vue from 'vue';
import { __, sprintf } from '@wordpress/i18n';

const fetchReportData = ( context, report, start, end ) => {
	return new Promise( ( resolve ) => {
		let formData = new FormData();
		formData.append( 'action', 'monsterinsights_vue_get_report_data' );
		formData.append( 'nonce', Vue.prototype.$mi.nonce );
		formData.append( 'report', report );
		formData.append( 'start', start );
		formData.append( 'end', end );
		axios.post( Vue.prototype.$mi.ajax, formData ).then( ( response ) => {
			resolve( response.data );
		}).catch( function( error ) {
			context.dispatch( '$_app/block', false, { root: true });
			if ( error.response ) {
				const response = error.response;
				return Vue.prototype.$mi_error_toast({
					title: sprintf( __( 'Can\'t load report data. Error: %1$s, %2$s', process.env.VUE_APP_TEXTDOMAIN ), response.status, response.statusText ),
				});
			}
			Vue.prototype.$swal.hideLoading();
			Vue.prototype.$mi_error_toast({
				allowOutsideClick: true,
				allowEscapeKey: true,
				title: __( 'Error loading report data', process.env.VUE_APP_TEXTDOMAIN ),
				html: error.message,
			});
		});
	});
};

export default {
	fetchReportData,
};
