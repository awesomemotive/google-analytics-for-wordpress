<template>
	<div>
		<button v-if="'addons'!==route" class="monsterinsights-mobile-nav-trigger" v-on:click="nav_open = !nav_open">
			<span v-text="routeTitle"></span>
			<i :class="buttonIconClass"></i>
		</button>
		<nav :class="navClass">
			<template v-if="'addons'===route">
				<addons-navigation />
			</template>
			<template v-else-if="'tools-import-export'===route||'tools-url-builder'===route">
				<tools-navigation />
			</template>
			<template v-else-if="0===route.indexOf('about')">
				<about-navigation />
			</template>
			<template v-else>
				<router-link class="monsterinsights-navigation-tab-link" to="/" v-text="text_settings"></router-link>
				<router-link class="monsterinsights-navigation-tab-link" to="engagement"
					v-text="text_engagement"
				></router-link>
				<router-link class="monsterinsights-navigation-tab-link" to="ecommerce"
					v-text="text_ecommerce"
				></router-link>
				<router-link class="monsterinsights-navigation-tab-link" to="publisher"
					v-text="text_publisher"
				></router-link>
				<router-link class="monsterinsights-navigation-tab-link" to="conversions"
					v-text="text_conversions"
				></router-link>
				<router-link class="monsterinsights-navigation-tab-link" to="advanced"
					v-text="text_advanced"
				></router-link>
			</template>
		</nav>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import AddonsNavigation from '../../../addons/components/AddonsNavigation';
	import ToolsNavigation from '../../../tools/components/ToolsNavigation';
	import AboutNavigation from "../../../about/components/AboutNavigation-MI_VERSION";

	const settings_links = document.querySelectorAll( '[href="admin.php?page=monsterinsights_settings"]' );
	const addons_link = document.querySelector( '[href*="monsterinsights_settings#/addons"]' );
	const tools_link = document.querySelector( '[href*="monsterinsights_settings#/tools"]' );
	const about_link = document.querySelector( '[href*="monsterinsights_settings#/about"]' );

	export default {
		name: 'SettingsTabsNavigation',
		components: { AboutNavigation, ToolsNavigation, AddonsNavigation },
		data() {
			return {
				text_settings: __( 'General', process.env.VUE_APP_TEXTDOMAIN ),
				text_engagement: __( 'Engagement', process.env.VUE_APP_TEXTDOMAIN ),
				text_ecommerce: __( 'eCommerce', process.env.VUE_APP_TEXTDOMAIN ),
				text_publisher: __( 'Publisher', process.env.VUE_APP_TEXTDOMAIN ),
				text_conversions: __( 'Conversions', process.env.VUE_APP_TEXTDOMAIN ),
				text_advanced: __( 'Advanced', process.env.VUE_APP_TEXTDOMAIN ),
				nav_open: false,
			};
		},
		computed: {
			route() {
				return this.$route.name;
			},
			routeTitle() {
				return this.$route.meta.title ? this.$route.meta.title : false;
			},
			buttonIconClass() {
				let buttonIconClass = 'monstericon-arrow';

				if ( this.nav_open ) {
					buttonIconClass += ' monstericon-down';
				}

				return buttonIconClass;
			},
			navClass() {
				let navClass = 'monsterinsights-main-navigation';

				if ( this.nav_open || 'addons' === this.route ) {
					navClass += ' monsterinsights-main-navigation-open';
				}

				return navClass;
			},
		},
		methods: {
			handleChange( name ) {
				let settings_index = 0;
				if ( settings_links.length > 1 ) {
					settings_index = 1;
				}
				if ( 'addons' === name ) {
					this.removeClasses();
					if ( addons_link ) {
						addons_link.parentElement.classList.add( 'current' );
					}
				} else if ( name.indexOf( 'tools' ) >= 0 ) {
					this.removeClasses();
					if ( tools_link ) {
						tools_link.parentElement.classList.add( 'current' );
					}
				} else if ( name.indexOf( 'about' ) >= 0 ) {
					this.removeClasses();
					if ( about_link ) {
						about_link.parentElement.classList.add( 'current' );
					}
				} else {
					this.removeClasses();
					settings_links[settings_index].parentElement.classList.add( 'current' );
				}
			},
			removeClasses() {
				let settings_index = 0;
				if ( settings_links.length > 1 ) {
					settings_index = 1;
				}
				if ( tools_link ) {
					tools_link.parentElement.classList.remove( 'current' );
				}
				if ( addons_link ) {
					addons_link.parentElement.classList.remove( 'current' );
				}
				if ( about_link ) {
					about_link.parentElement.classList.remove( 'current' );
				}
				settings_links[settings_index].parentElement.classList.remove( 'current' );
			},
			maybeCloseMenu() {
				// Close the WordPress menu if route is changed when the menu is open.
				const wrap = document.getElementById( 'wpwrap' );
				if ( wrap.classList.contains( 'wp-responsive-open' ) ) {
					const toggle = document.getElementById( 'wp-admin-bar-menu-toggle' );
					if ( toggle ) {
						toggle.click();
					}
				}
			},
		},
		watch: {
			$route( to ) {
				this.handleChange( to.name );
				this.nav_open = false; // Close the menu when navigating.
				this.maybeCloseMenu();
			},
		},
		created() {
			this.handleChange( this.route );
			settings_links.forEach( function( settings_link ) {
				settings_link.href = settings_link.href + '#/';
			} );
		},
	};
</script>
