<template>
	<a :href="upgrade_url" class="monsterinsights-powered-by" v-text="text_powered_by"></a>
</template>

<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'FrontendPoweredBy',
		data() {
			return {
				text_powered_by: __( 'Powered by MonsterInsights', process.env.VUE_APP_TEXTDOMAIN ),
				upgrade_url: this.$getUpgradeUrl( 'admin-bar', 'powered-by' ),
			};
		},
	};
</script>
