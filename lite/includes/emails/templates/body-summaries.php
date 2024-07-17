<?php
// https://buttons.cm/
/**
 * Email Body
 *
 * Heavily influenced by the great AffiliateWP plugin by Pippin Williamson.
 * https://github.com/AffiliateWP/AffiliateWP/tree/master/templates/emails
 *
 * @since 8.19.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

$icon_increase        = plugins_url("lite/assets/img/emails/summaries/increase.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_increase_2x     = plugins_url("lite/assets/img/emails/summaries/increase@2x.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_decrease        = plugins_url("lite/assets/img/emails/summaries/decrease.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_decrease_2x     = plugins_url("lite/assets/img/emails/summaries/decrease@2x.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_top_pages           = plugins_url("lite/assets/img/emails/summaries/icon-top-pages.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_top_pages_2x        = plugins_url("lite/assets/img/emails/summaries/icon-top-pages@2x.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_sessions        = plugins_url("lite/assets/img/emails/summaries/icon-sessions.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_sessions_2x     = plugins_url("lite/assets/img/emails/summaries/icon-sessions@2x.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_page_views        = plugins_url("lite/assets/img/emails/summaries/icon-page-views.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_page_views_2x     = plugins_url("lite/assets/img/emails/summaries/icon-page-views@2x.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_engagement        = plugins_url("lite/assets/img/emails/summaries/icon-engagement.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_engagement_2x     = plugins_url("lite/assets/img/emails/summaries/icon-engagement@2x.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_more_traffic        = plugins_url("lite/assets/img/emails/summaries/icon-more-traffic.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_more_traffic_2x     = plugins_url("lite/assets/img/emails/summaries/icon-more-traffic@2x.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_button_arrow        = plugins_url("lite/assets/img/emails/summaries/icon-button-arrow.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_button_arrow_2x     = plugins_url("lite/assets/img/emails/summaries/icon-button-arrow@2x.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_bulb        = plugins_url("lite/assets/img/emails/summaries/icon-bulb.png", MONSTERINSIGHTS_PLUGIN_FILE);
$icon_bulb_2x     = plugins_url("lite/assets/img/emails/summaries/icon-bulb@2x.png", MONSTERINSIGHTS_PLUGIN_FILE);

$site_url   = get_site_url();
$start_date = isset($startDate) ? $startDate : date("Y-m-d", strtotime("-1 day, last month"));
$start_date = date("F j, Y", strtotime($start_date));
$end_date   = isset($endDate) ? $endDate : date("Y-m-d", strtotime("last saturday"));
$end_date   = date("F j, Y", strtotime($end_date));

$range              = isset($summaries['data']['infobox']['range']) ? $summaries['data']['infobox']['range'] : 0;
$total_sessions              = isset($summaries['data']['infobox']['sessions']['value']) ? $summaries['data']['infobox']['sessions']['value'] : 0;
$prev_sessions_percentage    = isset($summaries['data']['infobox']['sessions']['prev']) ? $summaries['data']['infobox']['sessions']['prev'] : 0;
$sessions_percentage_icon    = $icon_decrease;
$sessions_percentage_icon_2x = $icon_decrease_2x;
$sessions_percentage_class   = 'mcnTextDecrease';
$sessions_difference         = __('Decrease sessions: ', 'google-analytics-for-wordpress');
if ((int) $prev_sessions_percentage === (int) $prev_sessions_percentage && (int) $prev_sessions_percentage >= 0) {
	$sessions_percentage_icon    = $icon_increase;
	$sessions_percentage_icon_2x = $icon_increase_2x;
	$sessions_percentage_class   = 'mcnTextIncrease';
	$sessions_difference         = __('Increase sessions: ', 'google-analytics-for-wordpress');
}

$total_pageviews              = isset($summaries['data']['infobox']['pageviews']['value']) ? $summaries['data']['infobox']['pageviews']['value'] : 0;
$prev_pageviews_percentage    = isset($summaries['data']['infobox']['pageviews']['prev']) ? $summaries['data']['infobox']['pageviews']['prev'] : 0;
$pageviews_percentage_icon    = $icon_decrease;
$pageviews_percentage_icon_2x = $icon_decrease_2x;
$pageviews_percentage_class   = 'mcnTextDecrease';
$pageviews_difference         = __('Decrease pageviews: ', 'google-analytics-for-wordpress');
if ((int) $prev_pageviews_percentage === (int) $prev_pageviews_percentage && (int) $prev_pageviews_percentage >= 0) {
	$pageviews_percentage_icon    = $icon_increase;
	$pageviews_percentage_icon_2x = $icon_increase_2x;
	$pageviews_percentage_class   = 'mcnTextIncrease';
	$pageviews_difference         = __('Increase pageviews: ', 'google-analytics-for-wordpress');
}

$top_pages      = isset($summaries['data']['toppages']) ? $summaries['data']['toppages'] : '';
$top_referrals  = isset($summaries['data']['referrals']) ? $summaries['data']['referrals'] : '';
$more_pages     = isset($summaries['data']['galinks']['topposts']) ? $summaries['data']['galinks']['topposts'] : '';
$more_referrals = isset($summaries['data']['galinks']['referrals']) ? $summaries['data']['galinks']['referrals'] : '';

$total_engagement = (isset($summaries['data']['infobox']['engagement']['value'])) ? $summaries['data']['infobox']['engagement']['value'] : 0;
$prev_engagement_percentage = isset($summaries['data']['infobox']['engagement']['prev']) ? $summaries['data']['infobox']['engagement']['prev'] : 0;

$engagement_percentage_icon    = $icon_decrease;
$engagement_percentage_icon_2x = $icon_decrease_2x;
$engagement_percentage_class   = 'mcnTextDecrease';
$engagement_difference         = __('Decrease engagement: ', 'google-analytics-for-wordpress');
if ((int) $prev_engagement_percentage === (int) $prev_engagement_percentage && (int) $prev_engagement_percentage >= 0) {
	$engagement_percentage_icon    = $icon_increase;
	$engagement_percentage_icon_2x = $icon_increase_2x;
	$engagement_percentage_class   = 'mcnTextIncrease';
	$engagement_difference         = __('Increase engagement: ', 'google-analytics-for-wordpress');
}
?>
<tr>
	<td valign="top" class="mcnTextBlockInner" style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%; border-radius:0 0 5px 5px; background-color: #FFFFFF;">

		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" class="mcnTextContentContainer">
			<tbody>
				<tr style="display:block;">
					<td style="padding-right: 25px;padding-left: 25px;font-weight: bold;font-size: 24px;line-height: 28px;color: #393F4C;" class="mcnTextContent"><?php echo wp_kses_post($title); ?></td>
				</tr>
				<tr style="display:block;">
					<td style="padding-right: 25px;padding-left: 25px;padding-top:8px;font-weight: normal;font-size: 14px;line-height: 16px;color: #7F899F;" class="mcnTextContent">
						<?php echo esc_html( $start_date );
						?>
						-
						<?php echo esc_html( $end_date );
						?>
					</td>
				</tr>
				<tr style="display:block;">
					<td style="padding-top:8px;padding-left: 25px;padding-right: 25px;font-weight: bold;font-size: 14px;line-height: 16px;color: #7F899F;text-align:left;" class="mcnTextContent">
						<a href="<?php echo esc_url($site_url); ?>" style="font-weight: bold;font-size: 14px;line-height: 16px;color: #7F899F;text-decoration: underline;"><?php echo esc_url($site_url); ?></a>
					</td>
				</tr>
				<tr style="display:block;padding: 30px 25px 0 25px;">
					<td style="font-weight: bold;font-size: 14px;line-height: 27px;color: #393F4C;" class="mcnTextContent"><?php _e('Hi there!', 'google-analytics-for-wordpress'); ?></td>
				</tr>
				<tr style="display:block;padding:0 25px;">
					<td style="font-weight: normal;font-size: 14px;line-height: 20px;color: #4F5769;" class="mcnTextContent"><?php echo wp_kses_post($description); ?></td>
				</tr>
			</tbody>
		</table>

		<table style="margin:25px; font-family: Helvetica; width:400px; color: #393F4C;">
			<tbody>
				<tr>
					<td style="width:30%;padding:12px 9px; text-align:center; outline: 1px solid #D6E2ED; border-radius:3px; font-size: 12px;">
						<table style="width:100%;">
							<tbody>
								<tr>
									<td style="padding-bottom:8px;">
										<?php
										if (!empty($icon_sessions)) {
											echo '<img src="' . esc_url($icon_sessions) . '" srcset="' . esc_url($icon_sessions_2x) . ' 2x" target="_blank" alt="' . esc_attr__('Sessions', 'google-analytics-for-wordpress') . '" />';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="padding-bottom:8px; font-size:12px;">
										<?php _e('Total Sessions', 'google-analytics-for-wordpress'); ?>
									</td>
								</tr>
								<tr>
									<td style="padding-bottom:5px; font-size:12px;" align="center">
										<table cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td valign="middle" style="font-size:28px; color: #000000; font-weight: bold; padding-right: 5px;">
														<?php echo esc_html( number_format_i18n( $total_sessions ) ); ?>
													</td>
													<td valign="middle">
														<table cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" style="padding-right:1px; padding-top:1px;">
																		<?php
																			if (!empty($sessions_percentage_icon_2x)) {
																				echo '<img src="' . esc_url($sessions_percentage_icon_2x) . '" target="_blank" alt="' . $sessions_difference . '" style="width: 8px; height: auto;" />';
																			}
																		?>
																	</td>
																	<td valign="middle" class="<?php echo $sessions_percentage_class; ?>">
																		<?php printf('%s&#37;', $prev_sessions_percentage); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="padding-bottom:5px; font-size:10px; color: #9CA4B5;">
										<?php
										/* translators: Placeholder adds a range of days. */
										printf(__('vs previous %s days', 'google-analytics-for-wordpress'), $range);
										?>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td style="width:3%;">&nbsp;</td>
					<td style="width:30%;padding:12px 9px; text-align:center; outline: 1px solid #D6E2ED; border-radius:3px; font-size: 12px;">
						<table style="width:100%;">
							<tbody>
								<tr>
									<td style="padding-bottom:8px;">
										<?php
										if (!empty($icon_page_views)) {
											echo '<img src="' . esc_url($icon_page_views) . '" srcset="' . esc_url($icon_page_views_2x) . ' 2x" target="_blank" alt="' . esc_attr__('Page Views', 'google-analytics-for-wordpress') . '" />';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="padding-bottom:8px; font-size:12px;">
										<?php _e('Total Pageviews', 'google-analytics-for-wordpress'); ?>
									</td>
								</tr>
								<tr>
									<td style="padding-bottom:5px; font-size:12px;" align="center">
										<table cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td valign="middle" style="font-size:28px; color: #000000; font-weight: bold; padding-right: 5px;">
														<?php echo esc_html( number_format_i18n( $total_pageviews ) ); ?>
													</td>
													<td valign="middle">
														<table cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" style="padding-right:1px; padding-top:1px;">
																		<?php
																			if (!empty($pageviews_percentage_icon)) {
																				echo '<img src="' . esc_url($pageviews_percentage_icon) . '" srcset="' . esc_url($pageviews_percentage_icon_2x) . ' 2x" target="_blank" alt="' . $pageviews_difference . '" />';
																			}
																		?>
																	</td>
																	<td valign="middle" class="<?php echo $pageviews_percentage_class; ?>">
																		<?php printf('%s&#37;', $prev_pageviews_percentage); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="padding-bottom:5px; font-size:10px; color: #9CA4B5;">
										<?php printf(__('vs previous %s days', 'google-analytics-for-wordpress'), $range);  ?>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td style="width:3%;">&nbsp;</td>
					<td style="width:30%;padding:12px 9px; text-align:center; outline: 1px solid #D6E2ED; border-radius:3px; font-size: 12px;">
						<table style="width:100%;">
							<tbody>
								<tr>
									<td style="padding-bottom:8px;">
										<?php
										if (!empty($icon_engagement)) {
											echo '<img src="' . esc_url($icon_engagement) . '" srcset="' . esc_url($icon_engagement_2x) . ' 2x" target="_blank" alt="' . esc_attr__('engagement', 'google-analytics-for-wordpress') . '" />';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="padding-bottom:8px; font-size:12px;">
										<?php _e('Total Engagement', 'google-analytics-for-wordpress'); ?>
									</td>
								</tr>
								<tr>
									<td style="padding-bottom:5px; font-size:12px;" align="center">
										<table cellspacing="0" cellpadding="0" border="0">
											<tbody>
												<tr>
													<td valign="middle" style="font-size:28px; color: #000000; font-weight: bold; padding-right: 5px;">
														<?php echo esc_html( number_format_i18n( $total_engagement ) ); ?>
													</td>
													<td valign="middle">
														<table cellspacing="0" cellpadding="0" border="0">
															<tbody>
																<tr>
																	<td valign="middle" style="padding-right:1px; padding-top:1px;">
																		<?php
																			if (!empty($engagement_percentage_icon)) {
																				echo '<img src="' . esc_url($engagement_percentage_icon) . '" srcset="' . esc_url($engagement_percentage_icon_2x) . ' 2x" target="_blank" alt="' . $engagement_difference . '" />';
																			}
																		?>
																	</td>
																	<td valign="middle" class="<?php echo $pageviews_percentage_class; ?>">
																		<?php printf('%s&#37;', $prev_engagement_percentage); ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="padding-bottom:5px; font-size:10px; color: #9CA4B5;">
										<?php printf(__('vs previous %s days', 'google-analytics-for-wordpress'), $range);  ?>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>

		<?php if (!empty($top_pages)) : ?>
			<table style="margin:25px; font-family: Helvetica; width:400px; color: #393F4C;">
				<tbody>
					<tr>
						<td style="width: 24px; vertical-align:middle;">
							<?php
							if (!empty($icon_top_pages)) {
								echo '<img src="' . esc_url($icon_top_pages) . '" srcset="' . esc_url($icon_top_pages_2x) . ' 2x" target="_blank" alt="' . esc_attr__('Pages', 'google-analytics-for-wordpress') . '" />';
							}
							?>
						</td>
						<td style="font-weight:bold; font-size: 14px;  vertical-align:middle;"><?php _e('Top Pages', 'google-analytics-for-wordpress'); ?></td>
					</tr>
				</tbody>
			</table>
			<table style="margin:10px 25px ; font-family: Helvetica; width:400px; color: #9CA4B5; font-size: 12px;">
				<tbody>
					<tr>
						<td style="text-align: left;">Page Title</td>
						<td style="text-align: right;">Page Views</td>
					</tr>
				</tbody>
			</table>
			<table style="margin:0px 25px; font-family: Helvetica; width:400px; color: #9CA4B5; font-size: 12px; width:400px;">
				<tbody>
					<?php $i = 0; ?>
					<?php while ($i <= 9) : ?>
						<?php if (isset($top_pages[$i])) : ?>
							<tr>
								<td style="width:300px;padding-top:8px;padding-bottom:8px;text-align:left;font-weight: normal;font-size: 14px;line-height: 16px;color: #393F4C;overflow:hidden;" class="mcnTextContent">
									<a href="<?php echo esc_url($top_pages[$i]['hostname'] . $top_pages[$i]['url']); ?>" target="_blank" style="text-decoration:none;color: #393F4C;"><?php echo esc_html($i + 1 . '. ' . monsterinsights_trim_text($top_pages[$i]['title'], 2)); ?></a>
								</td>
								<td style="width:100px;padding-top:8px;padding-bottom:8px;text-align:right;font-weight: normal;font-size: 14px;line-height: 16px;color: #509FE2;overflow:hidden;text-overflow: ellipsis;" class="mcnTextContent">
									<?php echo esc_html(number_format_i18n($top_pages[$i]['sessions'])); ?>
								</td>
							</tr>
						<?php endif; ?>
						<?php $i++; ?>
					<?php endwhile; ?>
					<tr style="display:flex;">
						<td style="width:67%;float:left;padding-top:18px;text-align:left;font-weight: normal;font-size: 12px;line-height: 14px;color: #509FE2;text-decoration: underline;" class="mcnTextContent"><a href="<?php echo esc_url($more_pages); ?>" style="color: #509FE2;"><?php _e('View All Report', 'google-analytics-for-wordpress'); ?></a>
						</td>
					</tr>
				</tbody>
			</table>
		<?php endif; ?>

		<br>
		<table style="margin: 0 25px; font-family: Helvetica; width:400px; color: #9CA4B5;">
			<tbody>
				<tr>
					<td style="text-align: center; background-color: #F9FBFF; border-radius: 3px; outline: 1px solid #D6E2ED; padding: 20px 20px 25px;">
						<table style="width: 100%;">
							<tbody>
								<tr>
									<td style="text-align:center; font-size: 17px; font-weight: 700; color: #393F4C; margin-bottom:10px;">
										<table style="width: 360px;">
											<tbody>
												<tr>
													<td style="width: 130px; text-align: right; padding-right: 5px;">
													<?php
														if (!empty($icon_more_traffic)) {
															echo '<img src="' . esc_url($icon_more_traffic) . '" srcset="' . esc_url($icon_more_traffic_2x) . ' 2x" target="_blank" alt="' . esc_attr__('More Traffic', 'google-analytics-for-wordpress') . '" />';
														}
													?>
													</td>
													<td style="text-align: left; padding-left: 5px;">
														<?php _e('Want to Grow?', 'google-analytics-for-wordpress') ?>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="font-size:13px; line-height:18px; text-align:center;color: #777777; padding-bottom:10px;">
										<?php _e('With MonsterInsights Pro, unlock all reports and integrations to help you see the stats that matter. Instantly track purchases, video plays, SEO reports, and much more.', 'google-analytics-for-wordpress') ?>
									</td>
								</tr>
								<tr>
									<td style="text-align:center;">
										<div style="text-align:center; margin:0 auto;">
											<!--[if mso]>
											<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="<?php echo monsterinsights_get_upgrade_link('monthly-lite-email', 'monthly-lite-email', "https://www.monsterinsights.com/pricing/"); ?>" style="height:35px;v-text-anchor:middle;width:164px;" arcsize="9%" strokecolor="#338EEF" fillcolor="#338EEF">
												<w:anchorlock/>
												<center style="color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;"><?php _e('Upgrade to Pro', 'google-analytics-for-wordpress'); ?></center>
											</v:roundrect>
										<![endif]-->
											<a href="<?php echo monsterinsights_get_upgrade_link('monthly-lite-email', 'monthly-lite-email', "https://www.monsterinsights.com/pricing/"); ?>" target="_blank" style="background-color:#338EEF;border-width:1px 1px 2px 1px;border-color:#1177E3;border-style:solid;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:35px;text-align:center;text-decoration:none;width:164px;-webkit-text-size-adjust:none;mso-hide:all;">
												<?php _e('Upgrade to Pro', 'google-analytics-for-wordpress'); ?>
												<?php
												if (!empty($icon_button_arrow)) {
													echo '<img src="' . esc_url($icon_button_arrow) . '" srcset="' . esc_url($icon_button_arrow_2x) . ' 2x" target="_blank" alt="' . esc_attr__('Upgrade To Pro', 'google-analytics-for-wordpress') . '" style="padding-left: 3px;" />';
												}
												?>
											</a>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<?php if (isset($info_block['title']) && !empty($info_block['title'])) : ?>


			<br>
			<table style="font-family: Helvetica; width:400px; color: #9CA4B5; width:400px; margin: 0 25px 0 25px; background-color: #F8F8F8; outline: 1px solid rgba(119, 119, 119, 0.15); font-size: 13px; border-radius: 3px;">
				<tbody>
					<tr>
						<td valign="top" style="padding: 20px 5px 0px 20px; text-align:center;">
							<?php
							if (!empty($icon_bulb)) {
								echo '<img src="' . esc_url($icon_bulb) . '" srcset="' . esc_url($icon_bulb_2x) . ' 2x" target="_blank" alt="' . esc_attr__('', 'google-analytics-for-wordpress') . '" style="padding-left: 3px;" />';
							}
							?>
						</td>
						<td style="padding: 20px 15px 20px 5px;">
							<div style="font-weight: normal; padding: 3px 0 10px 0;">
								<?php _e('Pro Tip from our experts', 'google-analytics-for-wordpress'); ?>
							</div>
							<div style="font-weight: 700;  color: #393F4C; padding-bottom:10px;">
								<?php echo esc_html($info_block['title']); ?>
							</div>
							<div>
								<?php echo wp_kses_post($info_block['html']); ?>
							</div>
							<div style="padding: 10px 0;">
								<a href="<?php echo esc_url($info_block['link_url']); ?>" target="_blank" style="color: #338EEF;">
									<?php echo esc_html($info_block['link_text']); ?>
								</a>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<br />
		<?php endif; ?>
		<!-- end table here.. -->
	</td>
</tr>
