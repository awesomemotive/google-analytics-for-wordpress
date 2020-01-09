import ModuleFrontendReports from './frontend.vue';

import Vue from 'vue';
import MonsterInsightsHelper from '../../plugins/monsterinsights-helper-plugin';
import clicky from 'vue-clicky';
import '@/assets/scss/MI_THEME/frontend/frontend.scss';
import { setLocaleData } from "@wordpress/i18n";
import store from '@/store';
import MonsterInsightsFrontendHelper from "../../plugins/monsterinsights-frontend-helper-plugin";

window.addEventListener( 'load', function( ) {
	const monsterinsights_frontend_reports = document.getElementById( 'wp-admin-bar-monsterinsights_frontend_button' );

	Vue.config.productionTip = false;

	if ( monsterinsights_frontend_reports ) {
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

		Vue.use( MonsterInsightsHelper );
		Vue.use( MonsterInsightsFrontendHelper, { store } );
		setLocaleData( window.monsterinsights.translations, process.env.VUE_APP_TEXTDOMAIN );
		new Vue( {
			store,
			render: h => h( ModuleFrontendReports ),
		} ).$mount( monsterinsights_frontend_reports );
	}
});
