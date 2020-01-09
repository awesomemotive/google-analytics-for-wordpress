<template>
	<div class="settings-input settings-input-usage-tracking">
		<settings-block :title="text_block_title">
			<p v-html="text_description"></p>
			<settings-input-checkbox name="usage_tracking" :label="text_input_label" :tooltip="text_usage_tracking_tooltip"></settings-input-checkbox>
		</settings-block>
	</div>
</template>

<script>
	import { __, sprintf } from '@wordpress/i18n';
	import SettingsBlock from '../../SettingsBlock';
	import SettingsInputCheckbox from '../SettingsInputCheckbox';

	export default {
		name: 'SettingsInputUsageTracking',
		components: {
			SettingsInputCheckbox,
			SettingsBlock,
		},
		props: {
			label: String,
		},
		data() {
			return {
				is_network: this.$mi.network,
				text_block_title: __( 'Usage Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_input_label: __( 'Allow Usage Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_description: __( 'By allowing us to track usage data we can better help you because we know with which WordPress configurations, themes and plugins we should test.', process.env.VUE_APP_TEXTDOMAIN ),
				text_usage_tracking_tooltip: sprintf( __( 'Complete documentation on usage tracking is available %1$shere%2$s.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="' + this.$getUrl( 'settings-panel', 'usage-tracking', 'https://www.monsterinsights.com/docs/usage-tracking/' ) + '" target="_blank">', '</a>' ),
			};
		},
	};
</script>
