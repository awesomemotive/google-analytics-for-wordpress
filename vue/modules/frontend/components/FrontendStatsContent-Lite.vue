<template>
	<div class="monsterinsights-content-lite">
		<frontend-stats-general />
	</div>
</template>
<script>
	import FrontendStatsGeneral from "./FrontendStatsGeneral";

	export default {
		name: 'FrontendStatsContent',
		components: { FrontendStatsGeneral },
	};
</script>
