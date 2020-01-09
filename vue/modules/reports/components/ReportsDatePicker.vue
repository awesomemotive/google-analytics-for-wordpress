<template>
	<div class="monsterinsights-reports-datepicker">
		<div class="monsterinsights-buttons-toggle">
			<button :class="getButtonClass(7)" v-on:click="getInterval(7)" v-text="text_7_days"></button>
			<button :class="getButtonClass(30)" v-on:click="getInterval(30)" v-text="text_30_days"></button>
		</div>
		<flat-pickr v-model="local_date" :config="config" class="monsterinsights-datepicker"
			:placeholder="text_datepicker_placeholder"
			v-on:on-close="updateDates"
		></flat-pickr>
		<button class="monsterinsights-button monsterinsights-mobile-details-toggle" v-on:click="toggleMobileTables"
			v-text="text_mobile_details"
		></button>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import flatPickr from 'vue-flatpickr-component';
	import 'flatpickr/dist/flatpickr.css';
	import moment from 'moment';
	import 'moment-timezone';

	const mobileWidth = 783;
	let resizing = false;

	export default {
		name: 'ReportsDatePicker',
		data() {
			const self = this;
			return {
				config: {
					mode: 'range',
					disableMobile: 'true',
					dateFormat: 'Y-m-d',
					disable: [
						function( date ) {
							let startDate = date;
							let endDate = moment( moment().tz( self.$mi.timezone ).format( 'YYYY-MM-DD' ) );
							let duration = moment.duration( endDate.diff( startDate ) );
							let diffDays = duration.asDays();
							diffDays = diffDays + 1;

							let odate = moment( date ).tz( self.$mi.timezone );
							let rangestart = odate.subtract( diffDays, 'd' ).tz( self.$mi.timezone );

							let today = moment();
							let inrange_left = rangestart.isBetween( moment( "01-01-2005", "MM-DD-YYYY" ).tz( self.$mi.timezone ), today );
							let inrange_right = moment( date ).isBetween( moment( "01-01-2005", "MM-DD-YYYY" ).tz( self.$mi.timezone ), today );
							return ! inrange_left || ! inrange_right;
						},
					],
				},
				text_7_days: __( 'Last 7 days', process.env.VUE_APP_TEXTDOMAIN ),
				text_30_days: __( 'Last 30 days', process.env.VUE_APP_TEXTDOMAIN ),
				isMobile: window.innerWidth < mobileWidth,
			};
		},
		computed: {
			...mapGetters( {
				date: '$_reports/date',
				activeReport: '$_reports/activeReport',
				mobileTableExpanded: '$_reports/mobileTableExpanded',
			} ),
			text_datepicker_placeholder() {
				return this.isMobile ? __( 'Custom Date Range', process.env.VUE_APP_TEXTDOMAIN ) : __( 'Set Custom Date Range', process.env.VUE_APP_TEXTDOMAIN );
			},
			text_mobile_details() {
				return this.mobileTableExpanded ? __( 'Hide Details', process.env.VUE_APP_TEXTDOMAIN ) : __( 'Show Details', process.env.VUE_APP_TEXTDOMAIN );
			},
			interval: {
				set( value ) {
					this.$store.commit( '$_reports/UPDATE_INTERVAL', value );
				},
				get() {
					return this.date.interval;
				},
			},
			local_date: {
				set( value ) {
					this.$store.commit( '$_reports/UPDATE_DATE_TEXT', value );
				},
				get() {
					return this.date.text;
				},
			},
		},
		components: {
			flatPickr,
		},
		methods: {
			updateDates( selectedDates ) {
				let dates = {};

				if ( selectedDates[0] && selectedDates[1] ) {
					dates.start = this.getFormattedDate( selectedDates[0] );
					dates.end = this.getFormattedDate( selectedDates[1] );

					this.interval = false;

					document.activeElement.blur(); // Prevent refocusing the flatpickr element after the sweetalert.

					this.$store.commit( '$_reports/UPDATE_DATE', dates );
					this.$store.dispatch( '$_reports/getReportData', this.activeReport );
				}
			},
			getFormattedDate( dateToParse ) {
				if ( dateToParse instanceof Date ) {
					let month = this.addLeadingZero( dateToParse.getMonth() + 1 );
					let date = this.addLeadingZero( dateToParse.getDate() );
					dateToParse = dateToParse.getFullYear() + '-' + month + '-' + date;
				}
				return dateToParse;
			},
			addLeadingZero( number ) {
				if ( number < 10 && number > 0 ) {
					return 0 + number.toString();
				}
				return number;
			},
			getInterval( days ) {
				this.interval = days;
				let endDate = moment().subtract( 1, 'day' );
				let startDate = moment( endDate ).subtract( parseInt( days ) - 1, 'day' );

				this.$store.commit( '$_reports/UPDATE_DATE', {
					start: startDate.format( 'YYYY-MM-DD' ),
					end: endDate.format( 'YYYY-MM-DD' ),
				} );

				window.blur(); // Prevent refocusing to a tooltip.
				this.$store.dispatch( '$_reports/getReportData', this.activeReport );
				this.local_date = '';
			},
			getButtonClass( interval ) {
				let buttonClass = 'monsterinsights-button';

				if ( interval === this.interval ) {
					buttonClass += ' monsterinsights-selected-interval';
				}

				return buttonClass;
			},
			hideMobileTables() {
				if ( this.mobileTableExpanded ) {
					this.$store.commit( '$_reports/CONTRACT_TABLES' );
				}
			},
			showMobileTables() {
				if ( ! this.mobileTableExpanded ) {
					this.$store.commit( '$_reports/EXPAND_TABLES' );
				}
			},
			toggleMobileTables() {
				if ( this.mobileTableExpanded ) {
					this.hideMobileTables();
				} else {
					this.showMobileTables();
				}
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
				this.isMobile = window.innerWidth < mobileWidth;
				resizing = false;
			},
		},
		mounted() {
			window.addEventListener( 'resize', this.handleResize );
		},
		beforeDestroy: function() {
			window.removeEventListener( 'resize', this.handleResize );
		},
	};
</script>
