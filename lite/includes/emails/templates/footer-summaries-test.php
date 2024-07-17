<?php
/**
 * Email Footer.
 *
 * @since 8.19.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<tr>
	<td valign="top" id="templateFooter"
		style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #F6F7F8;border-top: 0;border-bottom: 0;padding-top: 0;padding-bottom: 0;">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock"
			   style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
			<tbody class="mcnTextBlockOuter">
			<tr>
				<td valign="top" class="mcnTextBlockInner"
					style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
					<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
						   style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"
						   class="mcnTextContentContainer">
						<tbody>
						<tr>
							<td valign="top" class="mcnTextContent"
								style="padding-top: 30px;padding-right: 40px;padding-bottom: 0;padding-left: 40px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #828282;font-family: Helvetica;font-size: 12px;line-height: 20px;text-align: left;">
								<!-- Footer content -->
								<?php
								/* translators: Placeholders add links to form addresses and settings page. */
								$footer = sprintf( esc_html__( 'To make sure you keep getting these emails, please add %1$s to your address book or whitelist us. Want out of the loop? %2$s', 'google-analytics-for-wordpress' ), '<a href="mailto:' . $from_address . '" style="color:#4B9BF0;text-decoration:none;">' . $from_address . '</a>', '<a href="' . $settings_tab_url . '" target="_blank" style="color:#4B9BF0;text-decoration:none;">Unsubscribe.</a>' );
								echo apply_filters( 'mi_email_summaries_footer_text', $footer ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
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

</tbody>
</table>
</td>
</tr>
</table>
<!--[if gte mso 9]>
</td>
</tr>
</table>
<![endif]-->
<!-- // END TEMPLATE -->
</td>
</tr>
</table>
</center>
</body>
</html>
