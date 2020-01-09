import Router from 'vue-router';
import SettingsTabGeneral from '../components/tabs/SettingsTabGeneral-MI_VERSION';
import SettingsTabEngagement from '../components/tabs/SettingsTabEngagement';
import SettingsTabEcommerce from '../components/tabs/SettingsTabEcommerce-MI_VERSION';
import SettingsTabPublisher from '../components/tabs/SettingsTabPublisher';
import SettingsTabConversions from '../components/tabs/SettingsTabConversions-MI_VERSION';
import SettingsTabAdvanced from '../components/tabs/SettingsTabAdvanced';
import AddonsModuleSite from '../../addons/addons-MI_VERSION';
import ToolsModuleSite from '../../tools/tools';
import AboutModuleSite from '../../about/about';
import ToolsTabImportExport from '../../tools/components/ToolsTabImportExport';
import ToolsTabUrlBuilder from '../../tools/components/ToolsTabUrlBuilder';
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
			component: SettingsTabGeneral,
			meta: {
				title: __( 'General', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		{
			path: '/engagement',
			name: 'engagement',
			component: SettingsTabEngagement,
			meta: {
				title: __( 'Engagement', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		{
			path: '/ecommerce',
			name: 'ecommerce',
			component: SettingsTabEcommerce,
			meta: {
				title: __( 'eCommerce', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		{
			path: '/publisher',
			name: 'publisher',
			component: SettingsTabPublisher,
			meta: {
				title: __( 'Publisher', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		{
			path: '/conversions',
			name: 'conversions',
			component: SettingsTabConversions,
			meta: {
				title: __( 'Conversions', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		{
			path: '/advanced',
			name: 'advanced',
			component: SettingsTabAdvanced,
			meta: {
				title: __( 'Advanced', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		{
			path: '/addons',
			name: 'addons',
			component: AddonsModuleSite,
		},
		{
			path: '/tools',
			component: ToolsModuleSite,
			children: [
				{
					name: 'tools-url-builder',
					path: '',
					component: ToolsTabUrlBuilder,
					meta: {
						title: __( 'URL Builder', process.env.VUE_APP_TEXTDOMAIN ),
					},
				},
				{
					name: 'tools-import-export',
					path: 'import-export',
					component: ToolsTabImportExport,
					meta: {
						title: __( 'Import Export', process.env.VUE_APP_TEXTDOMAIN ),
					},

				},
			],
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
