<template>
	<main class="monsterinsights-settings-content settings-advanced">
		<settings-block :title="text_permissions_title">
			<settings-input-select :options="user_roles" :forced="user_roles_manage_options" :multiple="true" name="view_reports" :label="text_permissions_view_label" :description="text_permissions_view_description" :tooltip="text_permissions_view_tooltip" :disabled="disabled" />
			<div class="monsterinsights-separator"></div>
			<settings-input-select :options="user_roles" :forced="user_roles_manage_options" :multiple="true" name="save_settings" :label="text_permissions_save_label" :description="text_permissions_save_description" :tooltip="text_permissions_save_tooltip" />
			<div class="monsterinsights-separator"></div>
			<settings-input-select :options="user_roles" :multiple="true" name="ignore_users" :label="text_permissions_ignore_label" :description="text_permissions_ignore_description" :tooltip="text_permissions_ignore_tooltip" :disabled="disabled" />
		</settings-block>
		<settings-block :title="text_performance_title">
			<settings-input-performance></settings-input-performance>
		</settings-block>
		<settings-block :title="text_custom_code_title">
			<label for="input-custom_code" v-html="text_custom_code_description"></label>
			<settings-input-textarea v-if="can_edit_code" name="custom_code" :validate="validateCode"></settings-input-textarea>
			<p v-else v-text="text_cant_edit"></p>
		</settings-block>
		<settings-block :title="text_reports_title">
			<settings-input-radio :options="reports_options" name="dashboards_disabled"></settings-input-radio>
			<div class="monsterinsights-separator"></div>
			<settings-input-checkbox name="hide_admin_bar_reports" :label="text_hide_admin_bar"></settings-input-checkbox>
		</settings-block>
		<settings-block :title="text_automatic_updates_title">
			<settings-input-radio :options="automatic_updates" name="automatic_updates"></settings-input-radio>
		</settings-block>
		<settings-input-misc></settings-input-misc>
	</main>
</template>

