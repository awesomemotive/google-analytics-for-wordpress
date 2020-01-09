<template>
	<div v-if="show_notice" class="monsterinsights-first-time-notice monsterinsights-container">
		<div v-if="show_notice" class="monsterinsights-notice monsterinsights-notice-success">
			<div class="monsterinsights-notice-inner">
				<button v-if="is_authed" class="dismiss-notice" v-on:click="removeNotice()">
					<i class="monstericon-times"></i>
				</button>
				<h2 class="notice-title" v-html="notice_title"></h2>

				<div class="notice-content">
					<span v-on:click="maybe_open_video" v-html="notice_content"></span>
				</div>
				<div v-if="is_authed" class="monsterinsights-notice-button">
					<a class="monsterinsights-button" :href="reports_url"
						v-on:click="removeNotice()"
						v-text="notice_button"
					></a>
				</div>
			</div>
		</div>
		<welcome-overlay v-if="show_video" v-on:close="show_video=false">
			<iframe width="1280" height="720" src="https://www.youtube.com/embed/IbdKpSygp2U?autoplay=1" frameborder="0"
				allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen
			></iframe>
		</welcome-overlay>
	</div>
</template>
<script>
	import axios from 'axios';
	import { __, sprintf } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import WelcomeOverlay from "../../wizard-onboarding/components/WelcomeOverlay";

	export default {
		name: 'SettingsFirstTimeNotice',
		components: { WelcomeOverlay },
		data() {
			return {
				notice_button: __( 'View Reports', process.env.VUE_APP_TEXTDOMAIN ),
				text_notice_title: __( 'Welcome to MonsterInsights', process.env.VUE_APP_TEXTDOMAIN ),
				text_notice_title_auth: __( 'Congratulations!', process.env.VUE_APP_TEXTDOMAIN ),
				notice_content_noauth: sprintf( __( 'MonsterInsights makes it easy to connect your website with Google Analytics and see all important website stats right inside your WordPress dashboard. In order to setup website analytics, please take a look at our %1$sGetting started video%2$s or use our %3$s to get you quickly set up.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="https://www.youtube.com/watch?v=IbdKpSygp2U" target="_blank" id="monsterinsights-view-video">', '</a>', '<a href="' + this.$mi.wizard_url + '">' + __( 'Onboarding Wizard', process.env.VUE_APP_TEXTDOMAIN ) + '</a>' ),
				notice_content_auth: __( 'You are now connected with MonsterInsights. We make it effortless for you to implement Google Analytics tracking and see the stats that matter, right inside the WordPress dashboard.', process.env.VUE_APP_TEXTDOMAIN ),
				reports_url: this.$mi.reports_url,
				is_network: this.$mi.network,
				show_video: false,
			};
		},
		computed: {
			...mapGetters( {
				auth: '$_auth/auth',
			} ),
			is_authed() {
				return this.auth.network_ua ? this.auth.network_ua : this.auth.ua;
			},
			has_ua() {
				let is_authed = this.auth.network_ua ? this.auth.network_ua : this.auth.ua;
				if ( ! is_authed ) {
					is_authed = this.auth.network_manual_ua ? this.auth.network_manual_ua : this.auth.manual_ua;
				}
				return '' !== is_authed;
			},
			notice_content() {
				return this.is_authed ? this.notice_content_auth : this.notice_content_noauth;
			},
			notice_title() {
				return this.is_authed ? this.text_notice_title_auth : this.text_notice_title;
			},
			show_notice() {
				// Only show on settings panel
				const allowed_routes = [ 'general', 'engagement', 'ecommerce', 'publisher', 'conversions', 'advanced' ];
				const route_name = this.$route.name;
				if ( allowed_routes.indexOf( route_name ) < 0 ) {
					return false;
				}
				if ( ! this.is_authed ) {
					return true;
				}
				return ! this.$mi.first_run_notice;
			},
		},
		methods: {
			removeNotice() {
				this.$mi.first_run_notice = true;

				let formData = new FormData();
				formData.append( 'action', 'monsterinsights_vue_dismiss_first_time_notice' );
				formData.append( 'nonce', this.$mi.nonce );

				axios.post( this.$mi.ajax, formData );
			},
			maybe_open_video( e ) {
				if ( 'monsterinsights-view-video' === e.target.id ) {
					e.preventDefault();
					this.show_video = true;
				}
			},
		},
	};
</script>
