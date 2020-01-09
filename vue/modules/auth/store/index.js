import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
	auth: {
		'ua': '',
		'viewname': '',
		'network_ua': '',
		'network_viewname': '',
	},
};

export default {
	namespaced: true,
	state,
	actions,
	getters,
	mutations,
};
