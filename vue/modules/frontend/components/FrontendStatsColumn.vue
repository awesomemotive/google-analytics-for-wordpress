<template>
	<div :class="columnClass">
		<div v-if="label" class="monsterinsights-stats-label" v-text="label"></div>
		<div v-if="value" class="monsterinsights-stats-value"
			v-html="value"
		></div>
		<slot></slot>
	</div>
</template>
<script>
	export default {
		name: 'FrontendStatsColumn',
		props: {
			extraClass: {
				type: String,
				default: '',
			},
			label: String,
			value: String,
		},
		computed: {
			columnClass() {
				return 'monsterinsights-stats-column ' + this.extraClass;
			},
		},
	};
</script>
