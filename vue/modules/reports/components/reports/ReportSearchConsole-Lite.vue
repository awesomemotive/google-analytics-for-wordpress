<template>
	<main class="monsterinsights-report monsterinsights-report-search-console">
		<div class="monsterinsights-report-top">
			<h2 v-text="text_search_console"></h2>
		</div>
		<div class="monsterinsights-report-row">
			<report-table-box :headers="fake_headers" :title="text_upsell" :rows="[]"></report-table-box>
		</div>
		<report-upsell-overlay report="queries" />
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
				text_upsell: '',
				text_search_console: __( 'Search Console Report', process.env.VUE_APP_TEXTDOMAIN ),
				fake_headers: [
					'Terms',
					'Clicks',
					'Impressions',
					'CTR',
					'Avg. Position',
				],
			};
		},
	};
</script>
