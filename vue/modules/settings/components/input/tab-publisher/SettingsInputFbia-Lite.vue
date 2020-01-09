<template>
	<settings-block :title="text_fbia_title">
		<settings-addon-upgrade addon="instant-articles">
			<span v-text="text_fbia_upsell"></span>
		</settings-addon-upgrade>
	</settings-block>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import SettingsBlock from '../../SettingsBlock';
	import SettingsAddonUpgrade from '../../SettingsAddonUpgrade';

	export default {
		name: 'SettingsInputFbia',
		components: {
			SettingsAddonUpgrade,
			SettingsBlock,
		},
		data() {
			return {
				text_fbia_title: __( 'Facebook Instant Articles', process.env.VUE_APP_TEXTDOMAIN ),
				text_fbia_upsell: __( 'Want to expand your website audience beyond your website with Facebook Instant Articles? Upgrade to MonsterInsights Pro.', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};

</script>
