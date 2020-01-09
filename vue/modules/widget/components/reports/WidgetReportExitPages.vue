<template>
	<div class="monsterinsights-flex monsterinsights-height100">
		<report-upsell-overlay v-if="showUpsell()" report="publisher" />
		<report-table-box v-else :title="text_exit_pages" :headers="exit_pages_headers"
			:rows="exit_pages_rows" :emptytext="text_exit_pages_empty" :tooltip="text_exit_pages_tooltip"
			:mobile-width="1330"
		>
			<a v-if="gaLinks('exitpages')" slot="button" :href="gaLinks('exitpages')"
				target="_blank" class="monsterinsights-button" v-text="text_exit_pages_button"
			></a>
		</report-table-box>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import ReportTableBox from "../../../reports/components/ReportTableBox";
	import ReportUpsellOverlay from "../../../reports/components/upsells/ReportUpsellOverlay";

	export default {
		name: 'WidgetReportExitPages',
		components: { ReportUpsellOverlay, ReportTableBox },
		data() {
			return {
				text_exit_pages: __( 'Top Exit Pages', process.env.VUE_APP_TEXTDOMAIN ),
				text_exit_pages_button: __( 'View Full Top Exit Pages Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_exit_pages_empty: __( 'No exit pages tracked during this time period.', process.env.VUE_APP_TEXTDOMAIN ),
				text_exit_pages_tooltip: __( 'This list shows the top pages users exit your website from.', process.env.VUE_APP_TEXTDOMAIN ),
				exit_pages_headers: [
					__( 'Page Titles', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Exits', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Page Views', process.env.VUE_APP_TEXTDOMAIN ),
					__( '% of Exits', process.env.VUE_APP_TEXTDOMAIN ),
				],
			};
		},
		computed: {
			...mapGetters( {
				publisher: '$_reports/publisher',
				license: '$_license/license',
				license_network: '$_license/license_network',
			} ),
			licenseLevel() {
				return this.$mi.network ? this.license_network.type : this.license.type;
			},
			exit_pages_rows() {
				let rows = [];

				if ( this.publisher.exitpages ) {
					this.publisher.exitpages.forEach( function( row ) {
						let exitrate = Math.round( row.exitrate * 100 ) / 100;
						exitrate += '%';
						rows.push( [
							row.title,
							row.exits,
							row.pageviews,
							exitrate,
						] );
					} );
				}

				return rows;
			},
		},
		methods: {
			showUpsell() {
				let show = 'basic' === this.licenseLevel;
				if ( show ) {
					this.$emit( 'upsellshown' );
				}
				return show;
			},
			gaLinks( name ) {
				if ( 'undefined' !== typeof this.publisher.galinks && this.publisher.galinks[name] ) {
					return this.publisher.galinks[name];
				}

				return false;
			},
		},
	};
</script>
