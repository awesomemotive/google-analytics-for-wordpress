<template>
	<main class="monsterinsights-settings-content monsterinsights-settings-conversions">
		<settings-block :title="text_forms_title">
			<settings-addon-upgrade addon="forms">
				<div v-html="text_forms_description"></div>
			</settings-addon-upgrade>
		</settings-block>
		<settings-block :title="text_optimize_title">
			<settings-addon-upgrade addon="optimize">
				<div v-html="text_optimize_description"></div>
			</settings-addon-upgrade>
		</settings-block>
		<settings-block :title="text_dimesions_title">
			<settings-addon-upgrade addon="dimensions">
				<div v-html="text_dimesions_description"></div>
			</settings-addon-upgrade>
		</settings-block>
	</main>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import SettingsBlock from '../SettingsBlock';
	import SettingsAddonUpgrade from '../SettingsAddonUpgrade';

	export default {
		name: 'SettingsTabConversions',
		components: { SettingsAddonUpgrade, SettingsBlock },
		data() {
			return {
				text_forms_title: __( 'Forms Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_forms_description: __( 'See who\'s viewing and submitting your forms, so you can increase your conversion rate.', process.env.VUE_APP_TEXTDOMAIN ),
				text_optimize_title: __( 'Google Optimize', process.env.VUE_APP_TEXTDOMAIN ),
				text_optimize_description: __( 'Use Google Optimize to retarget your website visitors and perform A/B split tests with ease.', process.env.VUE_APP_TEXTDOMAIN ),
				text_dimesions_title: __( 'Custom Dimensions', process.env.VUE_APP_TEXTDOMAIN ),
				text_dimesions_description: __( 'Add Custom Dimensions and track who\'s the most popular author on your site, which post types get the most traffic, and more', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
	};
</script>
