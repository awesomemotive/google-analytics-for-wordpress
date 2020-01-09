<template>
	<div class="monsterinsights-flex monsterinsights-height100">
		<report-upsell-overlay v-if="showUpsell()" report="publisher" />
		<report-table-box v-else :title="text_downloads" :headers="downloads_headers"
			:rows="downloads_rows" :emptytext="text_downloads_empty" :tooltip="text_download_links_tooltip" :mobile-width="1330"
		>
			<a v-if="gaLinks('downloadlinks')" slot="button" :href="gaLinks('downloadlinks')"
				target="_blank" class="monsterinsights-button" v-text="text_download_links_button"
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
		name: 'WidgetReportDownloadLinks',
		components: { ReportUpsellOverlay, ReportTableBox },
		data() {
			return {
				text_download_links_button: __( 'View All Download Links Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_downloads: __( 'Top Download Links', process.env.VUE_APP_TEXTDOMAIN ),
				text_downloads_empty: __( 'No download link clicks detected for this time period.', process.env.VUE_APP_TEXTDOMAIN ),
				text_download_links_tooltip: __( 'This list shows the download links your visitors clicked the most.', process.env.VUE_APP_TEXTDOMAIN ),
				downloads_headers: [
					__( 'Link Label', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Clicks', process.env.VUE_APP_TEXTDOMAIN ),
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
			downloads_rows() {
				let rows = [];

				if ( this.publisher.downloadlinks ) {
					this.publisher.downloadlinks.forEach( function( row ) {
						rows.push( [
							row.title,
							row.clicks,
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
