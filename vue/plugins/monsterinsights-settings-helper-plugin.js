import { __, sprintf } from '@wordpress/i18n';

const MonsterInsightsSettingsHelper = {
	install( Vue ) {
		Vue.prototype.$mi_need_to_auth = sprintf( __( 'You need to %1$sconnect MonsterInsights%2$s first', process.env.VUE_APP_TEXTDOMAIN ), '<a href="' + Vue.prototype.$mi.wizard_url + '" class="monsterinsights-connect-inline">', '</a>' );
		if ( Vue.prototype.$swal ) {
			let is_saving = 0;
			Vue.prototype.$mi_saving_toast = function( settings ) {
				is_saving++;
				let {
					animation = false,
					toast = true,
					position = 'top-end',
					showConfirmButton = false,
					type = 'info',
					customClass = 'mi-info',
					showCloseButton = true,
					title = __( 'Saving Changes...', process.env.VUE_APP_TEXTDOMAIN ),
				} = settings;

				return Vue.prototype.$swal({
					customContainerClass: 'monsterinsights-swal',
					animation,
					toast,
					position,
					showConfirmButton,
					type,
					customClass,
					showCloseButton,
					title,
				});
			};
			Vue.prototype.$mi_success_toast = function( settings ) {
				is_saving--;
				if ( is_saving > 0 ) {
					return false;
				}
				let {
					animation = false,
					toast = true,
					position = 'top-end',
					showConfirmButton = false,
					type = 'success',
					timer = 3000,
					customClass = 'mi-success',
					showCloseButton = true,
					title = __( 'Settings Updated', process.env.VUE_APP_TEXTDOMAIN ),
					showCancelButton = false,
					confirmButtonText = '',
					cancelButtonText = '',
					text = '',
				} = settings;

				return Vue.prototype.$swal({
					customContainerClass: 'monsterinsights-swal',
					animation,
					toast,
					position,
					showConfirmButton,
					type,
					customClass,
					showCloseButton,
					title,
					timer,
					showCancelButton,
					confirmButtonText,
					cancelButtonText,
					text,
				});
			};
			Vue.prototype.$mi_error_toast = function( settings ) {
				is_saving--;
				let {
					animation = false,
					toast = true,
					position = 'top-end',
					showConfirmButton = false,
					type = 'error',
					customClass = 'mi-error',
					showCloseButton = true,
					title = __( 'Could Not Save Changes', process.env.VUE_APP_TEXTDOMAIN ),
					text = '',
				} = settings;

				return Vue.prototype.$swal({
					customContainerClass: 'monsterinsights-swal',
					animation,
					toast,
					position,
					showConfirmButton,
					type,
					customClass,
					showCloseButton,
					title,
					text,
					onOpen: function( ) {
						Vue.prototype.$swal.hideLoading();
					},
				});
			};

			Vue.prototype.$mi_loading_toast = function( title ) {
				Vue.prototype.$swal({
					customContainerClass: 'monsterinsights-swal',
					type: 'info',
					title: title ? title : __( 'Loading Settings', process.env.VUE_APP_TEXTDOMAIN ),
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

export default MonsterInsightsSettingsHelper;
