<template>
	<div class="monsterinsights-ecommerce-overview">
		<div v-if="ecommerce.infobox" class="monsterinsights-report-row monsterinsights-report-infobox-row monsterinsights-report-2-columns">
			<report-infobox :title="text_add_to_cart" :value="infoboxAddToCartData.value"
				:change="infoboxAddToCartData.change" :color="infoboxAddToCartData.color"
				:direction="infoboxAddToCartData.direction" :days="infoboxRange"
				:tooltip="text_add_to_cart_tooltip"
			/>
			<report-infobox :title="text_removed_from_cart" :value="infoboxRemFromCartData.value"
				:change="infoboxRemFromCartData.change" :color="infoboxRemFromCartData.color"
				:direction="infoboxRemFromCartData.direction" :days="infoboxRange"
				:tooltip="text_removed_from_cart_tooltip"
			/>
		</div>
		<report-upsell-overlay v-if="showUpsell()" report="ecommerce" />
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import ReportInfobox from "../../../reports/components/ReportInfobox";
	import ReportUpsellOverlay from "../../../reports/components/upsells/ReportUpsellOverlay";

	export default {
		name: 'WidgetReportAddRemove',
		components: { ReportUpsellOverlay, ReportInfobox },
		data() {
			return {
				text_add_to_cart: __( 'Total Add to Carts', process.env.VUE_APP_TEXTDOMAIN ),
				text_removed_from_cart: __( 'Total Removed from Cart', process.env.VUE_APP_TEXTDOMAIN ),
				text_add_to_cart_tooltip: __( 'The number of times products on your site were added to the cart.', process.env.VUE_APP_TEXTDOMAIN ),
				text_removed_from_cart_tooltip: __( 'The number of times products on your site were removed from the cart.', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				ecommerce: '$_reports/ecommerce',
				license: '$_license/license',
				license_network: '$_license/license_network',
			} ),
			infoboxAddToCartData() {
				return this.infoboxData( 'addtocart' );
			},
			infoboxRemFromCartData() {
				return this.infoboxData( 'remfromcart' );
			},
			infoboxRange() {
				return this.ecommerce.infobox && this.ecommerce.infobox.range ? this.ecommerce.infobox.range : 0;
			},
			licenseLevel() {
				return this.$mi.network ? this.license_network.type : this.license.type;
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
