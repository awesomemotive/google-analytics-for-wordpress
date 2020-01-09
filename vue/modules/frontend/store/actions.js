import api from '../api';
import Vue from "vue";

const getReportData = ( context ) => {
	return new Promise( ( resolve ) => {
		api.fetchReportData( context ).then( function( response ) {
			if ( 'license_level' === response.data.message ) {
				resolve( false );
				return;
			}
			if ( response.success ) {
				context.commit( 'UPDATE_REPORT_DATA', {
					report: 'pageinsights',
					data: response.data,
				} );
				resolve( true );
			} else {
				Vue.prototype.$mi_error_toast( false, response.data.message, response.data.footer );
				resolve( false );
			}
		} );
	});
};

export default {
	getReportData,
};
