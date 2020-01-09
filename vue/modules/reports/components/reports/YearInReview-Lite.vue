<template>
	<main :class="['monsterinsights-report-year-in-review', 'monsterinsights-report-row', !yearInReview.is_enabled ? 'monsterinsights-yir-report-calculating-row' : ''] ">
		<div v-if="!yearInReview.is_enabled" class="monsterinsights-yir-top-header monsterinsights-yir-report-calculating">
			<h1 class="monsterinsights-yir-title" v-text="text_calculating"></h1>
			<p class="monsterinsights-yir-summary" v-text="text_year_in_review_still_calculating"></p>
			<router-link class="monsterinsights-navigation-tab-link" to="/" v-text="text_back_to_overview_report"></router-link>
		</div>
		<div v-if="yearInReview.is_enabled">
			<header class="monsterinsights-yir-top-header">
				<span class="monsterinsights-yir-subtitle">
					<svg width="75" height="76" viewBox="0 0 75 76" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
						<path d="M0 75.25H75V0.249999H0V75.25Z" fill="url(#pattern0)" />
						<defs>
							<pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
								<use xlink:href="#image0" transform="scale(0.015625)" />
							</pattern>
							<image id="image0" width="64" height="64" xlink:href="data:image/png;base64,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" />
						</defs>
					</svg>
					{{ text_sub_title }}
				</span>
				<h1 class="monsterinsights-yir-title" v-text="text_title"></h1>
				<p class="monsterinsights-yir-summary" v-text="text_summary"></p>
			</header>
			<section class="monsterinsights-yir-audience">
				<h2 class="monsterinsights-yir-title" v-text="text_audience_section_title"></h2>
				<h3 class="monsterinsights-yir-summary">
					<span v-text="text_congrats"></span><span v-if="yearInReview.user_name!=''" v-text="', ' + yearInReview.user_name"></span>!
					<span v-if="isMoreVisitors" v-text="text_popular"></span>
					<span v-text="text_you_had"></span><span><strong v-text="commaNumbers(yearInReview.info.users.value) + text_visitors"></strong></span><br /><br />
					<span v-html="bestMonthVisitorsSummary"></span>
				</h3>
				<div class="monsterinsights-yir-total-visitors-sessions">
					<div class="monsterinsights-yir-visitors">
						<img src="~@/assets/img/icon-visitors.png" alt="" />
						<h4 v-text="text_total_visitors"></h4>
						<span class="monsterinsights-yir-number" v-text="commaNumbers(yearInReview.info.users.value)"></span>
					</div>
					<div class="monsterinsights-yir-sessions">
						<img src="~@/assets/img/icon-sessions.png" alt="" />
						<h4 v-text="text_total_sessions"></h4>
						<span class="monsterinsights-yir-number" v-text="commaNumbers(yearInReview.info.sessions.value)"></span>
					</div>
				</div>
				<div class="monsterinsights-yir-visitor-by-chart">
					<report-year-in-review-by-month
						id="visitorbymonth"
						:chartData="visitorByMonthData"
						:title="text_visitor_by_month_chart_title"
						:sub-title="text_visitor_by_month_chart_tooltip"
					/>
				</div>
				<report-year-in-review-tip :title="text_audience_tip_title" :summary="text_audience_tip_summary" />
			</section>
			<div class="monsterinsights-yir-separator"></div>
			<section class="monsterinsights-yir-demographics">
				<h2 class="monsterinsights-yir-title" v-text="text_section_demographics_title"></h2>
				<h3 class="monsterinsights-yir-summary" v-html="demoGraphicsSummary"></h3>
				<div class="monsterinsights-yir-countries">
					<div class="monsterinsights-yir-top-country">
						<span class="monsterinsights-yir-number-one" v-text="text_number_one"></span>
						<span :class="['monsterinsights-flag', topCountryFlagClass]"></span>
						<h4 class="monsterinsights-yir-top-country-name" v-text="yearInReview.countries[0].name"></h4>
						<h5 class="monsterinsights-yir-top-country-visitors" v-text="topCountryVisitors"></h5>
					</div>
					<div class="monsterinsights-yir-top-countries-graph">
						<report-year-in-review-list-box :title="text_countries" :sub-title="text_best_month_visitors" :rows="countriesData">
						</report-year-in-review-list-box>
					</div>
				</div>
				<h3 v-if="yearInReview.gender || yearInReview.age" class="monsterinsights-yir-know-visitors" v-text="text_know_your_visitors"></h3>
				<div class="monsterinsights-yir-visitors-info">
					<div v-if="yearInReview.gender" class="monsterinsights-yir-gender-info">
						<span v-text="text_gender"></span><br />
						<h2 v-text="maxVisitorGender"></h2>
						<p v-html="maxVisitorGenderSummary"></p>
					</div>
					<div v-if="yearInReview.age" class="monsterinsights-yir-age-info">
						<span v-text="text_average_age"></span><br />
						<h2 v-text="maxVisitorAverageAge"></h2>
						<p v-html="maxVisitorAgeSummary"></p>
					</div>
				</div>
			</section>
			<div class="monsterinsights-yir-separator"></div>
			<section class="monsterinsights-yir-behavior">
				<h2 class="monsterinsights-yir-title" v-text="text_section_behavior_title"></h2>
				<h3 class="monsterinsights-yir-summary" v-html="behaviourSummary"></h3>
				<div class="monsterinsights-yir-pages-data">
					<div class="monsterinsights-yir-pages-summary">
						<img src="~@/assets/img/icon-watch.png" alt="" /><br />
						<span class="monsterinsights-yir-time-spent" v-text="text_time_spent"></span><br />
						<h4 class="monsterinsights-yir-total-time-spent">
							<span class="monsterinsights-yir-number" v-text="commaNumbers(yearInReview.info.duration.total_minutes)"></span>
							<span class="monsterinsights-yir-type" v-text="text_minutes"></span>
						</h4>
						<h5 class="monsterinsights-yir-each-visitor-spent" v-text="eachVisitorSpentSummary"></h5>
					</div>
					<div class="monsterinsights-yir-top-pages-graph">
						<report-year-in-review-list-box
							:title="text_top_pages_graph_title"
							:sub-title="text_top_pages_graph_subtitle"
							:rows="topPages"
						/>
					</div>
				</div>
				<h3 v-if="yearInReview.gender || yearInReview.age" class="monsterinsights-yir-most-visitors-device" v-html="mostVisitorsDeviceSummary"></h3>
				<div class="monsterinsights-yir-visitors-info">
					<report-year-in-review-pie-chart
						id="devices"
						:chartData="devicesData"
						:title="text_device_type"
						:subtitle="mostVisitorsDevicePercent"
					/>
				</div>
				<div class="monsterinsights-yir-grow-traffic-tip">
					<report-year-in-review-tip
						:title="text_grow_traffic_tip_title"
						:summary="text_grow_traffic_tip_summary"
						:link-text="text_grow_traffic_tip_link_text"
						link="https://www.monsterinsights.com/marketing-hacks-guaranteed-to-grow-your-traffic/"
					/>
				</div>
				<div class="monsterinsights-yir-visitors-come-from">
					<h3 class="monsterinsights-yir-title" v-text="text_visitors_come_from"></h3>
					<div class="monsterinsights-yir-keywords-referrals">
						<div class="monsterinsights-yir-keywords">
							<report-year-in-review-list-box
								:title="text_top_keywords"
								:sub-title="text_clicks"
								:rows="searchConsoleSampleData"
								:tooltip="text_top_keywords_tooltip"
								:class="['monsterinsights-year-in-review-table-box-blur-report']"
							/>
							<year-in-review-up-sell-overlay
								:details="text_search_console_upsell_overlay_details"
								:btn-text="text_search_console_upsell_overlay_btn_text"
								btn-link="https://www.monsterinsights.com/lite/?utm_source=liteplugin&utm_medium=settings-panel&utm_campaign=license&utm_content=7.10.0"
								btn-class="monsterinsights-yir-success"
							/>
						</div>
						<div v-if="referralsData.length" class="monsterinsights-yir-referrals">
							<report-year-in-review-list-box
								:title="text_top_referrals"
								:sub-title="text_pageviews"
								:tooltip="text_top_referrals_tooltip"
								:rows="referralsData"
							/>
						</div>
					</div>
				</div>
				<div class="monsterinsights-yir-grow-traffic-tip">
					<report-year-in-review-tip
						:title="text_opportunity_tip_title"
						:summary="text_opportunity_tip_summary"
						:link-text="text_opportunity_tip_link_text"
						link="https://www.monsterinsights.com/how-to-spy-on-your-competitors-and-ethically-steal-their-traffic/"
					/>
				</div>
			</section>
			<div class="monsterinsights-yir-separator"></div>
			<section class="monsterinsights-yir-thank-you">
				<h2 class="monsterinsights-yir-title" v-text="text_thank_you_section_title"></h2>
				<h3 class="monsterinsights-yir-summary" v-text="text_thank_you_section_summary"></h3>
				<h4 class="monsterinsights-yir-amazing-year" v-text="text_amazing_2020"></h4>
				<div class="monsterinsights-yir-authors">
					<div class="monsterinsights-yir-author">
						<div class="monsterinsights-yir-thumbnail syed"></div>
						<span class="monsterinsights-yir-name" v-text="text_syed_balkhi"></span>
					</div>
					<div class="monsterinsights-yir-author">
						<div class="monsterinsights-yir-thumbnail chris"></div>
						<span class="monsterinsights-yir-name" v-text="text_chris_christoff"></span>
					</div>
				</div>
				<div class="monsterinsights-yir-write-review">
					<div class="monsterinsights-yir-content">
						<span v-text="text_enjoying_monsterinsights"></span><br />
						<h3 v-text="text_leave_review"></h3>
					</div>
					<div class="monsterinsights-yir-rating">
						<ul class="monsterinsights-yir-five-star">
							<li><img src="~@/assets/img/star.png" alt="" /></li>
							<li><img src="~@/assets/img/star.png" alt="" /></li>
							<li><img src="~@/assets/img/star.png" alt="" /></li>
							<li><img src="~@/assets/img/star.png" alt="" /></li>
							<li><img src="~@/assets/img/star.png" alt="" /></li>
						</ul>
					</div>
					<div class="monsterinsights-yir-review-button">
						<a href="https://wordpress.org/support/view/plugin-reviews/google-analytics-for-wordpress?filter=5" target="_blank" v-text="text_write_review"></a>
					</div>
				</div>
			</section>
			<section class="monsterinsights-yir-year-in-review-plugins-block">
				<h2 class="monsterinsights-yir-title" v-text="text_plugins_section_title"></h2>
				<h3 class="monsterinsights-yir-summary" v-text="text_plugins_section_summary"></h3>
				<div class="monsterinsights-yir-plugins">
					<addon-block v-for="(addon,index) in pluginsList()" :key="index" :addon="addon" :is-addon="false" />
				</div>
			</section>
			<section class="monsterinsights-yir-join-communities">
				<h2 class="monsterinsights-yir-title" v-text="text_communities_section_title"></h2>
				<h3 class="monsterinsights-yir-summary" v-text="text_communities_section_summary"></h3>

				<div class="monsterinsights-yir-communities">
					<div class="monsterinsights-yir-community">
						<img src="~@/assets/img/icon-chat.png" class="monsterinsights-yir-thumbnail" alt="" />
						<h3 class="monsterinsights-yir-title" v-text="text_facebook_group"></h3>
						<p class="monsterinsights-yir-details" v-text="text_facebook_group_summary"></p>
						<a href="https://www.facebook.com/groups/wpbeginner/" class="monsterinsights-yir-link" target="_blank" v-text="text_facebook_join_button"></a>
					</div>
					<div class="monsterinsights-yir-community">
						<img class="monsterinsights-yir-thumbnail" src="~@/assets/img/icon-pen.png" alt="" />
						<h3 class="monsterinsights-yir-title" v-text="text_wpbeginner_community_title"></h3>
						<p class="monsterinsights-yir-details" v-text="text_wpbeginner_community_summary"></p>
						<a href="https://www.wpbeginner.com/" class="monsterinsights-yir-link" target="_blank" v-text="text_visit_wpbeginner"></a>
					</div>
					<div class="monsterinsights-yir-community">
						<img class="monsterinsights-yir-thumbnail" src="~@/assets/img/icon-global.png" alt="" />
						<h3 class="monsterinsights-yir-title" v-text="text_follow_us"></h3>
						<p class="monsterinsights-yir-details" v-text="text_follow_us_summary"></p>
						<ul class="monsterinsights-yir-social-links">
							<li><a href="https://www.youtube.com/channel/UCnB-GV6lyQYgBLLhQr-kuVw" target="_blank"><img src="~@/assets/img/icon-youtube-small.png" alt="" /></a></li>
							<li><a href="https://www.facebook.com/monsterinsights" target="_blank"><img src="~@/assets/img/icon-fb-small.png" alt="" /></a></li>
							<li><a href="https://twitter.com/monsterinsights" target="_blank"><img src="~@/assets/img/icon-twitter-small.png" alt="" /></a></li>
						</ul>
					</div>
				</div>
			</section>
			<footer class="monsterinsights-yir-footer">
				<div v-text="text_copyright_monsterinsights"></div>
				<div class="monsterinsights-yir-text-right">
					<svg width="134" height="18" viewBox="0 0 134 18" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M20.201 10.1024C20.201 10.6528 20.0909 11.0932 19.8432 11.3959C19.5955 11.6987 19.2102 11.8363 18.6873 11.8363C18.1644 11.8363 17.7791 11.6711 17.5589 11.3959C17.3112 11.1207 17.2011 10.6528 17.2011 10.1024V8.12084C17.2011 7.5704 17.3112 7.13005 17.5589 6.82731C17.8066 6.55209 18.1919 6.38697 18.6873 6.38697C19.2102 6.38697 19.5955 6.55209 19.8432 6.82731C20.0909 7.10253 20.201 7.5704 20.201 8.12084V10.1024ZM22.733 10.1024V8.12084C22.733 7.5704 22.6504 7.07501 22.4853 6.57961C22.3202 6.08422 22.1 5.67139 21.7422 5.31361C21.4119 4.95583 20.9716 4.65309 20.4762 4.46044C19.9808 4.24027 19.3753 4.13018 18.6598 4.13018C17.9717 4.13018 17.3662 4.24027 16.8433 4.46044C16.3479 4.68062 15.9076 4.95583 15.5773 5.31361C15.2471 5.67139 14.9994 6.11174 14.8342 6.57961C14.6691 7.07501 14.5865 7.5704 14.5865 8.12084V10.1024C14.5865 10.6528 14.6691 11.1757 14.8342 11.6436C14.9994 12.139 15.2195 12.5518 15.5773 12.9096C15.9076 13.2674 16.3479 13.5701 16.8433 13.7628C17.3387 13.983 17.9442 14.0931 18.6598 14.0931C19.3478 14.0931 19.9533 13.983 20.4762 13.7628C20.9716 13.5426 21.4119 13.2674 21.7422 12.9096C22.0725 12.5518 22.3202 12.1115 22.4853 11.6436C22.6504 11.1757 22.733 10.6528 22.733 10.1024ZM33.2188 12.8546V7.65296C33.2188 7.13005 33.1637 6.63466 33.0537 6.22183C32.9436 5.78149 32.7784 5.39618 32.5307 5.09344C32.2831 4.7907 31.9528 4.543 31.5675 4.37787C31.1822 4.18522 30.6593 4.13018 30.0538 4.13018C29.4483 4.13018 28.8979 4.21274 28.43 4.37787C27.9621 4.57052 27.5768 4.7907 27.219 5.09344C27.109 4.90079 26.9438 4.73565 26.7787 4.62557C26.586 4.48796 26.4209 4.37788 26.2007 4.32283C25.9806 4.26779 25.7879 4.24026 25.5953 4.24026C25.2375 4.24026 24.9347 4.32283 24.687 4.48796C24.4393 4.65309 24.3293 4.90079 24.3293 5.2861C24.3293 5.47875 24.3568 5.61635 24.4118 5.72644C24.4669 5.83652 24.577 5.94661 24.6595 6.00166C24.8246 6.13926 24.9347 6.27688 25.0448 6.38697C25.1549 6.49705 25.2099 6.74475 25.2099 7.07501V12.8546C25.2099 13.2124 25.32 13.5151 25.5677 13.7628C25.8154 14.0105 26.1182 14.1206 26.476 14.1206C26.8337 14.1206 27.1365 14.0105 27.3842 13.7628C27.6319 13.5151 27.742 13.2124 27.742 12.8546V7.26766C27.9346 7.01996 28.1273 6.85483 28.4025 6.71722C28.6502 6.57961 28.9529 6.49705 29.3107 6.49705C29.5859 6.49705 29.8061 6.55209 29.9987 6.60714C30.1914 6.6897 30.3015 6.79979 30.4116 6.9374C30.4941 7.07501 30.5767 7.21262 30.6042 7.40527C30.6317 7.59792 30.6593 7.76305 30.6593 7.9557V12.8546C30.6593 13.2124 30.7694 13.5151 31.017 13.7628C31.2647 14.0105 31.5675 14.1206 31.9253 14.1206C32.283 14.1206 32.5858 14.0105 32.8335 13.7628C33.0812 13.5151 33.2188 13.2124 33.2188 12.8546ZM42.4661 11.0106C42.4661 10.4877 42.3835 10.0749 42.2459 9.74462C42.0808 9.41436 41.8881 9.13914 41.6404 8.91897C41.3928 8.69879 41.1175 8.53366 40.8148 8.42358C40.5121 8.31349 40.1818 8.2034 39.8791 8.12084C39.5488 8.06579 39.2736 8.01075 38.9984 7.9557C38.7231 7.90066 38.4754 7.84562 38.2553 7.76306C38.0351 7.68049 37.87 7.59793 37.7599 7.48784C37.6498 7.37775 37.5672 7.21262 37.5672 6.99244C37.5672 6.77227 37.6773 6.57961 37.87 6.41448C38.0626 6.24935 38.3929 6.19431 38.8332 6.19431C39.1635 6.19431 39.4662 6.22183 39.7414 6.27687C40.0167 6.33192 40.3469 6.442 40.7047 6.55209C40.9799 6.63466 41.2551 6.60714 41.5028 6.46953C41.7505 6.33192 41.9157 6.11175 41.9982 5.83653C42.1083 5.56131 42.0808 5.28609 41.9432 5.03839C41.8056 4.7907 41.5854 4.59805 41.3102 4.51548C40.9524 4.40539 40.5671 4.29531 40.1818 4.21274C39.7965 4.13017 39.2736 4.10265 38.6681 4.10265C38.2002 4.10265 37.7324 4.1577 37.3195 4.29531C36.9067 4.43292 36.5214 4.62557 36.1911 4.87327C35.8609 5.12097 35.6132 5.45123 35.4205 5.83653C35.2279 6.22184 35.1453 6.66218 35.1453 7.15757C35.1453 7.62544 35.2004 8.01075 35.338 8.34101C35.4481 8.67127 35.6132 8.91897 35.8334 9.11162C36.0535 9.3318 36.2737 9.4694 36.5489 9.60701C36.8241 9.7171 37.0994 9.82718 37.3746 9.88223C37.7048 9.96479 38.0076 10.0474 38.3378 10.1024C38.6681 10.1575 38.9433 10.2125 39.191 10.2951C39.4387 10.3776 39.6314 10.4877 39.7965 10.5978C39.9616 10.7354 40.0167 10.9281 40.0167 11.1482C40.0167 11.4234 39.8791 11.6711 39.6038 11.8363C39.3286 12.0014 38.9708 12.084 38.4754 12.084C37.9801 12.084 37.5947 12.0565 37.3195 11.9739C37.0443 11.8913 36.7691 11.8088 36.4939 11.7262C36.2462 11.6161 35.971 11.6436 35.6957 11.7537C35.4205 11.8638 35.2279 12.084 35.1453 12.4142C35.0627 12.6619 35.0903 12.9371 35.2004 13.2124C35.3104 13.4876 35.5031 13.6802 35.7783 13.7628C36.1086 13.8729 36.4664 13.983 36.8792 14.038C37.292 14.1206 37.8149 14.1481 38.503 14.1481C38.9984 14.1481 39.4938 14.0931 39.9891 13.983C40.4845 13.8729 40.8974 13.7078 41.2551 13.4325C41.6129 13.1848 41.9157 12.8546 42.1358 12.4693C42.356 12.084 42.4661 11.6161 42.4661 11.0381V11.0106ZM50.2272 12.8821C50.2272 12.5518 50.1172 12.3041 49.897 12.084C49.6768 11.8638 49.4291 11.7537 49.0989 11.7537H48.7411C48.1356 11.7537 47.7503 11.5886 47.5301 11.2583C47.3375 10.9281 47.2274 10.3501 47.2274 9.60701V6.3044H48.6585C48.9337 6.3044 49.1814 6.19431 49.4016 6.00166C49.5942 5.78148 49.7043 5.53379 49.7043 5.25857C49.7043 4.95583 49.5942 4.73566 49.4016 4.51548C49.2089 4.32283 48.9612 4.21274 48.6585 4.21274H47.2274V2.94674C47.2274 2.58896 47.1173 2.28622 46.8696 2.03852C46.6219 1.79082 46.3192 1.65322 45.9614 1.65322C45.6036 1.65322 45.3008 1.7633 45.0532 2.03852C44.8055 2.28622 44.6678 2.58896 44.6678 2.94674V4.21274H44.3651C44.0899 4.21274 43.8422 4.32283 43.622 4.51548C43.4294 4.70813 43.3193 4.95583 43.3193 5.25857C43.3193 5.53379 43.4294 5.78148 43.622 6.00166C43.8147 6.22183 44.0624 6.3044 44.3651 6.3044H44.6678V9.63453C44.6678 10.2951 44.7229 10.873 44.8605 11.4234C44.9981 11.9739 45.2183 12.4142 45.4935 12.7995C45.7962 13.1848 46.154 13.4876 46.6219 13.6802C47.0898 13.9004 47.6677 13.983 48.3282 13.983H49.0438C49.3741 13.983 49.6218 13.8729 49.8419 13.6527C50.1172 13.4601 50.1997 13.1848 50.2272 12.8821ZM56.805 7.76306V8.01075H53.8876V7.76306C53.8876 7.21262 53.9977 6.79979 54.2454 6.52458C54.4931 6.24936 54.8784 6.08423 55.3738 6.08423C55.8692 6.08423 56.227 6.24936 56.4747 6.52458C56.6949 6.79979 56.805 7.21262 56.805 7.76306ZM59.2544 9.00154V8.2034C59.2544 7.65296 59.1718 7.13005 59.0342 6.66218C58.8691 6.16679 58.6764 5.72644 58.3462 5.36866C58.0434 4.98335 57.6306 4.70814 57.1352 4.46044C56.6398 4.24027 56.0343 4.13018 55.3188 4.13018C54.6307 4.13018 54.0253 4.24027 53.5299 4.46044C53.0345 4.68062 52.6216 4.98335 52.2914 5.36866C51.9611 5.75396 51.7409 6.19431 51.5483 6.6897C51.3832 7.18509 51.3006 7.73553 51.3006 8.25844V10.0198C51.3006 10.5703 51.3832 11.0932 51.5208 11.5886C51.6584 12.084 51.8786 12.5243 52.2088 12.8821C52.5391 13.2674 52.9519 13.5702 53.4473 13.7903C53.9427 14.0105 54.5757 14.1206 55.2913 14.1206C55.7591 14.1206 56.227 14.0655 56.7224 14.0105C57.2178 13.9279 57.7132 13.7628 58.2636 13.5151C58.5113 13.405 58.6764 13.2674 58.759 13.0197C58.8691 12.7995 58.8691 12.5518 58.7865 12.2491C58.704 11.9739 58.5388 11.7812 58.2911 11.6436C58.0434 11.5335 57.7957 11.506 57.548 11.6161C57.2178 11.7537 56.915 11.8638 56.5848 11.9188C56.282 12.0014 55.8967 12.0289 55.4289 12.0289C54.9059 12.0289 54.4931 11.8638 54.2454 11.5611C53.9977 11.2308 53.8601 10.7905 53.8601 10.24V9.88223H58.3462C58.5939 9.88223 58.7865 9.79966 58.9517 9.63453C59.1718 9.4694 59.2544 9.24923 59.2544 9.00154ZM67.9513 5.78148C68.0338 5.53379 68.0338 5.2861 67.9788 5.01088C67.8962 4.76318 67.7586 4.543 67.5109 4.4054C67.3733 4.32283 67.2082 4.24027 67.0155 4.18522C66.8229 4.13018 66.6027 4.10265 66.355 4.10265C66.1899 4.10265 66.0247 4.13018 65.8046 4.13018C65.5844 4.1577 65.3642 4.21274 65.1441 4.29531C64.9239 4.37788 64.6762 4.48796 64.4285 4.65309C64.1808 4.81822 63.9606 5.01088 63.7404 5.2861C63.6304 5.0384 63.4377 4.81822 63.2175 4.65309C62.9974 4.46044 62.7221 4.32283 62.3643 4.24026C62.3093 4.21274 62.2267 4.21274 62.1442 4.21274H61.9515C61.5662 4.21274 61.2635 4.29531 61.0158 4.46044C60.7956 4.62557 60.6855 4.87327 60.6855 5.25857C60.6855 5.45122 60.713 5.58884 60.7681 5.69892C60.8231 5.80901 60.9332 5.9191 61.0158 5.97414C61.1809 6.11175 61.3185 6.24936 61.4011 6.35944C61.5112 6.46953 61.5662 6.71723 61.5662 7.04749V12.8271C61.5662 13.1848 61.6763 13.4876 61.924 13.7353C62.1717 13.983 62.4744 14.0931 62.8322 14.0931C63.19 14.0931 63.4927 13.983 63.7404 13.7353C63.9881 13.4876 64.0982 13.1848 64.0982 12.8271V7.5704C64.2083 7.1851 64.4285 6.88235 64.7037 6.71722C64.9789 6.55209 65.3367 6.46953 65.6945 6.46953C65.9422 6.46953 66.1073 6.46953 66.2174 6.49705C66.3 6.52457 66.4101 6.52457 66.5201 6.55209C66.8779 6.63466 67.1807 6.57962 67.4284 6.38697C67.7311 6.27688 67.8687 6.02918 67.9513 5.78148ZM12.2197 1.32295C12.2197 0.992691 12.1096 0.71748 11.8619 0.469784C11.6417 0.222088 11.339 0.111996 10.9812 0.111996C10.7335 0.111996 10.5133 0.16704 10.2931 0.249605C10.073 0.33217 9.90784 0.524823 9.77023 0.800041L6.08231 8.28597L2.47696 0.882611C2.33935 0.607393 2.17422 0.414737 1.98157 0.277128C1.78892 0.167041 1.54122 0.0844727 1.21096 0.0844727C0.880697 0.0844727 0.577957 0.194563 0.357783 0.414737C0.110087 0.634912 0 0.937653 0 1.26791V12.9647C0 13.2949 0.110087 13.5702 0.357783 13.7903C0.577957 14.0105 0.880697 14.1481 1.18344 14.1481C1.5137 14.1481 1.78892 14.038 2.00909 13.7903C2.22926 13.5702 2.36687 13.2674 2.36687 12.9647V5.78148L4.9264 11.1207C5.03649 11.3684 5.22914 11.5611 5.42179 11.6711C5.64196 11.7812 5.83462 11.8638 6.08231 11.8638C6.33001 11.8638 6.55018 11.8088 6.77036 11.6711C6.96301 11.5335 7.12814 11.3409 7.26575 11.0932L9.82528 5.75396V12.9096C9.82528 13.2399 9.93536 13.5151 10.1831 13.7628C10.4032 14.0105 10.706 14.1206 11.0362 14.1206C11.3665 14.1206 11.6417 14.0105 11.8619 13.7628C12.0821 13.5151 12.2197 13.2399 12.2197 12.9096V1.32295Z" fill="white" />
						<path d="M83.0897 12.8527V7.65105C83.0897 7.12814 83.0347 6.63275 82.9246 6.21992C82.8145 5.77957 82.6494 5.39427 82.4017 5.09153C82.154 4.78879 81.8237 4.54109 81.4384 4.34844C81.0531 4.15578 80.5302 4.10074 79.9247 4.10074C79.3192 4.10074 78.7688 4.18331 78.3009 4.34844C77.8331 4.54109 77.4478 4.76127 77.09 5.09153C76.9799 4.89888 76.8148 4.73374 76.6496 4.62365C76.457 4.48605 76.2918 4.37597 76.0717 4.32092C75.8515 4.26588 75.6588 4.23835 75.4662 4.23835C75.1084 4.23835 74.8057 4.32092 74.558 4.48605C74.3103 4.65118 74.2002 4.89888 74.2002 5.28418C74.2002 5.47684 74.2277 5.61444 74.2828 5.72453C74.3378 5.83461 74.4479 5.9447 74.5304 6.02727C74.6956 6.16488 74.8057 6.30248 74.9158 6.41257C75.0258 6.52266 75.0809 6.77035 75.0809 7.10061V12.8802C75.0809 13.238 75.191 13.5407 75.4387 13.7884C75.6864 14.0361 75.9891 14.1462 76.3469 14.1462C76.7047 14.1462 77.0074 14.0361 77.2551 13.7884C77.5028 13.5407 77.6129 13.238 77.6129 12.8802V7.29327C77.8055 7.04557 77.9982 6.88044 78.2734 6.74283C78.5211 6.60523 78.8238 6.52266 79.1816 6.52266C79.4568 6.52266 79.677 6.57771 79.8697 6.63275C80.0623 6.71531 80.1724 6.8254 80.2825 6.96301C80.3651 7.10061 80.4476 7.23823 80.4752 7.43088C80.5027 7.62353 80.5302 7.78866 80.5302 7.98131V12.8802C80.5302 13.238 80.6403 13.5407 80.888 13.7884C81.1357 14.0361 81.4384 14.1462 81.7962 14.1462C82.154 14.1462 82.4567 14.0361 82.7044 13.7884C82.9796 13.5132 83.0897 13.2105 83.0897 12.8527ZM92.337 11.0362C92.337 10.5133 92.2545 10.1005 92.1169 9.77023C91.9793 9.43997 91.7591 9.16475 91.5114 8.94458C91.2637 8.7244 90.9885 8.55927 90.6857 8.44919C90.383 8.3391 90.0527 8.22901 89.75 8.17397C89.4197 8.11893 89.1445 8.06388 88.8693 8.00884C88.5941 7.95379 88.3464 7.89875 88.1262 7.81618C87.906 7.73362 87.7409 7.65105 87.6308 7.54096C87.5207 7.43088 87.4382 7.26575 87.4382 7.04558C87.4382 6.8254 87.5482 6.63275 87.7409 6.46762C87.9336 6.30249 88.2638 6.24745 88.7042 6.24745C89.0344 6.24745 89.3372 6.27496 89.6124 6.33001C89.8876 6.38505 90.2179 6.49514 90.5756 6.60523C90.8509 6.68779 91.1261 6.66027 91.3738 6.52266C91.6215 6.38506 91.7866 6.16488 91.8692 5.88966C91.9793 5.61444 91.9517 5.33922 91.8141 5.09153C91.6765 4.84383 91.4563 4.65118 91.1811 4.56862C90.8233 4.45853 90.438 4.34844 90.0527 4.26587C89.6674 4.18331 89.1445 4.15579 88.539 4.15579C88.0712 4.15579 87.6033 4.21083 87.1905 4.34844C86.7776 4.48605 86.3923 4.6787 86.0621 4.9264C85.7318 5.17409 85.4841 5.50435 85.2915 5.88966C85.0988 6.27496 85.0162 6.71532 85.0162 7.21071C85.0162 7.67858 85.0713 8.06388 85.2089 8.39414C85.319 8.7244 85.4841 8.9721 85.7043 9.16475C85.9245 9.38492 86.1446 9.52254 86.4199 9.66014C86.6951 9.77023 86.9703 9.88032 87.2455 9.93536C87.5758 10.0179 87.8785 10.1005 88.2088 10.1555C88.539 10.2106 88.8142 10.2656 89.0619 10.3482C89.3096 10.4308 89.5023 10.5408 89.6674 10.6509C89.8326 10.7885 89.8876 10.9812 89.8876 11.2014C89.8876 11.4766 89.75 11.7243 89.4748 11.8894C89.1996 12.0545 88.8418 12.1371 88.3464 12.1371C87.851 12.1371 87.4657 12.1096 87.1905 12.027C86.9152 11.9445 86.64 11.8619 86.3648 11.7793C86.1171 11.6692 85.8419 11.6968 85.5667 11.8068C85.2915 11.9169 85.0988 12.1371 85.0162 12.4674C84.9337 12.7151 84.9612 12.9903 85.0713 13.2655C85.1814 13.5407 85.374 13.7334 85.6492 13.8159C85.9795 13.926 86.3373 14.0361 86.7501 14.1187C87.1629 14.2012 87.6859 14.2288 88.3739 14.2288C88.8693 14.2288 89.3647 14.1737 89.8601 14.0636C90.3555 13.9535 90.7683 13.7884 91.1261 13.5132C91.4839 13.2655 91.7866 12.9352 92.0068 12.5499C92.2269 12.1646 92.337 11.6968 92.337 11.1188V11.0362ZM96.8506 12.8527V5.36675C96.8506 5.00896 96.7405 4.70623 96.4928 4.45853C96.2451 4.21083 95.9424 4.07322 95.5846 4.07322C95.2268 4.07322 94.9241 4.18331 94.6764 4.45853C94.4287 4.70623 94.3186 5.00896 94.3186 5.36675V12.8527C94.3186 13.2105 94.4287 13.5132 94.6764 13.7609C94.9241 14.0086 95.2268 14.1187 95.5846 14.1187C95.9424 14.1187 96.2451 14.0086 96.4928 13.7609C96.713 13.5132 96.8506 13.2105 96.8506 12.8527ZM96.9882 1.40361C96.9882 1.01831 96.8506 0.660523 96.5754 0.412827C96.3002 0.137609 95.9699 0 95.5846 0C95.1993 0 94.8415 0.137609 94.5663 0.412827C94.2911 0.688044 94.1535 1.01831 94.1535 1.40361C94.1535 1.78892 94.2911 2.1467 94.5663 2.3944C94.8415 2.66962 95.1718 2.80722 95.5846 2.80722C95.9699 2.80722 96.3277 2.66962 96.5754 2.3944C96.8506 2.1467 96.9882 1.78892 96.9882 1.40361ZM104.529 6.88044V10.7335C104.309 10.9261 104.061 11.0362 103.786 11.1188C103.511 11.2014 103.263 11.2564 103.043 11.2564C102.493 11.2564 102.107 11.0913 101.915 10.8161C101.694 10.5408 101.612 10.1005 101.612 9.55006V8.03636C101.612 6.8254 102.107 6.24745 103.043 6.24745C103.291 6.24745 103.538 6.30248 103.786 6.41257C104.061 6.52266 104.281 6.68779 104.529 6.88044ZM107.061 5.39427C107.061 5.03649 106.951 4.73374 106.731 4.48605C106.511 4.23835 106.18 4.10074 105.795 4.10074C105.547 4.10074 105.3 4.18331 105.107 4.32092C104.887 4.48605 104.749 4.65118 104.639 4.87136C104.337 4.62366 104.034 4.43101 103.676 4.2934C103.318 4.15579 102.933 4.07322 102.548 4.07322C101.915 4.07322 101.392 4.15578 100.951 4.34844C100.511 4.54109 100.153 4.81631 99.878 5.20161C99.6028 5.5594 99.4101 6.02727 99.2725 6.55018C99.1624 7.07309 99.0799 7.67857 99.0799 8.36662V9.08219C99.0799 9.77023 99.1349 10.3757 99.245 10.9261C99.3551 11.4491 99.5477 11.9169 99.7954 12.2747C100.043 12.6325 100.401 12.9077 100.841 13.1004C101.282 13.293 101.805 13.3756 102.465 13.3756C102.823 13.3756 103.153 13.3205 103.538 13.2105C103.896 13.1004 104.226 12.9628 104.502 12.7701V13.2655C104.502 14.6416 103.896 15.3296 102.685 15.3296C102.355 15.3296 102.08 15.3021 101.887 15.2746C101.667 15.2471 101.502 15.192 101.364 15.1645C101.199 15.1095 101.089 15.0819 100.951 15.0544C100.841 15.0269 100.704 14.9994 100.566 14.9718C100.291 14.9443 100.043 15.0544 99.823 15.2746C99.6028 15.4948 99.5202 15.7425 99.5202 15.9902C99.5202 16.2378 99.6028 16.4305 99.7404 16.5956C99.878 16.7608 100.071 16.8709 100.263 16.9809C100.649 17.1461 101.061 17.2562 101.529 17.3112C101.997 17.3662 102.465 17.3938 102.905 17.3938C104.309 17.3938 105.355 17.0085 106.043 16.2378C106.731 15.4672 107.089 14.4214 107.089 13.1004L107.061 5.39427ZM117.519 12.8527V7.65105C117.519 7.12814 117.464 6.63275 117.354 6.21992C117.244 5.77957 117.079 5.39427 116.831 5.09153C116.584 4.78879 116.253 4.54109 115.868 4.34844C115.455 4.15578 114.96 4.10074 114.354 4.10074C113.887 4.10074 113.446 4.15579 113.088 4.26587C112.703 4.37596 112.373 4.51357 112.098 4.73375V1.65131C112.098 1.29352 111.988 0.990787 111.74 0.743091C111.492 0.495395 111.189 0.385303 110.832 0.385303C110.474 0.385303 110.171 0.495395 109.923 0.743091C109.676 0.990787 109.566 1.29352 109.566 1.65131V12.8802C109.566 13.238 109.676 13.5407 109.923 13.7884C110.171 14.0361 110.474 14.1462 110.832 14.1462C111.189 14.1462 111.492 14.0361 111.74 13.7884C111.988 13.5407 112.098 13.238 112.098 12.8802V7.29327C112.29 7.04557 112.483 6.88044 112.758 6.74283C113.006 6.60523 113.336 6.52266 113.694 6.52266C113.969 6.52266 114.189 6.57771 114.382 6.63275C114.575 6.71531 114.685 6.8254 114.795 6.96301C114.877 7.10061 114.96 7.23823 114.987 7.43088C115.015 7.62353 115.043 7.78866 115.043 7.98131V12.8802C115.043 13.238 115.153 13.5407 115.4 13.7884C115.648 14.0361 115.951 14.1462 116.309 14.1462C116.666 14.1462 116.969 14.0361 117.217 13.7884C117.409 13.5407 117.519 13.238 117.519 12.8527ZM125.804 12.8802C125.804 12.5499 125.693 12.3022 125.473 12.0821C125.253 11.8619 125.005 11.7518 124.675 11.7518H124.317C123.712 11.7518 123.327 11.5867 123.106 11.2564C122.914 10.9261 122.804 10.3482 122.804 9.6051V6.30248H124.235C124.51 6.30248 124.758 6.1924 124.978 5.99974C125.171 5.77957 125.281 5.53188 125.281 5.25666C125.281 4.98144 125.171 4.73374 124.978 4.51357C124.785 4.32092 124.538 4.21083 124.235 4.21083H122.831V2.94483C122.831 2.58705 122.721 2.28431 122.473 2.03661C122.226 1.78891 121.923 1.65131 121.565 1.65131C121.207 1.65131 120.905 1.76139 120.657 2.03661C120.409 2.28431 120.272 2.58705 120.272 2.94483V4.21083H119.969C119.694 4.21083 119.446 4.32092 119.226 4.51357C119.033 4.70622 118.923 4.95392 118.923 5.25666C118.923 5.53188 119.033 5.77957 119.226 5.99974C119.418 6.21992 119.666 6.30248 119.969 6.30248H120.272V9.63262C120.272 10.2931 120.327 10.8711 120.464 11.4215C120.602 11.972 120.822 12.4123 121.097 12.7976C121.373 13.1829 121.758 13.4857 122.226 13.6783C122.694 13.8985 123.272 13.9811 123.932 13.9811H124.648C124.978 13.9811 125.226 13.871 125.446 13.6508C125.693 13.4581 125.776 13.1829 125.804 12.8802ZM133.977 11.0087C133.977 10.4858 133.895 10.073 133.757 9.74271C133.592 9.41245 133.4 9.13723 133.152 8.91705C132.904 8.69688 132.629 8.53175 132.326 8.42166C132.023 8.31158 131.693 8.20149 131.39 8.14645C131.06 8.0914 130.785 8.03636 130.51 7.98131C130.235 7.92627 129.987 7.87122 129.767 7.78866C129.546 7.70609 129.381 7.62353 129.271 7.48593C129.161 7.37584 129.079 7.2107 129.079 6.99053C129.079 6.77035 129.189 6.5777 129.381 6.41257C129.574 6.24744 129.904 6.1924 130.345 6.1924C130.675 6.1924 130.978 6.21992 131.253 6.27496C131.528 6.33 131.858 6.44009 132.216 6.55018C132.491 6.63274 132.767 6.60523 133.014 6.46762C133.262 6.33001 133.427 6.10984 133.51 5.83462C133.62 5.5594 133.592 5.28418 133.455 5.03648C133.317 4.78879 133.097 4.59613 132.822 4.51357C132.464 4.40348 132.078 4.29339 131.693 4.21083C131.308 4.12826 130.785 4.10074 130.179 4.10074C129.712 4.10074 129.244 4.15579 128.831 4.2934C128.418 4.43101 128.033 4.62366 127.703 4.87136C127.372 5.11905 127.125 5.44931 126.932 5.83462C126.739 6.21992 126.657 6.66027 126.657 7.15566C126.657 7.62353 126.712 8.00883 126.849 8.33909C126.959 8.66936 127.125 8.91706 127.345 9.10971C127.565 9.32988 127.785 9.46749 128.06 9.6051C128.336 9.71518 128.611 9.82527 128.886 9.88031C129.216 9.96288 129.519 10.0455 129.849 10.1005C130.179 10.1555 130.455 10.2106 130.702 10.2931C130.95 10.3757 131.143 10.4858 131.308 10.5959C131.473 10.7335 131.528 10.9261 131.528 11.1463C131.528 11.4215 131.39 11.6692 131.115 11.8344C130.84 11.9995 130.482 12.0821 129.987 12.0821C129.491 12.0821 129.106 12.0545 128.831 11.972C128.528 11.8894 128.28 11.8068 128.005 11.7243C127.758 11.6142 127.482 11.6417 127.207 11.7518C126.932 11.8619 126.739 12.0821 126.657 12.4123C126.574 12.66 126.602 12.9352 126.712 13.2105C126.822 13.4857 127.014 13.6783 127.29 13.7609C127.62 13.871 127.978 13.9811 128.391 14.0361C128.803 14.1187 129.326 14.1462 130.014 14.1462C130.51 14.1462 131.005 14.0911 131.501 13.9811C131.996 13.871 132.409 13.7058 132.767 13.4306C133.124 13.1829 133.427 12.8527 133.647 12.4674C133.867 12.0821 133.977 11.6142 133.977 11.0362V11.0087ZM72.1085 12.8802V1.40361C72.1085 1.04583 71.9984 0.743091 71.7232 0.467874C71.4755 0.220178 71.1728 0.082562 70.7875 0.082562C70.4297 0.082562 70.127 0.192656 69.8793 0.467874C69.6316 0.715569 69.5215 1.01831 69.5215 1.40361V12.8802C69.5215 13.238 69.6316 13.5407 69.8793 13.8159C70.127 14.0636 70.4297 14.2012 70.7875 14.2012C71.1453 14.2012 71.448 14.0912 71.7232 13.8159C71.9984 13.5407 72.1085 13.238 72.1085 12.8802Z" fill="white" />
					</svg>
				</div>
			</footer>
		</div>
	</main>
