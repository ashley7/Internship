Hello {{ $user->name }},

We received a request to reset the password for your MedIntern account ({{ $user->email }}).

To reset your password, paste the link below into your browser:

{{ $resetUrl }}

This link will expire in 60 minutes.

If you did not request a password reset, you can safely ignore this email. Your password will not change.

---
MedIntern — Medical Internship Management System
Please do not reply to this automated message.
