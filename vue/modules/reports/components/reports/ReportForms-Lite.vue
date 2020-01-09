<template>
	<main class="monsterinsights-report monsterinsights-report-forms">
		<div class="monsterinsights-report-top">
			<h2 v-text="text_forms"></h2>
		</div>
		<div class="monsterinsights-report-row">
			<report-table-box :headers="fake_headers" :title="text_forms_upsell" :rows="[]"></report-table-box>
		</div>
		<report-upsell-overlay report="forms" />
	</main>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import ReportTableBox from "../ReportTableBox";
	import ReportUpsellOverlay from "../upsells/ReportUpsellOverlay";

	export default {
		name: 'ReportSearchConsole',
		components: { ReportUpsellOverlay, ReportTableBox },
		mounted() {
			this.$store.commit( '$_reports/ENABLE_BLUR' );
		},
		data() {
			return {
				text_forms: __( 'Forms Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_forms_upsell: '',
				fake_headers: [
					'Form Name or ID',
					'Impressions',
					'Conversions',
					'Conversion Rate',
				],
			};
		},
	};
</script>
