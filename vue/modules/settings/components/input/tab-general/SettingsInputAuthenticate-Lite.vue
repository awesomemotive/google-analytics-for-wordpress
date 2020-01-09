<template>
	<div class="monsterinsights-settings-input monsterinsights-settings-input-authenticate">
		<settings-network-notice v-if="auth.network_ua && ! is_network && ! auth.ua">
			<strong v-text="text_auth_network"></strong>
			<span v-text="text_auth_network_2"></span>
		</settings-network-notice>
		<div v-if="( is_network ? ! auth.network_ua : ! auth.ua )">
			<span class="monsterinsights-dark" v-text="label"></span>
			<p v-html="description"></p>
			<slot name="before_connect"></slot>
			<button class="monsterinsights-button" v-on:click="doAuth" v-text="text_button_connect"></button>
			<p v-if="! show_manual_ua_normal && ! showManualOnClick" class="monsterinsights-auth-manual-connect-paragraph">
				<a href="#monsterinsights-auth-manual-ua-input" class="monsterinsights-auth-manual-connect-text" v-on:click="showManualClick" v-html="text_manual_connect"></a>
			</p>
		</div>
		<div v-if="showManualOnClick || show_manual_ua_normal">
			<div class="monsterinsights-separator"></div>
			<span class="monsterinsights-dark" v-text="text_manual_label"></span>
			<p v-html="text_manual_description"></p>
			<input id="monsterinsights-auth-manual-ua-input" type="text" class="monsterinsights-manual-ua" :value="is_network ? auth.network_manual_ua : auth.manual_ua" v-on:change="updateManualUa" />
			<label v-if="has_error" class="monsterinsights-error">
				<i class="monstericon-warning-triangle"></i><span v-html="has_error"></span>
			</label>
		</div>
		<div v-if="( is_network ? auth.network_ua : auth.ua )" class="monsterinsights-auth-info">
			<span class="monsterinsights-settings-input-toggle-collapsible" role="button" v-on:click="toggleButtons" v-on:keyup.enter="toggleButtons" v-on:keyup.space="toggleButtons">
				<i :class="iconClass" tabindex="0" onkeypress="if(event.keyCode==32||event.keyCode==13){return false;};"></i>
			</span>
			<span class="monsterinsights-dark" v-text="text_website_profile"></span>
			<p>
				<span v-text="text_active_profile"></span>:
				<span v-text="is_network ? auth.network_viewname :auth.viewname"></span>
			</p>
			<slide-down-up>
				<div v-if="showButtons">
					<div class="monsterinsights-auth-actions">
						<button class="monsterinsights-button" v-on:click="doReAuth" v-text="text_button_reconnect"></button>
						<button class="monsterinsights-button monsterinsights-button-secondary" v-on:click="verifyAuth" v-text="text_button_verify"></button>
						<button class="monsterinsights-button monsterinsights-button-secondary" v-on:click="deleteAuth" v-text="text_button_disconnect"></button>
					</div>
				</div>
			</slide-down-up>
		</div>
	</div>
</template>

