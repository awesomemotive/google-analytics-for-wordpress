import { __ } from "@wordpress/i18n";

const MonsterInsightsReportsHelper = {
	install( Vue ) {
		Vue.prototype.$miOverviewTooltips = function( tooltip ) {
			if ( ! tooltip.title ) {
				const tooltips = document.querySelectorAll( '.monsterinsights-line-chart-tooltip' );
				tooltips.forEach( function( tooltipEl ) {
					tooltipEl.style.opacity = 0;
				} );
				return false;
			}
			let label = tooltip.title[0];
			let value = tooltip.title[1];
			let change = parseInt( tooltip.title[2] );
			let tooltip_text = tooltip.title[3];
			let id = tooltip.title[4];
			// Tooltip Element
			let tooltipEl = document.getElementById( 'monsterinsights-chartjs-line-' + id + '-tooltip' );
			if ( null === tooltipEl ) {
				tooltipEl = document.createElement( 'div' );
				document.body.appendChild( tooltipEl );
				tooltipEl.setAttribute( 'id', 'monsterinsights-chartjs-line-' + id + '-tooltip' );
				tooltipEl.classList.add( 'monsterinsights-line-chart-tooltip' );
			}
			// Hide if no tooltip
			if ( ! tooltip.opacity ) {
				tooltipEl.style.opacity = 0;
				return;
			}

			tooltipEl.classList.remove( 'above' );
			tooltipEl.classList.remove( 'below' );
			tooltipEl.classList.remove( 'no-transform' );
			if ( tooltip.yAlign ) {
				tooltipEl.classList.add( tooltip.yAlign );
			} else {
				tooltipEl.classList.add( 'no-transform' );
			}

			let trend = '';
			if ( 0 === change ) {
				trend += '0%';
			} else if ( change > 0 ) {
				trend += '<span class="monsterinsights-green"><span class="monsterinsights-arrow monsterinsights-up"></span>' + change + '%</span>';
			} else {
				trend += '<span class="monsterinsights-red"><span class="monsterinsights-arrow monsterinsights-down"></span>' + Math.abs( change ) + '%</span>';
			}

			let html = '<div class="monsterinsights-reports-overview-datagraph-tooltip-container monsterinsights-reports-tooltip">';
			html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-title">' + label + '</div>';
			html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-number">' + value + '</div>';
			html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-descriptor">' + tooltip_text + '</div>';
			html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-trend">' + trend + '</div>';
			html += '</div>';

			tooltipEl.innerHTML = html;

			// Find Y Location on page
			const position = this._chart.canvas.getBoundingClientRect();

			tooltipEl.style.opacity = '1';
			tooltipEl.style.left = position.left + window.pageXOffset + tooltip.x + 'px';
			tooltipEl.style.top = position.top + window.pageYOffset + tooltip.y + 'px';
			tooltipEl.style.fontFamily = 'Helvetica Neue, Helvetica, Arial, Lucida Grande, sans-serif;';
			tooltipEl.style.fontSize = tooltip.fontSize;
			tooltipEl.style.fontStyle = tooltip._fontStyle;
			tooltipEl.style.padding = tooltip.yPadding + 'px ' + tooltip.xPadding + 'px';
			tooltipEl.style.zIndex = 99999;
			tooltipEl.style.pointerEvents = 'none';
		};
		Vue.prototype.$miPieTooltips = function( tooltip ) {
			if ( ! tooltip.title ) {
				const tooltips = document.querySelectorAll( '.monsterinsights-pie-chart-tooltip' );
				tooltips.forEach( function( tooltipEl ) {
					tooltipEl.style.opacity = 0;
				} );
				return false;
			}
			let label = tooltip.title[0];
			let value = tooltip.title[1];
			let id = tooltip.title[2];
			// Tooltip Element
			let tooltipEl = document.getElementById( 'monsterinsights-chartjs-pie-' + id + '-tooltip' );
			if ( null === tooltipEl ) {
				tooltipEl = document.createElement( 'div' );
				document.body.appendChild( tooltipEl );
				tooltipEl.setAttribute( 'id', 'monsterinsights-chartjs-pie-' + id + '-tooltip' );
			}

			// Set caret Position
			tooltipEl.classList.remove( 'above' );
			tooltipEl.classList.remove( 'below' );
			tooltipEl.classList.remove( 'no-transform' );
			if ( tooltip.yAlign ) {
				tooltipEl.classList.add( tooltip.yAlign );
			} else {
				tooltipEl.classList.add( 'no-transform' );
			}

			let html = '<div class="monsterinsights-reports-overview-datagraph-tooltip-container monsterinsights-reports-tooltip">';
			html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-title">' + label + '</div>';
			html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-number">' + value + '%</div>';
			html += '</div>';

			tooltipEl.innerHTML = html;

			// Find Y Location on page
			let top = 0;

			if ( tooltip.yAlign ) {
				let ch = 0;
				if ( tooltip.caretHeight ) {
					ch = tooltip.caretHeight;
				}
				if ( 'above' === tooltip.yAlign ) {
					top = tooltip.y - ch - tooltip.caretPadding;
				} else {
					top = tooltip.y + ch + tooltip.caretPadding;
				}
			}
			// Display, position, and set styles for font
			tooltipEl.style.opacity = 1;
			tooltipEl.style.left = tooltip.x - 50 + 'px';
			tooltipEl.style.top = top - 40 + 'px';
			tooltipEl.style.padding = tooltip.yPadding + 'px ' + tooltip.xPadding + 'px';
			tooltipEl.style.zIndex = '99999';
		};
		Vue.prototype.$miyearInReviewTooltips = function( tooltip ) {
			if ( ! tooltip.title ) {
				const tooltips = document.querySelectorAll( '.monsterinsights-line-chart-tooltip' );
				tooltips.forEach( function( tooltipEl ) {
					tooltipEl.style.opacity = 0;
				} );
				return false;
			}
			let label = tooltip.title[0];
			let value = tooltip.title[1];
			let id = tooltip.title[4];
			// Tooltip Element
			let tooltipEl = document.getElementById( 'monsterinsights-chartjs-line-' + id + '-tooltip' );
			if ( null === tooltipEl ) {
				tooltipEl = document.createElement( 'div' );
				document.body.appendChild( tooltipEl );
				tooltipEl.setAttribute( 'id', 'monsterinsights-chartjs-line-' + id + '-tooltip' );
				tooltipEl.classList.add( 'monsterinsights-line-chart-tooltip' );
			}
			// Hide if no tooltip
			if ( ! tooltip.opacity ) {
				tooltipEl.style.opacity = 0;
				return;
			}

			tooltipEl.classList.remove( 'above' );
			tooltipEl.classList.remove( 'below' );
			tooltipEl.classList.remove( 'no-transform' );
			if ( tooltip.yAlign ) {
				tooltipEl.classList.add( tooltip.yAlign );
			} else {
				tooltipEl.classList.add( 'no-transform' );
			}

			let html = '<div class="monsterinsights-reports-overview-datagraph-tooltip-container monsterinsights-reports-tooltip">';
			html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-title">' + label + '</div>';
			html += '<div class="monsterinsights-reports-overview-datagraph-tooltip-number">' + value + '</div>';
			html += '</div>';

			tooltipEl.innerHTML = html;

			// Find Y Location on page
			const position = this._chart.canvas.getBoundingClientRect();

			tooltipEl.style.opacity = '1';
			tooltipEl.style.left = position.left + window.pageXOffset + tooltip.x + 'px';
			tooltipEl.style.top = position.top + window.pageYOffset + tooltip.y + 'px';
			tooltipEl.style.fontFamily = 'Helvetica Neue, Helvetica, Arial, Lucida Grande, sans-serif;';
			tooltipEl.style.fontSize = tooltip.fontSize;
			tooltipEl.style.fontStyle = tooltip._fontStyle;
			tooltipEl.style.padding = tooltip.yPadding + 'px ' + tooltip.xPadding + 'px';
			tooltipEl.style.zIndex = 99999;
			tooltipEl.style.pointerEvents = 'none';
		};
		Vue.prototype.$mi_loading_toast = function( title, content ) {
			Vue.prototype.$swal( {
				type: 'info',
				customContainerClass: 'monsterinsights-swal',
				title: title ? title : __( 'Refreshing Report', process.env.VUE_APP_TEXTDOMAIN ),
				html: content ? content : __( 'Loading new report data...', process.env.VUE_APP_TEXTDOMAIN ),
				allowOutsideClick: false,
				allowEscapeKey: false,
				allowEnterKey: false,
				onOpen: function() {
					Vue.prototype.$swal.showLoading();
				},
			} );
		};
		Vue.prototype.$mi_error_toast = function( settings ) {
			let {
				type = 'error',
				customContainerClass = 'monsterinsights-swal',
				allowOutsideClick = false,
				allowEscapeKey = false,
				allowEnterKey = false,
				title = __( 'Error', process.env.VUE_APP_TEXTDOMAIN ),
				html = __( 'Please try again.', process.env.VUE_APP_TEXTDOMAIN ),
				footer = false,
			} = settings;

			return Vue.prototype.$swal( {
				type,
				customContainerClass,
				allowOutsideClick,
				allowEscapeKey,
				allowEnterKey,
				title,
				html,
				footer,
				onOpen: function( ) {
					Vue.prototype.$swal.hideLoading();
				},
			} );
		};
		Vue.prototype.$mi_get_upsell_content = function( report ) {
			let upsell_content = {};

			const upsell_texts = {
				// Used in the Lite Widget.
				overview: {
					title: __( 'Unlock the Publishers Report and Focus on the Content that Matters', process.env.VUE_APP_TEXTDOMAIN ),
					subtitle: __( 'Stop guessing about what content your visitors are interested in. MonsterInsights Publisher Report shows you exactly which content gets the most visits, so you can analyze and optimize it for higher conversions.', process.env.VUE_APP_TEXTDOMAIN ),
					features: [
						__( 'See Your Top Landing Pages to Improve Enagement', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Exit Pages to Reduce Abandonment', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Outbound Links to Find New Revenue Opportunities', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Affiliate Links and Focus on what\'s working', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Downloads and Improve Conversions', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Audience Demographic Report ( Age / Gender / Interests )', process.env.VUE_APP_TEXTDOMAIN ),
					],
				},
				publisher: {
					title: __( 'Unlock the Publishers Report and Focus on the Content That Matters', process.env.VUE_APP_TEXTDOMAIN ),
					subtitle: __( 'Stop guessing about what content your visitors are interested in. The Publisher Report shows you exactly which content gets the most traffic, so you can analyze and optimize it for higher conversions.', process.env.VUE_APP_TEXTDOMAIN ),
					features: [
						__( 'See Your Top Landing Pages to Improve Enagement', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Exit Pages to Reduce Abandonment', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Outbound Links to Find New Revenue Opportunities', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Affiliate Links and Focus on what\'s working', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Downloads and Improve Conversions', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Audience Demographic Report ( Age / Gender / Interests )', process.env.VUE_APP_TEXTDOMAIN ),
					],
				},
				ecommerce: {
					title: __( 'Unlock the eCommerce Report and See Your Important Store Metrics', process.env.VUE_APP_TEXTDOMAIN ),
					subtitle: __( 'Increase your sales & revenue with insights. MonsterInsights answers all your top eCommerce questions using metrics like total revenue, conversion rate, average order value, top products, top referral sources and more.', process.env.VUE_APP_TEXTDOMAIN ),
					features: [
						__( 'See Your Conversion Rate to Improve Funnel', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See The Number of Transactions and make data-driven decisions', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See The Total Revenue to Track Growth', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Average Order Value to Find Offer Opportunities', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Products to See Individual Performance', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Conversion Sources and Focus on what\'s working', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See The Time it takes for Customers to Purchase', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See How Many Sessions are needed for a Purchase', process.env.VUE_APP_TEXTDOMAIN ),
					],
				},
				dimensions: {
					title: __( 'Unlock the Dimensions Report and Track Your Own Custom Data', process.env.VUE_APP_TEXTDOMAIN ),
					subtitle: __( 'Decide what data is important using your own custom tracking parameters. The Dimensions report allows you to easily see what\'s working right inside your WordPress dashboard.', process.env.VUE_APP_TEXTDOMAIN ),
					features: [
						__( 'See Which Authors Generate the Most Traffic', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Which Post Types Perform Better', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Which Categories are the Most Popular', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Blog\'s most popular SEO Scores', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Which Focus Keyword is Performing Better in Search Engines', process.env.VUE_APP_TEXTDOMAIN ),
					],
				},
				forms: {
					title: __( 'Unlock the Forms Report and Improve Conversions', process.env.VUE_APP_TEXTDOMAIN ),
					subtitle: __( 'Easily track your form views and conversions. The Forms Report allows you to see which forms are performing better and which forms have lower conversion rates so you can optimize using real data.', process.env.VUE_APP_TEXTDOMAIN ),
					features: [
						__( 'See Reports for Any Contact Form Plugin or Sign-up Form', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Converting Forms and Optimize', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Forms Impressions Count to Find the Best Placement', process.env.VUE_APP_TEXTDOMAIN ),
					],
				},
				queries: {
					title: __( 'Unlock the Search Console Report and See How People Find Your Website', process.env.VUE_APP_TEXTDOMAIN ),
					subtitle: __( 'See exactly how people find your website, which keywords they searched for, how many times the results were viewed, and more.', process.env.VUE_APP_TEXTDOMAIN ),
					features: [
						__( 'See Your Top Google Search Terms and Optimize Content', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See The Number of Clicks and Track Interests', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See The Click-Through-Ratio and Improve SEO', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See The Average Results Position and Focus on what works', process.env.VUE_APP_TEXTDOMAIN ),
					],
				},
				realtime: {
					title: __( 'Unlock the Real-Time Report and Track the Visitors on Your Site in Real-Time', process.env.VUE_APP_TEXTDOMAIN ),
					subtitle: __( 'Track the results of your marketing efforts and product launches as-it-happens right from your WordPress site. The Real-Time report allows you to view your traffic sources and visitors activity when you need it.', process.env.VUE_APP_TEXTDOMAIN ),
					features: [
						__( 'See Your Active Visitors and Track Their Behaviour to Optimize', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Pages Immediately After Making Changes', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Top Referral Sources and Adapt Faster', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'See Your Traffic Demographics and ', process.env.VUE_APP_TEXTDOMAIN ),
						__( 'Get Fresh Reports Data Every 60 Seconds', process.env.VUE_APP_TEXTDOMAIN ),
					],
				},
			};

			if ( upsell_texts[report] ) {
				upsell_content = upsell_texts[report];
			}

			return upsell_content;
		};
	},
};

export default MonsterInsightsReportsHelper;
