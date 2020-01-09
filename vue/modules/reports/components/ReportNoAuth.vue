<template>
	<div class="monsterinsights-not-authenticated-notice">
		<h3 v-text="text_no_auth"></h3>
		<div class="monsterinsights-settings-input monsterinsights-settings-input-authenticate">
			<span class="monsterinsights-dark" v-text="text_auth_label"></span>
			<div v-if="update_settings">
				<a slot="before_connect" :href="wizard_url" class="monsterinsights-button-alt monsterinsights-button" v-text="text_wizard"></a>
			</div>
			<div v-else>
				<p class="monsterinsights-dark">
					<strong v-text="text_ask_webmaster"></strong>
				</p>
			</div>
		</div>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'ReportNoAuth',
		data() {
			return {
				text_no_auth: __( 'You must connect with MonsterInsights before you can view reports.', process.env.VUE_APP_TEXTDOMAIN ),
				text_auth_label: __( 'MonsterInsights makes it "effortless" for you to connect your site with Google Analytics and see reports right here in the WordPress dashboard.', process.env.VUE_APP_TEXTDOMAIN ),
				text_wizard: __( 'Launch Setup Wizard', process.env.VUE_APP_TEXTDOMAIN ),
				text_ask_webmaster: __( 'Please ask your webmaster to connect MonsterInsights to Google Analytics.', process.env.VUE_APP_TEXTDOMAIN ),
				wizard_url: this.$mi.wizard_url,
				update_settings: this.$mi.update_settings,
			};
		},
	};
</script>
