<x-mail.layout>
    {{-- Icon Header --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 72px; height: 72px; background: #f3e8ff; border-radius: 50%; text-align: center; line-height: 72px;">
            <img src="{{ asset('images/email/reminder-icon.png') }}" alt="Reminder" width="36" height="36" style="vertical-align: middle; margin-top: 18px;">
        </div>
    </div>

    {{-- Title Section --}}
    <h1 style="margin: 0 0 8px; color: #111827; font-size: 26px; line-height: 32px; font-weight: 700; text-align: center;">
        Friendly Reminder to Review
    </h1>
    <h2 style="margin: 0 0 16px; color: #374151; font-size: 18px; line-height: 24px; font-weight: 400; text-align: center;">
        Your Service Agreement is Waiting
    </h2>
    <div style="width: 60px; height: 2px; background: #e5e7eb; margin: 0 auto 32px;"></div>

    {{-- Greeting --}}
    <p style="margin: 0 0 8px; color: #111827; font-size: 14px; line-height: 22px; font-weight: 600;">
        Hello {{ $agreement->client_name }},
    </p>
    <p style="margin: 0 0 8px; color: #374151; font-size: 14px; line-height: 22px;">
        This is a gentle reminder that Mars Station is still awaiting your review of the service agreement previously sent to you.
    </p>
    <p style="margin: 0 0 8px; color: #374151; font-size: 14px; line-height: 22px;">
        Please take a moment to review the agreement and sign it at your earliest convenience.
    </p>
    <p style="margin: 0 0 24px; color: #374151; font-size: 14px; line-height: 22px;">
        This link is unique to you and should not be shared with others.
    </p>

    {{-- Pending Status Box --}}
    <div style="background: #f5f3ff; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
        <div style="display: flex; align-items: flex-start;">
            <div style="margin-right: 12px;">
                <span style="display: inline-block; width: 24px; height: 24px; background: #ede9fe; border-radius: 50%; text-align: center; line-height: 24px; color: #7c3aed; font-weight: 700;">!</span>
            </div>
            <div>
                <h3 style="margin: 0 0 8px; color: #7c3aed; font-size: 14px; font-weight: 600;">
                    Action Required
                </h3>
                <p style="margin: 0; color: #374151; font-size: 13px; line-height: 20px;">
                    Your agreement is still pending. Please review the terms carefully and sign to confirm your acceptance.
                </p>
            </div>
        </div>
    </div>

    {{-- Agreement Summary Box --}}
    <div style="background: #f5f3ff; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
        <h3 style="margin: 0 0 16px; color: #7c3aed; font-size: 14px; font-weight: 600;">
            Agreement Summary
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
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Valid Until</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->valid_until?->format('M d, Y') ?? 'N/A' }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Status</p>
                    <p style="margin: 2px 0 0;">
                        <span style="display: inline-block; padding: 2px 10px; background: #fef3c7; color: #d97706; border-radius: 10px; font-size: 11px; font-weight: 700;">
                            PENDING
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
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Payment Type</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->payment_type ?? 'Milestone Payment' }}
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Sent On</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->created_at->format('M d, Y') }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- CTA Button --}}
    <div style="text-align: center; margin-bottom: 12px;">
        <a href="{{ $link->publicUrl() }}"
           style="display: inline-block; padding: 16px 48px; background: #1f1235; color: #ffffff; text-decoration: none; border-radius: 999px; font-size: 15px; font-weight: 600;">
            📝 Review Agreement Now
        </a>
    </div>
    <p style="margin: 0 0 24px; color: #6b7280; font-size: 13px; line-height: 20px; text-align: center;">
        Click the button above to view the agreement, sign, and continue.
    </p>

    {{-- Fallback Link Box --}}
    <div style="background: #f9fafb; border-radius: 10px; padding: 16px; margin-bottom: 16px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32" style="vertical-align: top;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: #ede9fe; border-radius: 50%; text-align: center; line-height: 24px; color: #7c3aed;">🌐</span>
                </td>
                <td style="vertical-align: top;">
                    <p style="margin: 0 0 4px; color: #374151; font-size: 13px;">
                        If the button doesn't work, copy and paste this link into your browser:
                    </p>
                    <a href="{{ $link->publicUrl() }}" style="color: #7c3aed; font-size: 12px; word-break: break-all; text-decoration: underline;">
                        {{ $link->publicUrl() }}
                    </a>
                </td>
            </tr>
        </table>
    </div>

    {{-- Need Help Box --}}
    <div style="background: #f5f3ff; border-radius: 10px; padding: 16px;">
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
                                    If you have any questions, feel free to contact us. We're here to help.
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
</x-mail.layout>
