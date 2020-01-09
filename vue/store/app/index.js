import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
	blocked: false,
	notices: {},
	mi: window.monsterinsights ? window.monsterinsights : {},
	ftp_form: {
		hostname: '',
		username: '',
		password: '',
		connection_type: 'ftp',
		visible: false,
		action: '', // Action to retry.
		data: {}, // Data to pass to action.
	},
};

export default {
	namespaced: true,
	state,
	actions,
	getters,
	mutations,
};
