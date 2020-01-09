<template>
	<div v-if="modal" class="monsterinsights-modal" v-on:click="maybeClose">
		<div class="monsterinsights-modal-inner">
			<template v-if="1===step">
				<h2 v-text="text_modal_question"></h2>
				<div class="monsterinsights-modal-buttons">
					<button class="monsterinsights-button monsterinsights-button-disabled"
						v-on:click="step=3" v-text="text_not_really"
					></button>
					<button class="monsterinsights-button" v-on:click="step=2" v-text="text_yes"></button>
				</div>
			</template>
			<template v-if="2===step">
				<h2 v-text="text_modal_step_2_title"></h2>
				<p v-html="text_modal_step_2_subtitle"></p>
				<div class="monsterinsights-modal-buttons">
					<button class="monsterinsights-button monsterinsights-button-disabled"
						v-on:click="modal=false" v-text="text_nope"
					></button>
					<a href="https://wordpress.org/support/view/plugin-reviews/google-analytics-for-wordpress?filter=5"
						class="monsterinsights-button" v-text="text_you_deserve_it"
					></a>
					<button class="monsterinsights-button monsterinsights-button-text" v-on:click="modal=false"
						v-text="text_already"
					></button>
				</div>
			</template>
			<template v-if="3===step">
				<h2 v-text="text_modal_step_3_title"></h2>
				<div class="monsterinsights-modal-buttons">
					<a href="https://www.monsterinsights.com/plugin-feedback/" class="monsterinsights-button monsterinsights-button-green"
						v-text="text_give_feedback"
					></a>
				</div>
			</template>
		</div>
	</div>
</template>
<script>
	import { __, sprintf } from '@wordpress/i18n';

	export default {
		name: 'TheAppRatingModal',
		data() {
			return {
				modal: false,
				step: 1,
				text_modal_question: __( 'Are you enjoying MonsterInsights?', process.env.VUE_APP_TEXTDOMAIN ),
				text_not_really: __( 'Not Really', process.env.VUE_APP_TEXTDOMAIN ),
				text_yes: __( 'Yes!', process.env.VUE_APP_TEXTDOMAIN ),
				text_modal_step_2_title: __( 'Awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', process.env.VUE_APP_TEXTDOMAIN ),
				text_modal_step_3_title: __( 'Sorry to hear you aren\'t enjoying MonsterInsights. We would love a chance to improve. Could you take a minute and let us know what we can do better?', process.env.VUE_APP_TEXTDOMAIN ),
				text_you_deserve_it: __( 'Ok, you deserve it', process.env.VUE_APP_TEXTDOMAIN ),
				text_nope: __( 'Nope, maybe later', process.env.VUE_APP_TEXTDOMAIN ),
				text_already: __( 'I already did', process.env.VUE_APP_TEXTDOMAIN ),
				text_give_feedback: __( 'Give Feedback', process.env.VUE_APP_TEXTDOMAIN ),
				text_modal_step_2_subtitle: sprintf( __( '~ Syed Balkhi%sCo-Founder of MonsterInsights', process.env.VUE_APP_TEXTDOMAIN ), '<br>' ),

			};
		},
		methods: {
			openModal( event ) {
				event.preventDefault();
				this.step = 1;
				this.modal = true;
			},
			maybeClose( event ) {
				if ( event.target.classList.contains( 'monsterinsights-modal' ) ) {
					this.modal = false;
				}
			},
		},
		created() {
			const footer = document.getElementById( 'wpfooter' );
			const footer_links = footer.querySelectorAll( 'a' );

			footer_links.forEach( ( link ) => {
				link.addEventListener( 'click', this.openModal );
			} );
		},
	};
</script>
