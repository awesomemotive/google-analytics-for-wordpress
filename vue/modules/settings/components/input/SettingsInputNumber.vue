<template>
	<div class="settings-input-text">
		<label :for="id">
			<span class="monsterinsights-dark" v-html="label"></span>
			<span v-if="description" v-html="description"></span><a v-if="showReset()" class="monsterinsights-reset-default" href="#" v-on:click.prevent="resetValue" v-text="text_reset"></a>
			<settings-info-tooltip v-if="tooltip" :content="tooltip" />
		</label>
		<div class="settings-input-text-input">
			<input :id="id" v-model="number_value" v-tooltip="tooltip_data" type="number" :name="name" :placeholder="placeholder" :min="min" :max="max" :step="step" :readonly="disabled" v-on:change="inputUpdate" />
		</div>
		<label v-if="has_error" class="monsterinsights-error">
			<i class="monstericon-warning-triangle"></i><span v-html="has_error"></span>
		</label>
	</div>
</template>

<script>
	import { sprintf, __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import SettingsInfoTooltip from '../SettingsInfoTooltip';

	export default {
		name: 'SettingsInputNumber',
		components: { SettingsInfoTooltip },
		props: {
			name: String,
			label: String,
			description: String,
			placeholder: String,
			type: {
				type: String, default: 'text',
			},
			tooltip: String,
			default_value: String,
			min: Number,
			max: Number,
			step: {
				type: Number,
				default: 1,
			},
			round: {
				type: Boolean,
				default: false,
			},
		},
		data() {
			return {
				has_error: false,
				id: 'input-' + this.name,
				text_reset: __( 'Reset to default', process.env.VUE_APP_TEXTDOMAIN ),
				text_error_value: sprintf( __( 'Please enter a value between %1$s and %2$s', process.env.VUE_APP_TEXTDOMAIN ), '<strong>' + this.min + '</strong>', '<strong>' + this.max + '</strong>' ),
				text_error_round: __( 'Value has to be a round number', process.env.VUE_APP_TEXTDOMAIN ),
				updated_number_value: false,
			};
		},
		computed: {
			...mapGetters({
				settings: '$_settings/settings',
				auth: '$_auth/auth',
			}),
			has_ua() {
				let is_authed = this.auth.network_ua ? this.auth.network_ua : this.auth.ua;
				if ( ! is_authed ) {
					is_authed = this.auth.network_manual_ua ? this.auth.network_manual_ua : this.auth.manual_ua;
				}
				return '' !== is_authed;
			},
			number_value: {
				get() {
					return false !== this.updated_number_value ? this.updated_number_value : this.settings[this.name];
				},
				set( value ) {
					return this.updated_number_value = value;
				},
			},
			tooltip_data() {
				return {
					content: this.has_ua ? '' : this.$mi_need_to_auth,
					autoHide: false,
					trigger: 'hover focus click',
				};
			},
			disabled() {
				return ! this.has_ua;
			},
		},
		methods: {
			inputUpdate: function( e ) {
				this.updateSetting( e.target.name, e.target.value );
			},
			updateSetting: function( name, value ) {
				if ( this.disabled ) {
					return false;
				}
				this.has_error = false;
				value = parseFloat( value );
				if ( this.round && value % 1 !== 0 ) {
					this.has_error = this.text_error_round;
					return false;
				}
				if ( isNaN( value ) || value > this.max || value < this.min ) {
					this.has_error = this.text_error_value;
					return false;
				}
				this.$mi_saving_toast({});
				this.$store.dispatch( '$_settings/updateSettings', {
					'name': name,
					'value': value,
				}).then( ( response ) => {
					if ( response.success ) {
						this.$mi_success_toast({});
					} else {
						this.$mi_error_toast({});
					}
				});
			},
			showReset() {
				return this.default_value && this.settings[this.name] !== this.default_value;
			},
			resetValue() {
				return this.updateSetting( this.name, this.default_value );
			},
		},
	};
</script>

<style lang="scss" scoped>
	.monsterinsights-dark {
		display: block;
	}

	.monsterinsights-reset-default {
		margin-left: 5px;
	}
</style>
