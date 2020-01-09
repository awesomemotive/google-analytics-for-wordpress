<template>
	<div :class="routeClass">
		<the-app-header>
			<settings-button-save></settings-button-save>
		</the-app-header>
		<the-app-navigation>
			<settings-tabs-navigation />
		</the-app-navigation>
		<the-app-notices />
		<settings-first-time-notice />
		<router-view />
		<div v-if="blocked" class="monsterinsights-blocked"></div>
		<the-quick-links />
	</div>
</template>

<script>
	import { mapGetters } from 'vuex';
	import { __ } from '@wordpress/i18n';
	import SettingsStore from './store';
	import SettingsRouter from './routes/site';
	import TheAppHeader from '../../components/TheAppHeader';
	import SettingsTabsNavigation from './components/tabs/SettingsTabsNavigation';
	import TheAppNavigation from '../../components/TheAppNavigation';
	import TheAppNotices from '../../components/TheAppNotices';
	import SettingsButtonSave from './components/SettingsButtonSave';
	import SettingsFirstTimeNotice from "./components/SettingsFirstTImeNotice";
	import TheQuickLinks from "../../components/TheQuickLinks";

	export default {
		name: 'SettingsModuleSite',
		components: {
			TheQuickLinks,
			SettingsFirstTimeNotice, SettingsButtonSave, TheAppNotices, TheAppNavigation, SettingsTabsNavigation, TheAppHeader },
		router: SettingsRouter,
		created() {
			const STORE_KEY = '$_settings';
			// Load the store for this module only if it's used.
			// eslint-disable-next-line no-underscore-dangle
			if ( ! (
				STORE_KEY in this.$store._modules.root._children
			) ) {
				this.$store.registerModule( STORE_KEY, SettingsStore );
			}
		},
		computed: {
			...mapGetters({
				blocked: '$_app/blocked',
				addons: '$_addons/addons',
				auth: '$_auth/auth',
			}),
			routeClass() {
				return 'monsterinsights-admin-page monsterinsights-settings-panel monsterinsights-path-' + this.$route.name;
			},
			is_authed() {
				let is_authed = this.auth.network_ua ? this.auth.network_ua : this.auth.ua;

				if ( ! is_authed ) {
					is_authed = this.auth.network_manual_ua ? this.auth.network_manual_ua : this.auth.manual_ua;
				}

				return '' !== is_authed;
			},
		},
		mounted() {
			this.$store.dispatch( '$_settings/getSettings' );
			this.$mi_loading_toast();
		},
		watch: {
			// Wait for the addons object to load as that's the last ajax call made.
			addons() {
				if ( Object.keys(this.addons).length > 0 ) {
					if ( '/oneclickupgrade' === this.$route.redirectedFrom ) {
						this.$nextTick().then( () => {
							this.$swal( {
								type: 'success',
								customContainerClass: 'monsterinsights-swal',
								title: __( 'Congratulations! ', process.env.VUE_APP_TEXTDOMAIN ),
								html: __( 'You Successfully Unlocked the most powerful Analytics plugin', process.env.VUE_APP_TEXTDOMAIN ),
							} );
						} );
					}
				}
			},
		},
	};
</script>

<style lang="scss" scoped>
	button {
		margin-top: 3px;
	}
</style>
