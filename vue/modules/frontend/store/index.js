import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
	loaded: false,
	pageinsights: {},
	error: false,
	noauth: false,
};

export default {
	namespaced: true,
	state,
	actions,
	getters,
	mutations,
};
