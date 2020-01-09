<template>
	<header>
		<h2 v-html="title"></h2>
		<p v-if="subtitle" class="subtitle" v-html="subtitle"></p>
	</header>
</template>
<script>
	export default {
		name: 'OnboardingContentHeader',
		props: {
			title: String,
			subtitle: String,
		},
	};
</script>
