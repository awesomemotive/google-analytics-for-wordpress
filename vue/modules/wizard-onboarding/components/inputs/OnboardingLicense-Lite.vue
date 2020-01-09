<template>
	<div class="monsterinsights-onboarding-license-lite">
		<div class="monsterinsights-separator"></div>
		<label v-text="text_license_label"></label>
		<settings-input-license></settings-input-license>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import SettingsInputLicense from '../../../settings/components/input/tab-general/SettingsInputLicense-MI_VERSION';

	export default {
		name: 'OnboardingLicense',
		components: {
			SettingsInputLicense,
		},
		computed: {
			...mapGetters({
				license: '$_license/license',
				license_network: '$_license/license_network',
				auth: '$_auth/auth',
			}),
		},
		data() {
			return {
				text_license_label: __( 'License Key', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};
</script>
