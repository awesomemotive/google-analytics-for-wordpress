<template>
	<main class="monsterinsights-settings-content settings-publisher">
		<settings-block :title="text_affiliate_title">
			<p>
				<span v-html="text_affiliate_repeater_description"></span>
				<settings-info-tooltip :content="text_affiliate_description_tooltip"></settings-info-tooltip>
			</p>
			<settings-input-repeater :structure="repeater_structure" name="affiliate_links" :data="settings['affiliate_links']"></settings-input-repeater>
		</settings-block>
		<settings-input-ads></settings-input-ads>
		<settings-input-amp></settings-input-amp>
		<settings-input-fbia></settings-input-fbia>
	</main>
</template>

<script>
	import { __, sprintf } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import SettingsBlock from '../SettingsBlock';
	import SettingsInputRepeater from '../input/SettingsInputRepeater';
	import SettingsInfoTooltip from '../SettingsInfoTooltip';
	import SettingsInputAds from '../input/tab-publisher/SettingsInputAds-MI_VERSION';
	import SettingsInputAmp from '../input/tab-publisher/SettingsInputAmp-MI_VERSION';
	import SettingsInputFbia from '../input/tab-publisher/SettingsInputFbia-MI_VERSION';

	export default {
		name: 'SettingsTabPublisher',
		components: {
			SettingsInfoTooltip,
			SettingsInputRepeater,
			SettingsBlock,
			SettingsInputAds,
			SettingsInputAmp,
			SettingsInputFbia,
		},
		data() {
			return {
				repeater_structure: [
					{
						name: 'path',
						label: sprintf(__( 'Path (example: %s)', process.env.VUE_APP_TEXTDOMAIN ), '/go/' ),
						pattern: /^\/\S+$/,
						error: __( 'Path has to start with a / and have no spaces', process.env.VUE_APP_TEXTDOMAIN ),
					},
					{
						name: 'label',
						label: sprintf( __( 'Label (example: %s)', process.env.VUE_APP_TEXTDOMAIN ), 'aff' ),
						pattern: /^\S+$/,
						error: __( 'Label can\'t contain any spaces', process.env.VUE_APP_TEXTDOMAIN ),
					},
				],
				text_affiliate_title: __( 'Affiliate Links', process.env.VUE_APP_TEXTDOMAIN ),
				text_affiliate_description_tooltip: sprintf( __( 'This allows you to track custom affiliate links. A path of /go/ would match urls that start with that. The label is appended onto the end of the string "outbound-link-", to provide unique labels for these links in Google Analytics. Complete documentation on affiliate links is available %1$shere%2$s.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="' + this.$getUrl( 'settings-panel', 'publisher-tab', 'https://www.monsterinsights.com/how-to-set-up-affiliate-link-tracking-in-wordpress/' ) + '" target="_blank">', '</a>' ),
				text_affiliate_repeater_description: __( 'Our affiliate link tracking works by setting path for internal links to track as outbound links.', process.env.VUE_APP_TEXTDOMAIN ),
				default_affiliate_value: false,
			};
		},
		computed: {
			...mapGetters( {
				settings: '$_settings/settings',
			} ),
		},
	};
</script>
