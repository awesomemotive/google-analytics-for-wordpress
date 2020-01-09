<template>
	<div class="monsterinsights-onboarding-step-welcome">
		<onboarding-content-header :title="text_header_title" :subtitle="text_header_subtitle"></onboarding-content-header>
		<div class="monsterinsights-onboarding-wizard-form">
			<div class="monsterinsights-separator"></div>
			<form v-on:submit.prevent="handleSubmit">
				<div class="monsterinsights-form-row">
					<div class="monsterinsights-form-label">
						<label v-text="text_category_label"></label>
						<p class="monsterinsights-description" v-text="text_category_sublabel"></p>
					</div>
					<settings-input-radio name="site_type" :options="options" :auth_disabled="false"></settings-input-radio>
				</div>
				<div class="monsterinsights-separator"></div>
				<div class="monsterinsights-form-row monsterinsights-form-buttons">
					<button type="submit" class="monsterinsights-onboarding-button monsterinsights-onboarding-button-large" name="next_step" v-text="text_save"></button>
				</div>
			</form>
		</div>
	</div>
</template>
<script>
	import { __, sprintf } from '@wordpress/i18n';
	import OnboardingContentHeader from '../OnboardingContentHeader';
	import SettingsInputRadio from '../../../settings/components/input/SettingsInputRadio';

	export default {
		name: 'OnboardingStepWelcome',
		components: { SettingsInputRadio, OnboardingContentHeader },
		data() {
			return {
				text_header_title: __( 'Welcome to MonsterInsights!', process.env.VUE_APP_TEXTDOMAIN ),
				text_header_subtitle: __( 'Let\'s get you set up.', process.env.VUE_APP_TEXTDOMAIN ),
				text_save: __( 'Save and Continue', process.env.VUE_APP_TEXTDOMAIN ),
				text_category_label: __( 'Which category best describes your website?', process.env.VUE_APP_TEXTDOMAIN ),
				text_category_sublabel: __( 'We will recommend the optimal settings for MonsterInsights based on your choice.', process.env.VUE_APP_TEXTDOMAIN ),
				options: [
					{
						value: 'business',
						label: __( 'Business Website', process.env.VUE_APP_TEXTDOMAIN ),
					},
					{
						value: 'publisher',
						label: sprintf( __( 'Publisher %1$s(Blog)%2$s', process.env.VUE_APP_TEXTDOMAIN ), '<small>', '</small>' ),
					},
					{
						value: 'ecommerce',
						label: __( 'Ecommerce', process.env.VUE_APP_TEXTDOMAIN ),
					},
				],
			};
		},
		methods: {
			handleSubmit() {
				this.$router.push( this.$wizard_steps[1]);
			},
		},
	};
</script>
