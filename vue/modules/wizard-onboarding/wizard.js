import WizardModuleOnboarding from './onboarding';
import WizardModuleWelcome from './welcome-MI_VERSION';

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueSweetalert2 from 'vue-sweetalert2';
import MonsterInsightsHelper from '../../plugins/monsterinsights-helper-plugin';
import MonsterInsightsWizardHelper from '../../plugins/monsterinsights-wizard-helper-plugin';
import store from '@/store';
import VTooltip from 'v-tooltip';
import clicky from 'vue-clicky';
import { setLocaleData } from "@wordpress/i18n";

const monsterinsights_onboarding_wizard = document.getElementById( 'monsterinsights-vue-onboarding-wizard' );
const monsterinsights_welcome = document.getElementById( 'monsterinsights-welcome' );

Vue.config.productionTip = false;

if ( monsterinsights_onboarding_wizard || monsterinsights_welcome ) {
	// Used to assign the order of the steps.
	const MonsterInsightsWizardSteps = {
		install( VueInstance ) {
			VueInstance.prototype.$wizard_steps = [
				'welcome',
				'authenticate',
				'recommended_settings',
				'recommended_addons',
				'wpforms',
				'success',
			];
		},
	};

	if ( process.env.NODE_ENV !== 'production' ) {
		const VueAxe = require( 'vue-axe' );
		Vue.use( VueAxe, {
			config: {
				rules: [
					{ id: 'heading-order', enabled: true },
					{ id: 'label-title-only', enabled: true },
					{ id: 'link-in-text-block', enabled: true },
					{ id: 'region', enabled: true },
					{ id: 'skip-link', enabled: true },
					{ id: 'help-same-as-label', enabled: true },
				],
			},
		});

		Vue.config.devtools = true;
		Vue.config.performance = true;
	}

	clicky({ ctrl: true });

	Vue.use( VueRouter );
	Vue.use( VueSweetalert2 );
	Vue.use( VTooltip, {
		defaultTemplate: '<div class="monsterinsights-tooltip" role="tooltip"><div class="monsterinsights-tooltip-arrow"></div><div class="monsterinsights-tooltip-inner"></div></div>',
		defaultArrowSelector: '.monsterinsights-tooltip-arrow, .monsterinsights-tooltip__arrow',
		defaultInnerSelector: '.monsterinsights-tooltip-inner, .monsterinsights-tooltip__inner',
	} );
	Vue.use( MonsterInsightsHelper );

	setLocaleData( window.monsterinsights.translations, process.env.VUE_APP_TEXTDOMAIN );

	if ( monsterinsights_welcome ) {
		new Vue( {
			store,
			mounted: () => {
				store.dispatch( '$_app/init' );
			},
			render: h => h( WizardModuleWelcome ),
		} ).$mount( monsterinsights_welcome );
	} else {
		Vue.use( MonsterInsightsWizardSteps );
		Vue.use( MonsterInsightsWizardHelper );

		new Vue( {
			store,
			mounted: () => {
				store.dispatch( '$_app/init' );
				store.dispatch( '$_license/getLicense' );
				store.dispatch( '$_auth/getAuth' );
				store.dispatch( '$_addons/getAddons' );
			},
			render: h => h( WizardModuleOnboarding ),
		} ).$mount( monsterinsights_onboarding_wizard );
	}
}
