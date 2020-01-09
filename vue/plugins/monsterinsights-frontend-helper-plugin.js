import { __ } from "@wordpress/i18n";

const MonsterInsightsFrontendHelper = {
	install( Vue, { store } ) {
		Vue.prototype.$mi_loading_toast = function() {};
		Vue.prototype.$mi_error_toast = function( settings ) {
			let {
				type = 'error',
				customContainerClass = 'monsterinsights-swal',
				allowOutsideClick = false,
				allowEscapeKey = false,
				allowEnterKey = false,
				title = __( 'Error', process.env.VUE_APP_TEXTDOMAIN ),
				html = __( 'Please try again.', process.env.VUE_APP_TEXTDOMAIN ),
				footer = false,
			} = settings;

			settings = {
				type,
				customContainerClass,
				allowOutsideClick,
				allowEscapeKey,
				allowEnterKey,
				title,
				html,
				footer,
			};
			store.commit( '$_frontend/SET_ERROR', {
				title: settings.title,
				content: settings.html,
				footer: settings.footer,
			} );
		};
		Vue.prototype.$swal = {
			close: function() {},
		};
	},
};

export default MonsterInsightsFrontendHelper;
