<?php
/**
 * Email Header
 *
 * @since 8.19.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$text_direction = is_rtl() ? 'rtl' : 'ltr';

?>
<!doctype html>
<html dir="<?php echo esc_attr( $text_direction ); ?>" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
	  xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<!--[if gte mso 15]>
	<xml>
		<o:OfficeDocumentSettings>
			<o:AllowPNG/>
			<o:PixelsPerInch>96</o:PixelsPerInch>
		</o:OfficeDocumentSettings>
	</xml>
	<![endif]-->
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo get_bloginfo( 'name' ); // phpcs:ignore ?></title>
	<style type="text/css">
		p {
			margin: 10px 0;
			padding: 0;
		}

		table {
			border-collapse: collapse;
			mso-table-lspace: 0pt;
			mso-table-rspace: 0pt;
		}

		h1, h2, h3, h4, h5, h6 {
			display: block;
			margin: 0;
			padding: 0;
		}

		img, a img {
			border: 0;
			height: auto;
			outline: none;
			text-decoration: none;
		}

		body, #bodyTable, #bodyCell {
			height: 100%;
			margin: 0;
			padding: 0;
			width: 100%;
		}

		#outlook a {
			padding: 0;
		}

		img {
			-ms-interpolation-mode: bicubic;
		}

		.ReadMsgBody {
			width: 100%;
		}

		.ExternalClass {
			width: 100%;
		}

		p, a, li, td, blockquote {
			mso-line-height-rule: exactly;
		}

		a[href^=tel], a[href^=sms] {
			color: inherit;
			cursor: default;
			text-decoration: none;
		}

		p, a, li, td, body, table, blockquote {
			-ms-text-size-adjust: 100%;
			-webkit-text-size-adjust: 100%;
		}

		.ExternalClass, .ExternalClass p, .ExternalClass td, .ExternalClass div, .ExternalClass span, .ExternalClass font {
			line-height: 100%;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: none !important;
			font-size: inherit !important;
			font-family: inherit !important;
			font-weight: inherit !important;
			line-height: inherit !important;
		}

		#bodyCell {
			padding: 50px 50px;
		}

		.templateContainer {
			max-width: 450px !important;
			border: 0;
		}

		a.mcnButton {
			display: block;
		}

		.mcnTextContent {
			word-break: break-word;
		}

		.mcnTextContent img {
			height: auto !important;
		}

		.mcnTextIncrease {
			color: #5CC0A5 !important;
		}

		.mcnTextDecrease {
			color: #EB5757 !important;
		}

		.mcnDividerBlock {
			table-layout: fixed !important;
		}

		/***** Make theme edits below if needed *****/
		/* Page - Background Style */
		body, #bodyTable {
			background-color: #F6F7F8;
		}

		/* Page - Heading 1 */
		h1 {
			color: #202020;
			font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
			font-size: 26px;
			font-style: normal;
			font-weight: bold;
			line-height: 125%;
			letter-spacing: normal;
		}

		/* Page - Heading 2 */
		h2 {
			color: #202020;
			font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
			font-size: 22px;
			font-style: normal;
			font-weight: bold;
			line-height: 125%;
			letter-spacing: normal;
		}

		/* Page - Heading 3 */
		h3 {
			color: #202020;
			font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
			font-size: 20px;
			font-style: normal;
			font-weight: bold;
			line-height: 125%;
			letter-spacing: normal;
		}

		/* Page - Heading 4 */
		h4 {
			color: #202020;
			font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
			font-size: 18px;
			font-style: normal;
			font-weight: bold;
			line-height: 125%;
			letter-spacing: normal;
		}

		/* Header - Header Style */
		#templateHeader {
			border-top: 0;
			border-bottom: 0;
			padding-top: 0;
			padding-bottom: 20px;
			text-align: left;
		}

		/* Body - Body Style */
		#templateBody {
			background-color: #FFFFFF;
			border-top: 0;
			border: 1px solid #c1c1c1;
			padding-top: 0;
			padding-bottom: 0px;
		}

		/* Body -Body Text */
		#templateBody .mcnTextContent,
		#templateBody .mcnTextContent p {
			color: #555555;
			font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
			font-size: 14px;
			line-height: 150%;
		}

		/* Body - Body Link */
		#templateBody .mcnTextContent a,
		#templateBody .mcnTextContent p a {
			color: #4B9BF0;
			font-weight: normal;
			text-decoration: none;
		}

		/* Footer - Footer Style */
		#templateFooter {
			background-color: #F6F7F8;
			border-top: 0;
			border-bottom: 0;
			padding-top: 0;
			padding-bottom: 0;
		}

		/* Footer - Footer Text */
		#templateFooter .mcnTextContent,
		#templateFooter .mcnTextContent p {
			color: #cccccc;
			font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
			font-size: 12px;
			line-height: 20px;
			text-align: center;
		}

		/* Footer - Footer Link */
		#templateFooter .mcnTextContent a,
		#templateFooter .mcnTextContent p a {
			color: #4B9BF0;
			font-weight: normal;
			text-decoration: underline;
		}

		@media only screen and (min-width: 768px) {
			.templateContainer {
				width: 450px !important;
			}
		}

		@media only screen and (max-width: 480px) {
			body, table, td, p, a, li, blockquote {
				-webkit-text-size-adjust: none !important;
			}
		}

		@media only screen and (max-width: 480px) {
			body {
				width: 100% !important;
				min-width: 100% !important;
			}
		}

		@media only screen and (max-width: 680px) {
			#bodyCell {
				padding: 20px 20px !important;
			}
		}

		@media only screen and (max-width: 480px) {
			.mcnTextContentContainer {
				max-width: 100% !important;
				width: 100% !important;
			}
		}
	</style>
