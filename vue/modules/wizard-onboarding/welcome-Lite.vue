<template>
	<div class="monsterinsights-admin-page monsterinsights-welcome">
		<div class="monsterinsights-welcome-container">
			<div class="monsterinsights-welcome-block monsterinsights-welcome-block-first">
				<div class="monsterinsights-welcome-logo-container">
					<div class="monsterinsights-welcome-logo monsterinsights-bg-img"></div>
				</div>
				<div class="monsterinsights-welcome-block-inner">
					<h3 v-text="welcome_title"></h3>
					<p class="monsterinsights-subtitle" v-text="text_welcome_subtitle"></p>
				</div>
				<div class="monsterinsights-welcome-video">
					<div class="monsterinsights-welcome-video-image monsterinsights-bg-img" v-on:click="welcome_video=true"></div>
					<welcome-overlay v-if="welcome_video" id="welcome-video" v-on:close="welcome_video=false">
						<iframe width="1280" height="720" src="https://www.youtube.com/embed/IbdKpSygp2U?autoplay=1" frameborder="0"
							allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
							allowfullscreen
						></iframe>
					</welcome-overlay>
				</div>
				<div class="monsterinsights-welcome-block-inner">
					<p v-text="text_above_buttons"></p>
					<div class="monsterinsights-button-wrap">
						<div class="monsterinsights-welcome-left">
							<a :href="wizard_url" class="monsterinsights-button monsterinsights-button-large" v-text="text_wizard_button"></a>
						</div>
						<div class="monsterinsights-welcome-right">
							<a :href="$getUrl( 'welcome-screen', 'guide', 'https://www.monsterinsights.com/docs/connect-google-analytics/')"
								target="_blank"
								rel="noopener noreferrer"
								class="monsterinsights-button monsterinsights-button-alt monsterinsights-button-large"
								v-text="text_read_more_button"
							></a>
						</div>
					</div>
				</div>
			</div>
			<div class="monsterinsights-welcome-block">
				<div class="monsterinsights-welcome-block-inner">
					<h3 v-text="text_features_title"></h3>
					<p class="monsterinsights-subtitle" v-text="text_features_subtitle"></p>
				</div>
				<div class="monsterinsights-welcome-block-inner monsterinsights-welcome-features">
					<div v-for="(feature, index) in features" :key="index" class="monsterinsights-welcome-feature">
						<div class="monsterinsights-welcome-feature-img" v-html="feature.icon">
						</div>
						<div class="monsterinsights-welcome-feature-text">
							<h4 v-text="feature.name"></h4>
							<p v-text="feature.description"></p>
						</div>
					</div>
				</div>
				<div class="monsterinsights-welcome-block-inner monsterinsights-welcome-block-footer">
					<a class="monsterinsights-button"
						:href="$getUrl( 'welcome-screen', 'features-button', 'https://monsterinsights.com/features')"
						target="_blank" v-text="text_view_all_features"
					></a>
				</div>
				<div class="monsterinsights-upgrade-cta">
					<div class="monsterinsights-welcome-block-inner">
						<div class="monsterinsights-welcome-left">
							<h2 v-text="text_upgrade_to_pro"></h2>
							<ul>
								<li v-for="(pro_feature, index) in pro_features" :key="index">
									<i class="monstericon-check"></i>
									{{ pro_feature }}
								</li>
							</ul>
						</div>
						<div class="monsterinsights-welcome-right">
							<h2><span>PRO</span></h2>
							<div class="monsterinsights-price">
								<span class="monsterinsights-amount">
									199
								</span><br />
								<span class="monsterinsights-term" v-text="text_per_year"></span>
							</div>
							<a :href="$getUpgradeUrl( 'welcome-screen', 'upgrade-features')" rel="noopener noreferrer"
								target="_blank"
								class="monsterinsights-button monsterinsights-button-large"
								v-text="text_upgrade_now"
							></a>
						</div>
					</div>
				</div>
				<div class="monsterinsights-welcome-testimonials monsterinsights-welcome-block-inner">
					<h3 v-text="text_testimonials"></h3>
					<div v-for="(testimonial, index) in testimonials" :key="index" class="monsterinsights-welcome-testimonial">
						<div class="monsterinsights-welcome-testimonial-image">
							<div :class="testimonial.image + ' monsterinsights-bg-img'"></div>
						</div>
						<div class="monsterinsights-welcome-testimonial-text">
							<p v-text="testimonial.text"></p>
							<p><strong v-text="testimonial.author"></strong>, {{ testimonial.function }}</p>
						</div>
					</div>
				</div>
				<div class="monsterinsights-welcome-footer-upsell monsterinsights-welcome-block-inner">
					<div class="monsterinsights-welcome-left">
						<a :href="wizard_url" class="monsterinsights-button" v-text="text_wizard_button"></a>
					</div>
					<div class="monsterinsights-welcome-right">
						<a :href="$getUpgradeUrl( 'welcome-screen', 'upgrade-testimonials')" rel="noopener noreferrer"
							target="_blank"
							class="monsterinsights-button monsterinsights-button-alt"
							v-text="text_upgrade_now"
						></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import { __ } from '@wordpress/i18n';
	import '@/assets/scss/MI_THEME/welcome.scss';
	import WelcomeOverlay from "./components/WelcomeOverlay";

	export default {
		name: 'WizardModuleWelcome',
		components: { WelcomeOverlay },
		data() {
			return {
				text_welcome_title: __( 'Welcome to MonsterInsights', process.env.VUE_APP_TEXTDOMAIN ),
				text_welcome_subtitle: __( 'Thank you for choosing MonsterInsights - The Most Powerful WordPress Analytics Plugin', process.env.VUE_APP_TEXTDOMAIN ),
				text_above_buttons: __( 'MonsterInsights makes it “effortless” to setup Google Analytics in WordPress, the RIGHT Way. You can watch the video tutorial or use our 3 minute setup wizard.', process.env.VUE_APP_TEXTDOMAIN ),
				text_wizard_button: __( 'Launch the Wizard!', process.env.VUE_APP_TEXTDOMAIN ),
				text_read_more_button: __( 'Read the Full Guide', process.env.VUE_APP_TEXTDOMAIN ),
				text_features_title: __( 'MonsterInsights Features & Addons', process.env.VUE_APP_TEXTDOMAIN ),
				text_features_subtitle: __( 'Here are the features that make MonsterInsights the most powerful and user-friendly WordPress analytics plugin in the market.', process.env.VUE_APP_TEXTDOMAIN ),
				text_view_all_features: __( 'See All Features', process.env.VUE_APP_TEXTDOMAIN ),
				text_upgrade_to_pro: __( 'Upgrade to PRO', process.env.VUE_APP_TEXTDOMAIN ),
				text_per_year: __( 'per year', process.env.VUE_APP_TEXTDOMAIN ),
				text_upgrade_now: __( 'Upgrade Now', process.env.VUE_APP_TEXTDOMAIN ),
				text_testimonials: __( 'Testimonials', process.env.VUE_APP_TEXTDOMAIN ),
				wizard_url: this.$mi.wizard_url,
				features: [
					{
						name: __( 'Universal Tracking', process.env.VUE_APP_TEXTDOMAIN ),
						description: __( 'Setup universal website tracking across devices and campaigns with just a few clicks (without any code).', process.env.VUE_APP_TEXTDOMAIN ),
						icon: '<svg class="" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 192 192" enable-background="new 0 0 192 192" xml:space="preserve"><rect fill="none" width="192" height="192"></rect><g><g><path fill="#509FE2" d="M130,29v132c0,14.77,10.189,23,21,23c10,0,21-7,21-23V30c0-13.54-10-22-21-22S130,17.33,130,29z"></path></g><g><path fill="#ACBDC9" d="M75,96v65c0,14.77,10.19,23,21,23c10,0,21-7,21-23V97c0-13.54-10-22-21-22S75,84.33,75,96z"></path></g><g><circle fill="#D6E2EA" cx="41" cy="163" r="21"></circle></g></g></svg>',
					},
					{
						name: __( 'Google Analytics Dashboard', process.env.VUE_APP_TEXTDOMAIN ),
						description: __( 'See your website analytics report right inside your WordPress dashboard with actionable insights.', process.env.VUE_APP_TEXTDOMAIN ),
						icon: '<svg class="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 75 76"><image width="69" height="56" xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEUAAAA4CAMAAACc78UEAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAANlBMVEUAAADU4OrU4OrU4OrU4OrU4OrU4OrU4OrU4OrU4OrU4OrU4OrU4OrU4OrU4OrU4OrU4OoAAACAdzTSAAAAEHRSTlMAEHCvv2CPMECA35/vz1Agb16hXgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAAFiUAABYlAUlSJPAAAAAHdElNRQfjBgcLLAR8mk7DAAAAlklEQVRIx+3XvQ7CMAwE4GuduM0f5P2fFoewICGk2ghl8C23fUpO6VBg2ymYQhFA6OYwDjvSO86/KyHlZxPPLlyvKywrkrSsuTXpQ7peVgQZM+bRNznZ6KRV2kshnbJ/uFHWrDsfV5krV9as++Wu6ylN+ym+rXtCmeSKK6644oorrrjiykqKMUsp958oKHZk/H7FZEzEA/X+Y6vtpvg9AAAAAElFTkSuQmCC"></image>    <image id="Vector_Smart_Object-2" data-name="Vector Smart Object" x="15" y="19" width="40" height="25" xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAZCAMAAAB0BpxXAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAANlBMVEUAAABQn+BQn+BQn+BQn+BQn+BQn+BQn+BQn+BQn+BQn+BQn+BQn+BQn+BQn+BQn+BQn+AAAAD+uSRLAAAAEHRSTlMAEGCAUO/fMCCfz3Cvv0CP0Y9aNgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAAFiUAABYlAUlSJPAAAAAHdElNRQfjBgcLLAR8mk7DAAABC0lEQVQ4y4VS0QKEIAgrTU+tcP//tQd6npAP8dYcMca27aV25717I3EdAVzx88ZLjcfM/YWYgbynApym/QJCThoKiKITuBX46WOC1vNjREOMQPHRNvNQ7nMsYGIEXDyFn9RwpoTKQkETO/vXYUDXl0bVjgFirNdEkc1ionE88RTaXED4eyY69L/M0lBEFlfWmxaMGo+ySVqIbPZN3nsqwwsZcSw8/7+nnNdR77ACJUhVXYQWDXaJacE5iEbgCJKSzctedBTdy1UlSHvWemKLTGonnXUjPPPxy4WNBxNLj8lES/OcTGSafzSio6zyNZh0tCwgS5Cmt+NI5odtxycquyH451HcEiRxnR7AF4SxERp7xl7rAAAAAElFTkSuQmCC"></image>    <image id="wordpress" x="35" y="35" width="39" height="39" xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAACcAAAAnCAMAAAC7faEHAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAACo1BMVEX////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////9/v75+/vu8vTT3OLI09vH09vI1NvN197J1NzZ4Ob19/ji6OzF0dnW3uTy9ff+/v7t8fTR2uHt8fPd5On7/Pzj6O3L1t28ydOzw86ywc24xtHAzdbT3OPs8PP+/v/6+/zQ2uH8/P3r7/LG0tr29/n7/P3V3uSsvcm6yNLk6u3F0dr2+Pnn7O/M1t62xdDBztf4+frE0Nnw8/XH0tuuv8rk6e3E0Nj8/f2tvsqyws34+vvv8/Xq7vHCz9fCz9i3xtC/zNXH0trK1dzS3OLh5+yuvsq7ydPa4ufq7/L5+vvz9ffl6+7O2N/o7fDk6u7P2eDz9ve8ytTp7vHc4+jh5+vM1962xc/Y4OXj6e319/ne5enDz9i1xM/L1d3p7fGzws2vv8v9/f7S2+L3+Pm0w86xwczY4Obx9Pbg5uvm6+/N19+9y9Tm6+7u8fTb4ufU3eOvwMvf5erQ2eDc5OnK1d33+fry9PbZ4eawwMzV3ePf5urX3+Wtvsng5+vb4+jl6u709vjp7fDv8vTe5eoAAABRfI6RAAAAW3RSTlMABRICD0uIq8jEqHc6O4734nkxW5EY6mQBmHYNpHiJaGL1IwfZmms21DX6F3JT0JAOPlkbaSthTxQm1qlsXDAg0jPdCwrcoF0Jam9FX+W4IROXjC0uKEBtf3tmY0JV1QAAAAFiS0dE4CgP/zAAAAAJcEhZcwAAFiUAABYlAUlSJPAAAAAHdElNRQfjBgcLLAR8mk7DAAADY0lEQVQ4y4VU91/TMRANTtziwr33QNx7i3tvPUaRCtaKVMVRSqlwQquIRYoIigNlFFHrFmSIuFDcG+e/4l2+tkBx3A/Ju7v3yUsuyQlRzzwaNBT/sUaNmzT1BIBmzVu0/DurVes2UGNtvdr9kdW+Gbhbh471aV6urH+AC3bydmN17qIkAoNUwZtDVCr1llAl0LUOrVt3GQzbqtmmpLXbw1U7JOpRmydpocERWt3OXbsj9+zdt18fZQiPNnLYq4bWk/0Y04HYuD3otIPxoE8wc6KXk9abPcshf/1hrG2J5iNJTOzTV6H160/OUat2Z3IdGh48BuYUA+UGKLyBBG1JBktqXdrxNIqfCGGtQZI3mJAm3abKOKk6hZnWDLIEE+Jpedy4dK4304YQCDjDsTRLCp5N9+cynsvC8xyy6Uw0Dh1GvOEELmTn0Jgr9fII2RnkE7ho03AZRxDPh+ZgKLhE02VOWwlcYXA+CsBxNTSa/JFC+NKkuwbX42i+welkOqI2ktFNgCArqCnhKcQoLrHjFp5yCd92CmcA3CmEIt6whxjN95p3F1HvFN7nFC6G2BIsLbtH/hgxlsaI7HhEu1O4nN9BMYH7EIaYU/GA/HFiPI0P8x9RXUt/C9tp//CYwBNIQIzVKLwJNFaGPaXwxd/C6c8I0EawKiAZn4OdSzZRTOL9V9myEE2KcHlpcaAivOMFYiYkUHiyEA1B1i+TCpIjhQss+JJCIRgZlUFrlm4mZwrVmb+PPe0V6URI4ZjXGKll4Wtv6GW8fcRlmkq8aVzAd8ZC1iDhcuN7RAsLVzp4L8H8sqYTb4YU1p2jBenucl8eI8BaBbZMLMnVVxGcKd8Vf7XAaDiDyHdnthKvhBYxhCI6tCZebqzkzeIFP+QZE+XdGUq4hh8JbcECKONHM3uO8qBb8wWo9UYV391V5W/wkT6BowJcywkx148co9UCDzUASZL3+RYYskHzgWnzXP9yvmwXZVU2LQRkSV5qNe1UVc3xmQtqPvBC+Rditr6ygTnvS8XtfB28CVLL1uHXqnZDWKS0i6/qIkfM229Hv1ec3vVDRvwW121ES5YqTF22QxP06afeX3GXLXfvaytWQn3zEn8w90Y5dNXqvzTeNQt9XCxP77X/aOTCd936DT03bmrp4Rb/BQ/D/XRsuY9vAAAAAElFTkSuQmCC"></image></svg>',
					},
					{
						name: __( 'Real-time Stats', process.env.VUE_APP_TEXTDOMAIN ),
						description: __( 'Get real-time stats right inside WordPress to see who is online, what are they doing, and more.', process.env.VUE_APP_TEXTDOMAIN ),
						icon: '<svg class="" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="14 14 32 32"><g>\t<path fill="#ACBCC8" d="M31,20.5v-1c0-0.276-0.224-0.5-0.5-0.5S30,19.224,30,19.5v1c0,0.276,0.224,0.5,0.5,0.5S31,20.776,31,20.5z"></path> <path fill="#ACBCC8" d="M30,39.5v1c0,0.276,0.224,0.5,0.5,0.5s0.5-0.224,0.5-0.5v-1c0-0.276-0.224-0.5-0.5-0.5S30,39.224,30,39.5z"></path> <path fill="#ACBCC8" d="M39.5,30c-0.276,0-0.5,0.224-0.5,0.5s0.224,0.5,0.5,0.5h1c0.276,0,0.5-0.224,0.5-0.5S40.776,30,40.5,30 H39.5z"></path> <path fill="#ACBCC8" d="M19.5,30c-0.276,0-0.5,0.224-0.5,0.5s0.224,0.5,0.5,0.5h1c0.276,0,0.5-0.224,0.5-0.5S20.776,30,20.5,30 H19.5z"></path> <path fill="#D6E2EA" d="M41.284,25.958C41.739,27.223,42,28.58,42,30c0,6.617-5.383,12-12,12c-6.617,0-12-5.383-12-12 s5.383-12,12-12c2.993,0,5.727,1.108,7.831,2.927l2.826-2.827C37.824,15.56,34.096,14,30,14c-8.822,0-16,7.178-16,16 s7.178,16,16,16s16-7.178,16-16c0-2.545-0.613-4.944-1.675-7.083L41.284,25.958z"></path> <path fill="#509FE1" d="M24.975,25.146C24.877,25.049,24.749,25,24.621,25s-0.256,0.049-0.354,0.146l-2.121,2.121 c-0.195,0.195-0.195,0.512,0,0.707l6.439,6.439C28.964,34.792,29.466,35,30,35s1.036-0.208,1.414-0.586l14.5-14.5 c0.195-0.195,0.195-0.512,0-0.707l-2.121-2.121c-0.098-0.098-0.226-0.146-0.354-0.146s-0.256,0.049-0.354,0.146L30,30.172 L24.975,25.146z"></path></g></svg>',
					},
					{
						name: __( 'Enhanced Ecommerce Tracking', process.env.VUE_APP_TEXTDOMAIN ),
						description: __( '1-click Google Analytics Enhanced Ecommerce tracking for WooCommerce, Easy Digital Downloads & MemberPress.', process.env.VUE_APP_TEXTDOMAIN ),
						icon: '<svg class="" xmlns="http://www.w3.org/2000/svg" width="96" viewBox="0 0 96 102"><path id="package" fill="#509fe1" d="M76.513,14.5a3.346,3.346,0,0,1,2.479,1.039,3.465,3.465,0,0,1,1.021,2.523V39.431a3.466,3.466,0,0,1-1.021,2.523,3.346,3.346,0,0,1-2.479,1.039h-35a3.346,3.346,0,0,1-2.479-1.039,3.466,3.466,0,0,1-1.021-2.523V18.06a3.466,3.466,0,0,1,1.021-2.523A3.346,3.346,0,0,1,41.513,14.5h4.375a6.68,6.68,0,0,1-.729-2.968,6.337,6.337,0,0,1,1.9-4.6A6.119,6.119,0,0,1,51.575,5a6.571,6.571,0,0,1,3.938,1.261,16.639,16.639,0,0,1,3.573,3.784,16.639,16.639,0,0,1,3.573-3.784A6.571,6.571,0,0,1,66.6,5a6.119,6.119,0,0,1,4.521,1.929,6.336,6.336,0,0,1,1.9,4.6,6.68,6.68,0,0,1-.729,2.968h4.229ZM66.6,8.562a3.2,3.2,0,0,0-1.458.3,5.947,5.947,0,0,0-1.823,1.558,41.215,41.215,0,0,0-2.99,4.081H66.6a2.8,2.8,0,0,0,2.078-.853,2.9,2.9,0,0,0,.839-2.115,2.9,2.9,0,0,0-.839-2.115A2.8,2.8,0,0,0,66.6,8.562ZM48.659,11.53a2.9,2.9,0,0,0,.839,2.115,2.8,2.8,0,0,0,2.078.853h6.271a41.215,41.215,0,0,0-2.99-4.081,5.947,5.947,0,0,0-1.823-1.558,3.2,3.2,0,0,0-1.458-.3,2.8,2.8,0,0,0-2.078.853A2.9,2.9,0,0,0,48.659,11.53Zm4.375,7.717H42.679v9.5H75.346v-9.5H64.992l2.625,2.671a1.288,1.288,0,0,1,0,1.632l-0.875.891a1.232,1.232,0,0,1-1.6,0l-5.1-5.194H57.992l-5.1,5.194a1.232,1.232,0,0,1-1.6,0l-0.875-.891a1.288,1.288,0,0,1,0-1.632Zm-10.354,19H75.346V33.494H42.679v4.749Z"></path><path id="cart" fill="#d5e2ea" d="M35.868,69.2l0.958,5.132H79.791a3.479,3.479,0,0,1,2.875,1.443,3.894,3.894,0,0,1,.8,3.207l-0.958,4.009A8.924,8.924,0,1,1,72.364,97.344a8.753,8.753,0,0,1-2.635-6.415,8.369,8.369,0,0,1,2.715-6.335H38.9a8.369,8.369,0,0,1,2.715,6.335,8.753,8.753,0,0,1-2.636,6.415,8.844,8.844,0,0,1-12.618,0,8.66,8.66,0,0,1-2.635-6.335,8.864,8.864,0,0,1,1.2-4.49A9.428,9.428,0,0,1,28.2,83.151L17.021,28.145H5.84A3.825,3.825,0,0,1,2.007,24.3V21.73A3.825,3.825,0,0,1,5.84,17.881H22.291a3.489,3.489,0,0,1,2.316.882,4.075,4.075,0,0,1,1.358,2.165L27.4,28.145"></path><path id="cart-2" data-name="cart" fill="#acbdc9" d="M27.4,28.145H90.173a3.634,3.634,0,0,1,3.035,1.443,3.486,3.486,0,0,1,.639,3.207L86.34,66.152A3.749,3.749,0,0,1,84.982,68.4a3.7,3.7,0,0,1-2.316.8h-46.8"></path></svg>',
					},
					{
						name: __( 'Page Level Analytics', process.env.VUE_APP_TEXTDOMAIN ),
						description: __( 'Get detailed stats for each post and page, so you can see the most popular posts, pages, and sections of your site.', process.env.VUE_APP_TEXTDOMAIN ),
						icon: '<svg class="" xmlns="http://www.w3.org/2000/svg" width="156" viewBox="0 0 156 156">    <rect fill="#d5e2e9" y="12" width="126" height="144" rx="8" ry="8"></rect>    <rect fill="#acbdc9" x="16" y="132" width="94" height="6" rx="3" ry="3"></rect>    <rect fill="#acbdc9" x="16" y="79" width="26" height="47" rx="3" ry="3"></rect>    <rect fill="#acbdc9" x="48" y="119" width="62" height="6" rx="3" ry="3"></rect>    <rect fill="#acbdc9" x="48" y="106" width="62" height="6" rx="3" ry="3"></rect>    <rect fill="#acbdc9" x="48" y="92" width="62" height="6" rx="3" ry="3"></rect>    <rect fill="#acbdc9" x="48" y="79" width="62" height="6" rx="3" ry="3"></rect>    <rect fill="#acbdc9" x="16" y="56" width="62" height="6" rx="3" ry="3"></rect>    <rect fill="#acbdc9" x="16" y="43" width="62" height="6" rx="3" ry="3"></rect>    <rect fill="#acbdc9" x="16" y="30" width="62" height="6" rx="3" ry="3"></rect> <circle fill="#ffffff" cx="94.5" cy="42.5" r="35.5"></circle> <path fill="#54a0de" d="M155.386,96.172A2.214,2.214,0,0,1,156,97.813a3,3,0,0,1-.616,1.846l-4.716,4.512a2.551,2.551,0,0,1-1.846.82,1.948,1.948,0,0,1-1.641-.82L122.164,79.356a2.729,2.729,0,0,1-.616-1.641V74.844a44.053,44.053,0,0,1-12.919,7.69,42.454,42.454,0,0,1-36.4-2.974A43.1,43.1,0,0,1,56.744,64.077,41.43,41.43,0,0,1,51,42.647a41.43,41.43,0,0,1,5.742-21.431A43.1,43.1,0,0,1,72.227,5.733a42.863,42.863,0,0,1,42.862,0,43.1,43.1,0,0,1,15.483,15.483,41.43,41.43,0,0,1,5.742,21.431,41.258,41.258,0,0,1-2.768,14.971,44.07,44.07,0,0,1-7.691,12.92h2.871a2.217,2.217,0,0,1,1.641.615ZM93.658,75.459a32.1,32.1,0,0,0,16.406-4.409,32.573,32.573,0,0,0,12-12,32.729,32.729,0,0,0,0-32.812,32.573,32.573,0,0,0-12-12,32.728,32.728,0,0,0-32.812,0,32.58,32.58,0,0,0-12,12,32.728,32.728,0,0,0,0,32.813,32.58,32.58,0,0,0,12,12A32.1,32.1,0,0,0,93.658,75.459Z"></path><path fill="#d4e2e8" d="M112.786,52.128H78.724V28.066a0.934,0.934,0,0,0-.937-0.937H74.661a0.934,0.934,0,0,0-.937.938V56.191a0.933,0.933,0,0,0,.938.938h38.125a0.933,0.933,0,0,0,.937-0.937V53.066A0.933,0.933,0,0,0,112.786,52.128Z"></path>    <path fill="#acbdc9" d="M102.864,34.55v0.078a0.717,0.717,0,0,1,.742-0.2,0.826,0.826,0,0,1,.586.508l7.031,14.688h-30V41.5l6.8-11.328a0.888,0.888,0,0,1,.742-0.43,0.9,0.9,0,0,1,.82.352L96.224,39Z"></path></svg>',
					},
					{
						name: __( 'Affiliate Link & Ads Tracking', process.env.VUE_APP_TEXTDOMAIN ),
						description: __( 'Automatically track clicks on your affiliate links, banner ads, and other outbound links with our link tracking.', process.env.VUE_APP_TEXTDOMAIN ),
						icon: '<svg class="" xmlns="http://www.w3.org/2000/svg" width="96" viewBox="0 0 96 102"><rect fill="#8ba4b7" x="41" y="60" width="14" height="42"></rect><path fill="#acbdc9" d="M14,71H82a0,0,0,0,1,0,0v3a2,2,0,0,1-2,2H16a2,2,0,0,1-2-2V71A0,0,0,0,1,14,71Z"></path><rect fill="#509fe2" y="5" width="96" height="66" rx="4.129" ry="4.129"></rect><path fill="#ffffff" d="M40.866,38.665l1.266-3.727L43.4,38.665H40.866ZM54.506,37.54a1.681,1.681,0,1,1-1.2.492A1.627,1.627,0,0,1,54.506,37.54Zm7.875-13.5a3.361,3.361,0,0,1,3.375,3.375v20.25a3.361,3.361,0,0,1-3.375,3.375H33.131a3.361,3.361,0,0,1-3.375-3.375V27.415a3.361,3.361,0,0,1,3.375-3.375h29.25ZM47.4,44.29a1.01,1.01,0,0,0,.879-0.457,1.2,1.2,0,0,0,.176-1.02l-3.8-10.9a1.957,1.957,0,0,0-.633-0.809,1.6,1.6,0,0,0-.984-0.316H41.217a1.6,1.6,0,0,0-.984.316,1.955,1.955,0,0,0-.633.809l-3.8,10.9a1.2,1.2,0,0,0,.176,1.02,1.01,1.01,0,0,0,.879.457h1.2a1.185,1.185,0,0,0,.668-0.211,0.86,0.86,0,0,0,.387-0.562L39.67,42.04h4.922l0.563,1.477a0.86,0.86,0,0,0,.387.563,1.185,1.185,0,0,0,.668.211h1.2Zm12.164-1.125V31.915a1.083,1.083,0,0,0-1.125-1.125H57.319a1.083,1.083,0,0,0-1.125,1.125v2.531a5.309,5.309,0,0,0-1.687-.281,5.063,5.063,0,1,0,0,10.125,4.64,4.64,0,0,0,1.969-.422,1.064,1.064,0,0,0,.844.422h1.125A1.083,1.083,0,0,0,59.569,43.165Z"></path><path fill="#2e7fbe" d="M73,0h1a2,2,0,0,1,2,2v8H71V2A2,2,0,0,1,73,0ZM70.5,10h6a2.5,2.5,0,0,1,0,5h-6A2.5,2.5,0,0,1,70.5,10Z"></path><path fill="#2e7fbe" d="M48,0h1a2,2,0,0,1,2,2v8H46V2A2,2,0,0,1,48,0ZM45.5,10h6a2.5,2.5,0,0,1,0,5h-6A2.5,2.5,0,0,1,45.5,10Z"></path><path fill="#2e7fbe" d="M23,0h1a2,2,0,0,1,2,2v8H21V2A2,2,0,0,1,23,0ZM20.5,10h6a2.5,2.5,0,0,1,0,5h-6A2.5,2.5,0,0,1,20.5,10Z"></path></svg>',
					},
					{
						name: __( 'EU Compliance (GDPR Friendly)', process.env.VUE_APP_TEXTDOMAIN ),
						description: __( 'Make Google Analytics compliant with GDPR and other privacy regulations automatically.', process.env.VUE_APP_TEXTDOMAIN ),
						icon: '<svg class="" xmlns="http://www.w3.org/2000/svg" width="96" viewBox="0 0 96 102"><path fill="#adbdc7" d="M28.884,78.139a0.832,0.832,0,0,0-.479-0.437,0.932,0.932,0,0,0-.629,0,0.832,0.832,0,0,0-.479.438l-1.777,3.609-3.992.574a0.83,0.83,0,0,0-.561.328,0.914,0.914,0,0,0-.191.6,0.839,0.839,0,0,0,.26.574l2.9,2.816-0.684,3.992a0.864,0.864,0,0,0,.123.615,0.8,0.8,0,0,0,.506.369,0.843,0.843,0,0,0,.629-0.082l3.582-1.859,3.582,1.859a0.843,0.843,0,0,0,.629.082,0.8,0.8,0,0,0,.506-0.369,0.863,0.863,0,0,0,.123-0.615l-0.684-3.992,2.9-2.816a0.839,0.839,0,0,0,.26-0.574,0.912,0.912,0,0,0-.191-0.6,0.83,0.83,0,0,0-.56-0.328l-3.992-.574Zm-14-14a0.832,0.832,0,0,0-.479-0.438,0.932,0.932,0,0,0-.629,0,0.832,0.832,0,0,0-.479.438l-1.777,3.609-3.992.574a0.83,0.83,0,0,0-.561.328,0.913,0.913,0,0,0-.191.6,0.839,0.839,0,0,0,.26.574l2.9,2.816L9.251,76.635a0.864,0.864,0,0,0,.123.615,0.8,0.8,0,0,0,.506.369,0.843,0.843,0,0,0,.629-0.082l3.582-1.859,3.582,1.859a0.843,0.843,0,0,0,.629.082,0.8,0.8,0,0,0,.506-0.369,0.863,0.863,0,0,0,.123-0.615l-0.684-3.992,2.9-2.816a0.839,0.839,0,0,0,.26-0.574,0.912,0.912,0,0,0-.191-0.6,0.83,0.83,0,0,0-.561-0.328l-3.992-.574Zm-5-19A0.832,0.832,0,0,0,9.406,44.7a0.932,0.932,0,0,0-.629,0,0.832,0.832,0,0,0-.479.438L6.521,48.749l-3.992.574a0.83,0.83,0,0,0-.561.328,0.913,0.913,0,0,0-.191.6,0.839,0.839,0,0,0,.26.574l2.9,2.817L4.251,57.635a0.864,0.864,0,0,0,.123.615,0.8,0.8,0,0,0,.506.369,0.843,0.843,0,0,0,.629-0.082l3.582-1.859,3.582,1.859a0.843,0.843,0,0,0,.629.082,0.8,0.8,0,0,0,.506-0.369,0.863,0.863,0,0,0,.123-0.615l-0.684-3.992,2.9-2.817a0.839,0.839,0,0,0,.26-0.574,0.912,0.912,0,0,0-.191-0.6,0.83,0.83,0,0,0-.561-0.328l-3.992-.574Zm5-20a0.832,0.832,0,0,0-.479-0.437,0.932,0.932,0,0,0-.629,0,0.832,0.832,0,0,0-.479.438l-1.777,3.609-3.992.574a0.83,0.83,0,0,0-.561.328,0.913,0.913,0,0,0-.191.6,0.839,0.839,0,0,0,.26.574l2.9,2.817L9.251,37.635a0.864,0.864,0,0,0,.123.615,0.8,0.8,0,0,0,.506.369,0.843,0.843,0,0,0,.629-0.082l3.582-1.859,3.582,1.859a0.843,0.843,0,0,0,.629.082,0.8,0.8,0,0,0,.506-0.369,0.863,0.863,0,0,0,.123-0.615l-0.684-3.992,2.9-2.817a0.839,0.839,0,0,0,.26-0.574,0.912,0.912,0,0,0-.191-0.6,0.83,0.83,0,0,0-.561-0.328l-3.992-.574Zm14-15A0.832,0.832,0,0,0,28.406,9.7a0.931,0.931,0,0,0-.629,0,0.832,0.832,0,0,0-.479.437l-1.777,3.609-3.992.574a0.83,0.83,0,0,0-.561.328,0.914,0.914,0,0,0-.191.6,0.839,0.839,0,0,0,.26.574l2.9,2.816-0.684,3.992a0.864,0.864,0,0,0,.123.615,0.8,0.8,0,0,0,.506.369,0.843,0.843,0,0,0,.629-0.082l3.582-1.859,3.582,1.859a0.843,0.843,0,0,0,.629.082,0.8,0.8,0,0,0,.506-0.369,0.863,0.863,0,0,0,.123-0.615l-0.684-3.992,2.9-2.816a0.839,0.839,0,0,0,.26-0.574,0.912,0.912,0,0,0-.191-0.6,0.83,0.83,0,0,0-.56-0.328l-3.992-.574Zm19.232,74a0.832,0.832,0,0,1,.479-0.437,0.932,0.932,0,0,1,.629,0,0.832,0.832,0,0,1,.478.438l1.777,3.609,3.992,0.574a0.83,0.83,0,0,1,.561.328,0.914,0.914,0,0,1,.191.6,0.839,0.839,0,0,1-.26.574l-2.9,2.816,0.684,3.992a0.864,0.864,0,0,1-.123.615,0.8,0.8,0,0,1-.506.369,0.843,0.843,0,0,1-.629-0.082l-3.582-1.859-3.582,1.859a0.843,0.843,0,0,1-.629.082,0.8,0.8,0,0,1-.506-0.369,0.863,0.863,0,0,1-.123-0.615l0.684-3.992-2.9-2.816a0.839,0.839,0,0,1-.26-0.574,0.912,0.912,0,0,1,.191-0.6,0.83,0.83,0,0,1,.56-0.328l3.992-.574Zm20-6a0.832,0.832,0,0,1,.479-0.437,0.932,0.932,0,0,1,.629,0,0.832,0.832,0,0,1,.479.438l1.777,3.609,3.992,0.574a0.83,0.83,0,0,1,.561.328,0.914,0.914,0,0,1,.191.6,0.839,0.839,0,0,1-.26.574l-2.9,2.816,0.684,3.992a0.864,0.864,0,0,1-.123.615,0.8,0.8,0,0,1-.506.369,0.843,0.843,0,0,1-.629-0.082l-3.582-1.859-3.582,1.859a0.843,0.843,0,0,1-.629.082,0.8,0.8,0,0,1-.506-0.369,0.863,0.863,0,0,1-.123-0.615l0.684-3.992-2.9-2.816a0.839,0.839,0,0,1-.26-0.574,0.912,0.912,0,0,1,.191-0.6,0.83,0.83,0,0,1,.56-0.328l3.992-.574Zm14-14a0.832,0.832,0,0,1,.479-0.438,0.932,0.932,0,0,1,.629,0,0.832,0.832,0,0,1,.479.438l1.777,3.609,3.992,0.574a0.83,0.83,0,0,1,.561.328,0.914,0.914,0,0,1,.191.6,0.839,0.839,0,0,1-.26.574l-2.9,2.816,0.684,3.992a0.864,0.864,0,0,1-.123.615,0.8,0.8,0,0,1-.506.369,0.843,0.843,0,0,1-.629-0.082l-3.582-1.859-3.582,1.859a0.843,0.843,0,0,1-.629.082,0.8,0.8,0,0,1-.506-0.369,0.863,0.863,0,0,1-.123-0.615l0.684-3.992-2.9-2.816a0.839,0.839,0,0,1-.26-0.574,0.912,0.912,0,0,1,.191-0.6,0.83,0.83,0,0,1,.561-0.328l3.992-.574Zm5-19a0.832,0.832,0,0,1,.479-0.437,0.932,0.932,0,0,1,.629,0,0.832,0.832,0,0,1,.479.438l1.777,3.609,3.992,0.574a0.83,0.83,0,0,1,.561.328,0.914,0.914,0,0,1,.191.6,0.839,0.839,0,0,1-.26.574l-2.9,2.817,0.684,3.992a0.864,0.864,0,0,1-.123.615,0.8,0.8,0,0,1-.506.369,0.843,0.843,0,0,1-.629-0.082l-3.582-1.859-3.582,1.859a0.843,0.843,0,0,1-.629.082,0.8,0.8,0,0,1-.506-0.369,0.863,0.863,0,0,1-.123-0.615l0.684-3.992-2.9-2.817a0.839,0.839,0,0,1-.26-0.574,0.912,0.912,0,0,1,.191-0.6,0.83,0.83,0,0,1,.561-0.328l3.992-.574Zm-5-20a0.832,0.832,0,0,1,.479-0.437,0.932,0.932,0,0,1,.629,0,0.832,0.832,0,0,1,.479.438l1.777,3.609,3.992,0.574a0.83,0.83,0,0,1,.561.328,0.914,0.914,0,0,1,.191.6,0.839,0.839,0,0,1-.26.574l-2.9,2.817,0.684,3.992a0.864,0.864,0,0,1-.123.615,0.8,0.8,0,0,1-.506.369,0.843,0.843,0,0,1-.629-0.082l-3.582-1.859-3.582,1.859a0.843,0.843,0,0,1-.629.082,0.8,0.8,0,0,1-.506-0.369,0.863,0.863,0,0,1-.123-0.615l0.684-3.992-2.9-2.817a0.839,0.839,0,0,1-.26-0.574,0.912,0.912,0,0,1,.191-0.6,0.83,0.83,0,0,1,.561-0.328l3.992-.574Zm-14-15A0.832,0.832,0,0,1,68.594,9.7a0.931,0.931,0,0,1,.629,0,0.832,0.832,0,0,1,.479.437l1.777,3.609,3.992,0.574a0.83,0.83,0,0,1,.561.328,0.914,0.914,0,0,1,.191.6,0.839,0.839,0,0,1-.26.574l-2.9,2.816,0.684,3.992a0.864,0.864,0,0,1-.123.615,0.8,0.8,0,0,1-.506.369,0.843,0.843,0,0,1-.629-0.082l-3.582-1.859-3.582,1.859a0.843,0.843,0,0,1-.629.082,0.8,0.8,0,0,1-.506-0.369,0.863,0.863,0,0,1-.123-0.615l0.684-3.992-2.9-2.816a0.839,0.839,0,0,1-.26-0.574,0.912,0.912,0,0,1,.191-0.6,0.83,0.83,0,0,1,.56-0.328l3.992-.574Zm-20-6A0.832,0.832,0,0,1,48.594,3.7a0.931,0.931,0,0,1,.629,0,0.832,0.832,0,0,1,.478.438l1.777,3.609,3.992,0.574a0.83,0.83,0,0,1,.561.328,0.914,0.914,0,0,1,.191.6,0.839,0.839,0,0,1-.26.574l-2.9,2.816,0.684,3.992a0.864,0.864,0,0,1-.123.615,0.8,0.8,0,0,1-.506.369,0.843,0.843,0,0,1-.629-0.082l-3.582-1.859-3.582,1.859a0.843,0.843,0,0,1-.629.082,0.8,0.8,0,0,1-.506-0.369,0.863,0.863,0,0,1-.123-0.615l0.684-3.992-2.9-2.816a0.839,0.839,0,0,1-.26-0.574,0.913,0.913,0,0,1,.191-0.6,0.829,0.829,0,0,1,.56-0.328l3.992-.574Z"></path><path fill="#509fe2" d="M65.147,42.845a3.346,3.346,0,0,0-.562-1.9,3.032,3.032,0,0,0-1.547-1.2l-13.5-5.625a2.988,2.988,0,0,0-2.531,0l-13.5,5.625a3.032,3.032,0,0,0-1.547,1.2,3.346,3.346,0,0,0-.562,1.9,32.51,32.51,0,0,0,2.391,12.445,28.183,28.183,0,0,0,5.836,9.07,20.888,20.888,0,0,0,7.383,5.2,2.988,2.988,0,0,0,2.531,0,20.754,20.754,0,0,0,6.75-4.57,29.547,29.547,0,0,0,6.188-8.859A32.516,32.516,0,0,0,65.147,42.845ZM46.8,60.7a1.026,1.026,0,0,1-1.547,0l-7.312-7.312a1.106,1.106,0,0,1,0-1.617l1.547-1.547a1.106,1.106,0,0,1,1.617,0l4.922,4.922L56.569,44.6a1.106,1.106,0,0,1,1.617,0l1.547,1.547a1.106,1.106,0,0,1,0,1.617Z"></path></svg>',
					},
					{
						name: __( 'Custom Dimensions', process.env.VUE_APP_TEXTDOMAIN ),
						description: __( 'Setup tracking for authors, categories, tags, searches, custom post types, users, and other events with 1-click.', process.env.VUE_APP_TEXTDOMAIN ),
						icon: '<svg class="" xmlns="http://www.w3.org/2000/svg" width="96" viewBox="0 0 96 102"><path fill="#509fe2" d="M93.623,52.744A9.542,9.542,0,0,1,91.9,64.718a8.43,8.43,0,0,1-5.919,2.29H47.033a8.569,8.569,0,0,1-5.919-2.225,9.134,9.134,0,0,1-2.991-5.627A9.485,9.485,0,0,1,39.4,52.744l14.894-24.6V8.383H53.27a2.906,2.906,0,0,1-2.164-.916,3.072,3.072,0,0,1-.891-2.225V3.149A3.072,3.072,0,0,1,51.106.924,2.906,2.906,0,0,1,53.27.008H79.748a2.906,2.906,0,0,1,2.164.916A3.072,3.072,0,0,1,82.8,3.149V5.242a3.072,3.072,0,0,1-.891,2.225,2.906,2.906,0,0,1-2.164.916H78.729v19.76ZM55.562,41.883H77.456l-6.11-10.207a3.9,3.9,0,0,1-.764-2.355V8.383H62.436V29.32a3.9,3.9,0,0,1-.764,2.355Z"></path><path fill="#d6e2ea" d="M68,99.334a11.55,11.55,0,0,0,4.246,0l1.061,1.837a1.418,1.418,0,0,0,.863.722,2.006,2.006,0,0,0,1.128.065,14.624,14.624,0,0,0,4.246-2.492,1.388,1.388,0,0,0,.6-0.984,1.94,1.94,0,0,0-.2-1.115l-1.061-1.837A13.05,13.05,0,0,0,81,91.987h2.123a1.686,1.686,0,0,0,1.128-.394,1.307,1.307,0,0,0,.464-1.05,11.23,11.23,0,0,0,0-4.855,1.175,1.175,0,0,0-.464-0.919,1.686,1.686,0,0,0-1.128-.394H81A15.309,15.309,0,0,0,78.879,80.7l1.061-1.837a1.94,1.94,0,0,0,.2-1.115,1.388,1.388,0,0,0-.6-0.984A12.6,12.6,0,0,0,75.3,74.4a1.437,1.437,0,0,0-1.128-.066,1.419,1.419,0,0,0-.863.722l-1.061,1.968A13.838,13.838,0,0,0,68,76.9l-1.062-1.837a1.419,1.419,0,0,0-.862-0.722,1.438,1.438,0,0,0-1.128.066A12.6,12.6,0,0,0,60.7,76.766a1.389,1.389,0,0,0-.6.984,1.941,1.941,0,0,0,.2,1.115L61.363,80.7a15.312,15.312,0,0,0-2.123,3.674H57.117a1.686,1.686,0,0,0-1.128.394,1.759,1.759,0,0,0-.6.919,12.766,12.766,0,0,0,.133,4.855,1.307,1.307,0,0,0,.464,1.05,1.686,1.686,0,0,0,1.128.394H59.24a13.053,13.053,0,0,0,2.123,3.543L60.3,97.366a1.941,1.941,0,0,0-.2,1.115,1.389,1.389,0,0,0,.6.984,14.624,14.624,0,0,0,4.246,2.492,2.007,2.007,0,0,0,1.128-.065,1.419,1.419,0,0,0,.862-0.722Zm-1.327-7.741A5.082,5.082,0,0,1,65.543,86.8a4.6,4.6,0,0,1,3.185-3.215,5.074,5.074,0,0,1,4.843,1.05A5.276,5.276,0,0,1,74.7,89.494a4.591,4.591,0,0,1-3.184,3.149A5.074,5.074,0,0,1,66.671,91.593Z"></path><path fill="#acbdc9" d="M51.279,72.568a21.653,21.653,0,0,0,0-8.266l4.511-2.231a3.077,3.077,0,0,0,1.393-1.706,3.115,3.115,0,0,0-.066-2.1,30.1,30.1,0,0,0-5.573-8.66,3.226,3.226,0,0,0-1.924-1.115,2.645,2.645,0,0,0-2.057.459l-3.848,2.1a25.939,25.939,0,0,0-7.3-4.068V42.521a2.878,2.878,0,0,0-.8-2.034,3.731,3.731,0,0,0-1.858-1.115,30.03,30.03,0,0,0-10.085.131,2.84,2.84,0,0,0-1.924.984,3.026,3.026,0,0,0-.73,2.034v4.461a22.237,22.237,0,0,0-7.3,4.068L9.88,48.95a3.054,3.054,0,0,0-2.189-.459A2.7,2.7,0,0,0,5.9,49.606a32.637,32.637,0,0,0-5.573,8.66,2.5,2.5,0,0,0-.133,2.1,3.408,3.408,0,0,0,1.46,1.706L6.032,64.3a21.654,21.654,0,0,0,0,8.266L1.653,74.8a2.825,2.825,0,0,0-1.46,1.64A2.643,2.643,0,0,0,.326,78.6,32.636,32.636,0,0,0,5.9,87.263a2.909,2.909,0,0,0,1.791.984,3.477,3.477,0,0,0,2.189-.328l3.848-2.23a22.345,22.345,0,0,0,7.3,4.2v4.461a3.107,3.107,0,0,0,.73,1.968,2.744,2.744,0,0,0,1.924,1.05,27.911,27.911,0,0,0,10.085,0,2.889,2.889,0,0,0,1.858-1.05,2.957,2.957,0,0,0,.8-1.968V89.887a24.326,24.326,0,0,0,7.3-4.2l3.848,2.23a3.007,3.007,0,0,0,2.057.328,3.535,3.535,0,0,0,1.924-.984q4.379-5.248,5.573-8.66a2.643,2.643,0,0,0,.133-2.165,2.825,2.825,0,0,0-1.46-1.64ZM35.754,75.323a11.432,11.432,0,0,1-7.7,2.493,8.964,8.964,0,0,1-6.17-2.69,8.772,8.772,0,0,1-2.72-6.1,10.684,10.684,0,0,1,2.521-7.479,10.691,10.691,0,0,1,7.563-2.624,9.649,9.649,0,0,1,9.023,8.922A10.684,10.684,0,0,1,35.754,75.323Z"></path></svg>',
					},
				],
				pro_features: [
					__( 'Ecommerce Report', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Form Conversions', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Custom Dimensions', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Author Tracking', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Google Optimize', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Category / Tags Tracking', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'WooCommerce', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'Easy Digital Downloads', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'MemberPress', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'LifterLMS', process.env.VUE_APP_TEXTDOMAIN ),
				],
				testimonials: [
					{
						image: 'monsterinsights-testimonial-one',
						text: 'It just works. Really easy way to insert Google Analytics tracking code and keep it there when switching themes. No need to copy/paste code anywhere. This is the best way to handle Google Analytics in WordPress.',
						author: 'Steven Gliebe',
						function: 'Founder of ChurchThemes',
					},
					{
						image: 'monsterinsights-testimonial-two',
						text: 'Analytics for PROs! This plugin brings it all, great features and helpful info to easily see what you are doing.',
						author: 'Frank van der Sluijs',
						function: 'Business Consultant',
					},
				],
				welcome_video: false,
			};
		},
		computed: {
			welcome_title() {
				if ( this.$mi.first_name && this.$mi.first_name.length < 28 ) {
					return this.text_welcome_title + ' ' + this.$mi.first_name;
				}
				return this.text_welcome_title;
			},
		},
		methods: {
			feature_class( icon ) {
				return 'monsterinsights-bg-img ' + icon;
			},
		},
	};
</script>
