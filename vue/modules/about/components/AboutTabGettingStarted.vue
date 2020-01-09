<template>
	<div class="monsterinsights-container">
		<about-block>
			<div class="monsterinsights-about-page-right-image">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/IbdKpSygp2U" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
			<h3 v-text="text_getting_started_title"></h3>
			<p v-html="text_getting_started_p1"></p>
			<p v-html="text_getting_started_p2"></p>
			<p v-html="text_getting_started_p3"></p>
			<p>
				<a :href="wizard_url" class="monsterinsights-button" v-text="text_getting_started_link1"></a>
			</p>
		</about-block>
		<about-block v-if="showLitePro">
			<h3 v-text="text_get_pro"></h3>
			<p v-html="text_get_pro_text"></p>
			<div class="monsterinsights-separator"></div>
			<div class="monsterinsights-two-column">
				<div class="monsterinsights-list-check">
					<ul>
						<li v-for="(item,index) in check_list" :key="index" v-html="item"></li>
					</ul>
				</div>
				<div class="monsterinsights-list-check">
					<ul>
						<li v-for="(item,index) in check_list_2" :key="index" v-html="item"></li>
					</ul>
				</div>
			</div>
			<div class="monsterinsights-separator"></div>
			<div class="monsterinsights-lite-vs-pro-footer monsterinsights-small">
				<h3><a :href="$getUpgradeUrl('about-page','lite-vs-pro')" v-text="text_get_upgrade"></a></h3>
				<p v-html="text_upgrade_subtitle"></p>
			</div>
		</about-block>
		<about-block>
			<div v-for="(row,index) in docs_rows" :key="index" class="monsterinsights-about-docs-row">
				<div v-if="index>0" class="monsterinsights-separator"></div>
				<div class="monsterinsights-about-docs-image">
					<div :class="row.image"></div>
				</div>
				<div class="monsterinsights-about-docs-text">
					<h3 v-html="row.title"></h3>
					<p v-html="row.text"></p>
					<a :href="row.link" target="_blank" class="monsterinsights-button monsterinsights-button-green"
						v-text="text_documentation"
					></a>
				</div>
			</div>
		</about-block>
	</div>
