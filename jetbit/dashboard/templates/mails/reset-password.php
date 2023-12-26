<?php
/**
 * Reset password mail content.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/mail/reset-password.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */

if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td style="padding: 2rem; text-align: center; ">
			<h2 style="margin: 0;"><?php I18n::e( 'Your are registered!' ); ?></h2>
			<p style="color: #7e848b; max-width: 270px; margin: 10px auto 0;"><?php I18n::e( 'We have received a new registration request. Read the instructions.' ); ?></p>
		</td>
	</tr>
	<tr>
		<td>
			<p style="margin: 0; text-align: center; font-size: 10.35px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #7c8188; height: 7px;">
				<span style="background-color: #fff; display: inline-block; padding: 0 8px;"><?php I18n::e( 'What\'s next?' ); ?></span>
			</p>
		</td>
	</tr>
	<tr>
		<td>
			<hr style="border:none; height:1px; background-color: #e6e7e9;">
		</td>
	</tr>
	<tr>
		<td style="padding: 2rem;">
			<p>Hi [name].</p>
			<p>You recently requested to reset the password for your [customer portal] account. Click the button below to proceed:</p>
			<p>If you did not request a password reset, please ignore this email or reply to let us know. This password reset link is only valid for the next 30 minutes.</p>
		</td>
	</tr>
	<tr>
		<td valign="middle" style="font-size: 80%; text-align: center; padding: 2rem; color: #7c8188;">
			<p>Team, PO Box 16122, Collins Street West, <a href="index.html" target="_blank" style="text-decoration: none; color:#206bc4;">Victoria 8007, Australia</a></p>
		</td>
	</tr>
</table>
