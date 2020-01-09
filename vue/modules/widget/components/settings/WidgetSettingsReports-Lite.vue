<template>
	<div class="monsterinsights-widget-dropdown">
		<button class="monsterinsights-widget-cog" type="button" v-on:click.stop="toggleDropdown">
			<i class="monstericon-cog"></i>
		</button>
		<div v-if="dropdownVisible" v-click-outside="hideDropdown" class="monsterinsights-widget-dropdown-content">
			<span v-text="text_settings_overview"></span>
			<div v-for="(report, key) in getReportSettings('overview')" :key="key"
				class="monsterinsights-widget-setting "
			>
				<label :class="report.enabled ? 'monsterinsights-checked' : ''" tabindex="0"
					v-on:click.prevent="toggleReport( $event, key)" v-on:keyup.enter="toggleReport( $event, key)"
					v-on:keyup.space="toggleReport( $event, key)"
				>
					<input type="checkbox" :checked="report.enabled" /> {{ report.name }}
				</label>
			</div>
			<span v-text="text_settings_publisher"></span>
			<div v-for="(report, key) in getReportSettings('publisher')" :key="key"
				class="monsterinsights-widget-setting "
			>
				<label v-tooltip.left="tooltip_data" class="monsterinsights-faded" tabindex="0">
					<input type="checkbox" :checked="report.enabled" /> {{ report.name }}
				</label>
			</div>
			<span v-text="text_settings_ecommerce"></span>
			<div v-for="(report, key) in getReportSettings('ecommerce')" :key="key"
				class="monsterinsights-widget-setting "
			>
				<label v-tooltip.left="tooltip_data" class="monsterinsights-faded" tabindex="0">
					<input type="checkbox" :checked="report.enabled" /> {{ report.name }}
				</label>
			</div>
			<widget-settings-hide />
		</div>
	</div>
</template>
<script>
	import Vue from 'vue';
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import WidgetSettingsHide from "./WidgetSettingsHide";

	Vue.directive( 'click-outside', {
		bind: function( el, binding, vnode ) {
			el.clickOutsideEvent = function( event ) {
				if ( ! (
					el === event.target || el.contains( event.target )
				) ) {// and if it did, call method provided in attribute value
					vnode.context[binding.expression]( event );
				}
			};
			document.body.addEventListener( 'click', el.clickOutsideEvent );
		},
		unbind: function( el ) {
			document.body.removeEventListener( 'click', el.clickOutsideEvent );
		},
	} );

	export default {
		name: 'WidgetSettingsReports',
		components: { WidgetSettingsHide },
		data() {
			return {
				dropdownVisible: false,
				text_settings_overview: __( 'Show Overview Reports', process.env.VUE_APP_TEXTDOMAIN ),
				text_settings_publisher: __( 'Show Publishers Reports', process.env.VUE_APP_TEXTDOMAIN ),
				text_settings_ecommerce: __( 'Show eCommerce Reports', process.env.VUE_APP_TEXTDOMAIN ),
				tooltip_data: {
					content: __( 'Available in PRO version', process.env.VUE_APP_TEXTDOMAIN ),
					autoHide: false,
					trigger: 'hover focus click',
				},
			};
		},
		computed: {
			...mapGetters( {
				widget_reports: '$_widget/reports',
			} ),
			reportSettings() {
				let reports = {};
				for ( let index in this.widget_reports ) {
					if ( this.widget_reports.hasOwnProperty( index ) && 'overview' !== index ) {
						reports[index] = this.widget_reports[index];
					}
				}
				return reports;
			},
		},
		methods: {
			toggleReport( event, key ) {
				if ( this.widget_reports[key].enabled ) {
					this.$store.commit( '$_widget/DISABLE_REPORT', key );
				} else {
					this.$store.commit( '$_widget/ENABLE_REPORT', key );
					if ( this.fullWidth ) {
						this.getReportData( key );
					}
				}
				this.saveState();
			},
			getReportSettings( type ) {
				let reports = {};
				for ( let index in this.reportSettings ) {
					if ( this.reportSettings.hasOwnProperty( index ) && 'overview' !== index && type === this.reportSettings[index].type ) {
						reports[index] = this.reportSettings[index];
					}
				}
				return reports;
			},
			toggleDropdown() {
				this.dropdownVisible = ! this.dropdownVisible;
			},

			hideDropdown() {
				this.dropdownVisible = false;
			},
			saveState() {
				this.$store.dispatch( '$_widget/saveWidgetState' );
			},
		},
	};
</script>
