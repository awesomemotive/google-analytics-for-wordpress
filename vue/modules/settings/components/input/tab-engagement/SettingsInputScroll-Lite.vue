<template>
	<settings-block :title="text_title_scrolling">
		<settings-addon-upgrade addon="scroll">
			<span v-html="text_description"></span>
		</settings-addon-upgrade>
	</settings-block>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import SettingsBlock from "../../SettingsBlock";
	import SettingsAddonUpgrade from "../../SettingsAddonUpgrade";

	export default {
		name: 'SettingsInputScroll',
		components: { SettingsAddonUpgrade, SettingsBlock },
		data() {
			return {
				text_title_scrolling: __( 'Scroll Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_description: __( 'Scroll depth tracking allows you to see how far your users scroll before they leave a page. This is great for publishers (bloggers), and eCommerce websites to boost conversions.', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};
</script>
