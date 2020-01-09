import Vue from 'vue';

const INIT = () => {
};

const BLOCK_APP = ( state ) => {
	state.blocked = true;
};

const UNBLOCK_APP = ( state ) => {
	state.blocked = false;
};

const ADD_NOTICE = ( state, notice ) => {
	if ( notice.id ) {
        Vue.set( state.notices, notice.id, notice);
    }
};

const REMOVE_NOTICE = ( state, index ) => {
    if ( state.notices[index] ) {
        Vue.delete( state.notices, index );
	}
};

const RESET_NOTICES = ( state ) => {
	state.notices = {};
};

const UPDATE_HOSTNAME = ( state, value ) => {
	state.ftp_form.hostname = value;
};

const UPDATE_USERNAME = ( state, value ) => {
	state.ftp_form.username = value;
};

const UPDATE_PASSWORD = ( state, value ) => {
	state.ftp_form.password = value;
};

const UPDATE_CONNECTION_TYPE = ( state, value ) => {
	state.ftp_form.connection_type = value;
};

const SHOW_FTP_FORM = ( state, options ) => {
	state.ftp_form.visible = true;
	state.ftp_form.action = options.action;
	state.ftp_form.data = options.data;
};

const HIDE_FTP_FORM = ( state ) => {
	state.ftp_form.visible = false;
};

export default {
	INIT,
	BLOCK_APP,
	UNBLOCK_APP,
	ADD_NOTICE,
	REMOVE_NOTICE,
	RESET_NOTICES,
	UPDATE_HOSTNAME,
	UPDATE_USERNAME,
	UPDATE_PASSWORD,
	UPDATE_CONNECTION_TYPE,
	SHOW_FTP_FORM,
	HIDE_FTP_FORM,
};
