<template>
	<div class="monsterinsights-welcome-overlay" v-on:click="maybeClose">
		<div class="monsterinsights-welcome-overlay-inner">
			<button class="monsterinsights-overlay-close" v-on:click="close">
				<span class="monstericon-times"></span>
			</button>
			<div class="monsterinsights-welcome-overlay-content">
				<slot></slot>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: 'WelcomeOverlay',
		props: {
			id: String,
		},
		methods: {
			close() {
				this.$emit( 'close', this.id );
			},
			maybeClose(e) {
				if ( e.target.classList.contains( 'monsterinsights-welcome-overlay' ) ) {
					this.close();
				}
			},
		},
	};
</script>
