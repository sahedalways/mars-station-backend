<x-mail.layout>
    {{-- Success Icon Header --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 72px; height: 72px; background: #dcfce7; border-radius: 50%; text-align: center; line-height: 72px;">
            <span style="display: inline-block; color: #16a34a; font-size: 36px; line-height: 72px; font-weight: 700;">✓</span>
        </div>
    </div>

    {{-- Title Section --}}
    <h1 style="margin: 0 0 8px; color: #111827; font-size: 26px; line-height: 32px; font-weight: 700; text-align: center;">
        Your Subscription Has <span style="color: #16a34a;">Started!</span>
    </h1>
    <p style="margin: 0 0 16px; color: #374151; font-size: 16px; line-height: 24px; text-align: center;">
        Thank you for choosing Mars Station.
    </p>
    <div style="width: 60px; height: 2px; background: #a78bfa; margin: 0 auto 32px;"></div>

    {{-- Greeting & Body --}}
    <p style="margin: 0 0 12px; color: #111827; font-size: 14px; line-height: 22px; font-weight: 600;">
        Hello {{ $subscription->client_name ?? 'Md Rony' }},
    </p>
    <p style="margin: 0 0 6px; color: #374151; font-size: 14px; line-height: 22px;">
        <strong>Great news!</strong> Your subscription for the selected service is now active.
    </p>
    <p style="margin: 0 0 6px; color: #374151; font-size: 14px; line-height: 22px;">
        You can manage your subscription, update payment method and view billing details anytime using the link below.
    </p>
    <p style="margin: 0 0 24px; color: #374151; font-size: 14px; line-height: 22px;">
        &nbsp;
    </p>

    {{-- Subscription Summary Box (Light Green) --}}
    <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
        <h3 style="margin: 0 0 16px; color: #16a34a; font-size: 14px; font-weight: 700;">
            Subscription Summary
        </h3>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Service Name</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $subscription->service_name ?? 'Website Maintenance' }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Start Date</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $subscription->start_date?->format('M d, Y') ?? 'May 16, 2025' }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Billing Cycle</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $subscription->billing_cycle ?? 'Monthly' }}
                    </p>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Subscription ID</p>
                    <p style="margin: 2px 0 0; color: #16a34a; font-size: 13px; font-weight: 600; word-break: break-all;">
                        {{ $subscription->subscription_id ?? 'sub_1R3j2hXk9l0q' }}
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Next Billing Date</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $subscription->next_billing_date?->format('M d, Y') ?? 'Jun 16, 2025' }}
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Amount</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $subscription->currency ?? '£' }}{{ number_format($subscription->amount ?? 55.99, 2) }} / {{ strtolower($subscription->billing_cycle ?? 'month') }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Info Box (Light Blue) --}}
    <div style="background: #eff6ff; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32" style="vertical-align: top;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: #dbeafe; border-radius: 50%; text-align: center; line-height: 24px; color: #2563eb; font-weight: 700; font-style: italic;">i</span>
                </td>
                <td style="vertical-align: top;">
                    <p style="margin: 0 0 4px; color: #2563eb; font-size: 14px; line-height: 20px; font-weight: 700;">
                        Your subscription is now active.
                    </p>
                    <p style="margin: 0; color: #374151; font-size: 13px; line-height: 20px;">
                        You will receive a confirmation invoice shortly.
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- CTA Button (Dark Purple) --}}
    <div style="text-align: center; margin-bottom: 12px;">
        <a href="{{ $manageUrl ?? $link->publicUrl() }}"
           style="display: inline-block; padding: 16px 48px; background: #2e1065; color: #ffffff; text-decoration: none; border-radius: 999px; font-size: 15px; font-weight: 600;">
            ⚙ Manage Subscription
        </a>
    </div>
    <p style="margin: 0 0 24px; color: #6b7280; font-size: 13px; line-height: 20px; text-align: center;">
        Use the link above to manage your subscription, update payment method, view invoices, or cancel anytime.
    </p>

    {{-- Fallback Link Box (Light Purple) --}}
    <div style="background: #f5f3ff; border-radius: 10px; padding: 16px; margin-bottom: 16px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32" style="vertical-align: top;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: #ede9fe; border-radius: 50%; text-align: center; line-height: 24px; color: #7c3aed; font-size: 14px;">🌐</span>
                </td>
                <td style="vertical-align: top;">
                    <p style="margin: 0 0 4px; color: #374151; font-size: 13px;">
                        If the button above doesn't work, copy and paste this link into your browser:
                    </p>
                    <a href="{{ $manageUrl ?? $link->publicUrl() }}" style="color: #7c3aed; font-size: 12px; word-break: break-all; text-decoration: underline;">
                        {{ $manageUrl ?? $link->publicUrl() }}
                    </a>
                </td>
            </tr>
        </table>
    </div>

    {{-- Need Help Box (Light Purple) --}}
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
                                    If you have any questions, our support team is here to help.
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
            Thank you for trusting Mars Station.
        </p>
        <p style="margin: 0; color: #111827; font-size: 14px; line-height: 20px; font-weight: 700;">
            We look forward to supporting your success!
        </p>
    </div>
</x-mail.layout>
