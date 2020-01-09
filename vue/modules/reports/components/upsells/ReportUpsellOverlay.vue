<template>
	<div v-if="!noauth" class="monsterinsights-upsell-overlay">
		<div class="monsterinsights-upsell-top">
			<h3 v-if="upsellData.title" v-text="upsellData.title"></h3>
			<p v-if="upsellData.subtitle" class="monsterinsights-upsell-subtitle" v-text="upsellData.subtitle"></p>
		</div>
		<div class="monsterinsights-upsell-content">
			<ul v-if="upsellData.features">
				<li v-for="(feature, index) in upsellData.features" :key="index" v-text="feature"></li>
			</ul>
			<div class="monsterinsights-center">
				<a :href="upgrade_link" class="monsterinsights-button" target="_blank" v-text="text_upsell_button"></a>
			</div>
		</div>
	</div>
</template>
<script>
	import { mapGetters } from 'vuex';
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'ReportUpsellOverlay',
		props: {
			report: String,
		},
		data() {
			return {
				upgrade_link: this.$getUpgradeUrl( 'report', this.report ),
				text_upsell_button: __( 'Upgrade to MonsterInsights Pro', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				noauth: '$_reports/noauth',
			} ),
			upsellData() {
				return this.$mi_get_upsell_content( this.report );
			},
		},
	};
</script>
