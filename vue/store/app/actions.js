const init = ( context ) => {
	context.commit( 'INIT' );
};
const block = ( context ) => {
	context.commit( 'BLOCK_APP' );
};
const unblock = ( context ) => {
	context.commit( 'UNBLOCK_APP' );
};
const addNotice = ( context, notice ) => {
	context.commit( 'ADD_NOTICE', notice );
};
const removeNotice = ( context, index ) => {
	context.commit( 'REMOVE_NOTICE', index );
};
const resetNotices = ( context ) => {
	context.commit( 'RESET_NOTICES' );
};

export default {
	init,
	block,
	unblock,
	addNotice,
	removeNotice,
	resetNotices,
};
