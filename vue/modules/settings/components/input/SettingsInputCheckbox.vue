<template>
	<div :class="componentClass">
		<label :class="disabled ? 'monsterinsights-styled-checkbox-faux' : ''" v-on:click.prevent="stopClick" v-on:keyup.enter="stopClick" v-on:keyup.space="stopClick">
			<span v-tooltip="tooltip_data" :class="labelClass()" :tabindex="faux ? '' : 0"></span>
			<input v-model="checked" type="checkbox" :name="name" :disabled="disabled" />
			<span class="monsterinsights-checkbox-label" v-html="label"></span>
			<span v-if="description" class="monsterinsights-checkbox-description" v-html="description"></span>
			<settings-info-tooltip v-if="tooltip" :content="tooltip" />
			<span v-if="hasCollapsibleSlot" class="monsterinsights-settings-input-toggle-collapsible" role="button" v-on:click="toggleCollapsible" v-on:keyup.enter="toggleCollapsible" v-on:keyup.space="toggleCollapsible">
				<i :class="iconClass" tabindex="0" onkeypress="if(event.keyCode==32||event.keyCode==13){return false;};"></i>
			</span>
		</label>
		<slide-down-up>
			<div v-if="slotCollapsibleVisible && hasCollapsibleSlot" class="monsterinsights-collapsible">
				<div v-if="hasCollapsibleSlot" class="monsterinsights-separator"></div>
				<div class="monsterinsights-collapsible-content">
					<slot name="collapsible"></slot>
				</div>
			</div>
		</slide-down-up>
	</div>
</template>

<script>
	import { mapGetters } from 'vuex';
	import SettingsInfoTooltip from '../SettingsInfoTooltip';
	import SlideDownUp from '../../../../components/helper/SlideDownUp';

	export default {
		name: 'SettingsInputCheckbox',
		components: { SlideDownUp, SettingsInfoTooltip },
		props: {
			name: String,
			label: String,
			description: String,
			tooltip: String,
			faux: Boolean,
			faux_tooltip: String,
			faux_tooltip_off: String,
			valueOn: String,
			valueOff: String,
			default: {
				type: Boolean,
				default: true,
			},
		},
		data() {
			return {
				is_loading: false,
				has_error: false,
				slotCollapsibleVisible: false,
			};
		},
		computed: {
			...mapGetters({
				settings: '$_settings/settings',
				auth: '$_auth/auth',
			} ),
			has_ua() {
				let is_authed = this.auth.network_ua ? this.auth.network_ua : this.auth.ua;
				if ( ! is_authed ) {
					is_authed = this.auth.network_manual_ua ? this.auth.network_manual_ua : this.auth.manual_ua;
				}
				return '' !== is_authed;
			},
			hasCollapsibleSlot() {
				return this.$slots['collapsible'];
			},
			iconClass() {
				let icon_class = 'monstericon-arrow';
				if ( this.slotCollapsibleVisible ) {
					icon_class += ' monstericon-down';
				}
				return icon_class;
			},
			componentClass() {
				let component_class = 'monsterinsights-settings-input-checkbox';

				if ( this.$slots['collapsible']) {
					component_class += ' has-collapsible';
				}
				return component_class;
			},
			checked: {
				get() {
					let value = this.valueOn ? this.valueOn === this.settings[this.name] : this.settings[this.name];
					return this.faux ? this.default : value;
				},
				set( checked ) {
					let value = this.valueOff ? this.valueOff : false;
					if ( checked ) {
						value = this.valueOn ? this.valueOn : true;
					}
					this.updateSetting( value );
				},
			},
			tooltip_data() {
				return {
					content: this.faux_tooltip_text,
					autoHide: false,
					trigger: 'hover focus click',
				};
			},
			faux_tooltip_text() {
				if ( ! this.has_ua ) {
					return this.$mi_need_to_auth;
				}
				return this.checked ? this.faux_tooltip : this.faux_tooltip_off;
			},
			disabled() {
				return this.has_ua ? this.faux : true;
			},
		},
		watch: {
			checked: function( current ) {
				this.slotCollapsibleVisible = current;
			},
		},
		methods: {
			stopClick: function( e ) {
				if ( ! e.target.classList.contains( 'monsterinsights-styled-checkbox' ) ) {
					e.preventDefault();
					e.stopPropagation();
				} else {
					this.checked = ! this.checked;
				}
			},
			updateSetting: function( value ) {
				if ( this.disabled ) {
					return false;
				}
				this.$mi_saving_toast({});
				this.$store.dispatch( '$_settings/updateSettings', {
					'name': this.name,
					'value': value,
				}).then( ( response ) => {
					if ( response.success ) {
						this.$mi_success_toast({});
					} else {
						this.$mi_error_toast({});
					}
				});
			},
			toggleCollapsible: function( e ) {
				e.preventDefault();
				this.slotCollapsibleVisible = ! this.slotCollapsibleVisible;
			},
			labelClass() {
				let label_class = 'monsterinsights-styled-checkbox';

				if ( this.checked ) {
					label_class += ' monsterinsights-styled-checkbox-checked';
				}

				return label_class;
			},
			mounted() {
				this.slotCollapsibleVisible = this.checked;
			},
		},
	};
</script>
