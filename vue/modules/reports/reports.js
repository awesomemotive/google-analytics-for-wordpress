import ModuleReports from './reports.vue';

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueSweetalert2 from 'vue-sweetalert2';
import MonsterInsightsHelper from '../../plugins/monsterinsights-helper-plugin';
import MonsterInsightsReportsHelper from '../../plugins/monsterinsights-reports-helper-plugin';
import store from '@/store';
import VTooltip from 'v-tooltip';
import clicky from 'vue-clicky';

const monsterinsights_reports = document.getElementById( 'monsterinsights-reports' );

import '@/assets/scss/MI_THEME/global.scss';
import '@/assets/scss/MI_THEME/reports.scss';
import { setLocaleData } from "@wordpress/i18n";

Vue.config.productionTip = false;

if ( monsterinsights_reports ) {
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
	});
	Vue.use( MonsterInsightsHelper );
	Vue.use( MonsterInsightsReportsHelper );
	setLocaleData( window.monsterinsights.translations, process.env.VUE_APP_TEXTDOMAIN );
	new Vue({
		store,
		mounted: () => {
			store.dispatch( '$_app/init' );
			store.dispatch( '$_addons/getAddons' );
			if ( Vue.prototype.$isPro() ) {
				store.dispatch( '$_license/getLicense' );
			}
		},
		render: h => h( ModuleReports ),
	}).$mount( monsterinsights_reports );
}
