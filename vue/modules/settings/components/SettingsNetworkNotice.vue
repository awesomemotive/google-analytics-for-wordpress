<template>
	<div class="monsterinsights-settings-network-notice">
		<div>
			<div class="monsterinsights-bg-img monsterinsights-icon-warning"></div>
		</div>
		<div class="monsterinsights-network-message">
			<slot></slot>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'SettingsNetworkNotice',
	};
</script>

<style scoped>
	strong {
		display: block;
	}
</style>