<script>
	import { __, sprintf } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import SettingsBlock from '../SettingsBlock';
	import SettingsInputSelect from '../input/SettingsInputSelect';
	import SettingsInputPerformance from '../input/tab-advanced/SettingsInputPerformance-MI_VERSION';
	import SettingsInputTextarea from '../input/SettingsInputTextarea';
	import SettingsInputRadio from '../input/SettingsInputRadio';
	import SettingsInputMisc from '../input/tab-advanced/SettingsInputMisc-MI_VERSION';
	import SettingsInputCheckbox from "../input/SettingsInputCheckbox";

	export default {
		name: 'SettingsTabAdvanced',
		components: {
			SettingsInputCheckbox,
			SettingsInputRadio,
			SettingsInputTextarea,
			SettingsInputSelect,
			SettingsBlock,
			SettingsInputPerformance,
			SettingsInputMisc,
		},
		data() {
			return {
				text_permissions_title: __( 'Permissions', process.env.VUE_APP_TEXTDOMAIN ),
				text_permissions_view_label: __( 'Allow These User Roles to See Reports', process.env.VUE_APP_TEXTDOMAIN ),
				text_permissions_view_description: __( 'Users that have at least one of these roles will be able to view the reports.', process.env.VUE_APP_TEXTDOMAIN ),
				text_permissions_view_tooltip: __( 'Users that have at least one of these roles will be able to view the reports, along with any user with the manage_options capability.', process.env.VUE_APP_TEXTDOMAIN ),
				text_permissions_save_label: __( 'Allow These User Roles to Save Settings', process.env.VUE_APP_TEXTDOMAIN ),
				text_permissions_save_description: __( 'Users that have at least one of these roles will be able to view and save the settings panel.', process.env.VUE_APP_TEXTDOMAIN ),
				text_permissions_save_tooltip: __( 'Users that have at least one of these roles will be able to view and save the settings panel, along with any user with the manage_options capability.', process.env.VUE_APP_TEXTDOMAIN ),
				text_permissions_ignore_label: __( 'Exclude These User Roles From Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_permissions_ignore_description: __( 'Users that have at least one of these roles will not be tracked into Google Analytics.', process.env.VUE_APP_TEXTDOMAIN ),
				text_permissions_ignore_tooltip: __( 'Users that have at least one of these roles will not be tracked into Google Analytics.', process.env.VUE_APP_TEXTDOMAIN ),
				text_performance_title: __( 'Performance', process.env.VUE_APP_TEXTDOMAIN ),
				text_custom_code_title: __( 'Custom code', process.env.VUE_APP_TEXTDOMAIN ),
				text_custom_code_description: sprintf( __( 'Not for the average user: this allows you to add a line of code, to be added before the %1$spageview is sent%2$s.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/pages#implementation" target="_blank">', '</a>' ),
				text_reports_title: __( 'Reports', process.env.VUE_APP_TEXTDOMAIN ),
				text_automatic_updates_title: __( 'Automatic Updates', process.env.VUE_APP_TEXTDOMAIN ),
				text_cant_edit: __( 'You must have the "unfiltered_html" capability to view/edit this setting.', process.env.VUE_APP_TEXTDOMAIN ),
				text_hide_admin_bar: __( 'Hide Admin Bar Reports', process.env.VUE_APP_TEXTDOMAIN ),
				reports_options: [
					{
						value: '0',
						label: sprintf( __( 'Enabled %1$s- Show reports and dashboard widget.%2$s', process.env.VUE_APP_TEXTDOMAIN ), '<small>', '</small>' ),
					},
					{
						value: 'dashboard_widget',
						label: sprintf( __( 'Dashboard Widget Only %1$s- Disable reports, but show dashboard widget.%2$s', process.env.VUE_APP_TEXTDOMAIN ), '<small>', '</small>' ),
					},
					{
						value: 'disabled',
						label: sprintf( __( 'Disabled %1$s- Hide reports and dashboard widget.%2$s', process.env.VUE_APP_TEXTDOMAIN ), '<small>', '</small>' ),
					},
				],
				automatic_updates: [
					{
						value: 'all',
						label: sprintf( __( 'Yes (recommended) %1$s- Get the latest features, bugfixes, and security updates as they are released.%2$s', process.env.VUE_APP_TEXTDOMAIN ), '<small>', '</small>' ),
					},
					{
						value: 'minor',
						label: sprintf( __( 'Minor only %1$s- Get bugfixes and security updates, but not major features.%2$s', process.env.VUE_APP_TEXTDOMAIN ), '<small>', '</small>' ),
					},
					{
						value: 'none',
						label: sprintf( __( 'None %1$s- Manually update everything.%2$s', process.env.VUE_APP_TEXTDOMAIN ), '<small>', '</small>' ),
					},
				],
				can_edit_code: this.$mi.unfiltered_html,
			};
		},
		computed: {
			...mapGetters({
				settings: '$_settings/settings',
				addons: '$_addons/addons',
				auth: '$_auth/auth',
			}),
			user_roles: function() {
				let roles = [];
				for ( let role in this.$mi.roles ) {
					roles.push({
						'label': this.$mi.roles[role],
						'value': role,
					});
				}
				return roles;
			},
			user_roles_manage_options: function() {
				let roles = [];
				for ( let role in this.$mi.roles_manage_options ) {
					roles.push({
						'label': this.$mi.roles_manage_options[role],
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
		},
		methods: {
			validateCode(code) {
				if ( code.indexOf( 'analytics.js' ) > -1 || code.indexOf( 'gtag.js' ) > -1 || code.indexOf( 'gtm.js' ) > -1 || code.indexOf( 'ga.js' ) > -1 ) {
					return sprintf( __( 'It looks like you added a Google Analytics tracking code in the custom code area, this can potentially prevent proper tracking. If you want to use a manual UA please use the setting in the %1$sGeneral%2$s tab.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="#/general">', '</a>' );
				}

				return true;
			},
		},
	};
</script>

<style lang="scss" scoped>
	.monsterinsights-dark {
		display: block;
	}
</style>
