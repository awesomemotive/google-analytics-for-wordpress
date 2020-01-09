<template>
	<div class="monsterinsights-reports-link">
		<span v-text="text_see_all"></span> <a :href="$mi.reports_url" class="monsterinsights-button monsterinsights-button-small" v-text="text_go_to"></a>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'WidgetReportsLink',
		data() {
			return {
				text_see_all: __( 'See All Reports', process.env.VUE_APP_TEXTDOMAIN ),
				text_go_to: __( 'Go to the Analytics Dashboard', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};
</script>
