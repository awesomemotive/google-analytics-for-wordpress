import api from '../api';
import Vue from 'vue';

const getSettings = ( context ) => {
	api.fetchSettings( context ).then( ( response ) => {
		context.commit( 'SETTINGS_UPDATED', response );
	}).catch( ( error ) => {
		// eslint-disable-next-line
		console.error( error );
	});
};

const updateSettings = ( context, update ) => {
	context.commit( 'SETTING_UPDATE', update );
	context.commit( 'SETTINGS_SAVE_START' );
	let api_action = api.saveSettings( context, update );
	api_action.then( function() {
		context.commit( 'SETTINGS_SAVE_END' );
	});
	return api_action;
};

const updateSettingsUndo = ( context, update ) => {
	context.commit( 'SETTING_UPDATE_UNDO', update );
	context.commit( 'SETTINGS_SAVE_START' );
	let api_action = api.saveSettings( context, update );
	api_action.then( function() {
		context.commit( 'SETTINGS_SAVE_END' );
	});
	return api_action;
};

const updateSettingsRedo = ( context, update ) => {
	context.commit( 'SETTING_UPDATE_REDO', update );
	context.commit( 'SETTINGS_SAVE_START' );
	let api_action = api.saveSettings( context, update );
	api_action.then( function() {
		context.commit( 'SETTINGS_SAVE_END' );
	});
	return api_action;
};

const simulateSave = ( context ) => {
	context.commit( 'SETTINGS_SAVE_START' );
	Vue.prototype.$mi_saving_toast({});

	setTimeout( function() {
		context.commit( 'SETTINGS_SAVE_END' );
		Vue.prototype.$mi_success_toast({});
	}, 1000 );
};

const undo = ( context ) => {
	context.dispatch( 'updateSettingsUndo', context.state.history[context.state.historyIndex - 1]);
};

const redo = ( context ) => {
	context.dispatch( 'updateSettingsRedo', context.state.history[context.state.historyIndex + 1]);
};

export default {
	getSettings,
	updateSettings,
	simulateSave,
	undo,
	redo,
	updateSettingsUndo,
	updateSettingsRedo,
};
