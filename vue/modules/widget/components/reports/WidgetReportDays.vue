<template>
	<div class="monsterinsights-flex monsterinsights-height100">
		<report-upsell-overlay v-if="showUpsell()" report="ecommerce" />
		<report-table-box v-else :title="text_time_to_purchase"
			:emptytext="text_empty_generic" :headers="time_to_purchase_headers" :rows="timeToPurchaseRows"
			:tooltip="text_time_to_purchase_tooltip" :mobile-width="1330"
		>
			<a v-if="gaLinks('days')" slot="button" :href="gaLinks('days')" target="_blank"
				class="monsterinsights-button" v-text="text_days_button"
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
		name: 'WidgetReportDays',
		components: { ReportTableBox, ReportUpsellOverlay },
		data() {
			return {
				text_time_to_purchase: __( 'Time to Purchase', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_products: __( 'Top Products', process.env.VUE_APP_TEXTDOMAIN ),
				text_empty_generic: __( 'No data for this time period.', process.env.VUE_APP_TEXTDOMAIN ),
				text_days_button: __( 'View Time to Purchase Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_time_to_purchase_tooltip: __( 'This list shows how many days from first visit it took users to purchase products from your site.', process.env.VUE_APP_TEXTDOMAIN ),
				time_to_purchase_headers: [
					__( 'Days', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Transactions', process.env.VUE_APP_TEXTDOMAIN ),
					__( '% of Total', process.env.VUE_APP_TEXTDOMAIN ),
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
			timeToPurchaseRows() {
				let rows = [];
				if ( this.ecommerce.days ) {
					for ( let day in this.ecommerce.days ) {
						if ( this.ecommerce.days.hasOwnProperty( day ) ) {
							let row = this.ecommerce.days[day];
							let percent = Math.round( row.percent * 100 ) / 100;
							let transactions = Math.round( row.transactions * 100 ) / 100;
							percent += '%';
							rows.push( [
								'&nbsp;',
								transactions,
								percent,
							] );
						}
					}
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
