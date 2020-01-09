<template>
	<fieldset>
		<div class="monsterinsights-settings-input-radio">
			<label v-for="option in options" :key="option.value" :for="'monsterinsights-settings-radio-' + name + '[' + option.value + ']'">
				<span v-tooltip="tooltip_data" :class="labelClass( option.value )"></span>
				<input :id="'monsterinsights-settings-radio-' + name + '[' + option.value + ']'" type="radio" :name="name" :value="option.value" :checked="isChecked(option.value)" autocomplete="off" :readonly="disabled" v-on:change="updateSetting" />
				<span v-html="option.label"></span>
			</label>
		</div>
	</fieldset>
</template>

<script>
	import { mapGetters } from 'vuex';

	export default {
		name: 'SettingsInputRadio',
		props: {
			options: Array,
			name: String,
			auth_disabled: {
				type: Boolean,
				default: true,
			},
		},
		data() {
			return {
				is_loading: false,
				has_error: false,
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
			tooltip_data() {
				return {
					content: this.disabled ? this.$mi_need_to_auth : '',
					autoHide: false,
					trigger: 'hover focus click',
				};
			},
			disabled() {
				if ( ! this.auth_disabled ) {
					return false;
				}
				return ! this.has_ua;
			},
		},
		methods: {
			updateSetting: function( e ) {
				if ( this.disabled ) {
					return false;
				}
				this.$mi_saving_toast({});
				this.$store.dispatch( '$_settings/updateSettings', {
					'name': this.name,
					'value': e.target.value,
				}).then( ( response ) => {
					if ( response.success ) {
						this.$mi_success_toast({});
					} else {
						this.$mi_error_toast({});
					}
				});
			},
			labelClass( value ) {
				let label_class = 'monsterinsights-styled-radio';

				if ( this.isChecked( value ) ) {
					label_class += ' monsterinsights-styled-radio-checked';
				}

				return label_class;
			},
			isChecked( value ) {
				if ( this.settings[this.name]) {
					return value === this.settings[this.name];
				}

				return value === this.options[0].value;
			},
		},
	};
</script>
