<template>
	<main class="monsterinsights-report monsterinsights-report-dimensions">
		<div class="monsterinsights-report-top">
			<h2 v-text="text_dimensions"></h2>
		</div>
		<div class="monsterinsights-report-row">
			<report-table-box :headers="fake_headers" :title="text_dimensions" :rows="[]"></report-table-box>
		</div>
		<report-upsell-overlay report="dimensions" />
	</main>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import ReportTableBox from "../ReportTableBox";
	import ReportUpsellOverlay from "../upsells/ReportUpsellOverlay";

	export default {
		name: 'ReportDimensions',
		components: { ReportUpsellOverlay, ReportTableBox },
		data() {
			return {
				text_dimensions_upsell: __( 'Dimensions', process.env.VUE_APP_TEXTDOMAIN ),
				text_dimensions: __( 'Dimensions Report', process.env.VUE_APP_TEXTDOMAIN ),
				fake_headers: [
					'Author',
					'Percent',
				],
			};
		},
		mounted() {
			this.$store.commit( '$_reports/ENABLE_BLUR' );
		},
	};
</script>
