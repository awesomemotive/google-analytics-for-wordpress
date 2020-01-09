<template>
	<div class="monsterinsights-flex monsterinsights-height100">
		<report-upsell-overlay v-if="showUpsell()" report="ecommerce" />
		<report-table-box v-else :title="text_sessions_to_purchase" :emptytext="text_empty_generic" :headers="sessions_to_purchase_headers" :rows="sessionsToPurchaseRows" :tooltip="text_sessions_to_purchase_tooltip" :mobile-width="1330">
			<a v-if="gaLinks('sessions')" slot="button" :href="gaLinks('sessions')" target="_blank"
				class="monsterinsights-button" v-text="text_sessions_button"
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
				text_sessions_to_purchase: __( 'Sessions to Purchase', process.env.VUE_APP_TEXTDOMAIN ),
				text_empty_generic: __( 'No data for this time period.', process.env.VUE_APP_TEXTDOMAIN ),
				text_sessions_button: __( 'View Session to Purchase Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_sessions_to_purchase_tooltip: __( 'This list shows the number of sessions it took users before they purchased a product from your website.', process.env.VUE_APP_TEXTDOMAIN ),
				sessions_to_purchase_headers: [
					__( 'Sessions', process.env.VUE_APP_TEXTDOMAIN ),
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
			sessionsToPurchaseRows() {
				let rows = [];
				if ( this.ecommerce.sessions ) {
					for ( let session in this.ecommerce.sessions ) {
						if ( this.ecommerce.sessions.hasOwnProperty( session ) ) {
							let row = this.ecommerce.sessions[session];
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
