<template>
	<settings-block :title="text_title_eu_compliance_title">
		<settings-addon-upgrade addon="eu-compliance">
			<span v-html="text_description"></span>
		</settings-addon-upgrade>
	</settings-block>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import SettingsAddonUpgrade from '../../SettingsAddonUpgrade';
	import SettingsBlock from '../../SettingsBlock';

	export default {
		name: 'SettingsInputEUCompliance',
		components: { SettingsBlock, SettingsAddonUpgrade },
		data() {
			return {
				text_upgrade: __( 'Upgrade', process.env.VUE_APP_TEXTDOMAIN ),
				text_description: __( 'The EU Compliance addon allows you to improve compliance with GDPR\n and other privacy regulations.', process.env.VUE_APP_TEXTDOMAIN ),
				text_title_eu_compliance_title: __( 'EU Compliance', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};
</script>

