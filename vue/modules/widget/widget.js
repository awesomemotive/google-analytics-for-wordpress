import ModuleDashboardWidget from './widget.vue';
import WidgetReminder from './components/WidgetReminder.vue';

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueSweetalert2 from 'vue-sweetalert2';
import MonsterInsightsHelper from '../../plugins/monsterinsights-helper-plugin';
import MonsterInsightsReportsHelper from '../../plugins/monsterinsights-reports-helper-plugin';
import MonsterInsightsWidgetHelper from '../../plugins/monsterinsights-widget-helper-plugin';
import store from '@/store';
import VTooltip from 'v-tooltip';
import clicky from 'vue-clicky';

const monsterinsights_dashboard_widget = document.getElementById( 'monsterinsights-dashboard-widget' );
const monsterinsights_reminder_notice = document.getElementById( 'monsterinsights-reminder-notice' );

import '@/assets/scss/MI_THEME/widget.scss';
import { setLocaleData } from "@wordpress/i18n";

Vue.config.productionTip = false;

if ( monsterinsights_dashboard_widget ) {
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
	Vue.use( MonsterInsightsWidgetHelper, { store } );
	setLocaleData( window.monsterinsights.translations, process.env.VUE_APP_TEXTDOMAIN );
	new Vue({
		store,
		mounted: () => {
			store.dispatch( '$_app/init' );
			store.dispatch( '$_license/getLicense' );
		},
		render: h => h( ModuleDashboardWidget ),
	}).$mount( monsterinsights_dashboard_widget );
}

if ( monsterinsights_reminder_notice ) {
	Vue.use( VueRouter );
	Vue.use( MonsterInsightsHelper );
	Vue.use( MonsterInsightsReportsHelper );
	Vue.use( MonsterInsightsWidgetHelper, { store } );
	setLocaleData( window.monsterinsights.translations, process.env.VUE_APP_TEXTDOMAIN );
	new Vue({
		store,
		mounted: () => {
			store.dispatch( '$_app/init' );
		},
		render: h => h( WidgetReminder ),
	}).$mount( monsterinsights_reminder_notice );
}
