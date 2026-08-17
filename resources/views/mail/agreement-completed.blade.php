<x-mail.layout>
    {{-- Success Icon Header --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 72px; height: 72px; background: #dcfce7; border-radius: 50%; text-align: center; line-height: 72px;">
            <span style="display: inline-block; color: #16a34a; font-size: 36px; line-height: 72px; font-weight: 700;">✓</span>
        </div>
    </div>

    {{-- Title Section --}}
    <h1 style="margin: 0 0 8px; color: #111827; font-size: 26px; line-height: 32px; font-weight: 700; text-align: center;">
        Your Agreement is <span style="color: #16a34a;">Completed!</span>
    </h1>
    <p style="margin: 0 0 16px; color: #374151; font-size: 16px; line-height: 24px; text-align: center;">
        The agreement has been successfully completed.
    </p>
    <div style="width: 60px; height: 2px; background: #a78bfa; margin: 0 auto 32px;"></div>

    {{-- Greeting --}}
    <p style="margin: 0 0 12px; color: #111827; font-size: 14px; line-height: 22px; font-weight: 600;">
        Hello {{ $agreement->client_name }},
    </p>
    <p style="margin: 0 0 6px; color: #374151; font-size: 14px; line-height: 22px;">
        We are pleased to inform you that the agreement below has been completed.
    </p>
    <p style="margin: 0 0 6px; color: #374151; font-size: 14px; line-height: 22px;">
        Thank you for your trust and for choosing Mars Station.
    </p>
    <p style="margin: 0 0 24px; color: #374151; font-size: 14px; line-height: 22px;">
        We appreciate the opportunity to work with you.
    </p>

    {{-- Agreement Summary Box --}}
    <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
        <h3 style="margin: 0 0 16px; color: #16a34a; font-size: 14px; font-weight: 700;">
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
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Completion Date</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->completed_at?->format('M d, Y') ?? now()->format('M d, Y') }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Status</p>
                    <p style="margin: 2px 0 0; color: #16a34a; font-size: 13px; font-weight: 600;">
                        Completed
                    </p>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Agreement No.</p>
                    <p style="margin: 2px 0 0; color: #16a34a; font-size: 13px; font-weight: 600;">
                        {{ $agreement->agreement_number }}
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Client Name</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->client_name }}
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Final Amount</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->currency_symbol ?? '£' }}{{ number_format($agreement->total_amount ?? 0, 2) }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Congratulations Box --}}
    <div style="background: #eff6ff; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32" style="vertical-align: top;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: #dbeafe; border-radius: 50%; text-align: center; line-height: 24px; color: #2563eb; font-weight: 700; font-style: italic;">i</span>
                </td>
                <td style="vertical-align: top;">
                    <h3 style="margin: 0 0 6px; color: #2563eb; font-size: 14px; font-weight: 700;">
                        Congratulations!
                    </h3>
                    <p style="margin: 0 0 2px; color: #374151; font-size: 13px; line-height: 20px;">
                        This agreement has been successfully completed.
                    </p>
                    <p style="margin: 0; color: #374151; font-size: 13px; line-height: 20px;">
                        You can download a copy or view the final agreement details anytime.
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- CTA Button --}}
    <div style="text-align: center; margin-bottom: 12px;">
        <a href="{{ $link->publicUrl() }}"
           style="display: inline-block; padding: 16px 48px; background: #4c1d95; color: #ffffff; text-decoration: none; border-radius: 999px; font-size: 15px; font-weight: 600;">
            📄 View Completed Agreement
        </a>
    </div>
    <p style="margin: 0 0 24px; color: #6b7280; font-size: 13px; line-height: 20px; text-align: center;">
        Use the link above to view or download the completed agreement.
    </p>

    {{-- Fallback Link Box --}}
    <div style="background: #f5f3ff; border-radius: 10px; padding: 16px; margin-bottom: 16px;">
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
                                    If you have any questions, we're here to help.
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
            Thank you for working with Mars Station.
        </p>
        <p style="margin: 0; color: #111827; font-size: 14px; line-height: 20px; font-weight: 700;">
            We look forward to future opportunities!
        </p>
    </div>
</x-mail.layout>
