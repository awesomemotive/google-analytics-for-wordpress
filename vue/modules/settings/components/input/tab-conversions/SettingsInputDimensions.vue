<template>
	<div class="monsterinsights-settings-input-repeater monsterinsights-settings-input-dimensions">
		<div class="monsterinsights-settings-input-repeater-labels settings-input-repeater-row">
			<label v-text="text_type"></label>
			<label v-text="text_id"></label>
		</div>
		<div class="monsterinsights-separator"></div>
		<template v-for="( row, index ) in rows">
			<div :key="index" :class="rowClass( row.type )">
				<select v-model="row.type" :disabled="disabled" v-on:change="updateSetting">
					<option v-for="option in getDimensions( row.type )" :key="option.type" :value="option.type"
						:disabled="! option.enabled" v-text="option.title"
					></option>
				</select>
				<input v-model="row.id" v-tooltip="tooltip_data" type="number" :max="getDimensionsCount()" :readonly="disabled" v-on:change="updateSetting" />
				<button :title="text_remove_row" v-on:click="removeRow(index)">
					<i class="monstericon-times-circle"></i>
				</button>
			</div>
			<div :key="'separator-'+index" class="monsterinsights-separator"></div>
		</template>
		<label v-if="has_errors" class="monsterinsights-error">
			<i class="monstericon-warning-triangle"></i><span v-html="has_errors"></span>
		</label>
		<button v-if="rows.length < getDimensionsCount()" v-tooltip="tooltip_data" :class="button_class" v-on:click="addRow"
			v-text="text_add"
		></button>
		<span class="monsterinsights-dimensions-count"
			v-text="sprintf( text_using, rows.length, getDimensionsCount() )"
		></span>
	</div>
</template>

