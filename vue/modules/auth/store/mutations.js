const AUTH_UPDATED = ( state, auth ) => {
	state.auth = auth;
};

const AUTH_DELETED = ( state, network ) => {
	if ( network ) {
		state.auth.network_manual_ua = state.auth.network_ua;
		state.auth.network_ua = false;
		state.auth.network_viewname = false;
	} else {
		state.auth.manual_ua = state.auth.ua;
		state.auth.ua = false;
		state.auth.viewname = false;
	}
};

const MANUAL_UA_UPDATE = ( state, ua, network ) => {
	if ( network ) {
		state.auth.network_manual_ua = ua;
	} else {
		state.auth.manual_ua = ua;
	}
};

export default {
	AUTH_UPDATED,
	AUTH_DELETED,
	MANUAL_UA_UPDATE,
};
