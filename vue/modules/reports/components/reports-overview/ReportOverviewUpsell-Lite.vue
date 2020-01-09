<template>
	<div class="monsterinsights-report-row monsterinsights-overview-upsell-desktop">
		<report-upsell-inline :title="text_upsell_title">
			<p v-text="text_upsell_content_1"></p>
			<p>
				<span class="monsterinsights-green-text">
					<b v-html="text_upsell_content_2"></b>
				</span><br />
				<span class="monsterinsights-light" v-html="text_upsell_content_3"></span>
			</p>
			<a :href="upgrade_link" class="monsterinsights-button monsterinsights-button-green"
				target="_blank" v-text="text_upsell_button"
			></a>
		</report-upsell-inline>
	</div>
</template>
<script>
	import { __, sprintf } from '@wordpress/i18n';
	import ReportUpsellInline from "../upsells/ReportUpsellInline";

	export default {
		name: 'ReportOverviewUpsell',
		components: { ReportUpsellInline },
		data() {
			return {
				text_upsell_title: __( 'Upgrade to MonsterInsights Pro to Unlock More Actionable Insights', process.env.VUE_APP_TEXTDOMAIN ),
				text_upsell_content_1: __( 'It\'s easy to double your traffic and sales when you know exactly how people find and use your website. MonsterInsights Pro shows you the stats that matter!', process.env.VUE_APP_TEXTDOMAIN ),
				text_upsell_content_2: __( 'Upgrade to MonsterInsights Pro and Save 50% OFF!', process.env.VUE_APP_TEXTDOMAIN ),
				text_upsell_content_3: sprintf( __( 'Use coupon code %1$sLITEUPGRADE%2$s', process.env.VUE_APP_TEXTDOMAIN ), '<b>', '</b>' ),
				text_upsell_button: __( 'Upgrade to MonsterInsights Pro', process.env.VUE_APP_TEXTDOMAIN ),
				upgrade_link: this.$getUpgradeUrl( 'report', 'overview' ),
			};
		},
	};
</script>
