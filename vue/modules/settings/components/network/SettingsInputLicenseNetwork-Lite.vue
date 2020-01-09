<template>
	<div class="settings-input settings-input-license">
		<p v-html="text_license_row_1"></p>
		<p v-html="text_license_row_2"></p>
		<p v-html="text_license_row_3"></p>
	</div>
</template>

<script>
	import { __, sprintf } from '@wordpress/i18n';

	export default {
		name: 'SettingsInputLicenseNetwork',
		props: {
			label: String,
		},
		data() {
			return {
				is_network: this.$mi.network,
				text_license_row_1: sprintf( __( 'You\'re using %1$sMonsterInsights Lite%2$s - no license needed. Enjoy!', process.env.VUE_APP_TEXTDOMAIN ), '<strong>', '</strong>' ),
				text_license_row_2: sprintf( __( 'To unlock more features consider %1$supgrading to PRO%2$s.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="#" class="monsterinsights-bold">', '</a>' ),
				text_license_row_3: sprintf( __( 'As a valued MonsterInsights Lite user you %1$sreceive 50%% off%2$s, automatically applied at checkout!', process.env.VUE_APP_TEXTDOMAIN ), '<span class="monsterinsights-highlighted-text">', '</span>' ),
			};
		},
	};
</script>
