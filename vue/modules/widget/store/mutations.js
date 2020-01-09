import Vue from 'vue';

const ENABLE_REPORT = ( state, report ) => {
	if ( state.reports[report] ) {
		Vue.set( state.reports[report], 'enabled', true );
	}
};
const DISABLE_REPORT = ( state, report ) => {
	if ( state.reports[report] ) {
		Vue.set( state.reports[report], 'enabled', false );
	}
};
const UPDATE_LOADED = ( state, loaded ) => {
	state.loaded = loaded;
};

const UPDATE_WIDTH = ( state, width ) => {
	state.width = width;
};

const SET_ERROR = ( state, error ) => {
	if ( 'undefined' === typeof error.title && 'undefined' === typeof error.content && 'undefined' === typeof error.footer ) {
		Vue.set( state.error, error.report, false );
		return;
	}
	Vue.set( state.error, error.report, error );
};

export default {
	ENABLE_REPORT,
	DISABLE_REPORT,
	UPDATE_LOADED,
	UPDATE_WIDTH,
	SET_ERROR,
};
