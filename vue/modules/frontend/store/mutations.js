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

const UPDATE_LOADED = ( state, loaded ) => {
	state.loaded = loaded;
};

const SET_ERROR = ( state, error ) => {
	state.error = error;
};

const ENABLE_NOAUTH = ( state ) => {
	state.noauth = true;
	state.loaded = true; // Don't show the spinner if the noauth message is visible.
};

export default {
	UPDATE_REPORT_DATA,
	UPDATE_DATE,
	UPDATE_INTERVAL,
	UPDATE_LOADED,
	SET_ERROR,
	ENABLE_NOAUTH,
};
