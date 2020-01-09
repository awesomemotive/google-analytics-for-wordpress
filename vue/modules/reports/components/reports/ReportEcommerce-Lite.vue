<template>
	<main class="monsterinsights-report monsterinsights-report-ecommerce">
		<div class="monsterinsights-report-top">
			<h2 v-text="text_ecommerce"></h2>
		</div>
		<div class="monsterinsights-report-row">
			<report-table-box :headers="fake_headers" :title="text_upsell_title" :rows="[]"></report-table-box>
		</div>
		<report-upsell-overlay report="ecommerce" />
	</main>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import ReportTableBox from "../ReportTableBox";
	import ReportUpsellOverlay from "../upsells/ReportUpsellOverlay";

	export default {
		name: 'ReportEcommerce',
		components: { ReportUpsellOverlay, ReportTableBox },
		data() {
			return {
				text_upsell_title: __( 'eCommerce', process.env.VUE_APP_TEXTDOMAIN ),
				text_ecommerce: __( 'eCommerce Report', process.env.VUE_APP_TEXTDOMAIN ),
				fake_headers: [
					'Conversions',
					'Percent',
					'Date',
				],
			};
		},
		mounted() {
			this.$store.commit( '$_reports/ENABLE_BLUR' );
		},
	};
</script>
