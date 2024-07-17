<?php
/**
 * Email Summaries body template (test plain text).
 *
 * @since 8.19.0
 *
 * @version 8.19.0
 *
 * @var array $info_block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$top_pages      = array(
	array(
		'url'      => 'https://example.com/test',
		'title'    => 'Contact Page',
		'hostname' => 'https://example.com',
		'sessions' => '10980',
	),
	array(
		'url'      => 'https://example.com/test',
		'title'    => 'Sample Page',
		'hostname' => 'https://example.com',
		'sessions' => '980',
	),
	array(
		'url'      => 'https://example.com/test',
		'title'    => 'Test Page',
		'hostname' => 'https://example.com',
		'sessions' => '80',
	),
);
$top_referrals  = array(
	array(
		'url'      => 'https://facebook.com/',
		'sessions' => '100980',
	),
	array(
		'url'      => 'https://youtube.com/',
		'sessions' => '9080',
	),
	array(
		'url'      => 'https://wordpress.org/',
		'sessions' => '9080',
	),
);
$more_pages     = "https://example.com";
$more_referrals = "https://example.com";

echo esc_html__( 'Hi there!', 'google-analytics-for-wordpress' ) . "\n\n";


echo esc_html__( 'Website Traffic Summary', 'google-analytics-for-wordpress' ) . "\n";
echo esc_html__( 'Let’s take a look at how your website traffic performed in the past month.', 'google-analytics-for-wordpress' ) . "\n\n";

echo esc_html__( 'January 01 - January 31, 2020', 'google-analytics-for-wordpress' ) . "\n";
echo esc_url( 'https://example.com' ) . "\n\n";

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo esc_html__( 'Total Visitors', 'google-analytics-for-wordpress' ) . '   -   ' . esc_html( number_format_i18n( '484000' ) ) . "\n";
echo esc_html__( 'Increase Visitors 13%', 'google-analytics-for-wordpress' ) . "\n\n";

echo esc_html__( 'Total Pageviews', 'google-analytics-for-wordpress' ) . '   -   ' . esc_html( number_format_i18n( '1800000' ) ) . "\n";
echo esc_html__( 'Decrease Pageviews 2%', 'google-analytics-for-wordpress' ) . "\n\n";

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo esc_html__( 'Top Pages', 'google-analytics-for-wordpress' ) . "\n\n";

$i = 0;
while ( $i <= 2 ) {
	echo esc_html( $i + 1 . ". " . $top_pages[ $i ]['title'] . " - " . $top_pages[ $i ]['url'] ) . "\n\n";
	$i ++;
}

echo "View More - " . esc_url( $more_pages ) . "\n\n";;

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo esc_html__( 'Top Referrals', 'google-analytics-for-wordpress' ) . "\n\n";

$i = 0;
while ( $i <= 2 ) {
	echo esc_html( $i + 1 . ". " . $top_referrals[ $i ]['url'] ) . "\n\n";
	$i ++;
}

echo "View More - " . esc_url( $more_referrals ) . "\n\n";

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo esc_html__( 'To make sure you keep getting these emails, please add support@monsterinsights.com to your address book or whitelist us. Want out of the loop? Unsubscribe.', 'google-analytics-for-wordpress' ) . "\n\n";
