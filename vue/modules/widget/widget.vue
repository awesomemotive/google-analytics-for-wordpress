<template>
	<div :class="mainClass">
		<widget-settings ref="settings" />
		<report-re-auth v-if="reauth"></report-re-auth>
		<widget-accordion v-else />
		<div v-if="fullWidth" class="monsterinsights-fullwidth-mascot"></div>
		<div v-if="fullWidth" class="monsterinsights-fullwidth-report-title" v-text="text_overview_report"></div>
		<widget-footer v-if="!fullWidth" />
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import moment from 'moment';

	import ReportsStore from './../reports/store';
	import WidgetStore from './store';
	import WidgetAccordion from "./components/WidgetAccordion-MI_VERSION";
	import WidgetSettings from "./components/WidgetSettings-MI_VERSION";
	import WidgetFooter from "./components/WidgetFooter";
	import ReportReAuth from "../reports/components/ReportReAuth";
	export default {
		name: 'ModuleDashboardWidget',
		components: { ReportReAuth, WidgetFooter, WidgetSettings, WidgetAccordion },
		data() {
			return {
				text_overview_report: __( 'Overview Report', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				blocked: '$_app/blocked',
				blur: '$_reports/blur',
				widget_width: '$_widget/width',
				reauth: '$_reports/reauth',
			} ),
			route() {
				return this.$route.name;
			},
			mainClass() {
				let mainClass = 'monsterinsights-dashboard-widget-page';

				if ( this.blur ) {
					mainClass += ' monsterinsights-blur';
				}

				return mainClass;
			},
			fullWidth() {
				return 'regular' !== this.widget_width;
			},
		},
		created() {
			const STORE_KEY = '$_reports';
			// eslint-disable-next-line no-underscore-dangle
			if ( ! (
				STORE_KEY in this.$store._modules.root._children
			) ) {
				this.$store.registerModule( STORE_KEY, ReportsStore );
			}
			const WIDGET_KEY = '$_widget';
			// eslint-disable-next-line no-underscore-dangle
			if ( ! (
				WIDGET_KEY in this.$store._modules.root._children
			) ) {
				this.$store.registerModule( WIDGET_KEY, WidgetStore );
			}
			if ( this.$mi.widget_state && this.$mi.widget_state.interval ) {
				let endDate = moment().subtract( 1, 'day' );
				let startDate = moment( endDate ).subtract( parseInt( this.$mi.widget_state.interval ) - 1, 'day' );

				this.$store.commit( '$_reports/UPDATE_INTERVAL', this.$mi.widget_state.interval );
				this.$store.commit( '$_reports/UPDATE_DATE', {
					start: startDate.format( 'YYYY-MM-DD' ),
					end: endDate.format( 'YYYY-MM-DD' ),
				} );
			}
		},
		mounted() {
			this.$store.dispatch( '$_widget/processDefaults' ).then( () => {
				this.$nextTick( () => {
					this.$refs.settings.toggleFullWidth( true );
				} );
			} );
		},
	};
</script>
