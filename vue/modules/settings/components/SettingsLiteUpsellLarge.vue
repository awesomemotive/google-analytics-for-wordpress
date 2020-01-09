<template>
	<div class="monsterinsights-upsell monsterinsights-upsell-large">
		<h3 v-text="text_upsell_title"></h3>
		<h2 v-text="text_upsell_subtitle"></h2>
		<h4 v-on:click="selectText" v-html="sprintf( text_upsell_coupon, `<span class='monsterinsights-coupon'>LITEUPGRADE</span>` )"></h4>
		<div class="monsterinsights-features">
			<span v-for="( feature, index ) in text_features" :key="index" v-text="feature"></span>
		</div>
		<a class="monsterinsights-button monsterinsights-button-green monsterinsights-button-large" target="_blank" :href="upgrade_button_url" v-text="text_button_upgrade"></a>
	</div>
</template>
<script>
	import { __, sprintf } from '@wordpress/i18n';

	export default {
		name: 'SettingsLiteUpsellLarge',
		data() {
			return {
				text_upsell_title: __( 'Thank you for being a loyal MonsterInsights Lite user.', process.env.VUE_APP_TEXTDOMAIN ),
				text_upsell_subtitle: __( 'Upgrade to MonsterInsights Pro and unlock all the awesome features.', process.env.VUE_APP_TEXTDOMAIN ),
				text_upsell_coupon: __( 'Use coupon code %s to get 50%% off.', process.env.VUE_APP_TEXTDOMAIN ),
				text_features: [
					__( 'Dashboard widget', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Affiliate Links', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Custom Dimensions', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Enhanced Ecommerce', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Banner Ads', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Author Tracking', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Form Conversions', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Google AMP', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'SEO Score Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				],
				text_button_upgrade: __( 'Upgrade to MonsterInsights Pro', process.env.VUE_APP_TEXTDOMAIN ),
				upgrade_button_url: this.$getUpgradeUrl( 'settings-panel', 'ecommerce-tab' ),
			};
		},
		methods: {
			sprintf,
		},
	};
</script>
