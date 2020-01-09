<template>
	<main class="monsterinsights-settings-content settings-engagement">
		<settings-block v-if="! isAddonActive" :title="text_title_demographics">
			<settings-input-checkbox name="demographics" :label="text_label_demographics" :tooltip="text_tooltip_demographics" />
			<div class="monsterinsights-separator"></div>
			<settings-input-checkbox name="anonymize_ips" :label="text_label_anonymize_ip" :tooltip="text_tooltip_anonymize" />
		</settings-block>
		<settings-input-e-u-compliance></settings-input-e-u-compliance>
		<settings-block :title="text_title_link_attribution">
			<settings-input-checkbox name="link_attribution" :label="text_label_enhanced_link" :tooltip="text_tooltip_link_attribution">
				<template slot="collapsible">
					<settings-input-checkbox name="hash_tracking" :label="text_label_anchor_tracking" :tooltip="text_tooltip_anchor_tracking"></settings-input-checkbox>
					<div class="monsterinsights-separator"></div>
					<settings-input-checkbox name="allow_anchor" :label="text_label_allow_anchor" :tooltip="text_tooltip_allow_anchor"></settings-input-checkbox>
					<div class="monsterinsights-separator"></div>
					<settings-input-checkbox name="tag_links_in_rss" :label="text_label_tag_links_in_rss" :tooltip="text_tooltip_tag_links_in_rss"></settings-input-checkbox>
				</template>
			</settings-input-checkbox>
		</settings-block>
		<settings-input-scroll />
		<settings-block :title="text_cross_domain">
			<label>
				<span v-html="text_cross_domain_description"></span>
			</label>
			<settings-input-repeater :text_add="text_add_domain" :structure="domain_repeater_structure" name="cross_domains" />
		</settings-block>

		<settings-block :title="text_title_file_downloads">
			<settings-input-text default_value="doc,pdf,ppt,zip,xls,docx,pptx,xlsx" name="extensions_of_files" :label="text_label_extensions_of_files" :description="text_description_extensions_of_files"></settings-input-text>
		</settings-block>
	</main>
</template>

