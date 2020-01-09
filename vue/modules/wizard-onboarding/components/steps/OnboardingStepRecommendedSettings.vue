<template>
	<div class="monsterinsights-onboarding-step-recommended-settings">
		<onboarding-content-header :title="text_header_title" :subtitle="text_header_subtitle"></onboarding-content-header>
		<div class="monsterinsights-onboarding-wizard-form">
			<form action="" method="post" v-on:submit.prevent="handleSubmit">
				<div class="monsterinsights-separator"></div>
				<settings-input-checkbox :label="text_events_label" :description="text_events_description" :tooltip="text_events_tooltip" :faux="true" :faux_tooltip="text_events_faux_tooltip" />
				<div class="monsterinsights-separator"></div>
				<settings-input-checkbox :label="text_link_attribution_label" :description="text_link_attribution_description" :tooltip="text_link_attribution_tooltip" :faux="true" :faux_tooltip="text_link_attribution_faux_tooltip" />
				<div class="monsterinsights-separator"></div>
				<settings-input-text default_value="doc,pdf,ppt,zip,xls,docx,pptx,xlsx" name="extensions_of_files" :label="text_extensions_of_files_label" :description="text_extensions_of_files_description" :tooltip="text_extensions_of_files_tooltip"></settings-input-text>
				<div class="monsterinsights-separator"></div>
				<p>
					<label v-text="text_affiliate_label"></label>
					<span class="monsterinsights-sublabel" v-html="text_affiliate_repeater_description"></span>
					<settings-info-tooltip :content="text_affiliate_tooltip_content"></settings-info-tooltip>
				</p>
				<settings-input-repeater :structure="repeater_structure" name="affiliate_links" :data="settings['affiliate_links']"></settings-input-repeater>
				<div class="monsterinsights-separator"></div>
				<settings-input-select :options="user_roles" :forced="user_roles_manage_options" :multiple="true" name="view_reports" :label="text_permissions_view_label" :description="text_permissions_view_description" :tooltip="text_permissions_view_tooltip" />
				<div class="monsterinsights-separator"></div>
				<settings-input-checkbox value-on="all" value-off="none" name="automatic_updates" :label="text_updates_label" :description="text_updates_description" :tooltip="text_updates_tooltip" />
				<div class="monsterinsights-separator"></div>
				<onboarding-improve></onboarding-improve>
				<div class="monsterinsights-form-row monsterinsights-form-buttons">
					<button type="submit" class="monsterinsights-onboarding-button monsterinsights-onboarding-button-large" name="next_step" v-text="text_save"></button>
				</div>
			</form>
		</div>
	</div>
