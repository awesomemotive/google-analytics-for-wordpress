<template>
	<a :href="upgradeUrl" class="monsterinsights-button" v-text="text_button"></a>
</template>
<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'FrontendUpsell',
		data() {
			return {
				text_button: __( 'Upgrade to PRO', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			upgradeUrl() {
				return this.$getUpgradeUrl( 'frontend-reports', 'admin-bar' );
			},
		},
	};
</script>
