<template>
	<div class="monsterinsights-flex monsterinsights-height100">
		<report-upsell-overlay v-if="showUpsell()" :report="text_upsell_title" :license="licenseLevel" level="plus" />
		<report-table-box v-else :title="text_affiliate_links"
			:headers="affiliate_links_headers" :rows="affiliate_links_rows"
			:emptytext="text_affiliate_links_empty" :tooltip="text_affiliate_links_tooltip" :mobile-width="1330"
		>
			<a v-if="gaLinks('affiliatelinks')" slot="button"
				:href="gaLinks('affiliatelinks')" target="_blank" class="monsterinsights-button"
				v-text="text_affiliate_links_button"
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
		name: 'WidgetReportAffiliateLinks',
		components: { ReportUpsellOverlay, ReportTableBox },
		data() {
			return {
				text_upsell_title: __( 'Publisher', process.env.VUE_APP_TEXTDOMAIN ),
				text_affiliate_links_button: __( 'View All Affiliate Links Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_affiliate_links: __( 'Top Affiliate Links', process.env.VUE_APP_TEXTDOMAIN ),
				text_affiliate_links_empty: __( 'No affiliate link clicks detected for this time period.', process.env.VUE_APP_TEXTDOMAIN ),
				text_affiliate_links_tooltip: __( 'This list shows the top affiliate links your visitors clicked on.', process.env.VUE_APP_TEXTDOMAIN ),
				affiliate_links_headers: [
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
			affiliate_links_rows() {
				let rows = [];

				if ( this.publisher.affiliatelinks ) {
					this.publisher.affiliatelinks.forEach( function( row ) {
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
