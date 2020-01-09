<template>
	<div class="monsterinsights-onboarding-step-recommended-addons">
		<onboarding-content-header :title="text_header_title" :subtitle="text_header_subtitle"></onboarding-content-header>
		<div class="monsterinsights-onboarding-wizard-form">
			<form action="" method="post" v-on:submit.prevent="handleSubmit">
				<div class="monsterinsights-separator"></div>
				<template v-for="(addon, index) in recommendedAddons()">
					<onboarding-addon v-if="addons[addon]" :key="index" :addon="addons[addon]" />
					<div :key="index+ 'separator'" class="monsterinsights-separator"></div>
				</template>
				<slide-down-up :done="hideButton">
					<div v-if="view_all">
						<template v-for="(addon,index) in other_addons">
							<onboarding-addon :key="index" :addon="addon" />
							<div :key="index + 'separator'" class="monsterinsights-separator"></div>
						</template>
					</div>
				</slide-down-up>
				<template v-if="view_button">
					<div class="monsterinsights-form-row">
						<h2 v-text="text_other_addons"></h2>
						<button type="button" class="monsterinsights-text-button monsterinsights-green-link" v-on:click.prevent="viewAll()" v-text="text_other_addons_button"></button>
					</div>
					<div class="monsterinsights-separator"></div>
				</template>
				<div class="monsterinsights-form-row monsterinsights-form-buttons">
					<button type="submit" class="monsterinsights-onboarding-button monsterinsights-onboarding-button-large" name="next_step" v-text="text_save"></button>
				</div>
			</form>
		</div>
	</div>
</template>
<script>
	import { mapGetters } from 'vuex';
	import { __ } from '@wordpress/i18n';
	import OnboardingContentHeader from '../OnboardingContentHeader';
	import OnboardingAddon from '../OnboardingAddon-Lite';
	import SlideDownUp from '../../../../components/helper/SlideDownUp';

	export default {
		name: 'OnboardingStepRecommendedAddons',
		components: { SlideDownUp, OnboardingAddon, OnboardingContentHeader },
		data() {
			return {
				text_header_title: __( 'Recommended Addons', process.env.VUE_APP_TEXTDOMAIN ),
				text_header_subtitle: __( 'To unlock more features consider upgrading to PRO. As a valued MonsterInsights Lite user you receive 50% off, automatically applied at checkout!', process.env.VUE_APP_TEXTDOMAIN ),
				text_other_addons: __( 'Other Addons', process.env.VUE_APP_TEXTDOMAIN ),
				text_other_addons_button: __( 'View all MonsterInsights addons', process.env.VUE_APP_TEXTDOMAIN ),
				text_save: __( 'Save and continue', process.env.VUE_APP_TEXTDOMAIN ),
				view_all: false,
				view_button: true,
			};
		},
		computed: {
			...mapGetters({
				settings: '$_settings/settings',
				addons: '$_addons/addons',
			}),
			other_addons() {
				let other_addons = [];
				for ( let i in this.addons ) {
					if ( this.addons[i].type && this.recommendedAddons().indexOf( this.addons[i].slug ) < 0 ) {
						other_addons.push( this.addons[i]);
					}
				}

				return other_addons;
			},
		},
		methods: {
			handleSubmit() {
				let step = 4;
				if ( this.addons.wpforms.active ) {
					step = 5;
				}
				this.$router.push( this.$wizard_steps[step]);
			},
			recommendedAddons() {
				const type = this.settings['site_type'];
				let addons = [
					'forms',
					'page-insights',
				];

				if ( 'publisher' === type ) {
					addons = [
						'dimensions',
						'page-insights',
						'facebook-instant-articles',
						'amp',
					];
				}
				if ( 'ecommerce' === type ) {
					addons = [
						'ecommerce',
						'dimensions',
						'forms',
						'google-optimize',
					];
				}

				if ( this.$mi.is_eu ) {
					addons.push( 'eu-compliance' );
				}

				let filtered_addons = [];

				for ( let i in addons ) {
					if ( this.addons[addons[i]]) {
						filtered_addons.push( addons[i]);
					}
				}

				return filtered_addons;
			},
			viewAll() {
				this.view_all = true;
			},
			hideButton() {
				this.view_button = false;
			},
		},
	};
</script>
