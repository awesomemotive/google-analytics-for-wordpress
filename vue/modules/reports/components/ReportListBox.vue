<template>
	<div class="monsterinsights-table-box">
		<h3 v-if="title" class="monsterinsights-report-title" v-text="title"></h3>
		<settings-info-tooltip v-if="tooltip" :content="tooltip" />
		<div class="monsterinsights-table-box-list">
			<div v-for="(row, index) in tableRows()" :key="index" class="monsterinsights-table-list-item">
				<span class="monsterinsights-reports-list-count" v-text="row.number"></span>
				<span class="monsterinsights-reports-list-text" v-html="row.text"></span>
				<span class="monsterinsights-reports-list-number" v-html="row.right"></span>
			</div>
		</div>
		<div v-if="hasButtonSlot() || paginate" class="monsterinsights-table-box-footer">
			<slot name="button"></slot>
			<div v-if="paginate" class="monsterinsights-table-box-pagination">
				<span v-text="text_show"></span>
				<div class="monsterinsights-buttons-toggle">
					<button :class="getButtonClass(10)" v-on:click="limit=10">
						10
					</button>
					<button v-if="rows.length > 10" :class="getButtonClass(25)" v-on:click="limit=25">
						25
					</button>
					<button v-if="rows.length > 25" :class="getButtonClass(50)" v-on:click="limit=50">
						50
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import SettingsInfoTooltip from "../../settings/components/SettingsInfoTooltip";

	export default {
		name: 'ReportListBox',
		components: { SettingsInfoTooltip },
		props: {
			title: String,
			tooltip: String,
			rows: Array,
		},
		data() {
			return {
				paginate: false,
				limit: 10,
				text_show: __( 'Show', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		methods: {
			tableRows() {
				let rows = this.rows;

				if ( rows.length < 10 ) {
					while ( rows.length < 10 ) {
						rows.push( {
							number: '',
							text: '',
							right: '',
						} );
					}
				}

				if ( rows.length > 10 ) {
					this.paginate = true;
				}

				rows = rows.slice( 0, this.limit );

				return rows;
			},
			hasButtonSlot() {
				return this.$slots['button'];
			},
			getButtonClass( limit ) {
				let buttonClass = 'monsterinsights-button';

				if ( limit === this.limit ) {
					buttonClass += ' monsterinsights-selected-interval';
				}

				return buttonClass;
			},
		},
	};
</script>
