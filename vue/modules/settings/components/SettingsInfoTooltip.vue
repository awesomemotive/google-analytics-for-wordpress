<template>
	<span v-tooltip="tooltip_data" class="monsterinsights-info" tabindex="0">
		<i class="monstericon monstericon-info-circle-regular"></i>
	</span>
</template>
<script>

	export default {
		name: 'SettingsInfoTooltip',
		props: {
			content: String,
		},
		data() {
			return {
				tooltip_data: {
					content: this.content,
					autoHide: false,
					trigger: 'hover focus click',
				},
			};
		},
	};
</script>
