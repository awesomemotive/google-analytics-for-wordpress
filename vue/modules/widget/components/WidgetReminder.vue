<template>
	<transition name="monsterinsights-slide-up">
		<div v-if="visitors && !hide" class="monsterinsights-tracking-notice">
			<div class="monsterinsights-tracking-notice-icon">
				<div class="monsterinsights-bg-img monsterinsights-fullwidth-mascot"></div>
			</div>
			<div class="monsterinsights-tracking-notice-text">
				<h3 v-text="title"></h3>
				<p>
					<span v-html="content"></span>&nbsp;<a :href="$mi.reports_url" v-on:click="hideNotice"
						v-text="text_link"
					></a>
				</p>
			</div>
			<div class="monsterinsights-tracking-notice-close" v-on:click="hideNotice">
				&times;
			</div>
		</div>
	</transition>
</template>
<script>
	import { __, sprintf } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import ReportsApi from '../../reports/api/index';
	import moment from 'moment';
	import '@/assets/scss/MI_THEME/dashboard-reminder.scss';

	export default {
		name: 'WidgetReminder',
		data() {
			return {
				text_title: __( 'See how %s visitors found your site!', process.env.VUE_APP_TEXTDOMAIN ),
				text_content: __( 'Your website was visited by %s users in the last 30 days.', process.env.VUE_APP_TEXTDOMAIN ),
				text_link: __( 'See the full analytics report!', process.env.VUE_APP_TEXTDOMAIN ),
				visitors_number: '',
				hide: false,
			};
		},
		computed: {
			...mapGetters( {
				overview: '$_reports/overview',
				date: '$_reports/date',
				widget_notice: '$_widget/notice30day',
			} ),
			loadNotice() {
				if ( 'undefined' === typeof this.$mi.widget_state.notice30day ) {
					return false;
				}
				if ( false === this.$mi.widget_state.notice30day ) {
					// Widget notice loaded first time, save date.
					this.$store.dispatch( '$_widget/markNoticeClosed' );
					return false;
				}
				let a = moment();
				let b = moment.unix( this.$mi.widget_state.notice30day );
				return a.diff( b, 'days' ) >= 30;
			},
			visitors: {
				get() {
					if ( ! this.loadNotice ) {
						return false;
					}
					if ( this.visitors_number ) {
						return this.visitors_number;
					}
					// If the current interval is not 30 days, we need to fetch the report data for that manually without interfering with the report displayed.
					if ( this.date.interval && 30 !== this.date.interval ) {
						return this.get30daysReportData();
					}
					// If the current interval is 30 days, we already have the data we need so we attempt to show that.
					return this.overview.infobox ? this.overview.infobox.sessions.value : '';
				},
				set(value) {
					this.visitors_number = value;
				},
			},
			title() {
				return sprintf( this.text_title, this.visitors );
			},
			content() {
				return sprintf( this.text_content, this.visitors );
			},
		},
		methods: {
			hideNotice() {
				this.$store.dispatch( '$_widget/markNoticeClosed' );
				this.hide = true;
			},
			get30daysReportData() {
				const self = this;
				const days = 30;
				let endDate = moment().subtract( 1, 'day' );
				let startDate = moment( endDate ).subtract( days - 1, 'day' );
				ReportsApi.fetchReportData( this.$store, 'overview', startDate.format( 'YYYY-MM-DD' ), endDate.format( 'YYYY-MM-DD' ) ).then( function( response ) {
					if ( response.data && response.data.infobox ) {
						setTimeout( () => {
							self.hide = false;
						}, 1500 );
						self.visitors = response.data.infobox.sessions.value;
					}
				} );
			},
		},
	};
</script>
