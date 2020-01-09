<template>
	<div class="monsterinsights-onboarding-wizard-container">
		<div class="monsterinsights-onboarding-wizard-steps">
			<template v-for="(step, index) in steps">
				<div v-if="index > 0" :key="index + 'line'" :class="lineClass( index )"></div>
				<div :key="index" :class="stepClass(index)"></div>
			</template>
		</div>
	</div>
</template>
<script>
	export default {
		name: 'TheWizardTimeline',
		data() {
			return {
				steps: this.$wizard_steps,
			};
		},
		methods: {
			stepClass( step ) {
				let stepClass = 'monsterinsights-onboarding-wizard-step';

				let index = 0;
				for ( let stepIndex in this.steps ) {
					if ( this.$route.name === this.steps[stepIndex]) {
						index = stepIndex;
					}
				}

				if ( step < index ) {
					stepClass += ' monsterinsights-onboarding-wizard-step-completed';
				}

				if ( parseInt( step ) === parseInt( index ) ) {
					stepClass += ' monsterinsights-onboarding-wizard-step-active';
				}

				return stepClass;
			},
			lineClass( step ) {
				let stepClass = 'monsterinsights-onboarding-wizard-step-line';

				let index = 0;
				for ( let stepIndex in this.steps ) {
					if ( this.$route.name === this.steps[stepIndex]) {
						index = stepIndex;
					}
				}

				if ( step <= index ) {
					stepClass += ' monsterinsights-onboarding-wizard-line-active';
				}

				return stepClass;
			},

		},
	};
</script>
