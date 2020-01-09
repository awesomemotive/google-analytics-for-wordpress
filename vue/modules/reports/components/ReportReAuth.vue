<template>
	<div class="monsterinsights-not-authenticated-notice">
		<h3 v-text="text_no_auth"></h3>
		<p v-text="text_auth_label"></p>
		<p>
			<button class="monsterinsights-button" v-on:click="doReAuth" v-text="text_button_reconnect"></button>
		</p>
	</div>
</template>

<script>

	import { __ } from '@wordpress/i18n';

	export default {
		name: 'ReportReAuth',
		data() {
			return {
				text_no_auth: __( 'MonsterInsights encountered an error loading your report data', process.env.VUE_APP_TEXTDOMAIN ),
				text_auth_label: __( 'There is an issue with your Google Account authentication. Please use the button below to fix it by re-authenticating.', process.env.VUE_APP_TEXTDOMAIN ),
				text_button_reconnect: __( 'Reconnect MonsterInsights', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		methods: {
			doReAuth: function( e ) {
				e.preventDefault();
				const self = this;
				this.$swal({
					type: 'info',
					title: __( 'Re-Authenticating', process.env.VUE_APP_TEXTDOMAIN ),
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
					onOpen: function() {
						self.$swal.showLoading();
					},
				});

				this.$store.dispatch( '$_auth/doReAuth', this.is_network ).then( function( resolve ) {
					if ( resolve.data.redirect ) {
						window.location = resolve.data.redirect;
					} else {
						self.$swal({
							type: 'error',
							title: __( 'Error', process.env.VUE_APP_TEXTDOMAIN ),
							text: resolve.data.message,
							confirmButtonText: __( 'Ok', process.env.VUE_APP_TEXTDOMAIN ),
						});
					}
				});
			},
		},
	};
</script>
