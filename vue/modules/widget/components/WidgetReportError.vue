<template>
	<div class="monsterinsights-widget-error">
		<div class="swal2-header">
			<div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;">
				<span class="swal2-x-mark">
					<span class="swal2-x-mark-line-left"></span>
					<span class="swal2-x-mark-line-right"></span>
				</span>
			</div>
			<h2 id="monsterinsights-error-title" class="swal2-title" v-text="title"></h2>
		</div>
		<div v-if="error.content" class="monsterinsights-error-content" v-html="error.content"></div>
		<div v-if="error.footer" class="monsterinsights-error-footer" v-html="error.footer"></div>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';

	export default {
		props: {
			error: [ Object, Boolean ],
		},
		computed: {
			title() {
				return this.error.title.title ? this.error.title.title : __( 'Error', process.env.VUE_APP_TEXTDOMAIN );
			},
		},
		name: 'WidgetReportError',
	};
</script>
