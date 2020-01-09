<template>
	<div>
		<button class="monsterinsights-mobile-nav-trigger" v-on:click="nav_open = !nav_open">
			<span v-text="routeTitle"></span>
			<i :class="buttonIconClass"></i>
		</button>
		<nav :class="navClass">
			<router-link class="monsterinsights-navigation-tab-link" to="/" v-text="text_overview"></router-link>
			<router-link class="monsterinsights-navigation-tab-link" to="publishers"
				v-text="text_publishers"
			/>
			<router-link class="monsterinsights-navigation-tab-link" to="search-console"
				v-text="text_search"
			/>
			<router-link class="monsterinsights-navigation-tab-link" to="ecommerce"
				v-text="text_ecommerce"
			/>
			<router-link class="monsterinsights-navigation-tab-link" to="dimensions"
				v-text="text_dimensions"
			/>
			<router-link class="monsterinsights-navigation-tab-link" to="forms" v-text="text_forms" />
			<router-link class="monsterinsights-navigation-tab-link" to="real-time"
				v-text="text_real_time"
			/>
			<router-link v-if="isYearInReviewVisible()" class="monsterinsights-navigation-tab-link year-in-review" to="year-in-review"
				v-text="text_year_in_review"
			/>
		</nav>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import moment from 'moment';

	export default {
		name: 'ReportsNavigation',
		data() {
			return {
				text_overview: __( 'Overview', process.env.VUE_APP_TEXTDOMAIN ),
				text_publishers: __( 'Publishers', process.env.VUE_APP_TEXTDOMAIN ),
				text_ecommerce: __( 'eCommerce', process.env.VUE_APP_TEXTDOMAIN ),
				text_search: __( 'Search Console', process.env.VUE_APP_TEXTDOMAIN ),
				text_dimensions: __( 'Dimensions', process.env.VUE_APP_TEXTDOMAIN ),
				text_forms: __( 'Forms', process.env.VUE_APP_TEXTDOMAIN ),
				text_real_time: __( 'Real-Time', process.env.VUE_APP_TEXTDOMAIN ),
				nav_open: false,
				text_year_in_review: __( '2019 Year in Review', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			routeTitle() {
				return this.$route.meta.title ? this.$route.meta.title : false;
			},
			navClass() {
				let navClass = 'monsterinsights-main-navigation monsterinsights-reports-navigation';

				if ( this.nav_open ) {
					navClass += ' monsterinsights-main-navigation-open';
				}

				return navClass;
			},
			buttonIconClass() {
				let buttonIconClass = 'monstericon-arrow';

				if ( this.nav_open ) {
					buttonIconClass += ' monstericon-down';
				}

				return buttonIconClass;
			},
		},
		methods: {
			isYearInReviewVisible() {
				return moment().isBetween('2020-01-01', '2020-01-14');
			},
		},
		watch: {
			$route() {
				this.nav_open = false; // Close the menu when navigating.
			},
		},
	};
</script>
