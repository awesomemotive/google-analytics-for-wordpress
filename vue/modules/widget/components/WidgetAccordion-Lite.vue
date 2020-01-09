<template>
	<div class="monsterinsights-widget-accordion monsterinsights-widget-accordion-lite">
		<div v-for="(report, key) in widgetReports" :key="key" :class="reportClass(key)">
			<div v-if="showReportTitle( report.enabled )" :class="toggleClass(key)" tabindex="0"
				v-on:click.prevent="toggle($event, key)" v-on:keyup.space="toggle($event, key)"
				v-on:keyup.enter="toggle($event, key)"
			>
				<h2 class="monsterinsights-widget-report-title">
					<span v-text="report.name"></span>
					<settings-info-tooltip v-if="report.tooltip" :content="report.tooltip" />
				</h2>
			</div>
			<div v-if="showReport( key, report )" class="monsterinsights-widget-content">
				<widget-report-error v-if="error[report.type]" :error="error[report.type]" />
				<WidgetReportOverview v-else-if="'overview'===key && loaded" />
				<report-upsell-overlay v-else-if="loaded" :report="report.type" />
				<div v-else class="monsterinsights-widget-loading"></div>
			</div>
		</div>
		<widget-reports-link v-if="!widgetFullWidth" />
	</div>
</template>
<script>
	import { mapGetters } from 'vuex';
	import WidgetReportOverview from "./reports/WidgetReportOverview-Lite";
	import SettingsInfoTooltip from "../../settings/components/SettingsInfoTooltip";
	import WidgetReportError from "./WidgetReportError";
	import ReportUpsellOverlay from "../../reports/components/upsells/ReportUpsellOverlay";
	import WidgetReportsLink from "./WidgetReportsLink";

	let upsellShown = false;
	let resizing = false;
	let upsellChecked = false;

	export default {
		name: 'WidgetAccordion',
		components: {
			WidgetReportsLink,
			ReportUpsellOverlay,
			WidgetReportError,
			SettingsInfoTooltip,
			WidgetReportOverview,
		},
		props: {
			mobileWidth: {
				default: 782,
				type: Number,
			},
		},
		data() {
			return {
				activeReport: 'overview',
				reportsWithUpsell: {},
				isMobile: false,
			};
		},
		computed: {
			...mapGetters( {
				widget_reports: '$_widget/reports',
				widget_width: '$_widget/width',
				loaded: '$_widget/loaded',
				error: '$_widget/error',
			} ),
			widgetFullWidth() {
				return 'regular' !== this.widget_width;
			},
			widgetReports() {
				let reports = {};
				let typesError = {};
				upsellShown = false;
				for ( let report in this.widget_reports ) {
					if ( this.widget_reports.hasOwnProperty( report ) && this.widget_reports[report].enabled ) {
						if ( this.widgetFullWidth ) {
							if ( 'undefined' !== typeof this.reportsWithUpsell[report] ) {
								if ( false === upsellShown ) {
									upsellShown = true;
								} else {
									continue;
								}
							}
							if ( this.error[this.widget_reports[report]['type']] ) {
								let type = this.widget_reports[report]['type'];
								typesError[type] = typesError[type] ? typesError[type] + 1 : 1;
								if ( typesError[type] > 1 ) {
									continue;
								}
							}
						}
						reports[report] = this.widget_reports[report];
					}
				}
				return reports;
			},
		},
		methods: {
			maybeHideUpsell( key ) {
				this.$set( this.reportsWithUpsell, key, 1 );
				if ( this.widgetFullWidth && ! upsellChecked ) {
					upsellChecked = true;
					this.$forceUpdate();
				}
			},
			toggle( event, key ) {
				const report_type = this.widget_reports[key].type;
				const self = this;
				self.$store.commit( '$_widget/UPDATE_LOADED', false );
				self.$store.commit( '$_widget/SET_ERROR', {
					report: report_type,
				} );
				this.$store.dispatch( '$_reports/getReportData', report_type ).then( function() {
					self.$store.commit( '$_widget/UPDATE_LOADED', true );
				} );
				this.activeReport = key === this.activeReport ? '' : key;
				if ( '' !== this.activeReport ) {
					this.scrollIntoView( event.target );
				}
			},
			toggleClass( key ) {
				let toggleClass = 'monsterinsights-widget-toggle';

				if ( this.activeReport === key ) {
					toggleClass += ' monsterinsights-widget-toggle-active';
				}

				return toggleClass;
			},
			showReport( key, report ) {
				if ( this.widgetFullWidth && report.enabled && ! this.isMobile ) {
					return true;
				}
				return this.activeReport === key;
			},
			reportClass( key ) {
				return 'monsterinsights-widget-report-element monsterinsights-widget-report-' + key;
			},
			scrollIntoView( element ) {
				this.$nextTick( () => {
					let bounds = element.getBoundingClientRect();
					window.scrollTo( {
						top: bounds.top - 50 + pageYOffset,
						left: 0,
						behavior: 'smooth',
					} );
				} );
			},
			showReportTitle( enabled ) {
				if ( this.widgetFullWidth ) {
					return true;
				}

				return enabled;
			},
			handleResize() {
				if ( ! resizing ) {
					resizing = true;

					if ( window.requestAnimationFrame ) {
						window.requestAnimationFrame( this.resizeCallback );
					} else {
						setTimeout( this.resizeCallback, 66 );
					}
				}
			},
			resizeCallback() {
				this.isMobile = window.innerWidth < this.mobileWidth;
				resizing = false;
			},
		},
		mounted() {
			const self = this;
			this.$store.dispatch( '$_reports/getReportData', 'overview' ).then( function() {
				self.$store.commit( '$_widget/UPDATE_LOADED', true );
				self.$forceUpdate();
			} );
			window.addEventListener( 'resize', this.handleResize );
			this.handleResize();
		},
		beforeDestroy: function() {
			window.removeEventListener( 'resize', this.handleResize );
		},
	};
</script>
