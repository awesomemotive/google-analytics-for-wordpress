<template>
	<div class="monsterinsights-settings-addon-upgrade">
		<div class="monsterinsights-upgrade-icon">
			<div :class="icon_class"></div>
		</div>
		<div class="monsterinsights-settings-addon-message">
			<slot></slot>
		</div>
		<div v-if="upgrade_button" class="settings-addons-upgrade-button">
			<a class="monsterinsights-button monsterinsights-button-green" target="_blank" :href="upgrade_link" v-text="text_button_upgrade"></a>
		</div>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'SettingsAddonUpgrade',
		props: {
			addon: String,
			upgrade_button: {
				type: Boolean,
				default: true,
			},
		},
		data() {
			return {
				text_button_upgrade: __( 'Upgrade', process.env.VUE_APP_TEXTDOMAIN ),
				icon_srcset: this.icon2x + ' 2x',
				upgrade_link: this.$getUpgradeUrl( 'settings-panel', this.addon ),
				icon_class: 'monsterinsights-bg-img monsterinsights-addon-' + this.addon,
			};
		},
	};
</script>
