import Vue from 'vue';

const UPDATE_REPORT_DATA = ( state, report_data ) => {
	if ( report_data.report && report_data.data && state[report_data.report] ) {
		Vue.set( state, report_data.report, report_data.data );
	}
};
const UPDATE_DATE = ( state, new_dates ) => {
	if ( new_dates.start && new_dates.end ) {
		Vue.set( state.date, 'start', new_dates.start );
		Vue.set( state.date, 'end', new_dates.end );
	}
};
const UPDATE_INTERVAL = ( state, interval ) => {
	Vue.set( state.date, 'interval', interval );
};
const UPDATE_DATE_TEXT = ( state, text ) => {
	Vue.set( state.date, 'text', text );
};
const UPDATE_ACTIVE_REPORT = ( state, report ) => {
	state.activeReport = report;
};
const ENABLE_BLUR = ( state ) => {
	state.blur = true;
};
const DISABLE_BLUR = ( state ) => {
	state.blur = false;
};
const EXPAND_TABLES = ( state ) => {
	state.mobileTableExpanded = true;
};
const CONTRACT_TABLES = ( state ) => {
	state.mobileTableExpanded = false;
};
const ENABLE_NOAUTH = ( state ) => {
	state.noauth = true;
};
const ENABLE_REAUTH = ( state ) => {
	state.reauth = true;
};

export default {
	UPDATE_REPORT_DATA,
	UPDATE_DATE,
	UPDATE_ACTIVE_REPORT,
	UPDATE_INTERVAL,
	UPDATE_DATE_TEXT,
	ENABLE_BLUR,
	DISABLE_BLUR,
	EXPAND_TABLES,
	CONTRACT_TABLES,
	ENABLE_NOAUTH,
	ENABLE_REAUTH,
};
