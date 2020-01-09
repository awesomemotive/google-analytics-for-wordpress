<template>
	<div class="monsterinsights-settings-license-site">
		<settings-network-notice v-if="license_network.key">
			<strong v-text="text_license_key_network"></strong>
			<span v-text="text_license_key_network_2"></span>
		</settings-network-notice>
		<label for="monsterinsights-license-key" v-html="getLabel()"></label>
		<div v-if="license.is_invalid || '' === license.type">
			<input id="monsterinsights-license-key" :readonly="is_loading" type="text" autocomplete="off" :value="license.key" :class="{ 'monsterinsights-has-error' : has_error }" :placeholder="text_license_placeholder" v-on:input="fieldInput" />
		</div>
		<div v-if=" ! license.is_invalid && license.type" class="inline-field">
			<input id="monsterinsights-license-key-valid" v-model="license_key" type="text" autocomplete="off" :class="{ 'monsterinsights-has-error' : has_error }" :placeholder="text_license_placeholder" v-on:input="license_changed = true" />
			<button class="monsterinsights-button" v-on:click="verifyLicense" v-text="text_license_verify_button"></button>
			<button class="monsterinsights-button monsterinsights-button-secondary" v-on:click="deactivateLicense" v-text="text_license_deactivate"></button>
		</div>

		<label v-if="has_error" class="monsterinsights-error license-key-error">
			<i class="monstericon-warning-triangle"></i><span v-html="has_error"></span>
		</label>

		<div v-if="license.type && license.key || license_network.type && license_network.key" class="monsterinsights-license-type-text">
			<span v-html="getLicenseType()"></span>
			<a v-if="! license.is_invalid && license.type" href="#" v-on:click.prevent="refreshLicense">
				<span v-text="text_license_refresh"></span>
			</a>
			<settings-info-tooltip :content="text_license_refresh_tooltip" />
		</div>
	</div>
