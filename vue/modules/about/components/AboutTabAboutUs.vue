<template>
	<div class="monsterinsights-container">
		<about-block>
			<figure class="monsterinsights-about-page-right-image">
				<div class="monsterinsights-bg-img monsterinsights-about-team"></div>
				<figcaption v-html="text_team_members">
				</figcaption>
			</figure>
			<h3 v-text="text_about_title"></h3>
			<p v-html="text_about_p1"></p>
			<p v-html="text_about_p2"></p>
			<p v-html="text_about_p3"></p>
			<p v-html="text_about_p4"></p>
		</about-block>
		<div class="monsterinsights-addons-list">
			<addon-block v-for="(addon,index) in addonsList()" :key="index" :addon="addon" :is-addon="false" />
		</div>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import AboutBlock from "./AboutBlock";
	import AddonBlock from "../../addons/components/AddonBlock";

	export default {
		name: 'AboutTabAboutUs',
		components: { AddonBlock, AboutBlock },
		data() {
			return {
				text_about_title: __( 'Hello and welcome to MonsterInsights, the best Google Analytics plugin for WordPress. MonsterInsights shows you exactly which content gets the most visit, so you can analyze and optimize it for higher conversions.', process.env.VUE_APP_TEXTDOMAIN ),
				text_about_p1: __( 'Over the years, we found that in order to get the most out of Google Analytics, you needed a full time developer who could implement custom tracking, so that Google Analytics would integrate with things like WooCommerce, and track things which Google doesn\'t by default, like outbound links.', process.env.VUE_APP_TEXTDOMAIN ),
				text_about_p2: __( 'Our goal is to take the pain out of analytics, making it simple and easy, by eliminating the need to have to worry about code, putting the best reports directly into the area you already go to (your WordPress dashboard), and adding the most advanced insights and features without complicating our plugin with tons of settings. Quite simply, it should "just work".', process.env.VUE_APP_TEXTDOMAIN ),
				text_about_p3: __( 'MonsterInsights is brought to you by the same team that\'s behind the largest WordPress resource site, WPBeginner, the most popular lead-generation software, OptinMonster, and the best WordPress forms plugin, WPForms.', process.env.VUE_APP_TEXTDOMAIN ),
				text_about_p4: __( 'Yup, we know a thing or two about building awesome products that customers love.', process.env.VUE_APP_TEXTDOMAIN ),
				text_team_members: __( 'The MonsterInsights Team', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				addons: '$_addons/addons',
			} ),
		},
		methods: {
			addonsList() {
				let addonsIncluded = [
					'wpforms',
					'optinmonster',
					'wp-mail-smtp',
				];
				let addons = [];

				addonsIncluded.forEach( ( addon_slug ) => {
					if ( this.addons[addon_slug] ) {
						let addon = Object.create( this.addons[addon_slug] );
						addon.type = 'licensed';
						addons.push( addon );
					}
				} );

				return addons;
			},
		},
	};
</script>