<script>
	import Vue from 'vue';
	import { __, sprintf } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';

	export default {
		name: 'SettingsInputDimensions',
		props: {
			name: String,
		},
		data() {
			return {
				has_errors: false,
				current_index: 0,
				text_add: __( 'Add New Custom Dimension', process.env.VUE_APP_TEXTDOMAIN ),
				text_remove_row: __( 'Remove row', process.env.VUE_APP_TEXTDOMAIN ),
				text_type: __( 'Type', process.env.VUE_APP_TEXTDOMAIN ),
				text_id: __( 'ID', process.env.VUE_APP_TEXTDOMAIN ),
				text_using: __( 'You are using %d out of %d custom dimensions', process.env.VUE_APP_TEXTDOMAIN ),
				dimensions: JSON.parse( JSON.stringify( this.$mi.dimensions ) ), // Use this to clone the object so we can use it as reference of those which should always be disabled.
			};
		},
		computed: {
			...mapGetters( {
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
			rows: {
				get: function() {
					if ( ! this.settings[this.name] ) {
						Vue.set( this.settings, this.name, [] );
					}
					if ( Object === this.settings[this.name].constructor ) {
						Vue.set( this.settings, this.name, Object.values( this.settings[this.name] ) );
					}
					const rows = JSON.parse( JSON.stringify( this.settings[this.name] ) );// Don't link the rows model directly to the settings store, this way the mutations are used.
					this.updateAvailableDimensions( rows );
					this.maybeShowYoastNotice( rows );
					return rows;
				},
				set: function() {
					this.updateSetting();
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
			button_class() {
				let bclass = 'monsterinsights-button';
				if ( this.disabled ) {
					bclass += ' monsterinsights-button-disabled';
				}
				return bclass;
			},
		},
		methods: {
			sprintf,
			updateSetting: function() {
				if ( this.disabled ) {
					return false;
				}
				this.has_errors = this.validateSettings();
				if ( this.has_errors ) {
					return false;
				}
				this.$mi_saving_toast( {} );
				this.updateAvailableDimensions();
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
				if ( this.disabled ) {
					return false;
				}
				this.updateAvailableDimensions();
				let selected_dimension = '';
				for ( let index in this.dimensions ) {
					if ( this.dimensions[index].enabled ) {
						selected_dimension = this.dimensions[index].type;
					}
				}
				let new_row = {
					id: this.getCurrentIndex(),
					type: selected_dimension,
				};
				this.rows.push( new_row );
				this.updateAvailableDimensions();
				this.updateSetting();
			},
			removeRow: function( index ) {
				this.rows.splice( index, 1 );
				this.updateSetting();
			},
			updateAvailableDimensions( rows ) {
				const current_rows = rows ? rows : this.rows;
				if ( current_rows ) {
					for ( let index in this.dimensions ) {
						let enabled = this.$mi.dimensions[index].enabled;
						for ( let loaded_index in current_rows ) {
							if ( this.dimensions[index]['type'] === current_rows[loaded_index]['type'] ) {
								enabled = false;
								break;
							}
						}
						this.dimensions[index]['enabled'] = enabled;
					}
				}
			},
			validateSettings() {
				let ids = [];
				let empty_value = false;
				for ( let index in this.rows ) {
					ids.push( parseInt( this.rows[index].id ) );
					if ( '' === this.rows[index].id ) {
						empty_value = true;
					}
				}
				if ( empty_value ) {
					return __( 'Each dimension needs to have an id set.', process.env.VUE_APP_TEXTDOMAIN );
				}
				const filtered = ids.filter( ( x, i, a ) => a.indexOf( x ) === i );
				if ( filtered.length !== ids.length ) {
					return __( 'The custom dimension IDs must be unique for each dimension.', process.env.VUE_APP_TEXTDOMAIN );
				}
				return false;
			},
			getDimensionsCount() {
				let count = 0;
				for ( let index in this.$mi.dimensions ) {
					if ( this.$mi.dimensions[index]['enabled'] ) {
						count++;
					}
				}
				return count;
			},
			rowClass( type ) {
				let row_class = 'monsterinsights-settings-input-repeater-row';

				if ( this.defaultDisabled( type ) ) {
					row_class += ' monsterinsights-disabled-row';
				}
				return row_class;
			},
			defaultDisabled( type ) {
				for ( let dimension in this.$mi.dimensions ) {
					if ( this.$mi.dimensions[dimension].type === type ) {
						if ( ! this.$mi.dimensions[dimension].enabled ) {
							return true;
						}
					}
				}
				return false;
			},
			getCurrentIndex() {
				let current_indexes = [];
				for ( let index in this.rows ) {
					current_indexes.push( this.rows[index]['id'] );
				}
				current_indexes.sort( function( a, b ) {
					return a - b;
				} );

				if ( 0 === current_indexes.length ) {
					this.current_index = 1;
					return 1;
				}

				let lowest = 1;
				for ( let i = 0; i < current_indexes.length; i++ ) {
					if ( current_indexes[i] - 1 !== i ) {
						lowest = i + 1;
						break;
					}
				}
				if ( lowest === 1 ) {
					lowest = parseInt( current_indexes[current_indexes.length - 1], 10 ) + 1;
				}
				this.current_index = lowest;
				return this.current_index;
			},
			getDimensions( type ) {
				let dimensions = [];

				for ( let index in this.dimensions ) {
					if ( this.dimensions[index].enabled || type === this.dimensions[index].type ) {
						dimensions.push( this.dimensions[index] );
					}
				}
				return dimensions;
			},
			maybeShowYoastNotice( rows ) {
				if ( rows ) {
					for ( let index in rows ) {
						if ( 'seo_score' === rows[index].type && this.defaultDisabled( 'seo_score' ) || 'focus_keyword' === rows[index].type && this.defaultDisabled( 'focus_keyword' ) ) {
							this.$emit( 'showYoastNotice' );
							break;
						}
					}
				}
			},
		},
	};
</script>

<style lang="scss" scoped>
    .settings-input-repeater-row {
        display: flex;
        margin-bottom: 10px;
    }
</style>
