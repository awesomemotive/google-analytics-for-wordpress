<template>
	<transition name="monsterinsights-slide">
		<div v-if="showBar" class="monsterinsights-floating-bar">
			<span v-html="barText"></span>
			<button class="monsterinsights-floating-bar-close" v-on:click="hideBar">
				<span
					class="monstericon-times"
				></span>
			</button>
		</div>
	</transition>
</template>
<script>
	import '@/assets/scss/MI_THEME/components/floatbar.scss';
	import { __, sprintf } from '@wordpress/i18n';
	import axios from 'axios';

	export default {
		name: 'TheFloatingBar',
		data() {
			return {
				showBar: false,
				barLink: this.$getUpgradeUrl( 'floatbar', 'upgrade' ),
			};
		},
		computed: {
			barText() {
				return sprintf( __( 'You’re using MonsterInsights Lite. To unlock more features consider %supgrading to Pro%s.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="' + this.barLink + '" target="_blank">', '</a>' );
			},
		},
		methods: {
			hideBar() {
				this.showBar = false;
				let formData = new FormData();
				formData.append( 'action', 'monsterinsights_hide_floatbar' );
				formData.append( 'nonce', this.$mi.nonce );
				axios.post( this.$mi.ajax, formData );
			},
			getBarStatus() {
				const self = this;
				let formData = new FormData();
				formData.append( 'action', 'monsterinsights_get_floatbar' );
				formData.append( 'nonce', this.$mi.nonce );
				axios.post( this.$mi.ajax, formData ).then( function( response ) {
					self.showBar = response.data.show;
				} ).catch( function() {
					self.showBar = false;
				} );
			},
		},
		mounted: function() {
			const self = this;
			// Give it some time so more important calls are triggered first.
			setTimeout( function() {
				self.getBarStatus();
			}, 1500 );
		},
	};
</script>
