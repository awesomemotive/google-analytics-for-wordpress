import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
	date: {
		start: '',
		end: '',
		interval: 30,
		text: '',
	},
	blur: false,
	activeReport: 'overview',
	mobileTableExpanded: false,
	overview: {},
	publisher: {},
	ecommerce: {},
	queries: {},
	dimensions: {},
	forms: {},
	realtime: {},
	yearinreview: {},
	noauth: false,
	reauth: false,
};

export default {
	namespaced: true,
	state,
	actions,
	getters,
	mutations,
};
