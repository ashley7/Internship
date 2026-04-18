<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password — MedIntern</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f4f8;
            color: #1c2833;
            padding: 40px 20px;
        }
        .wrapper {
            max-width: 560px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #0d2137 0%, #1a5276 100%);
            border-radius: 16px 16px 0 0;
            padding: 32px 40px;
            text-align: center;
        }
        .header-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        .logo-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,.15);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }
        .header h1 {
            color: #fff;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: .3px;
        }
        .header p {
            color: rgba(255,255,255,.65);
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .body {
            background: #ffffff;
            padding: 40px;
            border-left: 1px solid #dde3ea;
            border-right: 1px solid #dde3ea;
        }
        .greeting {
            font-size: 1.05rem;
            font-weight: 600;
            color: #1a5276;
            margin-bottom: 16px;
        }
        .text {
            font-size: .9rem;
            line-height: 1.7;
            color: #4a5568;
            margin-bottom: 16px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #1a5276, #2e86c1);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 10px;
            font-weight: 700;
            font-size: .95rem;
            letter-spacing: .3px;
        }
        .divider {
            border: none;
            border-top: 1px solid #e8edf3;
            margin: 28px 0;
        }
        .link-fallback {
            background: #f8fafc;
            border: 1px solid #e8edf3;
            border-radius: 8px;
            padding: 14px 16px;
            word-break: break-all;
            font-size: .78rem;
            color: #6b7c93;
            line-height: 1.6;
        }
        .link-fallback a {
            color: #2e86c1;
        }
        .warning-box {
            background: #fef9e7;
            border: 1px solid #f9e79f;
            border-radius: 8px;
            padding: 14px 16px;
            font-size: .82rem;
            color: #7d6608;
            margin-bottom: 20px;
        }
        .footer {
            background: #f8fafc;
            border: 1px solid #dde3ea;
            border-top: none;
            border-radius: 0 0 16px 16px;
            padding: 20px 40px;
            text-align: center;
        }
        .footer p {
            font-size: .75rem;
            color: #a0aab4;
            line-height: 1.6;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Header -->
    <div class="header">
        <div class="header-logo">
            <div class="logo-icon">🏥</div>
        </div>
        <h1>MedIntern</h1>
        <p>Medical Internship Portal</p>
    </div>

    <!-- Body -->
    <div class="body">
        <div class="greeting">Hello, {{ $user->name }} 👋</div>

        <p class="text">
            We received a request to reset the password for your MedIntern account
            associated with <strong>{{ $user->email }}</strong>.
        </p>

        <p class="text">
            Click the button below to choose a new password. This link is valid for
            <strong>60 minutes</strong>.
        </p>

        <div class="btn-wrapper">
            <a href="{{ $resetUrl }}" class="btn">Reset My Password</a>
        </div>

        <div class="warning-box">
            ⚠️ If you did not request a password reset, please ignore this email.
            Your password will remain unchanged and no action is needed.
        </div>

        <hr class="divider">

        <p class="text" style="font-size:.82rem;color:#6b7c93;">
            If the button above doesn't work, copy and paste this link into your browser:
        </p>
        <div class="link-fallback">
            <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            This email was sent by <strong>MedIntern</strong> — Medical Internship Management System.<br>
            This is an automated message, please do not reply to this email.
        </p>
    </div>
</div>
</body>
</html>
