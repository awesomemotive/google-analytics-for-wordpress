import { __ } from "@wordpress/i18n";

const MonsterInsightsWidgetHelper = {
	install( Vue, { store } ) {
		Vue.prototype.$mi_loading_toast = function() {
		};
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
				report = 'general', // Like license issues which block all reports.
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
				report,
			};
			store.commit( '$_widget/SET_ERROR', {
				report: report,
				title: settings.title,
				content: settings.html,
				footer: settings.footer,
			} );
		};
	},
};

export default MonsterInsightsWidgetHelper;
