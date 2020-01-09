<template>
	<div class="monsterinsights-flex monsterinsights-height100">
		<report-upsell-overlay v-if="showUpsell()" report="ecommerce" />
		<report-table-box v-else :title="text_top_conversions" :emptytext="text_empty_top_conversions"
			:headers="top_conversions_headers" :rows="topConversionsrows"
			:tooltip="text_top_conversions_tooltip"
			:mobile-width="1330"
		>
			<a v-if="gaLinks('conversions')" slot="button" :href="gaLinks('conversions')" target="_blank"
				class="monsterinsights-button" v-text="text_conversions_button"
			></a>
		</report-table-box>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import ReportUpsellOverlay from "../../../reports/components/upsells/ReportUpsellOverlay";
	import ReportTableBox from "../../../reports/components/ReportTableBox";

	export default {
		name: 'WidgetReportTopConversions',
		components: { ReportTableBox, ReportUpsellOverlay },
		data() {
			return {
				text_top_conversions: __( 'Top Conversion Sources', process.env.VUE_APP_TEXTDOMAIN ),
				text_empty_top_conversions: __( 'No conversion sources tracked during this time period.', process.env.VUE_APP_TEXTDOMAIN ),
				text_conversions_button: __( 'View Top Conversions Sources Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_conversions_tooltip: __( 'This list shows the top referral websites in terms of product revenue.', process.env.VUE_APP_TEXTDOMAIN ),
				top_conversions_headers: [
					__( 'Sources', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Visits', process.env.VUE_APP_TEXTDOMAIN ),
					__( '% of Visits', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Revenue', process.env.VUE_APP_TEXTDOMAIN ),
				],
			};
		},
		computed: {
			...mapGetters( {
				ecommerce: '$_reports/ecommerce',
				license: '$_license/license',
				license_network: '$_license/license_network',
			} ),
			licenseLevel() {
				return this.$mi.network ? this.license_network.type : this.license.type;
			},
			topConversionsrows() {
				let rows = [];

				if ( this.ecommerce.conversions ) {
					this.ecommerce.conversions.forEach( function( row ) {
						let percent = Math.round( row.percent * 100 ) / 100;
						let revenue = Math.round( row.revenue * 100 ) / 100;
						percent += '%';
						rows.push( [
							'<img class="monsterinsights-reports-referral-icon"  src="https://www.google.com/s2/favicons?domain=' + row.url + '" /> ' + row.url,
							row.sessions,
							percent,
							revenue,
						] );
					} );
				}

				return rows;
			},
		},
		methods: {
			showUpsell() {
				let show = 'plus' === this.licenseLevel || 'basic' === this.licenseLevel;
				if ( show ) {
					this.$emit( 'upsellshown' );
				}
				return show;
			},
			gaLinks( name ) {
				if ( 'undefined' !== typeof this.ecommerce.galinks && this.ecommerce.galinks[name] ) {
					return this.ecommerce.galinks[name];
				}

				return false;
			},
		},
	};
</script>
