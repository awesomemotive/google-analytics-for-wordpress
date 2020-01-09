<template>
	<div class="monsterinsights-widget-settings">
		<div class="monsterinsights-widget-interval-static" v-text="sprintf(text_last_days, 30)"></div>
		<widget-settings-width ref="width" />
		<widget-settings-reports />
	</div>
</template>
<script>
	import { __, sprintf } from '@wordpress/i18n';
	import WidgetSettingsReports from "./settings/WidgetSettingsReports-Lite";
	import WidgetSettingsWidth from "./settings/WidgetSettingsWidth";

	export default {
		name: 'WidgetSettings',
		components: { WidgetSettingsWidth, WidgetSettingsReports },
		data() {
			return {
				text_last_days: __( 'Last %s days', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		methods: {
			toggleFullWidth() {
				this.$refs.width.toggleFullWidth( true );
			},
			sprintf,
		},
	};
</script>
