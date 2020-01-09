<template>
	<div class="monsterinsights-settings-content monsterinsights-tools-url-builder">
		<settings-block :title="text_block_label">
			<p>
				<span v-text="text_url_builder_description"></span>&nbsp;<settings-info-tooltip
					:content="text_url_builder_tooltip"
				/>
			</p>
			<div class="monsterinsights-separator"></div>
			<div class="monsterinsights-input-text">
				<label for="monsterinsights-tools-website-url">
					<span class="monsterinsights-dark" v-html="text_website_url_label"></span>
					<span v-html="text_website_url_description"></span>
				</label>
				<input id="monsterinsights-tools-website-url" v-model="website_url" type="text" />
			</div>
			<div class="monsterinsights-input-text">
				<label for="monsterinsights-tools-campaign-source">
					<span class="monsterinsights-dark" v-html="text_campaign_source_label"></span>
					<span v-html="text_campaign_source_description"></span>
				</label>
				<input id="monsterinsights-tools-campaign-source" v-model="campaign_source" type="text" />
			</div>
			<div class="monsterinsights-input-text">
				<label for="monsterinsights-tools-campaign-medium">
					<span class="monsterinsights-dark" v-html="text_campaign_medium_label"></span>
					<span v-html="text_campaign_medium_description"></span>
				</label>
				<input id="monsterinsights-tools-campaign-medium" v-model="campaign_medium" type="text" />
			</div>
			<div class="monsterinsights-input-text">
				<label for="monsterinsights-tools-campaign-name">
					<span class="monsterinsights-dark" v-html="text_campaign_name_label"></span>
					<span v-html="text_campaign_name_description"></span>
				</label>
				<input id="monsterinsights-tools-campaign-name" v-model="campaign_name" type="text" />
			</div>
			<div class="monsterinsights-input-text">
				<label for="monsterinsights-tools-campaign-term">
					<span class="monsterinsights-dark" v-html="text_campaign_term_label"></span>
					<span v-html="text_campaign_term_description"></span>
				</label>
				<input id="monsterinsights-tools-campaign-term" v-model="campaign_term" type="text" />
			</div>
			<div class="monsterinsights-input-text">
				<label for="monsterinsights-tools-campaign-content">
					<span class="monsterinsights-dark" v-html="text_campaign_content_label"></span>
					<span v-html="text_campaign_content_description"></span>
				</label>
				<input id="monsterinsights-tools-campaign-content" v-model="campaign_content" type="text" />
			</div>
			<div class="monsterinsights-input-text">
				<label for="monsterinsights-tools-use-fragment">
					<span class="monsterinsights-dark" v-html="text_use_fragment_label"></span>
					<span v-html="text_use_fragment_description"></span>
				</label>
				<div class="monsterinsights-settings-input-checkbox">
					<label v-on:click.prevent="stopClick" v-on:keyup.enter="stopClick" v-on:keyup.space="stopClick">
						<span :class="checkboxClass" tabindex="0"></span>
						<input id="monsterinsights-tools-use-fragment" v-model="use_fragment" type="checkbox" />
						<span class="monsterinsights-checkbox-label" v-html="text_use_fragment_label"></span>
					</label>
				</div>
			</div>
			<div class="monsterinsights-separator"></div>
			<div class="monsterinsights-input-text">
				<label for="monsterinsights-tools-url-to-use">
					<span class="monsterinsights-dark" v-html="text_url_to_use_label"></span>
					<span v-html="text_url_to_use_description"></span>
				</label>
				<textarea id="monsterinsights-tools-url-to-use" v-model="url_to_use" readonly></textarea>
			</div>
			<div>
				<button class="monsterinsights-button" v-on:click="copyToClipboard" v-text="text_copy"></button>
			</div>
		</settings-block>
		<tools-tab-footer />
		<settings-block :title="text_block_info_label">
			<p v-text="text_block_info_description"></p>
			<div class="monsterinsights-separator"></div>
			<div class="monsterinsights-tools-info-row">
				<div class="monsterinsights-tools-info-label">
					<span class="monsterinsights-dark" v-html="text_row_1_label"></span>
					<p>utm_source</p>
				</div>
				<div class="monsterinsights-tools-info-description">
					<p v-text="text_row_1_description"></p>
					<p v-text="sprintf( text_example, 'google')"></p>
				</div>
			</div>
			<div class="monsterinsights-separator"></div>
			<div class="monsterinsights-tools-info-row">
				<div class="monsterinsights-tools-info-label">
					<span class="monsterinsights-dark" v-html="text_row_2_label"></span>
					<p>utm_medium</p>
				</div>
				<div class="monsterinsights-tools-info-description">
					<p v-text="text_row_2_description"></p>
					<p v-text="sprintf( text_example, 'cpc')"></p>
				</div>
			</div>
			<div class="monsterinsights-separator"></div>
			<div class="monsterinsights-tools-info-row">
				<div class="monsterinsights-tools-info-label">
					<span class="monsterinsights-dark" v-html="text_row_3_label"></span>
					<p>utm_name</p>
				</div>
				<div class="monsterinsights-tools-info-description">
					<p v-text="text_row_3_description"></p>
					<p v-text="sprintf( text_example, 'utm_campaign=spring_sale')"></p>
				</div>
			</div>
			<div class="monsterinsights-separator"></div>
			<div class="monsterinsights-tools-info-row">
				<div class="monsterinsights-tools-info-label">
					<span class="monsterinsights-dark" v-html="text_row_4_label"></span>
					<p>utm_term</p>
				</div>
				<div class="monsterinsights-tools-info-description">
					<p v-text="text_row_4_description"></p>
					<p v-text="sprintf( text_example, 'running+shoes')"></p>
				</div>
			</div>
			<div class="monsterinsights-separator"></div>
			<div class="monsterinsights-tools-info-row">
				<div class="monsterinsights-tools-info-label">
					<span class="monsterinsights-dark" v-html="text_row_5_label"></span>
					<p>utm_content</p>
				</div>
				<div class="monsterinsights-tools-info-description">
					<p v-text="text_row_5_description"></p>
					<p v-text="sprintf( text_examples, 'logolink or textlink')"></p>
				</div>
			</div>
			<div class="monsterinsights-separator"></div>
			<p>
				<span class="monsterinsights-dark" v-text="text_additional_title"></span>
			</p>
			<p v-for="(link, index) in additional_information" :key="index" class="monsterinsights-toolsadditional-info">
				<i class="monstericon-files"></i> <a :href="link.url" target="_blank" v-text="link.text"></a>
			</p>
		</settings-block>
	</div>
