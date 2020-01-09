<template>
	<div :class="routeClass">
		<the-app-header>
			<settings-button-save></settings-button-save>
		</the-app-header>
		<the-app-navigation v-if="'addons'===route">
			<addons-navigation />
		</the-app-navigation>
		<the-app-notices />
		<router-view />
		<div v-if="blocked" class="monsterinsights-blocked"></div>
		<the-quick-links />
	</div>
</template>

<script>
	import { mapGetters } from 'vuex';
	import SettingsStore from './store';
	import NetworkRouter from './routes/network';
	import TheAppHeader from '../../components/TheAppHeader';
	import SettingsButtonSave from './components/SettingsButtonSave';
	import TheAppNotices from "../../components/TheAppNotices";
	import TheAppNavigation from "../../components/TheAppNavigation";
	import AddonsNavigation from "../addons/components/AddonsNavigation";
	import TheQuickLinks from "../../components/TheQuickLinks";

	const settings_links = document.querySelectorAll( '[href="admin.php?page=monsterinsights_network"]' );
	const addons_link = document.querySelector( '[href*="monsterinsights_network#/addons"]' );
	const about_link = document.querySelector( '[href*="monsterinsights_network#/about"]' );

	export default {
		name: 'SettingsModuleSite',
		components: {
			TheQuickLinks,
			AddonsNavigation,
			TheAppNavigation,
			TheAppNotices,
			SettingsButtonSave,
			TheAppHeader,
		},
		router: NetworkRouter,
		created() {
			const STORE_KEY = '$_settings';
			// Load the store for this module only if it's used.
			// eslint-disable-next-line no-underscore-dangle
			if ( ! (
				STORE_KEY in this.$store._modules.root._children
			) ) {
				this.$store.registerModule( STORE_KEY, SettingsStore );
			}
			this.handleChange( this.route );
			settings_links.forEach( function(settings_link) {
				settings_link.href = settings_link.href + '#/';
			});
		},
		mounted() {
			this.$store.dispatch( '$_settings/getSettings' );
			this.$mi_loading_toast();
		},
		computed: {
			...mapGetters({
				blocked: '$_app/blocked',
			}),
			route() {
				return this.$route.name;
			},
			routeClass() {
				return 'monsterinsights-admin-page monsterinsights-settings-panel monsterinsights-settings-panel-network monsterinsights-path-' + this.$route.name;
			},
		},
		watch: {
			$route( to ) {
				this.handleChange( to.name );
			},
		},
		methods: {
			handleChange( name ) {
				let settings_index = 0;
				if ( settings_links.length > 1 ) {
					settings_index = 1;
				}
				if ( 'addons' === name ) {
					settings_links[settings_index].parentElement.classList.remove( 'current' );
					if ( addons_link ) {
						addons_link.parentElement.classList.add( 'current' );
					}
				} else if ( name.indexOf( 'about' ) >= 0 ) {
					settings_links[settings_index].parentElement.classList.remove( 'current' );
					if ( about_link ) {
						about_link.parentElement.classList.add( 'current' );
					}
					if ( addons_link ) {
						addons_link.parentElement.classList.remove( 'current' );
					}
				} else {
					settings_links[settings_index].parentElement.classList.add( 'current' );
					if ( addons_link ) {
						addons_link.parentElement.classList.remove( 'current' );
					}
					if ( about_link ) {
						about_link.parentElement.classList.remove( 'current' );
					}
				}
			},
		},
	};
</script>
