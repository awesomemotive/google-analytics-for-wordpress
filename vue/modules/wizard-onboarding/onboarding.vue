<template>
	<div class="monsterinsights-admin-page onboarding-wizard">
		<the-wizard-header></the-wizard-header>
		<the-wizard-timeline></the-wizard-timeline>
		<div class="monsterinsights-onboarding-wizard-container">
			<div class="monsterinsights-onboarding-wizard-content">
				<router-view />
			</div>
		</div>
		<div v-if="blocked" class="monsterinsights-blocked"></div>
	</div>
</template>

<script>
	import OnboardingRouter from './routes';
	import { mapGetters } from 'vuex';
	import SettingsStore from './../settings/store';
	import OnboardingStore from './store';
	import '@/assets/scss/MI_THEME/wizard_onboarding.scss';
	import TheWizardHeader from './components/TheWizardHeader';
	import TheWizardTimeline from './components/TheWizardTimeline';

	export default {
		name: 'WizardModuleOnboarding',
		components: { TheWizardTimeline, TheWizardHeader },
		router: OnboardingRouter,
		created() {
			const STORE_KEY = '$_settings';
			// eslint-disable-next-line no-underscore-dangle
			if ( ! (
				STORE_KEY in this.$store._modules.root._children
			) ) {
				this.$store.registerModule( STORE_KEY, SettingsStore );
			}
			const WIZARD_STORE_KEY = '$_onboarding';
			// eslint-disable-next-line no-underscore-dangle
			if ( ! (
				WIZARD_STORE_KEY in this.$store._modules.root._children
			) ) {
				this.$store.registerModule( WIZARD_STORE_KEY, OnboardingStore );
			}
		},
		computed: {
			...mapGetters({
				blocked: '$_app/blocked',
			}),
		},
		mounted() {
			this.$store.dispatch( '$_settings/getSettings' );
			this.$mi_loading_toast();
		},
	};
</script>

<style lang="scss" scoped>
	button {
		margin-top: 3px;
	}
</style>
