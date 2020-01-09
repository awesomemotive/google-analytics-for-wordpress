<template>
	<main class="monsterinsights-report monsterinsights-report-publishers">
		<div class="monsterinsights-report-top">
			<h2 v-text="text_publishers"></h2>
		</div>
		<div class="monsterinsights-report-row">
			<report-table-box :headers="fake_headers" :title="text_publishers" :rows="[]"></report-table-box>
		</div>
		<report-upsell-overlay report="publisher" level="plus" />
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
				text_publishers: __( 'Publishers', process.env.VUE_APP_TEXTDOMAIN ),
				fake_headers: [
					'Page Titles',
					'Visits',
					'Avg. Duration',
					'Bounce Rate',
				],
			};
		},
	};
</script>
