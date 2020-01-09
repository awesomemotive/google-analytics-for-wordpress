import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
	install_errors: [],
};

export default {
	namespaced: true,
	state,
	actions,
	getters,
	mutations,
};
