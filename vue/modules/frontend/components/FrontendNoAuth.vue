<template>
	<div class="monsterinsights-not-authenticated-notice">
		<h3 v-text="text_no_auth"></h3>
		<div class="monsterinsights-settings-input monsterinsights-settings-input-authenticate">
			<p v-text="text_auth_label"></p>
			<div>
				<a :href="wizard_url" class="monsterinsights-wp-button monsterinsights-wp-button-primary"
					v-text="text_wizard"
				></a>
				<a :href="learn_link" class="monsterinsights-wp-button"
					v-text="text_learn"
				></a>
			</div>
		</div>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'FrontendNoAuth',
		data() {
			return {
				text_no_auth: __( 'Please Setup Website Analytics to See Audience Insights', process.env.VUE_APP_TEXTDOMAIN ),
				text_auth_label: __( 'MonsterInsights, WordPress analytics plugin, helps you connect your website with Google Analytics, so you can see how people find and use your website. Over 2 million website owners use MonsterInsights to see the stats that matter and grow their business.', process.env.VUE_APP_TEXTDOMAIN ),
				text_wizard: __( 'Connect MonsterInsights and Setup Website Analytics', process.env.VUE_APP_TEXTDOMAIN ),
				text_learn: __( 'Learn More', process.env.VUE_APP_TEXTDOMAIN ),
				wizard_url: this.$mi.wizard_url,
				learn_link: this.$mi.getting_started_url,
			};
		},
	};
</script>
