<template>
	<header class="monsterinsights-onboarding-header">
		<nav class="monsterinsights-header-navigation">
			<a :href="href" class="monsterinsights-exit-button">
				<i class="monstericon-times-circle"></i>
				<span v-text="text_exit"></span>
			</a>
		</nav>
		<h1 class="monsterinsights-onboarding-wizard-logo">
			<div class="monsterinsights-logo">
				<div class="monsterinsights-bg-img"></div>
			</div>
		</h1>
	</header>
</template>

<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'TheWizardHeader',
		data() {
			return {
				text_exit: __( 'Exit Setup', process.env.VUE_APP_TEXTDOMAIN ),
				href: this.$mi.exit_url,
			};
		},
	};
</script>
