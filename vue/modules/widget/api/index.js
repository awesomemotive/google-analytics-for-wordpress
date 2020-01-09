import axios from 'axios';
import Vue from 'vue';

const saveWidgetState = ( context, width, reports, interval, notice ) => {
	return new Promise( ( resolve ) => {
		let formData = new FormData();
		reports = JSON.stringify(reports);
		formData.append( 'action', 'monsterinsights_save_widget_state' );
		formData.append( 'nonce', Vue.prototype.$mi.nonce );
		formData.append( 'width', width );
		formData.append( 'reports', reports );
		formData.append( 'interval', interval );
		formData.append( 'notice', notice );
		axios.post( Vue.prototype.$mi.ajax, formData ).then( ( response ) => {
			Vue.prototype.$swal.close();
			resolve( response.data );
		} ).catch( function( error ) {
			console.log( error ); // eslint-disable-line no-console
		} );
	} );
};

const markNoticeClosed = () => {
	return new Promise( () => {
		let formData = new FormData();
		formData.append( 'action', 'monsterinsights_mark_notice_closed' );
		formData.append( 'nonce', Vue.prototype.$mi.nonce );
		axios.post( Vue.prototype.$mi.ajax, formData );
	} );
};

export default {
	saveWidgetState,
	markNoticeClosed,
};
