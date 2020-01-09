<template>
	<div class="settings-input-text">
		<label :for="id">
			<span class="monsterinsights-dark" v-html="label"></span>
			<span v-if="description" v-html="description"></span><a v-if="showReset()" class="monsterinsights-reset-default" href="#" v-on:click.prevent="resetValue" v-text="text_reset"></a>
			<settings-info-tooltip v-if="tooltip" :content="tooltip" />
		</label>
		<div class="settings-input-text-input">
			<input :id="id" v-model="value" v-tooltip="tooltip_data" :type="type" :name="name" :placeholder="placeholder" :readonly="disabled" v-on:change="inputUpdate" />
		</div>
		<label v-if="has_error" class="monsterinsights-error">
			<i class="monstericon-warning-triangle"></i><span v-html="text_error"></span>
		</label>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import SettingsInfoTooltip from '../SettingsInfoTooltip';

	export default {
		name: 'SettingsInputText',
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
			format: RegExp,
		},
		data() {
			return {
				is_loading: false,
				has_error: false,
				id: 'input-' + this.name,
				text_reset: __( 'Reset to default', process.env.VUE_APP_TEXTDOMAIN ),
				text_error: __( 'The value entered does not match the required format', process.env.VUE_APP_TEXTDOMAIN ),
				updated_value: false,
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
			value: {
				get() {
					return false !== this.updated_value ? this.updated_value : this.settings[this.name];
				},
				set( value ) {
					return this.updated_value = value;
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
				if ( this.format ) {
					if ( ! (this.format).test( value ) ) {
						this.has_error = true;
						return false;
					}
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
