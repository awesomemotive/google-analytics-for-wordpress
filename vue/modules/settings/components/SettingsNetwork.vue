<template>
	<div class="monsterinsights-settings-content settings-network">
		<settings-block :title="text_license_title">
			<settings-input-license-network :label="text_license_label"></settings-input-license-network>
		</settings-block>
		<settings-block :title="text_auth_title">
			<settings-input-authenticate :label="text_auth_label"
				:description="text_auth_description"
			></settings-input-authenticate>
		</settings-block>
		<settings-block :title="text_misc_title">
			<p>
				<span class="monsterinsights-dark" v-html="text_announcements_title"></span>
				<span v-html="text_announcements_description"></span>
			</p>
			<settings-input-checkbox name="hide_am_notices" :label="text_announcements_label"></settings-input-checkbox>
		</settings-block>
	</div>
</template>

<script>
	import { __, sprintf } from '@wordpress/i18n';
	import SettingsBlock from "./SettingsBlock";
	import SettingsInputLicenseNetwork from "./network/SettingsInputLicenseNetwork-MI_VERSION";
	import SettingsInputAuthenticate from "./input/tab-general/SettingsInputAuthenticate-MI_VERSION";
	import SettingsInputCheckbox from "./input/SettingsInputCheckbox";

	export default {
		name: 'SettingsNetwork',
		components: { SettingsInputCheckbox, SettingsInputAuthenticate, SettingsInputLicenseNetwork, SettingsBlock },
		data() {
			return {
				text_license_title: __( 'License Key', process.env.VUE_APP_TEXTDOMAIN ),
				text_license_label: sprintf( __( 'Add your MonsterInsights license key from the email receipt or account area. %1$sRetrieve your license key%2$s.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="#">', '</a>' ),
				text_auth_title: __( 'Google Authentication', process.env.VUE_APP_TEXTDOMAIN ),
				text_auth_label: __( 'Connect Google Analytics + WordPress', process.env.VUE_APP_TEXTDOMAIN ),
				text_auth_description: __( 'You will be taken to the MonsterInsights website where you\'ll need to connect your Analytics account.', process.env.VUE_APP_TEXTDOMAIN ),
				text_misc_title: __( 'Miscellaneous', process.env.VUE_APP_TEXTDOMAIN ),
				text_announcements_title: __( 'Hide Announcements', process.env.VUE_APP_TEXTDOMAIN ),
				text_announcements_description: __( 'Hides plugin announcements and update details. This includes critical notices we use to inform about deprecations and important required configuration changes.', process.env.VUE_APP_TEXTDOMAIN ),
				text_announcements_label: __( 'Hide Announcements', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};
</script>

<style lang="scss" scoped>
    .monsterinsights-dark {
        display: block;
    }
</style>

