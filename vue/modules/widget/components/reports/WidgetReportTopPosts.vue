<template>
	<report-list-box :title="text_top_posts" :rows="topPostsData" :tooltip="text_top_posts_tooltip">
		<a v-if="gaLinks" slot="button" :href="overview.galinks.topposts"
			class="monsterinsights-button" target="_blank" v-text="text_top_posts_button"
		></a>
	</report-list-box>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import ReportListBox from "../../../reports/components/ReportListBox";

	export default {
		name: 'WidgetReportTopPosts',
		components: { ReportListBox },
		data() {
			return {
				text_top_posts_button: __( 'View Full Posts/Pages Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_posts: __( 'Top Posts/Pages', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_posts_tooltip: __( 'This list shows the most viewed posts and pages on your website.', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				overview: '$_reports/overview',
			} ),
			gaLinks() {
				return this.overview.galinks ? true : false;
			},
			topPostsData() {
				let pages = [];
				let number = 0;
				if ( this.overview.toppages ) {
					this.overview.toppages.forEach( function( page ) {
						number++;
						let text = page.hostname ? '<a href="' + page.hostname + page.url + '" target="_blank" rel="noreferrer noopener">' + page.title + '</a>' : page.title;
						pages.push( {
							number: number + '.',
							text: text,
							right: page.sessions,
						} );
					} );
				}
				return pages;
			},
		},
	};
</script>
