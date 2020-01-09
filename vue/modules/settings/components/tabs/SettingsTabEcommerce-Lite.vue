<template>
	<main class="monsterinsights-settings-content monsterinsights-settings-ecommerce monsterinsights-upsell">
		<div class="monsterinsights-upsell-top">
			<h2 v-text="text_title"></h2>
			<h4 v-text="text_sub_title"></h4>
			<div class="monsterinsights-logos-row">
				<span class="monsterinsights-box-title" v-text="text_one_click"></span>
				<div class="woocommerce-logo"></div>
				<div class="edd-logo"></div>
				<div class="memberpress-logo"></div>
			</div>
		</div>
		<div class="monsterinsights-upsell-bottom">
			<a class="monsterinsights-button monsterinsights-button-green monsterinsights-button-large monsterinsights-button-top" target="_blank" :href="upgrade_button_url" v-text="text_button_upgrade"></a>
			<div class="monsterinsights-bg-img monsterinsights-report-ecommerce"></div>
			<h3 v-text="text_bottom_title"></h3>
			<p v-text="text_bottom_content"></p>
			<a class="monsterinsights-button monsterinsights-button-green monsterinsights-button-large" target="_blank" :href="upgrade_button_url" v-text="text_button_upgrade"></a>
		</div>
	</main>
</template>

<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'SettingsTabEcommerce',
		data() {
			return {
				text_title: __( 'See All Your Important Store Metrics in One Place', process.env.VUE_APP_TEXTDOMAIN ),
				text_sub_title: __( 'Get an Answer to All Your Top Ecommerce Questions From a Single Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_one_click: __( 'ONE-CLICK INTEGRATIONS', process.env.VUE_APP_TEXTDOMAIN ),
				text_button_upgrade: __( 'Upgrade to MonsterInsights Pro', process.env.VUE_APP_TEXTDOMAIN ),
				text_bottom_title: __( 'Enable Ecommerce Tracking and Grow Your Business with Confidence', process.env.VUE_APP_TEXTDOMAIN ),
				text_bottom_content: __( 'MonsterInsights Ecommerce Addon makes it easy to setup enhanced eCommerce tracking, so you can see all your important eCommerce metrics like total revenue, conversion rate, average order value, top products, top referral sources, and more in a single report right inside your WordPress dashboard.', process.env.VUE_APP_TEXTDOMAIN ),
				upgrade_button_url: this.$getUpgradeUrl( 'settings-panel', 'ecommerce-tab' ),
			};
		},
	};
</script>
