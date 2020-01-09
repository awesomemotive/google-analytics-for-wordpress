<template>
	<li id="wp-admin-bar-monsterinsights_frontend_button" class="monsterinsights-adminbar-menu-item">
		<div :class="toggleButtonClass" v-on:click="toggleStatsVisibility" v-html="text_insights"></div>
		<div v-if="statsVisible" class="monsterinsights-frontend-stats">
			<frontend-no-auth v-if="noauth" />
			<widget-report-error v-else-if="error" :error="error" />
			<frontend-stats-content v-else />
			<div v-if="! loaded" class="monsterinsights-frontend-stats-loading">
				<span class="monsterinsights-frontend-spinner"></span>
			</div>
			<frontend-powered-by />
		</div>
	</li>
</template>
<script>
	import FrontendStore from './store';
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import FrontendStatsContent from "./components/FrontendStatsContent-MI_VERSION";
	import ReportsStore from './../reports/store';
	import WidgetReportError from "../widget/components/WidgetReportError";
	import FrontendPoweredBy from "./components/FrontendPoweredBy";
	import FrontendNoAuth from "./components/FrontendNoAuth";

	export default {
		name: 'ModuleFrontendReports',
		components: { FrontendNoAuth, FrontendPoweredBy, WidgetReportError, FrontendStatsContent },
		data() {
			return {
				text_insights: '<span class="ab-icon dashicons-before dashicons-chart-bar"></span><span class="monsterinsights-admin-bar-handle-text">' + __( 'Insights', process.env.VUE_APP_TEXTDOMAIN ) + '</span>',
				statsVisible: false,
				page_id: this.$mi.page_id,
			};
		},
		created() {
			const REPORTS_KEY = '$_reports';
			// eslint-disable-next-line no-underscore-dangle
			if ( ! (
				REPORTS_KEY in this.$store._modules.root._children
			) ) {
				this.$store.registerModule( REPORTS_KEY, ReportsStore );
			}
			const WIDGET_KEY = '$_frontend';
			// eslint-disable-next-line no-underscore-dangle
			if ( ! (
				WIDGET_KEY in this.$store._modules.root._children
			) ) {
				this.$store.registerModule( WIDGET_KEY, FrontendStore );
			}
		},
		computed: {
			...mapGetters( {
				reportdata: '$_frontend/pageinsights',
				loaded: '$_frontend/loaded',
				error: '$_frontend/error',
				noauth: '$_frontend/noauth',
			} ),
			toggleButtonClass() {
				let buttonClass = 'ab-item ab-empty-item monsterinsights-toggle';

				if ( this.statsVisible ) {
					buttonClass += ' monsterinsights-toggle-active';
				}

				return buttonClass;
			},
		},
		methods: {
			toggleStatsVisibility() {
				this.statsVisible = ! this.statsVisible;
			},
		},
	};
</script>
