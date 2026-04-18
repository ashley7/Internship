<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="margin:0;padding:0;background:#f0f4f8;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f0f4f8;padding:40px 20px;">
    <tr>
        <td align="center">
            <table width="560" cellpadding="0" cellspacing="0" border="0" style="max-width:560px;width:100%;">

                <!-- Header -->
                <tr>
                    <td style="background:#1a5276;border-radius:12px 12px 0 0;padding:32px 40px;text-align:center;">
                        <p style="margin:0 0 4px 0;font-size:11px;color:rgba(255,255,255,0.6);text-transform:uppercase;letter-spacing:2px;">Medical Internship Portal</p>
                        <h1 style="margin:0;font-size:22px;font-weight:700;color:#ffffff;">MedIntern</h1>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="background:#ffffff;padding:40px;border-left:1px solid #dde3ea;border-right:1px solid #dde3ea;">

                        <p style="margin:0 0 20px 0;font-size:18px;font-weight:700;color:#1a5276;">
                            Hello, {{ $user->name }}
                        </p>

                        <p style="margin:0 0 16px 0;font-size:15px;line-height:1.7;color:#4a5568;">
                            We received a request to reset the password for your MedIntern account
                            linked to <strong>{{ $user->email }}</strong>.
                        </p>

                        <p style="margin:0 0 28px 0;font-size:15px;line-height:1.7;color:#4a5568;">
                            Click the button below to set a new password. This link will expire in
                            <strong>60 minutes</strong>.
                        </p>

                        <!-- CTA Button — table-based for email client compatibility -->
                        <table cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 28px auto;">
                            <tr>
                                <td align="center" bgcolor="#1a5276" style="border-radius:8px;">
                                    <a href="{{ $resetUrl }}"
                                       target="_blank"
                                       style="display:inline-block;padding:14px 40px;font-size:15px;font-weight:700;color:#ffffff;text-decoration:none;border-radius:8px;mso-padding-alt:14px 40px;">
                                        Reset My Password
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <!-- Warning box -->
                        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:28px;">
                            <tr>
                                <td style="background:#fef9e7;border:1px solid #f9e79f;border-radius:8px;padding:14px 16px;">
                                    <p style="margin:0;font-size:13px;color:#7d6608;line-height:1.6;">
                                        If you did not request a password reset, you can safely ignore this email.
                                        Your password will not change.
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <!-- Divider -->
                        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:20px;">
                            <tr><td style="border-top:1px solid #e8edf3;font-size:0;line-height:0;">&nbsp;</td></tr>
                        </table>

                        <p style="margin:0 0 8px 0;font-size:12px;color:#6b7c93;">
                            If the button above does not work, copy and paste the link below into your browser:
                        </p>
                        <p style="margin:0;font-size:12px;color:#2e86c1;word-break:break-all;line-height:1.6;">
                            {{ $resetUrl }}
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#f8fafc;border:1px solid #dde3ea;border-top:none;border-radius:0 0 12px 12px;padding:20px 40px;text-align:center;">
                        <p style="margin:0;font-size:12px;color:#a0aab4;line-height:1.6;">
                            This email was sent by <strong>MedIntern</strong> &mdash; Medical Internship Management System.<br>
                            Please do not reply to this automated message.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
