import Router from 'vue-router';
import OnboardingStepWelcome from '../components/steps/OnboardingStepWelcome';
import OnboardingStepAuthenticate from '../components/steps/OnboardingStepAuthenticate';
import OnboardingStepRecommendedSettings from '../components/steps/OnboardingStepRecommendedSettings';
import OnboardingStepRecommendedAddons from '../components/steps/OnboardingStepRecommendedAddons-MI_VERSION';
import OnboardingStepWpforms from '../components/steps/OnboardingStepWpforms';
import OnboardingStepSuccess from '../components/steps/OnboardingStepSuccess';

export default new Router({
	routes: [
		{
			path: '*',
			redirect: '/',
		},
		{
			path: '/',
			name: 'welcome',
			component: OnboardingStepWelcome,
		},
		{
			path: '/authenticate',
			name: 'authenticate',
			component: OnboardingStepAuthenticate,
		},
		{
			path: '/recommended_settings',
			name: 'recommended_settings',
			component: OnboardingStepRecommendedSettings,
		},
		{
			path: '/recommended_addons',
			name: 'recommended_addons',
			component: OnboardingStepRecommendedAddons,
		},
		{
			path: '/wpforms',
			name: 'wpforms',
			component: OnboardingStepWpforms,
		},
		{
			path: '/success',
			name: 'success',
			component: OnboardingStepSuccess,
		},
	],
});
