<template>
	<div class="monsterinsights-settings-input-repeater">
		<div v-if="rows.length" class="monsterinsights-settings-input-repeater-labels monsterinsights-settings-input-repeater-row">
			<label v-for="( input, index ) in structure" :key="index" v-text="input.label"></label>
		</div>
		<template v-for="( row, index ) in rows">
			<div :key="index" class="monsterinsights-settings-input-repeater-row">
				<template v-for="( input, label ) in structure">
					<input :key="label" v-model="rows[index][input.name]" type="text" :readonly="disabled" v-on:change="updateSetting( false, input.pattern )" />
				</template>
				<button :title="text_remove_row" v-on:click.prevent="removeRow(index)">
					<i class="monstericon-times-circle"></i>
				</button>
			</div>
			<label v-if="has_errors[index]" :key="index + 'error'" class="monsterinsights-error">
				<i class="monstericon-warning-triangle"></i><span v-html="has_errors[index]"></span>
			</label>
		</template>

		<button v-tooltip="tooltip_data" :class="button_class" v-on:click.prevent="addRow" v-text="text_add_path"></button>
	</div>
</template>

<script>
	import Vue from 'vue';
	import { __, sprintf } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';

	export default {
		name: 'SettingsInputRepeater',
		props: {
			structure: Array,
			name: String,
			text_add: String,
		},
		data() {
			return {
				text_add_path: this.text_add ? this.text_add : __( 'Add Another Link Path', process.env.VUE_APP_TEXTDOMAIN ),
				text_remove_row: __( 'Remove row', process.env.VUE_APP_TEXTDOMAIN ),
				has_errors: [],
			};
		},
		computed: {
			...mapGetters( {
				settings: '$_settings/settings',
				auth: '$_auth/auth',
			} ),
			rows: {
				get: function() {
					if ( ! this.settings[this.name] ) {
						Vue.set( this.settings, this.name, [] );
					}
					return JSON.parse( JSON.stringify( this.settings[this.name] ) ); // Don't link the rows model directly to the settings store, this way the mutations are used.
				},
				set: function() {
					this.updateSetting( false );
				},
			},
			has_ua() {
				let is_authed = this.auth.network_ua ? this.auth.network_ua : this.auth.ua;
				if ( ! is_authed ) {
					is_authed = this.auth.network_manual_ua ? this.auth.network_manual_ua : this.auth.manual_ua;
				}
				return '' !== is_authed;
			},
			disabled() {
				return ! this.has_ua;
			},
			tooltip_data() {
				return {
					content: this.has_ua ? '' : this.$mi_need_to_auth,
					autoHide: false,
					trigger: 'hover focus click',
				};
			},
			button_class() {
				let bclass = 'monsterinsights-button';
				if ( this.disabled ) {
					bclass += ' monsterinsights-button-disabled';
				}
				return bclass;
			},
		},
		methods: {
			updateSetting: function( new_row ) {
				if ( this.disabled ) {
					return false;
				}
				// Don't validate new rows to avoid annoying errors.
				if ( ! new_row ) {
					if ( ! this.validateSettings() ) {
						return false;
					}
				}
				this.$mi_saving_toast( {} );
				this.$store.dispatch( '$_settings/updateSettings', {
					'name': this.name,
					'value': this.rows,
				} ).then( ( response ) => {
					if ( response.success ) {
						this.$mi_success_toast( {} );
					} else {
						this.$mi_error_toast( {} );
					}
				} );
			},
			addRow: function() {
				let new_row = {};
				for ( let index in this.structure ) {
					new_row[this.structure[index]['name']] = '';
				}
				this.rows.push( new_row );
				this.updateSetting( true );
			},
			removeRow: function( index ) {
				if ( this.rows && this.rows instanceof Array ) {
					this.rows.splice( index, 1 );
				} else {
					this.rows = '';
				}
				this.updateSetting();
			},
			validateSettings() {
				this.has_errors = [];
				let no_duplicates = {};
				for ( let index in this.rows ) {
					for ( let structure in this.structure ) {
						if ( '' === this.rows[index][this.structure[structure]['name']] ) {
							this.has_errors[index] = sprintf( __( '%s can\'t be empty.', process.env.VUE_APP_TEXTDOMAIN ), '<strong>' + this.structure[structure]['label'] + '</strong>' );
							break;
						}
						if ( this.structure[structure]['pattern'] ) {
							const match = (
								this.structure[structure]['pattern']
							).test( this.rows[index][this.structure[structure]['name']] );
							if ( false === match ) {
								this.has_errors[index] = this.structure[structure]['error'];
								break;
							}
						}
						if ( this.structure[structure]['prevent_duplicates'] ) {
							if ( 'undefined' === typeof no_duplicates[this.structure[structure]['name']] ) {
								no_duplicates[this.structure[structure]['name']] = [];
							}

							no_duplicates[this.structure[structure]['name']].push( this.rows[index][this.structure[structure]['name']] );
							const names = no_duplicates[this.structure[structure]['name']];
							let x = () => names.filter((v, i) => names.indexOf(v) === i);
							if ( names.length !== x(names).length) {
								this.has_errors[index] = __( 'Duplicate values are not allowed.', process.env.VUE_APP_TEXTDOMAIN );
							}
						}
					}
				}

				return 0 === this.has_errors.length;
			},
		},
	};
</script>
