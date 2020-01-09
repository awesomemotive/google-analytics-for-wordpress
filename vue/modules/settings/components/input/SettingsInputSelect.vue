<template>
	<div class="monsterinsights-settings-input-select">
		<label :for="id">
			<span class="monsterinsights-dark" v-html="label"></span>
			<span v-if="description" v-html="description"></span>
			<settings-info-tooltip v-if="tooltip" :content="tooltip" />
		</label>
		<div class="monsterinsights-settings-input-select-input">
			<multiselect v-model="selected" v-tooltip="disabled_tooltip" :options="options" :multiple="multiple" track-by="value" label="label" :searchable="false" selectLabel="" selectedLabel="" deselectLabel="" :readonly="disabled" v-on:input="updateSetting">
				<template slot="tag" slot-scope="{option, search, remove}">
					<span :class="tagClass(option)">
						<span v-text="option.label"></span>
						<i aria-hidden="true" tabindex="0" class="multiselect__tag-icon" v-on:keypress.enter.prevent="remove(option)" v-on:mousedown.prevent="remove(option)"></i>
					</span>
				</template>
			</multiselect>
		</div>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import Multiselect from 'vue-multiselect';
	import SettingsInfoTooltip from '../SettingsInfoTooltip';

	export default {
		name: 'SettingsInputSelect',
		components: {
			SettingsInfoTooltip,
			Multiselect,
		},
		props: {
			options: Array,
			forced: {
				type: Array,
				default: () => [],
			}, // List of items which can't be unselected from a multiselect.
			name: String,
			label: String,
			description: String,
			multiple: {
				type: Boolean,
				default: false,
			},
			tooltip: String,
			disabled: {
				type: Boolean,
				default: false,
			},
		},
		data() {
			return {
				is_loading: false,
				has_error: false,
				id: 'input-' + this.name,
				text_no_options: __( 'No options available', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters({
				settings: '$_settings/settings',
			}),
			selected: {
				get: function() {
					let selected = JSON.parse( JSON.stringify( this.forced ) );
					if ( this.settings[this.name]) {
						for ( let index in this.options ) {
							if ( this.settings[this.name].indexOf( this.options[index].value ) >= 0 ) {
								if ( this.notForced( this.options[index]) ) {
									selected.push( this.options[index]);
								}
							}
						}
					}
					return selected;
				},
				set: function() {
				},
			},
			disabled_tooltip() {
				return {
					content: this.disabled ? this.$mi_need_to_auth : '',
					autoHide: false,
					trigger: 'hover focus click',
				};
			},
		},
		methods: {
			updateSetting: function( value ) {
				if ( this.disabled ) {
					return false;
				}
				this.$mi_saving_toast({});
				let processed_value = [];
				for ( let index in value ) {
					processed_value.push( value[index].value );
				}
				this.$store.dispatch( '$_settings/updateSettings', {
					'name': this.name,
					'value': processed_value,
				}).then( ( response ) => {
					if ( response.success ) {
						this.$mi_success_toast({});
					} else {
						this.$mi_error_toast({});
					}
				});
			},
			notForced: function( option ) {
				for ( let index in this.forced ) {
					if ( this.forced[index].value === option.value ) {
						return false;
					}
				}
				return true;
			},
			tagClass( option ) {
				let tagClass = 'multiselect__tag';
				if ( ! this.notForced( option ) ) {
					tagClass += ' monsterinsights-tag-forced';
				}
				return tagClass;
			},
		},
	};
</script>

<style lang="scss" scoped>
	.monsterinsights-dark {
		display: block;
	}
</style>