</template>
<script>
	import { mapGetters } from 'vuex';
	import { __, sprintf } from '@wordpress/i18n';
	import OnboardingContentHeader from '../OnboardingContentHeader';
	import SettingsInputCheckbox from '../../../settings/components/input/SettingsInputCheckbox';
	import SettingsInputText from '../../../settings/components/input/SettingsInputText';
	import SettingsInputRepeater from '../../../settings/components/input/SettingsInputRepeater';
	import SettingsInfoTooltip from '../../../settings/components/SettingsInfoTooltip';
	import SettingsInputSelect from '../../../settings/components/input/SettingsInputSelect';
	import OnboardingImprove from '../inputs/OnboardingImprove-MI_VERSION';

	export default {
		name: 'OnboardingStepRecommendedSettings',
		components: {
			OnboardingImprove,
			SettingsInputSelect,
			SettingsInfoTooltip,
			SettingsInputRepeater, SettingsInputText, SettingsInputCheckbox, OnboardingContentHeader,
		},
		data() {
			return {
				text_header_title: __( 'Recommended Settings', process.env.VUE_APP_TEXTDOMAIN ),
				text_header_subtitle: __( 'MonsterInsights recommends the following settings based on your configuration.', process.env.VUE_APP_TEXTDOMAIN ),
				text_events_label: __( 'Events Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_events_description: __( 'Must have for all click tracking on site.', process.env.VUE_APP_TEXTDOMAIN ),
				text_events_tooltip: __( 'MonsterInsights uses an advanced system to automatically detect all outbound links, download links, affiliate links, telephone links, mail links, and more automatically. We do all the work for you so you don\'t have to write any code.', process.env.VUE_APP_TEXTDOMAIN ),
				text_link_attribution_label: __( 'Enhanced Link Attribution', process.env.VUE_APP_TEXTDOMAIN ),
				text_link_attribution_description: __( 'Improves the accuracy of your In-Page Analytics.', process.env.VUE_APP_TEXTDOMAIN ),
				text_link_attribution_tooltip: __( 'MonsterInsights will automatically help Google determine which links are unique and where they are on your site so that your In-Page Analytics reporting will be more accurate.', process.env.VUE_APP_TEXTDOMAIN ),
				text_updates_label: __( 'Install Updates Automatically', process.env.VUE_APP_TEXTDOMAIN ),
				text_updates_description: __( 'Get the latest features, bug fixes, and security updates as they are released.', process.env.VUE_APP_TEXTDOMAIN ),
				text_updates_tooltip: __( 'To ensure you get the latest bugfixes and security updates and avoid needing to spend time logging into your WordPress site to update MonsterInsights, we offer the ability to automatically have MonsterInsights update itself.', process.env.VUE_APP_TEXTDOMAIN ),
				text_extensions_of_files_label: __( 'File Download Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_extensions_of_files_description: __( 'Helps you see file downloads data.', process.env.VUE_APP_TEXTDOMAIN ),
				text_extensions_of_files_tooltip: __( 'MonsterInsights will automatically track downloads of common file types from links you have inserted onto your website. For example: want to know how many of your site\'s visitors have downloaded a PDF or other file you offer your visitors to download on your site? MonsterInsights makes this both easy, and code-free! You can customize the file types to track at any time from our settings panel.', process.env.VUE_APP_TEXTDOMAIN ),
				repeater_structure: [
					{
						name: 'path',
						label: sprintf(__( 'Path (example: %s)', process.env.VUE_APP_TEXTDOMAIN ), '/go/' ),
						pattern: /^\/\S+$/,
						error: __( 'Path has to start with a / and have no spaces', process.env.VUE_APP_TEXTDOMAIN ),
					},
					{
						name: 'label',
						label: sprintf( __( 'Label (example: %s)', process.env.VUE_APP_TEXTDOMAIN ), 'aff' ),
						pattern: /^\S+$/,
						error: __( 'Label can\'t contain any spaces', process.env.VUE_APP_TEXTDOMAIN ),
					},
				],
				text_affiliate_repeater_description: __( 'Helps you increase affiliate revenue.', process.env.VUE_APP_TEXTDOMAIN ),
				text_affiliate_tooltip_content: __( 'MonsterInsights will automatically help you track affiliate links that use internal looking urls like example.com/go/ or example.com/refer/. You can add custom affiliate patterns on our settings panel when you finish the onboarding wizard.', process.env.VUE_APP_TEXTDOMAIN ),
				text_affiliate_label: __( 'Affiliate Link Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_permissions_view_label: __( 'Who Can See Reports', process.env.VUE_APP_TEXTDOMAIN ),
				text_permissions_view_description: __( 'These user roles will be able to access MonsterInsights\'s reports in the WordPress admin area.', process.env.VUE_APP_TEXTDOMAIN ),
				text_permissions_view_tooltip: __( 'Users that have at least one of these roles will be able to view the reports, along with any user with the manage_options capability.', process.env.VUE_APP_TEXTDOMAIN ),
				text_save: __( 'Save and continue', process.env.VUE_APP_TEXTDOMAIN ),
				// Faux tooltips.
				text_events_faux_tooltip: __( 'Events Tracking is enabled the moment you set up MonsterInsights', process.env.VUE_APP_TEXTDOMAIN),
				text_link_attribution_faux_tooltip: __( 'Enhanced Link Attribution is enabled the moment you set up MonsterInsights', process.env.VUE_APP_TEXTDOMAIN),
			};
		},
		methods: {
			handleSubmit() {
				this.$router.push( this.$wizard_steps[3]);
			},
		},
		computed: {
			...mapGetters({
				settings: '$_settings/settings',
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
		},
	};
</script>
