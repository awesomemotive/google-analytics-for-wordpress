<template>
	<div class="monsterinsights-addon-row">
		<div class="monsterinsights-addon-icon">
			<div :class="icon_class"></div>
		</div>
		<div class="monsterinsights-addon-text">
			<label v-text="addon.title"></label>
			<p v-html="addon.excerpt"></p>
			<a :href="href" target="_blank" v-text="text_learn_more"></a>
		</div>
		<div class="monsterinsights-addon-button">
			<a target="_blank" :href="upgrade_url" class="monsterinsights-onboarding-button monsterinsights-onboarding-button-regular" v-text="text_upgrade"></a>
		</div>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'OnboardingAddon',
		props: {
			addon: Object,
		},
		data() {
			return {
				text_learn_more: __( 'Learn More', process.env.VUE_APP_TEXTDOMAIN ),
				text_upgrade: __( 'Upgrade to Pro', process.env.VUE_APP_TEXTDOMAIN ),
				href: 'https://www.monsterinsights.com/addons/' + this.addon.slug,
				icon_class: 'monsterinsights-addon-icon-' + this.addon.slug,
				upgrade_url: this.$getUpgradeUrl( 'onboarding-wizard', 'addons' ),
			};
		},
	};
</script>
