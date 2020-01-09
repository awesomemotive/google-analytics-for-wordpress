<template>
	<div v-if="ftp_form.visible" id="request-filesystem-credentials-dialog" class="notification-dialog-wrap request-filesystem-credentials-dialog">
		<div class="notification-dialog-background"></div>
		<div class="notification-dialog" role="dialog" aria-labelledby="request-filesystem-credentials-title" tabindex="0">
			<div class="request-filesystem-credentials-dialog-content">
				<div id="request-filesystem-credentials-form" class="request-filesystem-credentials-form">
					<h1 id="request-filesystem-credentials-title" v-text="text_form_title"></h1>
					<p id="request-filesystem-credentials-desc" v-text="text_form_description"></p>
					<label for="hostname">
						<span class="field-title" v-text="text_hostname_label"></span>
						<input id="hostname" v-model="localHostname" name="hostname" type="text" aria-describedby="request-filesystem-credentials-desc" class="code" placeholder="example: www.wordpress.org" value="" autocomplete="off" />
					</label>
					<div class="ftp-username">
						<label for="username">
							<span class="field-title" v-text="text_username_label"></span>
							<input id="username" v-model="localUsername" name="username" type="text" value="" autocomplete="off" />
						</label>
					</div>
					<div class="ftp-password">
						<label for="password">
							<span class="field-title" v-text="text_password_label"></span>
							<input id="password" v-model="localPassword" name="password" type="password" value="" autocomplete="off" />
							<em v-text="text_password_description"></em>
						</label>
					</div>
					<fieldset>
						<legend v-text="text_connection_type_label"></legend>
						<label for="ftp">
							<input id="ftp" v-model="localConnectionType" type="radio" name="connection_type" value="ftp" />
							FTP
						</label>
						<label for="ftps">
							<input id="ftps" v-model="localConnectionType" type="radio" name="connection_type" value="ftps" />
							FTPS (SSL)
						</label>
					</fieldset>
					<p class="request-filesystem-credentials-action-buttons">
						<input id="_fs_nonce" type="hidden" name="_fs_nonce" value="830ef6f43c" />
						<button class="button cancel-button" data-js-action="close" type="button" v-on:click="hideForm" v-text="text_button_cancel"></button>
						<button id="upgrade" class="button" v-on:click="retryAction" v-text="text_button_proceed"></button>
					</p>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';

	export default {
		name: 'TheAppFTPForm',
		computed: {
			...mapGetters( {
				ftp_form: '$_app/ftp_form',
			} ),
			localHostname: {
				get() {
					return this.ftp_form.hostname;
				},
				set( value ) {
					this.$store.commit( '$_app/UPDATE_HOSTNAME', value );
				},
			},
			localUsername: {
				get() {
					return this.ftp_form.username;
				},
				set( value ) {
					this.$store.commit( '$_app/UPDATE_USERNAME', value );
				},
			},
			localPassword: {
				get() {
					return this.ftp_form.password;
				},
				set( value ) {
					this.$store.commit( '$_app/UPDATE_PASSWORD', value );
				},
			},
			localConnectionType: {
				get() {
					return this.ftp_form.connection_type;
				},
				set( value ) {
					this.$store.commit( '$_app/UPDATE_CONNECTION_TYPE', value );
				},
			},
		},
		data() {
			return {
				text_form_title: __( 'Connection Information', process.env.VUE_APP_TEXTDOMAIN ),
				text_form_description: __( 'To perform the requested action, WordPress needs to access your web server. Please enter your FTP credentials to proceed. If you do not remember your credentials, you should contact your web host.', process.env.VUE_APP_TEXTDOMAIN ),
				text_hostname_label: __( 'Hostname', process.env.VUE_APP_TEXTDOMAIN ),
				text_username_label: __( 'FTP Username', process.env.VUE_APP_TEXTDOMAIN ),
				text_password_label: __( 'FTP Password', process.env.VUE_APP_TEXTDOMAIN ),
				text_password_description: __( 'This password will not be stored on the server.', process.env.VUE_APP_TEXTDOMAIN ),
				text_connection_type_label: __( 'Connection Type', process.env.VUE_APP_TEXTDOMAIN ),
				text_button_cancel: __( 'Cancel', process.env.VUE_APP_TEXTDOMAIN ),
				text_button_proceed: __( 'Proceed', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		methods: {
			hideForm() {
				this.$store.commit( '$_app/HIDE_FTP_FORM' );
			},
			retryAction() {
				const self = this;
				this.$store.commit( '$_app/HIDE_FTP_FORM' );
				this.$mi_loading_toast( __( 'Please wait...', process.env.VUE_APP_TEXTDOMAIN ) );
				this.$store.dispatch( this.ftp_form.action, this.ftp_form.data ).then(
					function() {
						self.$swal.close();
					},
				);
			},
		},
	};
</script>

<style scoped>
	#request-filesystem-credentials-dialog {
		display: block;
	}
</style>