</template>
<script>
	import { __, sprintf } from '@wordpress/i18n';
	import SettingsBlock from '../../settings/components/SettingsBlock';
	import SettingsInfoTooltip from "../../settings/components/SettingsInfoTooltip";
	import ToolsTabFooter from "./ToolsTabFooter-MI_VERSION";

	export default {
		name: 'ToolsTabUrlBuilder',
		components: { ToolsTabFooter, SettingsInfoTooltip, SettingsBlock },
		data() {
			return {
				text_block_label: __('Custom Campaign Parameters', process.env.VUE_APP_TEXTDOMAIN),
				text_url_builder_description: __('The URL builder helps you add parameters to your URLs you use in custom web or email ad campaigns.', process.env.VUE_APP_TEXTDOMAIN),
				text_url_builder_tooltip: __('A custom campaign is any ad campaign not using the AdWords auto-tagging feature. When users click one of the custom links, the unique parameters are sent to your Analytics account, so you can identify the urls that are the most effective in attracting users to your content.', process.env.VUE_APP_TEXTDOMAIN),
				text_website_url_label: sprintf(__('Website URL %s', process.env.VUE_APP_TEXTDOMAIN), '<span class="monsterinsights-required">*</span>'),
				text_website_url_description: sprintf( __('The full website URL (e.g. %1$s %2$s%3$s)', process.env.VUE_APP_TEXTDOMAIN), '<em>', window.location.origin, '</em>' ),
				text_campaign_source_label: sprintf(__('Campaign Source %s', process.env.VUE_APP_TEXTDOMAIN), '<span class="monsterinsights-required">*</span>'),
				text_campaign_source_description: sprintf(__('Enter a referrer (e.g. %1$sfacebook, newsletter, google%2$s)', process.env.VUE_APP_TEXTDOMAIN), '<em>', '</em>' ),
				text_campaign_medium_label: __('Campaign Medium', process.env.VUE_APP_TEXTDOMAIN),
				text_campaign_medium_description: sprintf(__('Enter a marketing medium (e.g. %1$scpc, banner, email%2$s)', process.env.VUE_APP_TEXTDOMAIN), '<em>', '</em>' ),
				text_campaign_name_label: __('Campaign Name', process.env.VUE_APP_TEXTDOMAIN),
				text_campaign_name_description: sprintf(__('Enter a name to easily identify (e.g. %1$sspring_sale%2$s)', process.env.VUE_APP_TEXTDOMAIN), '<em>', '</em>' ),
				text_campaign_term_label: __('Campaign Term', process.env.VUE_APP_TEXTDOMAIN),
				text_campaign_term_description: __('Enter the paid keyword', process.env.VUE_APP_TEXTDOMAIN),
				text_campaign_content_label: __('Campaign Content', process.env.VUE_APP_TEXTDOMAIN),
				text_campaign_content_description: __('Enter something to differentiate ads', process.env.VUE_APP_TEXTDOMAIN),
				text_use_fragment_label: __('Use Fragment', process.env.VUE_APP_TEXTDOMAIN),
				text_use_fragment_description: sprintf(__('Set the parameters in the fragment portion of the URL %1$s(not recommended)%2$s', process.env.VUE_APP_TEXTDOMAIN), '<strong>', '</strong>'),
				text_url_to_use_label: __('URL to use', process.env.VUE_APP_TEXTDOMAIN),
				text_url_to_use_description: __('Updates automatically', process.env.VUE_APP_TEXTDOMAIN),
				text_copy: __('Copy to clipboard', process.env.VUE_APP_TEXTDOMAIN),
				text_block_info_label: __('More Information & Examples', process.env.VUE_APP_TEXTDOMAIN),
				text_block_info_description: __('The following table gives a detailed explanation and example of each of the campaign parameters.', process.env.VUE_APP_TEXTDOMAIN),
				website_url: window.location.origin,
				campaign_source: '',
				campaign_medium: '',
				campaign_name: '',
				campaign_term: '',
				campaign_content: '',
				use_fragment: false,
				text_row_1_label: __('Campaign Source', process.env.VUE_APP_TEXTDOMAIN),
				text_row_1_description: __('Required. Use utm_source to identify a search engine, newsletter name, or other source.', process.env.VUE_APP_TEXTDOMAIN),
				text_row_2_label: __('Campaign Medium', process.env.VUE_APP_TEXTDOMAIN),
				text_row_2_description: __('Use utm_medium to identify a medium such as email or cost-per-click.', process.env.VUE_APP_TEXTDOMAIN),
				text_row_3_label: __('Campaign Name', process.env.VUE_APP_TEXTDOMAIN),
				text_row_3_description: __('Used for keyword analysis. Use utm_campaign to identify a specific product promotion or strategic campaign.', process.env.VUE_APP_TEXTDOMAIN),
				text_row_4_label: __('Campaign Term', process.env.VUE_APP_TEXTDOMAIN),
				text_row_4_description: __('Used for paid search. Use utm_term to note the keywords for this ad.', process.env.VUE_APP_TEXTDOMAIN),
				text_row_5_label: __('Campaign Content', process.env.VUE_APP_TEXTDOMAIN),
				text_row_5_description: __('Used for A/B testing and content-targeted ads. Use utm_content to differentiate ads or links that point to the same URL.', process.env.VUE_APP_TEXTDOMAIN),
				text_example: __('Example: %s', process.env.VUE_APP_TEXTDOMAIN),
				text_examples: __('Examples: %s', process.env.VUE_APP_TEXTDOMAIN),
				additional_information: [
					{
						text: __('About Campaigns', process.env.VUE_APP_TEXTDOMAIN),
						url: 'https://support.google.com/analytics/answer/1247851',
					},
					{
						text: __('About Custom Campaigns', process.env.VUE_APP_TEXTDOMAIN),
						url: 'https://support.google.com/analytics/answer/1033863',
					},
					{
						text: __('Best practices for creating Custom Campaigns', process.env.VUE_APP_TEXTDOMAIN),
						url: 'https://support.google.com/analytics/answer/1037445',
					},
					{
						text: __('About the Referral Traffic report', process.env.VUE_APP_TEXTDOMAIN),
						url: 'https://support.google.com/analytics/answer/1247839',
					},
					{
						text: __('About traffic source dimensions', process.env.VUE_APP_TEXTDOMAIN),
						url: 'https://support.google.com/analytics/answer/1033173',
					},
					{
						text: __('AdWords Auto-Tagging', process.env.VUE_APP_TEXTDOMAIN),
						url: 'https://support.google.com/adwords/answer/1752125',
					},
				],
				text_additional_title: __('Additional Information', process.env.VUE_APP_TEXTDOMAIN),
			};
		},
		computed: {
			url_to_use() {
				let url = '';

				if (this.website_url && this.campaign_source) {
					url = this.website_url;
					url = this.$addQueryArg(url, 'utm_source', this.campaign_source);

					if (this.campaign_medium) {
						url = this.$addQueryArg(url, 'utm_medium', this.campaign_medium);
					}
					if (this.campaign_name) {
						url = this.$addQueryArg(url, 'utm_campaign', this.campaign_name);
					}
					if (this.campaign_term) {
						url = this.$addQueryArg(url, 'utm_term', this.campaign_term);
					}
					if (this.campaign_content) {
						url = this.$addQueryArg(url, 'utm_content', this.campaign_content);
					}

					if (this.use_fragment) {
						url = url.replace('?', '#');
					}
				}

				return url;
			},
			checkboxClass() {
				let label_class = 'monsterinsights-styled-checkbox';

				if (this.use_fragment) {
					label_class += ' monsterinsights-styled-checkbox-checked';
				}

				return label_class;
			},
		},
		methods: {
			copyToClipboard() {
				document.querySelector('#monsterinsights-tools-url-to-use').select();
				document.execCommand('copy');
			},
			stopClick: function() {
				this.use_fragment = ! this.use_fragment;
			},
			sprintf,
		},
	};
</script>
