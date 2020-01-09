import actions from './actions';
import getters from './getters';
import mutations from './mutations';
import { __ } from '@wordpress/i18n';

const state = {
	width: 'regular',
	interval: 30,
	loaded: false,
	reports: {
		overview: {
			type: 'overview',
			name: __( 'Overview Report', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: true,
			component: 'WidgetReportOverview',
		},
		toppages: {
			type: 'overview',
			name: __( 'Top Posts/Pages', process.env.VUE_APP_TEXTDOMAIN ),
			tooltip: __( 'This list shows the most viewed posts and pages on your website.', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportTopPosts',
		},
		newvsreturn: {
			type: 'overview',
			name: __( 'New vs. Returning Visitors', process.env.VUE_APP_TEXTDOMAIN ),
			tooltip: __( 'This graph shows what percent of your user sessions come from new versus repeat visitors.', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportNewVsReturning',
		},
		devices: {
			type: 'overview',
			name: __( 'Device Breakdown', process.env.VUE_APP_TEXTDOMAIN ),
			tooltip: __( 'This graph shows what percent of your visitor sessions are done using a traditional computer or laptop, tablet or mobile device to view your site.', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportDevices',
		},
		landingpages: {
			type: 'publisher',
			name: __( 'Top Landing Pages', process.env.VUE_APP_TEXTDOMAIN ),
			tooltip: __( 'This list shows the top pages users first land on when visiting your website.', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportLandingPages',
		},
		exitpages: {
			type: 'publisher',
			name: __( 'Top Exit Pages', process.env.VUE_APP_TEXTDOMAIN ),
			tooltip: __( 'This list shows the top pages users exit your website from.', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportExitPages',
		},
		outboundlinks: {
			type: 'publisher',
			name: __( 'Top Outbound Links', process.env.VUE_APP_TEXTDOMAIN ),
			tooltip: __( 'This list shows the top links clicked on your website that go to another website.', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportOutboundLinks',
		},
		affiliatelinks: {
			type: 'publisher',
			name: __( 'Top Affiliate Links', process.env.VUE_APP_TEXTDOMAIN ),
			tooltip: __( 'This list shows the top affiliate links your visitors clicked on.', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportAffiliateLinks',
		},
		downloadlinks: {
			type: 'publisher',
			name: __( 'Top Download Links', process.env.VUE_APP_TEXTDOMAIN ),
			tooltip: __( 'This list shows the download links your visitors clicked the most.', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportDownloadLinks',
		},
		infobox: {
			type: 'ecommerce',
			name: __( 'Overview', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportEcommerceOverview',
		},
		products: {
			type: 'ecommerce',
			name: __( 'Top Products', process.env.VUE_APP_TEXTDOMAIN ),
			tooltip: __( 'This list shows the top selling products on your website.', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportTopProducts',
		},
		conversions: {
			type: 'ecommerce',
			name: __( 'Top Conversion Sources', process.env.VUE_APP_TEXTDOMAIN ),
			tooltip: __( 'This list shows the top referral websites in terms of product revenue.', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportTopConversions',
		},
		addremove: {
			type: 'ecommerce',
			name: __( 'Total Add/Remove', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportAddRemove',
		},
		days: {
			type: 'ecommerce',
			name: __( 'Time to Purchase', process.env.VUE_APP_TEXTDOMAIN ),
			tooltip: __( 'This list shows how many days from first visit it took users to purchase products from your site.', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportDays',
		},
		sessions: {
			type: 'ecommerce',
			name: __( 'Sessions to Purchase', process.env.VUE_APP_TEXTDOMAIN ),
			tooltip: __( 'This list shows the number of sessions it took users before they purchased a product from your website.', process.env.VUE_APP_TEXTDOMAIN ),
			enabled: false,
			component: 'WidgetReportSessions',
		},
	},
	error: {},
	notice30day: false,
};

export default
{
	namespaced: true,
		state,
		actions,
		getters,
		mutations,
};
