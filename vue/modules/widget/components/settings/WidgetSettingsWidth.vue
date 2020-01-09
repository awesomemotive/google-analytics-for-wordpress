<template>
	<button v-tooltip="tooltip_data" class="monsterinsights-width-button" v-on:click="toggleFullWidth">
		<i v-if="fullWidth" class="monstericon-compress"></i>
		<i v-else class="monstericon-expand"></i>
	</button>
</template>
<script>
	import { mapGetters } from 'vuex';
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'WidgetSettingsWidth',
		data() {
			return {
				normal_sortables: '',
				widget_element: '',
				welcome_panel: '',
			};
		},
		computed: {
			...mapGetters( {
				widget_width: '$_widget/width',
				widget_reports: '$_widget/reports',
			} ),
			fullWidth: {
				set( value ) {
					let width = 'regular';
					if ( value ) {
						width = 'full';
					}
					this.$store.commit( '$_widget/UPDATE_WIDTH', width );
					this.saveState();
				},
				get() {
					return 'regular' !== this.widget_width;
				},
			},
			tooltip_data() {
				return {
					content: this.fullWidth ? __( 'Show in widget mode', process.env.VUE_APP_TEXTDOMAIN ) : __( 'Show in full-width mode', process.env.VUE_APP_TEXTDOMAIN ),
					autoHide: false,
					trigger: 'hover focus click',
				};
			},
		},
		methods: {
			toggleFullWidth( ignore ) {
				if ( true !== ignore ) {
					this.fullWidth = ! this.fullWidth;
				}
				if ( this.fullWidth ) {
					this.widget_element.classList.add( 'monsterinsights-widget-full-width' );
					this.widget_element.classList.remove( 'monsterinsights-widget-regular-width' );
					this.welcome_panel.parentNode.insertBefore( this.widget_element, this.welcome_panel );
					this.getActiveReportsData();
				} else {
					this.widget_element.classList.add( 'monsterinsights-widget-regular-width' );
					if ( true === ignore ) {
						return;
					}
					this.widget_element.classList.remove( 'monsterinsights-widget-full-width' );
					this.normal_sortables.insertBefore( this.widget_element, this.normal_sortables.firstChild );
					this.normal_sortables.classList.remove( 'empty-container' );
				}
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
			saveState() {
				this.$store.dispatch( '$_widget/saveWidgetState' );
			},
		},
		mounted() {
			this.widget_element = document.getElementById( 'monsterinsights_reports_widget' );
			this.normal_sortables = document.getElementById( 'normal-sortables' );
			this.welcome_panel = document.getElementById( 'dashboard-widgets-wrap' );
		},
	};
</script>
