<template>
	<div :class="mainClass">
		<the-app-header />
		<the-app-navigation>
			<reports-navigation />
		</the-app-navigation>
		<the-app-notices />
		<router-view />
		<div v-if="blocked" class="monsterinsights-blocked"></div>
		<report-no-auth v-if="noauth"></report-no-auth>
		<report-re-auth v-if="reauth"></report-re-auth>
		<the-quick-links />
	</div>
</template>
<script>
	import { mapGetters } from 'vuex';

	import ReportsStore from './store';
	import ReportsRouter from './routes/index';
	import TheAppHeader from '../../components/TheAppHeader';
	import TheAppNavigation from '../../components/TheAppNavigation';
	import ReportsNavigation from './components/ReportsNavigation';
	import TheAppNotices from '../../components/TheAppNotices';
	import ReportNoAuth from "./components/ReportNoAuth";
	import ReportReAuth from "./components/ReportReAuth";
	import TheQuickLinks from "../../components/TheQuickLinks";

	export default {
		name: 'ModuleReports',
		router: ReportsRouter,
		components: {
			TheQuickLinks,
			ReportReAuth, ReportNoAuth, TheAppNotices, ReportsNavigation, TheAppNavigation, TheAppHeader },
		computed: {
			...mapGetters( {
				blocked: '$_app/blocked',
				blur: '$_reports/blur',
				noauth: '$_reports/noauth',
				reauth: '$_reports/reauth',
			} ),
			route() {
				return this.$route.name;
			},
			mainClass() {
				let mainClass = 'monsterinsights-admin-page monsterinsights-reports-page';

				if ( this.blur ) {
					mainClass += ' monsterinsights-blur';
				}

				return mainClass;
			},
		},
		created() {
			const STORE_KEY = '$_reports';
			// eslint-disable-next-line no-underscore-dangle
			if ( ! (
				STORE_KEY in this.$store._modules.root._children
			) ) {
				this.$store.registerModule( STORE_KEY, ReportsStore );
			}
			this.updateSelectedReport( this.route );
		},
		watch: {
			$route( to ) {
				this.updateSelectedReport( to.name );
			},
		},
		methods: {
			updateSelectedReport( report ) {
				if ( ! this.$mi.authed ) {
					this.$store.commit( '$_reports/ENABLE_BLUR' );
					this.$store.commit( '$_reports/ENABLE_NOAUTH' );
				}
				this.$store.commit( '$_reports/UPDATE_ACTIVE_REPORT', report );
			},
		},
	};
</script>
