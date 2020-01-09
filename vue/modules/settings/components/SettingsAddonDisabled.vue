<template>
	<div class="monsterinsights-settings-addon-upgrade monsterinsights-settings-addon-disabled">
		<div>
			<div class="monsterinsights-bg-img monsterinsights-icon-alert"></div>
		</div>
		<div class="monsterinsights-settings-addon-message">
			<slot v-if="install_plugins"></slot>
			<span v-else v-text="text_cant_install"></span>
		</div>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'SettingsAddonDisabled',
		data() {
			return {
				install_plugins: this.$mi.install_plugins,
				text_cant_install: __( 'In order to use these features, please ask your webmaster to install the necessary addon.', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};
</script>
