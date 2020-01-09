import api from '../api';

const getAuth = ( context ) => {
	const api_call = api.fetchAuth();
	api_call.then( ( response ) => {
		if ( response ) {
			context.commit( 'AUTH_UPDATED', response );
		}
	} ).catch( ( error ) => {
		// eslint-disable-next-line
		console.error( error );
	} );
};

const doAuth = ( context, network ) => {
	return api.getAuthRedirect( network );
};

const doReAuth = ( context, network ) => {
	return api.getReAuthRedirect( network );
};

const verifyAuth = ( context, network ) => {
	return api.verifyAuth( network );
};

const deleteAuth = ( context, options ) => {
	return api.deleteAuth( context, options.network, options.force );
};

const updateManualUa = ( context, data ) => {
	return api.updateManualUa( context, data.ua, data.network );
};

export default {
	getAuth,
	doAuth,
	doReAuth,
	verifyAuth,
	deleteAuth,
	updateManualUa,
};
