import Vue from 'vue';
import api from '../api';

const processDefaults = ( context ) => {
	return new Promise( ( resolve ) => {
		if ( Vue.prototype.$mi.widget_state && Vue.prototype.$mi.widget_state.reports ) {
			for ( let report_type in Vue.prototype.$mi.widget_state.reports ) {
				if ( Vue.prototype.$mi.widget_state.reports.hasOwnProperty( report_type ) ) {
					for ( let report in Vue.prototype.$mi.widget_state.reports[report_type] ) {
						if ( Vue.prototype.$mi.widget_state.reports[report_type].hasOwnProperty( report ) ) {
							context.state.reports[report].enabled = Vue.prototype.$mi.widget_state.reports[report_type][report];
						}
					}
				}
			}
		}
		context.state.width = Vue.prototype.$mi.widget_state.width;
		context.state.interval = Vue.prototype.$mi.widget_state.interval;
		context.state.notice30day = Vue.prototype.$mi.widget_state.notice30day;
		resolve( true );
	});
};

const saveWidgetState = ( context ) => {
	let reports = {
		overview: {},
		publisher: {},
		ecommerce: {},
	};
	let interval = context.rootGetters.hasOwnProperty( '$_reports/date' ) ? context.rootGetters['$_reports/date']['interval'] : '';

	for ( let report in context.state.reports ) {
		if ( context.state.reports.hasOwnProperty( report ) && context.state.reports[report].hasOwnProperty( 'type' ) ) {
			let type = context.state.reports[report].type;
			reports[type][report] = context.state.reports[report]['enabled'];
		}
	}

	api.saveWidgetState( context, context.state.width, reports, interval );
};

const markNoticeClosed = () => {
	api.markNoticeClosed();
};

export default {
	processDefaults,
	saveWidgetState,
	markNoticeClosed,
};