</template>
<script>
	import { mapGetters } from 'vuex';
	import { __, sprintf } from '@wordpress/i18n';
	import SettingsNetworkNotice from '../../SettingsNetworkNotice';
	import SettingsInfoTooltip from '../../SettingsInfoTooltip';
	import debounce from 'lodash.debounce';

	export default {
		name: 'SettingsInputLicenseSite',
		components: { SettingsInfoTooltip, SettingsNetworkNotice },
		data() {
			return {
				is_loading: false,
				has_error: false,
				text_license_key_network: __( 'Your license key has been set at the network level of your WordPress Multisite.', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_key_network_2: __( 'If you would like to use a different license for this subsite, you can enter it below.', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_no_key_subsite: __( 'No license key activated on this subsite', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_type: sprintf( __( 'Your license key type for this site is %s. ', process.env.VUE_APP_TEXTDOMAIN ), '<span class="monsterinsights-dark">%s</span>' ),
				text_license_refresh: __( 'Refresh Key', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_refresh_tooltip: __( 'Click refresh if your license has been upgraded or the type is incorrect.', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_verify: __( 'Verify', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_update: __( 'Change Key', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_deactivate: __( 'Deactivate', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_placeholder: __( 'Paste your license key here', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_label_site: sprintf( __( 'Add your MonsterInsights license key from the email receipt or account area. %1$sRetrieve your license key%2$s.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="' + this.$getUrl( 'license', 'settings_panel', 'https://www.monsterinsights.com/my-account/' ) + '" target="_blank">', '</a>' ),
				text_license_label_network: __( 'The license key is used to enable updates for MonsterInsights Pro and addons, as well enable the ability to view reports. Deactivate your license if you want to use it on another WordPress site.', process.env.VUE_APP_TEXTDOMAIN ),
				updated_license: false,
				license_changed: false,
			};
		},
		computed: {
			...mapGetters({
				license: '$_license/license',
				license_network: '$_license/license_network',
			}),
			license_key: {
				get() {
					return false !== this.updated_license ? this.updated_license : this.license.key;
				},
				set( value ) {
					this.updated_license = value;
				},
			},
			text_license_verify_button() {
				if ( this.license_changed ) {
					return this.text_license_update;
				}
				return this.text_license_verify;
			},
		},
		methods: {
			sprintf,
			getLabel() {
				return this.license_network.type ? this.text_license_label_network : this.text_license_label_site;
			},
			getLicenseType() {
				return this.license_network.type && ! this.license.type ? this.text_license_no_key_subsite : sprintf( this.text_license_type, this.license.type );
			},
			fieldInput:
				debounce( function( e ) {
					this.updateLicense( e );
				}, 500,
				),
			updateLicense: function( e ) {
				if ( '' === e.target.value ) {
					return false;
				}
				const self = this;
				this.is_loading = true;
				this.$mi_saving_toast({
					title: __( 'Verifying License', process.env.VUE_APP_TEXTDOMAIN ),
				});
				this.$emit( 'verify-license-start' );
				self.has_error = false;
				this.$store.dispatch( '$_license/updateLicense', e.target.value ).then( function( resolve ) {
					self.is_loading = false;
					self.$swal.close();
					self.$emit( 'verify-license-end' );
					if ( false === resolve.data.success ) {
						self.has_error = resolve.data.data.error;
						self.$mi_error_toast({});
					} else {
						self.has_error = false;
						self.$mi_success_toast({});
					}
				});
			},
			verifyLicense: function() {
				const self = this;
				this.$swal({
					type: 'info',
					title: __( 'Verifying License', process.env.VUE_APP_TEXTDOMAIN ),
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					customContainerClass: 'monsterinsights-swal',
					onOpen: function() {
						self.$swal.showLoading();
					},
				});
				this.$store.dispatch( '$_license/verifyLicense', self.license_key ).then( function( resolve ) {
					self.$swal.close();
					if ( resolve.data.success ) {
						self.$store.dispatch( '$_license/removeLicenseNotices' );
						self.$swal({
							type: 'info',
							title: __( 'Success', process.env.VUE_APP_TEXTDOMAIN ),
							html: resolve.data.data.message,
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
							customContainerClass: 'monsterinsights-swal',
						});
					} else {
						self.$store.dispatch( '$_license/addLicenseNotices' );
						self.$swal({
							type: 'error',
							title: __( 'There was an error verifying your license', process.env.VUE_APP_TEXTDOMAIN ),
							html: resolve.data.data.error,
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
							customContainerClass: 'monsterinsights-swal',
						});
					}
				});
			},
			refreshLicense: function( e ) {
				e.preventDefault();

				const self = this;
				this.$swal({
					type: 'info',
					title: __( 'Refreshing License', process.env.VUE_APP_TEXTDOMAIN ),
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					customContainerClass: 'monsterinsights-swal',
					onOpen: function() {
						self.$swal.showLoading();
					},
				});
				this.$store.dispatch( '$_license/validateLicense' ).then( function( resolve ) {
					self.$swal.close();
					if ( resolve.data.success ) {
						self.$store.dispatch( '$_license/removeLicenseNotices' );
						self.$swal({
							type: 'info',
							title: __( 'Success', process.env.VUE_APP_TEXTDOMAIN ),
							html: resolve.data.data.message,
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
							customContainerClass: 'monsterinsights-swal',
						});
					} else {
						self.$store.dispatch( '$_license/addLicenseNotices' );
						self.$swal({
							type: 'error',
							title: __( 'There was an error refreshing your license', process.env.VUE_APP_TEXTDOMAIN ),
							html: resolve.data.data.error,
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
							customContainerClass: 'monsterinsights-swal',
						});
					}
				});
			},
			deactivateLicense: function( e ) {
				e.preventDefault();

				const self = this;
				this.$swal({
					type: 'info',
					title: __( 'Deactivating License', process.env.VUE_APP_TEXTDOMAIN ),
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					customContainerClass: 'monsterinsights-swal',
					onOpen: function() {
						self.$swal.showLoading();
					},
				});
				this.$store.dispatch( '$_license/deactivateLicense' ).then( function( resolve ) {
					self.$swal.close();
					if ( resolve.data.success ) {
						self.$swal({
							type: 'info',
							title: __( 'Success', process.env.VUE_APP_TEXTDOMAIN ),
							html: resolve.data.data.message,
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
							customContainerClass: 'monsterinsights-swal',
						});
					} else {
						self.$swal({
							type: 'error',
							title: __( 'There was an error deactivating your license', process.env.VUE_APP_TEXTDOMAIN ),
							html: resolve.data.data.error,
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
							customContainerClass: 'monsterinsights-swal',
						});
					}
				});
			},
		},
	};
</script>
