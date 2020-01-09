<template>
	<settings-block :title="text_amp_title">
		<settings-addon-upgrade addon="amp">
			<span v-text="text_amp_upsell"></span>
		</settings-addon-upgrade>
	</settings-block>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import SettingsBlock from '../../SettingsBlock';
	import SettingsAddonUpgrade from '../../SettingsAddonUpgrade';

	export default {
		name: 'SettingsInputAmp',
		components: {
			SettingsAddonUpgrade,
			SettingsBlock,
		},
		data() {
			return {
				text_amp_title: __( 'Google AMP', process.env.VUE_APP_TEXTDOMAIN ),
				text_amp_upsell: __( 'Want to use track users visiting your AMP pages? By upgrading to MonsterInsights Pro, you can enable AMP page tracking.', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};

</script>
