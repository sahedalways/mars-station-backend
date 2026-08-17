<x-mail.layout>
    {{-- Alert Icon Header --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 72px; height: 72px; background: #fee2e2; border-radius: 50%; text-align: center; line-height: 72px;">
            <span style="display: inline-block; color: #dc2626; font-size: 32px; line-height: 72px; font-weight: 700;">✕</span>
        </div>
    </div>

    {{-- Title Section --}}
    <h1 style="margin: 0 0 8px; color: #111827; font-size: 26px; line-height: 32px; font-weight: 700; text-align: center;">
        Your Subscription Has Been <span style="color: #dc2626;">Cancelled</span>
    </h1>
    <p style="margin: 0 0 16px; color: #374151; font-size: 16px; line-height: 24px; text-align: center;">
        Your subscription has been successfully cancelled.
    </p>
    <div style="width: 60px; height: 2px; background: #e5e7eb; margin: 0 auto 32px;"></div>

    {{-- Greeting & Body --}}
    <p style="margin: 0 0 12px; color: #111827; font-size: 14px; line-height: 22px; font-weight: 600;">
        Hello {{ $subscription->client_name ?? $agreement->client_name ?? 'Md Rony' }},
    </p>
    <p style="margin: 0 0 6px; color: #374151; font-size: 14px; line-height: 22px;">
        Your subscription for the selected service has been cancelled as requested.
    </p>
    <p style="margin: 0 0 6px; color: #374151; font-size: 14px; line-height: 22px;">
        You can continue to enjoy the service until the end of your current billing period.
    </p>
    <p style="margin: 0 0 24px; color: #374151; font-size: 14px; line-height: 22px;">
        We're sorry to see you go and hope to serve you again in the future.
    </p>

    {{-- Subscription Summary Box (Red Theme) --}}
    <div style="background: #fef2f2; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
        <h3 style="margin: 0 0 16px; color: #dc2626; font-size: 14px; font-weight: 700;">
            Subscription Summary
        </h3>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Service Name</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $subscription->service_name ?? $subscription->plan_name ?? 'Website Maintenance' }}
                    </p>
                </td>
                <td width="33%" style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Cancellation Date</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $subscription->cancelled_at?->format('M d, Y') ?? 'May 16, 2025' }}
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
                    <p style="margin: 2px 0 0; color: #dc2626; font-size: 13px; font-weight: 600; word-break: break-all;">
                        {{ $subscription->subscription_id ?? $subscription->id ?? 'sub_1R3j2hXk9l0q' }}
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Access Until</p>
                    <p style="margin: 2px 0 0; color: #111827; font-size: 13px; font-weight: 600;">
                        {{ $subscription->access_until?->format('M d, Y') ?? 'Jun 16, 2025' }}
                    </p>
                </td>
                <td style="padding: 8px 0; vertical-align: top;">
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">Status</p>
                    <p style="margin: 4px 0 0;">
                        <span style="display: inline-block; padding: 3px 12px; background: #fee2e2; color: #dc2626; border-radius: 6px; font-size: 12px; font-weight: 600;">
                            Cancelled
                        </span>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- What Happens Next Info Box (Blue/Purple) --}}
    <div style="background: #eff6ff; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32" style="vertical-align: top;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: #dbeafe; border-radius: 50%; text-align: center; line-height: 24px; color: #2563eb; font-weight: 700; font-style: italic;">i</span>
                </td>
                <td style="vertical-align: top;">
                    <h3 style="margin: 0 0 6px; color: #4f46e5; font-size: 14px; font-weight: 700;">
                        What happens next?
                    </h3>
                    <p style="margin: 0 0 2px; color: #374151; font-size: 13px; line-height: 20px;">
                        You will continue to have access to the service until <strong>{{ $subscription->access_until?->format('M d, Y') ?? 'Jun 16, 2025' }}</strong>.
                    </p>
                    <p style="margin: 0; color: #374151; font-size: 13px; line-height: 20px;">
                        After this date, your subscription will no longer renew.
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
        Use the link above to review your subscription, reactivate, or explore other plans.
    </p>

    {{-- Fallback Link Box (Purple Theme) --}}
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
            Thank you for being a valued client of Mars Station.
        </p>
        <p style="margin: 0; color: #111827; font-size: 14px; line-height: 20px; font-weight: 700;">
            We appreciate your business!
        </p>
    </div>
</x-mail.layout>
