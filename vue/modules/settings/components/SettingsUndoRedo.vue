<template>
	<div class="monsterinsights-undo-redo">
		<button v-if="showUndo()" class="monsterinsights-undo" v-on:click="undo" v-text="text_undo"></button>
		<button v-if="showRedo()" class="monsterinsights-redo" v-on:click="redo" v-text="text_redo"></button>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';

	export default {
		name: 'SettingsUndoRedo',
		data() {
			return {
				text_undo: __( 'Undo', process.env.VUE_APP_TEXTDOMAIN ),
				text_redo: __( 'Redo', process.env.VUE_APP_TEXTDOMAIN ),
			};
		},
		computed: {
			...mapGetters({
				history: '$_settings/history',
				historyIndex: '$_settings/historyIndex',
			}),
		},
		methods: {
			undo() {
				this.$store.dispatch( '$_settings/undo' );
			},
			redo() {
				this.$store.dispatch( '$_settings/redo' );
			},
			showUndo() {
				return this.history.length > 0 && this.historyIndex > 0;
			},
			showRedo() {
				return this.history.length > 0 && this.historyIndex < this.history.length - 1;
			},
		},
	};
</script>
