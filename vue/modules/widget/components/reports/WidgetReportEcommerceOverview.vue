<template>
	<div class="monsterinsights-ecommerce-overview">
		<report-upsell-overlay v-if="showUpsell()" report="ecommerce" />
		<div v-else class="monsterinsights-report-row monsterinsights-report-infobox-row">
			<report-infobox :title="text_conversion_rate" :value="infoboxConversionRateData.value"
				:change="infoboxConversionRateData.change" :color="infoboxConversionRateData.color"
				:direction="infoboxConversionRateData.direction" :days="infoboxRange"
				:tooltip="text_conversion_rate_tooltip"
			/>
			<report-infobox :title="text_transactions" :value="infoboxTransactionsData.value"
				:change="infoboxTransactionsData.change" :color="infoboxTransactionsData.color"
				:direction="infoboxTransactionsData.direction" :days="infoboxRange"
				:tooltip="text_transactions_tooltip"
			/>
			<report-infobox :title="text_revenue" :value="infoboxRevenueData.value"
				:change="infoboxRevenueData.change" :color="infoboxRevenueData.color"
				:direction="infoboxRevenueData.direction" :days="infoboxRange"
				:tooltip="text_revenue_tooltip"
			/>
			<report-infobox :title="text_average_order_value" :value="infoboxOrderValueData.value"
				:change="infoboxOrderValueData.change" :color="infoboxOrderValueData.color"
				:direction="infoboxOrderValueData.direction" :days="infoboxRange"
				:tooltip="text_average_order_value_tooltip"
			/>
		</div>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import ReportInfobox from "../../../reports/components/ReportInfobox";
	import ReportUpsellOverlay from "../../../reports/components/upsells/ReportUpsellOverlay";

	export default {
		name: 'WidgetReportEcommerceOverview',
		components: { ReportUpsellOverlay, ReportInfobox },
		data() {
			return {
				text_conversion_rate: __( 'Conversion Rate', process.env.VUE_APP_TEXTDOMAIN ),
				text_transactions: __( 'Transactions', process.env.VUE_APP_TEXTDOMAIN ),
				text_revenue: __( 'Revenue', process.env.VUE_APP_TEXTDOMAIN ),
				text_average_order_value: __( 'Avg. Order Value', process.env.VUE_APP_TEXTDOMAIN ),
				text_conversion_rate_tooltip: __( 'The percentage of website sessions resulting in a transaction.', process.env.VUE_APP_TEXTDOMAIN ),
				text_transactions_tooltip: __( 'The number of orders on your website.', process.env.VUE_APP_TEXTDOMAIN ),
				text_revenue_tooltip: __( 'The total of the orders placed.', process.env.VUE_APP_TEXTDOMAIN ),
				text_average_order_value_tooltip: __( 'The average amount of the orders placed on your website.', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				ecommerce: '$_reports/ecommerce',
				license: '$_license/license',
				license_network: '$_license/license_network',
			} ),
			infoboxConversionRateData() {
				let values = this.infoboxData( 'conversionrate' );
				if ( values.value ) {
					values.value = Math.round( values.value * 100 ) / 100;
					values.value += '%';
				}
				return values;
			},
			infoboxTransactionsData() {
				return this.infoboxData( 'transactions' );
			},
			infoboxRevenueData() {
				let values = this.infoboxData( 'revenue' );
				if ( values.value ) {
					values.value = Math.round( values.value * 100 ) / 100;
					values.value = values.value.toString();
				}
				return values;
			},
			infoboxOrderValueData() {
				let values = this.infoboxData( 'ordervalue' );
				if ( values.value ) {
					values.value = Math.round( values.value * 100 ) / 100;
					values.value = values.value.toString();
				}
				return values;
			},
			licenseLevel() {
				return this.$mi.network ? this.license_network.type : this.license.type;
			},
			infoboxRange() {
				return this.ecommerce.infobox && this.ecommerce.infobox.range ? this.ecommerce.infobox.range : 0;
			},
		},
		methods: {
			infoboxData( type, reversed = false ) {
				let preparedData = {};
				if ( this.ecommerce.infobox && this.ecommerce.infobox[type] ) {
					preparedData.change = this.ecommerce.infobox[type]['prev'];
					preparedData.value = this.ecommerce.infobox[type]['value'].toString();
					if ( 0 === this.ecommerce.infobox[type]['prev'] ) {
						preparedData.direction = '';
					} else if ( this.ecommerce.infobox[type]['prev'] > 0 ) {
						preparedData.direction = 'up';
						preparedData.color = 'green';
					} else {
						preparedData.direction = 'down';
						preparedData.color = 'red';
					}
				}

				if ( reversed ) {
					if ( 'down' === preparedData.direction ) {
						preparedData.color = 'green';
					} else {
						preparedData.color = 'red';
					}
				}

				return preparedData;
			},
			showUpsell() {
				let show = 'plus' === this.licenseLevel || 'basic' === this.licenseLevel;
				if ( show ) {
					this.$emit( 'upsellshown' );
				}
				return show;
			},
		},
	};
</script>
