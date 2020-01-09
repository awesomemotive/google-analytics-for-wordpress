import api from '../api';

const getErrors = ( context ) => {
	let api_action = api.fetchErrors( context );

	api_action.then( ( response ) => {
		context.commit( 'ERRORS_UPDATED', response );
	}).catch( ( error ) => {
		// eslint-disable-next-line
		console.error( error );
	});

	return api_action;
};
export default {
	getErrors,
};
