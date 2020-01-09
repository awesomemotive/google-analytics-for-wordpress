<template>
	<div class="monsterinsights-tools-navbar">
		<router-link class="monsterinsights-navigation-tab-link" to="/tools" v-text="text_url_builder"></router-link>
		<router-link class="monsterinsights-navigation-tab-link" to="/tools/import-export" v-text="text_import"></router-link>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'ToolsNavigation',
		data() {
			return {
				text_import: __( 'Import Export', process.env.VUE_APP_TEXTDOMAIN ),
				text_url_builder: __( 'URL Builder', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};
</script>
