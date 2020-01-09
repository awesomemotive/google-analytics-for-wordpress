<template>
	<div v-if="value" class="monsterinsights-reports-infobox">
		<div v-if="title" class="monsterinsights-report-title" v-text="title"></div>
		<settings-info-tooltip v-if="tooltip" :content="tooltip"></settings-info-tooltip>
		<div v-if="value" class="monsterinsights-reports-infobox-number" :title="value" v-text="value"></div>
		<div :class="changeClass" v-html="changeText"></div>
		<div v-if="days" class="monsterinsights-reports-infobox-compare" v-text="compare"></div>
	</div>
</template>
<script>

	import { __, _n, sprintf } from '@wordpress/i18n';
	import SettingsInfoTooltip from "../../settings/components/SettingsInfoTooltip";

	export default {
		name: 'ReportInfobox',
		components: { SettingsInfoTooltip },
		props: {
			title: String,
			value: String,
			days: Number,
			tooltip: String,
			change: Number,
			color: {
				default: 'green',
				type: String,
			},
			direction: {
				default: 'up',
				type: String,
			},
		},
		computed: {
			compare() {
				return _n( 'vs. Previous Day', sprintf( 'vs. Previous %s Days', this.days ), this.days, process.env.VUE_APP_TEXTDOMAIN );
			},
			changeClass() {
				let changeClass = 'monsterinsights-reports-infobox-prev';
				if ( 0 === this.change ) {
					return changeClass;
				}
				return changeClass + ' ' + 'monsterinsights-' + this.color;
			},
			changeText() {
				if ( this.change ) {
					if ( '' === this.direction ) {
						return this.change + '%';
					}

					return '<span class="monsterinsights-arrow monsterinsights-' + this.direction + ' monsterinsights-' + this.color + '"></span> ' + this.change + '%';
				} else {
					return __( 'No change', process.env.VUE_APP_TEXTDOMAIN );
				}
			},
		},
	};
</script>
