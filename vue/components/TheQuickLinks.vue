<template>
	<div :class="boxClass">
		<button class="monsterinsights-quick-links-label" v-on:click.stop="showMenu=!showMenu">
			<span class="monsterinsights-bg-img monsterinsights-quick-links-mascot"></span>
			<span class="monsterinsights-quick-link-title" v-text="text_see_quick"></span>
		</button>
		<transition-group tag="div" class="monsterinsights-quick-links-menu" name="monsterinsights-staggered-fade"
			v-on:enter="enter" v-on:leave="leave"
		>
			<template v-if="showMenu">
				<a v-for="(item, index) in menuItems" :key="item.key" :href="item.link"
					:data-index="index" class="monsterinsights-quick-links-menu-item" target="_blank"
				>
					<span :class="item.icon"></span>
					<span class="monsterinsights-quick-link-title" v-html="item.tooltip"></span>
				</a>
			</template>
		</transition-group>
	</div>
</template>
<script>
	import '@/assets/scss/MI_THEME/quick-links.scss';
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';

	export default {
		name: 'TheQuickLinks',
		data() {
			return {
				showMenu: false,
				text_see_quick: __( 'See Quick Links', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				license: '$_license/license',
				license_network: '$_license/license_network',
			} ),
			boxClass() {
				let boxClass = 'monsterinsights-quick-links';

				if ( this.showMenu ) {
					boxClass += ' monsterinsights-quick-links-open';
				}

				return boxClass;
			},
			licenseLevel() {
				return this.$mi.network ? this.license_network.type : this.license.type;
			},
			showUpsell() {
				return 'plus' === this.licenseLevel || 'basic' === this.licenseLevel || '' === this.licenseLevel;
			},
			menuItems() {
				let items = [
					{
						icon: 'monstericon-lightbulb',
						tooltip: __( 'Suggest a Feature', process.env.VUE_APP_TEXTDOMAIN ),
						link: this.$getUrl( 'quick-links', 'suggest-feature', 'https://www.monsterinsights.com/customer-feedback/' ),
						key: 'suggest',
					},
					{
						icon: 'monstericon-wpbeginner',
						tooltip: __( 'Join Our Community', process.env.VUE_APP_TEXTDOMAIN ),
						link: this.$getUrl( 'quick-links', 'suggest-feature', 'https://www.facebook.com/groups/wpbeginner/' ),
						key: 'community',
					},
					{
						icon: 'monstericon-life-ring',
						tooltip: __( 'Support & Docs', process.env.VUE_APP_TEXTDOMAIN ),
						link: this.$getUrl( 'quick-links', 'support', 'https://www.monsterinsights.com/docs/' ),
						key: 'support',
					},
				];
				if ( this.showUpsell ) {
					items.unshift( {
						icon: 'monstericon-shopping-cart',
						tooltip: __( 'Upgrade to Pro &#187;', process.env.VUE_APP_TEXTDOMAIN ),
						link: this.$getUpgradeUrl( 'quick-links', 'upgrade' ),
						key: 'upgrade',
					} );
				}

				return items;
			},
		},
		methods: {
			enter: function( el, done ) {
				const delay = el.dataset.index * 50;
				setTimeout( function() {
					el.classList.add( 'monsterinsights-show' );
					done();
				}, delay );
			},
			leave: function( el, done ) {
				el.classList.remove( 'monsterinsights-show' );
				setTimeout( function() {
					done();
				}, 200 );
			},
		},
	};
</script>
