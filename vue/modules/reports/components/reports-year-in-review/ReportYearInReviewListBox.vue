<template>
	<div class="monsterinsights-year-in-review-table-box">
		<div class="monsterinsights-year-in-review-table-box-header">
			<div class="monsterinsights-year-in-review-table-box-title">
				<h3 v-if="title" class="monsterinsights-report-title" v-text="title"></h3>
			</div>
			<div class="monsterinsights-year-in-review-table-box-subtitle">
				<h4 v-if="subTitle" class="monsterinsights-report-subtitle" v-text="subTitle"></h4>
			</div>
		</div>
		<div class="monsterinsights-year-in-review-table-box-list">
			<div v-for="(row, index) in tableRows()" :key="index" class="monsterinsights-table-list-item">
				<span class="monsterinsights-reports-list-count" v-text="row.number"></span>
				<span class="monsterinsights-reports-list-text" v-html="row.text"></span>
				<span class="monsterinsights-reports-list-number" v-html="parseFloat(row.right).toLocaleString('en')"></span>
			</div>
		</div>
		<div v-if="tooltip" class="monsterinsights-year-in-review-table-box-footer">
			<span class="monsterinsights-yir-tooltip"><span class="monsterinsights-yir-icon">?</span><span v-text="tooltip"></span></span>
		</div>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';

	export default {
		name: 'ReportYearInReviewListBox',
		props: {
			title: String,
			subTitle: String,
			tooltip: String,
			rows: Array,
		},
		data() {
			return {
				limit: 5,
				text_show: __( 'Show', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		methods: {
			tableRows() {
				let rows = this.rows;

				if ( rows.length < 5 ) {
					while ( rows.length < 5 ) {
						rows.push( {
							number: '',
							text: '',
							right: '',
						} );
					}
				}

				rows = rows.slice( 0, this.limit );

				return rows;
			},
		},
	};
</script>
