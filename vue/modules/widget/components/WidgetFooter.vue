<template>
	<div v-if="!wpforms_enabled" class="monsterinsights-widget-footer">
		<span v-html="text_recommended_plugin"></span>
		<span> - </span>
		<a :href="wpforms_install" v-text="text_action"></a>
		<a href="https://wpforms.com/" target="_blank" v-text="text_learn_more"></a>
	</div>
</template>
<script>
	import { sprintf, __ } from '@wordpress/i18n';

	export default {
		name: 'WidgetFooter',
		data() {
			return {
				wpforms_enabled: this.$mi.wpforms_enabled,
				wpforms_install: this.$mi.wpforms_url,
				text_recommended_plugin: sprintf( __( 'Recommended Plugin: %s', process.env.VUE_APP_TEXTDOMAIN ), '<span class="monsterinsights-dark">WPForms</span>' ),
				text_install: __( 'Install', process.env.VUE_APP_TEXTDOMAIN ),
				text_activate: __( 'Activate', process.env.VUE_APP_TEXTDOMAIN ),
				text_learn_more: __( 'Learn More', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			text_action() {
				return this.$mi.wpforms_installed ? this.text_activate : this.text_install;
			},
		},
	};
</script>
