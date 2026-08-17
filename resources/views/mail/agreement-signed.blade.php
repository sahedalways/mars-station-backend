<x-mail.layout>
    {{-- Success Icon Header --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 72px; height: 72px; background: #dcfce7; border-radius: 50%; text-align: center; line-height: 72px;">
            <span style="display: inline-block; color: #16a34a; font-size: 36px; line-height: 72px; font-weight: 700;">✓</span>
        </div>
    </div>

    {{-- Title Section --}}
    <h1 style="margin: 0 0 8px; color: #111827; font-size: 26px; line-height: 32px; font-weight: 700; text-align: center;">
        Your Agreement Has Been Signed!
    </h1>
    <p style="margin: 0 0 16px; color: #374151; font-size: 16px; line-height: 24px; text-align: center;">
        Thank you for your trust in Mars Station.
    </p>
    <div style="width: 60px; height: 2px; background: #a78bfa; margin: 0 auto 32px;"></div>

    {{-- Greeting --}}
    <p style="margin: 0 0 12px; color: #111827; font-size: 14px; line-height: 22px; font-weight: 600;">
        Hello {{ $agreement->client_name }},
    </p>
    <p style="margin: 0 0 6px; color: #374151; font-size: 14px; line-height: 22px;">
        We're happy to inform you that your agreement has been successfully signed.
    </p>
    <p style="margin: 0 0 24px; color: #374151; font-size: 14px; line-height: 22px;">
        A signed copy of the agreement is attached for your records.
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
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Signed On</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->signed_at?->format('M d, Y h:i A') ?? now()->format('M d, Y h:i A') }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Payment Type</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->payment_type ?? 'Milestone Payment' }}
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
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Signed By</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $agreement->client_name }}
                    </p>
                    <p style="margin: 0; color: #6b7280; font-size: 11px;">
                        ({{ $agreement->client_email }})
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Status</p>
                    <p style="margin: 4px 0 0;">
                        <span style="display: inline-block; padding: 3px 12px; background: #dcfce7; color: #16a34a; border-radius: 6px; font-size: 12px; font-weight: 600;">
                            Signed
                        </span>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Attachment Info Box --}}
    <div style="background: #f5f3ff; border-radius: 12px; padding: 16px; margin-bottom: 28px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="48" style="vertical-align: middle;">
                    <div style="width: 40px; height: 40px; background: #ede9fe; border-radius: 8px; text-align: center; line-height: 40px;">
                        <span style="color: #7c3aed; font-size: 18px; font-weight: 700;">📄</span>
                    </div>
                </td>
                <td style="vertical-align: middle; padding-left: 12px;">
                    <p style="margin: 0 0 4px; color: #7c3aed; font-size: 14px; font-weight: 700;">
                        Your signed agreement is attached with this email.
                    </p>
                    <p style="margin: 0; color: #6b7280; font-size: 12px; line-height: 18px;">
                        File: Agreement_{{ $agreement->agreement_number }}_Signed.pdf
                        @if(isset($fileSize))
                            ({{ $fileSize }})
                        @endif
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- CTA Button --}}
    <div style="text-align: center; margin-bottom: 12px;">
        <a href="{{ $link->publicUrl() }}"
           style="display: inline-block; padding: 16px 48px; background: #1f1235; color: #ffffff; text-decoration: none; border-radius: 999px; font-size: 15px; font-weight: 600;">
            👁 View Signed Agreement
        </a>
    </div>
    <p style="margin: 0 0 24px; color: #6b7280; font-size: 13px; line-height: 20px; text-align: center;">
        You can view, download and track your agreement details anytime.
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
                        If the button above doesn't work, copy and paste this link into your browser:
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

    {{-- Footer Thank You Message --}}
    <div style="text-align: center; padding: 8px 0;">
        <p style="margin: 0 0 4px; color: #6b7280; font-size: 13px; line-height: 20px;">
            Thank you once again for partnering with Mars Station.
        </p>
        <p style="margin: 0; color: #111827; font-size: 14px; line-height: 20px; font-weight: 700;">
            We look forward to working with you!
        </p>
    </div>
</x-mail.layout>
