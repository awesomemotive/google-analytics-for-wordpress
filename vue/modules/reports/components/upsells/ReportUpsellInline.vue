<template>
	<div class="monsterinsights-report-box monsterinsights-upsell-inline">
		<div class="monsterinsights-upsell-inline-content">
			<h3 v-text="title"></h3>
			<div class="monsterinsights-upsell-content">
				<slot></slot>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: 'ReportUpsellInline',
		props: {
			title: String,
		},
	};
</script>
