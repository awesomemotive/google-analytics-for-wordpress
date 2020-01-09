<template>
	<div class="monsterinsights-conditional-logic-container">
		<div class="monsterinsights-conditional-logic-header">
			<p>
				<span class="monsterinsights-dark">{{ label }}</span>
				<span v-if="show_rules_number" class="monsterinsights-conditional-rule-sets-number"><strong>{{ conditions.length }} {{ conditions.length != 1 ? text_rules : text_rule }} </strong></span><br />
				<span>{{ description }}</span>
			</p>
		</div>
		<div v-for="( rule, ruleIndex ) in conditions" :key="ruleIndex" class="monsterinsights-conditional-logic-rule">
			<div v-if="ruleIndex!=0" class="monsterinsights-condition-rule-separator" v-text="text_or"></div>
			<div v-for="( condition, condIndex ) in rule.rule_conditions" :key="condIndex" class="monsterinsights-conditional-logic-repeater-row">
				<div class="monsterinsights-settings-conditional-column">
					<select v-model="conditions[ruleIndex].rule_conditions[condIndex].based_on" class="monsterinsights-settings-conditional-input-field" v-on:change="resetOperatorDropdown(ruleIndex,condIndex)">
						<option
							v-for="option in baseOptions"
							:key="option.value"
							:value="option.value"
							v-text="option.label"
						></option>
					</select>
				</div>
				<div class="monsterinsights-settings-conditional-column">
					<select v-model="conditions[ruleIndex].rule_conditions[condIndex].operator" class="monsterinsights-settings-conditional-input-field" v-on:change="resetValueDropdown(condition.value, ruleIndex,condIndex)">
						<option
							v-for="(operatorLabel, operatorValue) in operators[condition.based_on]"
							:key="operatorValue"
							:value="operatorValue"
							v-text="operatorLabel"
						></option>
					</select>
				</div>
				<!--				<div v-if="condition.based_on==='user' && condition.operator==='logged_in'" class="monsterinsights-settings-conditional-column">-->
				<!--					<select v-model="conditions[ruleIndex].rule_conditions[condIndex].value" class="monsterinsights-settings-conditional-input-field" v-on:change="updateSetting">-->
				<!--						<option-->
				<!--							v-for="option in userRoles"-->
				<!--							:key="option.value"-->
				<!--							:value="option.value"-->
				<!--							v-text="option.label"-->
				<!--						></option>-->
				<!--					</select>-->
				<!--				</div>-->
				<div v-if="condition.based_on==='page' && isOperatorPage( condition.operator )" class="monsterinsights-settings-conditional-column">
					<multiselect
						v-model="conditions[ruleIndex].rule_conditions[condIndex].value"
						:clear-on-select="false"
						:close-on-select="true"
						:internal-search="false"
						:loading="isLoading"
						:max-height="600"
						:options="pages"
						:placeholder="text_search_pages_placeholder"
						:searchable="true"
						:show-no-results="true"
						:showLabels="false"
						class="monsterinsights-settings-conditional-input-field"
						label="title"
						track-by="id"
						v-on:close="updateSetting"
						v-on:search-change="findPages"
					>
						<span slot="noResult" v-text="text_search_pages_not_found"></span>
						<span slot="noOptions" v-text="text_search_no_options"></span>
					</multiselect>
				</div>
				<div v-else-if="condition.based_on==='page' && isOperatorSlug( condition.operator )" class="monsterinsights-settings-conditional-column">
					<input
						v-model="conditions[ruleIndex].rule_conditions[condIndex].value"
						type="text"
						placeholder="example-page"
						class="monsterinsights-settings-conditional-input-field text-input"
						v-on:change="updateSetting"
					/>
				</div>
				<div v-else class="monsterinsights-settings-conditional-column"></div>
				<button class="monsterinsights-button add-new-condition" v-on:click="addConditionRow( ruleIndex )" v-text="text_and"></button>
				<button class="remove-condition" v-on:click="removeConditionRow( ruleIndex, condIndex )">
					<i class="monstericon-times-circle"></i>
				</button>
			</div>
		</div>
		<div class="monsterinsights-conditional-logic-footer">
			<button :class="button_class" v-on:click="addRuleSet" v-text="new_rule_set_button_label"></button>

			<label v-if="error_notice.length" class="monsterinsights-error">
				<i class="monstericon-warning-triangle"></i><span v-html="error_notice"></span>
			</label>
		</div>
	</div>
