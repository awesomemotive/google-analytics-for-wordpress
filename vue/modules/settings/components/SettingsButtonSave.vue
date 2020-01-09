<template>
	<button v-if="isRouteSettings" v-tooltip="tooltip" :class="buttonClass()" v-on:click="simulateSave" v-text="text_save_changes"></button>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';

	export default {
		name: 'SettingsButtonSave',
		computed: {
			...mapGetters({
				settings: '$_settings/settings',
				auth: '$_auth/auth',
			}),
			route() {
				return this.$route.name;
			},
			isRouteSettings() {
				if ( 'addons' === this.route ){
					return false;
				}
				if ( 'tools-url-builder' === this.route || 'tools-import-export' === this.route ){
					return false;
				}
				if ( 0 === this.route.indexOf( 'about' ) ) {
					return false;
				}

				return true;
			},
			is_authed() {
				let is_authed = this.auth.network_ua ? this.auth.network_ua : this.auth.ua;

				if ( ! is_authed ) {
					is_authed = this.auth.network_manual_ua ? this.auth.network_manual_ua : this.auth.manual_ua;
				}

				return '' !== is_authed;
			},
			tooltip() {
				return this.is_authed ? false : this.tooltip_data;
			},
		},
		data() {
			return {
				text_save_changes: __( 'Save Changes', process.env.VUE_APP_TEXTDOMAIN ),
				tooltip_data: {
					content: this.$mi_need_to_auth,
					autoHide: false,
					trigger: 'hover focus click',
				},
			};
		},
		methods: {
			buttonClass() {
				let button_class = 'monsterinsights-button';

				if ( this.settings.is_saving || ! this.is_authed ) {
					button_class += ' monsterinsights-button-disabled';
				}

				return button_class;
			},
			simulateSave( e /* eslint-disable-line no-unused-vars */ ) {
				if ( this.buttonClass().indexOf( 'monsterinsights-button-disabled' ) > -1 ) {
					return false;
				}
				this.$store.dispatch( '$_settings/simulateSave' );
			},
		},
	};
</script>
