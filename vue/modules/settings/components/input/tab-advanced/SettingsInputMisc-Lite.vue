<template>
	<div>
		<settings-block :title="text_misc_title">
			<p>
				<span class="monsterinsights-dark" v-html="text_announcements_title"></span>
				<span v-html="text_announcements_description"></span>
			</p>
			<settings-input-checkbox name="hide_am_notices" :label="text_announcements_label"></settings-input-checkbox>
			<div class="monsterinsights-separator"></div>
			<p>
				<span class="monsterinsights-dark" v-html="text_usage_tracking_title"></span>
				<span v-html="text_usage_tracking_description"></span>
			</p>
			<settings-input-checkbox name="usage_tracking" :label="text_usage_tracking_label" :tooltip="text_usage_tracking_tooltip"></settings-input-checkbox>
		</settings-block>
		<settings-lite-upsell-large />
	</div>
</template>

<script>
	import { __, sprintf } from '@wordpress/i18n';
	import SettingsBlock from '../../SettingsBlock';
	import SettingsInputCheckbox from '../SettingsInputCheckbox';
	import SettingsLiteUpsellLarge from "../../SettingsLiteUpsellLarge";

	export default {
		name: 'SettingsInputMisc',
		components: {
			SettingsLiteUpsellLarge,
			SettingsInputCheckbox,
			SettingsBlock,
		},
		data() {
			return {
				text_misc_title: __( 'Miscellaneous', process.env.VUE_APP_TEXTDOMAIN ),
				text_announcements_title: __( 'Hide Announcements', process.env.VUE_APP_TEXTDOMAIN ),
				text_announcements_description: __( 'Hides plugin announcements and update details. This includes critical notices we use to inform about deprecations and important required configuration changes.', process.env.VUE_APP_TEXTDOMAIN ),
				text_announcements_label: __( 'Hide Announcements', process.env.VUE_APP_TEXTDOMAIN ),
				text_usage_tracking_title: __( 'Usage Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_usage_tracking_description: __( 'By allowing us to track usage data we can better help you because we know with which WordPress configurations, themes and plugins we should test.', process.env.VUE_APP_TEXTDOMAIN ),
				text_usage_tracking_label: __( 'Allow usage tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_usage_tracking_tooltip: sprintf( __( 'Complete documentation on usage tracking is available %1$shere%2$s.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="' + this.$getUrl( 'settings-panel', 'usage-tracking', 'https://www.monsterinsights.com/docs/usage-tracking/' ) + '" target="_blank">', '</a>' ),
			};
		},
		methods: {
			sprintf,
			selectText( e /* eslint-disable-line no-unused-vars */ ) {
				const text = document.querySelector( '.monsterinsights-coupon' );
				let range, selection;
				if ( window.getSelection ) {
					selection = window.getSelection();
					range = document.createRange();
					range.selectNodeContents( text );
					selection.removeAllRanges();
					selection.addRange( range );
				}
			},
		},
	};
</script>

<style scoped>
	.monsterinsights-dark {
		display: block;
	}
</style>