</template>
<script>

	import { __, sprintf } from '@wordpress/i18n';
	import { mapGetters } from 'vuex';
	import YearInReviewUpSellOverlay from "../reports-year-in-review/YearInReviewUpSellOverlay";
	import ReportYearInReviewByMonth from "../reports-year-in-review/ReportYearInReviewByMonth";
	import ReportYearInReviewTip from "../reports-year-in-review/ReportYearInReviewTip";
	import ReportYearInReviewListBox from "../reports-year-in-review/ReportYearInReviewListBox";
	import ReportYearInReviewPieChart from "../reports-year-in-review/ReportYearInReviewPieChart";
	import AddonBlock from "../../../addons/components/AddonBlock";

	export default {
		name: 'YearInReview',
		components: { YearInReviewUpSellOverlay, ReportYearInReviewByMonth, ReportYearInReviewTip, ReportYearInReviewListBox, ReportYearInReviewPieChart, AddonBlock },
		data() {
			return {
				text_calculating: __( 'Still Calculating...', process.env.VUE_APP_TEXTDOMAIN ),
				text_year_in_review_still_calculating: __( 'Your 2019 Year in Review is still calculating. Please check back later to see how your website performed last year.', process.env.VUE_APP_TEXTDOMAIN ),
				text_back_to_overview_report: __( 'Back to Overview Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_title: __( 'Your 2019 Analytics Report', process.env.VUE_APP_TEXTDOMAIN ),
				text_sub_title: __( '2019 Year in Review', process.env.VUE_APP_TEXTDOMAIN ),
				text_summary: __( 'See how your website performed this year and find tips along the way to help grow even more in 2020!', process.env.VUE_APP_TEXTDOMAIN ),
				text_audience_section_title: __( 'Audience', process.env.VUE_APP_TEXTDOMAIN ),
				text_congrats: __( 'Congrats', process.env.VUE_APP_TEXTDOMAIN ),
				text_popular: __( 'Your website was quite popular this year! ', process.env.VUE_APP_TEXTDOMAIN ),
				text_you_had: __( 'You had ', process.env.VUE_APP_TEXTDOMAIN ),
				text_visitors: __( ' visitors!', process.env.VUE_APP_TEXTDOMAIN ),
				text_best_month_visitors: __( ' visitors', process.env.VUE_APP_TEXTDOMAIN ),
				text_total_visitors: __( 'Total Visitors', process.env.VUE_APP_TEXTDOMAIN ),
				text_total_sessions: __( 'Total Sessions', process.env.VUE_APP_TEXTDOMAIN ),
				text_visitor_by_month_chart_title: __( 'Visitors by Month', process.env.VUE_APP_TEXTDOMAIN ),
				text_visitor_by_month_chart_tooltip: __( 'January 1, 2019 - December 31, 2019', process.env.VUE_APP_TEXTDOMAIN ),
				text_audience_tip_title: __( 'A Tip for 2020', process.env.VUE_APP_TEXTDOMAIN ),
				text_audience_tip_summary: __( 'See the top Traffic Sources and Top Pages for the Month of May in the Overview Report to replicate your success.', process.env.VUE_APP_TEXTDOMAIN ),
				text_section_demographics_title: __( 'Demographics', process.env.VUE_APP_TEXTDOMAIN ),
				text_number_one: __( '#1', process.env.VUE_APP_TEXTDOMAIN ),
				text_countries: __( 'You Top 5 Countries', process.env.VUE_APP_TEXTDOMAIN ),
				text_know_your_visitors: __( 'Let’s get to know your visitors a little better, shall we?', process.env.VUE_APP_TEXTDOMAIN ),
				text_gender: __( 'Gender', process.env.VUE_APP_TEXTDOMAIN ),
				text_female: __( 'Female', process.env.VUE_APP_TEXTDOMAIN ),
				text_women: __( 'Women', process.env.VUE_APP_TEXTDOMAIN ),
				text_male: __( 'Male', process.env.VUE_APP_TEXTDOMAIN ),
				text_average_age: __( 'Average Age', process.env.VUE_APP_TEXTDOMAIN ),
				text_section_behavior_title: __( 'Behavior', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_pages_graph_title: __( 'Your Top 5 Pages', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_pages_graph_subtitle: __( 'Pageviews', process.env.VUE_APP_TEXTDOMAIN ),
				text_time_spent: __( 'Time Spent on Site', process.env.VUE_APP_TEXTDOMAIN ),
				text_minutes: __( 'minutes', process.env.VUE_APP_TEXTDOMAIN ),
				text_device_type: __( 'Device Type', process.env.VUE_APP_TEXTDOMAIN ),
				text_grow_traffic_tip_title: __( 'A Tip For 2020', process.env.VUE_APP_TEXTDOMAIN ),
				text_grow_traffic_tip_summary: __( 'Take advantage of what you’ve already built. See how to get more traffic from existing content in our 32 Marketing Hacks to Grow Your Traffic.', process.env.VUE_APP_TEXTDOMAIN ),
				text_grow_traffic_tip_link_text: __( 'Read - 32 Marketing Hacks to Grow Your Traffic', process.env.VUE_APP_TEXTDOMAIN ),
				text_visitors_come_from: __( 'So, where did all of these visitors come from?', process.env.VUE_APP_TEXTDOMAIN ),
				text_clicks: __( 'Clicks', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_keywords: __( 'Your Top 5 Keywords', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_keywords_tooltip: __( 'What keywords visitors searched for to find your site', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_referrals: __( 'Your Top 5 Referrals', process.env.VUE_APP_TEXTDOMAIN ),
				text_pageviews: __( 'Pageviews', process.env.VUE_APP_TEXTDOMAIN ),
				text_top_referrals_tooltip: __( 'The websites that link back to your website', process.env.VUE_APP_TEXTDOMAIN ),
				text_opportunity_tip_title: __( 'Opportunity', process.env.VUE_APP_TEXTDOMAIN ),
				text_opportunity_tip_summary: __( 'Use referral sources to create new partnerships or expand existing ones. See our guide on how to spy on your competitors and ethically steal their traffic.', process.env.VUE_APP_TEXTDOMAIN ),
				text_opportunity_tip_link_text: __( 'Read - How to Ethically Steal Your Competitor’s Traffic', process.env.VUE_APP_TEXTDOMAIN ),
				text_thank_you_section_title: __( 'Thank you for using MonsterInsights!', process.env.VUE_APP_TEXTDOMAIN ),
				text_thank_you_section_summary: __( 'We’re grateful for your continued support. If there’s anything we can do to help you grow your business, please don’t hesitate to contact our team.', process.env.VUE_APP_TEXTDOMAIN ),
				text_amazing_2020: __( "Here's to an amazing 2020!", process.env.VUE_APP_TEXTDOMAIN ),
				text_enjoying_monsterinsights: __( 'Enjoying MonsterInsights', process.env.VUE_APP_TEXTDOMAIN ),
				text_leave_review: __( 'Leave a five star review!', process.env.VUE_APP_TEXTDOMAIN ),
				text_syed_balkhi: __( 'Syed Balkhi', process.env.VUE_APP_TEXTDOMAIN ),
				text_chris_christoff: __( 'Chris Christoff', process.env.VUE_APP_TEXTDOMAIN ),
				text_write_review: __( 'Write Review', process.env.VUE_APP_TEXTDOMAIN ),
				text_plugins_section_title: __( 'Did you know over 10 million websites use our plugins?', process.env.VUE_APP_TEXTDOMAIN ),
				text_plugins_section_summary: __( 'Try our other popular WordPress plugins to grow your website in 2020.', process.env.VUE_APP_TEXTDOMAIN ),
				text_communities_section_title: __( 'Join our Communities!', process.env.VUE_APP_TEXTDOMAIN ),
				text_communities_section_summary: __( 'Become a WordPress expert in 2020. Join our amazing communities and take your website to the next level.', process.env.VUE_APP_TEXTDOMAIN ),
				text_facebook_group: __( 'Facebook Group', process.env.VUE_APP_TEXTDOMAIN ),
				text_facebook_group_summary: __( 'Join our team of WordPress experts and other motivated website owners in the WPBeginner Engage Facebook Group.', process.env.VUE_APP_TEXTDOMAIN ),
				text_facebook_join_button: __( 'Join Now...It’s Free!', process.env.VUE_APP_TEXTDOMAIN ),
				text_wpbeginner_community_title: __( 'WordPress Tutorials by WPBeginner', process.env.VUE_APP_TEXTDOMAIN ),
				text_wpbeginner_community_summary: __( 'WPBeginner is the largest free WordPress resource site for beginners and non-techy users.', process.env.VUE_APP_TEXTDOMAIN ),
				text_visit_wpbeginner: __( 'Visit WPBeginner', process.env.VUE_APP_TEXTDOMAIN ),
				text_follow_us: __( 'Follow Us!', process.env.VUE_APP_TEXTDOMAIN ),
				text_follow_us_summary: __( 'Follow MonsterInsights on social media to stay up to date with latest updates, trends, and tutorials on how to make the most out of analytics.', process.env.VUE_APP_TEXTDOMAIN ),
				text_copyright_monsterinsights: __( 'Copyright MonsterInsights, 2020', process.env.VUE_APP_TEXTDOMAIN ),
				text_search_console_upsell_overlay_details: __( 'Upgrade to MonsterInsights Pro to Unlock Additional Actionable Insights', process.env.VUE_APP_TEXTDOMAIN ),
				text_search_console_upsell_overlay_btn_text: __( 'Upgrade to MonsterInsights Pro', process.env.VUE_APP_TEXTDOMAIN ),
				months: [
					__( 'January', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'February', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'March', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'April', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'May', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'June', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'July', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'August', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'September', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'October', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'November', process.env.VUE_APP_TEXTDOMAIN ),
					__( 'December', process.env.VUE_APP_TEXTDOMAIN ),
				],
			};
		},
		mounted() {
			this.$store.dispatch( '$_reports/getReportData', 'yearinreview' );
		},
		computed: {
			...mapGetters( {
				addons: '$_addons/addons',
				yearInReview: '$_reports/yearinreview',
				date: '$_reports/date',
				license: '$_license/license',
				license_network: '$_license/license_network',
			} ),
			visitorByMonthData() {
				return {
					datasets: [
						{
							backgroundColor: '#338EEF',
							data: this.yearInReview.usersgraph.datapoints,
						},
					],
					labels: this.yearInReview.usersgraph.labels,
				};
			},
			bestMonthVisitorsSummary() {
				return sprintf( __( "Your best month was <strong>%1$s</strong> with <strong>%2$s visitors!</strong>", process.env.VUE_APP_TEXTDOMAIN ), this.months[this.yearInReview.usersgraph.bestmonth], this.commaNumbers(this.yearInReview.usersgraph.max) );
			},
			countriesData() {
				let countries = [];
				let number = 0;
				if ( this.yearInReview.countries ) {
					this.yearInReview.countries.forEach( function( country ) {
						number++;
						countries.push( {
							number: number + '.',
							text: '<span class="monsterinsights-flag monsterinsights-flag-' + country.iso.toLowerCase() + '"></span> ' + country.name,
							right: country.sessions,
						} );
					} );
				}
				return countries;
			},
			demoGraphicsSummary() {
				return sprintf( __( "Your <strong>%1$s</strong> visitors came from <strong>%2$s</strong> different countries.", process.env.VUE_APP_TEXTDOMAIN ), this.commaNumbers(this.yearInReview.info.users.value), this.commaNumbers(this.yearInReview.countries.length) );
			},
			topCountryFlagClass() {
				return "monsterinsights-flag-" + this.yearInReview.countries[0].iso.toLowerCase();
			},
			topCountryVisitors() {
				return sprintf( __( '%s Visitors', process.env.VUE_APP_TEXTDOMAIN ), this.commaNumbers(this.yearInReview.countries[0].sessions) );
			},
			maxVisitorGender() {
				let maxVisitorGender = '';
				let maxVisitorGenderObj = this.getMaxVisitorGenderObj();
				if ( maxVisitorGenderObj.gender === 'male' ) {
					maxVisitorGender = this.text_male;
				}
				if ( maxVisitorGenderObj.gender === 'female' ) {
					maxVisitorGender = this.text_female;
				}
				return maxVisitorGender;
			},
			maxVisitorGenderSummary() {
				return sprintf( __( '%1$s&#37 of your visitors were %2$s', process.env.VUE_APP_TEXTDOMAIN ), this.getMaxVisitorGenderObj().percent, this.maxVisitorGender.toLowerCase() );
			},
			maxVisitorAverageAge() {
				return this.getMaxVisitorAgeObj().age;
			},
			maxVisitorAgeSummary() {
				return sprintf( __( '%1$s&#37 of your visitors were between the ages of %2$s', process.env.VUE_APP_TEXTDOMAIN ), this.getMaxVisitorGenderObj().percent, this.maxVisitorAverageAge );
			},
			behaviourSummary() {
				if ( parseInt( this.yearInReview.info.pageviews.value ) == 0 || parseInt( this.yearInReview.info.users.value == 0 ) ) {
					return sprintf( __( "Your <strong>%1$s</strong> visitors viewed a total of <strong>%2$s</strong> pages. <span class='average-page-per-user' style='font-size: 20px;margin-top:25px;display:block;font-family:Lato'>That's an average of %3$s pages for each visitor!</span>",
						process.env.VUE_APP_TEXTDOMAIN ),
						this.commaNumbers(this.yearInReview.info.users.value),
						this.commaNumbers(this.yearInReview.info.pageviews.value),
						0,
					);
				}

				return sprintf( __( "Your <strong>%1$s</strong> visitors viewed a total of <strong>%2$s</strong> pages. <span class='average-page-per-user' style='font-size: 20px;margin-top:25px;display:block;font-family:Lato'>That's an average of %3$s pages for each visitor!</span>",
					process.env.VUE_APP_TEXTDOMAIN ),
					this.commaNumbers(this.yearInReview.info.users.value),
					this.commaNumbers(this.yearInReview.info.pageviews.value),
					this.commaNumbers(Math.round( parseInt( this.yearInReview.info.pageviews.value ) / parseInt( this.yearInReview.info.users.value ) )),
				);
			},
			topPages() {
				let pages = [];
				let number = 0;
				if ( this.yearInReview.toppages ) {
					this.yearInReview.toppages.forEach( function( page ) {
						number++;
						let text = page.hostname ? '<a href="' + page.hostname + page.url + '" target="_blank" rel="noreferrer noopener">' + page.title + '</a>' : page.title;
						pages.push( {
							number: number + '.',
							text: text,
							right: page.sessions,
						} );
					} );
				}
				return pages;
			},
			eachVisitorSpentSummary() {
				return sprintf( __( 'Each visitor spent an average of %s minutes on your website in 2019.', process.env.VUE_APP_TEXTDOMAIN ), this.commaNumbers(this.yearInReview.info.duration.avg_minutes) );
			},
			mostVisitorsDevice() {
				if ( this.yearInReview.devices ) {
					let devices = this.yearInReview.devices;
					let mostVisitorsDevice = 0;
					let mostVisitorsDeviceData = [];
					for (let device in devices) {
						if (devices.hasOwnProperty(device)) {
							if (devices[device] > mostVisitorsDevice) {
								mostVisitorsDeviceData['name'] = device;
								mostVisitorsDeviceData['percent'] = devices[device];
								mostVisitorsDevice = devices[device];
							}
						}
					}
					return mostVisitorsDeviceData;
				}
				return false;
			},
			mostVisitorsDeviceSummary() {
				return sprintf( __( 'Most of your visitors viewed your website from their <strong>%s</strong> device.', process.env.VUE_APP_TEXTDOMAIN ), this.mostVisitorsDevice.name );
			},
			mostVisitorsDevicePercent() {
				return sprintf( __( '%1$s&#37 of your visitors were on a %2$s device.', process.env.VUE_APP_TEXTDOMAIN ), this.mostVisitorsDevice.percent, this.mostVisitorsDevice.name );
			},
			devicesData() {
				if ( this.yearInReview.devices ) {
					return {
						datasets: [
							{
								data: [
									this.yearInReview.devices.desktop,
									this.yearInReview.devices.tablet,
									this.yearInReview.devices.mobile,
								],
								backgroundColor: [
									'#6AB1FC',
									'#AAD3FF',
									'#338EEF',
								],
							},
						],
						values: [
							this.yearInReview.devices.desktop,
							this.yearInReview.devices.tablet,
							this.yearInReview.devices.mobile,
						],
						labels: [
							__( 'Desktop', process.env.VUE_APP_TEXTDOMAIN ),
							__( 'Tablet', process.env.VUE_APP_TEXTDOMAIN ),
							__( 'Mobile', process.env.VUE_APP_TEXTDOMAIN ),
						],

					};
				}
				return false;
			},
			referralsData() {
				let referrals = [];
				let number = 0;
				if ( this.yearInReview.referrals ) {
					this.yearInReview.referrals.forEach( function( referral ) {
						number++;
						referrals.push( {
							number: number + '.',
							text: '<img src="https://www.google.com/s2/favicons?domain=http://' + referral.url + '" />' + referral.url,
							right: referral.sessions,
						} );
					} );
				}
				return referrals;
			},
			searchConsoleSampleData() {
				return [
					{
						number: 1,
						text: 'search term one',
						right: 7978,
					},
					{
						number: 2,
						text: 'search term two',
						right: 79789,
					},
					{
						number: 3,
						text: 'search three',
						right: 897,
					},
					{
						number: 4,
						text: 'search four',
						right: 797,
					},
					{
						number: 5,
						text: 'search term five',
						right: 299,
					},
				];
			},
		},
		methods: {
			isAddonActive( addon ) {
				if ( this.addons[addon]) {
					return this.addons[addon].active;
				}
				return false;
			},
			commaNumbers( num ){
				return parseFloat(num).toLocaleString('en');
			},
			isMoreVisitors() {
				return this.yearInReview.info.users.value > this.yearInReview.info.users.prev ? true : false;
			},
			getMaxVisitorGenderObj() {
				let gender = this.yearInReview.gender;
				let currentPercent = 0;
				let maxVisitorGenderObj = {};
				gender.forEach(function(item){
					if ( item.percent > currentPercent ) {
						currentPercent = item.percent;
						maxVisitorGenderObj = item;
					}
				});
				return maxVisitorGenderObj;
			},
			getMaxVisitorAgeObj() {
				let age = this.yearInReview.age;
				let currentPercent = 0;
				let maxVisitorAgeObj = {};
				age.forEach(function(item){
					if ( item.percent > currentPercent ) {
						currentPercent = item.percent;
						maxVisitorAgeObj = item;
					}
				});
				return maxVisitorAgeObj;
			},
			pluginsList() {
				let addonsIncluded = [
					'wpforms',
					'wp-mail-smtp',
					'optinmonster',
					'coming-soon',
					'rafflepress',
					'trustpulse-api',
				];
				let addons = [];

				addonsIncluded.forEach( ( addon_slug ) => {
					if ( this.addons[addon_slug] ) {
						let addon = Object.create( this.addons[addon_slug] );
						addon.type = 'licensed';
						addons.push( addon );
					}
				} );

				return addons;
			},
		},
	};
</script>
