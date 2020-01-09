<template>
	<main class="monsterinsights-settings-content settings-general">
		<template v-if="hasAuth">
			<settings-block :title="text_license_title">
				<settings-input-license></settings-input-license>
			</settings-block>
			<settings-block :title="text_auth_title">
				<settings-input-authenticate :label="text_auth_label" :description="text_auth_description"></settings-input-authenticate>
			</settings-block>
		</template>
		<template v-else>
			<settings-block :title="text_auth_title">
				<settings-input-authenticate :label="text_auth_label" :description="text_auth_description"></settings-input-authenticate>
			</settings-block>
			<settings-block :title="text_license_title">
				<settings-input-license></settings-input-license>
			</settings-block>
		</template>
		<settings-block v-if="settings.automatic_updates === 'minor' || settings.automatic_updates === 'none'" :title="text_automatic_updates">
			<settings-input-radio :options="automatic_updates" name="automatic_updates"></settings-input-radio>
		</settings-block>
		<template v-if="hasAuth">
			<settings-input-usage-tracking v-if="show_usage_tracking"></settings-input-usage-tracking>
			<settings-block :title="text_setup_wizard_title">
				<label v-text="text_setup_wizard_label"></label>
				<a :href="wizard_url" class="monsterinsights-button" v-text="text_setup_wizard_button"></a>
			</settings-block>
		</template>
		<template v-else>
			<settings-block :title="text_setup_wizard_title">
				<label v-text="text_setup_wizard_label"></label>
				<a :href="wizard_url" class="monsterinsights-button" v-text="text_setup_wizard_button"></a>
			</settings-block>
			<settings-input-usage-tracking v-if="show_usage_tracking"></settings-input-usage-tracking>
		</template>
		<settings-lite-upsell-large />
	</main>
</template>

<script>
	import { mapGetters } from 'vuex';
	import { __, sprintf } from '@wordpress/i18n';
	import SettingsBlock from '../SettingsBlock';
	import SettingsInputRadio from '../input/SettingsInputRadio';
	import SettingsInputLicense from '../input/tab-general/SettingsInputLicense-Lite';
	import SettingsInputUsageTracking from '../input/tab-general/SettingsInputUsageTracking-Lite';
	import SettingsInputAuthenticate from '../input/tab-general/SettingsInputAuthenticate-Lite';
	import SettingsLiteUpsellLarge from "../SettingsLiteUpsellLarge";

	export default {
		name: 'SettingsTabGeneral',
		components: {
			SettingsLiteUpsellLarge,
			SettingsInputUsageTracking,
			SettingsInputAuthenticate,
			SettingsInputLicense,
			SettingsInputRadio,
			SettingsBlock,
		},
		computed: {
			...mapGetters({
				auth: '$_auth/auth',
				settings: '$_settings/settings',
			}),
			hasAuth() {
				let is_authed = this.auth.network_ua ? this.auth.network_ua : this.auth.ua;
				return '' !== is_authed;
			},
		},
		data() {
			return {
				is_loading: false,
				has_error: false,
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
				text_license_title: __( 'License Key', process.env.VUE_APP_TEXTDOMAIN ),
				text_auth_title: __( 'Google Authentication', process.env.VUE_APP_TEXTDOMAIN ),
				text_auth_label: __( 'Connect Google Analytics + WordPress', process.env.VUE_APP_TEXTDOMAIN ),
				text_auth_description: __( 'You will be taken to the MonsterInsights website where you\'ll need to connect your Analytics account.', process.env.VUE_APP_TEXTDOMAIN ),
				text_automatic_updates: __( 'Automatic Updates', process.env.VUE_APP_TEXTDOMAIN ),
				text_setup_wizard_title: __( 'Setup Wizard', process.env.VUE_APP_TEXTDOMAIN ),
				text_setup_wizard_label: __( 'Use our configuration wizard to properly setup Google Analytics with WordPress (with just a few clicks).', process.env.VUE_APP_TEXTDOMAIN ),
				text_setup_wizard_button: __( 'Launch Setup Wizard', process.env.VUE_APP_TEXTDOMAIN ),
				wizard_url: this.$mi.wizard_url,
				show_usage_tracking: false,
				loaded_settings: false,
			};
		},
		watch: {
			settings: function( value ) {
				if ( ! this.loaded_settings ) {
					this.loaded_settings = true;
					this.show_usage_tracking = ! value['usage_tracking'];
				}
			},
		},
		methods: {
			__: __,
		},
	};
</script>
