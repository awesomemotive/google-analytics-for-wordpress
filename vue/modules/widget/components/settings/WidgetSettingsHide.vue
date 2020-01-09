<template>
	<button class="monsterinsights-hide-button" v-on:click.prevent="hideWidget"
		v-text="text_hide_widget"
	></button>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';

	export default {
		name: 'WidgetSettingsHide',
		data() {
			return {
				text_hide_widget: __( 'Hide dashboard widget', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters( {
				widget_width: '$_widget/width',
			} ),
			fullWidth() {
				return 'regular' !== this.widget_width;
			},
		},
		methods: {
			hideWidget() {
				const self = this;
				this.$swal( {
					type: 'info',
					customContainerClass: 'monsterinsights-swal',
					title: __( 'Are you sure you want to hide the MonsterInsights Dashboard Widget? ', process.env.VUE_APP_TEXTDOMAIN ),
					showCancelButton: true,
					confirmButtonText: __( 'Yes, hide it!', process.env.VUE_APP_TEXTDOMAIN ),
					cancelButtonText: __( 'No, cancel!', process.env.VUE_APP_TEXTDOMAIN ),
					reverseButtons: true,
				} ).then( function(result) {
					if (result.value) {
						self.$swal({
							type: 'success',
							title: __( 'MonsterInsights Widget Hidden', process.env.VUE_APP_TEXTDOMAIN ),
							html: __( 'You can re-enable the MonsterInsights widget at any time using the "Screen Options" menu on the top right of this page', process.env.VUE_APP_TEXTDOMAIN ),
						});
						const widget_input = document.getElementById( 'monsterinsights_reports_widget-hide' );
						if ( widget_input ) {
							widget_input.click();
						}
					}
				});
			},
		},
	};
</script>
