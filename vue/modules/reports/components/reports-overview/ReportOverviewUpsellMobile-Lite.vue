<template>
	<div class="monsterinsights-report-row monsterinsights-mobile-upsell">
		<div class="monsterinsights-notice monsterinsights-notice-success">
			<div class="monsterinsights-notice-inner">
				<span v-html="text_upsell_title"></span>
				<div class="monsterinsights-notice-button">
					<a :href="upgrade_link" class="monsterinsights-button monsterinsights-button-green" v-text="text_upsell_button"></a>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import { __, sprintf } from '@wordpress/i18n';

	export default {
		name: 'ReportOverviewUpsellMobile',
		data() {
			return {
				text_upsell_title: sprintf(__( 'Upgrade to Pro and unlock addons and other great features. %1$sSave 50%% automatically!%2$s', process.env.VUE_APP_TEXTDOMAIN ), '<span class="monsterinsights-green-text"><b>', '</b></span>' ),
				text_upsell_button: __( 'Upgrade Now', process.env.VUE_APP_TEXTDOMAIN ),
				upgrade_link: this.$getUpgradeUrl( 'report', 'overview-mobile' ),
			};
		},
	};
</script>
