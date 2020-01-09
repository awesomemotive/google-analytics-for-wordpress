<template>
	<div v-if="tip" class="monsterinsights-tips">
		<span class="monstericon-star"></span>
		<div class="monsterinsights-tip-text">
			<strong v-text="text_pro_tip"></strong> {{ tip.text }} <a :href="upgradeUrl( 'pro-tips', tip.utm )" v-text="text_upgrade_link"></a>
		</div>
	</div>
</template>
<script>
	import { mapGetters } from 'vuex';
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'WidgetTips',
		data() {
			return {
				tips: [
					{
						text: __( 'Forms Tracking help you see who’s viewing your forms, so you can increase conversions.', process.env.VUE_APP_TEXTDOMAIN ),
						utm: 'forms',
						level: 'pro',
					},
					{
						text: __( 'Custom Dimensions show you popular categories, best time to publish, focus keywords, etc.', process.env.VUE_APP_TEXTDOMAIN ),
						utm: 'custom-dimensions',
						level: 'pro',
					},
					{
						text: __( 'Make Google Analytics GDPR compliant with our EU Compliance addon.', process.env.VUE_APP_TEXTDOMAIN ),
						utm: 'gdpr',
						level: 'plus',
					},
					{
						text: __( 'Get real-time Google Analytics report right inside your WordPress dashboard.', process.env.VUE_APP_TEXTDOMAIN ),
						utm: 'real-time',
						level: 'plus',
					},
					{
						text: __( 'Use Google Optimize to easily perform A/B split tests on your site.', process.env.VUE_APP_TEXTDOMAIN ),
						utm: 'optimize',
						level: 'pro',
					},
					{
						text: __( 'See all your important store metrics in one place with Enhanced Ecommerce Tracking.', process.env.VUE_APP_TEXTDOMAIN ),
						utm: 'ecommerce',
						level: 'pro',
					},
					{
						text: __( 'Unlock search console report to see your top performing keywords in Google.', process.env.VUE_APP_TEXTDOMAIN ),
						utm: 'search-console',
						level: 'plus',
					},
					{
						text: __( 'Get Page Insights to see important metrics for individual posts / pages in WordPress.', process.env.VUE_APP_TEXTDOMAIN ),
						utm: 'page-insights',
						level: 'plus',
					},
					{
						text: __( 'Publishers Report shows your top performing pages, audience demographics, and more.', process.env.VUE_APP_TEXTDOMAIN ),
						utm: 'publishers',
						level: 'plus',
					},
					{
						text: __( 'Get Scroll-Depth tracking to see how far users scroll on your pages before leaving.', process.env.VUE_APP_TEXTDOMAIN ),
						utm: 'scroll',
						level: 'plus',
					},
				],
				text_upgrade_link: __( 'Upgrade to Pro »', process.env.VUE_APP_TEXTDOMAIN ),
				text_pro_tip: __( 'Pro Tip:', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				license: '$_license/license',
				license_network: '$_license/license_network',
			} ),
			licenseLevel() {
				return this.$mi.network ? this.license_network.type : this.license.type;
			},
			tip() {
				return this.getTip();
			},
		},
		methods: {
			getRandomTip() {
				return this.tips[Math.floor(Math.random() * this.tips.length)];
			},
			getPlusTip() {
				let tip = this.getRandomTip();
				if ( tip.level !== 'pro' ) {
					return this.getPlusTip();
				}
				return tip;
			},
			getTip() {
				if ( '' === this.licenseLevel ) {
					// Lite, display all tips.
					return this.getRandomTip();
				} else if ( 'plus' === this.licenseLevel || 'basic' === this.licenseLevel ) {
					return this.getPlusTip();
				} else {
					return false;
				}
			},
			upgradeUrl( medium, campaign ) {
				return this.$getUpgradeUrl( medium, campaign );
			},
		},
	};
</script>