</head>
<body
	style="height: 100%;margin: 0;padding: 0;width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #F6F7F8;">
<!-- Don't forget to run final template through http://templates.mailchimp.com/resources/inline-css/ -->
<center>
	<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable"
		   style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;height: 100%;margin: 0;padding: 0;width: 100%;background-color: #F6F7F8;">
		<tr>
			<td align="center" valign="top" id="bodyCell"
				style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;height: 100%;margin: 0;padding: 50px 50px;width: 100%;">
				<!-- BEGIN TEMPLATE // -->
				<!--[if gte mso 9]>
				<table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
					<tr>
						<td align="center" valign="top" width="600" style="width:600px;">
				<![endif]-->
				<table border="0" cellpadding="0" cellspacing="0" width="450" class="templateContainer"
					   style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;border: 0;max-width: 450px !important;">
					<?php
					if ( ! empty( $header_image ) ) {
						echo '<tr><td valign="top" align="center" id="templateHeader" style="padding-top:25px;padding-right:25px;padding-bottom:25px;padding-left:25px;text-align:left;background:#ffffff;border-radius:5px 5px 0 0;">';
						echo '<a href="' . esc_url( get_site_url() ) . '">';
						if ( ! empty( $header_image['2x'] ) ) {
							echo '<img style="width:200px; height:auto;"  src="' . esc_url( $header_image['2x'] ) . '" alt="' . esc_attr__( 'Monthly Traffic Summary', 'google-analytics-for-wordpress' ) . '" />';
						} else {
							echo '<img style="width:200px; height:auto;"  src="' . esc_url( $header_image['url'] ) . '" alt="' . esc_attr__( 'Monthly Traffic Summary', 'google-analytics-for-wordpress' ) . '" />';
						}
						echo '</a>';
						echo '</td></tr>';
					}
					?>
					<tr>
						<td valign="top" id="templateBody"
							style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;border-top: 0;border: 0;padding-top: 0;padding-bottom: 0px; background: #FFFFFF; border-radius:0 0 5px 5px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock"
								   style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
								<tbody class="mcnTextBlockOuter">
