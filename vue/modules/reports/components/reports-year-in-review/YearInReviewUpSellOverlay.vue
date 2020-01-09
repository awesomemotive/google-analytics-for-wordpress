<template>
	<div class="monsterinsights-yir-year-in-review-upsell-overlay">
		<div class="monsterinsights-yir-upsell-content">
			<p class="monsterinsights-yir-details" v-text="details"></p>
			<a :href="btnLink" :class="['monsterinsights-yir-btn-upsell-overlay', btnClass]" target="_blank" v-text="btnText"></a>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'YearInReviewUpSellOverlay',
		props: {
			details: String,
			btnText: String,
			btnLink: String,
			btnClass: String,
		},
	};
</script>
