<template>
	<div class="monsterinsights-about-navbar">
		<router-link class="monsterinsights-navigation-tab-link" to="/about" v-text="text_about_us" />
		<router-link class="monsterinsights-navigation-tab-link" to="/about/getting-started" v-text="text_getting_started" />
		<router-link class="monsterinsights-navigation-tab-link" to="/about/lite-vs-pro" v-text="text_lite_vs_pro" />
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'AboutNavigation',
		data() {
			return {
				text_about_us: __( 'About Us', process.env.VUE_APP_TEXTDOMAIN ),
				text_getting_started: __( 'Getting Started', process.env.VUE_APP_TEXTDOMAIN ),
				text_lite_vs_pro: __( 'Lite vs Pro', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};
</script>
