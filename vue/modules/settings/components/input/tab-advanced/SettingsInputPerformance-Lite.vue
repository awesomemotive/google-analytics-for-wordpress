<template>
	<div class="settings-input settings-input-performance">
		<settings-addon-upgrade addon="performance">
			<span v-html="text_description"></span>
		</settings-addon-upgrade>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import SettingsAddonUpgrade from '../../SettingsAddonUpgrade';

	export default {
		name: 'SettingsInputPerformance',
		components: { SettingsAddonUpgrade },
		data() {
			return {
				text_description: __( 'Adjust the sample rate so you don\'t exceed Google Analytics\' processing limit. Can also be used to enable Google Optimize for A/B testing and personalization.', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};
</script>

