<template>
	<div class="monsterinsights-notices-area">
		<div class="monsterinsights-container">
			<slide-down-up :group="true">
				<div v-for="(notice, index) in notices" :key="index" :class="getNoticeClass(notice.type)">
					<div class="monsterinsights-notice-inner">
						<button v-if="notice.dismissable" class="dismiss-notice" v-on:click="removeNotice(index)">
							<i class="monstericon-times"></i>
						</button>
						<div class="notice-content">
							<h2 v-if="notice.title" class="notice-title" v-html="notice.title"></h2>
							<span v-html="notice.content"></span>
							<div v-if="notice.button && notice.button.enabled" class="monsterinsights-notice-button">
								<a :class="buttonClass( notice.type )" target="_blank" :href="notice.button.link"
									v-text="notice.button.text"
								></a>
							</div>
						</div>
					</div>
				</div>
			</slide-down-up>
		</div>
	</div>
</template>

<script>
	import { mapGetters } from 'vuex';
	import SlideDownUp from './helper/SlideDownUp';

	export default {
		name: 'TheAppNotices',
		components: { SlideDownUp },
		computed: {
			...mapGetters( {
				notices: '$_app/notices',
			} ),
		},
		methods: {
			removeNotice( index ) {
				this.$store.dispatch( '$_app/removeNotice', index );
			},
			getNoticeClass( type ) {
				return 'monsterinsights-notice monsterinsights-notice-' + type;
			},
			buttonClass( type ) {
				let buttonClass = 'monsterinsights-button';

				if ( 'success' === type ) {
					buttonClass += ' monsterinsights-button-green';
				}

				return buttonClass;
			},
		},
	};
</script>