</template>
<script>
	import { __, sprintf } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import AboutBlock from "./AboutBlock";

	export default {
		name: 'AboutTabGettingStarted',
		components: { AboutBlock },
		data() {
			return {
				text_getting_started_title: __( 'Getting Started with MonsterInsights', process.env.VUE_APP_TEXTDOMAIN ),
				text_getting_started_p1: __( 'MonsterInsights is the easiest analytics solution on the market to get started with, as we walk you through exactly what you need to do, in plain english, using our 3 minute setup wizard.', process.env.VUE_APP_TEXTDOMAIN ),
				text_getting_started_p2: __( 'To begin with, we’ll get your site authorized with Google Analytics, so we can start tracking and generating reports for you right away.', process.env.VUE_APP_TEXTDOMAIN ),
				text_getting_started_p3: __( 'In no time at all, and after just a few clicks, you\'ll have setup the most powerful Google Analytics tracking available for WordPress. It\'s easy to double your traffic and sales when you know exactly how people find and use your website. Let\'s get started!.', process.env.VUE_APP_TEXTDOMAIN ),
				text_getting_started_link1: __( 'Launch the wizard!', process.env.VUE_APP_TEXTDOMAIN ),
				text_get_pro: __( 'Get MonsterInsights Pro and Unlock all the Powerful Features', process.env.VUE_APP_TEXTDOMAIN ),
				text_get_pro_text: sprintf( __( 'Thanks for being a loyal MonsterInsights Lite user. %sUpgrade to MonsterInsights Pro%s to unlock all the awesome features and experience why MonsterInsights is consistently rated the best Google Analytics solution for WordPress.', process.env.VUE_APP_TEXTDOMAIN ), '<strong>', '</strong>' ),
				check_list: [
					__( 'Universal Tracking across devices and campaigns with just a few clicks.', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'See your website analytics reports inside the WordPress dashboard', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Get real-time stats right inside WordPress', process.env.VUE_APP_TEXTDOMAIN ),
					__( '1-click Google Analytics Enhanced Ecommerce tracking', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Get detailed stats for each post and page.', process.env.VUE_APP_TEXTDOMAIN ),
				],
				check_list_2: [
					__( 'Automatically track clicks on your affiliate links and ads.', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Make Google Analytics GDPR compliant automatically', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Setup tracking for authors, categories, tags, custom post types, users and more', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Enable Google Optimize for A/B testing, adjust sample speed & sample rate.', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'More advanced features', process.env.VUE_APP_TEXTDOMAIN ),
				],
				text_get_upgrade: __( 'Get MonsterInsights Pro Today and Unlock all the Powerful Features', process.env.VUE_APP_TEXTDOMAIN ),
				text_upgrade_subtitle: sprintf( __( 'Bonus: MonsterInsights Lite users get %s50%% off regular price%s, automatically applied at checkout.', process.env.VUE_APP_TEXTDOMAIN ), '<span class="monsterinsights-green-text">', '</span>' ),
				docs_rows: [
					{
						image: 'monsterinsights-bg-img monsterinsights-about-docs-1',
						title: __( 'How to Connect to Google Analytics', process.env.VUE_APP_TEXTDOMAIN ),
						text: __( 'After you install MonsterInsights, you’ll need to connect your WordPress site with your Google Analytics account. MonsterInsights makes the process easy, with no coding required.', process.env.VUE_APP_TEXTDOMAIN ),
						link: this.$getUrl( 'about-page', 'documentation', 'https://www.monsterinsights.com/docs/connect-google-analytics/' ),
					},
					{
						image: 'monsterinsights-bg-img monsterinsights-about-docs-2',
						title: __( 'Guide and Checklist for Advanced Insights', process.env.VUE_APP_TEXTDOMAIN ),
						text: __( 'Our goal is to make it as easy as possible for you to measure and track your stats so you can grow your business. This easy-to-follow guide and checklist will get you set up with MonsterInsights’ advanced tracking.', process.env.VUE_APP_TEXTDOMAIN ),
						link: this.$getUrl( 'about-page', 'documentation', 'https://www.monsterinsights.com/docs/getting-started-guide-checklist/' ),
					},
					{
						image: 'monsterinsights-bg-img monsterinsights-about-docs-3',
						title: __( 'GDPR Guide', process.env.VUE_APP_TEXTDOMAIN ),
						text: __( 'Compliance with European data laws including GDPR can be confusing and time-consuming. In order to help MonsterInsights users comply with these laws, we’ve created an addon that automates a lot of the necessary configuration changes for you. ', process.env.VUE_APP_TEXTDOMAIN ),
						link: this.$getUrl( 'about-page', 'documentation', 'https://www.monsterinsights.com/docs/getting-started-with-the-eu-compliance-addon/' ),
					},
					{
						image: 'monsterinsights-bg-img monsterinsights-about-docs-4',
						title: __( 'How to install and activate MonsterInsights addons', process.env.VUE_APP_TEXTDOMAIN ),
						text: __( 'The process for installing and activating addons is quick and easy after you install the MonsterInsights plugin. In this guide we’ll walk you through the process, step by step.', process.env.VUE_APP_TEXTDOMAIN ),
						link: this.$getUrl( 'about-page', 'documentation', 'https://www.monsterinsights.com/docs/how-to-install-monsterinsights-addon/' ),
					},
					{
						image: 'monsterinsights-bg-img monsterinsights-about-docs-5',
						title: __( 'Enabling eCommerce Tracking and Reports', process.env.VUE_APP_TEXTDOMAIN ),
						text: __( 'Want to track your eCommerce sales data for your WooCommerce, MemberPress, or Easy Digital Downloads store with MonsterInsights? In this guide, we’ll show you how to enable eCommerce tracking in Google Analytics in just a few clicks.', process.env.VUE_APP_TEXTDOMAIN ),
						link: this.$getUrl( 'about-page', 'documentation', 'https://www.monsterinsights.com/docs/enable-ecommerce-tracking/' ),
					},
				],
				text_documentation: __( 'Read Documentation', process.env.VUE_APP_TEXTDOMAIN ),
				wizard_url: this.$mi.wizard_url,
			};
		},
		computed: {
			...mapGetters( {
				license: '$_license/license',
				license_network: '$_license/license_network',
			} ),
			showLitePro() {
				const licenseType = this.$mi.network ? this.license_network.type : this.license.type;
				return ! (
					this.$isPro() && '' !== licenseType
				);
			},
		},
	};
</script>
