<template>
	<settings-block :title="text_ads_title">
		<settings-addon-upgrade addon="ads">
			<span v-text="text_ads_upsell"></span>
		</settings-addon-upgrade>
	</settings-block>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import SettingsBlock from '../../SettingsBlock';
	import SettingsAddonUpgrade from '../../SettingsAddonUpgrade';

	export default {
		name: 'SettingsInputAds',
		components: {
			SettingsAddonUpgrade,
			SettingsBlock,
		},
		data() {
			return {
				text_ads_title: __( 'Ads Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_ads_upsell: __( 'Add Ads tracking to see who\'s clicking on your Google Ads, so you can increase your revenue.', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};

</script>
