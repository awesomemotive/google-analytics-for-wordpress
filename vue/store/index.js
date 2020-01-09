import Vue from 'vue';
import Vuex from 'vuex';
import LicenseStore from '@/modules/license/store';
import AuthStore from '@/modules/auth/store';
import AddonsStore from '@/modules/addons/store';
import AppStore from './app';
import MonsterInsightsCompatibilityPlugin from '../plugins/monsterinsights-compatibility-plugin';

Vue.use( Vuex );

const plugins = [
	MonsterInsightsCompatibilityPlugin,
];

export default new Vuex.Store({
	modules: {
		$_app: AppStore,
		$_license: LicenseStore,
		$_auth: AuthStore,
		$_addons: AddonsStore,
	},
	plugins,
});
