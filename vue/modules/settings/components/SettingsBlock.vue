<template>
	<div class="monsterinsights-settings-block">
		<div class="monsterinsights-settings-block-title">
			{{ title }}
		</div>
		<div class="monsterinsights-settings-block-content">
			<slot></slot>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'SettingsBlock',
		props: {
			title: String,
		},
	};
</script>
