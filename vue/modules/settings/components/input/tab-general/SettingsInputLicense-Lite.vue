<template>
	<div class="settings-input settings-input-license">
		<p v-html="text_license_row_1"></p>
		<p v-html="text_license_row_2"></p>
		<p v-html="text_license_row_3"></p>
		<div class="monsterinsights-settings-license-lite">
			<label for="monsterinsights-license-key" v-html="text_license_label"></label>
			<div class="inline-field">
				<input id="monsterinsights-license-key" v-model="connect_key" :readonly="is_loading" type="text" autocomplete="off" :placeholder="text_license_placeholder" v-on:input="fieldInput" />
				<button v-if="show_connect" class="monsterinsights-button" v-on:click.prevent="startUpgradeToPro" v-text="text_upgrade_to_pro"></button>
			</div>
		</div>
	</div>
</template>

<script>
	import { __, sprintf } from '@wordpress/i18n';
	import debounce from 'lodash.debounce';
	import api from '../../../../license/api';

	export default {
		name: 'SettingsInputLicense',
		props: {
			label: String,
		},
		data() {
			return {
				is_network: this.$mi.network,
				text_license_row_1: sprintf( __( 'You\'re using %1$sMonsterInsights Lite%2$s - no license needed. Enjoy! %3$s', process.env.VUE_APP_TEXTDOMAIN ), '<strong>', '</strong>', '<span class="monsterinsights-bg-img monsterinsights-smile"></span>' ),
				text_license_row_2: sprintf( __( 'To unlock more features consider %1$supgrading to PRO%2$s.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="' + this.$getUpgradeUrl( 'settings-panel', 'license' ) + '" class="monsterinsights-bold" target="_blank">', '</a>' ),
				text_license_row_3: sprintf( __( 'As a valued MonsterInsights Lite user you %1$sreceive 50%% off%2$s, automatically applied at checkout!', process.env.VUE_APP_TEXTDOMAIN ), '<span class="monsterinsights-highlighted-text">', '</span>' ),
				text_upgrade_to_pro: __( 'Unlock PRO Features Now', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_placeholder: __( 'Paste your license key here', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_verify: __( 'Verify', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_label: sprintf( __( 'Already purchased? Simply enter your license key below to connect with MonsterInsights PRO! %1$sRetrieve your license key%2$s.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="' + this.$getUrl( 'license', 'settings_panel', 'https://www.monsterinsights.com/my-account/' ) + '" target="_blank">', '</a>' ),
				is_loading: false,
				show_connect: false,
				connect_key: '',
			};
		},
		methods: {
			fieldInput: debounce( function() {
				this.show_connect = '' !== this.connect_key;
			}, 100,
			),
			startUpgradeToPro() {
				const self = this;

				this.$swal( {
					type: 'info',
					title: __( 'Please wait...', process.env.VUE_APP_TEXTDOMAIN ),
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					customContainerClass: 'monsterinsights-swal',
					onOpen: function() {
						self.$swal.showLoading();
					},
				} );

				api.getUpgradeLink( this.connect_key ).then( function( response ) {
					if ( response.success && response.data.url ) {
						return window.location = response.data.url;
					} else {
						let message = response.data.message ? response.data.message : __( 'There was an error unlocking MonsterInsights PRO please try again or install manually.', process.env.VUE_APP_TEXTDOMAIN );
						self.$mi_error_toast( {
							title: __( 'Error', process.env.VUE_APP_TEXTDOMAIN ),
							text: message,
							toast: false,
							position: 'center',
							showConfirmButton: true,
							showCloseButton: false,
							customClass: false,
						} ).then( function() {
							if ( response.data.reload ) {
								window.location.reload();
							}
						} );
					}
				} ).catch( function() {
					self.$swal.close();
				} );
			},
		},
	};
</script>
