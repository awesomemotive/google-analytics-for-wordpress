<template>
	<div :class="btnGroupClass()">
		<button v-if="!fullWidth"
			class="monsterinsights-btn-group-label" v-on:click.stop="toggleBtnDropdown"
			v-text="sprintf( text_last_days, interval )"
		></button>
		<div v-if="btnDropdownVisible" v-click-outside="hideBtnDropdown" class="monsterinsights-btn-group-list">
			<button :class="btnGroupButtonClass(7)" v-on:click="getInterval(7)"
				v-text="sprintf( text_last_days, 7 )"
			></button>
			<button :class="btnGroupButtonClass(30)" v-on:click="getInterval(30)"
				v-text="sprintf( text_last_days, 30 )"
			></button>
		</div>
	</div>
</template>
<script>
	import moment from 'moment';
	import { mapGetters } from 'vuex';
	import { __, sprintf } from '@wordpress/i18n';

	export default {
		name: 'WidgetSettingsInterval',
		data() {
			return {
				btnDropdown: false,
				selectedInterval: 30,
				text_last_days: __( 'Last %s days', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				widget_width: '$_widget/width',
				widget_reports: '$_widget/reports',
				date: '$_reports/date',
				activeReport: '$_reports/activeReport',
			} ),
			interval: {
				set( value ) {
					this.$store.commit( '$_reports/UPDATE_INTERVAL', value );
					this.saveState();
				},
				get() {
					return this.date.interval;
				},
			},
			fullWidth() {
				return 'regular' !== this.widget_width;
			},
			btnDropdownVisible: {
				set( value ) {
					this.btnDropdown = value;
				},
				get() {
					if ( this.fullWidth ) {
						return true;
					}
					return this.btnDropdown;
				},
			},
		},
		methods: {
			btnGroupClass() {
				let btnGroupClass = 'monsterinsights-btn-group';

				if ( this.btnDropdownVisible ) {
					btnGroupClass += ' monsterinsights-btn-group-open';
				}

				return btnGroupClass;
			},
			hideBtnDropdown() {
				this.btnDropdownVisible = false;
			},
			toggleBtnDropdown() {
				this.btnDropdownVisible = ! this.btnDropdownVisible;
			},
			btnGroupButtonClass( interval ) {
				let btnGroupButtonClass = 'monsterinsights-btn-group-list-button';

				if ( interval === this.interval ) {
					btnGroupButtonClass += ' monsterinsights-btn-group-list-button-selected';
				}

				return btnGroupButtonClass;
			},
			getInterval( days ) {
				const self = this;
				this.hideBtnDropdown();
				this.interval = days;
				let endDate = moment().subtract( 1, 'day' );
				let startDate = moment( endDate ).subtract( parseInt( days ) - 1, 'day' );

				this.$store.commit( '$_reports/UPDATE_DATE', {
					start: startDate.format( 'YYYY-MM-DD' ),
					end: endDate.format( 'YYYY-MM-DD' ),
				} );

				window.blur(); // Prevent refocusing to a tooltip.
				if ( this.fullWidth ) {
					this.getActiveReportsData();
					return;
				}
				self.$store.commit( '$_widget/UPDATE_LOADED', false );
				this.$store.dispatch( '$_reports/getReportData', this.activeReport ).then( function() {
					self.$store.commit( '$_widget/UPDATE_LOADED', true );
				} );
			},
			saveState() {
				this.$store.dispatch( '$_widget/saveWidgetState' );
			},
			getReportData( key ) {
				const self = this;
				self.$store.commit( '$_widget/UPDATE_LOADED', false );
				this.$store.dispatch( '$_reports/getReportData', this.widget_reports[key].type ).then( function() {
					self.$store.commit( '$_widget/UPDATE_LOADED', true );
				} );
			},
			getActiveReportsData() {
				const self = this;
				let types = {};
				for ( let key in this.widget_reports ) {
					if ( this.widget_reports.hasOwnProperty( key ) && this.widget_reports[key].enabled ) {
						types[this.widget_reports[key].type] = 1;
					}
				}
				let types_count = Object.keys(types).length;
				let i = 0;
				for ( let type in types ) {
					if ( types.hasOwnProperty( type ) ) {
						i++;
						self.$store.commit( '$_widget/UPDATE_LOADED', false );
						this.$store.dispatch( '$_reports/getReportData', type ).then( function() {
							if ( i === types_count ) {
								self.$store.commit( '$_widget/UPDATE_LOADED', true );
							}
						} );
					}
				}
			},
			sprintf,
		},
	};
</script>
