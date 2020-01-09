<template>
	<div class="monsterinsights-onboarding-step-success">
		<onboarding-content-header :title="text_header_title" :subtitle="text_header_subtitle"></onboarding-content-header>
		<div class="monsterinsights-onboarding-wizard-form">
			<ol>
				<li v-html="text_notice"></li>
				<template v-for="(error, index) in install_errors">
					<li :key="index" v-html="error"></li>
				</template>
				<li v-html="text_newsletter"></li>
			</ol>
			<div class="monsterinsights-separator"></div>
			<div class="monsterinsights-form-row monsterinsights-form-buttons">
				<div class="monsterinsights-form-input">
					<a :href="exit_url" class="monsterinsights-onboarding-button monsterinsights-onboarding-button-large" v-text="text_exit"></a>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import { mapGetters } from 'vuex';
	import { sprintf, __ } from '@wordpress/i18n';
	import OnboardingContentHeader from '../OnboardingContentHeader';

	export default {
		name: 'OnboardingStepSuccess',
		components: { OnboardingContentHeader },
		data() {
			return {
				text_header_title: __( 'Awesome, You\'re All Set!', process.env.VUE_APP_TEXTDOMAIN ),
				text_header_subtitle: __( 'MonsterInsights is all set up and ready to use. We\'ve verified that the tracking code is deployed properly and collecting data.', process.env.VUE_APP_TEXTDOMAIN ),
				text_notice: sprintf( __( '%1$sPlease Note:%2$s While Google Analytics is properly setup and tracking everything, it does not send the data back to WordPress immediately. Depending on the size of your website, it can take between a few hours to 24 hours for reports to populate.', process.env.VUE_APP_TEXTDOMAIN ), '<strong>', '</strong>' ),
				text_newsletter: sprintf( __( '%1$sSubscribe to the MonsterInsights blog%2$s for tips on how to get more traffic and grow your business.', process.env.VUE_APP_TEXTDOMAIN ), '<a target="_blank" href="https://www.monsterinsights.com/blog/">', '</a>' ),
				text_exit: __( 'Finish Setup & Exit Wizard', process.env.VUE_APP_TEXTDOMAIN ),
				exit_url: this.$mi.exit_url,
			};
		},
		computed: {
			...mapGetters({
				install_errors: '$_onboarding/install_errors',
			}),
		},
		mounted() {
			const self = this;
			self.$swal({
				type: 'info',
				title: __( 'Checking your website...', process.env.VUE_APP_TEXTDOMAIN ),
				allowOutsideClick: false,
				allowEscapeKey: false,
				allowEnterKey: false,
				onOpen: function() {
					self.$swal.showLoading();
				},
			});
			this.$store.dispatch( '$_onboarding/getErrors' ).then( function() {
				self.$swal.close();
			});
		},
	};
</script>
