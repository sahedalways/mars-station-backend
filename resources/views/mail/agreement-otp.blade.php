<x-mail.layout>
    <x-slot:title>Your verification code</x-slot:title>

    {{-- Lock Icon Header --}}


    {{-- Title Section --}}
    <h1 style="margin: 0 0 8px; color: #111827; font-size: 26px; line-height: 32px; font-weight: 700; text-align: center;">
        Your <span style="color: #7c3aed;">Access Code</span>
    </h1>
    <p style="margin: 0 0 16px; color: #374151; font-size: 16px; line-height: 24px; text-align: center;">
        Use the code below to access your agreement.
    </p>
    <div style="width: 60px; height: 2px; background: #a78bfa; margin: 0 auto 32px;"></div>

    {{-- Greeting --}}
    <p style="margin: 0 0 12px; color: #111827; font-size: 14px; line-height: 22px; font-weight: 600;">
        Hello {{ $agreement->client_name }},
    </p>
    <p style="margin: 0 0 6px; color: #374151; font-size: 14px; line-height: 22px;">
        You requested access to agreement <strong>{{ $agreement->agreement_number }}</strong>.
    </p>
    <p style="margin: 0 0 24px; color: #374151; font-size: 14px; line-height: 22px;">
        Please use the verification code below to continue. This code is unique to you and should not be shared with others.
    </p>

    {{-- OTP Code Box --}}
    <div style="background: #f5f3ff; border-radius: 12px; padding: 28px 20px; margin-bottom: 20px; text-align: center;">
        <p style="margin: 0 0 14px; color: #7c3aed; font-size: 13px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">
            Verification Code
        </p>
        <div style="display: inline-block; padding: 14px 32px; background: #ffffff; border: 2px dashed #c4b5fd; border-radius: 12px;">
            <span style="display: inline-block; color: #4c1d95; font-size: 34px; font-weight: 700; letter-spacing: 10px; font-family: 'Courier New', monospace; line-height: 40px;">
                {{ $otp }}
            </span>
        </div>
        <p style="margin: 14px 0 0; color: #6b7280; font-size: 12px; line-height: 18px;">
            ⏱ This code expires in
            <strong style="color: #dc2626;">{{ config('mars.agreement_otp.expiry_minutes', 5) }} minutes</strong>.
        </p>
    </div>

    {{-- Request Details Box --}}
    <div style="background: #f5f3ff; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
        <h3 style="margin: 0 0 16px; color: #7c3aed; font-size: 14px; font-weight: 700;">
            Request Details
        </h3>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Agreement Title</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->title }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Requested On</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ now()->format('M d, Y h:i A') }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Valid For</p>
                    <p style="margin: 4px 0 0;">
                        <span style="display: inline-block; padding: 3px 12px; background: #ede9fe; color: #7c3aed; border-radius: 6px; font-size: 12px; font-weight: 600;">
                            {{ config('mars.agreement_otp.expiry_minutes', 5) }} Minutes
                        </span>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Agreement No.</p>
                    <p style="margin: 2px 0 0; color: #7c3aed; font-size: 13px; font-weight: 600;">
                        {{ $agreement->agreement_number }}
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Requested By</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->client_name }}
                    </p>
                    <p style="margin: 0; color: #6b7280; font-size: 11px;">
                        ({{ $agreement->client_email }})
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Security</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        One-Time Code
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Security Notice Info Box --}}
    <div style="background: #fffbeb; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32" style="vertical-align: top;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: #fef3c7; border-radius: 50%; text-align: center; line-height: 24px; color: #d97706; font-weight: 700;">!</span>
                </td>
                <td style="vertical-align: top;">
                    <h3 style="margin: 0 0 6px; color: #d97706; font-size: 14px; font-weight: 700;">
                        Keep This Code Private
                    </h3>
                    <p style="margin: 0 0 2px; color: #374151; font-size: 13px; line-height: 20px;">
                        Never share this code with anyone, including our support team.
                    </p>
                    <p style="margin: 0; color: #374151; font-size: 13px; line-height: 20px;">
                        If you did not request this code, you can safely ignore this email.
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Need Help Box --}}
    <div style="background: #f5f3ff; border-radius: 10px; padding: 16px; margin-bottom: 24px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="60%" style="vertical-align: top;">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="32" style="vertical-align: top;">
                                <span style="display: inline-block; width: 24px; height: 24px; background: #ede9fe; border-radius: 50%; text-align: center; line-height: 24px; color: #7c3aed;">🎧</span>
                            </td>
                            <td style="vertical-align: top;">
                                <p style="margin: 0; color: #111827; font-size: 13px; font-weight: 600;">Need Help?</p>
                                <p style="margin: 2px 0 0; color: #6b7280; font-size: 12px; line-height: 18px;">
                                    If you have any trouble accessing your agreement, we're here to help.
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="40%" style="vertical-align: top; text-align: right;">
                    <p style="margin: 0;">
                        <a href="mailto:support@marsstation.dev" style="color: #7c3aed; font-size: 12px; text-decoration: underline;">
                            ✉ support@marsstation.dev
                        </a>
                    </p>
                    <p style="margin: 4px 0 0;">
                        <a href="https://marsstation.dev" style="color: #7c3aed; font-size: 12px; text-decoration: underline;">
                            🌐 marsstation.dev
                        </a>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Footer Thank You Message --}}
    <div style="text-align: center; padding: 8px 0;">
        <p style="margin: 0 0 4px; color: #6b7280; font-size: 13px; line-height: 20px;">
            Thank you for your trust in Mars Station.
        </p>
        <p style="margin: 0; color: #111827; font-size: 14px; line-height: 20px; font-weight: 700;">
            Your security is our priority!
        </p>
    </div>
</x-mail.layout>
