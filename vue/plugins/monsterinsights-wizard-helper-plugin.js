import { __ } from '@wordpress/i18n';

const MonsterInsightsWizardHelper = {
	install( Vue ) {
		if ( Vue.prototype.$swal ) {
			Vue.prototype.$mi_saving_toast = function() {};
			Vue.prototype.$mi_success_toast = function() {};
			Vue.prototype.$mi_error_toast = function() {};

			Vue.prototype.$mi_loading_toast = function() {
				Vue.prototype.$swal({
					type: 'info',
					title: __( 'Loading settings', process.env.VUE_APP_TEXTDOMAIN ),
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					onOpen: function() {
						Vue.prototype.$swal.showLoading();
					},
				});
			};
		}
	},
};

export default MonsterInsightsWizardHelper;
