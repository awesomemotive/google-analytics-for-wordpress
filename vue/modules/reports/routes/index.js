import Router from 'vue-router';
import { __ } from '@wordpress/i18n';
import moment from 'moment';
import ReportOverview from '../components/reports/ReportOverview';
import ReportPublishers from '../components/reports/ReportPublishers-MI_VERSION';
import ReportEcommerce from '../components/reports/ReportEcommerce-MI_VERSION';
import ReportSearchConsole from '../components/reports/ReportSearchConsole-MI_VERSION';
import ReportDimensions from '../components/reports/ReportDimensions-MI_VERSION';
import ReportForms from '../components/reports/ReportForms-MI_VERSION';
import ReportRealTime from '../components/reports/ReportRealTime-MI_VERSION';
import YearInReview from '../components/reports/YearInReview-MI_VERSION';

let routesObj = {
	routes: [
		{
			path: '*',
			redirect: '/',
		},
		{
			path: '/',
			name: 'overview',
			component: ReportOverview,
			meta: {
				title: __( 'Overview Report', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		{
			path: '/publishers',
			name: 'publisher',
			component: ReportPublishers,
			meta: {
				title: __( 'Publishers Report', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		{
			path: '/ecommerce',
			name: 'ecommerce',
			component: ReportEcommerce,
			meta: {
				title: __( 'eCommerce Report', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		{
			path: '/search-console',
			name: 'queries',
			component: ReportSearchConsole,
			meta: {
				title: __( 'Search Console Report', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		{
			path: '/dimensions',
			name: 'dimensions',
			component: ReportDimensions,
			meta: {
				title: __( 'Dimensions Report', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		{
			path: '/forms',
			name: 'forms',
			component: ReportForms,
			meta: {
				title: __( 'Forms Report', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		{
			path: '/real-time',
			name: 'realtime',
			component: ReportRealTime,
			meta: {
				title: __( 'Real-Time Report', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
	],
};

if ( moment().isBetween('2020-01-01', '2020-01-14') ) {
	routesObj.routes.push(
		{
			path: '/year-in-review',
			name: 'yearinreview',
			component: YearInReview,
			meta: {
				title: __( '2019 Year in Review', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
	);
}

export default new Router( routesObj );
