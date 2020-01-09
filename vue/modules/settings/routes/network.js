import Router from 'vue-router';
import SettingsNetwork from '../components/SettingsNetwork';
import AboutModuleSite from '../../about/about';
import AddonsModuleSite from '../../addons/addons-MI_VERSION';
import AboutTabAboutUs from '../../about/components/AboutTabAboutUs';
import AboutTabGettingStarted from '../../about/components/AboutTabGettingStarted';
import AboutTabLiteVsPro from '../../about/components/AboutTabLiteVsPro';
import { __ } from "@wordpress/i18n";

export default new Router({
	routes: [
		{
			path: '*',
			redirect: '/',
		},
		{
			path: '/',
			name: 'general',
			component: SettingsNetwork,
		},
		{
			path: '/addons',
			name: 'addons',
			component: AddonsModuleSite,
		},
		{
			path: '/about',
			component: AboutModuleSite,
			children: [
				{
					name: 'about-us',
					path: '',
					component: AboutTabAboutUs,
					meta: {
						title: __( 'About Us', process.env.VUE_APP_TEXTDOMAIN ),
					},
				},
				{
					name: 'about-getting-started',
					path: 'getting-started',
					component: AboutTabGettingStarted,
					meta: {
						title: __( 'Getting Started', process.env.VUE_APP_TEXTDOMAIN ),
					},

				},
				{
					name: 'about-lite-vs-pro',
					path: 'lite-vs-pro',
					component: AboutTabLiteVsPro,
					meta: {
						title: __( 'Lite vs Pro', process.env.VUE_APP_TEXTDOMAIN ),
					},

				},
			],
		},
	],
});
