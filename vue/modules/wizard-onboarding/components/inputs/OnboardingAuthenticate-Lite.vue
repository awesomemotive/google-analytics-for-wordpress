<template>
	<div class="monsterinsights-settings-input monsterinsights-settings-input-authenticate">
		<settings-network-notice v-if="auth.network_ua && ! is_network && ! auth.ua">
			<strong v-text="text_auth_network"></strong>
			<span v-text="text_auth_network_2"></span>
		</settings-network-notice>
		<div v-if="( is_network ? auth.network_ua : auth.ua )" class="monsterinsights-auth-info">
			<span class="monsterinsights-dark" v-text="text_website_profile"></span>
			<p>
				<span v-text="text_active_profile"></span>:
				<span v-text="is_network ? auth.network_viewname :auth.viewname"></span>
			</p>
			<div>
				<div class="monsterinsights-auth-actions">
					<button class="monsterinsights-onboarding-button monsterinsights-onboarding-button-large" v-on:click="doReAuth" v-text="text_button_reconnect"></button>
					<button type="submit" class="monsterinsights-text-button monsterinsights-pull-right" v-on:click.prevent="submitForm">
						<span v-text="text_skip"></span>
						<i class="monstericon-arrow-right"></i>
					</button>
				</div>
			</div>
		</div>
		<div v-else>
			<span class="monsterinsights-dark" v-text="label"></span>
			<p v-html="description"></p>
			<button class="monsterinsights-onboarding-button monsterinsights-onboarding-button-large" v-on:click="doAuth" v-text="text_button_connect"></button>
		</div>
	</div>
</template>

<script>
	import { mapGetters } from 'vuex';
	import { __ } from '@wordpress/i18n';
	import SettingsNetworkNotice from '../../../settings/components/SettingsNetworkNotice';

	export default {
		name: 'OnboardingAuthenticate',
		components: { SettingsNetworkNotice },
		props: {
			label: String,
			description: String,
		},
		data() {
			return {
				is_network: this.$mi.network,
				text_button_connect: __( 'Connect MonsterInsights', process.env.VUE_APP_TEXTDOMAIN ),
				text_button_reconnect: __( 'Reconnect MonsterInsights', process.env.VUE_APP_TEXTDOMAIN ),
				text_website_profile: __( 'Website profile', process.env.VUE_APP_TEXTDOMAIN ),
				text_active_profile: __( 'Active profile', process.env.VUE_APP_TEXTDOMAIN ),
				text_auth_network: __( 'Your website profile has been set at the network level of your WordPress Multisite.', process.env.VUE_APP_TEXTDOMAIN ),
				text_auth_network_2: __( 'If you would like to use a different profile for this subsite, you can authenticate below.', process.env.VUE_APP_TEXTDOMAIN ),
				text_skip: __( 'Skip and Keep Connection', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters({
				license: '$_license/license',
				license_network: '$_license/license_network',
				auth: '$_auth/auth',
			}),
			iconClass() {
				let icon_class = 'monstericon-arrow';
				if ( this.showButtons ) {
					icon_class += ' monstericon-down';
				}
				return icon_class;
			},
		},
		methods: {
			submitForm: function() {
				this.$emit( 'nextstep', true );
			},
			doAuth: function( e ) {
				e.preventDefault();
				const self = this;
				this.$swal({
					type: 'info',
					title: __( 'Authenticating', process.env.VUE_APP_TEXTDOMAIN ),
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					onOpen: function() {
						self.$swal.showLoading();
					},
				});

				this.$store.dispatch( '$_auth/doAuth', this.is_network ).then( function( resolve ) {
					if ( resolve.data.redirect ) {
						window.location = resolve.data.redirect;
					} else {
						self.$swal({
							type: 'error',
							title: __( 'Error', process.env.VUE_APP_TEXTDOMAIN ),
							html: resolve.data.message,
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
						});
					}
				});
			},
			doReAuth: function( e ) {
				e.preventDefault();
				const self = this;
				this.$swal({
					type: 'info',
					title: __( 'Re-Authenticating', process.env.VUE_APP_TEXTDOMAIN ),
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					onOpen: function() {
						self.$swal.showLoading();
					},
				});

				this.$store.dispatch( '$_auth/doReAuth', this.is_network ).then( function( resolve ) {
					if ( resolve.data.redirect ) {
						window.location = resolve.data.redirect;
					} else {
						self.$swal({
							type: 'error',
							title: __( 'Error', process.env.VUE_APP_TEXTDOMAIN ),
							html: resolve.data.message,
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
						});
					}
				});
			},
		},
	};
</script>