<script>
	import { mapGetters } from 'vuex';
	import { __ } from '@wordpress/i18n';
	import SlideDownUp from '../../../../../components/helper/SlideDownUp';
	import SettingsNetworkNotice from '../../SettingsNetworkNotice';

	export default {
		name: 'SettingsInputAuthenticate',
		components: { SettingsNetworkNotice, SlideDownUp },
		props: {
			label: String,
			description: String,
		},
		data() {
			return {
				force_deauth: false,
				showButtons: false,
				showManualOnClick: false,
				has_error: false,
				is_network: this.$mi.network,
				text_button_connect: __( 'Connect MonsterInsights', process.env.VUE_APP_TEXTDOMAIN ),
				text_button_verify: __( 'Verify Credentials', process.env.VUE_APP_TEXTDOMAIN ),
				text_button_reconnect: __( 'Reconnect MonsterInsights', process.env.VUE_APP_TEXTDOMAIN ),
				text_website_profile: __( 'Website Profile', process.env.VUE_APP_TEXTDOMAIN ),
				text_active_profile: __( 'Active Profile', process.env.VUE_APP_TEXTDOMAIN ),
				text_auth_network: __( 'Your website profile has been set at the network level of your WordPress Multisite.', process.env.VUE_APP_TEXTDOMAIN ),
				text_auth_network_2: __( 'If you would like to use a different profile for this subsite, you can authenticate below.', process.env.VUE_APP_TEXTDOMAIN ),
				text_manual_label: __( 'Manually enter your UA code', process.env.VUE_APP_TEXTDOMAIN ),
				text_manual_description: __( 'Warning: If you use a manual UA code, you won\'t be able to use any of the reporting and some of the tracking features. Your UA code should look like UA-XXXXXX-XX where the X\'s are numbers.', process.env.VUE_APP_TEXTDOMAIN ),
				text_manual_connect: __( 'Or manually enter UA code (limited functionality)', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters({
				auth: '$_auth/auth',
				addons: '$_addons/addons',
			}),
			text_button_disconnect() {
				return this.force_deauth ? __( 'Force Deauthenticate', process.env.VUE_APP_TEXTDOMAIN ) : __( 'Disconnect MonsterInsights', process.env.VUE_APP_TEXTDOMAIN );
			},
			iconClass() {
				let icon_class = 'monstericon-arrow';
				if ( this.showButtons ) {
					icon_class += ' monstericon-down';
				}
				return icon_class;
			},
			is_authed() {
				return this.is_network ? this.auth.network_ua : this.auth.ua;
			},
			show_manual_ua() {
				if ( this.addons['manual_ua'] && this.addons['manual_ua'].active && '' === this.is_authed ) {
					return true;
				}
				return this.is_network ? this.auth.network_manual_ua : this.auth.manual_ua;
			},
			show_manual_ua_normal() {
				if ( this.addons['manual_ua'] && this.addons['manual_ua'].active && '' === this.is_authed ) {
					return true;
				}
				return this.is_network ? this.auth.network_manual_ua : this.auth.manual_ua;
			},
		},
		methods: {
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
							text: resolve.data.message,
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
							text: resolve.data.message,
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
						});
					}
				});
			},
			verifyAuth: function( e ) {
				e.preventDefault();
				const self = this;
				this.$swal({
					type: 'info',
					title: __( 'Verifying Credentials', process.env.VUE_APP_TEXTDOMAIN ),
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					onOpen: function() {
						self.$swal.showLoading();
					},
				});

				this.$store.dispatch( '$_auth/verifyAuth', this.is_network ).then( function( resolve ) {
					self.$swal.close();
					if ( resolve.success ) {
						self.$swal({
							type: 'success',
							title: resolve.data.message,
							text: __( 'Your site is connected to MonsterInsights!', process.env.VUE_APP_TEXTDOMAIN ),
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
						});
					} else {
						self.$swal({
							type: 'error',
							title: __( 'Error', process.env.VUE_APP_TEXTDOMAIN ),
							text: resolve.data.message,
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
						});
					}
				});
			},
			deleteAuth: function( e ) {
				e.preventDefault();
				const self = this;
				this.$swal({
					type: 'info',
					title: __( 'Deauthenticating', process.env.VUE_APP_TEXTDOMAIN ),
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					onOpen: function() {
						self.$swal.showLoading();
					},
				});

				this.$store.dispatch( '$_auth/deleteAuth', {
					network: this.is_network,
					force: this.force_deauth,
				} ).then( function( resolve ) {
					self.$swal.close();
					if ( resolve.success ) {
						self.$swal({
							type: 'success',
							title: resolve.data.message,
							text: __( 'You\'ve disconnected your site from MonsterInsights. Your site is no longer being tracked by Google Analytics and you won\'t see reports anymore.', process.env.VUE_APP_TEXTDOMAIN ),
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
						});
					} else {
						self.$swal({
							type: 'error',
							title: __( 'Error', process.env.VUE_APP_TEXTDOMAIN ),
							text: resolve.data.message,
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
						});
						self.force_deauth = true;
					}
				});
			},
			toggleButtons: function( e ) {
				e.preventDefault();
				this.showButtons = ! this.showButtons;
			},
			updateManualUa: function( e ) {
				const self = this;
				this.$mi_saving_toast({});
				self.has_error = false;
				this.$store.dispatch( '$_auth/updateManualUa', { ua: e.target.value, network: this.is_network } ).then( function( resolve ) {
					if ( false === resolve.success ) {
						self.has_error = resolve.data.error;
						self.$mi_error_toast({});
					} else {
						self.has_error = false;
						self.$mi_success_toast({});
					}
				});
			},
			showManualClick: function() {
				this.showManualOnClick = true;
			},
		},
	};
</script>