</template>

<script>
	import Vue from 'vue';
	import { mapGetters } from 'vuex';
	import { __, sprintf } from '@wordpress/i18n';
	import Multiselect from 'vue-multiselect';
	import axios from 'axios';
	import debounce from 'lodash.debounce';

	export default {
		name: 'SettingsInputConditionalLogic',
		components: {
			Multiselect,
		},
		props: {
			name: String,
			label: String,
			description: String,
			based_on: {
				type: Array,
				default() {
					return [ 'page', 'user' ];
				},
			},
			max_rules: {
				type: Number,
				default: -1,
			},
			show_rules_number: {
				type: Boolean,
				default: true,
			},
			new_rule_set_button_label: {
				type: String,
				default: __( 'Add Rule', process.env.VUE_APP_TEXTDOMAIN ),
			},
		},
		data() {
			return {
				pages: [],
				isLoading: false,
				error_notice: '',
				operators: {
					page: {
						page_is: __('Is', process.env.VUE_APP_TEXTDOMAIN),
						page_is_not: __('Is Not', process.env.VUE_APP_TEXTDOMAIN),
						slug_contains: __('Slug Contains', process.env.VUE_APP_TEXTDOMAIN),
						slug_not_contains: __('Slug Does Not Contain', process.env.VUE_APP_TEXTDOMAIN),
						slug_starts_with: __('Slug Starts With', process.env.VUE_APP_TEXTDOMAIN),
						slug_ends_with: __('Slug Ends With', process.env.VUE_APP_TEXTDOMAIN),
					},
					user: {
						logged_in: __('Logged In', process.env.VUE_APP_TEXTDOMAIN),
						not_logged_in: __('Not Logged In', process.env.VUE_APP_TEXTDOMAIN),
					},
				},
				text_and: __('AND', process.env.VUE_APP_TEXTDOMAIN),
				text_or: __('OR', process.env.VUE_APP_TEXTDOMAIN),
				text_rule: __( 'Rule', process.env.VUE_APP_TEXTDOMAIN ),
				text_rules: __( 'Rules', process.env.VUE_APP_TEXTDOMAIN ),
				text_search_pages_placeholder: __('Select page/search', process.env.VUE_APP_TEXTDOMAIN),
				text_search_pages_not_found: __('Oops! No page found.', process.env.VUE_APP_TEXTDOMAIN),
				text_search_no_options: __('Search by page title', process.env.VUE_APP_TEXTDOMAIN),
			};
		},
		computed: {
			...mapGetters({
				settings: '$_settings/settings',
				auth: '$_auth/auth',
			}),
			conditions: {
				get: function() {
					if ( ! this.settings[this.name] ) {
						Vue.set( this.settings, this.name, [] );
					}
					return JSON.parse( JSON.stringify( this.settings[this.name] ) );
				},
				set: function() {
					this.updateSetting();
				},
			},
			baseOptions() {
				let options = [];
				for ( let option in this.based_on ) {
					options.push({
						'label': this.based_on[option],
						'value': this.based_on[option],
					});
				}
				return options;
			},
			userRoles() {
				let roles = [
					{
						'label': __('All Roles', process.env.VUE_APP_TEXTDOMAIN),
						'value': 'all_roles',
					},
				];
				for ( let role in this.$mi.roles ) {
					roles.push({
						'label': this.$mi.roles[role],
						'value': role,
					});
				}
				return roles;
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
			button_class() {
				let bclass = 'monsterinsights-button';
				if ( this.disabled ) {
					bclass += ' monsterinsights-button-disabled';
				}
				return bclass;
			},
		},
		methods: {
			getDefaultRuleSet() {
				return {
					rule_logic: "or",
					rule_conditions: [
						this.getDefaultCondition(),
					],
				};
			},
			getDefaultCondition() {
				return {
					based_on: this.based_on.includes( 'user' ) ? "user" : "page",
					operator: this.based_on.includes( 'user' ) ? "logged_in" : "page_is",
					value: this.based_on.includes( 'user' ) ? "all_roles" : "",
					logic: "and",
				};
			},
			addRuleSet() {
				if ( typeof this.conditions === 'undefined' || ! this.conditions.length ) {
					this.conditions = [];
				}
				if ( this.conditions.length === this.max_rules ) {
					this.error_notice = sprintf( __( 'You can add maximum %s rule set(s).', process.env.VUE_APP_TEXTDOMAIN ), this.max_rules );
					return;
				}
				this.conditions.push( this.getDefaultRuleSet() );
				this.updateSetting();
			},
			addConditionRow(ruleIndex) {
				this.conditions[ruleIndex].rule_conditions.push( this.getDefaultCondition() );
				this.updateSetting();
			},
			removeConditionRow(ruleIndex, condIndex) {
				this.conditions[ruleIndex].rule_conditions.splice( condIndex, 1 );
				this.updateSetting();
			},
			resetOperatorDropdown(rule_index = '', cond_index = '') {
				if ( rule_index >= 0 && cond_index >= 0 ) {
					let basedOn = this.conditions[rule_index].rule_conditions[cond_index].based_on;
					switch (basedOn) {
						case 'user':
							this.conditions[rule_index].rule_conditions[cond_index].operator = 'logged_in';
							this.conditions[rule_index].rule_conditions[cond_index].value = 'all_roles';
							break;
						case 'page':
							this.conditions[rule_index].rule_conditions[cond_index].operator = 'page_is';
							this.conditions[rule_index].rule_conditions[cond_index].value = '';
							break;
					}
				}
				this.updateSetting();
			},
			resetValueDropdown(previous_value, rule_index = '', cond_index = '') {
				if (rule_index >= 0 && cond_index >= 0 ) {
					let operator 				= this.conditions[rule_index].rule_conditions[cond_index].operator;
					let isPageSelectedBefore 	= false;
					if ( previous_value && typeof previous_value === 'object' ) {
						isPageSelectedBefore = true;
					}
					switch (operator) {
						case 'logged_in':
							this.conditions[rule_index].rule_conditions[cond_index].value = 'all_roles';
							break;
						case 'not_logged_in':
							this.conditions[rule_index].rule_conditions[cond_index].value = '';
							break;
						case 'page_is':
						case 'page_is_not':
							if	( ! isPageSelectedBefore ) {
								this.conditions[rule_index].rule_conditions[cond_index].value = '';
							}
							break;
						case 'slug_contains':
						case 'slug_not_contains':
						case 'slug_starts_with':
						case 'slug_ends_with':
							if	( isPageSelectedBefore ) {
								this.conditions[rule_index].rule_conditions[cond_index].value = '';
							}
							break;
					}
				}
				this.updateSetting();
			},
			updateSetting() {
				this.isLoading = false;
				if ( this.disabled ) {
					return false;
				}
				this.validateSettings();
				this.$mi_saving_toast( {} );
				this.$store.dispatch( '$_settings/updateSettings', {
					'name': this.name,
					'value': this.conditions,
				} ).then( ( response ) => {
					if ( response.success ) {
						this.$mi_success_toast( {} );
					} else {
						this.$mi_error_toast( {} );
					}
				} );
				this.error_notice = '';
			},
			validateSettings() {
				// clear the empty ruleset
				for ( let ruleIndex in this.conditions ) {
					if (this.isEmptyRuleSet(ruleIndex)) {
						this.conditions.splice(ruleIndex, 1);
					}
				}
			},
			findPages: debounce( function( query ) {
				let self = this;
				self.isLoading = true;
				let formData = new FormData();
				formData.append( 'nonce', Vue.prototype.$mi.nonce );
				formData.append( 'action', 'monsterinsights_get_posts' );
				formData.append( 'keyword', query );
				axios.post( this.$mi.ajax, formData ).then( function( response ) {
					self.pages = response.data.data;
					self.isLoading = false;
				} ).catch( function() {
					self.isLoading = false;
					Vue.prototype.$mi_error_toast({
						title: __( 'Can\'t load pages.', process.env.VUE_APP_TEXTDOMAIN ),
					});
				} );
			}, 300 ),
			isEmptyRuleSet(index) {
				return this.conditions[index].rule_conditions.length ? false : true;
			},
			isOperatorPage( operator ) {
				return operator === 'page_is' || operator === 'page_is_not';
			},
			isOperatorSlug( operator ) {
				return operator === 'slug_contains' ||
					operator === 'slug_not_contains' ||
					operator === 'slug_starts_with' ||
					operator === 'slug_ends_with';
			},
		},
	};
</script>
