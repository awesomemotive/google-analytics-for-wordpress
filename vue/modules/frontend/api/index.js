import axios from 'axios';
import Vue from 'vue';

const fetchReportData = ( context ) => {
	return new Promise( ( resolve ) => {
		let formData = new FormData();
		let page_path = Vue.prototype.$mi.page_id ? Vue.prototype.$mi.page_id : window.location.pathname;
		formData.append( 'action', 'monsterinsights_pageinsights_refresh_report' );
		formData.append( 'security', Vue.prototype.$mi.nonce );
		formData.append( 'report', 'pageinsights' );
		formData.append( 'post_id', page_path );
		formData.append( 'json', 1 );
		axios.post( Vue.prototype.$mi.ajax, formData ).then( ( response ) => {
//			Vue.prototype.$swal.close();
			resolve( response.data );
		}).catch( function( error ) {
			context.dispatch( '$_app/block', false, { root: true });
			if ( error.response ) {
//				const response = error.response;
//				return Vue.prototype.$mi_error_toast({
//					title: sprintf( __( 'Can\'t load report data. Error: %s, %s', process.env.VUE_APP_TEXTDOMAIN ), response.status, response.statusText ),
//				});
			}
//			Vue.prototype.$mi_error_toast({
//				title: __( 'You appear to be offline.', process.env.VUE_APP_TEXTDOMAIN ),
//			});
		});
	});
};

export default {
	fetchReportData,
};
