<template>
	<div :class="componentClass">
		<h3 v-if="title" class="monsterinsights-report-title" v-text="title"></h3>
		<settings-info-tooltip v-if="tooltip" :content="tooltip" />
		<div class="monsterinsights-table-box-list monsterinsights-table-box-table">
			<table>
				<thead v-if="headers.length > 0">
					<tr>
						<th v-for="(header,index) in headers" :key="index" v-text="header"></th>
					</tr>
				</thead>
				<tbody v-if="rows.length > 0">
					<tr v-for="(row,index) in tableRows" :key="index" :class="rowClass(index)"
						v-on:click="toggleMobileTables(index)"
					>
						<td v-for="(cell,cellindex) in row" :key="cellindex" :class="cellClass(cellindex)">
							<div v-if="showMobileRow(index, cellindex)" class="monsterinsights-table-mobile-heading"
								v-text="headers[cellindex]"
							></div>
							<div class="monsterinsights-table-item-content" v-html="cellText(cell,cellindex,index)"></div>
						</td>
					</tr>
				</tbody>
				<tbody v-else>
					<tr v-for="(row,index) in emptyTable" :key="index"
						class="monsterinsights-table-list-item monsterinsights-table-list-item-empty"
					>
						<td :colspan="headers.length" v-html="row[0]"></td>
					</tr>
				</tbody>
			</table>
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
	import { mapGetters } from 'vuex';
	import SettingsInfoTooltip from "../../settings/components/SettingsInfoTooltip";

	export default {
		name: 'ReportTableBox',
		components: { SettingsInfoTooltip },
		props: {
			title: String,
			tooltip: String,
			rows: Array,
			headers: Array,
			button: Object,
			emptytext: String,
			mobileWidth: {
				default: 783,
				type: Number,
			},
		},
		data() {
			return {
				paginate: false,
				limit: 10,
				text_show: __( 'Show', process.env.VUE_APP_TEXTDOMAIN ),
				activeRow: '',
				isMobile: false,
				resizing: false,
			};
		},
		computed: {
			...mapGetters( {
				mobileTableExpanded: '$_reports/mobileTableExpanded',
			} ),
			mobileHeaders() {
				let headers = [];
				this.headers.forEach( function( value, index ) {
					if ( index > 0 ) {
						headers.push( value );
					}
				} );
				return headers;
			},
			emptyTable() {
				let rows = [
					[
						this.emptytext,
					],
				];

				while ( rows.length < 10 ) {
					rows.push( [
						'&nbsp;',
					] );
				}

				return rows;
			},
			componentClass() {
				let componentClass = 'monsterinsights-table-box';
				if ( this.isMobile ) {
					componentClass += ' monsterinsights-table-box-mobile';
				}
				return componentClass;
			},
			tableRows() {
				let rows = this.rows;

				if ( rows.length < 10 ) {
					let empty_array = [];
					let i = 0;
					while ( i < this.headers.length ) {
						empty_array.push( '' );
						i++;
					}
					while ( rows.length < 10 ) {
						rows.push( empty_array );
					}
				}

				rows = rows.slice( 0, this.limit );

				return rows;
			},
		},
		methods: {
			hasButtonSlot() {
				if ( this.rows.length > 10 ) {
					this.paginate = true;
				}
				return this.$slots['button'];
			},
			getButtonClass( limit ) {
				let buttonClass = 'monsterinsights-button';

				if ( limit === this.limit ) {
					buttonClass += ' monsterinsights-selected-interval';
				}

				return buttonClass;
			},
			cellText( cell, cellindex, index ) {
				if ( '' === cell ) {
					return '&nbsp;';
				}
				if ( 0 === cellindex ) {
					let count = index + 1;
					return '<span class="monsterinsights-reports-list-count">' + count + '.</span><span class="monsterinsights-reports-list-title">' + cell + '</span>';
				}
				return cell;
			},
			rowClass( index ) {
				let rowClass = 'monsterinsights-table-list-item';

				if ( ( this.mobileTableExpanded || this.activeRow === index ) && window.innerWidth < this.mobileWidth ) {
					rowClass += ' monsterinsights-table-list-item-active';
				}
				if ( '' === this.tableRows[index][0] ) {
					rowClass += ' monsterinsights-table-list-item-empty';
				}
				return rowClass;
			},
			showMobileRow( index, cellindex ) {
				return window.innerWidth < this.mobileWidth && cellindex > 0 && (this.mobileTableExpanded || index === this.activeRow);
			},
			handleResize() {
				if ( ! this.resizing ) {
					this.resizing = true;
					if ( window.requestAnimationFrame ) {
						window.requestAnimationFrame( this.resizeCallback );
					} else {
						setTimeout( this.resizeCallback, 66 );
					}
				}
			},
			resizeCallback() {
				this.isMobile = window.innerWidth < this.mobileWidth;
				this.resizing = false;
			},
			cellClass( index ) {
				index++;
				return 'monsterinsights-table-cell-' + index;
			},
			toggleMobileTables(index) {
				if ( this.mobileTableExpanded ) {
					return false;
				}
				this.activeRow = this.activeRow === index ? '' : index;
			},
		},
		mounted() {
			window.addEventListener( 'resize', this.handleResize );
			this.handleResize();
		},
		beforeDestroy: function() {
			window.removeEventListener( 'resize', this.handleResize );
		},
	};
</script>
