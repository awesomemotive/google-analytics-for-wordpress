<script>
	export default {
		name: 'SlideDownUp',
		functional: true,
		props: {
			group: {
				type: Boolean,
				default: false,
			},
			done: Function,
		},
		render( createElement, context ) {
			const data = {
				props: {
					name: 'expand',
				},
				on: {
					afterEnter( element ) {
						// eslint-disable-next-line no-param-reassign
						element.style.height = 'auto';
					},
					enter( element ) {
						const { width } = getComputedStyle( element );
						/* eslint-disable no-param-reassign */
						element.style.width = width;
						element.style.position = 'absolute';
						element.style.visibility = 'hidden';
						element.style.height = 'auto';
						/* eslint-enable */

						const { height } = getComputedStyle( element );

						/* eslint-disable no-param-reassign */
						element.style.width = 'auto';
						element.style.position = 'relative';
						element.style.visibility = 'visible';
						element.style.height = 0;
						/* eslint-enable */

						setTimeout( () => {
							// eslint-disable-next-line no-param-reassign
							element.style.height = height;
						});
						if ( context.props.done ) {
							setTimeout( () => {
								context.props.done();
							}, 500 );
						}
					},
					leave( element ) {
						const { height } = getComputedStyle( element );

						// eslint-disable-next-line no-param-reassign
						element.style.height = height;

						setTimeout( () => {
							// eslint-disable-next-line no-param-reassign
							element.style.height = 0;
						});
					},
				},
			};

			let tag = 'transition';
			if ( context.props.group ) {
				tag = 'transition-group';
			}

			return createElement( tag, data, context.children );
		},
	};
</script>

<style scoped>
	* {
		will-change: height;
		transform: translateZ(0);
		backface-visibility: hidden;
		perspective: 1000px;
	}
</style>

<style>
	.expand-enter-active,
	.expand-leave-active {
		transition: height 500ms ease-in-out;
		overflow: hidden;
	}

	.expand-enter,
	.expand-leave-to {
		height: 0;
	}
</style>
