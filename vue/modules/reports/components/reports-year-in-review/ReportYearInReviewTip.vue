<template>
	<div class="monsterinsights-yir-year-in-review-tip">
		<div class="monsterinsights-yir-tip-icon">
			<img src="~@/assets/img/icon-tip.png" alt="" />
		</div>
		<div class="monsterinsights-yir-tip-content">
			<h3 class="monsterinsights-yir-tip-content-title" v-text="title"></h3>
			<p class="monsterinsights-yir-tip-content-summary" v-text="summary"></p>
			<div class="monsterinsights-yir-tip-content-link-wrapper">
				<a :href="link" class="monsterinsights-yir-tip-content-link" target="_blank" v-text="linkText"></a>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: 'ReportYearInReviewTip',
		props: {
			title: String,
			summary: String,
			link: String,
			linkText: String,
		},
	};
</script>
