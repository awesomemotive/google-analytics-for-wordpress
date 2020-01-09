<template>
	<div class="monsterinsights-onboarding-step-wpforms">
		<onboarding-content-header :title="text_header_title" :subtitle="text_header_subtitle"></onboarding-content-header>
		<div class="monsterinsights-onboarding-wizard-form">
			<form action="" method="post" v-on:submit.prevent="handleSubmit">
				<div class="monsterinsights-separator"></div>
				<div class="monsterinsights-addon-row monsterinsights-wpforms-row">
					<div class="monsterinsights-addon-icon">
						<div class="monsterinsights-addon-wpforms">
						</div>
					</div>
					<div class="monsterinsights-addon-text">
						<label v-text="text_wpforms_label"></label>
						<p v-text="text_wpforms_description"></p>
					</div>
				</div>
				<div class="monsterinsights-separator"></div>
				<div class="monsterinsights-form-row monsterinsights-form-buttons">
					<div class="monsterinsights-form-input">
						<button type="button" :class="buttonClass()" v-on:click.prevent="installPlugin" v-text="button_text"></button>
						<button v-if="!loading" type="submit" class="monsterinsights-text-button monsterinsights-pull-right" name="next_step">
							<span v-text="text_skip_step"></span>
							<i class="monstericon-arrow-right"></i>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import OnboardingContentHeader from '../OnboardingContentHeader';

	export default {
		name: 'OnboardingStepWpforms',
		components: { OnboardingContentHeader },
		data() {
			return {
				text_header_title: __( 'MonsterInsights Recommends WPForms', process.env.VUE_APP_TEXTDOMAIN ),
				text_header_subtitle: __( 'Built by the folks behind MonsterInsights, WPForms is the most beginner friendly form plugin in the market.', process.env.VUE_APP_TEXTDOMAIN ),
				text_wpforms_label: __( 'Used on over 1,000,000 websites!', process.env.VUE_APP_TEXTDOMAIN ),
				text_wpforms_description: __( 'WPForms allow you to create beautiful contact forms, subscription forms, payment forms, and other types of forms for your site in minutes, not hours!', process.env.VUE_APP_TEXTDOMAIN ),
				text_skip_step: __( 'Skip this Step', process.env.VUE_APP_TEXTDOMAIN ),
				text_install_wpforms: __( 'Continue & Install WPForms', process.env.VUE_APP_TEXTDOMAIN ),
				text_installing_wpforms: __( 'Installing...', process.env.VUE_APP_TEXTDOMAIN ),
				button_text: '',
				loading: false,
			};
		},
		mounted() {
			this.button_text = this.text_install_wpforms;
		},
		methods: {
			handleSubmit() {
				this.$router.push( this.$wizard_steps[5]);
			},
			buttonClass() {
				let button_class = 'monsterinsights-onboarding-button monsterinsights-onboarding-button-large monsterinsights-install-wpforms';

				if ( this.loading ) {
					button_class += ' monsterinsights-button-disabled';
				}

				return button_class;
			},
			installPlugin() {
				let self = this;
				this.loading = true;
				this.button_text = this.text_installing_wpforms;
				this.$store.dispatch( '$_addons/installWPForms' ).then( function() {
					self.loading = false;
					self.button_text = self.text_install_wpforms;
					self.handleSubmit();
				});
			},
		},
	};
</script>
