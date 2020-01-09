<template>
	<div class="settings-input-text">
		<label v-if="label || description" :for="id">
			<span class="monsterinsights-dark" v-html="label"></span>
			<span v-if="description" v-html="description"></span>
		</label>
		<div class="settings-input-text-input">
			<textarea :id="id" v-model="value" v-tooltip="tooltip_data" :readonly="disabled" :name="name" :placeholder="placeholder" v-on:change="updateSetting"></textarea>
		</div>
		<label v-if="error" class="monsterinsights-error">
			<i class="monstericon-warning-triangle"></i><span v-html="error"></span>
		</label>
	</div>
</template>

<script>
	import { mapGetters } from 'vuex';

	export default {
		name: 'SettingsInputTextarea',
		props: {
			name: String,
			label: String,
			description: String,
			placeholder: String,
			validate: Function,
		},
		data() {
			return {
				is_loading: false,
				has_error: false,
				id: 'input-' + this.name,
				error: false,
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
			updateSetting: function(e) {
				if ( this.disabled ) {
					return false;
				}
				this.error = false;

				if ( this.validate ) {
					let valid = this.validate(e.target.value);
					if ( true !== valid ) {
						this.error = valid;
						return false;
					}
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
		},
	};
</script>

<style lang="scss" scoped>
	.monsterinsights-dark {
		display: block;
	}
</style>
