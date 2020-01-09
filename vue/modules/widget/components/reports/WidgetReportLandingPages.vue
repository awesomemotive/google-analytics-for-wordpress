<template>
	<div class="monsterinsights-flex monsterinsights-height100">
		<report-upsell-overlay v-if="showUpsell()" report="publisher" />
		<report-table-box v-else :title="text_landing_pages" :headers="landing_pages_headers"
			:rows="landing_pages_rows" :emptytext="text_landing_pages_empty" :tooltip="text_landing_pages_tooltip" :mobile-width="1330"
		>
			<a v-if="gaLinks('landingpages')" slot="button" :href="gaLinks('landingpages')"
				target="_blank" class="monsterinsights-button" v-text="text_landing_pages_button"
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
		name: 'WidgetReportLandingPages',
		components: { ReportUpsellOverlay, ReportTableBox },
		data() {
			return {
				text_landing_pages: __( 'Top Landing Pages', process.env.VUE_APP_TEXTDOMAIN ),
				text_landing_pages_button: __( 'View Full Top Landing Pages Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_landing_pages_empty: __( 'No landing pages tracked during this time period.', process.env.VUE_APP_TEXTDOMAIN ),
				text_landing_pages_tooltip: __( 'This list shows the top pages users first land on when visiting your website.', process.env.VUE_APP_TEXTDOMAIN ),
				landing_pages_headers: [
					__( 'Page Titles', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Visits', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Avg. Duration', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Bounce Rate', process.env.VUE_APP_TEXTDOMAIN ),
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
			landing_pages_rows() {
				let rows = [];

				if ( this.publisher.landingpages ) {
					this.publisher.landingpages.forEach( function( row ) {
						let bounce = Math.round( row.bounce * 100 ) / 100;
						bounce += '%';
						rows.push( [
							row.title,
							row.visits,
							row.duration,
							bounce,
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
