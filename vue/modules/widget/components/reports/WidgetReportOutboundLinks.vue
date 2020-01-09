<template>
	<div class="monsterinsights-flex monsterinsights-height100">
		<report-upsell-overlay v-if="showUpsell()" report="publisher" />
		<report-table-box v-else :title="text_outbound_links" :headers="outbound_links_headers"
			:rows="outbound_links_rows" :emptytext="text_outbound_links_empty"
			:tooltip="text_outbound_links_tooltip" :mobile-width="1330"
		>
			<a v-if="gaLinks('outboundlinks')" slot="button" :href="gaLinks('outboundlinks')"
				target="_blank" class="monsterinsights-button" v-text="text_outbound_links_button"
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
		name: 'WidgetReportOutboundLinks',
		components: { ReportUpsellOverlay, ReportTableBox },
		data() {
			return {
				text_outbound_links_button: __( 'View All Outbound Links Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_outbound_links: __( 'Top Outbound Links', process.env.VUE_APP_TEXTDOMAIN ),
				text_outbound_links_empty: __( 'No outbound link clicks detected for this time period.', process.env.VUE_APP_TEXTDOMAIN ),
				text_outbound_links_tooltip: __( 'This list shows the top links clicked on your website that go to another website.', process.env.VUE_APP_TEXTDOMAIN ),
				outbound_links_headers: [
					__( 'Links', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Total Clicks', process.env.VUE_APP_TEXTDOMAIN ),
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
			outbound_links_rows() {
				let rows = [];

				if ( this.publisher.outboundlinks ) {
					this.publisher.outboundlinks.forEach( function( row ) {
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
