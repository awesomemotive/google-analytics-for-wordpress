import Vue from 'vue';
import VueRouter from 'vue-router';
import SettingsModuleSite from './modules/settings/site';
import SettingsModuleNetwork from './modules/settings/network';
import store from './store/index';
import VueSweetalert2 from 'vue-sweetalert2';
import VTooltip from 'v-tooltip';
import MonsterInsightsHelper from './plugins/monsterinsights-helper-plugin';
import MonsterInsightsSettingsHelper from './plugins/monsterinsights-settings-helper-plugin';
import { setLocaleData } from '@wordpress/i18n';
import clicky from 'vue-clicky';
// Styles
import '@/assets/scss/MI_THEME/global.scss';
import '@/assets/scss/MI_THEME/settings.scss';
import '@/assets/scss/MI_THEME/settings-MI_VERSION.scss';

Vue.config.productionTip = false;

Vue.use( VueRouter );
Vue.use( VueSweetalert2 );
Vue.use( VTooltip, {
	defaultTemplate: '<div class="monsterinsights-tooltip" role="tooltip"><div class="monsterinsights-tooltip-arrow"></div><div class="monsterinsights-tooltip-inner"></div></div>',
	defaultArrowSelector: '.monsterinsights-tooltip-arrow, .monsterinsights-tooltip__arrow',
	defaultInnerSelector: '.monsterinsights-tooltip-inner, .monsterinsights-tooltip__inner',
});
Vue.use( MonsterInsightsHelper );

setLocaleData( window.monsterinsights.translations, process.env.VUE_APP_TEXTDOMAIN );

const monsterinsights_settings = document.getElementById( 'monsterinsights-vue-site-settings' );

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

if ( monsterinsights_settings ) {
	Vue.use( MonsterInsightsSettingsHelper );
	new Vue({
		store,
		mounted: () => {
			store.dispatch( '$_app/init' );
			store.dispatch( '$_auth/getAuth' );
			store.dispatch( '$_addons/getAddons' );
			if ( Vue.prototype.$isPro() ) {
				store.dispatch( '$_license/getLicense' );
			}
		},
		render: h => h( SettingsModuleSite ),
	}).$mount( monsterinsights_settings );
}

const monsterinsights_network_settings = document.getElementById( 'monsterinsights-vue-network-settings' );

if ( monsterinsights_network_settings ) {
	Vue.use( MonsterInsightsSettingsHelper );
	new Vue({
		store,
		mounted: () => {
			store.dispatch( '$_app/init' );
			store.dispatch( '$_auth/getAuth' );
			store.dispatch( '$_addons/getAddons' );
			if ( Vue.prototype.$isPro() ) {
				store.dispatch( '$_license/getLicense' );
			}
		},
		render: h => h( SettingsModuleNetwork ),
	}).$mount( monsterinsights_network_settings );
}
