<template>
	<header class="monsterinsights-header">
		<the-floating-bar />
		<div class="monsterinsights-container">
			<div class="monsterinsights-logo-area">
				<img :src="logo" :srcset="logo2x" />
			</div>
			<div class="float-right">
				<slot></slot>
			</div>
		</div>
	</header>
</template>

<script>

	import TheFloatingBar from "./TheFloatingBar-MI_VERSION";
	export default {
		name: 'TheAppHeader',
		components: { TheFloatingBar },
		data: function() {
			return {
				logo: this.$mi.assets + '/img/logo.png',
				logo2x: this.$mi.assets + '/img/logo@2x.png 2x',
			};
		},
	};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
	.float-right {
		float: right;
	}

	.monsterinsights-container:after {
		display: table;
		clear: both;
		content: '';
	}
</style>