<script>
	import { mapGetters } from 'vuex';
	import { __, sprintf } from '@wordpress/i18n';
	import SettingsBlock from '../SettingsBlock';
	import SettingsInputCheckbox from '../input/SettingsInputCheckbox';
	import SettingsInputText from '../input/SettingsInputText';
	import SettingsInputEUCompliance from '../input/tab-engagement/SettingsInputEUCompliance-MI_VERSION';
	import SettingsInputRepeater from "../input/SettingsInputRepeater";
	import SettingsInputScroll from "../input/tab-engagement/SettingsInputScroll-MI_VERSION";

	export default {
		name: 'SettingsTabEngagement',
		components: {
			SettingsInputScroll,
			SettingsInputRepeater,
			SettingsInputEUCompliance,
			SettingsInputText,
			SettingsInputCheckbox,
			SettingsBlock,
		},
		data() {
			let domain = window.location.origin.replace( /(^\w+:|^)\/\//, '' );
			domain = domain.replace( /\./, '\\.' );

			return {
				text_title_demographics: __( 'Demographics', process.env.VUE_APP_TEXTDOMAIN ),
				text_label_demographics: __( 'Enable Demographics and Interests Reports for Remarketing and Advertising', process.env.VUE_APP_TEXTDOMAIN ),
				text_label_anonymize_ip: __( 'Anonymize IP Addresses', process.env.VUE_APP_TEXTDOMAIN ),
				text_title_link_attribution: __( 'Link Attribution', process.env.VUE_APP_TEXTDOMAIN ),
				text_label_enhanced_link: __( 'Enable Enhanced Link Attribution', process.env.VUE_APP_TEXTDOMAIN ),
				text_label_anchor_tracking: __( 'Enable Anchor Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_label_allow_anchor: __( 'Enable allowAnchor', process.env.VUE_APP_TEXTDOMAIN ),
				text_label_allow_linker: __( 'Enable allowLinker', process.env.VUE_APP_TEXTDOMAIN ),
				text_label_tag_links_in_rss: __( 'Enable Tag Links in RSS', process.env.VUE_APP_TEXTDOMAIN ),
				text_title_file_downloads: __( 'File Downloads', process.env.VUE_APP_TEXTDOMAIN ),
				text_label_extensions_of_files: __( 'Extensions of Files to Track as Downloads', process.env.VUE_APP_TEXTDOMAIN ),
				text_description_extensions_of_files: __( 'MonsterInsights will send an event to Google Analytics if a link to a file has one of the above extensions.', process.env.VUE_APP_TEXTDOMAIN ),
				text_tooltip_demographics: sprintf( __( 'Enable this setting to add the Demographics and Remarketing features to your Google Analytics tracking code. Make sure to enable Demographics and Remarketing in your Google Analytics account. We have a guide for how to do that in our %1$sknowledge base%2$s. For more information about Remarketing, we refer you to %3$sGoogle\'s documentation%4$s. Note that usage of this function is affected by privacy and cookie laws around the world. Be sure to follow the laws that affect your target audience.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="' + this.$getUrl( 'settings-panel', 'demographics', 'https://www.monsterinsights.com/docs/enable-demographics-and-interests-report-in-google-analytics/' ) + '" target="_blank">', '</a>', '<a href="https://support.google.com/analytics/answer/2444872?hl=en_US" target="_blank" rel="noopener noreferrer">', '</a>' ),
				text_tooltip_anonymize: sprintf( __( 'This adds %1$sanonymizeIp%2$s, telling Google Analytics to anonymize the information sent by the tracker objects by removing the last octet of the IP address prior to its storage.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/ip-anonymization" target="_blank" rel="noopener noreferrer">', '</a>' ),
				text_tooltip_link_attribution: sprintf( __( 'Add %1$sEnhanced Link Attribution%2$s to your tracking code.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-link-attribution" target="_blank" rel="noopener noreferrer">', '</a>' ),
				text_tooltip_anchor_tracking: __( 'Many WordPress "1-page" style themes rely on anchor tags for navigation to show virtual pages. The problem is that to Google Analytics, these are all just a single page, and it makes it hard to get meaningful statistics about pages viewed. This feature allows proper tracking in those themes.', process.env.VUE_APP_TEXTDOMAIN ),
				text_tooltip_allow_anchor: sprintf( __( 'This adds %1$sallowAnchor%2$s to the create command of the pageview hit tracking code, and makes RSS link tagging use a # as well.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/field-reference#allowAnchor" target="_blank" rel="noopener noreferrer">', '</a>' ),
				text_tooltip_allow_linker: sprintf( __( 'Enabling %1$scross-domain tracking (additional setup required)%2$s allows you to track users across multiple properties you own (such as example-1.com and example-2.com as a single session. It also allows you fix an issue so that when a user has to go to an off-site hosted payment gateway to finish a purchase it doesn\'t count it as referral traffic from that gateway but maintains the visit as part of the same session.) It is required that the other site includes a Google Analytics tracker with the same UA Code.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="https://www.monsterinsights.com/docs/setup-cross-domain-tracking/" target="_blank">', '</a>' ),
				text_tooltip_tag_links_in_rss: sprintf( __( 'Do not use this feature if you use FeedBurner, as FeedBurner can do this automatically and better than this plugin can. Check this %1$shelp page%2$s for info on how to enable this feature in FeedBurner.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="https://support.google.com/feedburner/answer/165769?hl=en&ref_topic=13075" target="_blank" rel="noopener noreferrer">', '</a>' ),
				text_add_domain: __( 'Add domain', process.env.VUE_APP_TEXTDOMAIN ),
				domain_repeater_structure: [
					{
						name: 'domain',
						label: sprintf( __( 'Domain (example: %s)', process.env.VUE_APP_TEXTDOMAIN ), 'monsterinsights.com' ),
						pattern: new RegExp( "^(?!(?:.+)\\." + domain + "$|" + domain + "$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$" ),
						error: sprintf( __( 'Please enter domain names only ( example: example.com not http://example.com ) and not current site domain ( %s ).', process.env.VUE_APP_TEXTDOMAIN ), domain ),
						prevent_duplicates: true,
					},
				],
				text_cross_domain: __( 'Cross Domain Tracking', process.env.VUE_APP_TEXTDOMAIN ),
				text_cross_domain_description: sprintf( __( 'Cross domain tracking makes it possible for Analytics to see sessions on two related sites as a single session. More info on specific setup steps can be found in our %1$sknowledge base%2$s.', process.env.VUE_APP_TEXTDOMAIN ), '<a href="' + this.$getUrl( 'settings', 'cross-domain', 'https://www.monsterinsights.com/docs/setup-cross-domain-tracking/' ) + '" target="_blank" rel="noopener noreferrer">', '</a>' ),
			};
		},
		computed: {
			...mapGetters({
				addons: '$_addons/addons',
			}),
			isAddonActive() {
				if ( this.addons['eu-compliance']) {
					return this.addons['eu-compliance'].active;
				}
				return false;
			},
		},
	};
</script>
