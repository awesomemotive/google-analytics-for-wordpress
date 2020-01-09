import Vue from 'vue';

const SETTINGS_UPDATED = ( state, settings ) => {
	settings.is_saving = false;
	state.settings = settings;
};

const SETTING_UPDATE = ( state, new_settings ) => {
	state.settings.is_saving = true;
	if ( ! state.history[state.historyIndex] || state.history[state.historyIndex].name !== new_settings.name ) {
		state.history.push({
			name: new_settings.name,
			value: state.settings[new_settings.name] ? state.settings[new_settings.name] : false,
		});
		state.historyIndex++;
	}
	if ( state.historyIndex < state.history.length - 1 ) {
		// Restart the count from the current action.
		state.history.splice( state.historyIndex + 1 );
	}
	state.history.push({
		name: new_settings.name,
		value: new_settings.value,
	});
	state.historyIndex++;
	Vue.set( state.settings, new_settings.name, new_settings.value ); // This is needed in order to make the new properties reactive.
};

const SETTING_UPDATE_UNDO = ( state, new_settings ) => {
	state.settings.is_saving = true;
	state.historyIndex--;
	Vue.set( state.settings, new_settings.name, new_settings.value ); // This is needed in order to make the new properties reactive.
};

const SETTING_UPDATE_REDO = ( state, new_settings ) => {
	state.settings.is_saving = true;
	state.historyIndex++;
	Vue.set( state.settings, new_settings.name, new_settings.value ); // This is needed in order to make the new properties reactive.
};

const SETTINGS_SAVE_START = ( state ) => {
	state.settings.is_saving = true;
};

const SETTINGS_SAVE_END = ( state ) => {
	state.settings.is_saving = false;
};

export default {
	SETTINGS_UPDATED,
	SETTING_UPDATE,
	SETTINGS_SAVE_START,
	SETTINGS_SAVE_END,
	SETTING_UPDATE_UNDO,
	SETTING_UPDATE_REDO,
};
