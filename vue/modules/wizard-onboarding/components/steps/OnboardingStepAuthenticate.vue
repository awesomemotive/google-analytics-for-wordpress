<template>
	<div class="monsterinsights-onboarding-step-authenticate">
		<onboarding-content-header :title="text_header_title" :subtitle="text_header_subtitle"></onboarding-content-header>
		<div class="monsterinsights-onboarding-wizard-form">
			<form>
				<div class="monsterinsights-form-row">
					<onboarding-license></onboarding-license>
				</div>
				<div class="monsterinsights-separator"></div>
				<template v-if="auth_error">
					<div class="monsterinsights-notice monsterinsights-notice-error">
						<div class="monsterinsights-notice-inner">
							<span v-text="text_error_auth"></span>
						</div>
					</div>
					<div class="monsterinsights-separator"></div>
					<label v-text="text_manual_label"></label>
					<p v-html="text_manual_description"></p>
					<input id="monsterinsights-auth-manual-ua-input" type="text" class="monsterinsights-manual-ua" :value="is_network ? auth.network_manual_ua : auth.manual_ua" v-on:change="updateManualUa" v-on:input="fieldInput" />
					<label v-if="has_error" class="monsterinsights-error">
						<i class="monstericon-warning-triangle"></i><span v-html="has_error"></span>
					</label>
					<div class="monsterinsights-separator"></div>
					<div class="monsterinsights-form-row monsterinsights-form-buttons">
						<button type="submit" :class="manual_button_class" name="next_step" v-on:click.prevent="handleSubmit" v-text="text_save"></button>
					</div>
				</template>
				<template v-else>
					<onboarding-authenticate :label="text_authenticate_label" :description="text_authenticate_description" v-on:nextstep="handleSubmit" />
				</template>
			</form>
		</div>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import OnboardingContentHeader from '../OnboardingContentHeader';
	import OnboardingLicense from '../inputs/OnboardingLicense-MI_VERSION';
	import OnboardingAuthenticate from '../inputs/OnboardingAuthenticate-MI_VERSION';
	import debounce from 'lodash.debounce';

	export default {
		name: 'OnboardingStepWelcome',
		components: { OnboardingAuthenticate, OnboardingLicense, OnboardingContentHeader },
		data() {
			return {
				text_header_title: __( 'Connect MonsterInsights to Your Website', process.env.VUE_APP_TEXTDOMAIN ),
				text_header_subtitle: __( 'MonsterInsights connects Google Analytics to WordPress and shows you stats that matter.', process.env.VUE_APP_TEXTDOMAIN ),
				text_authenticate_label: __( 'Connect Google Analytics + WordPress', process.env.VUE_APP_TEXTDOMAIN ),
				text_authenticate_description: __( 'You will be taken to the MonsterInsights website where you\'ll need to connect your Analytics account.', process.env.VUE_APP_TEXTDOMAIN ),
				text_error_auth: __( 'Whoops, something went wrong and we weren\'t able to connect to MonsterInsights. Please enter your Google UA code manually.', process.env.VUE_APP_TEXTDOMAIN ),
				text_manual_label: __( 'Manually enter your UA code', process.env.VUE_APP_TEXTDOMAIN ),
				text_manual_description: __( 'Warning: If you use a manual UA code, you won\'t be able to use any of the reporting and some of the tracking features. Your UA code should look like UA-XXXXXX-XX where the X\'s are numbers.', process.env.VUE_APP_TEXTDOMAIN ),
				text_save: __( 'Save and Continue', process.env.VUE_APP_TEXTDOMAIN ),
				is_network: this.$mi.network,
				has_error: false,
				auth_error: false,
				manual_valid: true,
			};
		},
		computed: {
			...mapGetters( {
				auth: '$_auth/auth',
			}),
			manual_button_class() {
				let button_class = 'monsterinsights-onboarding-button monsterinsights-onboarding-button-large';
				if ( ! this.manual_valid ) {
					button_class += ' monsterinsights-button-disabled';
				}
				return button_class;
			},
		},
		methods: {
			fieldInput: debounce( function( e ) {
				this.updateManualUa( e );
			}, 500 ),
			handleSubmit() {
				if ( '' === this.auth.manual_ua ) {
					this.manual_valid = false;
					this.has_error = __( 'UA code can\'t be empty', process.env.VUE_APP_TEXTDOMAIN );
					return;
				}
				if ( this.auth_error && ! this.manual_valid ) {
					return;
				}
				this.$router.push( this.$wizard_steps[2]);
			},
			updateManualUa: function( e ) {
				const self = this;
				self.$swal({
					type: 'info',
					title: __( 'Saving UA code...', process.env.VUE_APP_TEXTDOMAIN ),
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					onOpen: function() {
						self.$swal.showLoading();
					},
				});
				self.has_error = false;
				self.manual_valid = false;
				this.$store.dispatch( '$_auth/updateManualUa', { ua: e.target.value, network: this.is_network } ).then( function( resolve ) {
					if ( false === resolve.success ) {
						self.has_error = resolve.data.error;
						self.$swal.close();
					} else {
						self.has_error = false;
						self.manual_valid = true;
						self.$swal.close();
					}
				});
			},
		},
		mounted() {
			if ( 'undefined' !== typeof URLSearchParams ) {
				let search_params = new URLSearchParams( window.location.search );
				if ( search_params ) {
					let error = search_params.get( 'mi-auth-error' );
					if ( '1' === error || '2' === error ) {
						this.auth_error = parseInt(error);
						if ( this.auth.manual_ua ) {
							this.manual_valid = true;
						}
					}
				}
			}
		},
	};
</script>
